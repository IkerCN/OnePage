import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useCapitulos() {
    const capitulos = ref({})
    const capitulo = ref({
        numero: '',
        titulo: '',
        descripcion:'',
        categoria_id: '',
        thumbnail: ''
    })
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getCapitulos = async (
        page = 1,
        search_categoria = '',
        search_id = '',
        search_titulo = '',
        search_descripcion = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/capitulos?page=' + page +
            '&search_categoria=' + search_categoria +
            '&search_id=' + search_id +
            '&search_titulo=' + search_titulo +
            '&search_descripcion=' + search_descripcion +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                console.log(response.data);
                capitulos.value = response.data;
            })
    }

    const getCapitulo = async (id) => {
        axios.get('/api/capitulos/' + id)
            .then(response => {
                capitulo.value = response.data.data;
            })
    }

    const storeCapitulo = async (capitulo) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedCapitulo = new FormData()
        for (let item in capitulo) {
            if (capitulo.hasOwnProperty(item)) {
                serializedCapitulo.append(item, capitulo[item])
            }
        }

        axios.post('/api/capitulos', serializedCapitulo,{
            headers: {
                "content-type": "multipart/form-data"
            }
        })
            .then(response => {
                router.push({name: 'capitulos.index'})
                swal({
                    icon: 'success',
                    title: 'capitulo saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const updateCapitulo = async (capitulo) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}
        console.log(capitulo);
        axios.put('/api/capitulos/' + capitulo.id, capitulo)
            .then(response => {
                router.push({name: 'capitulos.index'})
                swal({
                    icon: 'success',
                    title: 'capitulo updated successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deleteCapitulo = async (id) => {
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
                    axios.delete('/api/capitulos/' + id)
                        .then(response => {
                            getCapitulos()
                            router.push({name: 'capitulos.index'})
                            swal({
                                icon: 'success',
                                title: 'capitulo deleted successfully'
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
        capitulos,
        capitulo,
        getCapitulos,
        getCapitulo,
        storeCapitulo,
        updateCapitulo,
        deleteCapitulo,
        validationErrors,
        isLoading
    }
}
