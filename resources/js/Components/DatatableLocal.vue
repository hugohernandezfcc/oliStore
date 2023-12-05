<script>
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

    export default {
        components:{
             InputLabel, TextInput, PrimaryButton, SecondaryButton, SecondaryButtonPay 
        },
        props:{
            id:String,
            columns: Array,
            search: String,
            zeroRecords: String,
            records: Array
        },
        data(){
            return {
                dt: null,
                rowCollectionSelected: Array
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
            console.log(this.product);
            this.dt = $('#'+this.id).DataTable();
            this.dt.on( 'select', () => this.onRowClick())
            this.dt.on( 'deselect', () => this.onRowClick())
        }
    }
</script>
<template>
    <div>
        <DataTable 
            class="cell-border compact stripe hover order-column loading"
            ref="table" :id="id"
            :data="records"
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
                    search: this.search, zeroRecords: this.zeroRecords
                }
            }"
            :columns="columns">
            <thead>
                <tr >
                    <th v-for="column in columns">{{ column.label }}</th>
                </tr>
            </thead>
        </DataTable>
    </div>
</template>