
<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<script setup>
import { ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';
import {useAbility} from '@casl/vue'
const {can} = useAbility();

const vela = "pepe";


const model = ref([
    {
        label: 'Tienda',
        items: [
            //label: es el texto  icn: el icono que esta al lado    to: hacia donde va  permision: son los tipos de usuarios que pueden ver eso
            { label: 'Productos', icon: 'pi pi-fw pi-shopping-bag', to: '/admin/producto', permision: 'all'},
            { label: 'Pedidos', icon: 'pi pi-fw pi-shopping-cart', to: '/admin/pedidos', permision: 'all'}
        ]
    },
    {
        label: 'Wiki',
        items: [
            { label: 'Todas las entradas', icon: 'pi pi-fw pi-id-card', to: '/admin/wikipedias', permision: 'all' }
        ]
    },
    {
        label: 'Multimedia',
        items: [
            { label: 'Series', icon: 'pi pi-fw pi-play', to: '/admin/capitulo', permision: 'all' },
            { label: 'Mangas', icon: 'pi pi-fw pi-book', to: '/admin/manga', permision: 'all' }
        ]
    },
    {
        label: 'Blog',
        items: [
            { label: 'Todas las entradas', icon: 'pi pi-fw pi-pencil', to: '/admin/posts', permision: 'all' }
        ]
    }
]);
</script>


<style lang="scss" scoped></style>
