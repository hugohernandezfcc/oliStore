<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';


export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton
    },

    props:{
        liabilities: Array 
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
        console.log('mounted',this.liabilities);
    },
    computed: {
        filterTableData() {
            return this.liabilities.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        },
    }

}
</script>
<template>
    <AppLayout title="Gastos">
        <template #header>
            <h3 class="text-lg text-gray-900"> Listado de Gastos - # {{ liabilities.length }}</h3>
            <p class="text-sm text-gray">Catalogo de gastos registrados recurrentes </p>
        </template>
        
        

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-2">
            <div class="flex flex-row m-1">
                <div class="basis-1/2">
                    <inertia-link :href="route('liabilities.create')" class="m-1"> 
                        <PrimaryButton >
                            Nuevo Gasto
                        </PrimaryButton>
                    </inertia-link> 
                </div>
                <div class="basis-1/2">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </div>
            </div>
            
            <br/>
            <el-table :data="filterTableData" class="shadow-lg m-1" stripe  >
                <el-table-column align="left" width="70" fixed="left">
                    <template #default="scope">
                        <inertia-link :href="route('liabilities.show', scope.row.id)" >
                            <el-button size="small" color="#dc2626"> 
                                <svg class="h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M512 160c320 0 512 352 512 352S832 864 512 864 0 512 0 512s192-352 512-352m0 64c-225.28 0-384.128 208.064-436.8 288 52.608 79.872 211.456 288 436.8 288 225.28 0 384.128-208.064 436.8-288-52.608-79.872-211.456-288-436.8-288zm0 64a224 224 0 1 1 0 448 224 224 0 0 1 0-448m0 64a160.192 160.192 0 0 0-160 160c0 88.192 71.744 160 160 160s160-71.808 160-160-71.744-160-160-160"></path></svg>
                            </el-button>
                        </inertia-link>
                    </template>
                </el-table-column>

                <el-table-column type="expand" sortable>
                    <template #default="props" >
                        <div class="m-6 w-[70%]">
                            <p> {{ props.row.description }} </p>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column prop="name" label="Concepto" width="200" sortable/>
                <el-table-column label="Porcentaje" width="100" >
                    <template #default="scope">
                        {{ scope.row.percentage }}%
                    </template>
                </el-table-column>


                <el-table-column label="Periodo" width="300" sortable>
                    <template #default="scope">
                        {{ scope.row.financial.name}}
                    </template>
                </el-table-column>

                <el-table-column label="Periodo" width="300" sortable>
                    <template #default="scope">
                        {{ scope.row.financial.id}}
                    </template>
                </el-table-column>


                

            </el-table>
            
            <br/>

        </div>

    </AppLayout>
</template>
