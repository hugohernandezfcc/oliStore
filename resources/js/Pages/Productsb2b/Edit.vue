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
import { ArrowLeftBold } from '@element-plus/icons-vue';
export default{

    components:{
        AppLayout, ElNotification, FormSection, PrimaryButton, InputError, InputLabel, TextInput, SecondaryButton, ActionMessage, LookupField, Plus,
        ZoomIn,
        Download,
        Delete,
        ElLoading,
        ArrowLeftBold
    },
    props:{
        customRecord: Object,
        pricebookentries: Array
    },
    data(){

        return {
            autoFillBarCode: false,

            form: {
                name: this.customRecord.name,
                description: this.customRecord.description,
                unit_measure: this.customRecord.unit_measure,
                expiration_range: this.customRecord.expiration_range,
                public_description: this.customRecord.description,
                folio: this.customRecord.folio,
                is_public: this.customRecord.is_public,
                is_private: this.customRecord.is_private,
                image: this.customRecord.image,

            },
            dialogImageUrl: '',
            dialogVisible: false,
            disabled: false
        }
    },
    methods:{
        handleRemove(file) {
            console.log(file)
        },
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url
            this.dialogVisible = true
        },
        handleDownload(file) {
            console.log(file)
        },
        async uploadImage({ file }) {
            console.log(file)
            const formData = new FormData()
            formData.append('file', file)
            formData.append('name', file.name)
            try {
                const response = await axios.post('/b2b/productsb2b/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
                })
                console.log(response)
                file.url = response.data.url;
                this.form.image = response.data.url;
                console.log(file.url);
            } catch (error) {
                console.error('Error uploading image:', error)
            }
        },
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
            let localValidation = this.validForm();
            console.log(this.form);
            if(localValidation.isValid){
                this.$inertia.put(this.route('productsb2b.update', this.customRecord.id), this.form);
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
        console.log('componente montado', this.customRecord)
    }
}


</script> 

<template>
    <AppLayout title="Profile">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <PrimaryButton  class="mb-3 ml-3 lg:ml-0" @click="$inertia.visit(route('productsb2b.index'))"> 
                <el-icon><ArrowLeftBold /></el-icon>
            </PrimaryButton>&nbsp;Detalle del producto
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <FormSection >
                    <template #title>
                        Editar Producto
                    </template>

                    <template #description>
                        Aquí podrás Editar los productos B2B<br/>
                        <el-image style="width: 70px;" :src="customRecord.image" />
                    </template>
                    <template #form>
                        
                        <div class="col-span-3 ">

                            <InputLabel for="name" value="Nombre de producto" class="mb-6"/>
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="name"
                            />
                        </div>
                        <div class="col-span-3 ">
                            <el-switch v-model="autoFillBarCode" class="mb-2" active-text="Generar Código de barras" inactive-text=" "/>
                            <TextInput id="folio" :disabled="autoFillBarCode" v-model="form.folio" type="text" :class="(autoFillBarCode) ? 'mt-1 block w-full bg-gray-100' : 'mt-1 block w-full ' " autocomplete="folio"/>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="unit_measure" value="Unidad de medida" />
                            <el-select id="status" v-model="form.unit_measure" placeholder="Seleccionar tipo de unidad ..." class="mt-1 block w-full" size="large">
                                <el-option v-for="item in [
                                        { label: 'Sobre', value: 'Sobre' },
                                        { label: 'Bolsa', value: 'Bolsa' },
                                        { label: 'Lata', value: 'Lata' },
                                        { label: 'Caja', value: 'Caja' },
                                        { label: 'Botella ', value: 'Botella ' },
                                        { label: 'Granel', value: 'Granel' },
                                        { label: 'Botella retornable', value: 'Botella retornable' }
                                    ]" :key="item.value" :label="item.label" :value="item.value"/>
                            </el-select>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel for="expiry_date" value="Rango de caducidad" />
                            <el-select id="status" v-model="form.expiration_range" placeholder="Seleccionar tipo de unidad ..." class="mt-1 block w-full" size="large">
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
          
                        <div class="col-span-3 mb-2">
                            <InputLabel for="description" value="Descripción" />
                            <textarea
                                id="description"
                                v-model="form.description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                autocomplete="Description"
                            >
                            </textarea>
                            
                        </div>
                        <!-- <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="description" value="Categoría principal" />
                            <LookupField :firstLine="'name'" :secondLine="'description'"  ref="lookupCategory" :likeDataFx="{
                                fields: ['id', 'name', 'description'],
                                table: 'categories',
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
                                form.parent_id = newValue.id;
                            }"/>
                        </div> -->

                        <div class="col-span-6 sm:col-span-4">
                            Imagen de producto<br/>
                            
                            <el-upload action="#" list-type="picture-card" :http-request="uploadImage" :auto-upload="true">
                                <el-icon><Plus /></el-icon>

                                <template #file="{ file }">
                                <div>
                                    <img class="el-upload-list__item-thumbnail" :src="file.url" alt="" />
                                    <span class="el-upload-list__item-actions">
                                    <span
                                        class="el-upload-list__item-preview"
                                        @click="handlePictureCardPreview(file)"
                                    >
                                        <el-icon><zoom-in /></el-icon>
                                    </span>
                                    <span
                                        v-if="!disabled"
                                        class="el-upload-list__item-delete"
                                        @click="handleDownload(file)"
                                    >
                                        <el-icon><Download /></el-icon>
                                    </span>
                                    <span
                                        v-if="!disabled"
                                        class="el-upload-list__item-delete"
                                        @click="handleRemove(file)"
                                    >
                                        <el-icon><Delete /></el-icon>
                                    </span>
                                    </span>
                                </div>
                                </template>
                            </el-upload>
                            
                            <el-dialog v-model="dialogVisible">
                                <img w-full :src="dialogImageUrl" alt="Preview Image" />
                            </el-dialog>
                        </div>
                    </template>
                    

                    
                    <template #actions>
                        <inertia-link :href="route('categories.index')" class="m-1">
                            <el-button :type="'plain'" text bg class="m-2">
                                Cancelar 
                            </el-button>
                        </inertia-link>
                        <PrimaryButton @click="submit" class="m-2">
                            Guardar cambios
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