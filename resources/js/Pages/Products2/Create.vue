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
 import { Delete, Download, Plus, ZoomIn } from '@element-plus/icons-vue';

export default{

    components:{
        AppLayout, ElNotification, FormSection, PrimaryButton, InputError, InputLabel, TextInput, SecondaryButton, ZoomIn, Download, Plus, Delete, ActionMessage
    },
    props:{
        product: Object

    },
    data(){

        return {
            autoFillBarCode: true,
            form: {
                
                name: '',
                Description: '',
                unit_measure: '',
                price_list: '',
                price_customer: '',
                profit_percentage: '',
                expiry_date_range: '',
                take_portion: false,
                is_public: false,
                is_private: false,
                public_description: '',
                public_name: '',
                image: '',
            },
            dialogImageUrl: '',
            dialogVisible: false,
            disabled: false,
            localFile: null
        }
    },
    methods:{
        handleRemove(file) {
            console.log(file)
        },
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            this.disabled = true;
            this.dialogVisible = true;
        },
        handleDownload(file) {
            console.log(file)
        },
        async uploadImage({ file }) {
            console.log(file)
            const formData = new FormData()
            formData.append('file', file)
            try {
                const response = await axios.post('/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
                })
                console.log(response)
                file.url = response.data.url;
                this.dialogImageUrl = response.data.url;
                this.form.image = response.data.url;
                this.localFile = file;
                console.log(file.url);
            } catch (error) {
                console.error('Error uploading image:', error)
            }
        },
        validForm(){
            const fields = [
                'name',
                'Description',
                'unit_measure',
                'price_list',
                'price_customer',
                'expiry_date_range',
            ];


            let fieldLabels = {
                'name' : 'Nombre del producto',
                'Description' : 'Descripción',
                'unit_measure' : 'Unidad de medida',
                'price_list' : 'Precio de lista',
                'price_customer' : 'Precio al cliente',
                'profit_percentage' : 'Porcentaje de ganancia',
                'expiry_date_range' : 'Rango de caducidad',
                'public_name' : 'Nombre del producto publico',
                'public_description' : 'Descripción del producto publico'
            };

            if(this.form.is_public){
                fields.push('public_name');
                fields.push('public_description');

            }

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
                this.form.take_portion = (this.form.unit_measure == 'Granel') ? true : false;
                console.log(this.form);
                this.form.is_private = !this.form.is_public;
                this.$inertia.post(this.route('products.store'), this.form);
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
                        
                        
                        <div class="col-span-3 ">
                            <InputLabel class="py-2" for="name" value="Nombre del producto" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" autocomplete="name"/>
                        </div>
                        <div class="col-span-3 ">
                            <el-switch v-model="autoFillBarCode" class="mb-1" active-text="Generar Código de barras" inactive-text=" "/>
                            <TextInput id="folio" :disabled="autoFillBarCode" v-model="form.folio" type="text" :class="(autoFillBarCode) ? 'mt-1 block w-full bg-gray-100' : 'mt-1 block w-full ' " autocomplete="folio"/>
                        </div>
                        <div class="col-span-6">
                            <InputLabel for="Description" value="Descripción" />
                            <textarea id="Description" v-model="form.Description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="Description" >
                            </textarea>
                        </div>
                        <div class="col-span-3">
                            <InputLabel for="unit_measure" value="Unidad de medida" />
                            <el-select id="status" v-model="form.unit_measure" placeholder="Seleccionar tipo de unidad ..." class="mt-1 block w-full" size="large">
                                <el-option v-for="item in [
                                        { label: 'Sobre', value: 'Sobre' },
                                        { label: 'Lata', value: 'Lata' },
                                        { label: 'Botella ', value: 'Botella ' },
                                        { label: 'Granel', value: 'Granel' },
                                        { label: 'Botella retornable', value: 'Botella retornable' }
                                    ]" :key="item.value" :label="item.label" :value="item.value"/>
                            </el-select>
                        </div>
                        <div class="col-span-3">
                            <InputLabel for="expiry_date" value="Rango de caducidad" />
                            <el-select id="status" v-model="form.expiry_date_range" placeholder="Seleccionar tipo de unidad ..." class="mt-1 block w-full" size="large">
                                <el-option
                                    v-for="item in [
                                        { label: '1 Semana', value: '1_week' },
                                        { label: '2 Semanas', value: '2_weeks' },
                                        { label: '3 Semanas', value: '3_weeks ' },
                                        { label: '1 mes', value: '1_month' },
                                        { label: '2 meses', value: '2_month' },
                                        { label: 'Igual o mayor a 3 meses', value: 'major_equals_3_months' }
                                    ]" :key="item.value" :label="item.label" :value="item.value"/>
                            </el-select>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="price_list" value="Precio de lista" />
                            <TextInput id="price_list" v-model="form.price_list" type="number" step="0.01" class="mt-1 block w-full" autocomplete="price_list"/>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="price_customer" value="Precio al cliente" />
                            <TextInput id="price_customer" v-model="form.price_customer" type="number" step="0.01" class="mt-1 block w-full" autocomplete="price_customer"/>
                        </div>
                        <div class="col-span-8 sm:col-span-4">
                            <el-switch v-model="form.is_public" class="mb-2" active-text="Producto Público" inactive-text=" "/><el-switch v-model="form.visible_product" class="mb-2" active-text="Producto visible" inactive-text=" "/>
                        </div>

                        <div class="col-span-6 " v-if="form.is_public">
                            Este panel aparecerá en la aplicación móvil, asegúrate de que la información sea correcta.
                            <article class="text-wrap border border-red-400  rounded-lg mx-2 my-1 px-2 py-1">
                                <div class="grid grid-rows-3 grid-flow-col ">
                                    <div class=" row-span-4  pl-2">
                                        <center>
                                            <el-upload action="#" :style="(dialogImageUrl == '') ? 'display: block;': 'display: none;'" list-type="picture-card" multiple="false" limit="1" :http-request="uploadImage" :auto-upload="true">
                                                <el-icon><Plus /></el-icon>
                                            </el-upload>
                                            <img class="el-upload-list__item-thumbnail" :src="dialogImageUrl" alt="" width="128"/>
                                        </center>
                                    <el-dialog v-model="dialogVisible">
                                        <img w-full :src="dialogImageUrl" alt="Preview Image" />
                                    </el-dialog>
                                    </div>
                                    
                                    <div class=" col-span-3 ">
                                        <div class="flex flex-wrap my-2 ">
                                            <div class="w-3/1 my-2"><span class="text-lg font-bold text-green-600 ">$ {{ form.price_customer }} MXN</span> &nbsp;&nbsp;</div>
                                            <div class="w-3/2 "><TextInput id="name" v-model="form.public_name" type="text" class="my-1 " autocomplete="name"/></div>
                                        </div>
                                    </div>
                                    
                                    <div class=" col-span-3 ">
                                        <textarea id="Description" v-model="form.public_description" class=" block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="Description" ></textarea>
                                    </div>
                                    
                                </div>
                            </article>    
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
