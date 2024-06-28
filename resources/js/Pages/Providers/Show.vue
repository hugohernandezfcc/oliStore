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
        provider: Object,
        tickets: Array

    },
    data(){
        return {
            dialogVisible: false,
            optionSchedule: '',
            salesList: []
        }
    },
    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('providers.destroy', this.provider.id));
            }
        },
        beforeCloseModal() {
        this.dialogVisible = false;

        // this.$confirm('Are you sure to close this dialog?')
        //   .then(_ => {
        //     done();
        //   })
        //   .catch(_ => {});
      },
      openModal(itemType) {

        this.dialogVisible = true;
        this.optionSchedule = itemType;
        
      },
      closeModal() {
        this.dialogVisible = false;
      },
    },
    mounted(){
        console.log(this.tickets);
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
                                        <inertia-link :href="route('providers.edit', provider.id)">
                                            <PrimaryButton >
                                                Editar 
                                            </PrimaryButton>
                                        </inertia-link>
                                        <br/>
                                        </td>
                                        <td>
                                            <PrimaryButton @click="eliminar()">
                                                Eliminar 
                                            </PrimaryButton>
                                            <br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>ID DB :</b></td>
                                    <td>{{provider.id}}</td>
                                </tr>
                                <tr>
                                    <td><b>Compañia :</b></td>
                                    <td>{{provider.company}}</td>
                                </tr>
                                <tr>
                                    <td><b>Representante:</b></td>
                                    <td>{{provider.representative}}</td>
                                </tr>
                                <tr>
                                    <td><b>Descripción:</b></td>
                                    <td>{{provider.description}}</td>
                                </tr>
                                <tr>
                                    <td><b>E-mail:</b></td>
                                    <td>{{provider.email}}</td>
                                </tr>
                                <tr>
                                    <td><b>Whatsapp:</b></td>
                                    <td>{{provider.whatsapp}}</td>
                                </tr>
                                <tr>
                                    <td><b>Día de visita:</b></td>
                                    <td>{{provider.visit_day}}</td>
                                </tr>
                                <tr>
                                    <td><b>Fecha de creación:</b></td>
                                    <td>{{provider.created_at}}</td>
                                </tr>
                                

                            </table>
                            <br/>
                            <el-button type="danger" @click="openModal('normal')" plain>Programar visitas</el-button>
                            <el-button type="warning" @click="openModal('extraordinary')" plain>Visita extraordinaria</el-button>
                        </div>
                    </div>

                     <!-- modal -->
                    <el-dialog v-model="dialogVisible"  :before-close="beforeCloseModal" :width="'80%'" :center="true">
                        <div v-if="optionSchedule == 'extraordinary'">
                            extraordnary
                        </div>
                        <div v-if="optionSchedule == 'normal'">
                            normal
                        </div>
                    </el-dialog>
                    <!-- /modal -->

                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <h2>{{provider.company}} // {{provider.representative}}</h2>
                            <hr class="my-6"/>

                            

                            <table>
                                <tr>
                                    <td><b>Total de ventas:</b></td>
                                    <td>#</td>
                                </tr>
                            </table>

                            <table>
                                <tr>
                                    <td><b>Total precio Cliente:</b></td>
                                    <td>#</td>
                                </tr>
                            </table>


                            <table>
                                <tr>
                                    <td><b>Total precio Lista:</b></td>
                                    <td>#</td>
                                </tr>
                            </table>

                            <hr class="my-6"/>
                            Agregar ticket<br/>
                            <el-input v-model="search"  placeholder="Agregar ticket" class="shadow-2xl"/>
                            <br/>
                            <br/>
                           

                            <el-table :data="filterTableData" class="shadow-lg" stripe style="width: 100%; " >
                                <el-table-column prop="noTicket" label="No. Ticket" width="120" />
                                <el-table-column prop="who_issued_ticket" label="Surtido por" width="150" />
                                <el-table-column prop="provider" label="Compañia" width="100" />
                                <el-table-column label="Total" width="150" >
                                    <template #default="i">
                                        $ {{ i.row.total }} MXN 
                                    </template>
                                </el-table-column>
                                <el-table-column prop="date_time_issued" label="Fecha de compra" width="170" />
                            </el-table>

                            <hr class="my-6"/>
                            <inertia-link :href="route('providers.index')">
                                Regresar
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


