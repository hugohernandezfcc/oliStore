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
import FullCalendarCustom from '@/Components/FullCalendarCustom.vue';


export default{
    components:{
        AppLayout, PrimaryButton, SecondaryButton, Footer, FullCalendarCustom
    },
    props:{
        providers: Array
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
    data(){
        return {
            rowCollectionSelected: new Array(),
            dt: null,
            search:'',
        }
    },
    mounted(){
        
    },
    computed: {
        filterTableData() {
            
            return this.providers.filter(
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
            <h3 class="text-lg text-gray-900"> Listado de proveedores </h3>
            <p class="text-sm text-gray">Catalogo de proveedores registrados en la base de datos </p>
        </div>
        <inertia-link :href="route('providers.create')" class="p-4 m-1"> 
            <PrimaryButton >
                Crear Proveedor 
            </PrimaryButton>
        </inertia-link> 
        <div class="flex flex-wrap-reverse shadow bg-white p-4 m-4">
            <div class="basis-1/4">
                <el-table :data="filterTableData" class="shadow-lg" stripe style="width: 100%; height: 100%;" >
                    <el-table-column prop="company" label="Compañia" width="250" />
                    <el-table-column align="right" fixed="right" width="120">
                        <template #default="scope">
                            <inertia-link :href="route('providers.show', scope.row.id)" >
                                <el-button
                                size="small"
                                color="#dc2626"
                                >Ver detalle</el-button
                                >
                            </inertia-link>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <div class="basis-3/4">
                <div class="overflow-hidden shadow-xl sm:rounded-lg ml-2">
                    <FullCalendarCustom />
                </div>
            </div>
        </div>

    </AppLayout>
    
</template>
