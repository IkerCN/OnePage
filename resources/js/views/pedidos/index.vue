<template>
    <div>
        <h2>Mis Pedidos</h2>
        <ul>
            <li v-for="pedido in pedidos" :key="pedido.id">
                <p>ID: {{ pedido.id }}</p>
                <p>Precio Total: {{ pedido.precioFinal }}</p>
                <!-- Otros detalles del pedido -->
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const pedidos = ref([
    { id: 1, precioFinal: 50 },
    { id: 2, precioFinal: 70 },
    // Aquí cargarías los pedidos del usuario desde la API o desde tu backend
]);
</script>

<style scoped>
/* Estilos específicos para esta vista */
</style>
