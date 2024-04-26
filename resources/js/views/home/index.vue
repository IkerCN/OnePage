<template>
    <div class="m-5 d-flex justify-content-center align-items-center">
        <div class="grid-center">
            <div class="col-12 xl:col-12">
                <div class="container">
                    <div id="carouselBanner" class="carousel slide">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="/images/prueba_banner2.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="/images/prueba_banner3.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="/images/prueba_banner5.jpg" class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselBanner" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12 xl:col-12">
                <div class="rounded mt-5 text-center">
                    <h3>Ultimas Noticias</h3>
                </div>
                <div class="d-flex justify-content-center">
                    <div id="carouselExampleIndicators" class="carousel slide carrousel-size carousel-dark slide">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active bg-primary" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" class="bg-primary" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" class="bg-primary" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div v-for="(post, index) in posts?.data" :key="post.id" :class="{ 'carousel-item': true, 'active': index === 0 }">
                                <div class="card text-center">
                                    <div class="card-header color-fondo-blanco">
                                        <h4>{{ post.title }}</h4>
                                    </div>
                                    <div class="col-auto d-none d-lg-block d-md-block">
                                        <img :src="getImageUrl(post)" class="img-fluid"/>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"></h5>
                                        <p class="card-text" v-html="post.content.substring(0, 150) + '...'"></p>
                                    </div>
                                    <router-link :to="{ name: 'public-posts.details', params: { id: post.id } }" class="mb-2 stretched-link">Continual leyendo
                                    </router-link>
                                    <div class="card-footer color-fondo-blanco text-body-secondary">
                                        Fecha publicacion
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="container col-12 xl:col-12">
                <div class="mt-5 text-center">
                    <i></i>
                    <h3>Multimedia</h3>
                </div>
            </div>
            <div class="col-6 xl:col-6">
                <div class="container">
                    <h4 class="text-center">Manga</h4>
                    <div class="accordion" id="accordionOne" v-if="categorias?.length > 0">
                        <div class="accordion-item" v-for="(categoria, index) in categorias" :key="'manga' + categoria.id">
                            <h5 class="accordion-header">
                                <button class="accordion-button" type="button" :class="{ 'collapsed': index !== 0 }" :aria-expanded="index === 0 ? 'true' : 'false'" :aria-controls="'collapseManga' + categoria.id" data-bs-toggle="collapse" :data-bs-target="'#collapseManga' + categoria.id">
                                    <b>{{ categoria.nombre }}</b>
                                </button>
                            </h5>
                            <div :id="'collapseManga' + categoria.id" class="accordion-collapse collapse" :class="{ 'show': index === 0 }" :aria-labelledby="'headingManga' + categoria.id" data-bs-parent="#accordionOne">
                                <div class="accordion-body">
                                    <div v-for="manga1 in mangas?.data" :key="manga1.id">
                                        <button v-if="manga1.categoria_id === categoria.id" class="accordion-item" @click="verManga(manga1.id)">{{ manga1.titulo }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 xl:col-6">
                <div class="container">
                    <h4 class="text-center">Videos</h4>
                    <div class="accordion" id="accordionTwo" v-if="categorias?.length > 0">
                        <div class="accordion-item" v-for="(categoria, index) in categorias" :key="'video' + categoria.id">
                            <h5 class="accordion-header">
                                <button class="accordion-button" type="button" :class="{ 'collapsed': index !== 0 }" :aria-expanded="index === 0 ? 'true' : 'false'" :aria-controls="'collapseVideo' + categoria.id" data-bs-toggle="collapse" :data-bs-target="'#collapseVideo' + categoria.id">
                                    <b>{{ categoria.nombre }}</b>
                                </button>
                            </h5>
                            <div :id="'collapseVideo' + categoria.id" class="accordion-collapse collapse" :class="{ 'show': index === 0 }" :aria-labelledby="'headingVideo' + categoria.id" data-bs-parent="#accordionTwo">
                                <div class="accordion-body">
                                    <div v-for="capitulo in capitulos?.data" :key="capitulo.id">
                                    
                                        <button v-if="capitulo.categoria_id === categoria.id" class="accordion-item mb-2" @click="verCapitulo(capitulo.id)">{{ capitulo.titulo }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 xl:col-12">
                <div class="rounded mt-5 text-center">
                    <h3>Productos</h3>
                </div>
                <div class="d-flex justify-content-center">
                    <div id="carouselProductosIndicators" class="carousel slide carrousel-size carousel-dark slide">
                        <div class="carousel-inner">
                            <div class="carousel-item" v-for="(chunk, index) in chunkedProductos" :key="index" :class="{ 'active': index === 0 }">
                                <div class="row">
                                    <div v-for="(producto, innerIndex) in chunk" :key="innerIndex" class="col-lg-3 col-md-3 col-sm-6">
                                        <div class="card text-center">
                                            <div class="card-header color-fondo-blanco">
                                                <h4>{{ producto.nombre }}</h4>
                                            </div>
                                            <div class="col-auto d-none d-lg-block d-md-block">
                                                <img :src="getImageUrl(producto)" class="img-fluid"/>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title"></h5>
                                                <p class="card-text" v-html="producto.descripcion.substring(0, 150) + '...'"></p>
                                                <p class="card-text" v-html="producto.precio + '€'"></p>
                                            </div>
                                            <router-link :to="{ name: 'public-productos.details', params: { id: producto.id } }" class="mb-2 stretched-link">Ve al producto
                                            </router-link>
                                            <div class="card-footer color-fondo-blanco text-body-secondary">
                                                <button @click="agregarAlCarrito(producto)" class="btn btn-primary">Añadir al carrito</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Controles del carrusel -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProductosIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselProductosIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import {ref, onMounted, computed} from 'vue'
import { useRouter } from 'vue-router'


const router = useRouter();
const posts = ref();
const productos = ref();
const mangas = ref();
const capitulos = ref();
const categorias = ref();

const verCapitulo = (id) => {
    router.push({ name: 'public-capitulos.details', params: { id: id } });
};

const verManga = (id) => {
    router.push({ name: 'public-mangas.details', params: { id: id } });
};

function getImageUrl(media) {
    let image
    if (media.resized_image.length > 0) {
        image = media.resized_image
    } else {
        image = media.original_image
    }
    return new URL(image, import.meta.url).href
}

onMounted(() => {
    axios.get('/api/get-mangas').then(({data}) => {
        mangas.value = data;
    })
    axios.get('/api/categoria-list').then(({ data }) => {
            categorias.value = data.data
        })
    axios.get('/api/get-posts').then(({data}) => {
        posts.value = data;
    })
    axios.get('/api/get-productos').then(({data}) => {
        productos.value = data;
    })
    axios.get('/api/get-capitulos').then(({data}) => {
        capitulos.value = data;
    })
})

const agregarAlCarrito = (producto) => {
    // Realizar una solicitud al servidor para agregar el producto al carrito
    axios.post('/agregar-al-carrito', { producto })
        .then(response => {
            console.log(response.data.message);
            // Aquí podrías mostrar un mensaje de éxito al usuario si lo deseas
        })
        .catch(error => {
            console.error('Error al agregar al carrito:', error);
            // Aquí podrías mostrar un mensaje de error al usuario si lo deseas
        });
};

const chunkedProductos = computed(() => {
    if (!productos.value || !productos.value.data || productos.value.data.length === 0) {
        return [[]];
    }

    const products = productos.value.data;
    const chunkSize = 4;
    const totalProducts = products.length;
    const totalChunks = Math.ceil(totalProducts / chunkSize);
    const duplicatedProducts = [...products];
    
    // Duplicar los productos hasta que haya suficientes para llenar todas las diapositivas
    while (duplicatedProducts.length < totalChunks * chunkSize) {
        duplicatedProducts.push(...products);
    }

    const chunks = [];
    for (let i = 0; i < totalChunks; i++) {
        const startIndex = i * chunkSize;
        const chunk = duplicatedProducts.slice(startIndex, startIndex + chunkSize);
        chunks.push(chunk);
    }

    return chunks;
});
</script>

<style scoped>
.grid-center {
    display: flex;
    flex-wrap: wrap;
}
.carrousel-size{
    display: flex;
}
h3{
    color:#FAFAFA;
}
h4{
    color:#FAFAFA;
}
</style>
