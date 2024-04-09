<template>
    <div>
      <h2>Agregar Producto</h2>
      <form @submit.prevent="agregarProducto">
        <div>
          <label for="nombre">Nombre:</label>
          <input type="text" id="nombre" v-model="producto.nombre" required>
        </div>
        <div>
          <label for="descripcion">Descripción:</label>
          <textarea id="descripcion" v-model="producto.descripcion" required></textarea>
        </div>
        <div>
          <label for="precio">Precio:</label>
          <input type="number" id="precio" v-model.number="producto.precio" required>
        </div>
        <div>
          <label for="categoria">Categoría:</label>
          <select id="categoria" v-model="producto.categoria" required>
            <option value="">Seleccione una categoría</option>
            <option value="Electrónica">Electrónica</option>
            <option value="Ropa">Ropa</option>
            <option value="Hogar">Hogar</option>
            <!-- Agrega más opciones según tus necesidades -->
          </select>
        </div>
        <button type="submit">Agregar Producto</button>
      </form>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
    setup() {
      const producto = ref({
        nombre: '',
        descripcion: '',
        precio: 0,
        categoria: ''
      });
  
      const agregarProducto = () => {
        // Aquí puedes realizar alguna acción, como enviar el producto a un servidor
        // o almacenarlo en una base de datos local
        console.log('Producto agregado:', producto.value);
        // Limpia el formulario después de agregar el producto
        producto.value = {
          nombre: '',
          descripcion: '',
          precio: 0,
          categoria: ''
        };
      };
  
      return {
        producto,
        agregarProducto
      };
    }
  };
  </script>
  
  <style scoped>
  /* Agrega estilos CSS si es necesario */
  </style>
  