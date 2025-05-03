<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import moment           from 'moment';
import FormSection      from '@/Components/FormSection.vue';
import StockIndicator   from '@/Components/StockIndicator.vue';
import { ArrowLeftBold } from '@element-plus/icons-vue';
import RelatedList      from '@/Components/RelatedList.vue';
import { Delete, Download, Plus, ZoomIn } from '@element-plus/icons-vue';
import { ElLoading } from 'element-plus';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection,
        StockIndicator,
        ArrowLeftBold,
        RelatedList,
        Plus,
        ZoomIn,
        Download,
        Delete,
        ElLoading
    },

    props:{
        customRecord: Object,
        relatedListConfig: Object,


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
            console.log(file.name)

            const loading = ElLoading.service({
                lock: true,
                text: 'Loading',
                background: 'rgba(0, 0, 0, 0.7)',
            }); 

            const formData = new FormData()
            formData.append('file', file)
            formData.append('name', file.name)
            formData.append('id', this.customRecord.id)
            try {
                

                const response = await axios.post('/upload/icon/prod', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
                })

                setTimeout(() => {
                        loading.close()
                        window.location.reload();
                    console.log('response', response.data)

                }, 4000);       
                
            } catch (error) {
                console.error('Error uploading image:', error)
                setTimeout(() => {
                    loading.close()
                }, 1000);
            }
        },
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('products.destroy', this.customRecord.id));
                setTimeout(() => {
                    this.$inertia.visit(route('products.index'));
                }, 1000);
            }
        },
        lineItems(){

            for (let i = 0; i < this.customRecord.line_any_items.length; i++) {
                console.log(this.customRecord.line_any_items[i]);

                if (!this.lineItemsObject[this.customRecord.line_any_items[i].type]) {
                    this.lineItemsObject[this.customRecord.line_any_items[i].type] = [];
                }
                

                this.lineItemsObject[this.customRecord.line_any_items[i].type].push(
                    this.customRecord.line_any_items[i][this.customRecord.line_any_items[i].target_id.replace('_id', '')]
                );
                
                this.lineItemsObject[this.customRecord.line_any_items[i].type][this.lineItemsObject[this.customRecord.line_any_items[i].type].length-1]['_id'] = this.customRecord.line_any_items[i].id;
                console.log('>>>>>>>>>>', this.lineItemsObject[this.customRecord.line_any_items[i].type][this.lineItemsObject[this.customRecord.line_any_items[i].type].length-1]);
            }

            console.log('this.lineItemsObject', this.lineItemsObject);
        }
    },
    data(){
        return {
            dialogImageUrl: '',
            dialogVisible: false,
            reportResults8: new Array(),
            search:'',
            date: new Date(),
            lineItemsObject: new Array()
        }
    },
    mounted(){
        let globalResults = [];
        this.lineItems();

        console.log('componente montado', this.customRecord)


    },
    computed: {
        filterTableData() {
            return this.customRecord.prices.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        }
    }

}
</script>
<style>
button {
    touch-action: manipulation !important;
}
</style>
<template>
    <AppLayout title="Dashboard">
        <template #header>

            <PrimaryButton  class="mb-3 ml-3 lg:ml-0" @click="$inertia.visit(route('products.index'))"> 
                <el-icon><ArrowLeftBold /></el-icon>
            </PrimaryButton>&nbsp;
            Detalle de producto 
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <inertia-link :href="route('products.edit', customRecord.id)">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0"> Editar producto </PrimaryButton>
                </inertia-link>

                <PrimaryButton @click="eliminar" class="mb-3 ml-3 lg:ml-1"> 
                    Eliminar <svg class="ml-1 -mt-0.5 h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                </PrimaryButton>

                <FormSection >

                    <template #title>
                        <center>
                            {{customRecord.name}}
                        </center>
                    </template>
                    <template #description >
