<script >
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
 
export default{

    components:{
        AppLayout, FormSection, PrimaryButton, InputError, InputLabel, TextInput, SecondaryButton, ActionMessage
    },
    props:{
        product: Object

    },
    data(){

        return {
            form: {
                name: '',
                Description: '',
                unit_measure: '',
                price_list: '',
                price_customer: '',
                profit_percentage: '',
                expiry_date: ''
            }
        }
    },
    methods:{
        submit(){
            this.$inertia.post(this.route('products.store'), this.form);
        }
    },
    mounted() {
        console.log(import.meta.env.VITE_DB_CONNECTION)
    }
}



</script>

<template>
    <AppLayout title="Profile">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle del producto
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <FormSection @submitted="submit">
                    <template #title>
                        Crear Prodcuto
                    </template>

                    <template #description>
                        Aquí puedes registrar un producto
                    </template>
                    <template #form>
                        
                        
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
                            <InputLabel for="profit_percentage" value="Porcentaje de ganancía" />
                            <TextInput
                                id="profit_percentage"
                                v-model="form.profit_percentage"
                                type="number"
                                step="0.01"
                                class="mt-1 block w-full"
                                required
                                autocomplete="profit_percentage"
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

                    </template>
                    <template #actions>
                        <ActionMessage :on="true" class="me-3">
                            Saved.
                        </ActionMessage>
            <!-- :class="{ 'opacity-25': form.processing }" :disabled="form.processing" -->
                        <PrimaryButton @click="submit">
                            create
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>
    </AppLayout>
   
</template>
