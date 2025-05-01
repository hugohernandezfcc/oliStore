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
import LookupField from '@/Components/LookupField.vue';
import { Delete, Download, Plus, ZoomIn } from '@element-plus/icons-vue'
import { ElLoading } from 'element-plus';

export default{

    components:{
        AppLayout, ElNotification, FormSection, PrimaryButton, InputError, InputLabel, TextInput, SecondaryButton, ActionMessage, LookupField, Plus,
        ZoomIn,
        Download,
        Delete,
        ElLoading
    },
    props:{
        customRecord: Object   
    },
    data(){

        return {
            form: {
                name:'',
                email:'',
                firstname:'',
                lastname:'',
                phone:'',
                address:'',
                city:'',
                state:'',
                zip:'',
                country:''

            },
            loading: false,
        }
    },
    methods:{
        
        validForm(){
            const fields = [
                'name',
                'description',

            ];

            let fieldLabels = {
                'name': 'Nombre del producto',
                'description': 'Descripción',
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
            this.loading = true;
            let localValidation = this.validForm();
            console.log(this.form);
            if(localValidation.isValid){
                axios.post(this.route('accounts.store'), this.form).then(response => {
                    console.log(response);
                    this.$inertia.visit(route('accounts.show', response.data.account.id));
                    this.loading = false;
                }).catch(error => {
                    console.log(error);
                    this.loading = false;
                });
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
                Detalle del cliente
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8" v-loading="loading">
                <FormSection >
                    <template #title>
                        Crear registro de cliente
                    </template>

                    <template #description>
                        Aquí podrás registrar los detalles del cliente.
                    </template>
                    <template #form>
                        
                        <div class="col-span-6 ">
                            <InputLabel for="name" value="Nombre de la cuenta" class="mb-6"/>
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" autocomplete="name"/>
                        </div>
                        
                        <div class="col-span-6 "><br/>
                            <div class="bg-red-600 text-white text-center font-bold rounded-full " >Nombre de responsable</div>
                            <InputLabel for="firstname" value="Nombre del responsable" class="mt-1"/>
                            <TextInput id="firstname" v-model="form.firstname" type="text" class="mt-1 block w-full" autocomplete="firstname"/>
                            
                            <InputLabel for="lastname" value="Apellidos" class="mt-1"/>
                            <TextInput id="lastname" v-model="form.lastname" type="text" class="mt-1 block w-full" autocomplete="lastname"/>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="email" value="Correo Electrónico" class="mb-6"/>
                            <TextInput id="email" v-model="form.email" type="text" class="mt-1 block w-full" autocomplete="email"/>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="phone" value="Nombre de producto" class="mb-6"/>
                            <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" autocomplete="phone"/>
                        </div>
                        <div class="col-span-6 "><br/>
                            <div class="bg-red-600 text-white text-center font-bold rounded-full " >Dirección de la tienda</div>
                            <InputLabel for="address" value="Calle y número" class="mt-1"/>
                            <TextInput id="address" v-model="form.address" type="text" class="mt-1 block w-full" autocomplete="address"/>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="city" value="Ciudad" class="mb-6"/>
                            <TextInput id="city" v-model="form.city" type="text" class="mt-1 block w-full" autocomplete="city"/>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="state" value="Estado" class="mb-6"/>
                            <TextInput id="state" v-model="form.state" type="text" class="mt-1 block w-full" autocomplete="state"/>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="zip" value="Código postal" class="mb-6"/>
                            <TextInput id="zip" v-model="form.zip" type="text" class="mt-1 block w-full" autocomplete="zip"/>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="country" value="País" class="mb-6"/>
                            <TextInput id="country" v-model="form.country" type="text" class="mt-1 block w-full" autocomplete="country"/>
                        </div>
                        
                    </template>
                    
                   
                    
                    <template #actions>
                       
                        <inertia-link :href="route('accounts.index')" class="m-1">
                            <el-button :type="'plain'" text bg class="m-2">
                                Cancelar 
                            </el-button>
                        </inertia-link>
                        <PrimaryButton @click="submit" class="m-2">
                            Guardar cliente
                        </PrimaryButton>
                    </template>

                </FormSection>
            </div>
        </div>
    </AppLayout>
   
</template>
<style >
    div.el-autocomplete{ width: 100% !important; } input.el-input__inner{ margin: 5px; font-size:large }
    div.el-select{
        width: 100% !important;
    }
</style>