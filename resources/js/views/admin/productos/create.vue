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
    categoria_id
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
  