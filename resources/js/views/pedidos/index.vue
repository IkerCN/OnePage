<template>
    <div class="row justify-content-center my-2">
        <div class="col-md-12">
            <div class="card border-0">
                <div class="card-header bg-transparent">
                    <h5 class="float-start">Mis Pedidos</h5>
                </div>
                <div class="card-body shadow-sm">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">ID</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Precio Final</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Fecha de Creación</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Productos</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Precio Unitario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="pedido in pedidos" :key="pedido.id">
                                    <td class="px-6 py-4 text-sm">{{ pedido.id }}</td>
                                    <td class="px-6 py-4 text-sm">{{ pedido.precioFinal }}</td>
                                    <td class="px-6 py-4 text-sm">{{ pedido.created_at }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <ul>
                                            <li v-for="producto in pedido.productos" :key="producto.id">
                                                {{ producto.nombre }}
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <ul>
                                            <li v-for="producto in pedido.productos" :key="producto.id">
                                                {{ producto.precio }}
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from "vue";
import { useAbility } from '@casl/vue'

const { can } = useAbility();

// Variable para almacenar los pedidos del usuario logueado
const pedidos = ref([]);

onMounted(() => {
    axios.get('/api/mis-pedidos').then(({data}) => {
        pedidos.value = data;
    })
})
</script>

<style scoped>
/* Estilos específicos para esta vista */
</style>
