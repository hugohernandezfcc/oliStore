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
            this.search1 = this.searchGlobal;
            this.search2 = this.searchGlobal;
            
        },
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
            originalResultCounter: 0,
            originalResultCounter1: 0,
            originalResultCounter2: 0,
            searchGlobal: '',
            date: new Date()
        }
    },
    mounted(){
        let globalResults = [];

        this.reportResults['30'].forEach(item => {
            const description = (item.product_id.Description == 'EXPRESS CREATION') ? item.product_id.name : item.product_id.Description;

            let difference = moment().diff(moment(item.created_at), 'days');
            globalResults[description] = item;
            if(difference <= 8){
                // console.log(difference, description);
                if (this.reportResults8[description] === undefined) 
                    this.reportResults8[description] = 1;
                else
                    this.reportResults8[description] += 1;
            }

            if(difference <= 15){
                if (this.reportResults15[description] === undefined) {
                    this.reportResults15[description] = 1;
                } else {
                    this.reportResults15[description] += 1;
                }
            }

            if(difference <= 30){
                if (this.reportResults30[description] === undefined) {
                    this.reportResults30[description] = 1;
                } else {
                    this.reportResults30[description] += 1;
                }
            }
        });
        console.log(globalResults);
        let listKeys8 = Object.keys(this.reportResults8);
        for (let index = 0; index < listKeys8.length; index++) {

            this.reportResultsLocal8.push({
                description: listKeys8[index], 
                count: this.reportResults8[listKeys8[index]],
                store: globalResults[listKeys8[index]].sale_id.store
            });
            this.originalResultCounter += this.reportResults8[listKeys8[index]];
        }

        let listKeys15 = Object.keys(this.reportResults15);
        for (let index = 0; index < listKeys15.length; index++) {

            this.reportResultsLocal15.push({
                description: listKeys15[index], 
                count: this.reportResults15[listKeys15[index]],
                store: globalResults[listKeys15[index]].sale_id.store
            });
            this.originalResultCounter1 += this.reportResults15[listKeys15[index]];
        }

        let listKeys30 = Object.keys(this.reportResults30);
        for (let index = 0; index < listKeys30.length; index++) {

            this.reportResultsLocal30.push({
                description: listKeys30[index], 
                count: this.reportResults30[listKeys30[index]],
                store: globalResults[listKeys30[index]].sale_id.store
            });
            this.originalResultCounter2 += this.reportResults30[listKeys30[index]];
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
           

            <div class="flex flex-row">
                <div class="bg-white m-2 p-1 border border-red-600 basis-1/5"><el-statistic title="Ventas 30 d." :value="'# '+originalResultCounter2" /></div>
                <div class="bg-white m-2 p-1 border border-red-600 basis-1/5"><el-statistic title="Ventas 15 d." :value="'# '+originalResultCounter1" /></div>
                <div class="bg-white m-2 p-1 border border-red-600 basis-1/5"><el-statistic title="Ventas 8 d." :value="'# '+originalResultCounter" /></div>
            </div>
        </div>
            
        <div class="shadow bg-white md:rounded-md lg:p-4 lg:m-4 m-1">
            {{ formattedDate }}
            <el-input v-model="searchGlobal"  placeholder="Buscar en reporte" class="shadow-2xl shadow-red-200 mb-1"/>
            <el-button @click="filterTableglobal" class="shadow-2xl shadow-red-200 mb-1">Filtrar</el-button>
            <div class="flex flex-wrap">
                <div class="m-3">
                    <h3 class="text-lg text-gray-900"> Reporte de los ultimos 8 días </h3>
                    <p class="text-base text-red-700 font-bold ">Cantidad de productos filtrados: # {{resultCounter}}</p>

                    <br/> 
                    <el-input v-model="search"  placeholder="Buscar en reporte" class="shadow-2xl shadow-red-400 mb-3 custom-width "/>
                    <el-table :data="filterTableData8" class="shadow-lg shadow-red-400 " :default-sort="{ prop: 'count', order: 'descending' }" stripe style="width: 90%; height: 400px;" >
                        <el-table-column prop="store" label="Tienda" width="90" />
                        <el-table-column prop="description" label="Producto" width="190" />
                        <el-table-column prop="count" label="Ventas" width="120" sortable/>
                    </el-table>
                </div>
                <div class="m-3">
                    <h3 class="text-lg text-gray-900"> Reporte de los ultimos 15 días </h3>
                    <p class="text-base text-red-700 font-bold ">Cantidad de productos filtrados: # {{resultCounter1}}</p>

                    <br/> 
                    <el-input v-model="search1"  placeholder="Buscar en reporte" class="shadow-2xl shadow-red-400 mb-3 custom-width "/>
                    <el-table :data="filterTableData15" class="shadow-lg shadow-red-400" :default-sort="{ prop: 'count', order: 'descending' }" stripe style="width: 90%; height: 400px;" >
                        <el-table-column prop="store" label="Tienda" width="90" />
                        <el-table-column prop="description" label="Producto" width="200" />
                        <el-table-column prop="count" label="Ventas" width="120" sortable/>
                    </el-table>
                </div>
                <div class="m-3">
                    <h3 class="text-lg text-gray-900"> Reporte de los ultimos 30 días </h3>
                    <p class="text-base text-red-700 font-bold ">Cantidad de productos filtrados: # {{resultCounter2}}</p>

                    <br/> 
                    <el-input v-model="search2"  placeholder="Buscar en reporte" class="shadow-2xl shadow-red-400 mb-3 custom-width "/>
                    <el-table :data="filterTableData30" class="shadow-lg shadow-red-400" :default-sort="{ prop: 'count', order: 'descending' }" stripe style="width: 90%; height: 400px;" >
                        <el-table-column prop="store" label="Tienda" width="90" />
                        <el-table-column prop="description" label="Producto" width="200" />
                        <el-table-column prop="count" label="Ventas" width="120" sortable/>
                    </el-table>
                </div>
            </div>
            <br/>

        </div>
    </AppLayout>
</template>

<style scoped>
.custom-width {
  width: 70%;
}
</style>