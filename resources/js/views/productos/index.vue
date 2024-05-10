<template>
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card mt-4 titulo-productos">
                <h2 class="text-center text-white mt-4">Productos</h2>
            </div>
        </div>           <div v-for="categoria in categorias" :key="categoria.id">
            <h4 style="color: white; text-align: center;">{{ categoria.nombre }}</h4>
            <div class="row">
                <div v-for="producto in productosPorCategoria(categoria.id)" :key="producto.id" class="col-lg-3 col-md-3 col-sm-6">
                    <div class="card text-center">
                        <div class="card-header color-fondo-blanco">
                            <h3>{{ producto.nombre }}</h3>
                        </div>
                        <div class="col-auto d-none d-lg-block d-md-block">
                            <img :src=getImageUrl(producto) class="img-fluid"/>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title"></h4>
                            <p class="card-text">{{ producto.descripcion }}</p>
                            <p class="card-text">{{ producto.precio }}€</p>
                            <button class="btn btn-secondary" @click="verProducto(producto.id)">Ve al producto</button>
                        </div>
                        <div class="card-footer color-fondo-blanco text-body-secondary">
                            <button v-if="user?.name" @click="agregarAlCarrito(producto)" class="btn btn-primary">Añadir al carrito</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import {ref, onMounted} from 'vue'

const productos = ref();
const categorias = ref();


function getImageUrl(producto) {
    let image
    if (producto.resized_image.length > 0) {
        image = producto.resized_image
    } else {
        image = producto.original_image
    }
    return new URL(image, import.meta.url).href
}

onMounted(() => {
    axios.get('/api/get-productos').then(({data}) => {
        productos.value = data;
    })
    axios.get('/api/categoria-list').then(({ data }) => {
            categorias.value = data.data
        })
})

// Función para filtrar los productos por categoría
const productosPorCategoria = (categoriaId) => {
    return productos.value.data.filter(producto => producto.categoria.id === categoriaId);
}
</script>
<style>
.titulo-productos{
    background-color:#252525;
    width:250px;
    height:150px;
    border:none;
}
</style>