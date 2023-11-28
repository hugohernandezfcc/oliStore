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
                this.getMeProduct(this.form.name);
            }
        },
        getMeProduct(folio){
            axios.get('/sales/retrieveproduct/'+folio).then((res) => {
                console.log(res);
                
            }).catch((error) => {
                console.log(error.res.data);
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
                name: ''
            }
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
                            <Field id="folio" :label="'Código de barras'" v-model="form.name" typeField="text" :required="true" v-on:keyup="onEnter" />

                        </div>
                        <div v-if="radio1 == '2'">
                            ddd
                        </div>
                        <hr class="my-6"/>
                        <el-button style="margin-top: 12px" @click="next">Siguiente</el-button>
                    </div>

                    <div v-if="active == 1">
                        sacdasdc1
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
