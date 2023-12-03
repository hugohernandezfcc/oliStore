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
import Field from '@/Components/Field.vue';
import $ from 'jquery';
import "datatables.net-responsive-dt/css/responsive.dataTables.min.css"
import "datatables.net-dt/css/jquery.dataTables.min.css"
import 'datatables.net-responsive-bs5';
import 'datatables.net-select';
import FooterPos from '@/Components/FooterPos.vue';
import round10 from 'round10';
import { HollowDotsSpinner } from 'epic-spinners';



export default{
    components:{
        AppLayout,
        InputLabel,
        TextInput,
        PrimaryButton, SecondaryButton, FooterPos, SecondaryButtonPay, HollowDotsSpinner, Field
    },
    props:{
        sale: Object,
        Sales: Array,
        results: Object,
        SalesYesterday: Array,
        resultsYesterday: Object,
        productFilters: Array
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
            ],
            productFiltersLocal: [],
            resultCss: 'margin-top hidden',
            dialogFormVisible: false,
            modal: {
                salesIn : 'money',
                salesValues : '',
            },
            finallyAddRecord:null,
            finallyAddRecordDescription : '',
            labelSalesValues: 'Especifica el monto en PESOS   -  $ MXN',
            expressProductCreation: false,
            expressProductCreationTitle: '',
            formNewRecords:{
                name: '',
                folio: '',
                Description: 'EXPRESS CREATION',
                unit_measure: '',
                price_list: 0,
                price_customer: '',
                profit_percentage: '',
                take_portion: false,
                express_creation: true
            }
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
                this.resultCss = 'margin-top hidden';
                this.processing = false;
            }else if(this.realtime.value.length > 2){
                this.resultCss = 'margin-top';
                this.processing = false;
            }else if(this.realtime.value.length == 0){
                this.resultCss = 'margin-top hidden';
                this.processing = false;
            }
        },
        enterClicking(param){
            this.processing = true;
            console.log(param);
            this.getMeProduct(param);

            this.realtime.value = '';
            this.resultCss = 'margin-top hidden';
            setTimeout(() => {
                this.processing = false;
            }, 1200);
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

                let element = this.prepareRecords(res.data[0]);
                if( element != 'waiting-for'){

                    this.productsAdded.push(element);
                    this.status = 'Productos Agregados';
                    this.processing = false;
                    this.form.no_products = this.productsAdded.length;
                    this.form.productosRelacionados = this.productsAdded;
                    console.log(this.productsAdded);
                }
            }).catch((error) => {
                console.log(error);
                this.processing = false;
                this.expressProductCreation = true;

                if(folio.length == 13){
                    this.formNewRecords.folio =  folio;
                }else{
                    this.formNewRecords.name =  folio;  
                }
                this.status = 'Productos Agregados';
            });
        },
        prepareRecords(record){
            let element = record;

            if(element.take_portion){
                this.modalConfirmation(element);
                return 'waiting-for';
            }

            if(!element.take_portion){
                element.final_price = element.price_customer;
                this.form.total = parseFloat(this.form.total) + parseFloat(element.price_customer);
                this.form.total = this.form.total.toFixed(2);
                record.price_customer = '$ '+ record.price_customer + ' MXN';
                return record;
            }
        },
        modalConfirmation(recordToReturn){
            console.log(recordToReturn);

            this.dialogFormVisible = true;
            this.finallyAddRecord = recordToReturn; 
            this.labelSalesValues = 'Especifica el monto en PESOS   -  $ MXN';   
            this.modal.salesIn = 'money';
            this.modal.salesValues = '';

        },
        finallyCreateNewOne(){
            
            let ganancia = this.formNewRecords.price_customer;
            ganancia = ganancia*0.20;
            this.formNewRecords.price_list = this.formNewRecords.price_customer - ganancia;
            this.formNewRecords.profit_percentage = 20;
            
            console.log(this.formNewRecords);
            
            axios.post('/storeProductFromPos/', this.formNewRecords ).then((res) => {

                console.log(res.data);

                res.data.final_price = parseFloat(res.data.price_customer);
                this.form.total = parseFloat(this.form.total) + parseFloat(res.data.price_customer);
                this.form.total = this.form.total.toFixed(2);
                res.data.price_customer = '$ '+ res.data.price_customer + ' MXN';


                this.productsAdded.push(res.data);
                this.status = 'Productos Agregados';
                this.processing = false;
                this.form.no_products = this.productsAdded.length;
                this.form.productosRelacionados = this.productsAdded;

                console.log(this.productsAdded);
                this.expressProductCreation = false;

            }).catch((error) => {
                console.log(error);
            });

        },
        finallyAdd(){
            let heavy = this.modal.salesValues;
            let operacion = 0;
            if(this.modal.salesIn == 'money'){
                operacion = (parseFloat(heavy)*1000)/this.finallyAddRecord.price_customer;
                this.finallyAddRecord.price_customer = parseFloat(heavy);
                this.finallyAddRecord.Description = operacion.toFixed(2) + ' GRAMOS DE ' + this.finallyAddRecord.name + ' X $'+parseFloat(heavy)+' MXN' ;
                this.finallyAddRecord.unit_measure = 'GRAMOS';
                this.finallyAddRecord.quantity = operacion;
            }else if(this.modal.salesIn == 'grammes'){
                this.finallyAddRecord.price_customer = (this.finallyAddRecord.price_customer/1000)*parseFloat(heavy);
                this.finallyAddRecord.Description = heavy + ' GRAMOS DE ' + this.finallyAddRecord.name + ' X $'+this.finallyAddRecord.price_customer+' MXN';
                this.finallyAddRecord.unit_measure = 'GRAMOS';
                this.finallyAddRecord.quantity = parseFloat(heavy);
            }

            this.finallyAddRecord.final_price = this.finallyAddRecord.price_customer;
            this.form.total = parseFloat(this.form.total) + this.finallyAddRecord.price_customer;
            this.form.total = this.form.total.toFixed(2);
            this.finallyAddRecord.price_customer = '$ '+ this.finallyAddRecord.price_customer + ' MXN';


            this.productsAdded.push(this.finallyAddRecord);
            this.status = 'Productos Agregados';
            this.processing = false;
            this.form.no_products = this.productsAdded.length;
            this.form.productosRelacionados = this.productsAdded;
            console.log(this.productsAdded);
            this.dialogFormVisible = false;
            
        },
        calculateExchange(){
            this.form.outbound_amount =  parseFloat(this.form.inbound_amount) - parseFloat(this.form.total);
        },
        onChangeSalesType(e){
            console.log(e);
            console.log(e.value);
            if(e == 'money'){
                this.labelSalesValues = 'Especifica el monto en PESOS   -  $ MXN';
            }

            if(e == 'grammes'){
                this.labelSalesValues = 'Especifica el gramaje';
            }
            this.calclsRealTime();
        },
        calclsRealTime(){
            let operacion = 0;
            let heavy = this.modal.salesValues;

            if(parseFloat(heavy) == NaN)
                this.finallyAddRecordDescription = '';

            if(this.modal.salesIn == 'money'){
                operacion =  (parseFloat(heavy)*1000)/this.finallyAddRecord.price_customer;

                this.finallyAddRecordDescription = operacion.toFixed(2) + ' GRAMOS DE ' + this.finallyAddRecord.name + ' X $'+parseFloat(heavy)+' MXN' ;

            }else if(this.modal.salesIn == 'grammes'){

                this.finallyAddRecordDescription = heavy + ' GRAMOS DE ' + this.finallyAddRecord.name + ' X $'+((this.finallyAddRecord.price_customer/1000)*parseFloat(heavy)).toFixed(2)+' MXN';
            }
            console.log(this.finallyAddRecordDescription);
        },
        filteredList() {
            if(this.productFiltersLocal !== undefined)
                return this.productFiltersLocal.filter((productName) =>
                    productName.Description.toLowerCase().includes(this.realtime.value.toLowerCase()) || productName.name.toLowerCase().includes(this.realtime.value.toLowerCase())
                );
        }
        
    },
    
    mounted(){
        this.dt = $('#datatable').DataTable();
        this.dt.on( 'select', () => this.onRowClick())
        this.dt.on( 'deselect', () => this.onRowClick())
        console.log(this.results);
        this.productFiltersLocal = this.productFilters;

        console.log(this.productFiltersLocal);
    }
}


