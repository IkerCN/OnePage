<template>
  <div class="container">
    <div class="col-md-12">
        <div class="text-white my-4 titulo-carrito">
          <h2 class="text-center mt-4">Productos en el Carrito</h2>
        </div>
    </div>
    <div class="row">
      <!-- Columna de Productos -->
      <div class="col-md-6">
        <div v-if="productosEnCarrito.length === 0" class="text-center mt-4">
          <p>No hay productos en el carrito.</p>
        </div>
        <div v-else>
          <div v-for="producto in productosEnCarrito" :key="producto.producto.id" class="card mb-3">
            <div class="row g-0">
              <div class="col-md-4">
                <img :src="producto.producto.media[0].original_url" class="img-fluid rounded-start" alt="...">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5 class="card-title">{{ producto.producto.nombre }}</h5>
                  <p class="card-text">{{ producto.producto.descripcion }}</p>
                  <p class="card-text"><small class="text-muted">{{ producto.producto.precio }}€</small></p>
                  <div class="input-group mb-3">
                    <button class="btn btn-outline-secondary" type="button" @click="restarCantidad(producto)">-</button>
                    <input type="text" class="form-control text-center" :value="producto.cantidad" readonly>
                    <button class="btn btn-outline-secondary" type="button" @click="sumarCantidad(producto)">+</button>
                  </div>
                  <button class="btn btn-danger" @click="eliminarProducto(producto)">Eliminar Producto</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Columna del Resumen del Pedido y Acciones -->
      <div class="col-md-6">
        <!-- Resumen del Pedido -->
        <div class="card mt-4">
          <div class="card-body">
            <h5 class="card-title">Resumen del Pedido</h5>
            <p>Total de Productos: {{ calcularTotalPedido() }}€</p>
            <p>IVA (21%): {{ calcularIVAPedido() }}€</p>
            <p>Total a Pagar: {{ (parseFloat(calcularTotalPedido()) + parseFloat(calcularIVAPedido())).toFixed(2) }}€</p>
          </div>
        </div>
        <!-- Acciones -->
        <div class="mt-4">
          <button class="btn btn-success mr-2" @click="finalizarPedido">Finalizar Pedido</button>
          <button class="btn btn-danger" @click="vaciarCarrito">Vaciar Carrito</button>
          <!-- Puedes agregar más acciones aquí según sea necesario -->
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue'


// Define el ref para almacenar los productos en el carrito
const productosEnCarrito = ref([]);


// Define una función para vaciar el carrito
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

// Llama a la API para obtener los productos en el carrito al cargar la página
onMounted(() => {
  axios.get('/api/ver-carrito')
    .then(({ data }) => {
      productosEnCarrito.value = data.productosEnCarrito;
    })
    .catch(error => {
      console.error('Error al obtener productos en el carrito:', error);
    });
});

// Define una función para calcular el total del pedido
const calcularTotalPedido = () => {
  let total = 0
  productosEnCarrito.value.forEach(producto => {
    total += producto.producto.precio * producto.cantidad
  })
  return total.toFixed(2)
}

// Define una función para calcular el IVA del pedido
const calcularIVAPedido = () => {
  const total = calcularTotalPedido()
  const iva = (total * 0.21).toFixed(2) // IVA del 21%
  return iva
}

// Define una función para eliminar un producto del carrito
const eliminarProducto = (producto) => {
  axios.post('/api/eliminar-producto', {
    producto_id: producto.producto.id
  })
  .then(response => {
    console.log('Producto eliminado del carrito:', response.data);
    // Eliminar el producto del array de productos en el frontend
    const index = productosEnCarrito.value.findIndex(item => item.producto.id === producto.producto.id);
    if (index !== -1) {
      productosEnCarrito.value.splice(index, 1);
    }
  })
  .catch(error => {
    console.error('Error al eliminar producto del carrito:', error);
  });
};

// Define una función para aumentar la cantidad de un producto en el carrito
const sumarCantidad = (producto) => {
  producto.cantidad++;
  actualizarCantidadEnServidor(producto);
};

// Define una función para disminuir la cantidad de un producto en el carrito
const restarCantidad = (producto) => {
  if (producto.cantidad > 1) {
    producto.cantidad--;
    actualizarCantidadEnServidor(producto);
  }
};

// Define una función para actualizar la cantidad de un producto en el servidor
const actualizarCantidadEnServidor = (producto) => {
  axios.post('/api/actualizar-cantidad-producto', {
    producto_id: producto.producto.id,
    cantidad: producto.cantidad
  })
  .then(response => {
    console.log('Cantidad actualizada en el servidor:', response.data);
  })
  .catch(error => {
    console.error('Error al actualizar cantidad en el servidor:', error);
  });
};

// Define una función para finalizar el pedido
const finalizarPedido = () => {
  axios.post('/api/finalizar-pedido', {
    precioFinal: (parseFloat(calcularTotalPedido()) + parseFloat(calcularIVAPedido())).toFixed(2) // Calcula el precio final
  })
  .then(response => {
    console.log('Pedido finalizado:', response.data);
    // Limpia el carrito en el frontend
    productosEnCarrito.value = [];
    // Aquí podrías mostrar un mensaje de éxito al usuario si lo deseas
  })
  .catch(error => {
    console.error('Error al finalizar pedido:', error);
    // Aquí podrías mostrar un mensaje de error al usuario si lo deseas
  });
};

</script>