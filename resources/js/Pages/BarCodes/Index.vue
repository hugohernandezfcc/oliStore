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
import LookupField from '@/Components/LookupField.vue';

export default{
    components:{
        AppLayout, PrimaryButton, SecondaryButton, Footer, wizardForm, LookupField
    },
    props:{
        barCodes: Array,
        
    },
    methods:{
        
    },
    data(){
        return {
            rowCollectionSelected: new Array(),
            search:'',
            dialogVisible: false
        }
    },
    mounted(){
        
    },
    computed: {
        filterTableData() {
            return this.barCodes.filter(
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
            <h3 class="text-lg text-gray-900"> Listado de Códigos de baaras - #0</h3>
            <p class="text-sm text-gray">Catalogo de Códigos de baaras Registrados en la base de datos </p>
        </div>

        <!-- MODAL -->
            <el-dialog v-model="dialogVisible" title="Documentar ticket" width="500" draggable>
                


            </el-dialog>
        <!-- MODAL -->
        <div class="shadow bg-white md:rounded-md p-4 m-4">
            
            
            <el-row :gutter="20">
                <el-col :span="16">

                    <PrimaryButton @click="dialogVisible = true">
                        Documentar Código de barras
                    </PrimaryButton>

                </el-col>
                <el-col :span="8">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </el-col>
            </el-row>
            <br/>
            <el-table :data="filterTableData" class="shadow-lg" stripe style="width: 100%; height: 500px;" >
                
             
                <el-table-column prop="product_id" label="Id" width="100" />
                
                <el-table-column prop="id" label="Id" width="150" />

                <el-table-column align="right" fixed="right" width="120">
                    <template #default="scope">
                        <inertia-link :href="route('BarCodes.show', scope.row.id)" >
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
                <LookupField />
            </el-divider>
            <br/>

        </div>
    </AppLayout>
    
</template>
