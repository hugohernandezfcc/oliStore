<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ImporterWizard from '@/Components/ImporterWizard.vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        ImporterWizard
    },

    props:{
        productsb2b: Array,
        wizard: Array
    },
    methods:{
        changeStatus(id, status){
            console.log('cambio de estado', id, status);

            axios.post('/b2b/productsb2b/changestatus', {
                id: id,
                status: status
            }).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            });
        },
        changingCateogory(data){
            console.log('cambiando categoria', data)
            axios.post('/b2b/productsb2b/changing/cateogry', data).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            });
        },
        updateCategories(visible, recordId, categories){
            if(!visible){
                let preparingData = {id: recordId};
                for(let i = 0; i < categories.length; i++){
                    preparingData[categories[i]] = true;
                }
                for(let i = 0; i < this.virtualCategories.length; i++){
                    if(!categories.includes(this.virtualCategories[i].value)){
                        preparingData[this.virtualCategories[i].value] = false;
                    }
                }

                console.log(preparingData);
                this.changingCateogory(preparingData);
            }

        },
        deleteProduct(id){
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                axios.delete(route('productsb2b.destroy', id))
                .then(response => {
                    console.log(response.data);
                    this.$inertia.visit(route('productsb2b.index'));
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        updateOrder(id, order){
            console.log('cambio de orden', id, order);
            let preparingData = {id: id, order: order};
            axios.post('/b2b/productsb2b/changing/cateogry', preparingData).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            });
        },
        addPriceTo(id, name){
            this.productName = name;
            this.addPriceDialogVisible = true;
            this.productId = id;
        },
        handleClose(){
            axios.post(route('addprice'), {
                product_id: this.productId,
                cost: this.costLocal,
                price: this.priceLocal,
                activar: true
            }).then(response => {
                console.log(response.data);
                this.addPriceDialogVisible = false;
            }).catch(error => {
                console.log(error);
            });
        },
        navigationCategory(){
            this.loading = true;
            console.log('navigationCategory', this.activeTab);
            if(this.activeTab == 'correctionWizard'){
                axios.get(route('products.to.fix')).then(response => {
                    console.log(response.data);
                    this.productsb2bToFix = this.organizeCategories(response.data.productsb2bToFix);
                    this.loading = false;

                }).catch(error => {
                    console.log(error);
                    this.loading = false;
                });
            }


            //this.virtualProductsToFix = this.productsb2bToFix;
        },

        organizeCategories( virtualList){
            for(let i = 0; i < virtualList.length; i++){
                virtualList[i].virtualCategories = [];

                if(virtualList[i].bulkSale){
                    virtualList[i].virtualCategories.push('bulkSale')
                }

                if(virtualList[i].drinks){
                    virtualList[i].virtualCategories.push('drinks')
                }

                if(virtualList[i].snacks){
                    virtualList[i].virtualCategories.push('snacks')
                }

                if(virtualList[i].groceries){
                    virtualList[i].virtualCategories.push('groceries')
                }

                if(virtualList[i].cleaning){
                    virtualList[i].virtualCategories.push('cleaning')
                }

                if(virtualList[i].underFox){
                    virtualList[i].virtualCategories.push('underFox')
                }

                if(virtualList[i].cigars){
                    virtualList[i].virtualCategories.push('cigars')
                }

                if(virtualList[i].bimbo){
                    virtualList[i].virtualCategories.push('bimbo')
                }

                if(virtualList[i].marinela){
                    virtualList[i].virtualCategories.push('marinela')
                }

                if(virtualList[i].sabritas){
                    virtualList[i].virtualCategories.push('sabritas')
                }

                if(virtualList[i].barcel){
                    virtualList[i].virtualCategories.push('barcel')
                }

            }
            return virtualList;
        }
    },
    data(){
        return {
            loading: false,
            productsb2bToFix: [],
            productName: '',
            addPriceDialogVisible: false,
            costLocal: 0,
            priceLocal: 0,
            productId: null,
            activeTab: 'productList',
            rowCollectionSelected: new Array(),
            search:'',
            virtualProducts:[],
            virtualCategories:[
                {label: 'Granel', value: 'bulkSale'},
                {label: 'Bebidas', value: 'drinks'},
                {label: 'Botanas', value: 'snacks'},
                {label: 'Abarrotes', value: 'groceries'},
                {label: 'Limpieza', value: 'cleaning'},
                {label: 'Bajo zorro', value: 'underFox'},
                {label: 'Cigarros', value: 'cigars'},
                {label: 'Bimbo', value: 'bimbo'},
                {label: 'Marinela', value: 'marinela'},
                {label: 'Sabritas', value: 'sabritas'},
                {label: 'Barcel', value: 'barcel'}
            ]

        }
    },
    mounted(){
        this.virtualProducts = this.organizeCategories(this.productsb2b);
        console.log('mounted',this.productsb2b);
    },
    computed: {
        filterTableData() {
            return this.productsb2b.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        },
        filterTableDataToFix() {
            return this.productsb2bToFix.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        }
    }

}
</script>
<template>

    <el-dialog
    v-model="addPriceDialogVisible"
    title="Agregar precio"
    width="500"
  >
    <span class="text-xl text-red-600 font-bold">Proporciona el precio de <u>{{ productName }}</u></span>
    <br/>
    <br/>
    <div class="grid grid-cols-2 gap-4">
        <div class="text-center font-bold">Precio de lista
            <el-input-number v-model="costLocal" :min="0" :max="100" label="Costo"/>
        </div>
        <div class="text-center font-bold">Precio del cliente
            <el-input-number v-model="priceLocal" :min="0" :max="100" label="Precio de venta"/>
        </div>
    </div>
    <template #footer>
      <div class="dialog-footer">
        <el-button @click="addPriceDialogVisible = false">Cancelar</el-button>
        <el-button type="danger" @click="handleClose">
          Guardar precio
        </el-button>
      </div>
    </template>
  </el-dialog>

    <AppLayout title="Gastos">
        <template #header>
            <h3 class="text-lg text-gray-900"> Listado de productos - # {{ productsb2b.length }}</h3>
            <p class="text-sm text-gray">Catalogo de productos registrados </p>
        </template>



        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-2">
            <div class="flex flex-row m-1">
                <div class="basis-1/2">
                    <inertia-link :href="route('productsb2b.create')" class="m-1">
                        <PrimaryButton >
                            Nuevo Producto
                        </PrimaryButton>
                    </inertia-link>
                </div>
                <div class="basis-1/2">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </div>
            </div>

            <br/>

            <el-tabs v-model="activeTab" @tab-change="navigationCategory" class="demo-tabs" type="border-card">
                <el-tab-pane name="productList">
                    <template #label>
                        <span class="custom-tabs-label">
                            <span class="font-bold text-lg text-blue-600">Tabla de Productos</span>
                        </span>
                    </template>
                    <el-table :data="filterTableData" class="shadow-lg m-1" stripe  >
                        <el-table-column width="90" label="Editar" >
                            <template #default="scope" >
                                <a :href="route('productsb2b.edit', scope.row.id)" class="text-blue-500 hover:text-blue-600" >
                                    Editar
                                </a>
                            </template>
                        </el-table-column>
                        <el-table-column width="100" label="Eliminar" >
                            <template #default="scope" >
                                <el-button size="small" color="#dc2626" @click="deleteProduct(scope.row.id)" >
                                    X
                                </el-button>
                            </template>
                        </el-table-column>
                        <el-table-column align="left" width="385" label="Nombre producto" fixed="left">
                            <template #default="scope">
                                <a :href="route('productsb2b.show', scope.row.id)" class="text-blue-500 hover:text-blue-600" >
                                    <b>{{scope.row.name}}</b>
                                </a>
                            </template>
                        </el-table-column>
                        <el-table-column width="140" label="Publicar" >
                            <template #default="scope" >
                                <el-checkbox v-model="scope.row.is_public" @change="changeStatus(scope.row.id, scope.row.is_public)" :label="(scope.row.is_public) ? 'Publico' : 'Oculto'" />
                            </template>
                        </el-table-column>
                        <el-table-column width="280" label="Categorías" >
                            <template #default="scope" >
                                <el-select-v2
                                    v-model="scope.row.virtualCategories"
                                    :options="virtualCategories"
                                    placeholder="Seleccionar categorias"
                                    style="width: 240px"
                                    multiple
                                    clearable
                                    filterable
                                    @visible-change="updateCategories($event, scope.row.id, scope.row.virtualCategories)"
                                />
                            </template>
                        </el-table-column>
                        <el-table-column width="90" label="Orden" sortable >
                            <template #default="scope" >
                                <el-input v-model="scope.row.order" style="width: 50px" placeholder="Orden" @blur="updateOrder(scope.row.id, scope.row.order)" />
                            </template>
                        </el-table-column>
                        <el-table-column width="80" label="Precio" >
                            <template #default="scope" >
                                <el-button size="small" color="#dc2626" @click="addPriceTo(scope.row.id, scope.row.name)" >
                                    $$
                                </el-button>
                            </template>
                        </el-table-column>
                        <el-table-column width="180" label="Fecha de creación" sortable >
                            <template #default="scope" >
                                {{ scope.row.created_at }}
                            </template>
                        </el-table-column>

                    </el-table>
                </el-tab-pane>
                <el-tab-pane name="correctionWizard">
                    <template #label>
                        <span class="custom-tabs-label">
                            <span class="font-bold text-lg text-red-600">Productos a corregir</span>
                        </span>
                    </template>
                    <el-table :data="filterTableDataToFix" class="shadow-lg m-1" stripe v-loading="loading">
                        <el-table-column width="90" label="Editar" >
                            <template #default="scope" >
                                <a :href="route('productsb2b.edit', scope.row.id)" class="text-blue-500 hover:text-blue-600" >
                                    Editar
                                </a>
                            </template>
                        </el-table-column>
                        <el-table-column width="100" label="Eliminar" >
                            <template #default="scope" >
                                <el-button size="small" color="#dc2626" @click="deleteProduct(scope.row.id)" >
                                    X
                                </el-button>
                            </template>
                        </el-table-column>
                        <el-table-column align="left" width="385" label="Nombre producto" fixed="left">
                            <template #default="scope">
                                <a :href="route('productsb2b.show', scope.row.id)" class="text-blue-500 hover:text-blue-600" >
                                    <b>{{scope.row.name}}</b>
                                </a>
                            </template>
                        </el-table-column>
                        <el-table-column width="140" label="Publicar" >
                            <template #default="scope" >
                                <el-checkbox v-model="scope.row.is_public" @change="changeStatus(scope.row.id, scope.row.is_public)" :label="(scope.row.is_public) ? 'Publico' : 'Oculto'" />
                            </template>
                        </el-table-column>
                        <el-table-column width="280" label="Categorías" >
                            <template #default="scope" >
                                <el-select-v2
                                    v-model="scope.row.virtualCategories"
                                    :options="virtualCategories"
                                    placeholder="Seleccionar categorias"
                                    style="width: 240px"
                                    multiple
                                    clearable
                                    filterable
                                    @visible-change="updateCategories($event, scope.row.id, scope.row.virtualCategories)"
                                />
                            </template>
                        </el-table-column>
                        <el-table-column width="90" label="Orden" sortable >
                            <template #default="scope" >
                                <el-input v-model="scope.row.order" style="width: 50px" placeholder="Orden" @blur="updateOrder(scope.row.id, scope.row.order)" />
                            </template>
                        </el-table-column>
                        <el-table-column width="80" label="Precio" >
                            <template #default="scope" >
                                <el-button size="small" color="#dc2626" @click="addPriceTo(scope.row.id, scope.row.name)" >
                                    $$
                                </el-button>
                            </template>
                        </el-table-column>
                        <el-table-column width="180" label="Fecha de creación" sortable >
                            <template #default="scope" >
                                {{ scope.row.created_at }}
                            </template>
                        </el-table-column>

                    </el-table>
                </el-tab-pane>
                <el-tab-pane name="importerWizard">
                    <template #label>
                        <span class="custom-tabs-label">
                            <span class="font-bold text-lg text-green-600">Importar Productos</span>
                        </span>
                    </template>
                    <div class="bg-gray-50 p-4 border-gray-400 border-2 rounded-xl">
                        <ImporterWizard :modelFields="wizard" />
                    </div>
                </el-tab-pane>
            </el-tabs>

            <br/>

        </div>

    </AppLayout>
</template>
<style >
span.el-select-v2__tags-text{
    color: red !important;
}
</style>

