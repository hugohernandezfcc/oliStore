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
import { ref, getCurrentInstance } from 'vue';
import axios from 'axios';

import $ from 'jquery';
import "datatables.net-responsive-dt/css/responsive.dataTables.min.css"
import "datatables.net-dt/css/jquery.dataTables.min.css"
import 'datatables.net-responsive-bs5';
import 'datatables.net-select';
import FooterPos from '@/Components/FooterPos.vue';

import { HollowDotsSpinner } from 'epic-spinners';



export default{
    components:{
        AppLayout,
        InputLabel,
        TextInput,
        PrimaryButton, SecondaryButton, FooterPos, SecondaryButtonPay, HollowDotsSpinner
    },
    props:{
        sale: Object,
        Sales: Array,
        results: Object,
        SalesYesterday: Array,
        resultsYesterday: Object
        
    },
    setup() {

    const componentKey = ref(0);
    
    function refreshComponent() {
      componentKey.value += 1;
    }

    return {
      componentKey,
      refreshComponent,
    };
  },
    data(){

        return {
            consultarProducto:'',
            form: {
                payment_method: 'cash',
                delivery_method: 'on-site',
                status: 'open',
                no_products: '',
                note: '',
                store: 'Oli Store 1',
                inbound_amount: '',
                outbound_amount: '',
                subtotal: 0,
                total: 0,
                closed: true,
                productosRelacionados: []
            },
            realtime: {
                value: ''
            },
            productsAdded:[],
            rowCollectionSelected: new Array(),
            processing: false,
            total: 0,
            componentKey: 0,
            accordionItems: [
        { title: 'Section 1', content: 'Content for Section 1' },
        { title: 'Section 2', content: 'Content for Section 2' },
        // Add more accordion items as needed
      ]
        }
    },
    methods:{
        

        onEnter(e){
            this.processing = true;
            if (e.keyCode === 13) {
                console.log(e);
                console.log('Enter was pressed');
                console.log(this.realtime.value);
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
        deleteRow(){

            let rowCollectionSelected = new Array();
            let i = 0;
            this.dt.rows({ selected: true }).data().each( function ( recordSelected, index ) {
                rowCollectionSelected.push(recordSelected.folio);
                i++;
                console.log(i + '.- ' +recordSelected.folio);
                
            } );

            let rowCollectionSelectedKeep = new Array();

            for (let index = 0; index < this.productsAdded.length; index++) {
                const element = this.productsAdded[index];
                console.log(index + '.- ' +element.folio);
                if(!rowCollectionSelected.includes(element.folio)){
                    rowCollectionSelectedKeep.push(element);
                }
            }
            
            this.productsAdded = rowCollectionSelectedKeep;
            this.form.productosRelacionados = [];
            this.componentKey += 1; 
            this.form.total    = 0;
            this.rowCollectionSelected = new Array();
            this.form.no_products = this.productsAdded.length;
            for (let o = 0; o < this.productsAdded.length; o++) {
                const element = this.productsAdded[o];
                element.price_customer = element.price_customer.replace(' MXN','');
                element.price_customer = element.price_customer.replace('$ ','');
                console.log(element);
                this.form.total = parseFloat(this.form.total) + parseFloat(element.price_customer);
                this.form.total = this.form.total.toFixed(2);
                this.form.productosRelacionados.push(element);
            }
            console.log(this.form.total);
            console.log(this.form.productosRelacionados);
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
                this.processing = false;
                alert('producto no encontrado');
                this.status = 'Productos Agregados';
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
        console.log(this.results);
        
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

                            <el-collapse v-model="activeName" accordion class="shadow bg-white md:rounded-md p-4">

                            <el-collapse-item title="Historial de la venta hoy" name="1" >

                                <table class="grid grid-cols-1 divide-y hidden md:block m-1" v-for="item in Sales">
                                    <tr >
                                        <td># Prod:</td><td><b>{{ item.no_products }}</b> </td> <td> | $ </td><td><b>{{ item.total }}</b> </td> <td> |   </td><td><b>{{ item.created_at }}</b> </td>
                                    </tr>
                                </table>
                                <hr class="my-6"/>
                                <div>
                                    Total de prod. Vds.: {{ results.no_products }} | TOTAL $ {{ results.total }} MXN | # Ventas: {{ Sales.length }}
                                </div>
                            </el-collapse-item>
                            <el-collapse-item title="Historial de la venta ayer" name="2">
                                <table class="grid grid-cols-1 divide-y hidden md:block m-1" v-for="item in SalesYesterday">
                                    <tr >
                                        <td># Prod:</td><td><b>{{ item.no_products }}</b> </td> <td> | $ </td><td><b>{{ item.total }}</b> </td> <td> |   </td><td><b>{{ item.created_at }}</b> </td>
                                    </tr>
                                </table>
                                <hr class="my-6"/>
                                <div>
                                    Total de prod. Vds.: {{ resultsYesterday.no_products }} | TOTAL $ {{ resultsYesterday.total }} MXN | # Ventas: {{ SalesYesterday.length }}
                                </div>
                            </el-collapse-item>
                            <el-collapse-item title="Consultar un producto" name="3">
                                <el-autocomplete
                                    v-model="consultarProducto"
                                    :fetch-suggestions="querySearch"
                                    :trigger-on-focus="false"
                                    clearable
                                    class="inline-input w-50"
                                    placeholder="Please Input"
                                    @select="handleSelect"
                                />
                            </el-collapse-item>
                            <el-collapse-item title="Lista de productos a surtir" name="4">
                                pendiente
                            </el-collapse-item>
                            </el-collapse>



                            
                            <!-- <br/>
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
                            <br/>
                            <b class="grid grid-cols-1 divide-y hidden md:block"> HISTORIAL DE VENTA </b> -->
                            
                            
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
                                    <el-select id="payment_method" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                        ref="payment_method"
                                        v-model="form.payment_method">
                                
                                        <el-option
                                            v-for="item in [
                                                {value:'cash', label:'Efectivo'},
                                                {value:'card', label:'Tarjeta'},
                                                {value:'delivery cash', label:'Domicilio Efectivo'},
                                                {value:'delivery card', label:'Domicilio Tarjeta'},
                                                {value:'other', label:'Otro'}
                                            ]"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value"
                                        />
                                    </el-select>
                                </div>
                                <div class="basis-1/3 p-1" >
                                    <InputLabel for="delivery_method" value="Método de venta" class="m-1"/>
                                    <el-select id="delivery_method" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                        ref="delivery_method"
                                        v-model="form.delivery_method">
                                        <el-option
                                            v-for="item in [
                                                {value:'on-site', label:'En tienda'},
                                                {value:'delivery', label:'A Domicilio'},
                                                {value:'remote', label:'Pago por internet'}
                                            ]"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value"
                                        />

                                    </el-select>
                                </div>
                                <div class="basis-1/3 p-1" >
                                    <InputLabel for="status" value="Estado de venta" class="m-1"/>
                                    <el-select id="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                        ref="status"
                                        v-model="form.status">
                                        <el-option
                                            v-for="item in [
                                                {value:'open', label:'Abierto'},
                                                {value:'in-progress', label:'En progreso'},
                                                {value:'closed', label:'Venta cerrada'}
                                            ]"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value"
                                        />
                                    </el-select>
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

<Accordion />

    <FooterPos 
        :key="componentKey"
        :selectedProducts="rowCollectionSelected.length" 
        :products="productsAdded"
        :total="form.total"
        :sale="form"
        @destroy="deleteRow"
        v-if="productsAdded.length > 0"/>
</template>


