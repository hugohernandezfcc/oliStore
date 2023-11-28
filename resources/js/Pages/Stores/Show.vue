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

export default{
    components:{
        AppLayout, InputLabel, TextInput, PrimaryButton, SecondaryButton
    },
    props:{
        store: Object

    },
    data(){
        return {
            salesList: [],
            dt: null
        }
    },
    methods:{
        onRowClick(){
            let rowCollectionSelected = new Array();
            this.dt.rows({ selected: true }).data().each( function ( recordSelected, index ) {
                rowCollectionSelected.push(recordSelected);
            } );

            this.rowCollectionSelected = rowCollectionSelected;
            console.log(this.rowCollectionSelected);
        }
    },
    mounted(){
        console.log(this.store);
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
                                        <inertia-link :href="route('products.edit', store.id)">
                                            <PrimaryButton >
                                                Editar Producto
                                            </PrimaryButton>
                                            
                                        </inertia-link>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>ID DB :</b></td>
                                    <td>{{store.id}}</td>
                                </tr>
                                <tr>
                                    <td><b>NOMBRE storeO:</b></td>
                                    <td>{{store.name}}</td>
                                </tr>
                               
                            </table>
                            
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <h2>{{store.name}} // {{store.id}}</h2>
                            <hr class="my-6"/>

                            
                            <hr class="my-6"/>
                            <!-- <DatatableLocal 
                                :data=""
                                :columns=""
                                :search=""
                                :zeroRecords=""
                                :records=""
                            /> -->
                           
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


