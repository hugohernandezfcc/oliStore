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
                description: '',
                object: '',
                fields: '',
                criterials: '',
            }
        }
    },
    methods:{
        submit(){
            this.$inertia.post(this.route('reports.store'), this.form);
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
                Detalle del reporte
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <FormSection >
                    <template #title>
                        Crear reporte
                    </template>

                    <template #description>
                        Aquí puedes registrar los reportes que quieras ejecutar
                    </template>
                    <template #form>
                        
                        
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Nombre del producto" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                
                                autocomplete="name"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="Description" value="Descripción" />
                            <textarea
                                id="Description"
                                v-model="form.description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                
                                autocomplete="Description"
                            >

                            </textarea>
                            
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Objeto a reportear" />
                            <TextInput
                                id="name"
                                v-model="form.object"
                                type="text"
                                class="mt-1 block w-full"
                                
                                autocomplete="name"
                            />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="fields" value="Campos" />
                            <textarea
                                id="fields"
                                v-model="form.fields"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                
                                autocomplete="fields"
                            >

                            </textarea>
                            
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="criterials" value="Criterios" />
                            <textarea
                                id="criterials"
                                v-model="form.criterials"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                
                                autocomplete="Description"
                            >

                            </textarea>
                            
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
