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
import wizardForm from '@/Components/TicketForm.vue';
import EditTicketItem from './EditTicketItem.vue'
import axios from 'axios';
import { parse } from 'vue/compiler-sfc';


export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        wizardForm,
        EditTicketItem
    },

    props:{
        tickets: Array,
        total_investment: Number,
        totalTickets: Number
    },
    methods:{
        show(id) {
            alert(id);
        },
        deleteItem(id, ticket_id){
            if(!confirm('¿Deseas eliminar este producto del ticket?')){
                return;
            }
            axios.get(`tickets/destroyItem/${id}`).then(response => {
                console.log(response.data);
                for (let i = 0; i < this.tickets.length; i++) {
                    if (this.tickets[i].id === ticket_id) {
                        this.tickets[i].ticket_items = this.tickets[i].ticket_items.filter(item => item.id !== response.data);
                    }
                }


            }).catch(error => {
                console.log(error);
            });
        },
        
        deleteTicket(id){
            if(!confirm('¿Deseas eliminar este ticket?')){
                return;
            }
            axios.get(`tickets/destroy/${id}`).then(response => {
                console.log(response.data);
                const index = this.tickets.findIndex(ticket => ticket.id === response.data);
                if (index !== -1) {
                    this.tickets.splice(index, 1);
                }
            }).catch(error => {
                console.log(error);
            });
        },
        handleClick(row) {
            console.log(row);
            let unitcost = row.unitCost;
            unitcost = parseFloat(unitcost.replace('$ ', '').replace(' MXN', ''));

            this.view = 'product-details';
            axios.get('/sales/retrieveproduct/' + row.bar_code).then(response => {
                this.dialogVisible = true;
                this.productDetail = response.data[0];
                this.productDetailDiff = row;
                this.diff = (unitcost - parseFloat(this.productDetail.price_list)).toFixed(2);
                console.log(this.diff);
                console.log(response.data[0]);
            }).catch(error => {
                console.log(error);
            });
        },
        closeModal(){
            this.dialogVisible = false;
        },
        applyPrice(row) {
            console.log(row);
            this.view = 'product-apply-price';
            console.log(row);
            let unitcost = row.unitCost;
            unitcost = parseFloat(unitcost.replace('$ ', '').replace(' MXN', ''));

            axios.get('/sales/retrieveproduct/' + row.bar_code).then(response => {
                this.dialogVisible = true;
                this.productDetail = response.data[0];
                this.productDetailDiff = row;
                this.form.price_list = unitcost;
                this.diff = (unitcost - parseFloat(this.productDetail.price_list)).toFixed(2);
                this.form.price_customer = parseFloat(this.productDetail.price_customer)+parseFloat(this.diff);
                console.log(this.diff);
                this.form.description = (this.diff > 0) ? `Perdida de $ ${this.diff} MXN` : `Ganancia de $ ${this.diff} MXN`;
                this.form.product_id  = this.productDetail.id;
                console.log(response.data[0]);
            }).catch(error => {
                console.log(error);
            });   
        },
        submitNewPrice(){
            console.log(this.form);
            let revenue = this.form.price_customer - this.form.price_list;
            let xrev = revenue / this.form.price_list;
            axios.post(route('core.store.price'), {
                product_id: this.form.product_id,
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
                window.location.reload();
            }).catch(error => {
                console.log(error);
                this.$message({
                    showClose: true,
                    message: 'Error al guardar el precio',
                    type: 'error'
                });
            });
        },

    },
    data(){
        return {
            rowCollectionSelected: new Array(),
            search:'',
            dialogVisible: false,
            form: {
                product_id: '',
                price_list: '',
                price_customer: '',
                activar: false,
                description: '',
                bulk_sale: false
            },
            view: '',
            productDetail: {},
            productDetailDiff: {},
            diff: 0,
        }
    },
    mounted(){
        
    },
    computed: {
        filterTableData() {
            return this.tickets.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        },
    }
}

</script>

