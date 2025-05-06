<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import FormSection      from '@/Components/FormSection.vue';
import RelatedList      from '@/Components/RelatedList.vue';
import RelatedListNative from '@/Components/RelatedListNative.vue';
import QuickEntries from '@/Components/QuickEntries.vue';
import { ArrowLeftBold } from '@element-plus/icons-vue';
import Referenceb2cElement from '@/Components/Referenceb2c.vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection,
        RelatedList,
        RelatedListNative,
        QuickEntries,
        ArrowLeftBold,
        Referenceb2cElement
    },
    name: 'ProductShow',
    props:{
        customRecord: Object,
        pricebookentries: Array,
        referenceb2c: Object
    },

    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                axios.delete(route('productsb2b.destroy', this.customRecord.id))
                .then(response => {
                    console.log(response.data);
                    this.$inertia.visit(route('productsb2b.index'));
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    },
    data(){
        return {
            lineItemsObject: new Array(),
            
            date: new Date()
        }
    },
    mounted(){
        let globalResults = [];
        console.log('componente montado', this.customRecord)
        console.log('componente montado', this.pricebookentries)
        console.log('componente montado', this.referenceb2c)
    },
    computed: {
        
    }

}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <PrimaryButton  class="mb-3 ml-3 lg:ml-0" @click="$inertia.visit(route('productsb2b.index'))"> 
                <el-icon><ArrowLeftBold /></el-icon>
            </PrimaryButton>&nbsp;Detalle del producto
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <inertia-link :href="route('productsb2b.edit', customRecord.id)">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0"> Editar Producto </PrimaryButton>
                </inertia-link>&nbsp;&nbsp;
                <PrimaryButton @click="eliminar" class="mb-3 ml-3 lg:ml-1"> 
                    Eliminar <svg class="ml-1 -mt-0.5 h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                </PrimaryButton>


                <FormSection >

                    <template #title>
                        {{customRecord.name}}
                    </template>
                    <template #description>

                        {{customRecord.description}}<br/>
                        Creador: {{customRecord.created_by.name}} 

                        

                    </template>
                    <template #details>

                        <br/>
                        <el-descriptions title="Detalle del producto" :column="1" border>
                            <el-descriptions-item label="Producto" label-align="right" align="left" >
                                <span >{{customRecord.name}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Descripción" label-align="right" align="left" >
                                <span >{{customRecord.description}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Imagen de producto" label-align="right" align="left" >
                                <el-image style="width: 50px;" :src="customRecord.image" />
                            </el-descriptions-item>
                            <el-descriptions-item label="Contenido del paquete " label-align="right" align="left" v-if="!customRecord.bulkSale" >
                                <span >{{customRecord.bundle}} unidades</span>
                            </el-descriptions-item>

                            <el-descriptions-item label="Actualizado por" label-align="right" align="left" >
                                <span > {{customRecord.edited_by.name}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de creación" label-align="right" align="left" >
                                <span >{{customRecord.created_at}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Ultima actualización " label-align="right" align="left" >
                                <span >{{customRecord.updated_at}}</span>
                            </el-descriptions-item>
                        </el-descriptions>
                        <br/>
                        
                        <Referenceb2cElement 
                        :cost="referenceb2c.price_list"
                        :price="referenceb2c.price_customer"
                        :porcentage="((referenceb2c.price_customer - referenceb2c.price_list) / referenceb2c.price_list) * 100"
                         v-if="referenceb2c != null"></Referenceb2cElement>

                    </template>
                    <template #relatedlist>

                        <QuickEntries :parentRecord="customRecord" :currentPriceBookEntries="pricebookentries" ></QuickEntries>

                    </template>
                    

                </FormSection>

                
            </div>
        </div>

    </AppLayout>
</template>
