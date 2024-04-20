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
        }
    },
    data(){
        return {
            rowCollectionSelected: new Array(),
            search:''

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
                                <el-table-column width="100">
                                    <template #default="subScope">
                                        <el-button size="small" @click="deleteItem(subScope.row.id, props.row.id )" color="#dc2626">Eliminar </el-button>
                                    </template>
                                </el-table-column>

                                <el-table-column width="100">
                                    <template #default="subScope">
                                        <el-button size="small" @click="this.$refs.editTicketItemRef.openModalForm(subScope.row.id, 'editItem')" color="#dc2626">Editar</el-button>
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
                <el-table-column prop="provider" label="Id" width="100" />
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
