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


export default{
    components:{
        AppLayout, InputLabel, TextInput, PrimaryButton, SecondaryButton, FooterPos, SecondaryButtonPay, HollowDotsSpinner, Field,DatatableLocal
    },
    props:{
        ventas: Array,
        ventasTotales: Number
    },
    data(){
        return {
            
        }
    },
    methods:{

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
                OliStore > Ventas de hoy
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">
                            <h3 class="text-lg text-gray-900"> Detalle ventas ayer </h3>
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
                            <h2><b>TIENDA:</b> asodicjoaisdcj</h2>
                            
                            <div v-for="venta in ventas">
                                <table class="border border-slate-500 w-full">
                                    <tr class="border border-slate-500 ">
                                        <td colspan="3">Id Venta: {{ venta.id }} | # Productos: {{ venta.no_products }} | Fecha/hora de venta: {{ venta.fcreacion }} | <b>TOTAL: ${{venta.total}} MXN</b></td>
                                    </tr>
                                    <tr  v-for="(sp, spKey) in venta.soldProducts">
                                        <td class="border border-slate-500 "># {{spKey + 1}}</td>
                                        <td class="border border-slate-500 ">{{ sp.product.Description }}</td>
                                        <td class="border border-slate-500 ">$ {{ sp.final_price }} MXN</td>
                                    </tr>
                                </table>
                                <hr class="my-6"/>
                            </div>
                            


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