<template>
    {{ producto }}
    <form @submit.prevent="submitForm">
        <div class="row my-5">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <!-- nombre -->
                        <div class="mb-3">
                            <label for="producto-nombre" class="form-label">
                                nombre
                            </label>
                            <input v-model="producto.nombre" id="producto-nombre" type="text" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.nombre }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.nombre">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- precio -->
                        <div class="mb-3">
    <label for="producto-precio" class="form-label">
        Precio
    </label>
    <input v-model="producto.precio" type="number" min="0" id="producto-precio" step="0.01" class="form-control">
    <div class="text-danger mt-1">
        {{ errors.precio }}
    </div>
    <div class="text-danger mt-1">
        <div v-for="message in validationErrors?.precio">
            {{ message }}
        </div>
    </div>
</div>


                        <!-- descripcion -->
                        <div class="mb-3">
                            <label for="producto-descripcion" class="form-label">
                                descripcion
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
                            <button :disabled="isLoading" class="btn btn btn-outline-primary me-2">
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Save Draft</span>
                            </button>
                            <button :disabled="isLoading" class="btn btn-primary">
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Publish</span>
                            </button>
                        </div>
                        <h6 class="mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                            </svg> Categoria
                        </h6>
                        <!-- Categoria -->
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
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<script setup>
    import { onMounted, reactive, watchEffect } from "vue";
    import { useRoute } from "vue-router";
    import useCategorias from "@/composables/categorias";
    import useProductos from "@/composables/productos";
    import { useForm, useField, defineRule } from "vee-validate";
    import { required, min } from "@/validation/rules"
    import TextEditorComponent from "@/components/TextEditorComponent.vue";
    import DropZone from "@/components/DropZone.vue";
    defineRule('required', required)
    defineRule('min', min);

    // Define a validation schema
    const schema = {
        nombre: 'required',
        precio: 'required',
        descripcion: 'required',
        categoria_id: 'required'
    }
    // Create a form context with the validation schema
    const { validate, errors, resetForm } = useForm({ validationSchema: schema })
    // Define actual fields for validation
    const { value: nombre } = useField('nombre', null, { initialValue: '' });
    const { value: precio } = useField('precio', null, { initialValue: '' });
    const { value: descripcion } = useField('descripcion', null, { initialValue: '' });
    const { value: categoria_id } = useField('categoria_id', null, { initialValue: '', label: 'categori_id' });
    const { categoriaList, getCategoriaList } = useCategorias()
    const { producto: productoData, getProducto, updateProducto, validationErrors, isLoading } = useProductos()
    const producto = reactive({
        nombre,
        precio,
        descripcion,
        categoria_id,
        thumbnail: ''
    })
    const route = useRoute()
    function submitForm() {
        validate().then(form => { if (form.valid) updateProducto(producto) })
    }
    onMounted(() => {
        getProducto(route.params.id)
        getCategoriaList()
    })
    // https://vuejs.org/api/reactivity-core.html#watcheffect
    watchEffect(() => {
        producto.id = productoData.value.id
        producto.precio = productoData.value.precio
        producto.nombre = productoData.value.nombre
        producto.descripcion = productoData.value.descripcion
        producto.thumbnail = productoData.value.original_image
        producto.categoria_id = productoData.value.categoria_id
    })
</script>
