<template>
    {{ manga }}
    <form @submit.prevent="submitForm">
        <div class="row my-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                         <!-- titulo -->
                        <div class="mb-3">
                            <label for="manga-titulo" class="form-label">
                                titulo
                            </label>
                            <input v-model="manga.titulo" id="manga-titulo" type="text" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.titulo }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.titulo">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <!-- descripcion -->
                        <div class="mb-3">
                            <label for="manga-descripcion" class="form-label">
                                descripcion
                            </label>
                            <TextEditorComponent v-model="manga.descripcion"/>
                            <div class="text-danger mt-1">
                                {{ errors.descripcion }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.descripcion">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <h6 class="mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                            </svg> Categoria
                        </h6>
                        <!-- Categoria -->
                        <div class="mb-3">
                            <v-select v-model="manga.categoria_id" :options="categoriaList"
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
                        <!-- numero -->
                        <div class="mb-3">
                            <label for="manga-numero" class="form-label">
                                numero
                            </label>
                            <input v-model="manga.numero" id="manga-numero" type="number" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.numero }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.numero">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <h6>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                            </svg> Action
                        </h6>
                        <div class="mt-3 text-center">
                            <button :disabled="isLoading" class="btn btn-primary">
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Guardar Datos</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Paginas -->
                        <div class="mb-3">
                            <h6 class="mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                                </svg> Paginas
                            </h6>

                            
                            <FileUpload name="pagina[]" :url=url @upload="onAdvancedUpload($event)" :multiple="true" accept="image/*" :maxFileSize="1000000">
                                    <template #empty>
                                        <p>Drag and drop files to here to upload.</p>
                                    </template>
                                </FileUpload>
                                <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.pagina">
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
    import useMangas from "@/composables/mangas";
    import { useForm, useField, defineRule } from "vee-validate";
    import { required, min } from "@/validation/rules"
    import TextEditorComponent from "@/components/TextEditorComponent.vue";
    import DropZone from "@/components/DropZone.vue";
    defineRule('required', required)
    defineRule('min', min);

    // Define a validation schema
    const schema = {
        titulo: 'required|min:5',
        numero: 'required',
        descripcion: 'required',
        categoria_id: 'required'
    }
    // Create a form context with the validation schema
    const { validate, errors, resetForm } = useForm({ validationSchema: schema })
    // Define actual fields for validation
    const { value: titulo } = useField('titulo', null, { initialValue: '' });
    const { value: numero } = useField('numero', null, { initialValue: '' });
    const { value: descripcion } = useField('descripcion', null, { initialValue: '' });
    const { value: categoria_id } = useField('categoria_id', null, { initialValue: '', label: 'categoria' });
    const { categoriaList, getCategoriaList } = useCategorias()
    const { manga: mangaData, getManga, updateManga, validationErrors, isLoading } = useMangas()
    const manga = reactive({
        titulo,
        numero,
        descripcion,
        categoria_id,
        pagina: []
    })

    
    const route = useRoute()
    const url="/api/upload/" + route.params.id;
    function submitForm() {
        validate().then(form => { if (form.valid) updateManga(manga) })


    }
    onMounted(() => {
        getManga(route.params.id)
        getCategoriaList()
    })
    // https://vuejs.org/api/reactivity-core.html#watcheffect
    watchEffect(() => {
        manga.id = mangaData.value.id
        manga.numero = mangaData.value.numero
        manga.titulo = mangaData.value.titulo
        manga.descripcion = mangaData.value.descripcion
        manga.pagina = mangaData.value.original_image
        manga.categoria_id = mangaData.value.categoria_id
    })
    
    const onAdvancedUpload = ($event) => {
    console.log($event);    
    };

</script>
