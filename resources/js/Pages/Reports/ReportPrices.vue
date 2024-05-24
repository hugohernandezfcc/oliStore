<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import $ from 'jquery';
import "datatables.net-responsive-dt/css/responsive.dataTables.min.css"
import "datatables.net-dt/css/jquery.dataTables.min.css"
import 'datatables.net-responsive-bs5';
import 'datatables.net-select';
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
        filterTableglobal() {
            this.search = this.searchGlobal;

            
        },
    },
    data(){
        return {

            reportResultsLocal: new Array(),
            search:'',
            resultCounter: 0,
            originalResultCounter: 0,

            date: new Date(),
            summaries: new Array()
        }
    },
    beforeMount(){
        let globalResults = [];

        console.log(this.reportResults.records);
        console.log(Object.values(this.reportResults.records)[0])
        for (const key in Object.values(this.reportResults.records)[0]) {
            if(this.summaries[key] !== undefined)
                this.summaries[key] = {list:0, customer:0, revenue:0}
            else{
                this.summaries[key] = [];
                this.summaries[key] = {list:0, customer:0, revenue:0}
            }

            for (let index = 0; index < Object.values(this.reportResults.records)[0][key].length; index++) {
                const element = Object.values(this.reportResults.records)[0][key][index];
                console.log(element);

                this.summaries[key].list += parseFloat(element.product.price_list);
                this.summaries[key].customer += parseFloat(element.product.price_customer);
                this.summaries[key].revenue += parseFloat(parseFloat(element.product.price_customer)-parseFloat(element.product.price_list));

            }
        }
        console.log(this.summaries);
        // console.log(globalResults);
        // let listKeys = Object.keys(this.reportResults);
        // for (let index = 0; index < listKeys.length; index++) {

        //     this.reportResultsLocal.push({
        //         description: listKeys[index], 
        //         count: this.reportResults8[listKeys[index]],
        //         store: globalResults[listKeys[index]].sale_id.store
        //     });
        //     this.originalResultCounter += this.reportResults8[listKeys[index]];
        // }

    },
    computed: {
        
        filterTableData() {
            let result = this.reportResultsLocal.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
            setTimeout(() => {
                this.resultCounter = 0;
                for (let index = 0; index < result.length; index++) {
                    this.resultCounter += result[index].count;
                }

            }, 3000);
            return result;
        },

    }

}
</script>
<template>
    <AppLayout title="Dashboard">
        <template #header>
            
        </template>
        
        <div class="m-4">
            <h3 class="text-lg text-gray-900"> {{report.name}} </h3>
            <p class="text-sm text-gray">{{report.description}}</p>
            <br/>
           
            <el-date-picker v-model="rangeValue" type="daterange" range-separator="To" start-placeholder="Start date" end-placeholder="End date"  />

        </div>
        
        <div class="shadow bg-white md:rounded-md lg:p-4 lg:m-4 m-1">
            <h3 class="text-lg text-gray-900"> Reporte de los ultimos 8 días </h3>

            <div class="flex flex-wrap" v-for="(fechas,tienda) in reportResults.records" :key="fechas">
                
                <el-descriptions
                    class="margin-top"
                    :title="'Tienda '+tienda"
                    :column="2"
                    :size="'small'"
                    border
                >
                    <el-descriptions-item :label="tienda">
                        <div  v-for="(k1,v1) in fechas" :key="k1">
                            <div class="flex flex-wrap" v-for="(registros,v1) in fechas" :key="k1">
                                <el-descriptions
                                    class="margin-top"
                                    title="Fecha"
                                    :column="2"
                                    :size="'small'"
                                    border
                                >
                                    <el-descriptions-item :label="v1">
                                        <el-table :data="registros" class="mt-4 border border-slate-600 " style="width: 100%" :height="300" fixed>
                                            <el-table-column label="Producto" width="180">
                                                <template #default="scope">
                                                    {{ scope.row.product.name }}
                                                </template>
                                            </el-table-column>
                                            <el-table-column :label="'Ganancia - $' +summaries[v1].revenue.toFixed(2)" width="180">
                                                <template #default="scope">
                                                    $ {{ (scope.row.product.price_customer - scope.row.product.price_list).toFixed(2) }} 
                                                </template>
                                            </el-table-column>
                                            <el-table-column :label="'P. Lista - $' +summaries[v1].list.toFixed(2)" width="160" >
                                                <template #default="scope">
                                                    $ {{ scope.row.product.price_list }} 
                                                </template>
                                            </el-table-column>

                                            <el-table-column :label="'P. Cliente - $' +summaries[v1].customer.toFixed(2)" width="160" >
                                                <template #default="scope">
                                                    $ {{ scope.row.product.price_customer }} 
                                                </template>
                                            </el-table-column>
                                            
                                        </el-table>
                                    </el-descriptions-item>
                                </el-descriptions>
                            </div>
                        </div>
                    </el-descriptions-item>
                </el-descriptions>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
    .table-container {
    display: block;
}

.tbody-container {
    display: block;
    max-height: 300px; /* Set the desired height */
    overflow-y: auto; /* Enable vertical scrolling */
}

.tbody-container table {
    width: 100%; /* Ensure the table takes the full width */
    border-collapse: collapse; /* Optional: to collapse borders */
}

</style>
