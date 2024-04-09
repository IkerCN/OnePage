import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useProductos() {
    const productos = ref({})
    const producto = ref({
        title: '',
        content: '',
        categoria_id: ''
    })
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getProductos = async (
        page = 1,
        search_categoria = '',
        search_id = '',
        search_title = '',
        search_content = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/productos?page=' + page +
            '&search_categoria=' + search_categoria +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_content=' + search_content +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                productos.value = response.data;
            })
    }

    const getProducto = async (id) => {
        axios.get('/api/productos/' + id)
            .then(response => {
                producto.value = response.data.data;
            })
    }

    const storeProducto = async (producto) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedProducto = new FormData()
        for (let item in producto) {
            if (producto.hasOwnProperty(item)) {
                serializedProducto.append(item, producto[item])
            }
        }

        axios.post('/api/productos', serializedProducto, {
            headers: {
                "content-type": "multipart/form-data"
            }
        })
            .then(response => {
                router.push({ nombre: 'productos.index' })
                swal({
                    icon: 'success',
                    title: 'producto saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const updateProducto = async (producto) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        axios.post('/api/productos/update/' + producto.id, producto, {
            headers: {
                "content-type": "multipart/form-data"
            }
        })
        .then(response => {
            router.push({ nombre: 'productos.index' })
            console.log(response);
            swal({
                icon: 'success',
                title: 'producto updated successfully'
            })
        })
        .catch(error => {
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors
            }
        })
        .finally(() => isLoading.value = false)
    }

    const deleteProducto = async (id) => {
        swal({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this action!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
            .then(result => {
                if (result.isConfirmed) {
                    axios.delete('/api/productos/' + id)
                        .then(response => {
                            getProductos()
                            router.push({ nombre: 'productos.index' })
                            swal({
                                icon: 'success',
                                title: 'producto deleted successfully'
                            })
                        })
                        .catch(error => {
                            swal({
                                icon: 'error',
                                title: 'Something went wrong'
                            })
                        })
                }
            })

    }

    return {
        productos,
        producto,
        getProductos,
        getProducto,
        storeProducto,
        updateProducto,
        deleteProducto,
        validationErrors,
        isLoading,
        router
    }
}
