<template>
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card mt-4 titulo-capitulo">
                <h2 class="text-center text-white mt-4">Capitulos</h2>
            </div>
        </div>        
        <div class="row mb-2">
            <div v-for="capitulo in capitulos?.data" :key="capitulo.id" class="col-md-6">
                <div
                    class="row g-0 bg-light border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col-auto d-none d-lg-block d-md-block">
                        <video class="img-fluid">
                            <source :src="getImageUrl(capitulo)" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="col p-4 d-flex flex-column position-static">
                        <h3 class="mb-0">{{ capitulo.titulo }}</h3>
                        <div class="mb-1 text-muted">{{ capitulo.created_at }}</div>
                        <div class="card-text mb-auto" v-html="capitulo.descripcion.substring(0, 150) + '...'"></div>
                        <router-link :to="{ name: 'public-capitulos.details', params: { id: capitulo.id } }" class="stretched-link">Continual leyendo
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

const capitulos = ref();

function getImageUrl(capitulo) {
    let image

    image = capitulo.original_image
    
    return new URL(image, import.meta.url).href
}

onMounted(() => {
    axios.get('/api/get-capitulos').then(({data}) => {
        capitulos.value = data;
    })
})
</script>
<style>
.titulo-capitulo{
    background-color:#252525;
    width:250px;
    height:150px;
    border:none;
}
</style>
