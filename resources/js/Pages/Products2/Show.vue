<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import moment           from 'moment';
import FormSection      from '@/Components/FormSection.vue';
import StockIndicator   from '@/Components/StockIndicator.vue';
import { ArrowLeftBold } from '@element-plus/icons-vue';
export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection,
        StockIndicator,
        ArrowLeftBold
    },

    props:{
        customRecord: Object,

    },
    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('products.destroy', this.customRecord.id));
                setTimeout(() => {
                    this.$inertia.visit(route('products.index'));
                }, 1000);
            }
        },
    },
    data(){
        return {
            reportResults8: new Array(),
            search:'',
            date: new Date()
        }
    },
    mounted(){
        let globalResults = [];
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
                        {{customRecord.name}}
                    </template>
                    <template #description >
                        <div class="w-[70%]">
                            {{customRecord.Description}}
                        </div>
                        <br/>
                        <StockIndicator 
                            :customRecord="customRecord"
                            :typeRecord="'tesaosicos'" />
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
