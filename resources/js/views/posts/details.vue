<template>
    <div class="container">
        <div class="row g-5 mt-4">
            <div class="col-md-8 bg-light mb-2 rounded">
                <h3 class="pb-4 mb-4 fst-italic border-bottom">
                    {{ post?.title }}
                </h3>
                <article class="blog-post pb-3">
                    <div v-for="image in post?.media">
                        <img :src="image.original_url" alt="image" class="img-fluid">
                    </div>
                    <div class="mt-4" v-html="post?.content"></div>
                    <p class="blog-post-meta">{{ post?.created_at }} by <a href="#">{{ post?.user?.name}}</a></p>

                </article>
            </div>
            <div class="col-md-4">
                <div class="position-sticky" style="top: 2rem;">
                    <div class="p-4 mb-3 bg-light rounded">
                        <h4 class="fst-italic">Categorias</h4>
                        <ol v-if="categorias?.length > 0" class="list-unstyled">
                            <li v-for="categoria in categorias" :key="categoria.id">
                                <router-link :to="{ name: 'categoria-posts.index', params: { id: categoria.id } }">{{ categoria.nombre }}</router-link>
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


    const post = ref();
    const categorias = ref();
    const route = useRoute()

    onMounted(() => {
        axios.get('/api/get-post/' + route.params.id).then(({ data }) => {
            post.value = data
        })
        axios.get('/api/categoria-list').then(({ data }) => {
            categorias.value = data.data
        })
    })
</script>
