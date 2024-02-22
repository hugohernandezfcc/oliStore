

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex flex-wrap px-8">
                <div class="w-full md:w-1/3 p-2">
                    <span class="demonstration">Fecha Inicio</span><br/>
                    <el-date-picker
                        v-model="startDate"
                        type="date"
                        placeholder="Pick a day"

                    />
                </div>
                <div class="w-full md:w-1/3 p-2">
                    <span class="demonstration">Fecha Final</span><br/>
                    <el-date-picker
                        v-model="endDate"
                        type="date"
                        placeholder="Pick a day"
                        :disabled-date="disabledDate"

                    />
                </div>
                <div class="w-full md:w-1/3 p-2"><br/>
                    <el-button @click="query"  type="danger">Ajustar parametros</el-button>
                </div>
            </div>
                
        </template>

        <div class="py-4 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-wrap ">
                        <div class="w-full md:w-1/4 p-2">
                            <CardStatic :mainValue="card1.mount" :mainValueFooter="12" :title="card1.title" :titleFooter="'Footer text'" />
                        </div>
                        <div class="w-full md:w-1/4 p-2">
                            <CardStatic :mainValue="card2.mount" :mainValueFooter="12" :title="card2.title" :titleFooter="'Footer text'" />
                        </div>
                        <div class="w-full md:w-1/4 p-2">
                            <CardStatic :mainValue="card3.mount" :mainValueFooter="12" :title="card3.title" :titleFooter="'Footer text'" />
                        </div>
                        <div class="w-full md:w-1/4 p-2">
                            <CardStatic :mainValue="card4.mount" :mainValueFooter="12" :title="card4.title" :titleFooter="'Footer text'" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-wrap ">
                        <div class="w-full md:w-2/4 p-2">
                            <el-card class="box-card">
                                <template #header>
                                    <div class="card-header">
                                        <span>Ventas por mes</span>
                                    </div>
                                </template>
                                <BarChart  :key="componentKey" :labels="graphicBar.x" 
                                            :datasets="[ 
                                                {
                                                    label: 'Acumulado de ventas por mes', 
                                                    backgroundColor: 'red',
                                                    data: graphicBar.y
                                                } 
                                            ]"/>

                            </el-card>
                        </div>
                        <div class="w-full md:w-2/4 p-2">
                            <el-card class="box-card">
                                <template #header>
                                    <div class="card-header">
                                        <span>Ventas de la semana</span>
                                    </div>
                                </template>
                                <ChartPoligono  :key="componentKey" :labels="graphicPoligono.x" 
                                                :datasets="[
                                                    {
                                                    label: 'Acumulado por día',
                                                    backgroundColor: 'red',
                                                    data: graphicPoligono.y
                                                    }
                                                ]"/>
                                <template #footer>Footer content</template>
                            </el-card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-4 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-wrap ">
                        <div class="w-full md:w-1/4 p-2">
                            <el-card class="box-card">
                                <template #header>
                                    <div class="card-header">
                                        <span>Ventas por mes</span>
                                    </div>
                                </template>
                                <Doughnut   :labels="['VueJs', 'EmberJs', 'ReactJs', 'AngularJs']" 
                                            :datasets="[
                                                    {
                                                    backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
                                                    data: [40, 20, 80, 10]
                                                }
                                            ]"/>

                            </el-card>
                        </div>
                        <div class="w-full md:w-3/4 p-2">
                            <el-card class="box-card">
                                <template #header>
                                    <div class="card-header">
                                        <span>Lista de ventas</span>
                                    </div>
                                </template>
                                
                                <el-table  height="250" >
                                    <el-table-column prop="quantity" label="#" width="50" />
                                    <el-table-column prop="money" label="$ Total" width="80" />
                                    <el-table-column prop="producto" label="Barras" width="140" />

                                        <el-table-column align="right" fixed="right" width="100">
                                            <template #header>
                                                <el-input v-model="search" size="small" placeholder="Type to search" />
                                            </template>
                                            <template #default="scope">
                                                <el-button
                                                size="small"
                                                type="danger"
                                                @click="handleDelete(scope.$index, scope.row)"
                                                >Delete</el-button
                                                >
                                            </template>
                                        </el-table-column>

                                </el-table>
                                <template #footer>Footer content</template>
                            </el-card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </AppLayout>
