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
import Field from '@/Components/Field.vue';
import { ElNotification } from 'element-plus';
import { ElLoading } from 'element-plus';
 
export default{

    components:{
        AppLayout, FormSection, PrimaryButton, InputError, InputLabel, TextInput, SecondaryButton, ActionMessage, Field
    },
    props:{
        provider: Object

    },
    data(){

        return {
            form: {
                representative: '',
                description: '',
                phone: '',
                email: '',
                whatsapp: '',
                company: '',
                visit_day: '',
                created_by_id: '',
                edited_by_id: '',
                store_id: 3
            }
        }
    },
    methods:{
        submit(){
            this.$inertia.post(this.route('providers.store'), this.form);
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
                Detalle del proveedor
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <FormSection >
                    <template #title>
                        Crear Proveedor
                    </template>

                    <template #description>
                        Aquí puedes registrar un proveedor
                    </template>
                    <template #form>
                        <div class="col-span-8 sm:col-span-6">
                            <Field id="representative"      :label="'Representante de ventas'"  v-model="form.representative"       typeField="text"    :required="true"  />
                        </div>
                        <div class="col-span-8 sm:col-span-6">
                            <Field id="description"         :label="'Descripción'"              v-model="form.description"          typeField="text"    :required="true"  />
                        </div>
                        <div class="col-span-8 sm:col-span-6">
                            <Field id="phone"               :label="'Télefono'"                 v-model="form.phone"                typeField="text"    :required="true"  />
                        </div>
                        <div class="col-span-8 sm:col-span-6">
                            <Field id="email"               :label="'E-mail'"                   v-model="form.email"                typeField="text"    :required="true"  />
                        </div>
                        <div class="col-span-8 sm:col-span-6">
                            <Field id="whatsapp"            :label="'WhatsApp'"                 v-model="form.whatsapp"             typeField="text"    :required="true"  />
                        </div>
                        <div class="col-span-8 sm:col-span-6">
                            <Field id="company"             :label="'Compañia'"                 v-model="form.company"              typeField="text"    :required="true"  />
                        </div>
                        <div class="col-span-8 sm:col-span-6">
                            <Field id="visit_day"           :label="'Día de visita'"            v-model="form.visit_day"            typeField="text"    :required="true"  />
                        </div>

                        
                        
                        
                        
                        
                        

                    </template>
                    <template #actions>
                        <ActionMessage :on="true" class="me-3">
                            Saved.
                        </ActionMessage>
            <!-- :class="{ 'opacity-25': form.processing }" :disabled="form.processing" -->
                        <PrimaryButton @click="submit">
                            Crear proveedor
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>
    </AppLayout>
   
</template>
