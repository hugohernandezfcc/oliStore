<script >
import { ref } from 'vue';
import { ElNotification } from 'element-plus';
import AppLayout from '@/Layouts/AppLayout.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Field from '@/Components/Field.vue';


export default{

    components:{
        AppLayout, ElNotification, FormSection, PrimaryButton, InputError, InputLabel, TextInput, SecondaryButton,  ActionMessage, Field
    },
    props:{
        provider: Object

    },
    data(){

        return {
            form: {
                id: this.provider.id,
                representative: this.provider.representative,
                description: this.provider.description,
                phone: this.provider.phone,
                email: this.provider.email,
                whatsapp: this.provider.whatsapp,
                company: this.provider.company,
                visit_day: this.provider.visit_day,
            }
        }
    },
    methods:{
        
        validForm(){
            const fields = [
                'representative',
                'description',
                'phone',
                'email',
                'whatsapp',
                'company'
            ];


            let fieldLabels = {
                'representative' : 'Representante',
                'description' : 'Descripción',
                'phone' : 'Teléfono',
                'email' : 'Email',
                'whatsapp' : 'Whatsapp',
                'company' : 'Compañía'
            };

            

            for (let field of fields) {
                if (this.form[field] === null || this.form[field] === '') {
                    return {
                        isValid: false,
                        errorMessage: 'Verifica el valor de ' + fieldLabels[field]
                    };
                }
            }

            return {
                isValid: true,
                errorMessage: 'ok'
            };

        },
        submit(){
            let localValidation = this.validForm();
            if(localValidation.isValid){
                console.log(this.form);
                this.$inertia.put(this.route('providers.update', this.provider.id), this.form)
                setTimeout(() => {
                    window.location.href = '/providers/' + this.provider.id;
                }, 500);
            }else{
                ElNotification.warning({
                    title: 'warning',
                    message: localValidation.errorMessage,
                    offset: 100,
                });
            }
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
                <FormSection >
                    <template #title>
                        Crear Prodcuto
                    </template>
                    <template #description>
                        Detalla el producto con precios, nombres y descripciones
                        
                    </template>

                    <template #form>
                        
                        
                        <div class="col-span-8 sm:col-span-6" >
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
                        <PrimaryButton @click="submit">
                            create
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>
    </AppLayout>
   
</template>
