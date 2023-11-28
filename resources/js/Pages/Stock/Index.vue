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
import Field from '@/Components/Field.vue';
import { ElNotification } from 'element-plus';


export default{
    components:{
        AppLayout, PrimaryButton, SecondaryButton, Footer, Field 
    },
    props:{
        stock: Array
        

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
        next()  {
            if (this.active++ > 2) 
                this.active = 0;
        },
        saveAndNext()  {
            console.log(this.form);
        },

        back()  {
            if (this.active-- > 2) 
                this.active = 0;
        },

        changeOption(e){
            console.log(e)
            this.radio1 = e;
        },
        onEnter(e){
            if (e.keyCode === 13) {
                this.getMeProduct(this.form.folio);
            }
        },
        getMeProduct(folio){
            axios.get('/sales/retrieveproduct/'+folio).then((res) => {
                console.log(res);
                this.formResult = res.data[0];
                ElNotification.success({
                    title: 'Success',
                    message: res.data[0].name + ' encontrado',
                    offset: 100,
                })
                this.next();
            }).catch((error) => {

                ElNotification.warning({
                    title: folio,
                    message: 'No fue encontrado',
                    offset: 100,
                });
            });
        }
    },
    data(){
        return {
            rowCollectionSelected: new Array(),
            dt: null,
            active:0,
            activeSection:1,
            radio1:'1',
            form: {
                name: '',
                folio: '',
                Description: '',
                unit_measure: '',
                price_list: '',
                price_customer: '',
                profit_percentage: '',
                expiry_date: ''
            },
            formResult: null
        }
    },
    mounted(){
        this.dt = $('#datatable').DataTable();
        this.dt.on( 'select', () => this.onRowClick())
        this.dt.on( 'deselect', () => this.onRowClick())
        
        console.log(this.rowCollectionSelected.length);
        console.log(this.stores);
    }
}

</script>

<template>
    <AppLayout title="Stock">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                OliStore > Stock
            </h2>
        </template>
        
        <div class="m-4">
            <h3 class="text-lg text-gray-900"> Listado de tiendas </h3>
            <p class="text-sm text-gray">Aquí podrás ver las tiendas registradas con sus detalles</p>
        </div>

        <div class="shadow bg-white md:rounded-md p-4 m-4">
            
            <el-collapse v-model="activeSection" accordion class="shadow bg-white md:rounded-md p-4">
                
                <el-collapse-item title="Historial de la venta ayer" name="2">

                    <el-steps :active="active" finish-status="success"  align-center>
                        <el-step title="Paso 1" description="Encuentra / Registra un producto"/>
                        <el-step title="Paso 2" description="Agrega el número de piesas disponibles"/>
                        <el-step title="Paso 3" description="Confirma el resultado" />
                    </el-steps>
                    <hr class="my-6"/>
                    <div v-if="active == 0" align-center>
                        <center>
                            <el-radio-group v-model="radio1" class="px-12" >
                                <el-radio label="2" size="large" border class="m-2" v-on:change="changeOption('2')">Nuevo producto</el-radio>
                                <el-radio label="1" size="large" border class="m-2" v-on:change="changeOption('1')">Inventariar producto</el-radio>
                            </el-radio-group>
                        </center>
                        <hr class="my-6"/>
                        <div v-if="radio1 == '1'" >
                            <Field id="folio" :label="'Código de barras'" v-model="form.folio" typeField="text" :required="true" v-on:keyup="onEnter" />

                        </div>
                        <div v-if="radio1 == '2'">
                            <Field id="folio"               :label="'Código de barras'"         v-model="form.folio"                typeField="text"    :required="true"  />
                            <Field id="name"                :label="'Nombre del producto'"      v-model="form.name"                 typeField="text"    :required="true"  />
                            <Field id="Description"         :label="'Descripción'"              v-model="form.Description"          typeField="text"    :required="true"  />
                            <Field id="unit_measure"        :label="'Unidad de medida'"         v-model="form.unit_measure"         typeField="number"  :required="true"  />
                            <Field id="price_list"          :label="'Precio de lista'"          v-model="form.price_list"           typeField="number"  :required="true"  />
                            <Field id="price_customer"      :label="'Precio al cliente'"        v-model="form.price_customer"       typeField="number"  :required="true"  />
                            <Field id="profit_percentage"   :label="'Porcentaje de ganancía'"   v-model="form.profit_percentage"    typeField="number"  :required="true"  />
                            <Field id="expiry_date"         :label="'Fecha de caducidad'"       v-model="form.expiry_date"          typeField="date"    :required="true"  />

                            <el-button style="margin-top: 12px" @click="saveAndNext">Crear y siguiente</el-button>
                        </div>
                        <hr class="my-6"/>
                    </div>

                    <div v-if="active == 1" class="md:w-full lg:px-[20%]">
                        <table >
                            <tr>
                                <td><b>PRODUCTO:</b></td>
                                <td>{{formResult.name}}</td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td><b>DESCRIPCION:</b></td>
                                <td>{{formResult.Description}}</td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td ><b>Código de barras:  </b></td>
                                <td class="p-2">
                                    <div v-html="formResult.bar_code" /> 
                                    {{formResult.folio}}
                                </td>
                                <td> </td>
                            </tr>
                        </table>
                        <div class="flex">
                            <div class="flex-none w-32 h-14">
                                <el-button type="danger" circle >-</el-button>
                            </div>
                            <div class="flex-initial w-64 ...">
                                02
                            </div>
                            <div class="flex-initial w-32 ...">
                                <el-button type="success" circle >+</el-button>
                            </div>
                        </div>

                        <hr class="my-6"/>
                        <el-button style="margin-top: 12px" @click="back">Atras</el-button>
                        <el-button style="margin-top: 12px" @click="next">Siguiente</el-button>
                    </div>

                    <div v-if="active == 2">
                        sacdasdc2
                        <hr class="my-6"/>
                        <el-button style="margin-top: 12px" @click="back">Atras</el-button>
                        <el-button style="margin-top: 12px" @click="next">Continuar con otro Producto</el-button>
                    </div>


                    
                    
                </el-collapse-item>
                <el-collapse-item title="Lista de productos a surtir" name="4">
                    pendiente
                </el-collapse-item>
            </el-collapse>

        </div>
    </AppLayout>
</template>
