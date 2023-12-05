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
        sale: Object
    },
    data(){
        return {

        }
    },
    methods:{

    },
    mounted(){
        console.log(this.sale);
    }
}

</script>
<template>
    
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                OliStore
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">
                            <h3 class="text-lg text-gray-900"> Detalle de un producto </h3>
                            <br/>
                            <table>
                                <tr>
                                    <td>
                                        <!-- <inertia-link :href="route('products.edit', product.id)">
                                            <PrimaryButton >
                                                Editar Producto
                                            </PrimaryButton>
                                            
                                        </inertia-link> -->
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>ID DB :</b></td>
                                    <td>{{sale.id}}</td>
                                </tr>
                                <tr>
                                    <td><b>MÉTODO DE PAGO:</b></td>
                                    <td>{{sale.payment_method}}</td>
                                </tr>
                                <tr>
                                    <td><b>METODO DE ENTREGA:</b></td>
                                    <td>{{sale.delivery_method}}</td>
                                </tr>
                                <tr>
                                    <td><b>ESTATUS:</b></td>
                                    <td>{{sale.status}}</td>
                                </tr>
                                <tr>
                                    <td><b>NUMERO DE PRODUCTOS:</b></td>
                                    <td>{{sale.no_products}}</td>
                                </tr>
                                <tr>
                                    <td><b>NOTA:</b></td>
                                    <td>{{sale.note}}</td>
                                </tr>
                                <tr>
                                    <td><b>TIENDA:</b></td>
                                    <td>{{sale.store}}</td>
                                </tr>
                                <tr>
                                    <td><b>MONTO ENTRANTE:</b></td>
                                    <td>{{sale.inbound_amount}} %</td>
                                </tr>
                                <tr>
                                    <td><b>CAMBIO AL CLIENTE:</b></td>
                                    <td>{{sale.outbound_amount}}</td>
                                </tr>

                                <tr>
                                    <td><b>TOTAL:</b></td>
                                    <td>{{sale.total}}</td>
                                </tr>

                                <tr>
                                    <td><b>CREADO POR:</b></td>
                                    <td>{{sale.createdByUser.name}}</td>
                                </tr>
                                <tr>
                                    <td><b>ULTIMA ACTUALIZACIÓN:</b></td>
                                    <td>{{sale.editedByUser.name}}</td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <h2>product.name  product.Description</h2>
                            <hr class="my-6"/>

                
                            <hr class="my-6"/>

                            <DatatableLocal 
                            id="saleslocaltable"
                            :columns="[
                                {
                                    data:null, render:function(data,type,row,meta) {
                                        return `${meta.row+1}`
                                    }
                                },
                                {data:'id'},
                                {data:'sale_id'},
                                {data:'product_id'},
                                {data:'created_by_id'},
                                {data:'edited_by_id'},
                                {data:'take_portion'},
                                {data:'unit_measure'},
                                {data:'quantity'},
                                {data:'final_price'}
                            ]"
                            :search="'Buscar venta '"
                            :zeroRecords="'No hay ventas'"
                            :records="sale.ProductLineItems"
                            />


                            
                            <hr class="my-6"/>
                            <inertia-link :href="route('products.index')">
                                Regresar
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
</template>