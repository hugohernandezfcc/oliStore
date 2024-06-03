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

import moment from 'moment';
import FormSection from '@/Components/FormSection.vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection
    },
    name: 'Financial_Show',
    props:{
        customRecord: Object,

    },
    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('financials.destroy', this.customRecord.id));
            }
        },
    },
    data(){
        return {
            reportResults8: new Array(),
            
            date: new Date()
        }
    },
    mounted(){
        let globalResults = [];

        console.log('componente montado', this.customRecord)

    },
    computed: {
        
    }

}
</script>
<template>
    <AppLayout :title="customRecord.name">
        <template #header>
            Detalle de periodo 
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <inertia-link :href="route('financials.edit', customRecord.id)">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0"> Editar periodo </PrimaryButton>
                </inertia-link>

                <PrimaryButton @click="eliminar" class="mb-3 ml-3 lg:ml-1"> 
                    Eliminar <svg class="ml-1 -mt-0.5 h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                </PrimaryButton>

                <FormSection >

                    <template #title>
                        {{customRecord.name}}
                    </template>
                    <template #description>
                        {{customRecord.description}}
                    </template>
                    <template #details>
                        <br/>
                        <el-descriptions title="Detalle del periodo" :column="1" border>
                            <el-descriptions-item label="Concepto" label-align="right" align="left" width="80px">
                                <span >{{customRecord.name}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Description" label-align="right" align="left" width="80px">
                                <span >{{customRecord.description}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Periodo" label-align="right" align="left" width="80px">
                                <span >{{customRecord.store.name}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="¿Periodo actual?" label-align="right" align="left" width="80px">
                                <el-tag
                                    :type="(customRecord.current == 1) ? 'success' : 'warning' "
                                    effect="dark"
                                    >
                                    {{(customRecord.current == 1) ? 'Periodo Actual' : 'Periodo Anterior'}}
                                </el-tag>


                            </el-descriptions-item>
                        </el-descriptions>

                        <br/>
                        <el-descriptions title="Fechas" :column="1" border>
                            <el-descriptions-item label="Fecha de creación" label-align="right" align="left" width="150px">
                                <span >{{customRecord.created_at}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de actualización" label-align="right" align="left" width="150px">
                                <span >{{customRecord.updated_at}}</span>
                            </el-descriptions-item>
                        </el-descriptions>

                        <br/>
                        <el-descriptions title="Auditoría usuarios" :column="1" border>
                            <el-descriptions-item label="Creado por" label-align="right" align="left" width="150px">
                                <span >{{customRecord.created_by.name}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Editado por" label-align="right" align="left" width="150px">
                                <span >{{customRecord.edited_by.name}}</span>
                            </el-descriptions-item>
                        </el-descriptions>
                    </template>

                </FormSection>
            </div>
        </div>

    </AppLayout>
</template>
