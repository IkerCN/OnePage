<template>
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card mt-4 titulo-wiki">
                <h2 class="text-center text-white mt-4">Wikipedias</h2>
            </div>
        </div>
        <div class="row mb-2">
            <div v-for="wikipedia in wikipedias?.data" :key="wikipedia.id" class="col-md-6">
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative bg-white text-dark">
                    <div class="col p-4 d-flex flex-column position-static">
                        <strong v-for="categoria in wikipedia.categorias" class="d-inline-block mb-2 text-primary">
                            {{ categoria.nombre }}
                        </strong>
                        <h3 class="mb-0">{{ wikipedia.titulo }}</h3>
                        <div class="mb-1 text-muted">{{ wikipedia.created_at }}</div>
                        <div class="card-text mb-auto" v-html="wikipedia.contenido.substring(0, 90) + '...'"></div>
                        <router-link :to="{ name: 'public-wikipedias.details', params: { id: wikipedia.id } }"
                                     class="stretched-link">Continuar leyendo
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

const wikipedias = ref();

onMounted(() => {
    axios.get('/api/get-wikipedias').then(({data}) => {
        wikipedias.value = data;
    })
})
</script>
<style>
.titulo-wiki{
    background-color:#252525;
    width:250px;
    height:150px;
    border:none;
}
</style>
