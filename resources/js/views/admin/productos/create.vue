<template>
  <form @submit.prevent="submitForm">
      <div class="row my-5">
          <div class="col-md-8">
              <div class="card border-0 shadow-sm">
                  <div class="card-body">

                      <!-- Title -->
                      <div class="mb-3">
                          <label for="producto-title" class="form-label">
                              Nombre
                          </label>
                          <input v-model="producto.nombre" id="producto-title" type="text" class="form-control">
                          <div class="text-danger mt-1">
                              {{ errors.nombre }}
                          </div>
                          <div class="text-danger mt-1">
                              <div v-for="message in validationErrors?.nombre">
                                  {{ message }}
                              </div>
                          </div>
                      </div>
                      <!-- Content -->
                      <div class="mb-3">
                          <label for="producto-content" class="form-label">
                              Descripcion
                          </label>
                          <TextEditorComponent v-model="producto.descripcion"/>
                          <div class="text-danger mt-1">
                              {{ errors.descripcion }}
                          </div>
                          <div class="text-danger mt-1">
                              <div v-for="message in validationErrors?.descripcion">
                                  {{ message }}
                              </div>
                          </div>
                      </div>
                      <div class="mb-3">
                        <label for="precio">Precio</label>
                        <input type="number" id="precio" step="any" min="0" v-model.number="producto.precio" required class="form-control">
                          <div class="text-danger mt-1">
                              {{ errors.precio }}
                          </div>
                          <div class="text-danger mt-1">
                              <div v-for="message in validationErrors?.precio">
                                  {{ message }}
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-md-4">
              <div class="card border-0 shadow-sm">
                  <div class="card-body">
                      <h6>
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                          </svg> Action
                      </h6>
                      <div class="mt-3 text-center">
                          <button :disabled="isLoading" class="btn btn-primary">
                              <div v-show="isLoading" class=""></div>
                              <span v-if="isLoading">Processing...</span>
                              <span v-else>Create</span>
                          </button>
                      </div>
                      <h6 class="mt-3">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                          </svg> Categoria
                      </h6>
                      <!-- Categoria -->
                      <div class="mb-3">
                        <label for="categoria">Categoría:</label>
                        <v-select v-model="producto.categoria_id" :options="categoriaList" :reduce="categoria => categoria.id" label="nombre" class="form-control" placeholder="Seleccionar categoría"/>
                        <div class="text-danger mt-1">
                          <div v-for="message in validationErrors?.categoria_id">{{ message }}</div>
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
                  </div>
              </div>
          </div>
      </div>
  </form>
</template>
  
  <script setup>
  import TextEditorComponent from "@/components/TextEditorComponent.vue";
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
  