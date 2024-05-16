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
            reportResults8: new Array(),
            reportResults15: new Array(),
            reportResults30: new Array(),
            reportResultsLocal8: new Array(),
            reportResultsLocal15: new Array(),
            reportResultsLocal30: new Array(),
            search:'',
            search1:'',
            search2:'',
            resultCounter: 0,
            resultCounter1: 0,
            resultCounter2: 0,
        }
    },
    mounted(){
        this.reportResults['8'].forEach(item => {
            const description = (item.product_id.Description == 'EXPRESS CREATION') ? item.product_id.name : item.product_id.Description;

            console.log(description);

            if (this.reportResults8[description] === undefined) {
                this.reportResults8[description] = 1;
            } else {
                this.reportResults8[description] += 1;
            }
        });
        let listKeys8 = Object.keys(this.reportResults8);
        for (let index = 0; index < listKeys8.length; index++) {

            this.reportResultsLocal8.push({
                description: listKeys8[index], 
                count: this.reportResults8[listKeys8[index]]
            });
        }

        this.reportResults['15'].forEach(item => {
            const description = (item.product_id.Description == 'EXPRESS CREATION') ? item.product_id.name : item.product_id.Description;

            console.log(description);

            if (this.reportResults15[description] === undefined) {
                this.reportResults15[description] = 1;
            } else {
                this.reportResults15[description] += 1;
            }
        });
        let listKeys15 = Object.keys(this.reportResults15);
        for (let index = 0; index < listKeys15.length; index++) {

            this.reportResultsLocal15.push({
                description: listKeys15[index], 
                count: this.reportResults15[listKeys15[index]]
            });
        }


        this.reportResults['30'].forEach(item => {
            const description = (item.product_id.Description == 'EXPRESS CREATION') ? item.product_id.name : item.product_id.Description;

            console.log(description);

            if (this.reportResults30[description] === undefined) {
                this.reportResults30[description] = 1;
            } else {
                this.reportResults30[description] += 1;
            }
        });
        let listKeys30 = Object.keys(this.reportResults30);
        for (let index = 0; index < listKeys30.length; index++) {

            this.reportResultsLocal30.push({
                description: listKeys30[index], 
                count: this.reportResults30[listKeys30[index]]
            });
        }



    },
    computed: {
        filterTableData8() {
            let result = this.reportResultsLocal8.filter(
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

        filterTableData15() {
            let result = this.reportResultsLocal15.filter(
                (data) =>
                !this.search1 || JSON.stringify(data).toLowerCase().includes(this.search1.toLowerCase() )
            );
            setTimeout(() => {
                this.resultCounter1 = 0;
                for (let index = 0; index < result.length; index++) {
                    this.resultCounter1 += result[index].count;
                }

            }, 3000);
            return result;
        },

        filterTableData30() {

            let result = this.reportResultsLocal30.filter(
                (data) =>
                !this.search2 || JSON.stringify(data).toLowerCase().includes(this.search2.toLowerCase() )
            );
            setTimeout(() => {
                this.resultCounter2 = 0;
                for (let index = 0; index < result.length; index++) {
                    this.resultCounter2 += result[index].count;
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
            <el-row>
                <el-col :span="4" class="bg-white md:p-3 p-1 border-red-600 border md:m-2 lg:m-2">
                    <el-statistic title="Ventas 30 d." :value="'# '+reportResults['30'].length" />
                </el-col>

                <el-col :span="4" class="bg-white md:p-3 p-1 border-red-600 border md:m-2 lg:m-2">
                    <el-statistic title="Ventas 15 d." :value="'# '+reportResults['15'].length" />
                </el-col>

                <el-col :span="4" class="bg-white md:p-3 p-1 border-red-600 border md:m-2 lg:m-2">
                    <el-statistic title="Ventas 8 d." :value="'# '+reportResults['8'].length" />
                </el-col>
            </el-row>
        </div>
            
        <div class="shadow bg-white md:rounded-md p-4 m-4">
            <div class="flex flex-wrap">
                <div class="m-3">
                    <h3 class="text-lg text-gray-900"> Reporte de los ultimos 8 días </h3>
                    <p class="text-base text-red-700 font-bold ">Cantidad de productos filtrados: # {{resultCounter}}</p>

                    <br/> 
                    <el-input v-model="search"  placeholder="Buscar en reporte" class="shadow-2xl shadow-red-400 mb-3"/>
                    <el-table :data="filterTableData8" class="shadow-lg shadow-red-400" stripe style="width: 100%; height: 400px;" >
                        <el-table-column prop="description" label="Producto" width="350" />
                        <el-table-column prop="count" label="Número de ventas" width="150" />
                    </el-table>
                </div>
                <div class="m-3">
                    <h3 class="text-lg text-gray-900"> Reporte de los ultimos 15 días </h3>
                    <p class="text-base text-red-700 font-bold ">Cantidad de productos filtrados: # {{resultCounter1}}</p>

                    <br/> 
                    <el-input v-model="search1"  placeholder="Buscar en reporte" class="shadow-2xl shadow-red-400 mb-3"/>
                    <el-table :data="filterTableData15" class="shadow-lg shadow-red-400" stripe style="width: 100%; height: 400px;" >
                        <el-table-column prop="description" label="Producto" width="350" />
                        <el-table-column prop="count" label="Número de ventas" width="150" />
                    </el-table>
                </div>
                <div class="m-3">
                    <h3 class="text-lg text-gray-900"> Reporte de los ultimos 30 días </h3>
                    <p class="text-base text-red-700 font-bold ">Cantidad de productos filtrados: # {{resultCounter2}}</p>

                    <br/> 
                    <el-input v-model="search2"  placeholder="Buscar en reporte" class="shadow-2xl shadow-red-400 mb-3"/>
                    <el-table :data="filterTableData30" class="shadow-lg shadow-red-400" stripe style="width: 100%; height: 400px;" >
                        <el-table-column prop="description" label="Producto" width="350" />
                        <el-table-column prop="count" label="Número de ventas" width="150" />
                    </el-table>
                </div>
            </div>
            <br/>

        </div>
    </AppLayout>
</template>

