<template>
    <div class="container">
        <h2 class="text-center my-4">Categoria capitulos</h2>
        <div class="row mb-2">
            <div v-for="capitulo in capitulos?.data" :key="capitulo.id" class="col-md-6">
                <div
                    class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col p-4 d-flex flex-column position-static">
                        <!--<div class="row">
                            <div class="w-auto" v-for="categoria in capitulo?.categorias" :key="categoria.id">
                                <router-link :to="{ name: 'categoria-capitulos.index', params: { id: categoria.id } }">{{ categoria.nombre }}</router-link>        
                            </div>
                        </div>-->
                        <div class="mb-1 ">{{ capitulo.categoria_id }}</div>

                        <h3 class="mb-0">{{ capitulo.titulo }}</h3>
                        <div class="mb-1 ">Nov 12</div>
                        <p class="card-text mb-auto">{{ capitulo.descripcion.substring(0, 90) + "..." }}</p>
                        <router-link :to="{ name: 'public-capitulos.details', params: { id: capitulo.id } }"
                                     class="stretched-link">Ves al capitulo
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
const capitulos = ref();

onMounted(() => {
    axios.get('/api/get-categoria-capitulos/' + route.params.id).then(({data}) => {
        capitulos.value = data;
    })
})
</script>
