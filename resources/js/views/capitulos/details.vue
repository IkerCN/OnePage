<template>
    <div class="container">
        <div class="row g-5 mt-4">
            <div class="col-md-8">
            <h3 class="pb-4 mb-4 fst-italic border-bottom text-white">
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

            <nav class="blog-pagination py-3" aria-label="Pagination">
                <router-link :to="{ name: 'public-capitulos.details', params: { id: (capitulo?.id -1) } }" class="btn btn-outline-secondary rounded-pill mr-2">Capitulo anterior</router-link>
                <router-link :to="{ name: 'public-capitulos.details', params: { id: (capitulo?.id +1) } }" class="btn btn-outline-primary rounded-pill">Siguiente capitulo</router-link>
            </nav>

            </div>

            <div class="col-md-4">
                <div class="position-sticky" style="top: 2rem;">
                    <div class="p-4 mb-3 bg-light rounded">
                        <h4 class="fst-italic">información</h4>
                        <h3 class="pb-4 mb-4 fst-italic border-bottom">
                            {{ capitulo?.titulo }}
                        </h3>
                        <p class="blog-capitulo-meta">Categoria: <a href="#">{{ capitulo?.categorias?.nombre}}</a></p>
                        <p class="blog-capitulo-meta">Numero: <a href="#">{{ capitulo?.numero}}</a></p>
                        <div class="mt-4" v-html="capitulo?.descripcion"></div> 
                    </div>

                    <div class="p-4 mb-3 bg-light rounded">
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
import { ref, onMounted, onUpdated } from 'vue';
import { useRoute } from "vue-router";
import { useRouter } from 'vue-router'

    const capitulo = ref();
    const capitulos = ref();
    const categorias = ref();
    const route = useRoute()
    const router = useRouter()

    let anime = router.currentRoute.value.params.id;

    onMounted(() => {
        axios.get('/api/get-capitulo/' + route.params.id).then(({ data }) => {
            capitulo.value = data
        })
        axios.get('/api/categoria-list').then(({ data }) => {
            categorias.value = data.data
        })
        axios.get('/api/get-capitulos').then(({data}) => {
            capitulos.value = data;
        })
    })

    onUpdated( async() =>{
        if(anime != router.currentRoute.value.params.id){
            let idExiste = capitulos.value.data.some(capitulo => capitulo.id === parseInt(router.currentRoute.value.params.id));

            if (idExiste) {
                location.reload();
            }else {
                // Restaurar el valor original del parámetro 'id' en la URL
                router.push({ name: 'public-capitulos.details', params: { id: anime } });
            }
        }
    });
</script>
