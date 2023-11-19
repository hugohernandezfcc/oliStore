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
        AppLayout,
        InputLabel,
        TextInput,
        PrimaryButton, SecondaryButton, FooterPos, SecondaryButtonPay, HollowDotsSpinner
    },
    props:{
        sale: Object
        
    },
    data(){

        return {
            form: {
                payment_method: 'cash',
                delivery_method: 'delivery',
                status: 'open',
                no_products: '',
                note: '',
                store: 'Oli Store 1',
                inbound_amount: '',
                outbound_amount: '',
                subtotal: 0,
                total: 0,
                closed: '',
                productosRelacionados: []
            },
            realtime: {
                value: ''
            },
            productsAdded:[],
            rowCollectionSelected: new Array(),
            processing: false,
            total: 0
        }
    },
    methods:{
        onEnter(e){
            this.processing = true;
            if (e.keyCode === 13) {
                console.log(e);
                console.log('Enter was pressed');
                this.getMeProduct(this.realtime.value);
                this.realtime.value = '';
            }
        },

        onRowClick(){
            let rowCollectionSelected = new Array();
            this.dt.rows({ selected: true }).data().each( function ( recordSelected, index ) {
                rowCollectionSelected.push(recordSelected);
            } );

            this.rowCollectionSelected = rowCollectionSelected;
            console.log(this.rowCollectionSelected);
        },

        getMeProduct(folio){
            axios.get('/sales/retrieveproduct/'+folio).then((res) => {
                console.log(res);
                this.productsAdded.push(this.prepareRecords(res.data[0]));
                this.status = 'Productos Agregados';
                this.processing = false;
                this.form.no_products = this.productsAdded.length;
                this.form.productosRelacionados = this.productsAdded;
                console.log(this.productsAdded);
            }).catch((error) => {
                console.log(error.res.data);
            });
        },
        prepareRecords(record){
            let element = record;
            this.form.total = parseFloat(this.form.total) + parseFloat(element.price_customer);
            this.form.total = this.form.total.toFixed(2);
            record.price_customer = '$ '+ record.price_customer + ' MXN';
            return record;
        },
        calculateExchange(){
            this.form.outbound_amount =  parseFloat(this.form.inbound_amount) - parseFloat(this.form.total);
        }

    },
    mounted(){
        this.dt = $('#datatable').DataTable();
        this.dt.on( 'select', () => this.onRowClick())
        this.dt.on( 'deselect', () => this.onRowClick())
        
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
                            <h3 class="text-lg text-gray-900"> Venta de la venta </h3>
                            <br/>
                            <table>
                                <tr>
                                    <td>
                                        <b>
                                            <span v-if="processing">AGREGANDO PRODUCTOS  . . . . </span>
                                            <span v-else>PRODUCTOS AGREGADOS: </span>
                                        </b>
                                    </td>
                                    <td>{{ productsAdded.length }} </td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel for="products" value="Agregar productos" />
                                <TextInput
                                    id="products"
                                    ref="products"
                                    v-model="realtime.value"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autocomplete="name"
                                    v-on:keyup.enter="onEnter"
                                />
                            </div>
                            <br/>
                            <center>
                                <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="30"
                                    :dots-num="6"
                                    color="red" v-if="processing"
                                    />
                            </center>
                            <hr class="my-6"/>

                            <DataTable 
                                class="cell-border compact stripe hover order-column loading"
                                ref="table" id="datatable"
                                :data="productsAdded"
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
                                    {data:'id'},
                                    {data:'name'},
                                    {data:'Description'},
                                    {data:'price_customer'},
                                    {data:'expiry_date'}
                            ]">
                                <thead>
                                    <tr>
                                        <th> ID DB </th>
                                        <th>NOMBRE PRODUCTO</th>
                                        <th>DESCRIPCION</th>
                                        <th>PRECIO CLIENTE</th>
                                        <th>FECHA DE CADUCIDAD</th>
                                    </tr>
                                </thead>
                            </DataTable>

                            <hr class="my-6"/>
                            <div class="flex flex-row flex-wrap">
                                <div class="basis-1/3 p-1" >
                                    <InputLabel for="payment_method" value="Método de pago" class="m-1"/>
                                    <select id="payment_method" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                        ref="payment_method"
                                        v-model="form.payment_method">
                                        <option value="cash">Efectivo</option>
                                        <option value="card">Tarjeta</option>
                                        <option value="cash">Domicilio Efectivo</option>
                                        <option value="card">Domicilio Tarjeta</option>
                                        <option value="other">Otro</option>
                                    </select>
                                </div>
                                <div class="basis-1/3 p-1" >
                                    <InputLabel for="delivery_method" value="Método de venta" class="m-1"/>
                                    <select id="delivery_method" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                        ref="delivery_method"
                                        v-model="form.delivery_method">
                                        <option value="delivery">A Domicilio</option>
                                        <option value="on-site">En tienda</option>
                                        <option value="remote">Pago por internet</option>
                                    </select>
                                </div>
                                <div class="basis-1/3 p-1" >
                                    <InputLabel for="status" value="Estado de venta" class="m-1"/>
                                    <select id="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                        ref="status"
                                        v-model="form.status">
                                        <option value="open">Abierto</option>
                                        <option value="in-progress">En progreso</option>
                                        <option value="closed">Venta cerrada</option>
                                    </select>
                                </div>
                            </div>
                            <InputLabel for="note" value="Nota de la venta" />
                            <TextInput
                                id="note"
                                ref="note"
                                v-model="form.note"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <div class="flex flex-row flex-wrap">
                                <div class="basis-1/2 p-1" >
                                    <InputLabel for="inbound_amount" value="Dinero recibido >> $ MXN" />
                                    <TextInput
                                        id="inbound_amount"
                                        ref="inbound_amount"
                                        v-model="form.inbound_amount"
                                        type="number"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        v-on:keyup="calculateExchange"
                                    />
                                </div>
                                <div class="basis-1/2 p-1" >
                                    <InputLabel for="outbound_amount" value="Cambio al cliente << $ MXN" />
                                    <TextInput
                                        id="outbound_amount"
                                        ref="outbound_amount"
                                        v-model="form.outbound_amount"
                                        type="number"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        disabled="true"
                                    />
                                </div>
                            </div>

                        </div>
                        <br/>
                        <br/>
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    <FooterPos 
        :selectedProducts="rowCollectionSelected.length" 
        :products="productsAdded"
        :total="form.total"
        :sale="form"
        v-if="productsAdded.length > 0"/>
</template>
