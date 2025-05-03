<template>
    
    <el-tabs type="border-card" class="demo-tabs">
        <el-tab-pane v-for="(category, index) in productCategories" :key="index" :label="category.nameApi">
            <template #label>
                <span class="custom-tabs-label">
                    <span :class="category.color +' text-white rounded-full px-1'">
                        {{category.products.length}}
                    </span>
                    <span>&nbsp; {{category.name}}</span>
                </span>
            </template>
            <el-table :data="category.products" :table-layout="'fixed'" style="width: 100%">
                <el-table-column label="Nombre" width="150">
                    <template #default="scope">
                        <span class="text-xs">{{scope.row.name.split(' - ')[0]}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="Cant." width="70">
                    <template #default="scope">{{scope.row.quantity}}</template>
                </el-table-column>
                <el-table-column label="$" width="70">
                    <template #default="scope">${{scope.row.unit_price}}</template>
                </el-table-column>
                
                <el-table-column label=" " width="70">
                    <template #default="scope">
                        <el-popconfirm v-if="category.nameApi != 'deliveried'"  confirm-button-text="¡Listo!" cancel-button-text="Pendiente"
                                        icon-color="#626AEF" :title="labels[category.nameApi]" width="200"
                                        @confirm="confirmEvent(category.nameApi, scope.row.id)" @cancel="cancelEvent(scope.row.id)">
                            <template #reference>
                                <el-button size="small" type="primary" class="touch-manipulation">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" class="h-4 w-4 text-white " ><path fill="currentColor" d="M384 192v640l384-320.064z"></path></svg>
                                </el-button>
                            </template>
                        </el-popconfirm>
                        <svg v-if="category.nameApi == 'deliveried'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" style="color: green;" class="h-5 w-5  "><path fill="currentColor" d="M512 64a448 448 0 1 1 0 896 448 448 0 0 1 0-896m-55.808 536.384-99.52-99.584a38.4 38.4 0 1 0-54.336 54.336l126.72 126.72a38.272 38.272 0 0 0 54.336 0l262.4-262.464a38.4 38.4 0 1 0-54.272-54.336z"></path></svg>
                    </template>
                </el-table-column>
            </el-table>
                
        </el-tab-pane>
    </el-tabs>
  
    
</template>

<script>
    import {Delete} from '@element-plus/icons-vue';
    import { ElLoading } from 'element-plus';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';



    export default {
        name: 'ProductsInOrder',
        components: {
            PrimaryButton,
            SecondaryButton,
            ElLoading,
            InputError,
            InputLabel,
            TextInput,
            Delete
        },
        props: {
            products: Object
            
        },
        data() {
            return {
                labels: {
                    requested: 'Productos Verificados',
                    verified: 'Productos cargados',
                    loaded: 'Productos Entregados',
                    deliveried: 'Entregados'
                },
                productCategories:[
                    {
                        name: 'Solicitados',
                        nameApi: 'requested',
                        color: 'bg-blue-600',
                        products: []
                    },
                    {
                        name: 'Verificados',
                        nameApi: 'verified',
                        color: 'bg-orange-600',
                        products: []
                    },
                    {
                        name: 'Cargados',
                        nameApi: 'loaded',
                        color: 'bg-red-600',
                        products: []
                    },
                    {
                        name: 'Entregados',
                        nameApi: 'deliveried',
                        color: 'bg-green-600',
                        products: []
                    }
                ],
                readerOrders: [],
                loading: false,
                somethingChange: false
            }
        },
        methods:{

            confirmEvent(objApiName, productId){
                console.log('id product', productId);
                console.log('objApiName', objApiName);

                for (let i = 0; i < this.productCategories.length; i++) {
                    if(this.productCategories[i].nameApi == objApiName){
                        for (let j = 0; j < this.productCategories[i].products.length; j++) {
                            if(this.productCategories[i].products[j].id == productId){
                                this.productCategories[i].products[j][objApiName] = false;
                                this.productCategories[i].products[j][this.productCategories[i + 1].nameApi ] = true;
                                this.productCategories[i+1].products.push(this.productCategories[i].products[j]);
                                this.productCategories[i].products.splice(j, 1);
                                this.updateStatusProduct(this.productCategories[i + 1].nameApi, productId);
                                console.log('productCategories', this.productCategories);
                                break;
                            }
                        } 
                    }
                }
            },
            cancelEvent(productId){
                console.log('id product', productId);
            },
            updateStatusProduct(fieldApi, recordId){
                axios.get('/app/salesorder/'+fieldApi+'/'+recordId).then(response => {
                    console.log(response.data)

                }).catch(error => {
                    console.log(error)
                });
            },
            stageProducts() {
                for (let i = 0; i < this.products.length; i++) {
                    if(this.products[i].requested){
                        this.productCategories[0].products.push(this.products[i]);
                    }
                    if(this.products[i].verified){
                        this.productCategories[1].products.push(this.products[i]);
                    }
                    if(this.products[i].loaded){
                        this.productCategories[2].products.push(this.products[i]);
                    }
                    if(this.products[i].deliveried){
                        this.productCategories[3].products.push(this.products[i]);
                    }
                }
            }
        },
        mounted() {

            console.log('productInOrder: ', this.products);
            this.stageProducts();

        },
    }
</script>