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
import { list } from 'postcss';

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
        getCurrentDayNumber(){
            let day = new Date().getDay();
            const dayMapping = { 0: 7, 1: 1, 2: 2, 3: 3, 4: 4, 5: 5, 6: 6 };

            console.log('Before return ', dayMapping[day]);
            return dayMapping[day];
        },

    },
    data(){
        return {

            reportResultsLocal: new Array(),
            search:'',
            resultCounter: 0,
            originalResultCounter: 0,

            date: new Date(),
            summaries: new Array(),
            list: 0,
            customer: 0,
            revenue: 0,
            byDay: 0,
        }
    },
    beforeMount(){
        let globalResults = [];

        console.log(this.reportResults.records);
        console.log(Object.values(this.reportResults.records).length)
        console.log(Object.keys(this.reportResults.records).length)
        console.log(Object.keys(this.reportResults.records))
        
        Object.keys(this.reportResults.records).forEach((tienda) => {

            if (!this.summaries[tienda]) {
                this.summaries[tienda] = {};
            }

            for (const key in this.reportResults.records[tienda]) {
                console.log(tienda);
                console.log(key);


                if (!this.summaries[tienda][key]) {
                    this.summaries[tienda][key] = { list: 0, customer: 0, revenue: 0 };
                }

                for (let index = 0; index < this.reportResults.records[tienda][key].length; index++) {
                    const element = this.reportResults.records[tienda][key][index];
                    // console.log(element);

                    this.summaries[tienda][key].list += parseFloat(element.product.price_list);
                    this.summaries[tienda][key].customer += parseFloat(element.product.price_customer);
                    this.summaries[tienda][key].revenue += parseFloat(parseFloat(element.product.price_customer)-parseFloat(element.product.price_list));

                }
            }
            
        });
        Object.keys(this.reportResults.records).forEach((tienda) => {
            for (const key in this.reportResults.records[tienda]) {
                this.list += this.summaries[tienda][key].list;
                this.customer += this.summaries[tienda][key].customer;
                this.revenue += this.summaries[tienda][key].revenue;
            }
        });
        this.byDay = this.revenue / this.getCurrentDayNumber();
        console.log(this.summaries);
        

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
<style scoped>
:global(h2#card-usage ~ .example .example-showcase) {
  background-color: var(--el-fill-color) !important;
}

.el-statistic {
  --el-statistic-content-font-size: 28px;
}

.statistic-card {
    height: 100%;
    margin: 0 0 0 10px;
    padding: 15px;
    border-radius: 4px;
    background-color: white;
    border: 1px solid red;
}

.statistic-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  font-size: 12px;
  color: var(--el-text-color-regular);
  margin-top: 16px;
}

.statistic-footer .footer-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.statistic-footer .footer-item span:last-child {
  display: inline-flex;
  align-items: center;
  margin-left: 4px;
}

.green {
  color: var(--el-color-success);
}
.red {
  color: var(--el-color-error);
}
</style>
<template>
    <AppLayout title="Dashboard">
        <template #header>
            
        </template>
        
        <div class="m-4">
            <h3 class="text-lg text-gray-900"> {{report.name}} </h3>
            <p class="text-sm text-gray">{{report.description}}</p>
            <br/>
           
            <el-row>
                <el-col :span="4">
                    <div class="statistic-card">
                        <el-statistic :value="'$'+revenue.toFixed(2)+' MXN'">
                            <template #title>
                                Total de Ganancias
                            </template>
                        </el-statistic>
                    </div>
                </el-col>
                <el-col :span="4">
                    <div class="statistic-card">
                        <el-statistic :value="'$'+list.toFixed(2)+' MXN'">
                            <template #title>
                                Total de precio de lista
                            </template>
                        </el-statistic>
                    </div>
                </el-col>
                <el-col :span="4">
                    <div class="statistic-card">
                        <el-statistic :value="'$'+customer.toFixed(2)+' MXN'">
                            <template #title>
                                Total de precio de cliente
                            </template>
                        </el-statistic>
                    </div>
                </el-col>
                <el-col :span="4">
                    <div class="statistic-card">
                        <el-statistic :value="'$'+byDay.toFixed(2)+' MXN'">
                            <template #title>
                                Promedio de Ganancia
                            </template>
                        </el-statistic>
                    </div>
                </el-col>
            </el-row>   

        </div>
        
        <div class="shadow bg-white md:rounded-md lg:p-4 lg:m-4 m-1">
            <h3 class="text-lg text-gray-900"> Reporte de los ultimos 8 días </h3>

            <div class="flex flex-wrap " >
                
                <div v-for="(fechas,tienda) in reportResults.records" :key="fechas" class="m-2 shadow-lg shadow-red-600 ">
                    <h1 class="bg-red-500 text-white bold m-1 p-1">{{'Tienda '+tienda}}</h1>

                    <div  v-for="(k1,v1) in fechas" :key="k1">
                        <el-descriptions class="margin-top" :column="4"  border>
                            <el-descriptions-item :label="'Fecha'">{{ v1 }}</el-descriptions-item>
                            <el-descriptions-item :label="'Ganancia'">{{ summaries[tienda][v1].revenue.toFixed(2) }}</el-descriptions-item>
                            <el-descriptions-item :label="'P. Lista'">{{ summaries[tienda][v1].list.toFixed(2) }}</el-descriptions-item>
                            <el-descriptions-item :label="'P. Cliente'">{{ summaries[tienda][v1].customer.toFixed(2) }}</el-descriptions-item>
                        </el-descriptions>
                    </div>
                </div>

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
