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
import FooterPos from '@/Components/FooterPos.vue';
import { HollowDotsSpinner } from 'epic-spinners'

export default{
    components:{
        AppLayout, InputLabel, TextInput, PrimaryButton, SecondaryButton, FooterPos, SecondaryButtonPay, HollowDotsSpinner
    },
    props:{
        ticket: Object,
        products: Array,
        sales: Array,
        salesDates: Array,
    },
    data(){
        return {
            relatedProducts: [],
            search: '',
            search2: '',
            search3: '',
            restantes: [],
        }
    },
    methods:{
        
    },
    mounted(){
        console.log(this.ticket);
        console.log(this.sales);
        console.log(this.salesDates);

        for (let index = 0; index < this.products.length; index++) {

            for (let i = 0; i < this.sales.length; i++) {
                if(this.products[index].product_id == this.sales[i].product_id){
                    this.products[index].editQuantity = this.products[index].editQuantity - 1;
                }
                
            }
            this.products[index].vendidas = this.products[index].editvendidas - this.products[index].editQuantity;
        }
        console.log(this.products);

    },
    computed: {
        filterTableData() {
            return this.products.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        },
        filterTableData2() {
            return this.sales.filter(
                (data) =>
                !this.search2 || JSON.stringify(data).toLowerCase().includes(this.search2.toLowerCase() )
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

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">
                            <h3 class="text-lg text-gray-900"> Detalle del ticket </h3>
                            <br/>
                            <table>
                                <tr>
                                    <td>
                                        <inertia-link :href="route('tickets.edit', ticket.id)">
                                            <PrimaryButton >
                                                Editar Ticket
                                            </PrimaryButton>
                                        </inertia-link>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>ID DB :</b></td>
                                    <td>{{ticket.id}}</td>
                                </tr>

                                <tr>
                                    <td><b>No. Ticket :</b></td>
                                    <td>{{ticket.noTicket}}</td>
                                </tr>

                                
                                <tr>
                                    <td><b>Creado por :</b></td>
                                    <td>{{ticket.created_by.name}}</td>
                                </tr>
                                
                                <tr class="bg-green-100">
                                    <td><b>Provedor :</b></td>
                                    <td>{{ticket.provider}}</td>
                                </tr>

                                <tr class="bg-green-100">
                                    <td><b>Total :</b></td>
                                    <td>$ {{ticket.total}} MXN</td>
                                </tr>

                                <tr>
                                    <td><b>Fecha de surtido :</b></td>
                                    <td>{{ticket.date_time_issued}}</td>
                                </tr>

                                <tr >
                                    <td><b>Surtido por :</b></td>
                                    <td>{{ticket.who_issued_ticket}}</td>
                                </tr>

                                <tr class="bg-green-100">
                                    <td><b>Total de tipo de productos :</b></td>
                                    <td># {{ticket.noProducts}}</td>
                                </tr>
                                <tr class="bg-green-200">
                                    <td><b>Total de productos :</b></td>
                                    <td># {{ticket.quantitytotal}}</td>
                                </tr>

                                <tr >
                                    <td><b>Fecha de creación :</b></td>
                                    <td>{{ticket.created_at2}}</td>
                                </tr>

                                <tr>
                                    <td><b>Ultima actualización :</b></td>
                                    <td>{{ticket.updated_at2}}</td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <h1 class="bg-red-600 text-zinc-50 text-lg"> Ticket {{ticket.id}} // Provedor {{ ticket.provider }} // Se surtio el {{ ticket.date_time_issued }}</h1>
                            <hr class="my-6"/>

                            <table>
                                <tr>
                                    <td><b>Total de ventas:</b></td>
                                    <td>{{sales.length}}</td>
                                </tr>
                            </table>

                            <table>
                                <tr>
                                    <td><b>Total faltantes por vender:</b></td>
                                    <td>{{ticket.quantitytotal - sales.length}}</td>
                                </tr>
                            </table>


                            
                            <el-divider content-position="center">
                                {{ticket.quantitytotal}} Productos del ticket
                            </el-divider>

                            <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                            <el-table :data="filterTableData" class="shadow-lg" stripe style="height: 300px;" >

                                <el-table-column prop="product_name" label="Producto" width="150" />
                                <el-table-column prop="quantity" label="Total Piezas" width="100" />
                                <el-table-column prop="vendidas" label="Piezas vendidas" width="130" />
                                <el-table-column prop="editQuantity" label="Piezas restantes" width="130" />
                                <el-table-column prop="unitCost" label="Precio unitario" width="125" />
                                <el-table-column prop="cost_customer" label="Precio subtotal" width="125" />
                            </el-table>

                            <el-divider content-position="center">
                                {{sales.length}} Ventar realizadas 
                            </el-divider>

                            <el-input v-model="search2"  placeholder="Type to search" class="shadow-2xl"/>
                            <el-table :data="filterTableData2" class="shadow-lg" stripe style="height: 300px;" >
                                <el-table-column prop="id" label="Id" width="150" />
                                <el-table-column prop="name" label="Producto" width="150" />
                                <el-table-column label="Precio lista" width="150" >
                                    <template #default="i">
                                        $ {{ i.row.price_list }} MXN 
                                    </template>
                                </el-table-column>

                                <el-table-column label="Precio unitario" width="150" >
                                    <template #default="i">
                                        $ {{ i.row.price_customer }} MXN 
                                    </template>
                                </el-table-column>
                                <el-table-column prop="folio" label="Producto" width="150" />


                            </el-table>

                            <hr class="my-6"/>
                            <inertia-link :href="route('tickets.index')">
                                Regresar
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


