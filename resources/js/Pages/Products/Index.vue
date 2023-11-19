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



export default{
    components:{
        AppLayout, PrimaryButton, SecondaryButton, Footer
    },
    props:{
        products: Array
        

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
            
        }
    },
    mounted(){
        this.dt = $('#datatable').DataTable();
        this.dt.on( 'select', () => this.onRowClick())
        this.dt.on( 'deselect', () => this.onRowClick())
        
        console.log(this.rowCollectionSelected.length);
        console.log(this.products);
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
            
            <inertia-link :href="route('products.create')" class="m-1"> 
                <PrimaryButton >
                    Crear Producto 
                </PrimaryButton>
            </inertia-link> 
            

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
                    {data:'id'},
                    {data:'name'},
                    {data:'folio'},
                    {data:'Description'},
                    {data:'price_list'},
                    {data:'price_customer'},
                    {data:'profit_percentage'},
                    {data:'expiry_date'},
                    {data:'created_by_id'},
                    {data:'updated_at'}
            ]">
                <thead>
                    <tr>
                        <th> ID DB </th>
                        <th>NOMBRE PRODUCTO</th>
                        <th>CODIGO DE BARRAS</th>
                        <th>DESCRIPCION</th>
                        <th>PRECIO DE LISTA</th>
                        <th>PRECIO CLIENTE</th>
                        <th>PORCENTAJE (GANANCIA)</th>
                        <th>FECHA DE CADUCIDAD</th>
                        <th>CREADO POR</th>
                        <th>ULTIMA ACTUALIZACIÓN</th>
                    </tr>
                </thead>
            </DataTable>

        </div>
    </AppLayout>
    <Footer 
        :selectedProducts="rowCollectionSelected.length" 
        :products="rowCollectionSelected"
        :product="(rowCollectionSelected.length > 0) ? rowCollectionSelected[0] : null"
        v-if="rowCollectionSelected.length > 0"/>
</template>
