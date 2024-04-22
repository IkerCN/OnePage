<template>
    <div class="container">
        <h2 class="text-center my-4">Mangas</h2>
        <div class="row mb-2">
            <div v-for="manga in mangas?.data" :key="manga.id" class="col-md-6">
                <div
                    class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col-auto d-none d-lg-block d-md-block">
                        <img :src="getImageUrl(manga)" class="img-fluid"/>
                    </div>
                    <div class="col p-4 d-flex flex-column position-static">
                        <!--<div class="row">
                            <div class="w-auto" v-for="categoria in manga?.categorias" :key="categoria.id">
                                <router-link :to="{ name: 'categoria-mangas.index', params: { id: categoria.id } }">{{ categoria.nombre }}</router-link>        
                            </div>
                        </div>-->
                        <div class="mb-1 ">{{ manga.categoria_id }}</div>

                        <h3 class="mb-0">{{ manga.titulo }}</h3>
                        <div class="mb-1 text-muted">{{ manga.created_at }}</div>
                        <div class="card-text mb-auto" v-html="manga.descripcion.substring(0, 150) + '...'"></div>
                        <router-link :to="{ name: 'public-mangas.details', params: { id: manga.id } }" class="stretched-link">Continual leyendo
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <h4 class="text-center">Manga</h4>
        <div class="accordion" id="accordionOne" v-if="categorias?.length > 0">
            <div class="accordion-item" v-for="(categoria, index) in categorias" :key="categoria.id">
                <h5 class="accordion-header">
                    <button class="accordion-button" type="button" :class="{ 'collapsed': index !== 0 }" :aria-expanded="index === 0 ? 'true' : 'false'" :aria-controls="'collapse' + categoria.id" data-bs-toggle="collapse" :data-bs-target="'#collapse' + categoria.id">
                        <b>{{ categoria.nombre }}</b>
                    </button>
                </h5>
                <div :id="'collapse' + categoria.id" class="accordion-collapse collapse" :class="{ 'show': index === 0 }" :aria-labelledby="'heading' + categoria.id" data-bs-parent="#accordionOne">
                    <div class="accordion-body">
                        <div v-for="manga in mangas?.data" :key="manga.id">
                            <p v-if="manga.categoria_id === categoria.id">
                                <router-link :to="{ name: 'public-mangas.details', params: { id: manga.id } }" class="stretched-link">{{ manga.titulo }}</router-link>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import {ref, onMounted} from 'vue'

const mangas = ref();
const categorias = ref();

function getImageUrl(manga) {
    let image
    if (manga.resized_image.length > 0) {
        image = manga.resized_image
    } else {
        image = manga.original_image
    }
    return new URL(image, import.meta.url).href
}

onMounted(() => {
    axios.get('/api/get-mangas').then(({data}) => {
        mangas.value = data;
    })
    axios.get('/api/categoria-list').then(({ data }) => {
            categorias.value = data.data
        })
})
</script>
