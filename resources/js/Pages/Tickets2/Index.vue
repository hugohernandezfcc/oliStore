<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ElMessage } from 'element-plus';
import LookupField from  '@/Components/LookupField.vue';
import { ElLoading } from 'element-plus';
import EditTicketItem from './EditTicketItem.vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        ElMessage,
        LookupField,
        ElLoading,
        EditTicketItem
    },

    props:{
        tickets: Array 
    },
    methods:{
        redirectToCreate(){
            Window.location.href = '/tickets/create';
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
            this.loading2 = true;
            let unitcost = row.unitCost;
            unitcost = parseFloat(unitcost.replace('$ ', '').replace(' MXN', ''));

            this.view = 'product-details';
            axios.get('/sales/retrieveproduct/' + row.bar_code).then(response => {
                this.dialogVisible = true;
                this.loading2 = false;
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
        indexLoadAfter(){

            axios.get(route('tickets2.index2')).then(response => {
                
                this.localTickets = response.data.tickets;

            }).catch(error => {
                console.log(error)
            });
        }
      
    },
    data(){
        return {
            dialog:false,
            loading2:false,
            localTickets: [],
            search:'',
            localProducts: {},
            loading:false,
            temporalSearch: [],
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
        this.localTickets = this.tickets;
        console.log('mounted',this.localTickets);
        setTimeout(() => {
            this.indexLoadAfter();
        }, 1000);
    },
    computed: {
        filterTableData() {
            let beforeReturn = Object.values(this.localTickets).filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
            return beforeReturn;            
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
                <h2 class="text-base font-semibold text-red-600">{{ productDetail.Description }}</h2>
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
                <h2 class="text-sm font-semibold text-red-600">{{ productDetail.Description }}</h2>
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
        <EditTicketItem ref="editTicketItemRef" />
    <AppLayout title="Productos">
        <template #header>
            <h3 class="text-lg text-gray-900"> Listado de tickets - # {{ localTickets.length }}</h3>
            <p class="text-sm text-gray">Catalogo de tickets registrados </p>
        </template>
        
        

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-2">
            <div class="flex flex-row m-1">
                <div class="basis-1/2">
                    <inertia-link :href="route('tickets2.create')" class="m-1 transition transform hover:scale-105 focus:scale-95 active:scale-90"> 
                        <PrimaryButton class="transition transform hover:scale-105 focus:scale-95 active:scale-90">
                            Registrar ticket
                        </PrimaryButton>
                    </inertia-link> 
                </div>
                <div class="basis-1/2">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </div>
            </div>
            
            <br/>
            <el-table v-loading="loading" :data="filterTableData" height="600" class="shadow-lg m-1"  stripe :default-sort="{ prop: 'updated_at', order: 'descending' }" >
                <el-table-column align="left" width="70" fixed="left">
                    <template #default="scope">
                        <inertia-link :href="route('products.show', scope.row.id)" class="transition transform hover:scale-105 focus:scale-95 active:scale-90">
                            <el-button size="small" color="#dc2626" class="transition transform hover:scale-105 focus:scale-95 active:scale-90">
                                <svg class="h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M512 160c320 0 512 352 512 352S832 864 512 864 0 512 0 512s192-352 512-352m0 64c-225.28 0-384.128 208.064-436.8 288 52.608 79.872 211.456 288 436.8 288 225.28 0 384.128-208.064 436.8-288-52.608-79.872-211.456-288-436.8-288zm0 64a224 224 0 1 1 0 448 224 224 0 0 1 0-448m0 64a160.192 160.192 0 0 0-160 160c0 88.192 71.744 160 160 160s160-71.808 160-160-71.744-160-160-160"></path></svg>
                            </el-button>
                        </inertia-link>
                    </template>
                </el-table-column>

                <el-table-column type="expand" sortable>
                    <template #default="props" >
                        <div class="m-2 ">
                            <div class="flex ...">                                
                                <div class="p-2 rounded-lg w-3/5 flex items-center justify-center text-white bg-gray-500 shadow-lg">
                                    <el-button size="small" @click="deleteTicket(props.row.id)" class="transition transform hover:scale-105 focus:scale-95 active:scale-90" color="#dc2626">Eliminar Ticket </el-button> &nbsp;&nbsp;&nbsp;
                                    <b>Número de productos {{props.row.id}} {{ props.row.ticket_items.length }}</b>
                                </div>
                            </div>

                            <el-table :data="props.row.ticket_items" class="shadow-lg " stripe v-loading="loading2">
                                <el-table-column width="200">
                                    <template #default="subScope">
                                        <el-dropdown split-button class="custom-dropdown transition transform hover:scale-105 focus:scale-95 active:scale-90" trigger="click" type="danger" @click="handleClick(subScope.row)" >
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

                                <el-table-column width="200" label="Producto">
                                    <template #default="subScope">
                                        <el-button  size="small" v-if="subScope.row.product_id == null" class="transition transform hover:scale-105 focus:scale-95 active:scale-90" color="#dc2626">
                                            Buscar 
                                        </el-button>
                                        <span v-else>
                                            {{ subScope.row.product_name }}
                                        </span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="#" prop="quantity" width="100"/>
                                <el-table-column width="120" label="$ Ticket">
                                    <template #default="subScope">
                                        {{ subScope.row.cost_customer }}
                                    </template>
                                </el-table-column>
                                <el-table-column width="120" label="$ Unitario">
                                    <template #default="subScope">
                                        {{ subScope.row.unitCost }}
                                    </template>
                                </el-table-column>
                                <!-- <el-table-column label="Código de barras" prop="bar_code" width="170"/> -->
                            </el-table>
                        </div>

                    </template>
                </el-table-column>

                <el-table-column prop="noTicket"            label="# Ticket"        width="100" sortable/>
                <el-table-column width="120" label="Total">
                    <template #default="scope">
                        {{scope.row.total}} 
                    </template>
                </el-table-column>
                <el-table-column prop="who_issued_ticket"   label="Surtido por"     width="150" sortable/>
                <el-table-column prop="date_time_issued"    label="Fecha Surtido"   width="150" sortable/>
                <el-table-column width="150" label="Creado">
                    <template #default="scope">
                        {{scope.row.created_at.split('T')[0]}} {{ scope.row.created_at.split('T')[1].split(':')[0] }}:{{ scope.row.created_at.split('T')[1].split(':')[1] }}
                    </template>
                </el-table-column>
                <el-table-column width="120" label="Creado por">
                    <template #default="scope">
                        {{scope.row.created_by.name}}
                    </template>
                </el-table-column>
                <el-table-column width="120" label="Actualizado">
                    <template #default="scope">
                        {{scope.row.edited_by.name}}
                    </template>
                </el-table-column>
                <el-table-column prop="provider"            label="Proveedor"       width="170" sortable/>
            </el-table>

            <br/>

        </div>

    </AppLayout>

    
</template>

<style>
li.el-dropdown-menu__item{
    background-color: #dc2626 !important;
    color: white !important;
    padding: 5%;
    margin: 1%;
}
.el-button--danger:hover,.el-button--danger:focus{
    background-color: rgb(231, 103, 103);
    border-color: rgb(231, 103, 103);
}
.el-button--danger{
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;

}
</style>