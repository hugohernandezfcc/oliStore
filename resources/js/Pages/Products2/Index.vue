<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ElMessage } from 'element-plus';
import LookupField from  '@/Components/LookupField.vue';
import { ElLoading } from 'element-plus';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        ElMessage,
        LookupField
    },

    props:{
        products: Array 
    },
    methods:{
        redirectToCreate(){
            window.location.href = '/products/create';
        },
        handleClose(){
            this.dialog = false;
            this.temporalSearch = [];
        },
        openProvider(){
            this.dialog = true;
        },
        asignarProveedor(){

            this.loading2 = ElLoading.service({
                lock: true,
                text: 'Loading',
                background: 'rgba(255, 0, 0, 0.395)',
            })

            if(this.lookupProviderId == null){
                ElMessage({
                    showClose: true,
                    message: 'Selecciona un proveedor',
                    type: 'error',
                })
                this.loading2.close();
                return;
            }

            console.log('asignarProveedor', this.temporalSearch);
            let relationShips = [];
            for (let i = 0; i < this.temporalSearch.length; i++) {
                relationShips.push({
                    type: 'products_providers',
                    origin: 'products',
                    origin_field: 'product_id',
                    target: 'providers',
                    target_field: 'provider_id',
                    provider_id: this.lookupProviderId,
                    product_id:this.temporalSearch[i].id
                });
            }        

            axios.post(route('products.assign.providers'), {assignments: relationShips}).then((res) => {
                console.log(res);
                ElMessage({
                    showClose: true,
                    message: 'Proveedores asignados correctamente',
                    type: 'success',
                })
                this.dialog = false;
                this.temporalSearch = [];
                this.lookupProviderId = null;
                this.loading2.close();
            }).catch((error) => { console.log(error); });

            console.log(relationShips);
            
        }
    },
    data(){
        return {
            dialog:false,
            loading2:false,
            lookupProviderId: null,
            counterSearch:0,
            rowCollectionSelected: new Array(),
            search:'',
            localProducts: {},
            loading:false,
            temporalSearch: []
        }
    },
    mounted(){
        this.localProducts = this.products;
        console.log('mounted',this.products);
    },
    computed: {
        filterTableData() {
            
            let validateComidin = (this.search.indexOf('*') > -1) ? this.search.split('*')[0] : this.search;

            let beforeReturn = Object.values(this.localProducts).filter(
                (data) =>
                !validateComidin || JSON.stringify(data).toLowerCase().includes(validateComidin.toLowerCase() )
            );
            
            console.log('filterTableData', beforeReturn.length)

            let limit = (this.search.split('*').length == 2) ? 0 : beforeReturn.length;
            console.log('limit', limit);
            if(limit == 0 && this.search.length > 0)
                {
                    this.loading = true;

                    axios.post(route('search.product'), {search: validateComidin}).then((res) => {
                        console.log(res);
                        this.localProducts = {
                            ...this.localProducts,
                            ...res.data
                        };
                        this.loading = false;
                        this.counterSearch++;
                        this.search = this.search.split('*')[0];

                        if(this.counterSearch == 8 && limit == 0 && this.search != ''){
                            this.search = '';
                            ElMessage({
                                showClose: true,
                                message: 'No se encontraron resultados, busca de nuevo.',
                                type: 'info',
                            })
                        }

                    }).catch((error) => { console.log(error); });

                }
            else
                this.loading = false;


            if(this.search != '')
                this.temporalSearch = beforeReturn;
            else{
                this.temporalSearch = [];
            }
                
            
            console.log('beforeReturn', beforeReturn);

            return beforeReturn;
            
        },
    }

}
</script>

