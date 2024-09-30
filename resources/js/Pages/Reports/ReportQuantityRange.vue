<script >
import AppLayout from '@/Layouts/AppLayout.vue';


import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Footer from '@/Components/Footer.vue';
import wizardForm from '@/Components/TicketForm.vue';
import moment from 'moment';
import Doughnut from '@/Components/Doughnut.vue';

import axios from 'axios';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        wizardForm,
        Doughnut,
        moment
    },

    props:{
        report: Object,
        filters: Array,
        reportResults: Array,
        storesBackEnd: Array
    },
    methods:{
        formatTooltip(value) {
            return `Diferencia de ${30-value} días `;  // Adding '%' symbol to the tooltip value
        },
        rangeChange(e){
            
            const today = new Date();
            console.log(e)
            const daysToSubtract1 = (30-e[1]);
            const daysToSubtract2 = (30-e[0]);
            console.log(daysToSubtract1)
            console.log(daysToSubtract2)


            const start = new Date(today);
            start.setDate(today.getDate() - daysToSubtract1);
            console.log(moment(start).format('L'))


            const end = new Date(today);
            end.setDate(today.getDate() - daysToSubtract2);
            console.log(moment(end).format('L'))

            this.range.start = moment(end).format('L');
            this.range.end = moment(start).format('L');
            this.disabledExecuteReport = false;
        },

        executeReport(){
            console.log('Ejecutando reporte')
            console.log(this.range)
            console.log(this.store)
            console.log(this.unitType)
            this.disabledExecuteReport = true;
            this.profit = 0;
            this.plist = 0;
            this.cprice = 0;
            axios.post(route('reports.quantity.data'),{
                start: this.range.start,
                end: this.range.end,
                store: this.store,
                unitType: this.unitType
            }).then(response => {
                console.log(response.data)
                this.datatableRecords = [];
                for (const producto in response.data.reportResults.records){
                    if(producto.name != 'RECARGA DE SALDO - RECARGA DE SALDO'){
                        console.log(response.data.reportResults.records[producto][0].take_portion)
                        if(response.data.reportResults.records[producto][0].take_portion == this.unitType || this.unitType == 'all'){

                            this.datatableRecords.push({
                                name: response.data.reportResults.records[producto][0].product,
                                cantidad: (response.data.reportResults.records[producto][0].take_portion) ? this.getPortion(response.data.reportResults.records[producto]) + 'kg.' : response.data.reportResults.records[producto].length + ' ud.',
                                quantity: (response.data.reportResults.records[producto][0].take_portion) ? this.getPortion(response.data.reportResults.records[producto])  : response.data.reportResults.records[producto].length,
                                precioCliente: response.data.reportResults.records[producto][0].price,
                                preciolista: response.data.reportResults.records[producto][0].priceList,
                                price: response.data.reportResults.records[producto][0].price,
                                priceList: response.data.reportResults.records[producto][0].priceList
                            });
                            
                            this.profit += this.getProfit(response.data.reportResults.records[producto]);
                            this.plist += this.getPriceList(response.data.reportResults.records[producto]);
                            this.cprice += this.getPrecioCliente(response.data.reportResults.records[producto]);
                        }
                    }
                    
                }

            }).catch(error => {
                console.log(error)
            });
        },

        getPortion(arrayProducts){
            let portion = 0;
            for (const product in arrayProducts){
                portion += arrayProducts[product].quantity;
            }
            return (portion/1000).toFixed(3);
        },
        getProfit2(element){
            return element.price-element.priceList;
        },
        getProfit(arrayProducts){
            let profit = 0;
            for (const product in arrayProducts){
                profit += (arrayProducts[product].price-arrayProducts[product].priceList);
            }
            return profit;
        },

        getPriceList(arrayProducts){
            let priceList = 0;
            for (const product in arrayProducts){
                priceList += arrayProducts[product].priceList;
            }
            return priceList;
        },
        getPrecioCliente(arrayProducts){
            let precioCliente = 0;
            for (const product in arrayProducts){
                precioCliente += arrayProducts[product].price;
            }
            return precioCliente;
        },
        enableExecuteReport(){
            this.disabledExecuteReport = false;
        }
    },
    data(){
        return {
            range: {
                start: moment(new Date().setDate(new Date().getDate() - 10 ) ).format('L'),
                end: moment(new Date()).format('L')
            },
            search:'',
            days: [20, 30],
            marks: {
                0: '0',
                10: '10',
                20: '20',
                30: 'HOY'
            },
            datatable: [],

            stores: [],
            profit: 0,
            cprice: 0,
            plist: 0,
            store: 'all',
            stores:[],
            unitType: 'all',
            unitsType:[
                {value: false,  label: 'Ventar por unidad'},
                {value: true, label: 'Venta a granel'},
                {value: 'all', label: 'Ambos tipos de venta'}
            ],
            datatableRecords: [],
            disabledExecuteReport: true
        }
    },
    mounted(){
        this.stores = this.storesBackEnd;
        this.stores.push({value: 'all', label: 'Todas las tiendas'});

        for (const producto in this.reportResults.records){
            
            this.datatableRecords.push({
                name: this.reportResults.records[producto][0].product,
                cantidad: (this.reportResults.records[producto][0].take_portion) ? this.getPortion(this.reportResults.records[producto]) + 'kg.' : this.reportResults.records[producto].length + ' ud.',
                quantity: (this.reportResults.records[producto][0].take_portion) ? this.getPortion(this.reportResults.records[producto])  : this.reportResults.records[producto].length,
                precioCliente: this.reportResults.records[producto][0].price,
                preciolista: this.reportResults.records[producto][0].priceList,
                price: this.reportResults.records[producto][0].price,
                priceList: this.reportResults.records[producto][0].priceList
            });
            this.profit += this.getProfit(this.reportResults.records[producto]);
            this.plist += this.getPriceList(this.reportResults.records[producto]);
            this.cprice += this.getPrecioCliente(this.reportResults.records[producto]);
        }
        console.log(this.datatableRecords);

    },
    computed: {
        filterTableData() {
          

            let beforeReturn = Object.values(this.datatableRecords).filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
            console.log(beforeReturn)
           
            return beforeReturn;            
        }
    }

}
</script>
<template>
    <AppLayout title="Dashboard">
        <template #header>
            Reporte de ventas por unidades / Semanal
        </template>
        
       
        <div class="m-8">
            <el-button type="danger" class="mb-3 ml-3 lg:ml-1 bg-red-600" @click="executeReport" :disabled="disabledExecuteReport">
                Ejecutar reporte <svg class="ml-1 -mt-0.5 h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M384 192v640l384-320.064z"></path></svg>
            </el-button>
            
            <div class="grid grid-rows-5 grid-flow-col gap-4 font-mono text-white text-sm font-bold leading-6 bg-stripes-fuchsia rounded-lg">
                <div class="p-3 rounded-lg shadow-lg bg-gray-500 grid col-span-1 row-span-6 ">
                    <!-- <div class="w-5/12 h-48 justify-self-center bg-white rounded-lg  ">
                        <Doughnut  :labels="[ 'Red', 'Blue']" 
                                :datasets="[
                                        {
                                        backgroundColor: ['#FF6633', '#FFB399'],
                                        data: [ 4, 5]
                                    }
                                ]"/>
                    </div> -->

                    <el-select
                            v-model="store"
                            placeholder="Tienda"
                            size="large"
                            @change="enableExecuteReport"
                            >
                            <el-option
                                v-for="item in stores"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value"
                            />
                            </el-select>

                            <el-select
                            v-model="unitType"
                            placeholder="Tipo de venta"
                            size="large"
                            @change="enableExecuteReport"
                            >
                            <el-option
                                v-for="item in unitsType"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value"
                            /></el-select>
                            <div class="p-2  rounded-lg h-10 items-center  bg-red-600 shadow-lg">
                                {{range.start}} - {{range.end}} 
                            </div>
                            <div class="p-2  rounded-lg h-10 items-center  bg-red-500 shadow-lg">
                                Profit: ${{profit.toFixed(2)}} MXN
                            </div>
                            <div class="p-2  rounded-lg h-10 items-center  bg-red-400 shadow-lg">
                                P. Lista: ${{plist.toFixed(2)}} MXN
                            </div>
                            <div class="p-2  rounded-lg h-10 items-center  bg-red-300 shadow-lg">
                                P. Cliente: ${{ cprice.toFixed(2) }} MXN
                            </div>
                            
                            
                    
                </div>
                <div class="rounded-lg bg-gray-300 grid row-span-1 col-span-3 ">
                    
                    <el-slider v-model="days" :format-tooltip="formatTooltip" range show-stops :max="30" @change="rangeChange" :marks="marks" class="m-3"/>
                </div>
                <div class="p-4 rounded-lg shadow-lg bg-gray-500 grid  row-span-5 col-span-3 text-gray-700">
                    <input  type="text" 
                            id="filterInput" 
                            placeholder="Buscar..." 
                            v-model="search"
                            class="mb-3 p-1 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <div class="relative overflow-hidden border border-gray-300 rounded-lg shadow-md">
                        <!-- Table Header (static) -->
                        <table class="min-w-full bg-white">
                        <thead class="bg-red-600">
                            <tr>
                                <th class="border w-3/6 py-3 text-center text-sm font-medium text-white">Producto</th>
                                <th class="border w-1/6 py-3 text-center text-sm font-medium text-white">Cantidad</th>
                                <th class="border w-1/6 py-3 text-center text-sm font-medium text-white">Precio Cliente</th>
                                <th class="border w-1/6 py-3 text-center text-sm font-medium text-white">Precio Lista</th>
                            </tr>
                        </thead>
                        </table>

                        <!-- Table Body (scrollable) -->
                        <div class="h-72 overflow-y-auto">
                        <table class="min-w-full bg-white">
                            <tbody id="dataTable">
                                

                                <tr class="odd:bg-gray-300 even:bg-gray-200" v-for="p in filterTableData">
                                    <td class="border text-left w-3/6 p-1">{{p.name}} </td>
                                    <td class="border text-center w-1/6">{{p.cantidad}}</td>
                                    <td class="border text-center w-1/6">$ {{p.precioCliente.toFixed(2)}} MXN</td>
                                    <td class="border text-center w-1/6">$ {{p.preciolista.toFixed(2)}} MXN</td>
                                </tr>
                                
                                                          
                            </tbody>
                        </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
    </AppLayout>
</template>

<style >

.el-slider__stop{
    background-color: red;
    height: 10px;
    width: 10px;
    margin-top: -2px;
}
.el-slider{
    width: 95%;
}

</style>
