<template>
    <div>
        {{ producto }}
      <h2>Agregar Producto</h2>
      <form @submit.prevent="submitForm">
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
          <input type="number" id="precio" step="any" min="0" v-model.number="producto.precio" required >
        </div>
        <div class="mb-3">
            <v-select v-model="producto.categoria_id" :options="categoriaList"
                      :reduce="categoria => categoria.id" label="nombre" class="form-control" placeholder="Select categoria"/>
            <div class="text-danger mt-1">
                {{ errors.categoria_id }}
            </div>
            <div class="text-danger mt-1">
                <div v-for="message in validationErrors?.categoria_id">
                    {{ message }}
                </div>
            </div>
        </div>
        <div class="mb-3">
                            <h6 class="mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                                </svg> Thumbnail
                            </h6>
                            <DropZone v-model="producto.thumbnail"/>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.thumbnail">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
        <button :disabled="isLoading" class="btn btn-primary">
            <div v-show="isLoading" class=""></div>
            <span v-if="isLoading">Processing...</span>
            <span v-else>Agregar Producto</span>
        </button>
      </form>
    </div>
  </template>
  
  <script setup>
import {onMounted, reactive, ref} from "vue";
import TextEditorComponent from "@/components/TextEditorComponent.vue";
import DropZone from "@/components/DropZone.vue";
import useCategoria_id from "@/composables/categorias";
import useProductos from "@/composables/productos";
import {useForm, useField, defineRule} from "vee-validate";
import {required, min} from "@/validation/rules"

defineRule('required', required)
defineRule('min', min);

const dropZoneActive = ref(true)

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

const thefile = ref('')

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
  
  </style>
  