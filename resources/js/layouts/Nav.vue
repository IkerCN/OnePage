<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <router-link to="/" class="navbar-brand"><img src="/images/logo_onepage_sin color.svg" alt="Logo de OnePage" height="40"></router-link>
            <a class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mt-2 mt-lg-0 me-auto mb-2 mb-lg-0">
                    <LocaleSwitcher />
                </ul>
                <ul class="navbar-nav mx-auto"> <!-- Clase mx-auto para centrar -->
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" aria-current="page">{{ $t('home') }}</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name : 'public-posts.index'}" class="nav-link">Blog</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name : 'public-productos.index'}" class="nav-link">{{ $t('shop') }}</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name : 'public-wikipedias.index'}" class="nav-link">Wikipedia</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name : 'public-capitulos.index'}" class="nav-link">{{ $t('chapters') }}</router-link>
                    </li>                        
                    <li class="nav-item">
                        <router-link :to="{ name : 'public-mangas.index'}" class="nav-link">Mangas</router-link>
                    </li>
                </ul>
                <ul class="navbar-nav mt-2 mt-lg-0 ms-auto">
                    <template v-if="!user?.name">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/login">{{ $t('login') }}</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/register">{{ $t('register') }}</router-link>
                        </li>
                    </template>
                    <li v-if="user?.name" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ user.name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end"> 
                            <li v-if="user && user.roles && user.roles.length > 0 && user.roles[0].name === 'admin'"><router-link class="dropdown-item" to="/admin">Admin</router-link></li>
                            <li><router-link to="/carrito" class="dropdown-item">Carrito</router-link></li>
                            <li><router-link to="/pedido" class="dropdown-item">Mis Pedidos</router-link></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useStore } from "vuex";
import useAuth from "@/composables/auth";
import { computed } from "vue";
import LocaleSwitcher from "../components/LocaleSwitcher.vue";

const store = useStore();
const user = computed(() => store.getters["auth/user"]);
const { processing, logout } = useAuth();
</script>
