<template>
    <div class="container">
      <div class="d-flex justify-content-center">
        <div class="card mt-4 titulo-wiki">
          <h2 class="text-center text-white mt-4">Productos en el Carrito</h2>
        </div>
      </div>
      <div v-if="productosEnCarrito.length === 0" class="text-center mt-4">
        <p>No hay productos en el carrito.</p>
      </div>
      <div v-else class="row mb-2">
        <div v-for="producto in productosEnCarrito" :key="producto.id" class="col-md-6">
          <div class="card mb-3">
            <div class="row g-0">
              <div class="col-md-4">
                <img :src="getImageUrl(producto.producto)" class="img-fluid rounded-start" alt="...">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5 class="card-title">{{ producto.producto.nombre }}</h5>
                  <p class="card-text">{{ producto.producto.descripcion }}</p>
                  <p class="card-text"><small class="text-muted">{{ producto.producto.precio }}€</small></p>
                  <p class="card-text">{{ producto.cantidad }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-center mt-4">
        <button class="btn btn-danger" @click="vaciarCarrito">Vaciar Carrito</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import axios from 'axios';
  import { ref, onMounted } from 'vue'
  
  const productosEnCarrito = ref([]);
  
  function getImageUrl(producto) {
    let image
   // if (producto.resized_image.length > 0) {
    //  image = producto.resized_image
    //} else {
      image = producto.original_image
    //}
    return new URL(image, import.meta.url).href
  }
  
  const vaciarCarrito = () => {
    // Realizar una solicitud al servidor para vaciar el carrito
    axios.post('/api/vaciar-carrito')
      .then(response => {
        // Actualizar la lista de productos en el carrito después de vaciarlo
        productosEnCarrito.value = [];
        console.log(response.data);
        // Aquí podrías mostrar un mensaje de éxito al usuario si lo deseas
      })
      .catch(error => {
        console.error('Error al vaciar el carrito:', error);
        // Aquí podrías mostrar un mensaje de error al usuario si lo deseas
      });
  };
  
  onMounted(() => {
    axios.get('/api/ver-carrito').then(({ data }) => {
      productosEnCarrito.value = data.productosEnCarrito;
    })
  })
  </script>
  
  