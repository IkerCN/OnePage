<template>
    <div class="container">
        <h2 class="text-center my-4">Categoria mangas</h2>
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
                        <div class="mb-1 ">Nov 12</div>
                        <p class="card-text mb-auto">{{ manga.descripcion.substring(0, 90) + "..." }}</p>
                        <router-link :to="{ name: 'public-mangas.details', params: { id: manga.id } }"
                                     class="stretched-link">Continue reading
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
import {useRoute} from "vue-router";

const route = useRoute();
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
    axios.get('/api/get-categoria-mangas/' + route.params.id).then(({data}) => {
        mangas.value = data;
    })
})
</script>
