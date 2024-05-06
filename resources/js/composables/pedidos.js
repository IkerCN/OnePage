import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function usePedidos() {
    const pedidos = ref({})
    const pedido = ref({
        usuario_id: '',
        precioFinal: ''
    })
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getPedidos = async (
        page = 1,
        search_id = '',
        search_usuario_id = '',
        search_precioFinal = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/pedidos?page=' + page +
            '&search_id=' + search_id +
            '&search_usuario_id=' + search_usuario_id +
            '&search_precioFinal=' + search_precioFinal +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                pedidos.value = response.data;
            })
    }

    const getPedido = async (id) => {
        axios.get('/api/pedidos/' + id)
            .then(response => {
                pedido.value = response.data.data;
            })
    }

    const storePedido = async (pedido) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedPedido = new FormData()
        for (let item in pedido) {
            if (pedido.hasOwnProperty(item)) {
                serializedPedido.append(item, pedido[item])
            }
        }

        axios.post('/api/pedidos', serializedPedido, {
            headers: {
                "precioFinal-type": "multipart/form-data"
            }
        })
            .then(response => {
                router.push({ nombre: 'pedidos.index' })
                swal({
                    icon: 'success',
                    title: 'pedido saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const updatePedido = async (pedido) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        axios.post('/api/pedidos/update/' + pedido.id, pedido, {
            headers: {
                "precioFinal-type": "multipart/form-data"
            }
        })
        .then(response => {
            router.push({ nombre: 'pedidos.index' })
            console.log(response);
            swal({
                icon: 'success',
                title: 'pedido updated successfully'
            })
        })
        .catch(error => {
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors
            }
        })
        .finally(() => isLoading.value = false)
    }

    const deletePedido = async (id) => {
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
                    axios.delete('/api/pedidos/' + id)
                        .then(response => {
                            getPedidos()
                            router.push({ nombre: 'pedidos.index' })
                            swal({
                                icon: 'success',
                                title: 'pedido deleted successfully'
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
        pedidos,
        pedido,
        getPedidos,
        getPedido,
        storePedido,
        updatePedido,
        deletePedido,
        validationErrors,
        isLoading,
        router
    }
}