</template>

<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import { h } from 'vue';
import { ElNotification } from 'element-plus';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import BarChart from '@/Components/BarChart.vue';
import ChartPoligono from '@/Components/ChartPoligono.vue';
import Doughnut from '@/Components/Doughnut.vue';
import CardStatic from '@/Components/CardStatic.vue';
import { ElLoading } from 'element-plus';

export default {
  name: 'dashboard',
  components: { BarChart, CardStatic, AppLayout, TextInput, InputLabel, ElNotification, ChartPoligono, Doughnut, InputError},
  data(){

        return {
            startDate: new Date(),
            endDate: new Date(),
            loading: null,
            card1:{
                title: 'Ventas de ',
                mount: 0,
                footer: '',
                footerValue: ''
            },
            card2:{
                title: 'Prod. vendidos de ',
                mount: 0,
                footer: '',
                footerValue: ''
            },
            card3:{
                title: 'Tickets de ',
                mount: 0,
                footer: '',
                footerValue: ''
            },
            card4:{
                title: 'Gastos de ',
                mount: 0,
                footer: '',
                footerValue: ''
            },
            graphicBar:{
                x: [],
                y: []
            },
            graphicPoligono:{
                x: [],
                y: []
            },
            componentKey: 0
        }
    },
    methods:{
        refreshChildComponent() {
          this.componentKey++;
        },
        query(){
            this.loading = ElLoading.service({
                lock: true,
                text: 'Calculando datos',
                background: '#ff000054',

            });
            this.rewriteTitle();    
            axios.post(route('dashboard.results'),{
                startDate: this.startDate.toLocaleDateString('en-US'),
                endDate: this.endDate.toLocaleDateString('en-US')
            }).then((res) => {
                console.log(res.data);
                this.card1.mount = res.data.salesToday.mound;
                this.card2.mount = res.data.productsToday.mound;
                this.loading.close()
            }).catch((error) => {
                console.log(error);
                this.loading.close()
            });
        },

        rewriteTitle(){
            console.log(this.startDate.toLocaleDateString('en-US'));
            console.log(this.endDate.toLocaleDateString('en-US'));
            let rangeString = this.startDate.toLocaleDateString('en-US') + ' - ' + this.endDate.toLocaleDateString('en-US');
            
            this.card1.title = `Ventas de ${rangeString}`;
            this.card2.title = `Prod. vendidos de ${rangeString}`;
            this.card3.title = `Tickets de ${rangeString}`;
            this.card4.title = `Gastos de ${rangeString}`;
        }
    },
    beforeMount(){
        this.loading = ElLoading.service({
            lock: true,
            text: 'Calculando datos',
            background: '#ff000054',

        });
        this.rewriteTitle();
        axios.post(route('dashboard.results')).then((res) => {
            console.log(res);
            this.card1.mount = res.data.salesToday.mound;
            this.card2.mount = res.data.productsToday.mound;
            this.graphicBar.y =  res.data.graphicBar.values;
            this.graphicBar.x =  res.data.graphicBar.keys;
            this.graphicPoligono.y =  res.data.ChartPoligono.values;
            this.graphicPoligono.x =  res.data.ChartPoligono.keys;
            this.refreshChildComponent();

            this.loading.close()
        }).catch((error) => {
            console.log(error);
            this.loading.close()
        });
    },
    computed: {
            
    }
}
</script>

<style scoped>
.demo-date-picker {
  display: flex;
  width: 100%;
  padding: 0;
  flex-wrap: wrap;
}

.demo-date-picker .block {
    padding-left: 20px;
  border-right: solid 1px var(--el-border-color);
  flex: 1;
}

.demo-date-picker .block:last-child {
  border-right: none;
}

.demo-date-picker .demonstration {
  display: block;
  color: var(--el-text-color-secondary);
  font-size: 14px;
  margin-bottom: 10px;
}
</style>