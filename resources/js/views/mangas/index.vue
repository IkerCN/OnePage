<template>
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card mt-4 titulo-manga">
                <h2 class="text-center text-white mt-4">Tomos</h2>
            </div>
        </div>
        <div class="row mb-2">
            <div v-for="manga in mangas?.data" :key="manga.id" class="col-md-6">
                <div
                    class="row g-0 bg-light border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col-auto d-none d-lg-block d-md-block">
                        <img :src="getImageUrl(manga)" class="img-fluid"/>
                    </div>
                    <div class="col p-4 d-flex flex-column position-static">
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
</template>

<script setup>
import axios from 'axios';
import {ref, onMounted} from 'vue'

const mangas = ref();

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
})
</script>
<style>
.titulo-manga{
    background-color:#252525;
    width:250px;
    height:150px;
    border:none;
}
</style>