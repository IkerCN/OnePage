<template>
    <div class="container">
        <div class="row g-5 mt-4">
            <div class="col-md-8">
            <h3 class="pb-4 mb-4 fst-italic border-bottom">
                {{ capitulo?.titulo }}
            </h3>
            <article class="blog-capitulo">
                <div v-for="anime in capitulo?.media">
                    <!--Video width="320" height="240" -->
                    <video width="100%" controls :key="anime.original_url">
                        <source :src="anime.original_url" type="video/mp4" />
                    </video>
                    
                </div>
            </article>

            <nav class="blog-pagination" aria-label="Pagination">
                <router-link :to="{ name: 'public-capitulos.details', params: { id: (capitulo?.id +1) } }" class="btn btn-outline-primary rounded-pill">Siguiente capitulo</router-link>
                <router-link :to="{ name: 'public-capitulos.details', params: { id: (capitulo?.id -1) } }" class="btn btn-outline-secondary rounded-pill">Capitulo anterior</router-link>

            </nav>

            </div>

            <div class="col-md-4">
                <div class="position-sticky" style="top: 2rem;">
                    <div class="p-4 mb-3 bg-light rounded">
                        <h4 class="fst-italic">información</h4>
                        <h3 class="pb-4 mb-4 fst-italic border-bottom">
                            {{ capitulo?.titulo }}
                        </h3>
                        <p class="blog-capitulo-meta">1 de Enero de 2024 by <a href="#">{{ capitulo?.user?.name}}</a></p>
                        <div class="mt-4" v-html="capitulo?.descripcion"></div>                    
                    </div>

                    <div class="p-4">
                        <h4 class="fst-italic">Categorias</h4>
                        <ol v-if="categorias?.length > 0" class="list-unstyled">
                            <li v-for="categoria in categorias" :key="categoria.id">
                                <router-link :to="{ name: 'categoria-capitulos.index', params: { id: categoria.id } }">{{ categoria.nombre }}</router-link>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRoute } from "vue-router";


    const capitulo = ref();
    const categorias = ref();
    const route = useRoute()

    onMounted(() => {
        axios.get('/api/get-capitulo/' + route.params.id).then(({ data }) => {
            capitulo.value = data
        })
        axios.get('/api/categoria-list').then(({ data }) => {
            categorias.value = data.data
        })
    })
</script>
