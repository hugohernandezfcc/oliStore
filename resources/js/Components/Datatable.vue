<template>
    <div>
        <table class="table table-hover table-bordered" id="dt-users">
            <thead class="border-1 rounded-sm text-white border-gray-800 bg-gray-900">
                <tr>
                    <th v-for="header in headers" :key="header.id">{{ header }}</th>
                </tr>
            </thead>
        </table>
    </div>
</template>

<script>
    import 'jquery/dist/jquery.min.js';
    import "datatables.net-dt/js/dataTables.dataTables"
    import $ from 'jquery'; 

    export default {
        props: ['url', 'columns', 'headers'],
        mounted(){
            let datatable = $('#dt-users').on('processing.dt', function(e, settings, processing) {
                    if (processing) {
                        $('table').addClass('opacity-25');
                    }else {
                        $('table').removeClass('opacity-25');
                    }
                }).DataTable({
                ajax: {
                    url: this.url,
                },
                serverSide: true,
                processing: true,
                paging: true,
                columns: this.columns,
            });

        },
    }
</script>