<!--  -->               <StockIndicator  :customRecord="customRecord" :typeRecord="'tesaosicos'" /><br/>
                        <center>
                            <img v-if="customRecord.contains_icon == true" :src="customRecord.image" alt="image" class="w-[30%]"/>
                            <el-upload v-if="customRecord.contains_icon != true"  action="#" list-type="picture-card" :http-request="uploadImage" :auto-upload="true">
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
                            <div class="w-[70%]">
                                {{customRecord.Description}}
                            </div>
                        </center>
                        
                    </template>
                    <template #details>
                        <br/>
                        <div :class=" (customRecord.visible_product) ? 'bg-green-600 text-white text-center font-bold rounded-full' : 'bg-red-600 text-white text-center font-bold rounded-full' " >{{ (customRecord.visible_product) ? 'Producto visible' : 'Producto no visible' }}</div>
                        <el-descriptions :title="'Detalle del producto >> ' + customRecord.name" :column="1" border>
                            <el-descriptions-item label="Número de ventas" label-align="right" align="left" width="80px">
                                <span ># {{customRecord.product_line_items.length}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Dinero en ventas" label-align="right" align="left" width="80px">
                                <span >$ {{customRecord.product_line_items.length * customRecord.price_customer}} MXN</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Dinero en ventas precio de lista" label-align="right" align="left" width="80px">
                                <span >$ {{customRecord.product_line_items.length * customRecord.price_list}} MXN</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Dinero en diferencia" label-align="right" align="left" width="80px">
                                <span >$ {{(customRecord.product_line_items.length * customRecord.price_customer) - (customRecord.product_line_items.length * customRecord.price_list)}} MXN</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Diferencia unitaría de dinero" label-align="right" align="left" width="80px">
                                <span >$ {{(customRecord.price_customer) - (customRecord.price_list)}} MXN</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Rango de ventas" label-align="right" align="left" width="80px" v-if="customRecord.product_line_items.length != 0">
                                <span > Ventas del {{customRecord.product_line_items[0].created_at.split(' ')[0] +'  -  '+customRecord.product_line_items[customRecord.product_line_items.length-1].created_at.split(' ')[0] }}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Precio de lista" label-align="right" align="left" width="80px">
                                <el-tag type="info"><b >$ {{customRecord.price_list}} MXN</b></el-tag>
                            </el-descriptions-item>
                            <el-descriptions-item label="Precio cliente" label-align="right" align="left" width="80px">
                                <el-tag type="success"><b >$ {{customRecord.price_customer}} MXN</b></el-tag>
                            </el-descriptions-item>
                            <el-descriptions-item label="Código de barras" label-align="right" align="left" width="80px">
                                <div v-html="customRecord.bar_code" /> 
                                {{customRecord.folio}}
                            </el-descriptions-item>
                            <el-descriptions-item label="Método de venta" label-align="right" align="left" width="80px">
                                <el-tag :type="(customRecord.take_portion) ? 'info' : 'primary'"><b>{{(customRecord.take_portion) ? 'SE VENDE A GRANEL' : 'SE VENDE POR UNIDAD'}}</b></el-tag>
                            </el-descriptions-item>
                            <el-descriptions-item label="Tipo de unidad" label-align="right" align="left" width="80px">
                                <span >{{customRecord.unit_measure}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Porcentaje de ganancía" label-align="right" align="left" width="80px">
                                <span >{{customRecord.profit_percentage}} %</span>
                            </el-descriptions-item>
                        </el-descriptions>
                        
                        <br/>
                        <el-descriptions title="Fechas" :column="1" border>
                            <el-descriptions-item label="Fecha de creación" label-align="right" align="left" width="150px">
                                <span >{{customRecord.created_at}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de actualización" label-align="right" align="left" width="150px">
                                <span >{{customRecord.updated_at}}</span>
                            </el-descriptions-item>
                        </el-descriptions>

                        <br/>
                        <el-descriptions title="Auditoría de usuarios" :column="1" border>
                            <el-descriptions-item label="Creado por" label-align="right" align="left" width="150px">
                                <span >{{customRecord.created_by.name}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Editado por" label-align="right" align="left" width="150px">
                                <span >{{customRecord.edited_by.name}}</span>
                            </el-descriptions-item>
                        </el-descriptions>
                        
                    </template>

                    <template #relatedlist>
                        <div v-for="(c, i) in relatedListConfig">

                        <RelatedList 
                            :customRecordsRelated ="lineItemsObject[i]"
                            :title                ="c['title']"
                            :titleModel           ="c['titleModel']"
                            :visibleColumns       ="c['visibleColumns']"
                            :formFields           ="c['formFields']" 
                            :table                ="c['table']"
                            :origin               ="c['origin']"
                            :origin_field         ="c['origin_field']"
                            :target_field         ="c['target_field']"
                            :currentRecordId      ="c['currentRecordId']"
                            :searchIn             ="c['searchIn']"
                            :secondLine           ="c['secondLine']"
                            :lastLine             ="c['lastLine']"
                            classCard             ="'my-2 -pr-0 w-[470px]'" />
                        </div>

                        <h1>Historial de precios</h1>
                        <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl pb-3"/>

                        <el-table :data="filterTableData" class="shadow-lg" stripe style="height: 300px;" >

                            <el-table-column label="Precio de lista" width="130" >
                                <template #default="scope">
                                    <span>$ {{scope.row.price_list}} MXN</span>
                                </template>
                            </el-table-column>
                            <el-table-column label="Precio cliente" width="130" >
                                <template #default="scope">
                                    <span>$ {{scope.row.price_customer}} MXN</span>
                                </template>
                            </el-table-column>
                            <el-table-column  label="% Ganancia" width="150" >
                                <template #default="scope">
                                    <span>{{scope.row.porcentage_revenue}} %</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="description" label="Descripción" width="130" />
                            <el-table-column align="right" fixed="right" width="100">
                                <template #default="scope">
                                    <el-button @click="eliminarPrecio(scope.row.id) "
                                        size="small"
                                        >Eliminar</el-button>
                                </template>
                            </el-table-column>
                            <el-table-column align="right" fixed="right" width="100">
                                <template #default="scope">
                                    <el-button @click="activarPrecio(scope.row.id) "
                                        size="small"
                                        color="#dc2626"
                                        >Activar</el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </template>

                </FormSection>
            </div>
        </div>

    </AppLayout>
</template>
