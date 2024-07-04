<template>
    <el-tabs v-model="activeName" class="lg:w-[100%] md:w-[100%] sm:w-[230%] w-[180%]" @tab-click="handleClick">
        <el-tab-pane  label="Inventarios" name="first">

            <div class="w-full py-1" v-for="(s, i) in product.stores">
                <div class="bg-red-600 text-white p-1 rounded-t-lg" id="header">
                    <span class="text-lg p-1 bold" > 
                        <el-button  :type="'info'" @click="submitStock(
                            {
                                store_id: s.id,
                                product_id: product.id,
                                name: product.name,
                                folio: product.folio,
                                description: product.Description,
                                counterProducts:s.stock.quantity,
                                price_list: product.price_list,
                                quantity: s.stock.quantity,
                                stock: (s.stock.id !== undefined) ? s.stock.id : null,

                            }, i
                        )" :disabled="s.buttonConfig.disabledButton"  size="small">{{s.buttonConfig.labelButton}}</el-button> {{ s.name }}
                    </span> 
                </div>
                <div class="bg-white rounded-b-lg border p-2 border-red-600 " id="body">
                    <el-input-number @change="handleChange(s, i)" v-model="s.stock.quantity" :min="s.stock.quantity" :precision="stockConfig.precision" :step="stockConfig.step" />
                </div>
            </div>

        </el-tab-pane>
        <el-tab-pane label="Ventas" name="second">Config</el-tab-pane>

    </el-tabs>
</template>
<style scoped>
.el-tabs__item.is-active {
    background-color: red;
}
</style>
<script lang="ts">
    import axios from 'axios';

    export default {
        name: 'StockIndicator',
        components: {  },
        props:{
            customRecord: Object,
            typeRecord: String
        },
        data() {
            return {
                stores: [],
                activeName: 'first',
                product: {},
                stockConfig: {
                    precision: 0,
                    step: 1
                }
            }
        },
        
        methods:{
            submitStock(productStock, index){
                console.log(productStock);
                axios.post(route('store.stock.product'), productStock).then((res) => {
                    console.log(res.data);
                    this.product.stores[index].buttonConfig.disabledButton = true;
                    this.product.stores[index].buttonConfig.labelButton = '...';
                }).catch((error) => { console.log(error); });
            },
            handleChange(s, product) {
                console.log(s)
                console.log(product)
                this.product.stores[product].buttonConfig.disabledButton = false;
                this.product.stores[product].buttonConfig.labelButton = 'Guardar';
            },
            handleClick(tab: TabsPaneContext, event: Event) {
                console.log(tab, event)
            },
            getStores(){
                axios.get(route('stores.index2')).then((res) => {
                    this.stores = res.data;

                    for (let index = 0; index < this.customRecord.stocks.length; index++) {
                        for (let o = 0; o < this.stores.length; o++) {
                            
                            if(this.stores[o].id == this.customRecord.stocks[index].store_id){
                                this.customRecord.stocks[index].quantity = parseFloat(this.customRecord.stocks[index].quantity);
                                this.stores[o].stock = this.customRecord.stocks[index]
                            }
                            this.stores[o].buttonConfig = {disabledButton: true, labelButton: '...'}
                            if(this.stores[o].stock == undefined)
                                this.stores[o].stock = {quantity: 0};
                        }
                    }
                    this.product = { ...this.customRecord, stores: this.stores };

                    if(this.product.take_portion)
                        this.stockConfig = { precision: 2, step: 0.1}
                    else
                        this.stockConfig = { precision: 0, step: 1 }
                    
                    console.log('StockIndicator', this.product);
                }).catch((error) => { console.log(error); });
            }  
        },
        mounted(){
            this.getStores();
            // console.log(this.customRecord);
        }
    }
</script>
