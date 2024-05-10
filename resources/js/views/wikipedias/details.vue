<template>
    <div class="container">
        <div class="row g-5 mt-4 mb-5">
            <div class="col-md-8 bg-white text-dark">
            <h3 class="pb-4 mb-4 fst-italic pt-4 border-bottom">
                {{ wikipedia?.titulo }}
            </h3>
            <p class="blog-wikipedia-meta">{{ wikipedia?.created_at }} by <a href="#">{{ wikipedia?.user?.name}}</a></p>
            <article class="blog-wikipedia">
                <div class="row">
                    <div class="w-auto" v-for="categoria in wikipedia?.categorias" :key="categoria.id">
                        <router-link :to="{ name: 'categoria-wiki.index', params: { id: categoria.id } }">{{ categoria.nombre }}</router-link>
                    </div>
                </div>
                <div class="mt-4" v-html="wikipedia?.contenido"></div>
            </article>
            </div>
            <div class="col-md-4">
                <div class="position-sticky" style="top: 2rem;">
                    <div class="p-4 bg-light rounded">
                        <h4 class="fst-italic">Categorias</h4>
                        <ol v-if="categorias?.length > 0" class="list-unstyled">
                            <li v-for="categoria in categorias" :key="categoria.id">
                                <router-link :to="{ name: 'categoria-wiki.index', params: { id: categoria.id } }">{{ categoria.nombre }}</router-link>
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


    const wikipedia = ref();
    const categorias = ref();
    const route = useRoute()

    onMounted(() => {
        axios.get('/api/get-wikipedia/' + route.params.id).then(({ data }) => {
            wikipedia.value = data
        })
        axios.get('/api/categoria-list').then(({ data }) => {
            categorias.value = data.data
        })
    })
</script>
