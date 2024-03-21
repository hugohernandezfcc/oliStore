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
                name: this.product.name,
                folio: this.product.folio,
                Description: this.product.Description,
                unit_measure: this.product.unit_measure,
                price_list: this.product.price_list,
                price_customer: this.product.price_customer,
                profit_percentage: this.product.profit_percentage,
                expiry_date: this.product.expiry_date,
                take_portion: this.product.take_portion,
                visible_product:this.product.visible_product
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
                                <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Nombre del producto" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="name"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="folio" value="Código de barras" />
                            <TextInput
                                id="folio"
                                v-model="form.folio"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="folio"
                            />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="Description" value="Descripción" />
                            <textarea
                                id="Description"
                                v-model="form.Description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                                autocomplete="Description"
                            >

                            </textarea>
                            
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="unit_measure" value="Unidad de medida" />
                            <TextInput
                                id="unit_measure"
                                v-model="form.unit_measure"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="unit_measure"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="price_list" value="Precio de lista" />
                            <TextInput
                                id="price_list"
                                v-model="form.price_list"
                                type="number"
                                step="0.01"
                                class="mt-1 block w-full"
                                required
                                autocomplete="price_list"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="price_customer" value="Precio al cliente" />
                            <TextInput
                                id="price_customer"
                                v-model="form.price_customer"
                                type="number"
                                step="0.01"
                                class="mt-1 block w-full"
                                required
                                autocomplete="price_customer"
                            />
                        </div>

                       
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="expiry_date" value="Fecha de caducidad" />
                            <TextInput
                                id="expiry_date"
                                v-model="form.expiry_date"
                                type="date"
                                class="mt-1 block w-full"
                                required
                                autocomplete="expiry_date"
                            />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <el-switch
                                v-model="form.take_portion"
                                class="mb-2"
                                active-text="Se vende a granel"
                                inactive-text=" "
                            />
                            
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <el-switch
                                v-model="form.visible_product"
                                class="mb-2"
                                active-text="Se Muestra"
                                inactive-text=" "
                            />
                            
                        </div>

                                <!-- <InputError :message="form.errors.current_password" class="mt-2" /> -->
                                <PrimaryButton @click="submit">
                            Actualizar
                        </PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
