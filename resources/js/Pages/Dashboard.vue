

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
                            <CardStatic :key="componentKey" :mainValue="card1.mount" :mainValueFooter="12" :title="card1.title" :titleFooter="card1.footerValue" />
                        </div>
                        <div class="w-full md:w-1/4 p-2">
                            <CardStatic :key="componentKey" :mainValue="card2.mount" :mainValueFooter="12" :title="card2.title" :titleFooter="'Footer text'" />
                        </div>
                        <div class="w-full md:w-1/4 p-2">
                            <CardStatic :key="componentKey" :mainValue="card3.mount" :mainValueFooter="12" :title="card3.title" :titleFooter="'Footer text'" />
                        </div>
                        <div class="w-full md:w-1/4 p-2">
                            <CardStatic :key="componentKey" :mainValue="card4.mount" :mainValueFooter="12" :title="card4.title" :titleFooter="'Footer text'" />
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
                        <div class="w-full md:w-2/4 p-2">
                            <el-card class="box-card">
                                <template #header>
                                    <div class="card-header">
                                        <span>{{ counter }} Productos afectados en tickets </span>
                                    </div>
                                </template>
                                <Doughnut   :key="componentKey" :labels="doughnut.x" 
                                            :datasets="[
                                                    {
                                                    backgroundColor: Object.values(this.colors),
                                                    data: doughnut.y
                                                }
                                            ]"/>

                            </el-card>
                        </div>
                        <div class="w-full md:w-2/4">
                            <el-card class="box-card">
                                <template #header>
                                    <div class="card-header">
                                        <span>Productos a Corregir: {{ expressCreation.length }}</span>
                                    </div>
                                </template>
                                
                                <el-table :data="filterTableData" height="500" >
                                    <el-table-column prop="name" label="Nombre"  />
                                    <el-table-column prop="Description" label="Descripción"  />
                                    <el-table-column prop="folio" label="Folio"  />

                                        <el-table-column align="right" fixed="right" width="90">
                                            <template #header>
                                                <el-input v-model="search" size="small" placeholder="Type to search" />
                                            </template>
                                            <template #default="scope">
                                                <inertia-link :href="route('products.edit', scope.row.id)" >
                                                    <el-button
                                                    size="small"
                                                    type="danger"

                                                    >Editar</el-button
                                                    >
                                                </inertia-link>
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
            endDate: new Date(new Date().getTime() + 24 * 60 * 60 * 1000),
            loading: null,
            card1:{
                title: 'Ventas de ',
                mount: 0,
                footer: '',
                footerValue: {
                    start: '',
                    end: '',   
                    title: ''
                }

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
            doughnut:{
                x: [],
                y: []
            },
            componentKey: 0,
            colors : [],
            counter : 0,
            expressCreation: []
        }
    },
    methods:{
        refreshChildComponent() {
          this.componentKey++;
        },
        getRandomHexColor() {
            return '#' + Math.floor(Math.random()*16777215).toString(16);
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
                this.card1.mount = res.data.salesToday.mount;
                this.card1.footerValue.start = this.startDate.toLocaleDateString('en-GB').replace(/\//g, '-');
                this.card1.footerValue.end = this.endDate.toLocaleDateString('en-GB').replace(/\//g, '-');
                
                this.card1.footerValue.title = 'Mostrar las ventas del periodo';
                this.card2.mount = res.data.productsToday.mount;
                this.card3.mount = res.data.ticketsRecorded.mount;
                this.card4.mount = res.data.pasiveData.mount;
                this.loading.close()
                this.refreshChildComponent()
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
            console.log('>>>>>>>');
            console.log(res);
            this.card1.mount = res.data.salesToday.mount;
            
            this.card1.footerValue.title = 'Mostrar las ventas del periodo';
            this.card1.footerValue.start = new Date().toLocaleDateString('en-GB').replace(/\//g, '-');
            this.card1.footerValue.end = new Date(new Date().getTime() + 24 * 60 * 60 * 1000).toLocaleDateString('en-GB').replace(/\//g, '-');
            this.card2.mount = res.data.productsToday.mount;
            this.card3.mount = res.data.ticketsRecorded.mount;
            this.card4.mount = res.data.pasiveData.mount;

            this.graphicBar.y =  res.data.graphicBar.values;
            this.graphicBar.x =  res.data.graphicBar.keys;
            this.graphicPoligono.y =  res.data.ChartPoligono.values;
            this.graphicPoligono.x =  res.data.ChartPoligono.keys;
            this.expressCreation   = res.data.expressCreation;

            let counter = [];
            for (let index = 0; index < res.data.doughnut.values.length; index++) {
                
                counter.push(res.data.doughnut.values[index].length);
            }

            this.colors = res.data.doughnut.values.reduce((acc, brand) => {
                acc[brand] = this.getRandomHexColor();
                return acc;
            }, {});
            
            console.log(Object.values(this.colors));
            this.counter = counter.reduce((acumulador, valorActual) => acumulador + valorActual, 0);


            this.doughnut.y =  counter;
            this.doughnut.x =  res.data.doughnut.keys;

            this.refreshChildComponent();

            this.loading.close()
        }).catch((error) => {
            console.log(error);
            this.loading.close()
        });
    },
    computed: {
        filterTableData() {
            return this.expressCreation.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        },
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