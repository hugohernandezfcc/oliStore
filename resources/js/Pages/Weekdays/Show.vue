<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import moment           from 'moment';
import FormSection      from '@/Components/FormSection.vue';
import StockIndicator   from '@/Components/StockIndicator.vue';
import { ArrowLeftBold } from '@element-plus/icons-vue';


export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection,
        StockIndicator,
        ArrowLeftBold,

    },

    props:{
        customRecord: Object,

    },
    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('week_days.destroy', this.customRecord.id));
                setTimeout(() => {
                    this.$inertia.visit(route('week_days.index'));
                }, 1000);
            }
        },
    },
    data(){
        return {
            reportResults8: new Array(),
            search:'',
            date: new Date()
        }
    },
    mounted(){
        let globalResults = [];
        console.log('componente montado', this.customRecord)

    },
    computed: {
        filterTableData() {
            return this.customRecord.prices.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        }
    }

}
</script>
<style>
button {
    touch-action: manipulation !important;
}
</style>
<template>
    <AppLayout title="Dashboard">
        <template #header>

            <PrimaryButton  class="mb-3 ml-3 lg:ml-0" @click="$inertia.visit(route('week_days.index'))"> 
                <el-icon><ArrowLeftBold /></el-icon>
            </PrimaryButton>&nbsp;
            Detalle de día {{customRecord.name}} 
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <inertia-link :href="route('week_days.edit', customRecord.id)">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0"> Editar días </PrimaryButton>
                </inertia-link>

                <PrimaryButton @click="eliminar" class="mb-3 ml-3 lg:ml-1"> 
                    Eliminar <svg class="ml-1 -mt-0.5 h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                </PrimaryButton>

                <FormSection >

                    <template #title>
                        {{customRecord.name}}
                    </template>
                    <template #description >
                        <div class="w-[70%]">
                            {{customRecord.Description}}
                        </div>
                        <br/>
                        
                    </template>
                    <template #details>
                        <br/>

                        <el-descriptions :title="'Detalle del registro '" :column="1" border>
                            <el-descriptions-item label="Día" label-align="right" align="left" width="80px">
                                <span > {{customRecord.name}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Descripción" label-align="right" align="left" width="80px">
                                <span > {{customRecord.description}} </span>
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
                        <el-descriptions title="Auditoría de usuarios" :column="1" border>
                            <el-descriptions-item label="Creado por" label-align="right" align="left" width="150px">
                                <span >{{customRecord.created_by.name}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Editado por" label-align="right" align="left" width="150px">
                                <span >{{customRecord.updated_by.name}}</span>
                            </el-descriptions-item>
                        </el-descriptions>
                        
                    </template>

                    <template #relatedlist>
                        -
                    </template>

                </FormSection>
            </div>
        </div>

    </AppLayout>
</template>
