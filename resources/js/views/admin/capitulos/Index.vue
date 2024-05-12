<template>
    <div class="row justify-content-center my-2">
        <div class="col-md-12">
            <div class="card border-0">
                <div class="card-header bg-transparent">
                    <h5 class="float-start">Capitulos</h5>
                    <router-link :to="{ name: 'capitulos.create' }" class="btn btn-primary btn-sm float-end">
                        Create Capitulo
                    </router-link>
                </div>
                <div class="card-body shadow-sm">
                    <div class="mb-4">
                        <input v-model="search_global" type="text" placeholder="Search..."
                               class="form-control w-25">
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <input v-model="search_id" type="text"
                                           class="inline-block mt-1 form-control"
                                           placeholder="Filter by ID">
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <input v-model="search_titulo" type="text"
                                           class="inline-block mt-1 form-control"
                                           placeholder="Filter by titulo">
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <v-select multiple v-model="search_categoria" :options="categoriaList"
                                              :reduce="categoria => categoria.id" label="nombre" class="form-control w-100"/>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <input v-model="search_descripcion" type="text"
                                           class="inline-block mt-1 form-control"
                                           placeholder="Filter by descripcion">
                                </th>
                                <th class="px-6 py-3 text-start"></th>
                                <th class="px-6 py-3 text-start"></th>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 text-start">
                                    <div class="flex flex-row"
                                         @click="updateOrdering('id')">
                                        <div class="font-medium text-uppercase"
                                             :class="{ 'font-bold text-blue-600': orderColumn === 'id' }">
                                            ID
                                        </div>
                                        <div class="select-none">
                                <span :class="{
                                  'text-blue-600': orderDirection === 'asc' && orderColumn === 'id',
                                  'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'id',
                                }">&uarr;</span>
                                            <span :class="{
                                  'text-blue-600': orderDirection === 'desc' && orderColumn === 'id',
                                  'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'id',
                                }">&darr;</span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex flex-row"
                                         @click="updateOrdering('titulo')">
                                        <div class="font-medium text-uppercase"
                                             :class="{ 'font-bold text-blue-600': orderColumn === 'titulo' }">
                                            Titulo
                                        </div>
                                        <div class="select-none">
                                <span :class="{
                                  'text-blue-600': orderDirection === 'asc' && orderColumn === 'titulo',
                                  'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'titulo',
                                }">&uarr;</span>
                                            <span :class="{
                                  'text-blue-600': orderDirection === 'desc' && orderColumn === 'titulo',
                                  'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'titulo',
                                }">&darr;</span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex flex-row">
                                        <div class="font-medium text-uppercase">
                                            Thumbnail
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Categoria</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">descripcion</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <div class="flex flex-row items-center justify-between cursor-pointer"
                                         @click="updateOrdering('created_at')">
                                        <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                             :class="{ 'font-bold text-blue-600': orderColumn === 'created_at' }">
                                            Created at
                                        </div>
                                        <div class="select-none">
                                <span :class="{
                                  'text-blue-600': orderDirection === 'asc' && orderColumn === 'created_at',
                                  'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'created_at',
                                }">&uarr;</span>
                                            <span :class="{
                                  'text-blue-600': orderDirection === 'desc' && orderColumn === 'created_at',
                                  'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'created_at',
                                }">&darr;</span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="capitulo in capitulos.data" :key="capitulo.id">
                                <td class="px-6 py-4 text-sm" width="20">
                                    {{ capitulo.id }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ capitulo.titulo }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <video height="70">
                                        <source :src="capitulo.original_image" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ capitulo.categoria_id }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div v-html="capitulo.descripcion.slice(0, 100) + '...'"></div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ capitulo.created_at }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <router-link :to="{ name: 'capitulos.edit', params: { id: capitulo.id } }" class="badge bg-primary">
                                        Edit
                                    </router-link>
                                    <a href="#" @click.prevent="deleteCapitulo(capitulo.id)"
                                       class="ms-2 badge bg-danger">Delete</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <Pagination :data="capitulos" :limit="3"
                                @pagination-change-page="page => getCapitulos(page, search_categoria, search_id, search_titulo, search_descripcion, search_global, orderColumn, orderDirection)"
                                class="mt-4"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {ref, onMounted, watch} from "vue";
    import useCapitulos from "@/composables/capitulos";
    import useCategorias from "@/composables/categorias";
    import {useAbility} from '@casl/vue'

    const search_categoria = ref('')
    const search_id = ref('')
    const search_titulo = ref('')
    const search_descripcion = ref('')
    const search_global = ref('')
    const orderColumn = ref('created_at')
    const orderDirection = ref('desc')
    const {capitulos, getCapitulos, deleteCapitulo} = useCapitulos()
    const {categoriaList, getCategoriaList} = useCategorias()
    const {can} = useAbility();
    onMounted(() => {
        getCapitulos()
        getCategoriaList()
    })
    const updateOrdering = (column) => {
        orderColumn.value = column;
        orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';
        getCapitulos(
            1,
            search_categoria.value,
            search_id.value,
            search_titulo.value,
            search_descripcion.value,
            search_global.value,
            orderColumn.value,
            orderDirection.value
        );
    }
    watch(search_categoria, (current, previous) => {
        getCapitulos(
            1,
            current,
            search_id.value,
            search_titulo.value,
            search_descripcion.value,
            search_global.value
        )
    })
    watch(search_id, (current, previous) => {
        getCapitulos(
            1,
            search_categoria.value,
            current,
            search_titulo.value,
            search_descripcion.value,
            search_global.value
        )
    })
    watch(search_titulo, (current, previous) => {
        getCapitulos(
            1,
            search_categoria.value,
            search_id.value,
            current,
            search_descripcion.value,
            search_global.value
        )
    })
    watch(search_descripcion, (current, previous) => {
        getCapitulos(
            1,
            search_categoria.value,
            search_id.value,
            search_titulo.value,
            current,
            search_global.value
        )
    })
    watch(search_global, _.debounce((current, previous) => {
        getCapitulos(
            1,
            search_categoria.value,
            search_id.value,
            search_titulo.value,
            search_descripcion.value,
            current
        )
    }, 200))

</script>