// (20*1000)/104  => calcular peso por la cantidad solicitada
// (104/1000)*192.6   => calcular precio por el gramaje solicitado
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                OliStore
            </h2>
        </template>


        <el-dialog v-model="dialogFormVisible" :title="finallyAddRecordDescription">
            <div class="md:w-full lg:px-[20%]">
                <InputLabel for="salesIn" value="Venta en " class="m-1"/>
                <el-select id="salesIn" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"  @change="onChangeSalesType($event)"
                    ref="salesIn"
                    v-model="modal.salesIn">
                    <el-option
                        v-for="item in [
                            {value:'money', label:'$ MXN'},
                            {value:'grammes', label:'Gramos'}
                        ]"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                    />
                </el-select>
            </div><br/>
            <Field id="salesValues"      :label="labelSalesValues"  v-model="modal.salesValues"       typeField="number"  v-on:keyup="calclsRealTime" />
            <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogFormVisible = false">Cancelar</el-button>
                <el-button type="danger" @click="finallyAdd">
                    Agregar producto 
                </el-button>
            </span>
            </template>
        </el-dialog>


        <el-dialog v-model="expressProductCreation" :title="expressProductCreationTitle">
            
            <Field id="name"            :label="'NOMBRE PRODUCTO'"  v-model="formNewRecords.name"               typeField="text"/>
            <Field id="folio"           :label="'FOLIO'"            v-model="formNewRecords.folio"              typeField="text"/>
            <Field id="unit_measure"    :label="'UNIDAD DE MEDIDA'" v-model="formNewRecords.unit_measure"       typeField="text"/>
            <Field id="price_customer"  :label="'PRECIO CLIENTE'"   v-model="formNewRecords.price_customer"     typeField="number"/>
            <br/>
            <div class="md:w-full lg:px-[19%]">
                <el-switch
                    v-model="formNewRecords.take_portion"
                    class="mb-2"
                    active-text="Se vende a granel"
                    inactive-text=" "
                />
            </div>
            <template #footer>
            <span class="dialog-footer">
                <el-button @click="expressProductCreation = false">Cancelar</el-button>
                <el-button type="danger" @click="finallyCreateNewOne">
                    Agregar producto 
                </el-button>
            </span>
            </template>
        </el-dialog>



        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">

                            <el-collapse  accordion class="shadow bg-white md:rounded-md p-4">
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
                                    v-on:keyup="onEnter"
                                />
                            </div>
                            <br/>
                            <el-descriptions
                            
                                :class="`m-1 cursor-pointer ${resultCss}`"
                                :column="3"
                                border v-for="productName in filteredList()" :key="productName"
                                @click.prevent="enterClicking(productName.folio)"
                            >
                                <el-descriptions-item>
                                    <template #label>
                                        <div class="cell-item">
                                            {{ productName.name }}
                                        </div>
                                    </template>
                                    {{ productName.folio }}
                                </el-descriptions-item>

                                <el-descriptions-item>
                                    <template #label>
                                        <div class="cell-item">
                                            Descripción
                                        </div>
                                    </template>
                                    {{ productName.Description }}
                                </el-descriptions-item>

                            </el-descriptions>
                            <div class="item error" v-if="input && !filteredList().length">
                                <p>No results found!</p>
                            </div>
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
                                    <InputLabel for="inbound_amount" value="Dinero recibido >> $" />
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
                                    <InputLabel for="outbound_amount" value="Cambio al cliente << $" />
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
        :key="componentKey"
        :selectedProducts="rowCollectionSelected.length" 
        :products="productsAdded"
        :total="form.total"
        :sale="form"
        @destroy="deleteRow"
        v-if="productsAdded.length > 0"/>
</template>