<template>
    <AppLayout title="Productos">
        <template #header>
            <h3 class="text-lg text-gray-900"> Listado de productos - # {{ localProducts.length }}</h3>
            <p class="text-sm text-gray">Catalogo de productos registrados </p>
        </template>
        
        

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-2">
            <div class="flex flex-row m-1">
                <div class="basis-1/2">
                    <el-dropdown type="danger" split-button @click="redirectToCreate" id="newProductButton">

                            Nuevo producto

                        <template #dropdown >
                            <el-dropdown-menu>
                                <el-dropdown-item @click="openProvider">Asignar proveedor</el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                    
                    
                </div>
                <div class="basis-1/2">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </div>
            </div>

            <br/>
            <el-table v-loading="loading" :data="filterTableData" height="600" class="shadow-lg m-1"  stripe :default-sort="{ prop: 'updated_at', order: 'descending' }" >
                <el-table-column align="left" width="70" fixed="left">
                    <template #default="scope">
                        <inertia-link :href="route('products.show', scope.row.id)" >
                            <el-button size="small" color="#dc2626"> 
                                <svg class="h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M512 160c320 0 512 352 512 352S832 864 512 864 0 512 0 512s192-352 512-352m0 64c-225.28 0-384.128 208.064-436.8 288 52.608 79.872 211.456 288 436.8 288 225.28 0 384.128-208.064 436.8-288-52.608-79.872-211.456-288-436.8-288zm0 64a224 224 0 1 1 0 448 224 224 0 0 1 0-448m0 64a160.192 160.192 0 0 0-160 160c0 88.192 71.744 160 160 160s160-71.808 160-160-71.744-160-160-160"></path></svg>
                            </el-button>
                        </inertia-link>
                    </template>
                </el-table-column>

                <el-table-column type="expand" sortable>
                    <template #default="props" >
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;{{ props.row.Description }} </p>
                    </template>
                </el-table-column>

                <el-table-column prop="name"            label="NOMBRE PRODUCTO"  width="200" sortable/>
                <el-table-column label="UNIDAD"     width="170" sortable>
                    <template #default="props" >    
                        <el-tag :type="(props.row.take_portion) ? 'error' : 'primary'"><b>{{(props.row.take_portion) ? 'SE VENDE A GRANEL' : 'SE VENDE POR UNIDAD'}}</b></el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="PRECIO LISTA"     width="170" sortable>
                    <template #default="props" >    
                        <p> ${{ props.row.price_list }} MXN </p>
                    </template>
                </el-table-column>
                <el-table-column label="PRECIO CLIENTE"     width="170" sortable>
                    <template #default="props" >    
                        <p> ${{ props.row.price_customer }} MXN </p>
                    </template>
                </el-table-column>
                <el-table-column prop="folio"      label="CODIGO DE BARRAS"     width="200" />
                <el-table-column prop="updated_at" label="ULTIMA ACTUALIZACIÓN" width="220" sortable/>
               
            </el-table>
            
            <br/>

        </div>

    </AppLayout>
<!-- <el-tag :type="(props.row.take_portion) ? 'info' : 'primary'"><b>{{(props.row.take_portion) ? 'SE VENDE A GRANEL' : 'SE VENDE POR UNIDAD'}}</b></el-tag> -->
    <el-drawer
        v-model="dialog"
        :title="'Asignar proveedor a : ' + temporalSearch.length + ' productos'"
        :before-close="handleClose"
        direction="btt"
        class=" border-red-600  rounded-3xl"
        size="80%"
    >
        <div class="demo-drawer__content ">
            

            <LookupField :firstLine="'company'" 
                        :secondLine="'representative'" 
                        :lastLine="'whatsapp'" 
                        ref="lookupProvider" 
                        :likeDataFx="{
                        fields:  ['id', 'representative', 'description', 'company'],
                        table: 'providers',
                        limit: 50,
                        orderBy: 'created_at',
                        order: 'asc',
                        where: {
                            field: 'company',
                            operator: 'LIKE',
                        }
                    }" style="width: 100%; border-width: 1px; border-color: rgb(107 114 128 / var(--tw-border-opacity)); border-radius: 3px;"
                    @updateValue="(newValue) => {
                        console.log(newValue)
                        this.lookupProviderId = newValue.id;
                    }"/>
            <span class="text-lg font-bold text-red-600">{{ temporalSearch.length }} productos seleccionados</span>
            <div class="bg-gray-600 text-white p-1 m-1 my-2 rounded-lg"  v-for="product in temporalSearch">
                <el-row :gutter="12">
                    <el-col xs="8" :sm="6" :md="4" :lg="3" :xl="1" class="border-t border-gray-500 -px-1"><b>Lista:</b> $ {{ product.price_list }} MXN</el-col>
                    <el-col xs="8" :sm="6" :md="4" :lg="3" :xl="1" class="border-t border-gray-500 -px-1"><b>Cliente:</b> $ {{ product.price_customer }} MXN</el-col>
                    <el-col xs="8" :sm="6" :md="7" :lg="6" :xl="1" class="border-t border-gray-500 -px-1 font-bold">{{product.Description}}</el-col>
                </el-row>
            </div>
            <el-row >
                <el-col :span="4" class="text-right">
                    <el-button @click="cancelAddProvider">Cancelar</el-button>
                </el-col>
                <el-col :span="12" class="text-right">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0"  v-loading.fullscreen.lock="fullscreenLoading" @click="asignarProveedor"> Asignar Proveedor </PrimaryButton>
                </el-col>
            </el-row>
        </div>
    </el-drawer>

</template>

<style>
li.el-dropdown-menu__item{
    background-color: #dc2626 !important;
    color: white !important;
}
.el-button--danger:hover,.el-button--danger:focus{
    background-color: rgb(231, 103, 103);
    border-color: rgb(231, 103, 103);
}
.el-button--danger{
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;

}
</style>