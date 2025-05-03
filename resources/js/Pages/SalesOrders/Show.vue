<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import FormSection      from '@/Components/FormSection.vue';
import RelatedList      from '@/Components/RelatedList.vue';
import RelatedListNative from '@/Components/RelatedListNative.vue';
import { ArrowLeftBold } from '@element-plus/icons-vue';
import SalesOrderGenerator from '@/Components/SalesOrderGenerator.vue';
import ProductsInOrder from '@/Components/ProductsInOrder.vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection,
        RelatedList,
        RelatedListNative,
        ArrowLeftBold, 
        SalesOrderGenerator,
        ProductsInOrder
    },
    name: 'SalesOrderShow',
    props:{
        customRecord: Object,
        created_at: String,
        updated_at: String,
        delivery_date: String,
    },

    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                axios.delete(route('salesorder.destroy', this.account.id)).then(response => {
                    console.log(response.data);
                    this.$inertia.visit(route('accounts.index'));
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        statusChange(status, recordId){
            console.log('statusChange', status, recordId)

            axios.get('/app/salesorder/'+status+'/'+recordId+'/status').then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            });
            
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
        console.log('SalesOrderShow', this.customRecord)

    },
    computed: {
        
    }

}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <PrimaryButton  class="mb-3 ml-3 lg:ml-0" @click="$inertia.visit(route('salesorder.index'))"> 
                <el-icon><ArrowLeftBold /></el-icon>
            </PrimaryButton>&nbsp;Detalle de la orden de compra
        </template>
        <div>
            <el-select id="status"  placeholder="Seleccionar  ..." clearable class="w-[90%] m-3 shadow-lg shadow-red-500/50" v-model="customRecord.status" @change="statusChange(encodeURIComponent(customRecord.status), customRecord.id)">
                <el-option v-for="item in [
                    {name: 'Abierta',       key: 'Abierta'},
                    {name: 'En progreso',   key: 'En progreso'},
                    {name: 'Cerrada entregada',       key: 'Cerrada entregada'},
                    {name: 'Cancelada',     key: 'Cancelada'}
                ]" :key="item.id" :label="item.name" :value="item.key"/>
            </el-select>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                <FormSection >

                    <template #title>
                        No. #{{customRecord.id}} de Orden de compra
                    </template>
                    <template #description>
                        <div class="bg-yellow-500 m-2 p-3 text-black font-bold rounded-md " >{{customRecord.note.split('//')[0]}}<br/><br/>{{customRecord.note.split('//')[1]}}</div>
                        

                    </template>
                    <template #details>
                        

                        <br/>
                        <el-descriptions title="Detalle de la orden" :column="1" border>
                            <el-descriptions-item label="Tienda" label-align="right" align="left" >
                                <a href="#" class="text-blue-500 hover:text-blue-700 font-bold" @click="$inertia.visit(route('accounts.show', customRecord.account.id))"> {{customRecord.account.name}} </a>
                            </el-descriptions-item>
                            <el-descriptions-item label="Dirección" label-align="right" align="left" >
                                <span > {{customRecord.account.address}} {{customRecord.account.city}} {{customRecord.account.state}}, {{customRecord.account.zip}}, {{customRecord.account.country}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Datos de contacto" label-align="right" align="left" >
                                <span > {{customRecord.account.phone}}<br/>{{customRecord.account.email}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="No. Productos" label-align="right" align="left" >
                                <span >#  {{customRecord.no_products}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Método de pago" label-align="right" align="left" >
                                <span > {{customRecord.payment_method}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Monto a Cobrar" label-align="right" align="left" >
                                <el-tag type="warning"><b>$ {{customRecord.total}} MXN</b></el-tag>
                            </el-descriptions-item>
                            <el-descriptions-item label="Estado de la orden" label-align="right" align="left" >
                                <el-tag type="success"><b>{{customRecord.status}}</b></el-tag>
                                
                            </el-descriptions-item>
                            
                        </el-descriptions>
                        <br/>
                        <el-descriptions title="Auditoria" :column="1" border>
                            <el-descriptions-item label="Actualizado por" label-align="right" align="left" >
                                <span > {{customRecord.updated_by.name}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Actualizado por" label-align="right" align="left" >
                                <span > {{customRecord.updated_by.name}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de entrega" label-align="right" align="left" >
                                <span class="bg-green-600 text-white p-3"><b>{{ delivery_date }}</b></span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de creación" label-align="right" align="left" >
                                <span >{{ created_at }}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Ultima actualización " label-align="right" align="left" >
                                <span >{{updated_at}}</span>
                            </el-descriptions-item>
                        </el-descriptions>
                       

                    </template>
                    <template #relatedlist>
                        <ProductsInOrder :products="customRecord.sales_order_items"/>

                    </template>
                    

                </FormSection>

                
            </div>
        </div>

    </AppLayout>
</template>
