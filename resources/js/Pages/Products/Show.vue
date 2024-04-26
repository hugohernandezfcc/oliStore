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
            salesList: [],
            search2: '',
            search: '',
            dialogVisible: false,
            form: {
                price_list: '',
                price_customer: '',
                activar: false,
                description: '',
                bulk_sale: false
            }
        }
    },
    methods:{
        
        closeModal(){
            this.dialogVisible = false;
        },
        openModal(){
            this.dialogVisible = true; 
        },
        submitNewPrice(){
            console.log(this.form);
            let revenue = this.form.price_customer - this.form.price_list;
            let xrev = revenue / this.form.price_list;
            axios.post(route('core.store.price'), {
                product_id: this.product.id,
                price_list: this.form.price_list,
                price_customer: this.form.price_customer,
                activar: this.form.activar,
                description: this.form.description,
                revenue: revenue,
                porcentage_revenue: xrev * 100,
                bulk_sale: this.form.bulk_sale ? 1 : 0
            }).then(response => {
                console.log(response);
                this.dialogVisible = false;
                this.$message({
                    showClose: true,
                    message: 'Precio guardado con éxito',
                    type: 'success'
                });

            }).catch(error => {
                console.log(error);
                this.$message({
                    showClose: true,
                    message: 'Error al guardar el precio',
                    type: 'error'
                });
            });
        },
        activarPrecio(id){
            axios.post(route('core.active.price'), {
                product_id: this.product.id,
                active: 1,
                price_id: id 
            }).then(response => {
                console.log(response);

                this.$message({
                    showClose: true,
                    message: 'Precio activado con éxito',
                    type: 'success'
                });
                window.location.reload();
            }).catch(error => {
                console.log(error);
                this.$message({
                    showClose: true,
                    message: 'Error al activar el precio',
                    type: 'error'
                });
            });
        },
        eliminarPrecio(id){
            axios.post(route('core.delete.price'), {
                price_id: id 
            }).then(response => {
                console.log(response);
                
                this.$message({
                    showClose: true,
                    message: 'Precio eliminado con éxito',
                    type: 'success'
                });
                window.location.reload();
            }).catch(error => {
                console.log(error);
                this.$message({
                    showClose: true,
                    message: 'Error al eliminar el precio',
                    type: 'error'
                });
            });
        }
            
    },
    mounted(){
        console.log(this.product);
        for (let index = 0; index < this.product.ProductLineItems.length; index++) {
            const element = this.product.ProductLineItems[index];
            for (let i = 0; i < element.sale.length; i++) {
                const venta = element.sale[i];
                venta.idVenta = 'Venta con Id:' + venta.id;
                venta.total = '$' + venta.total + ' MXN';
                this.salesList.push(venta);
            }
        }
        console.log(this.salesList);
    },
    computed: {
        filterTableData() {
            return this.product.prices.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        },
        filterTableData2() {
            return this.salesList.filter(
                (data) =>
                !this.search2 || JSON.stringify(data).toLowerCase().includes(this.search2.toLowerCase() )
            );
        },
    }
}




</script>

<template>
     <el-dialog v-model="dialogVisible"  :before-close="closeModal" :width="'70%'" :center="true">

        <el-alert type="info" show-icon :closable="false">
            <p>Agregar nuevos precios de lista y cliente. Al activar el precio se actualizará el producto con la información que proporciones.</p>
        </el-alert>
        <br/>
        <el-form :label-width="'180px'" >
            <el-form-item label="Precio de lista">
                <el-input v-model="form.price_list" placeholder="Precio de lista" />
            </el-form-item>
            <el-form-item label="Precio cliente">
                <el-input v-model="form.price_customer" placeholder="Precio cliente" />
            </el-form-item>

            <el-form-item label="Activar precio">
                <el-checkbox v-model="form.activar" :value="form.activar" name="activar">$ MXN</el-checkbox>
            </el-form-item>
            <el-form-item label="Se vende ">
                <el-checkbox v-model="form.bulk_sale" :value="form.bulk_sale" name="bulk_sale">Granel</el-checkbox>
            </el-form-item>
            <el-form-item label="Descripción">
                <el-input v-model="form.description" placeholder="El cambio es porque ha salido más caro." />
            </el-form-item>
            <el-row>
                <el-col :span="16" class="text-center">
                    <el-button color="#dc2626" @click="submitNewPrice" type="primary">Guardar  precio</el-button>
                </el-col>
                <el-col :span="8">
                    <el-button @click="closeModal">Cancelar</el-button>
                </el-col>
            </el-row>
        </el-form>
        <el-divider content-position="left">Comentarios</el-divider>
        precios anteriores
    </el-dialog>

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
                                    <td>{{product.created_by.name}}</td>
                                </tr>
                                <tr>
                                    <td><b>ULTIMA ACTUALIZACIÓN:</b></td>
                                    <td>{{product.edited_by.name}}</td>
                                </tr>

                                <tr>
                                    <td><b>SE VENDE A GRANEL:</b></td>
                                    <td v-if="product.take_portion">Si, se vende</td>
                                    <td v-else="product.take_portion">No, se vende por unidad</td>
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

                            

                            <el-input v-model="search2"  placeholder="Type to search" class="shadow-2xl"/>
                            <el-table :data="filterTableData2" class="shadow-lg" stripe style="height: 300px;" >

                                <el-table-column prop="idVenta" label="Id" width="150" />
                                <el-table-column prop="created_at" label="Fecha creación" width="150" />
                                <el-table-column prop="store" label="Tienda" width="120" />
                                <el-table-column prop="total" label="Total de la venta" width="130" />
                                <el-table-column prop="payment_method" label="Método pago" width="110" />
                                <el-table-column align="right" fixed="right" width="120">
                                    <template #default="scope">
                                        <inertia-link :href="route('sales.show', scope.row.id)" >
                                            <el-button
                                            size="small"
                                            color="#dc2626"
                                            >Ver detalle</el-button
                                            >
                                        </inertia-link>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <br/>
                            <el-button
                                @click="openModal"
                                color="#dc2626"
                                >Agregar nuevo precio
                            </el-button>
                            <br/>
                            <br/>
                            <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                            <el-table :data="filterTableData" class="shadow-lg" stripe style="height: 300px;" >

                                <el-table-column label="Precio de lista" width="130" >
                                    <template #default="scope">
                                        <span>$ {{scope.row.price_list}} MXN</span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="Precio cliente" width="130" >
                                    <template #default="scope">
                                        <span>$ {{scope.row.price_customer}} MXN</span>
                                    </template>
                                </el-table-column>
                                <el-table-column  label="% Ganancia" width="150" >
                                    <template #default="scope">
                                        <span>{{scope.row.porcentage_revenue}} %</span>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="description" label="Descripción" width="130" />
                                <el-table-column align="right" fixed="right" width="100">
                                    <template #default="scope">
                                        <el-button @click="eliminarPrecio(scope.row.id) "
                                            size="small"
                                            >Eliminar</el-button>
                                    </template>
                                </el-table-column>
                                <el-table-column align="right" fixed="right" width="100">
                                    <template #default="scope">
                                        <el-button @click="activarPrecio(scope.row.id) "
                                            size="small"
                                            color="#dc2626"
                                            >Activar</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>

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


