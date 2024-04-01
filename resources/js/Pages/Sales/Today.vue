<script >
import AppLayout from '@/Layouts/AppLayout.vue';


import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SecondaryButtonPay from '@/Components/SecondaryButtonPay.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, getCurrentInstance } from 'vue';
import axios from 'axios';
import Field from '@/Components/Field.vue';
import $ from 'jquery';
import "datatables.net-responsive-dt/css/responsive.dataTables.min.css"
import "datatables.net-dt/css/jquery.dataTables.min.css"
import 'datatables.net-responsive-bs5';
import 'datatables.net-select';
import FooterPos from '@/Components/FooterPos.vue';

import { HollowDotsSpinner } from 'epic-spinners';
import DatatableLocal from '@/Components/DatatableLocal.vue';
import { useTransition } from '@vueuse/core';
import { ElDivider } from 'element-plus';
import { ElLoading } from 'element-plus';
export default{
    components:{
        AppLayout,ElLoading, InputLabel, TextInput, PrimaryButton, SecondaryButton, FooterPos, SecondaryButtonPay, HollowDotsSpinner, Field,DatatableLocal
    },
    
    props:{
        ventas: Array,
        ventasTotales: Number,
        salesCount: Number,
        productCounts: Number,
        total: Number,
        period: String,
        duplicates: Array
    },
    data(){
        return {
            loading: null,
        }
    },
    methods:{
        deleteSale(id){
            console.log(id);
            this.loading = ElLoading.service({
                lock: true,
                text: 'Eliminando venta...',
                background: '#ff000054',

            });
            axios.get(`/sales/delete/${id}`).then(response => {
                console.log(response);
                this.loading.close()
                location.reload();
            })
            .catch(error => {
                console.log(error);
                this.loading.close()
            });
        }
    },
    created() {

    },
    mounted(){

        console.log(this.ventas);

    }
}

</script>
<template>
    
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                OliStore > Ventas del {{period}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">
                            <h3 class="text-lg text-gray-900"> Detalle ventas hoy </h3>
                            <br/>
                    
                            <table>
                                <tr>
                                    <td><b>Ventas totales :</b></td>
                                    <td>{{ventasTotales}}</td>
                                </tr>
                            </table>
                        </div>
                        
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <h2><b>TIENDA:</b> OliStore 1</h2>
                            <el-row>
                                
                                <el-col :span="6">
                                    <el-statistic title="Número de ventas" :value="`# ${salesCount}` " />
                                </el-col>
                                <el-col :span="6">
                                    <el-statistic title="Número de productos vendidos" :value="`# ${productCounts}`" />
                                </el-col>
                                <el-col :span="6">
                                    <el-statistic title="Total $ MXN" :value="`$ ${total}`" />
                                </el-col>
                                
                            </el-row>
                            <el-divider content-position="center" id="gotop">Posibles duplicados</el-divider>

                            <a v-for="dup in duplicates" class="py-2" type="primary" :href="`#${dup}`"> / {{dup}} </a>

                            <el-divider content-position="center" >Ventas</el-divider>

                            <el-timeline style="max-width: 600px">
                                <el-timeline-item v-for="venta in ventas" center :timestamp="venta.fcreacion" :size="'large'" color="red" placement="top">
                                    <el-card :id="venta.id">
                                        <ul  class="infinite-list " >
                                            <li v-for="(sp, spKey) in venta.soldProducts">
                                                <span># {{spKey + 1}} &nbsp;</span>
                                                <b class="text-xs ">{{ sp.product.name }}</b>

                                                <b class="text-xs text-green-600 "> $ {{ sp.final_price }} MXN</b>
                                            </li>
                                            <el-divider content-position="center">
                                                {{`# Productos: ${venta.no_products} / TOTAL: $ ${venta.total} MXN`}}
                                            </el-divider>
                                            <a  href="#gotop">Ir Arriba </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                             <!-- <el-button type="danger" @click="delete(venta.id)" plain>No es duplicado</el-button> -->
                                             <el-button type="danger" @click="deleteSale(venta.id)" plain>Eliminar</el-button>

                                        </ul>
                                    </el-card>
                                </el-timeline-item>
                            </el-timeline>
                            <inertia-link :href="route('sales.index')">
                                Regresar
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

</template>