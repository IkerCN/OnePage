<template>
    <div>
      <h2 class="section-title">Agregar Producto</h2>
      <form @submit.prevent="submitForm">
        <fieldset>
          <legend class="section-title">Información del Producto</legend>
          <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" v-model="producto.nombre" required class="form-control">
          </div>
          <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" v-model="producto.descripcion" required class="form-control"></textarea>
          </div>
          <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" id="precio" step="any" min="0" v-model.number="producto.precio" required class="form-control">
          </div>
          <div class="form-group">
            <label for="categoria">Categoría:</label>
            <v-select v-model="producto.categoria_id" :options="categoriaList" :reduce="categoria => categoria.id" label="nombre" class="form-control" placeholder="Seleccionar categoría"/>
            <div class="text-danger mt-1">
              <div v-for="message in validationErrors?.categoria_id">{{ message }}</div>
            </div>
          </div>
        </fieldset>
        
        <fieldset>
          <legend class="section-title">Thumbnail</legend>
          <div class="form-group">
            <DropZone v-model="producto.thumbnail"/>
            <div class="text-danger mt-1">
              <div v-for="message in validationErrors?.thumbnail">{{ message }}</div>
            </div>
          </div>
        </fieldset>
        
        <button :disabled="isLoading" class="btn btn-primary submit-button">
          <div v-show="isLoading" class=""></div>
          <span v-if="isLoading">Procesando...</span>
          <span v-else>Agregar Producto</span>
        </button>
      </form>
    </div>
  </template>
  
  <script setup>
  import {onMounted, reactive} from "vue";
  import DropZone from "@/components/DropZone.vue";
  import useCategoria_id from "@/composables/categorias";
  import useProductos from "@/composables/productos";
  import {useForm, useField, defineRule} from "vee-validate";
  import {required, min} from "@/validation/rules"
  
  defineRule('required', required)
  defineRule('min', min);
  
  const schema = {
      nombre: 'required|min:5',
      descripcion: 'required',
      precio: 'required',
      categoria_id: 'required'
  }
  const {validate, errors} = useForm({validationSchema: schema})
  const {value: nombre} = useField('nombre', null, {initialValue: ''});
  const {value: descripcion} = useField('descripcion', null, {initialValue: ''});
  const {value: categoria_id} = useField('categoria_id', null, {initialValue: '', label: 'categoria'});
  const {value: precio} = useField('precio', null, {initialValue: ''});
  const {categoriaList, getCategoriaList} = useCategoria_id()
  const {storeProducto, validationErrors, isLoading} = useProductos()
  const producto = reactive({
      nombre,
      descripcion,
      precio,
      categoria_id,
      thumbnail: ''
  })
  
  function submitForm() {
      validate().then(form => {
          if (form.valid) storeProducto(producto)
      })
  }
  
  onMounted(() => {
      getCategoriaList()
  })
  </script>
  
  <style scoped>
  .form-group {
    margin-bottom: 1.5rem;
  }
  
  fieldset {
    border: none;
    background-color: #fff;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
  }
  
  legend {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
  }
  
  .section-title {
    margin-bottom: 2rem;
  }
  
  .form-control {
    width: 100%;
  }
  
  .submit-button {
    margin-top: 0.7rem;
    margin-bottom: 0.5rem;
    padding: 0.75rem 1.5rem;
  }
  
  .text-danger {
    color: red;
  }
  </style>
  