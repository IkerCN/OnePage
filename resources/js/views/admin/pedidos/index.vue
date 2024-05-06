<template>
    <div class="row justify-content-center my-2">
     <div class="col-md-12">
         <div class="card border-0">
             <div class="card-header bg-transparent">
                 <h5 class="float-start">Pedidos</h5>
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
                                 <input v-model="search_usuario_id" type="text"
                                        class="inline-block mt-1 form-control"
                                        placeholder="Filter by usuario_id">
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
                                      @click="updateOrdering('usuario_id')">
                                     <div class="font-medium text-uppercase"
                                          :class="{ 'font-bold text-blue-600': orderColumn === 'usuario_id' }">
                                         usuario_id
                                     </div>
                                     <div class="select-none">
                             <span :class="{
                               'text-blue-600': orderDirection === 'asc' && orderColumn === 'usuario_id',
                               'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'usuario_id',
                             }">&uarr;</span>
                                         <span :class="{
                               'text-blue-600': orderDirection === 'desc' && orderColumn === 'usuario_id',
                               'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'usuario_id',
                             }">&darr;</span>
                                     </div>
                                 </div>
                             </th>
                             <th class="px-6 py-3 bg-gray-50 text-left">
                                 <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">precioFinal</span>
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
                         <tr v-for="pedido in pedidos" :key="pedido.id">
                             <td class="px-6 py-4 text-sm" width="20">
                                 {{ pedido.id }}
                             </td>
                             <td class="px-6 py-4 text-sm">
                                 {{ pedido.usuario_id }}
                             </td>
                             <td class="px-6 py-4 text-sm">
                                 <div v-html="pedido.precioFinal + ' €'"></div>
                             </td>
                             <td class="px-6 py-4 text-sm">
                                 {{ pedido.created_at }}
                             </td>
                             <td class="px-6 py-4 text-sm">                
                                 <router-link v-if="can('pedido-edit')"
                                              :to="{ name: 'pedidos.edit', params: { id: pedido.id } }" class="badge bg-primary">Edit
                                 </router-link>
                                 <a href="#" @click.prevent="deletePedido(pedido.id)"
                                    class="ms-2 badge bg-danger">Delete</a>
                             </td>
                         </tr>
                         </tbody>
                     </table>
                 </div>
             </div>
             <div class="card-footer">
                 <Pagination :data="pedidos" :limit="3"
                             @pagination-change-page="page => getPedidos(page, search_id, search_usuario_id, search_global, orderColumn, orderDirection)"
                             class="mt-4"/>
             </div>
         </div>
     </div>
 </div>
</template>
<script setup>

import {ref, onMounted, watch} from "vue";
 import usePedidos from "@/composables/pedidos";
 import {useAbility} from '@casl/vue'

 const search_id = ref('')
 const search_usuario_id = ref('')
 const search_global = ref('')
 const orderColumn = ref('created_at')
 const orderDirection = ref('desc')
 const {pedidos, getPedidos, deletePedido} = usePedidos()
 const {can} = useAbility();
 onMounted(() => {
     getPedidos()
 })
 const updateOrdering = (column) => {
     orderColumn.value = column;
     orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';
     getPedidos(
         1,
         search_id.value,
         search_usuario_id.value,
         search_global.value,
         orderColumn.value,
         orderDirection.value
     );
 }

 watch(search_id, (current, previous) => {
     getPedidos(
         1,
         current,
         search_usuario_id.value,
         search_global.value
     )
 })
 watch(search_usuario_id, (current, previous) => {
     getPedidos(
         1,
         search_id.value,
         current,
         search_global.value
     )
 })

 watch(search_global, _.debounce((current, previous) => {
    getPedidos(
        1,
        search_id.value,
        search_usuario_id.value,
        current
    )
}, 200))



</script>

<style scoped>

</style>
