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

import axios from 'axios';
import { parse } from 'vue/compiler-sfc';


export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        wizardForm
    },

    props:{
        reports: Array 
    },
    methods:{

    },
    data(){
        return {
            rowCollectionSelected: new Array(),
            search:'',

        }
    },
    mounted(){
        
    },
    computed: {
        filterTableData() {
            return this.reports.filter(
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
            <h3 class="text-lg text-gray-900"> Listado de Reportes - # </h3>
            <p class="text-sm text-gray">Catalogo de Tickets Registrados en la base de datos </p>
        </template>
        
        
        <div class="shadow bg-white md:rounded-md p-4 m-4">
            
            <el-row :gutter="20">
                <el-col :span="16">
                    
                    <inertia-link :href="route('reports.create')" class="m-1"> 
                        <PrimaryButton >
                            Nuevo Reporte
                        </PrimaryButton>
                    </inertia-link> 

                </el-col>
                <el-col :span="8">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </el-col>
            </el-row>
            <br/>
            <el-table :data="filterTableData" class="shadow-lg" stripe style="width: 100%; height: 500px;" >

                <el-table-column prop="id" label="Id" width="150" />
                <el-table-column prop="name" label="Name" width="200" />
                <el-table-column prop="object" label="Objeto" width="150" />
                <el-table-column prop="description" label="Description" width="400" />
                <el-table-column prop="created_at" label="Fecha de creación" width="150" />
                <el-table-column prop="updated_at" label="Fecha de actualización" width="180" />

                <el-table-column align="right" fixed="right" width="120">
                    <template #default="scope">
                        <inertia-link :href="route('reports.show', scope.row.id)" >
                            <el-button size="small" color="#dc2626"> Ver reporte </el-button>
                        </inertia-link>
                    </template>
                </el-table-column>

            </el-table>
            
            <br/>

        </div>
    </AppLayout>
</template>