<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between pb-2 mb-2">
                        <h5 class="card-title">Todos los productos</h5>
                        <div>
                            <router-link :to="{name: 'productos.create'}" class="btn btn-success" >Nuevo Producto</router-link>
                       </div>
                    </div>

                    <table class="table table-hover table-sm">
                        <thead class="bg-dark text-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Nombre</th>
                            <th>description</th>
                            <th>precio</th>
                            <th>categoria</th>
                            <th class="text-center" width="200">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(producto,index) in productos">
                                <td class="text-center">{{ producto.id }}</td>
                                <td>{{producto.nombre}}</td>
                                <td>{{producto.descripcion}}</td>
                                <td>{{producto.precio}}</td>
                                <td>{{producto.categoria_id}}</td>
                                <td class="text-center">
                                    <router-link :to="{titulo: 'productos.update', params: {id: producto.id} }" class="btn btn-warning me-2">Edit</router-link>
                                    <button class="btn btn-danger" @click="deleteProducto(producto.id, index)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<!-- Primera vista de prueba, aqui finaliza la plantilla html -->
<script setup>

    import {ref, onMounted, inject} from "vue"
    const productos = ref();
    const swal = inject('$swal');
    onMounted(()=>{
        console.log('Mi vista esta montada');
        axios.get('/api/productos')
            .then(response => {
                productos.value = response.data;
            })
    });

    const deleteProducto = (id,index) => {
        axios.delete('/api/productos/'+id)
        .then(response => {
            productos.value.splice(index,1);
            swal({
                icon:'success',
                title:'Producto eliminado correctamente'
            });
        }).catch(error =>{
            console.log(error)
            swal({
                icon:'Error',
                title:'El producto no se ha eliminado correctamente'
            });

        });
    }

</script>

<style scoped>

</style>
