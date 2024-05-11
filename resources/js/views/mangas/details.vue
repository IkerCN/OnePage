<template>
    <div class="container">
        <div class="row g-5 mt-4">
            <div class="col-md-8">
            <h3 class="pb-4 mb-4 fst-italic border-bottom text-white">
                {{ manga?.titulo }}
            </h3>
            <article class="blog-manga">
                <div v-for="image in manga?.media">
                    <img :src="image.original_url" alt="image" class="img-fluid">
                </div>
            </article>

            <nav class="blog-pagination py-3" aria-label="Pagination">
            <router-link :to="{ name: 'public-mangas.details', params: { id: (manga?.id -1) } }" class="btn btn-outline-secondary rounded-pill mr-2">Tomo anterior</router-link>
            <router-link :to="{ name: 'public-mangas.details', params: { id: (manga?.id +1) } }" class="btn btn-outline-primary rounded-pill">Siguiente tomo</router-link>

            </nav>

            </div>

            <div class="col-md-4">
                <div class="position-sticky" style="top: 2rem;">
                    <div class="p-4 mb-3 bg-light rounded">
                        <h4 class="fst-italic">información</h4>
                        <h3 class="pb-4 mb-4 fst-italic border-bottom">
                            {{ manga?.titulo }}
                        </h3>
                        <p class="blog-manga-meta">Categoria: <a href="#">{{ manga?.categorias?.nombre}}</a></p>
                        <p class="blog-manga-meta">Numero: <a href="#">{{ manga?.numero}}</a></p>
                        <div class="mt-4" v-html="manga?.descripcion"></div> 
                    </div>

                    <div class="p-4  mb-3 bg-light rounded">
                        <h4 class="fst-italic">Categorias</h4>
                        <ol v-if="categorias?.length > 0" class="list-unstyled">
                            <li v-for="categoria in categorias" :key="categoria.id">
                                <router-link :to="{ name: 'categoria-mangas.index', params: { id: categoria.id } }">{{ categoria.nombre }}</router-link>
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

    const manga = ref();
    const mangas = ref();
    const categorias = ref();
    const route = useRoute()
    const router = useRouter()

    let tomo = router.currentRoute.value.params.id;

    onMounted(() => {
        axios.get('/api/get-manga/' + route.params.id).then(({ data }) => {
            manga.value = data
        })
        axios.get('/api/categoria-list').then(({ data }) => {
            categorias.value = data.data
        })
        axios.get('/api/get-mangas').then(({data}) => {
            mangas.value = data;
        })
    })

    onUpdated( async() =>{
        if(tomo != router.currentRoute.value.params.id){
            let idExiste = mangas.value.data.some(manga => manga.id === parseInt(router.currentRoute.value.params.id));

            if (idExiste) {
                location.reload();
            }else {
                // Restaurar el valor original del parámetro 'id' en la URL
                router.push({ name: 'public-mangas.details', params: { id: tomo } });
            }
        }
    });

</script>
