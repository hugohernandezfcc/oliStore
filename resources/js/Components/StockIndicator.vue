<template>
    <el-tabs v-model="activeName" class="w-full" @tab-click="handleClick">
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
                    <el-input-number @change="handleChange(s, i)" v-model="s.stock.quantity" :min="0" :precision="stockConfig.precision" :step="stockConfig.step" />
                </div>
            </div>

        </el-tab-pane>
        <el-tab-pane label="Ventas" name="second">
            <el-tree
                style="max-width: 600px"
                :data="salesTree"
                :props="defaultProps"
                @node-click="handleNodeClick"
            />
        </el-tab-pane>

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
                },
                renderSalesTree : false,
                defaultProps : {
                    children: 'children',
                    label: 'label',
                },
                salesTree: []
            }
        },
        
        methods:{
            handleNodeClick(data) {
                console.log(data);
            },
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

                    for (let o = 0; o < this.stores.length; o++) {
                        this.stores[o].buttonConfig = {disabledButton: true, labelButton: '...'}
                        for (let index = 0; index < this.customRecord.stocks.length; index++) {

                            if(this.stores[o].id == this.customRecord.stocks[index].store_id){
                                this.customRecord.stocks[index].quantity = parseFloat(this.customRecord.stocks[index].quantity);
                                this.stores[o].stock = this.customRecord.stocks[index];
                            }
                            
                        }
                        if(this.stores[o].stock == undefined)
                            this.stores[o].stock = {quantity: 0};
                    }
                    this.product = { ...this.customRecord, stores: this.stores };

                    if(this.product.take_portion)
                        this.stockConfig = { precision: 3, step: 0.001}
                    else
                        this.stockConfig = { precision: 0, step: 1 }
                    
                    console.log('StockIndicator', this.product);
                }).catch((error) => { console.log(error); });
            }  
        },
        mounted(){
            this.getStores();

            if(this.customRecord.product_line_items.length > 0)
                this.renderSalesTree = true;

                let years = {};
                let months = {
                    '01': 'Enero',
                    '02': 'Febrero',
                    '03': 'Marzo',
                    '04': 'Abril',
                    '05': 'Mayo',
                    '06': 'Junio',
                    '07': 'Julio',
                    '08': 'Agosto',
                    '09': 'Septiembre',
                    '10': 'Octubre',
                    '11': 'Noviembre',
                    '12': 'Diciembre'
                }
            
                // this.customRecord.product_line_items.forEach((element) => {
                //     years[element.created_at.split(' ')[0].split('-')[0]] = element.created_at.split(' ')[0].split('-')[0];
                // });

                this.customRecord.product_line_items.forEach((element) => {
                    if(years[element.created_at.split(' ')[0].split('-')[0]] == undefined)
                        years[element.created_at.split(' ')[0].split('-')[0]] = [];

                    if(years[element.created_at.split(' ')[0].split('-')[0]][months[element.created_at.split(' ')[0].split('-')[1]]] == undefined)
                        years[element.created_at.split(' ')[0].split('-')[0]][months[element.created_at.split(' ')[0].split('-')[1]]] = 0;

                    years[element.created_at.split(' ')[0].split('-')[0]][months[element.created_at.split(' ')[0].split('-')[1]]]++;
                });


                console.log(years);

                Object.keys(years).forEach(
                    year => {
                        console.log(Object.values(years[year]));
                        this.salesTree.push({
                            label: year,
                            children: Object.keys(years[year]).map(
                                month => {
                                    return {
                                        label: month + ' (' + years[year][month] + ')',
                                        children: [
                                            {
                                                label: 'Ventas: $' + (years[year][month]*parseFloat(this.customRecord.price_customer)) 
                                            }
                                        ]
                                    }
                                }
                            )
                        });
                });


            console.log(this.salesTree);
        }
    }
</script>
