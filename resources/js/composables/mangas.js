import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useMangas() {
    const mangas = ref({})
    const manga = ref({
        numero: '',
        titulo: '',
        descripcion:'',
        categoria_id: '',
        pagina: ''
    })
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getMangas = async (
        page = 1,
        search_categoria = '',
        search_id = '',
        search_titulo = '',
        search_descripcion = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/mangas?page=' + page +
            '&search_categoria=' + search_categoria +
            '&search_id=' + search_id +
            '&search_titulo=' + search_titulo +
            '&search_descripcion=' + search_descripcion +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                console.log(response.data);
                mangas.value = response.data;
            })
    }

    const getManga = async (id) => {
        axios.get('/api/mangas/' + id)
            .then(response => {
                manga.value = response.data;
                console.log(manga.value);
            })
    }

    const storeManga = async (manga) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedManga = new FormData()
        for (let item in manga) {
            if (manga.hasOwnProperty(item)) {
                serializedManga.append(item, manga[item])
            }
        }

        axios.post('/api/mangas', serializedManga,{
            headers: {
                "content-type": "multipart/form-data"
            }
        })
            .then(response => {
                router.push({name: 'mangas.index'})
                swal({
                    icon: 'success',
                    title: 'manga saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const updateManga = async (manga) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}
        console.log(manga);
        axios.put('/api/mangas/' + manga.id, manga)
            .then(response => {
                router.push({name: 'mangas.index'})
                swal({
                    icon: 'success',
                    title: 'manga updated successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deleteManga = async (id) => {
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
                    axios.delete('/api/mangas/' + id)
                        .then(response => {
                            getMangas()
                            router.push({name: 'manga.index'})
                            swal({
                                icon: 'success',
                                title: 'manga deleted successfully'
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
        mangas,
        manga,
        getMangas,
        getManga,
        storeManga,
        updateManga,
        deleteManga,
        validationErrors,
        isLoading
    }
}
