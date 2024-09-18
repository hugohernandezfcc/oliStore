<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import $ from 'jquery';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Footer from '@/Components/Footer.vue';
import wizardForm from '@/Components/TicketForm.vue';
import moment from 'moment';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        wizardForm
    },

    props:{
        report: Object,
        reportResults: Array 
    },
    methods:{
        
    },
    data(){
        return {
         
            search:'',
            kStore: '',
            kDates: '',
            datatable: [],
            filters: [],
            stores: [],
            profit: 0,
            cprice: 0,
            lprice: 0
        }
    },
    mounted(){
        
        console.log(this.reportResults);
        let level1RecordsLocal = [];
        const datesFilters = new Set();
        for (const tienda in this.reportResults.records) {
            this.stores.push({
                text: tienda,
                value: tienda
            });
            for (const date in this.reportResults.records[tienda]) {
                level1RecordsLocal[date] = [];
                                
                for (const product in this.reportResults.records[tienda][date]) {
                    let localRecords = {};

                    localRecords.id = product;
                    localRecords.product = this.reportResults.records[tienda][date][product][0].product;

                    localRecords.quantity = 0;
                    localRecords.cprice = 0;
                    localRecords.lprice = 0;
                    
                    localRecords.quantity = this.reportResults.records[tienda][date][product].length;
                    localRecords.cprice   = localRecords.quantity*this.reportResults.records[tienda][date][product][0].clist;
                    localRecords.lprice   = localRecords.quantity*this.reportResults.records[tienda][date][product][0].plist;
                    localRecords.profit   = localRecords.cprice-localRecords.lprice;

                    localRecords.tienda = tienda;
                    localRecords.date = date;

                    if(this.reportResults.records[tienda][date][product][0].take_portion !== true){
                        this.datatable.push(localRecords);
                        level1RecordsLocal[date].push(localRecords);
                        this.profit += localRecords.profit;
                        this.cprice += localRecords.cprice;
                        this.lprice += localRecords.lprice;
                    }
                    datesFilters.add(date);

                }
            }
        }


        for (const date of datesFilters) {
            this.filters.push({ text: date, value: date });
        }
        this.filters.push({ text: 'All', value: '' });  
        this.stores.push({ text: 'All', value: '' });  
        
        console.log(this.datatable);
        console.log(this.filters);
    },
    computed: {
        /**
         */
        filterTableData() {

            if(this.kStore !== '' || this.kDates !== ''){
                let beforeReturn = this.datatable.filter(
                    (data) =>
                    (data.tienda === this.kStore || data.date === this.kDates) && (!this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() ))
                );
                return beforeReturn;
            }else{
                let beforeReturn = this.datatable.filter(
                    (data) =>
                    !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
                );
                return beforeReturn;
            }          
        }
       
    }

}
</script>
<template>
    <AppLayout title="Dashboard">
        <template #header>
            Reporte de ventas por unidades / Semanal
        </template>
        
        <div class="m-4">
            <div class="flex flex-wrap gap-1 font-mono text-white text-sm font-bold leading-6 bg-stripes-indigo rounded-lg">
                <div class="p-4 w-1/5 rounded-lg flex items-center justify-center bg-red-500 shadow-lg">
                    Profit: $ {{profit.toFixed(2)}} MXN
                </div>
                <div class="p-4 w-1/5 rounded-lg flex items-center justify-center bg-red-500 shadow-lg">
                    Lista P.: $ {{lprice.toFixed(2)}} MXN
                </div>
                <div class="p-4 w-1/5 rounded-lg flex items-center justify-center bg-red-500 shadow-lg">
                    Cliente P.: $ {{cprice.toFixed(2)}} MXN
                </div>
            </div>
        </div>
        <div class="shadow bg-white md:rounded-md lg:p-4 lg:m-4 m-1 font-mono">

            <div class="flex flex-wrap">
                <div class="p-1">
                    <el-select v-model="kStore" placeholder="Select" style="width: 240px">
                        <el-option
                        v-for="item in stores"
                        :key="item.text"
                        :label="item.text"
                        :value="item.value"
                        />
                    </el-select>
                </div>
                <div class="p-1">
                    <el-select v-model="kDates" placeholder="Select" style="width: 240px">
                        <el-option
                            v-for="item in filters"
                            :key="item.text"
                            :label="item.text"
                            :value="item.value"
                        />
                    </el-select>
                </div>
                <div class="p-1"><el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/></div>
            </div>

            
            <el-table v-loading="loading" :data="filterTableData" height="600" show-summary class="shadow-lg m-1"  stripe :default-sort="{ prop: 'quantity', order: 'descending' }" >
                <el-table-column prop="tienda"      label="Tienda"           width="250"></el-table-column>
                <el-table-column prop="date"        label="Fecha"            width="180"></el-table-column>
                <el-table-column prop="product"     label="Producto"         width="300"></el-table-column>
                <el-table-column prop="quantity"    label="Cantidad"         width="180"></el-table-column>
                <el-table-column prop="cprice"      label="Precio de Compra" width="180"></el-table-column>
                <el-table-column prop="lprice"      label="Precio de Venta"  width="180"></el-table-column>
                <el-table-column prop="profit"      label="Ganancia"         width="180"></el-table-column>
            </el-table>

        </div>
    </AppLayout>
</template>

<style scoped>
.custom-width {
  width: 70%;
}
</style>
