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
        AppLayout,
        PrimaryButton,
        SecondaryButton
    },

    props:{
        financials: Array 
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
        console.log('mounted',this.financials);
    },
    computed: {
        filterTableData() {
            return this.financials.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        },
    }

}
</script>
<template>
    <AppLayout title="Periodos">
        <template #header>
            <h3 class="text-lg text-gray-900"> Listado de periodos - # {{ financials.length }}</h3>
            <p class="text-sm text-gray">Catalogo de periodos registrados </p>
        </template>
        

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-2">
            <div class="flex flex-row m-1">
                <div class="basis-1/2">
                    <inertia-link :href="route('financials.create')" class="m-1"> 
                        <PrimaryButton >
                            Nuevo Periodo
                        </PrimaryButton>
                    </inertia-link> 
                </div>
                <div class="basis-1/2">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </div>
            </div>
            
            <br/>
            <el-table :data="filterTableData" class="shadow-lg m-1" stripe  >
                <el-table-column align="left" width="70" fixed="left" >
                    <template #default="scope">
                        <inertia-link :href="route('financials.show', scope.row.id)" >
                            <el-button size="small" color="#dc2626"> 
                                <svg class="h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M512 160c320 0 512 352 512 352S832 864 512 864 0 512 0 512s192-352 512-352m0 64c-225.28 0-384.128 208.064-436.8 288 52.608 79.872 211.456 288 436.8 288 225.28 0 384.128-208.064 436.8-288-52.608-79.872-211.456-288-436.8-288zm0 64a224 224 0 1 1 0 448 224 224 0 0 1 0-448m0 64a160.192 160.192 0 0 0-160 160c0 88.192 71.744 160 160 160s160-71.808 160-160-71.744-160-160-160"></path></svg>
                            </el-button>
                        </inertia-link>
                    </template>
                </el-table-column>

                <el-table-column prop="name" label="Concepto" width="220" />
                <el-table-column label="Periodo" width="220" >
                    <template #default="scope">
                        <el-tag
                            :type="(scope.row.current == 1) ? 'success' : 'warning' "
                            effect="dark"
                            >
                            {{(scope.row.current == 1) ? 'Periodo Actual' : 'Periodo Anterior'}}
                        </el-tag>

                    </template>
                </el-table-column>
                <el-table-column prop="description" label="Descripción" width="250" />
                <el-table-column label="Tienda" width="220" >
                    <template #default="scope">
                        {{ scope.row.store.name }}
                    </template>
                </el-table-column>
            </el-table>
            
            <br/>

        </div>

    </AppLayout>
</template>
