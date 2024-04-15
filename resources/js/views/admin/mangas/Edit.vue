<template>
    {{ manga }}
    <form @submit.prevent="submitForm">
        <div class="row my-5">
            <div class="col-md-8">
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
                            <v-select multiple v-model="manga.categorias" :options="categoriaList"
                                      :reduce="categoria => categoria.id" label="nombre" class="form-control" placeholder="Select categoria"/>
                            <div class="text-danger mt-1">
                                {{ errors.categorias }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.categorias">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                                </svg> Paginas
                            </h6>
                            <FileUpload  name="pagina[]" url="/api/upload/" @upload="onAdvancedUpload($event)" :multiple="true" accept="image/*" :maxFileSize="1000000">
                            <!--    <FileUpload name="demo[]" url="/api/upload" @upload="onAdvancedUpload($event)" :multiple="true" accept="image/*" :maxFileSize="1000000">-->
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
                        <!--<div class="mb-3">
                            <h6 class="mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                                </svg> Thumbnail
                            </h6>
                            <DropZone v-model="manga.thumbnail"/>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.thumbnail">
                                    {{ message }}
                                </div>
                            </div>
                        </div>-->
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
        descripcion: 'required|min:50',
        categorias: 'required'
    }
    // Create a form context with the validation schema
    const { validate, errors, resetForm } = useForm({ validationSchema: schema })
    // Define actual fields for validation
    const { value: titulo } = useField('titulo', null, { initialValue: '' });
    const { value: descripcion } = useField('descripcion', null, { initialValue: '' });
    const { value: categorias } = useField('categorias', null, { initialValue: '', label: 'categoria' });
    const { categoriaList, getCategoriaList } = useCategorias()
    const { manga: mangaData, getManga, updateManga, validationErrors, isLoading } = useMangas()
    const manga = reactive({
        titulo,
        descripcion,
        categorias,
        pagina: []
    })
    const route = useRoute()
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
        manga.titulo = mangaData.value.titulo
        manga.descripcion = mangaData.value.descripcion
        manga.pagina = mangaData.value.original_image
        manga.descripcion = mangaData.value.categorias
    })
</script>
