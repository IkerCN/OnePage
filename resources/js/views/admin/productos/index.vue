<template>
       <div class="row justify-content-center my-2">
        <div class="col-md-12">
            <div class="card border-0">
                <div class="card-header bg-transparent">
                    <h5 class="float-start">Productos</h5>
                    <router-link :to="{ name: 'productos.create' }" class="btn btn-primary btn-sm float-end">
                        Create Producto
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
                                    <input v-model="search_nombre" type="text"
                                           class="inline-block mt-1 form-control"
                                           placeholder="Filter by nombre">
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
                                         @click="updateOrdering('nombre')">
                                        <div class="font-medium text-uppercase"
                                             :class="{ 'font-bold text-blue-600': orderColumn === 'nombre' }">
                                            nombre
                                        </div>
                                        <div class="select-none">
                                <span :class="{
                                  'text-blue-600': orderDirection === 'asc' && orderColumn === 'nombre',
                                  'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'nombre',
                                }">&uarr;</span>
                                            <span :class="{
                                  'text-blue-600': orderDirection === 'desc' && orderColumn === 'nombre',
                                  'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'nombre',
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
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">precio</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="producto in productos.data" :key="producto.id">
                                <td class="px-6 py-4 text-sm" width="20">
                                    {{ producto.id }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ producto.nombre }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <img :src="producto.original_image" alt="image" height="70">
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ producto.categoria.nombre }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div v-html="producto.descripcion.slice(0, 100) + '...'"></div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div v-html="producto.precio + ' €'"></div>
                                </td>
                                <td class="px-6 py-4 text-sm">                
                                    <router-link v-if="can('producto-edit')"
                                                 :to="{ name: 'productos.edit', params: { id: producto.id } }" class="badge bg-primary">Edit
                                    </router-link>
                                    <a href="#" @click.prevent="deleteProducto(producto.id)"
                                       class="ms-2 badge bg-danger">Delete</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <Pagination :data="productos" :limit="3"
                                @pagination-change-page="page => getProductos(page, search_categoria, search_id, search_nombre, search_descripcion, search_global, orderColumn, orderDirection)"
                                class="mt-4"/>
                </div>
            </div>
        </div>
    </div>
</template>
<!-- Primera vista de prueba, aqui finaliza la plantilla html -->
<script setup>

import {ref, onMounted, watch} from "vue";
    import useProductos from "@/composables/productos";
    import useCategorias from "@/composables/categorias";
    import {useAbility} from '@casl/vue'

    const search_categoria = ref('')
    const search_id = ref('')
    const search_nombre = ref('')
    const search_descripcion = ref('')
    const search_global = ref('')
    const orderColumn = ref('created_at')
    const orderDirection = ref('desc')
    const {productos, getProductos, deleteProducto} = useProductos()
    const {categoriaList, getCategoriaList} = useCategorias()
    const {can} = useAbility();
    onMounted(() => {
        getProductos()
        getCategoriaList()
    })
    const updateOrdering = (column) => {
        orderColumn.value = column;
        orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';
        getProductos(
            1,
            search_categoria.value,
            search_id.value,
            search_nombre.value,
            search_descripcion.value,
            search_global.value,
            orderColumn.value,
            orderDirection.value
        );
    }
    watch(search_categoria, (current, previous) => {
        getProductos(
            1,
            current,
            search_id.value,
            search_nombre.value,
            search_descripcion.value,
            search_global.value
        )
    })
    watch(search_id, (current, previous) => {
        getProductos(
            1,
            search_categoria.value,
            current,
            search_nombre.value,
            search_descripcion.value,
            search_global.value
        )
    })
    watch(search_nombre, (current, previous) => {
        getProductos(
            1,
            search_categoria.value,
            search_id.value,
            current,
            search_descripcion.value,
            search_global.value
        )
    })
    watch(search_descripcion, (current, previous) => {
        getProductos(
            1,
            search_categoria.value,
            search_id.value,
            search_nombre.value,
            current,
            search_global.value
        )
    })
    watch(search_global, _.debounce((current, previous) => {
        getProductos(
            1,
            search_categoria.value,
            search_id.value,
            search_nombre.value,
            search_descripcion.value,
            current
        )
    }, 200))

</script>

<style scoped>

</style>
