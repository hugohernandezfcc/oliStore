<script >
import { ref, reactive, nextTick } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
 
export default{

    components:{
        AppLayout, PrimaryButton, InputError, InputLabel, TextInput
    },
    props:{
        product: Object

    },
    data(){
        return {
            form: {
                id: this.product.id,
                name: this.product.name
            }
        }
    },
    methods:{
        submit(){
            this.$inertia.put(this.route('products.update', this.product.id), this.form)
        }
    },
    mounted(){

    }
}



</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                OliStore
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">
                            <h3 class="text-lg text-gray-900"> Editar el detalle de un producto </h3>
                            <p class="text-sm text-gray">{{product.id}} </p>
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <form @submit.prevent="submit">
                                <label class="block font-medium text-sm text-gray-700">
                                    Detalle
                                </label>
                                <InputLabel for="product_name" value="Nombre del producto" />
                                <TextInput
                                    id="product_name"
                                    ref="product_name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <!-- <InputError :message="form.errors.current_password" class="mt-2" /> -->
                                <PrimaryButton
                                    class="ms-3"
                                    
                                >
                                    {{ button }}
                                </PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
