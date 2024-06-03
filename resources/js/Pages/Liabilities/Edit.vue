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
 
export default{

    components:{
        AppLayout, ElNotification, FormSection, PrimaryButton, InputError, InputLabel, TextInput, SecondaryButton, ActionMessage, LookupField
    },
    props:{
        customRecord: Object   
    },
    data(){

        return {
            form: {
                name: this.customRecord.name,
                description: this.customRecord.description,
                percentage: this.customRecord.percentage,
                financial_id: this.customRecord.financial.id,
                id: this.customRecord.id
            },
            financial_object: this.customRecord.financial
        }
    },
    methods:{
        validForm(){
            const fields = [
                'name',
                'description',
                'percentage',
                'financial_id'
            ];

            let fieldLabels = {
                'name': 'Concepto del gasto',
                'description': 'Porcentaje por corte',
                'percentage': 'Descripción',
                'financial_id': 'Periodo de financiamiento'
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
            console.log(this.form);
            if(localValidation.isValid){
                this.$inertia.put(this.route('liabilities.update', this.customRecord.id), this.form)
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
                Detalle de gasto
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <FormSection >
                    <template #title>
                        Editar Gasto
                    </template>

                    <template #description>
                        Aquí podrás registrar los gastos recurrentes para que por cada corte de caja puedas deducirlos de tus ingresos.
                    </template>
                    <template #form>
                        
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Concepto del gasto" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="name"
                            />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="percentage" value="Porcentaje por corte" />
                            <TextInput
                                id="percentage"
                                v-model="form.percentage"
                                type="number"
                                class="mt-1 block w-full"

                            />
                        </div>
                        
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="description" value="Descripción" />
                            <textarea
                                id="description"
                                v-model="form.description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                autocomplete="Description"
                            >
                            </textarea>
                            
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="description" value="Periodo de financiamiento" />
                            <LookupField v-model="financial_object.name" :firstLine="'name'" :secondLine="'description'"  ref="lookupProduct" :likeDataFx="{
                                fields: ['id', 'name', 'description', 'store_id'],
                                table: 'financial',
                                limit: 50,
                                orderBy: 'updated_at',
                                order: 'asc',
                                where: {
                                    field: 'name',
                                    operator: 'LIKE',
                                }
                            }" style="width: 240px"
                            @updateValue="(newValue) => {
                                console.log(newValue)
                                form.financial_id = newValue.id;
                            }"/>
                        </div>
                        
                    </template>
                    

                    
                    <template #actions>
                        <inertia-link :href="route('liabilities.index')" class="m-1">
                            <el-button :type="'plain'" text bg class="m-2">
                                Cancelar 
                            </el-button>
                        </inertia-link>
                        <PrimaryButton @click="submit" class="m-2">
                            Guardar gasto
                        </PrimaryButton>
                    </template>

                </FormSection>
            </div>
        </div>
    </AppLayout>
   
</template>
<style >
    div.el-autocomplete{ width: 100% !important; } input.el-input__inner{ margin: 5px; font-size:large }
    
</style>