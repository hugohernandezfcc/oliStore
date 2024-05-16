<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import LookupField from '@/Components/LookupField.vue';


export default{
    components:{
        AppLayout, PrimaryButton, LookupField
    },
    props:{
        providers: Array,
        stores: Array,
    },
    methods: {
        loadOrders() {
            this.loading = true;
            setTimeout(() => {
                this.count += 2;
                this.loading = false;
            }, 2000);
        },

        addNewProvider(provider){
            this.providers.push(provider);
        },

        addNewOrderLine(line){
            let dataForm = {
                product: JSON.parse(JSON.stringify(line.product)),
                quantity: line.quantity,
                provider_id: line.provider_id
            }
            if(this.orderLines[line.provider_id] === undefined){
                this.orderLines[line.provider_id] = [];
            }

            this.orderLines[line.provider_id].push(dataForm);

            this.form.provider_id = null;
            this.form.product = null;
            this.form.quantity = 0;
            this.$refs.lookupProduct.clear();
            console.log(this.orderLines);
        },
        newPurchaseOrder(){
            this.orderSelected = true;
        },
        removeOrderLine(provider_id, index){
            this.orderLines[provider_id].splice(index, 1);
            if(this.orderLines[provider_id].length === 0){
                delete this.orderLines[provider_id];
            }
        }
    },
    data() {
        return {
            count: 10,
            loading: false,

            form: {
                provider_id: null,
                product: null,
                quantity: 0
            },
            store_id: null,
            orderLines: {},
            providersById: {},
            orderSelected: false
        }
    },
    mounted(){
        for(let i = 0; i < this.providers.length; i++){
            this.providersById[this.providers[i].id] = this.providers[i].company;
        }
        console.log(this.providersById)
        console.log(this.stores)
    },
    computed: {
        noMore() {
            return this.count >= 20;
        },
        disabled() {
            return this.loading || this.noMore;
        }
    },
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                OliStore
            </h2>
        </template>
        
        <div class="m-4">
            <h3 class="text-lg text-gray-900"> Listado de ordenes de compra </h3>
            <p class="text-sm text-gray">Todas las ordenes de compra podrán ser creadas y modificadas aquí </p>
        </div>
        <!-- <inertia-link :href="route('providers.create')" class="p-4 m-1"> 
            <PrimaryButton >
                Crear Proveedor 
            </PrimaryButton>
        </inertia-link>  -->

        <div class="flex flex-wrap-reverse shadow bg-white p-4 m-4 ">
            <div class="w-[100%] basis-4/4 md:basis-1/4 lg:basis-1/4 ">
                <div class="infinite-list-wrapper mt-5" style="overflow: auto">
                    <ul v-infinite-scroll="loadOrders" class="list w-full" :infinite-scroll-disabled="disabled" >
                        <li v-for="i in count" :key="i" class="list-item">
                            <div class="mx-4 my-4">
                                <PrimaryButton>
                                    Detalle
                                </PrimaryButton>
                            </div>
                            <div class="mx-4 my-2">
                                <span class="text-xs text-gray-500 font-bold underline">No. Orden</span><br/>
                                <span class="text-base font-bold">15698</span>
                            </div>
                            <div class="mx-4 my-2">
                                <span class="text-xs text-gray-500 font-bold underline">Estatus</span><br/>
                                <span class="text-base font-bold">Pendiente</span>
                            </div>
                            <div class="mx-4 my-2">
                                <span class="text-xs text-gray-500 font-bold underline">Total</span><br/>
                                <span class="text-base font-bold">$1000</span>
                            </div>
                        </li>
                    </ul>
                    <p v-if="loading">Loading...</p>
                    <p v-if="noMore">No more</p>
                </div>
            </div>
            <div class="basis-4/4 md:basis-3/4 lg:basis-3/4 "> 
                <div class="overflow-hidden shadow-xl sm:rounded-lg ml-2 mt-5">
<!-- ['Open', 'In_progress', 'Pre_sales', 'Sales', 'Order_complete', 'Order_incomplete']
['Pending', 'Approved', 'Rejected', 'Canceled', 'Completed'] -->
                    <div class="border-2 border-red-500">
                        <div v-if="orderSelected">
                            <div class="my-4 mx-2">
                                <el-breadcrumb separator=" >> " >
                                    <el-breadcrumb-item :to="{ path: '/' }" class="bg-red-200 p-2 ">homepage</el-breadcrumb-item>
                                    <el-breadcrumb-item>
                                        <a href="#" @click="console.log('huevos')">promotion management</a>
                                    </el-breadcrumb-item>
                                    <el-breadcrumb-item >promotion list</el-breadcrumb-item>
                                    <el-breadcrumb-item>promotion detail</el-breadcrumb-item>
                                </el-breadcrumb>
                            </div>
                            <div class="mx-4 my-2">
                                <h3 class="text-lg text-gray-900"> Orden de compra </h3>
                                <p class="text-sm text-gray">Detalles de la orden de compra</p>
                            </div>

                            <div class="form w-full">
                                <div class="flex flex-wrap">
                                    <div class="mx-4 my-2">
                                        <el-select v-model="form.provider_id" filterable placeholder="Provedor" class="w-full shadow-lg shadow-red-100 text-red-600 " style="width: 240px">
                                                <el-option v-for="item in providers"
                                                    :key="item.id"
                                                    :label="item.company"
                                                    :value="item.id"/>
                                        </el-select>
                                    </div>
                                    <div class="mx-4 my-2">
                                        
                                        <LookupField :firstLine="'name'" :secondLine="'Description'" :lastLine="'folio'" ref="lookupProduct" :likeDataFx="{
                                            fields: ['id', 'name', 'Description', 'folio', 'price_list'],
                                            table: 'products',
                                            limit: 50,
                                            orderBy: 'updated_at',
                                            order: 'asc',
                                            where: {
                                                field: 'name',
                                                operator: 'LIKE',
                                            }
                                        }" style="width: 240px"
                                        @updateValue="(newValue) => {
                                            form.product = newValue;
                                        }"/>
                                    </div>
                                    <div class="mx-4 my-2">
                                        <el-input v-model="form.quantity" ref="quantity" placeholder="# Cantidad" autofocus style="width: 240px"/>
                                        
                                    </div>
                                    <div class="mx-4 my-2">
                                        <PrimaryButton @click="addNewOrderLine(form)">
                                            agregar
                                        </PrimaryButton>
                                    </div>
                                </div>

                                

                                    
                                <div v-for="(lines, k) in orderLines" >
                                    <div class="mx-4 my-2">
                                        <span class="text-xs font-bold underline">Provedor</span><br/>
                                        <span> {{ providersById[k] }} </span>
                                    </div>
                                    <div v-for="(line, o) in lines">
                                        {{ o }}
                                        {{ k }}
                                        <div class="flex flex-wrap bg-red-200 m-4 ml-10" >
                                            <div class="my-4 mx-2">
                                                <PrimaryButton @click="removeOrderLine(k,o)">
                                                    <el-icon :size="20" :color="'#FFFFFF'" >
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                                                    </el-icon>
                                                </PrimaryButton>
                                            </div>
                                            <div class="my-2 mx-3 ">
                                                <span class="text-xs font-bold underline">Código de barras</span><br/>
                                                <span>{{ line.product.folio }}</span>
                                            </div>
                                            <div class="my-2 mx-3">
                                                <span class="text-xs font-bold underline">Cantidad</span><br/>
                                                <span class="text-base"># {{ line.quantity }}</span>
                                            </div>
                                            
                                            <div class="my-2 mx-4">
                                                <span class="text-xs font-bold underline">Precio de lista</span><br/>
                                                <span class="text-base">$ 100</span>
                                            </div>
                                            
                                            <div class="my-2 mx-5">
                                                <span class="text-xs font-bold underline">Total</span><br/>
                                                <span class="text-base">$1000</span>
                                            </div>
                                            
                                            <div class="my-2 ">
                                                <span class="text-xs font-bold underline">{{ line.product.Description }}</span><br/>
                                                <span>{{ line.product.name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                

                            </div>
                            <div class="footer">
                                
                            </div>
                        </div >
                        <el-empty v-else="orderSelected" description="No se ha seleccionado una órden de compra" >
                            <el-select v-model="store_id" filterable placeholder="Tienda" class="w-full shadow-lg shadow-red-100 text-red-600 " style="width: 240px">
                                    <el-option v-for="item in stores"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id"/>
                            </el-select><br /><br />
                            <PrimaryButton @click="newPurchaseOrder()">
                                Nueva Órden de compra
                            </PrimaryButton>
                        </el-empty>

                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
    
</template>
<style scoped>
.infinite-list-wrapper {
  height: 40rem;
  width: 100%;
}
.infinite-list-wrapper .list {
  padding: 0;
  margin: 0;
  list-style: none;
}

.infinite-list-wrapper .list-item {
  display: flex;
  align-items: center;
  height: 50px;
  background: var(--el-color-danger-light-9);
  color: var(--el-color-danger);
}
.infinite-list-wrapper .list-item + .list-item {
  margin-top: 10px;
}
</style>