<template>
    <el-dialog v-model="dialogVisible" class="custom-dialog" :center="true">

        <div v-if="view == 'product-details'" class="flex flex-wrap-reverse ">
            <div class="m-2 pl-3 text-center">
                <div v-html="productDetail.bar_code" /> 
                {{productDetail.folio}}
            </div>
            <div class="m-2 pl-2">
                <h2 class="text-lg font-semibold text-red-600">{{ productDetail.Description }}</h2>
                <el-divider>Precios actuales</el-divider>
                <p class="text-base text-gray-600">Precio lista: $ {{ productDetail.price_list }} MXN</p>
                <p class="text-base text-gray-600">Precio cliente: $ {{ productDetail.price_customer }}  MXN</p>
                <el-divider><span class="bg-red-200">Precios Ticket</span></el-divider>
                <p class="text-base text-gray-600">Precio lista: {{ productDetailDiff.unitCost }} MXN</p>
                <p class="text-base text-gray-600">Cantidad: # {{ productDetailDiff.quantity }} </p>

                <p class="text-base text-red-600" v-if="diff > 0">Perdida de $ {{diff}} MXN
                    <el-icon :size="15" :color="'#ec0505'" class="m-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M352 768a32 32 0 1 0 0 64h448a32 32 0 0 0 32-32V352a32 32 0 0 0-64 0v416z"></path><path fill="currentColor" d="M777.344 822.656a32 32 0 0 0 45.312-45.312l-544-544a32 32 0 0 0-45.312 45.312z"></path></svg>
                    </el-icon>
                </p>
                <p class="text-base text-green-600" v-if="diff < 0">Ganancia de $ {{diff}} MXN
                    <el-icon :size="15" :color="'#ec0505'" class="m-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M768 256H353.6a32 32 0 1 1 0-64H800a32 32 0 0 1 32 32v448a32 32 0 0 1-64 0z"></path><path fill="currentColor" d="M777.344 201.344a32 32 0 0 1 45.312 45.312l-544 544a32 32 0 0 1-45.312-45.312l544-544z"></path></svg>
                    </el-icon>
                </p>
            </div>
        </div>
        <div v-if="view == 'product-apply-price'" class="flex flex-wrap-reverse ">
            <div class="m-2 pl-3 text-center">
                <el-form :label-width="'180px'" :label-position="'top'">
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
            </div>
            <div class="m-2 pl-2">
                <h2 class="text-lg font-semibold text-red-600">{{ productDetail.Description }}</h2>
                <el-divider>Precios actuales</el-divider>
                <p class="text-base text-gray-600">Precio lista: $ {{ productDetail.price_list }} MXN</p>
                <p class="text-base text-gray-600">Precio cliente: $ {{ productDetail.price_customer }}  MXN</p>
                <el-divider><span class="bg-red-200">Precios Ticket</span></el-divider>
                <p class="text-base text-gray-600">Precio lista: {{ productDetailDiff.unitCost }}</p>
                <p class="text-base text-gray-600">Cantidad: # {{ productDetailDiff.quantity }}</p>

                <p class="text-base text-red-600" v-if="diff > 0">Perdida de $ {{diff}} MXN
                    <el-icon :size="15" :color="'#ec0505'" class="m-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M352 768a32 32 0 1 0 0 64h448a32 32 0 0 0 32-32V352a32 32 0 0 0-64 0v416z"></path><path fill="currentColor" d="M777.344 822.656a32 32 0 0 0 45.312-45.312l-544-544a32 32 0 0 0-45.312 45.312z"></path></svg>
                    </el-icon>
                </p>
                <p class="text-base text-green-600" v-if="diff < 0">Ganancia de $ {{diff}} MXN
                    <el-icon :size="15" :color="'#ec0505'" class="m-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M768 256H353.6a32 32 0 1 1 0-64H800a32 32 0 0 1 32 32v448a32 32 0 0 1-64 0z"></path><path fill="currentColor" d="M777.344 201.344a32 32 0 0 1 45.312 45.312l-544 544a32 32 0 0 1-45.312-45.312l544-544z"></path></svg>
                    </el-icon>
                </p><br/>
                <div v-html="productDetail.bar_code" /> 
                {{productDetail.folio}}
            </div>
        </div>
    </el-dialog>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                OliStore
            </h2>
        </template>
        
        <div class="m-4">
            <h3 class="text-lg text-gray-900"> Listado de Tickets - #{{ totalTickets }}</h3>
            <p class="text-sm text-gray">Catalogo de Tickets Registrados en la base de datos </p>
        </div>

        <EditTicketItem ref="editTicketItemRef" />



        <div class="shadow bg-white md:rounded-md p-4 m-4">
            
            <el-row :gutter="20">
                <el-col :span="16">
                    
                    <PrimaryButton @click="this.$refs.editTicketItemRef.openModalForm(null, 'ticketForm')">
                        Documentar Ticket
                    </PrimaryButton>

                </el-col>
                <el-col :span="8">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </el-col>
            </el-row>
            <br/>
            <el-table :data="filterTableData" class="shadow-lg" stripe style="width: 100%; height: 500px;" >
                
                    
                
                <el-table-column type="expand" >
                    <template #default="props" >
                        <div class="m-6 ">
                            <div class="h-15 bg-gradient-to-r from-white to-red-600">
                                <el-button size="small" @click="deleteTicket(props.row.id)" color="#dc2626">Eliminar ticket completo</el-button>
                                <br/>
                                <b>Id de ticket:</b> {{ props.row.id }}<br/>
                                <b>No. Ticket:</b> {{ props.row.noTicket }}
                            </div>
                            <br/>
                            
                            <el-table :data="props.row.ticket_items" :border="true" class="shadow-2xl" >
                                <!-- <el-table-column width="100">
                                    <template #default="subScope">
                                        <el-button size="small" @click="deleteItem(subScope.row.id, props.row.id )" color="#dc2626">Eliminar </el-button>
                                    </template>
                                </el-table-column>

                                <el-table-column width="100">
                                    <template #default="subScope">
                                        <el-button size="small" @click="this.$refs.editTicketItemRef.openModalForm(subScope.row.id, 'editItem')" color="#dc2626">Editar</el-button>
                                    </template>
                                </el-table-column> -->

                                <el-table-column width="200">
                                    <template #default="subScope">
                                        <el-dropdown split-button class="custom-dropdown" trigger="click" type="danger" @click="handleClick(subScope.row)" >
                                            Ver producto 
                                            <template #dropdown>
                                                <el-dropdown-menu>
                                                <el-dropdown-item @click="deleteItem(subScope.row.id, props.row.id )">Eliminar</el-dropdown-item>
                                                <el-dropdown-item @click="this.$refs.editTicketItemRef.openModalForm(subScope.row.id, 'editItem')">Editar</el-dropdown-item>
                                                <el-dropdown-item @click="applyPrice(subScope.row)" >Aplicar precio</el-dropdown-item>

                                                </el-dropdown-menu>
                                            </template>
                                        </el-dropdown>
                                    </template>
                                </el-table-column>

                                <el-table-column label="Nombre del producto" prop="product_name" width="200"/>
                                <el-table-column label="Cantidad" prop="quantity" width="200"/>
                                <el-table-column label="Costo en ticket" prop="cost_customer" width="200"/>
                                <el-table-column label="Costo unitario" prop="unitCost" width="200"/>
                                <el-table-column label="Código de barras" prop="bar_code" width="200"/>
                            </el-table>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="provider" label="Provedor" width="100" />
                <el-table-column prop="date_time_issued" label="Fecha de surtido" width="150" />
                <el-table-column prop="total" label="Total" width="150" />
                <el-table-column prop="who_issued_ticket" label="Surtido por" width="150" />
                <el-table-column prop="noTicket" label="Número de ticket" width="150" />
                <el-table-column prop="createddate" label="Fecha de creación" width="150" />
                <el-table-column prop="who_issued_ticket" label="Surtido por" width="150" />
                <el-table-column prop="createdByName" label="Creado por" width="200" />
                <el-table-column prop="noProducts" label="No. Productos" width="150" />
                <el-table-column prop="id" label="Id" width="150" />

                <el-table-column align="right" fixed="right" width="120">
                    <template #default="scope">
                        <inertia-link :href="route('tickets.show', scope.row.id)" >
                            <el-button
                            size="small"
                            color="#dc2626"
                            >Ver detalle</el-button
                            >
                        </inertia-link>
                    </template>
                </el-table-column>

            </el-table>
            <el-divider content-position="center">
                $ {{ total_investment }} MXN total invertido.
            </el-divider>
            <br/>

        </div>
    </AppLayout>
    
</template>
<style >
    .el-button--danger{
        --el-button-bg-color: #dc2626 !important;

    }

    .el-dropdown-menu__item:not(.is-disabled):focus {
        background-color:  #fcc9c9 !important;
        color: #dc2626 !important;
        text-decoration: underline;
    }
    .custom-dialog {
        width: 40%; /* Default to 50% of the screen size on desktop */
    }

    @media (max-width: 768px) {
        .custom-dialog {
            width: 70%; /* Increase width for smaller screens like tablets */
        }
    }

    @media (max-width: 480px) {
        .custom-dialog {
            width: 95%; /* Use almost full width for mobile screens */
        }
    }
</style>