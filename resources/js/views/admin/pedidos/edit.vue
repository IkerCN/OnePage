<template>
    {{ pedido }}
    <form @submit.prevent="submitForm">
        <div class="row my-5">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        
                        <!-- precioFinal -->
                        <div class="mb-3">
                            <label for="pedido-precioFinal" class="form-label">
                                precioFinal
                            </label>
                            <input v-model="pedido.precioFinal" type="number" min="0" id="pedido-precioFinal" step="0.01" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.precioFinal }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.precioFinal">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                            </svg> Action
                        </h6>
                        <div class="mt-3 text-center">
                            <button :disabled="isLoading" class="btn btn btn-outline-primary me-2">
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Save Draft</span>
                            </button>
                            <button :disabled="isLoading" class="btn btn-primary">
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Publish</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<script setup>
    import { onMounted, reactive, watchEffect } from "vue";
    import { useRoute } from "vue-router";
    import usePedidos from "@/composables/pedidos";
    import { useForm, useField, defineRule } from "vee-validate";
    import { required, min } from "@/validation/rules"
    import TextEditorComponent from "@/components/TextEditorComponent.vue";
    import DropZone from "@/components/DropZone.vue";
    defineRule('required', required)
    defineRule('min', min);

    // Define a validation schema
    const schema = {
        precioFinal: 'required'
    }
    // Create a form context with the validation schema
    const { validate, errors, resetForm } = useForm({ validationSchema: schema })
    // Define actual fields for validation
    const { value: precioFinal } = useField('precioFinal', null, { initialValue: '' });
    const { pedido: pedidoData, getPedido, updatePedido, validationErrors, isLoading } = usePedidos()
    const pedido = reactive({
        precioFinal
    })
    const route = useRoute()
    function submitForm() {
        validate().then(form => { if (form.valid) updatePedido(pedido) })
    }
    onMounted(() => {
        getPedido(route.params.id)
    })
    // https://vuejs.org/api/reactivity-core.html#watcheffect
    watchEffect(() => {
        pedido.id = pedidoData.value.id
        pedido.precioFinal = pedidoData.value.precioFinal
    })
</script>
