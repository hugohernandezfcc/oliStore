<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import $ from 'jquery';
import "datatables.net-responsive-dt/css/responsive.dataTables.min.css"
import "datatables.net-dt/css/jquery.dataTables.min.css"
import 'datatables.net-responsive-bs5';
import 'datatables.net-select';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';


export default{
    components:{
        AppLayout, PrimaryButton, SecondaryButton
    },
    props:{
        products: Array

    },
    methods:{
        onRowClick(event){
            window.location.href = route('products.create') 
        }
    },
    data(){
        return {
            dt: null
        }
    },
    mounted(){
        this.dt = $('#datatable').DataTable();
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
            <h3 class="text-lg text-gray-900"> Listado de productos </h3>
            <p class="text-sm text-gray">aoscdijasdo </p>
        </div>
        <div class="shadow bg-white md:rounded-md p-4 m-4">
            
            <div class="flex flex-wrap">
                <div class="m-1">
                    <SecondaryButton @click="selectedRecords">
                        + Agregar Inventario
                    </SecondaryButton>
                </div>
                <div class="m-1">
                    <inertia-link :href="route('products.create')"> 
                        <PrimaryButton >
                            Crear Producto 
                        </PrimaryButton>
                    </inertia-link>
                </div>
            </div>
            

            <br/>
            <br/>
            <DataTable 
                class="cell-border compact stripe hover order-column loading"
                ref="table" id="datatable"
                :data="products"
                :options="{
                    responsive:true, autoWidth:false, select: true,  dom:'Bfrtip', buttons:[
                        { 
                            extend: 'selectAll', 
                            className: 'shadow relative bg-primary-500 hover:bg-red-600 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-red-600 text-white dark:text-gray-900' 
                        },
                        { 
                            extend: 'print',
                            text: 'Print selected rows', 
                            className: 'shadow relative bg-primary-500 hover:bg-red-600 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-red-600 text-white dark:text-gray-900' 
                        }
                    ], language:{
                        search:'Buscar Producto ', zeroRecords:'No hay registros'
                    }
                }"
                
                :columns="[
                    {data:'name'},
                    {data:'folio'},
                    {data:'Description'},
                    {data:'price_list'},
                    {data:'price_customer'},
                    {data:'profit_percentage'},
                    {data:'expiry_date'},
                    {data:'created_by_id'},
                    {data:'updated_at'},
                    {
                        data: 'id', render:function(data,type,row,meta) {
                            let detalle = '<a href=\'/products/show/'+data+'\' class=\'m-1 inline-flex items-center px-2 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150\' >Detalle</a>';
                            let deleteButton = '<a href=\'/products/delete/'+data+'\' class=\'m-1 inline-flex items-center px-2 py-1 bg-red-600 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:bg-red-900 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150\' >Eliminar</a>';
                            return detalle + '    ' + deleteButton;
                        }
                    }
            ]">
                <thead>
                    <tr>
                        <th>NOMBRE PRODUCTO</th>
                        <th>CODIGO DE BARRAS</th>
                        <th>DESCRIPCION</th>
                        <th>PRECIO DE LISTA</th>
                        <th>PRECIO CLIENTE</th>
                        <th>PORCENTAJE (GANANCIA)</th>
                        <th>FECHA DE CADUCIDAD</th>
                        <th>CREADO POR</th>
                        <th>ULTIMA ACTUALIZACIÓN</th>
                        <th> </th>
                    </tr>
                </thead>
            </DataTable>

        </div>
    </AppLayout>
</template>
