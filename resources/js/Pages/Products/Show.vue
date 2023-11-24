<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SecondaryButtonPay from '@/Components/SecondaryButtonPay.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import axios from 'axios';

import $ from 'jquery';
import "datatables.net-responsive-dt/css/responsive.dataTables.min.css"
import "datatables.net-dt/css/jquery.dataTables.min.css"
import 'datatables.net-responsive-bs5';
import 'datatables.net-select';
import FooterPos from '@/Components/FooterPos.vue';
import { HollowDotsSpinner } from 'epic-spinners'

export default{
    components:{
        AppLayout, InputLabel, TextInput, PrimaryButton, SecondaryButton, FooterPos, SecondaryButtonPay, HollowDotsSpinner
    },
    props:{
        product: Object

    },
    data(){
        return {
            salesList: []
        }
    },
    methods:{
    onRowClick(){
                let rowCollectionSelected = new Array();
                this.dt.rows({ selected: true }).data().each( function ( recordSelected, index ) {
                    rowCollectionSelected.push(recordSelected);
                } );

                this.rowCollectionSelected = rowCollectionSelected;
                console.log(this.rowCollectionSelected);
            },
    },
    mounted(){
        console.log(this.product);
        this.dt = $('#datatable').DataTable();
        this.dt.on( 'select', () => this.onRowClick())
        this.dt.on( 'deselect', () => this.onRowClick())
        for (let index = 0; index < this.product.ProductLineItems.length; index++) {
            const element = this.product.ProductLineItems[index];
            for (let i = 0; i < element.sale.length; i++) {
                const venta = element.sale[i];
                venta.idVenta = 'Producto parte de la venta con Id:' + venta.id;
                this.salesList.push(venta);
            }
        }
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
                                        <inertia-link :href="route('products.edit', product.id)">
                                            <PrimaryButton >
                                                Editar Producto
                                            </PrimaryButton>
                                            
                                        </inertia-link>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>ID DB :</b></td>
                                    <td>{{product.id}}</td>
                                </tr>
                                <tr>
                                    <td><b>NOMBRE PRODUCTO:</b></td>
                                    <td>{{product.name}}</td>
                                </tr>
                                <tr>
                                    <td><b>CODIGO DE BARRAS:</b></td>
                                    <td>{{product.folio}}</td>
                                </tr>
                                <tr>
                                    <td><b>DESCRIPCION:</b></td>
                                    <td>{{product.Description}}</td>
                                </tr>
                                <tr>
                                    <td><b>UNIDAD DE MEDIDA:</b></td>
                                    <td>{{product.unit_measure}}</td>
                                </tr>
                                <tr>
                                    <td><b>PRECIO DE LISTA:</b></td>
                                    <td>$ {{product.price_list}}</td>
                                </tr>
                                <tr>
                                    <td><b>PRECIO CLIENTE:</b></td>
                                    <td>$ {{product.price_customer}}</td>
                                </tr>
                                <tr>
                                    <td><b>PORCENTAJE (GANANCIA):</b></td>
                                    <td>{{product.profit_percentage}} %</td>
                                </tr>
                                <tr>
                                    <td><b>FECHA DE CADUCIDAD:</b></td>
                                    <td>{{product.expiry_date}}</td>
                                </tr>
                                <tr>
                                    <td><b>CREADO POR:</b></td>
                                    <td>{{product.createdByUser.name}}</td>
                                </tr>
                                <tr>
                                    <td><b>ULTIMA ACTUALIZACIÓN:</b></td>
                                    <td>{{product.editedByUser.name}}</td>
                                </tr>
                                <tr>
                                    <td ><b>Código de barras:  </b></td>
                                    <td class="p-2">
                                        <div v-html="product.bar_code" /> 
                                        {{product.folio}}
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <h2>{{product.name}} // {{product.Description}}</h2>
                            <hr class="my-6"/>

                            <table>
                                <tr>
                                    <td><b>Total de ventas:</b></td>
                                    <td>{{product.totalVentas}}</td>
                                </tr>
                            </table>

                            <table>
                                <tr>
                                    <td><b>Total precio Cliente:</b></td>
                                    <td>$ {{product.totalPrecioCliente}} MXN</td>
                                </tr>
                            </table>


                            <table>
                                <tr>
                                    <td><b>Total precio Lista:</b></td>
                                    <td>$ {{product.totalPrecioList}} MXN</td>
                                </tr>
                            </table>
                            <hr class="my-6"/>

                            <DataTable 
                                class="cell-border compact stripe hover order-column loading"
                                ref="table" id="datatable"
                                :data="salesList"
                                :options="{
                                    responsive:true, autoWidth:false, select: true,  dom:'Bfrtip', buttons:[
                                        { 
                                            extend: 'selectAll', 
                                            className: 'shadow relative bg-primary-500 hover:bg-red-600 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-red-600 text-white dark:text-gray-900' 
                                        },
                                        { 
                                            extend: 'print',
                                            text: 'Print selected rows', 
                                            className: 'shadow relative bg-primary-500 hover:bg-red-600 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-red-600 text-white dark:text-gray-900' 
                                        }
                                    ], language:{
                                        search:'Buscar Producto ', zeroRecords:'No hay registros'
                                    }
                                }"
                                :columns="[
                                    {data:'idVenta'},
                                    {data:'payment_method'},
                                    {data:'store'},
                                    {data:'total'},
                                    {data:'created_at'}
                            ]">
                                <thead>
                                    <tr>
                                        <th>ID DB </th>
                                        <th>MÉTODO DE PAGO</th>
                                        <th>TIENDA</th>
                                        <th>TOTAL DE ESA COMPRA</th>
                                        <th>FECHA DE CREACION</th>
                                    </tr>
                                </thead>
                            </DataTable>
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


