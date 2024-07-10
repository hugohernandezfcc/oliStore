<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import moment           from 'moment';
import FormSection      from '@/Components/FormSection.vue';
import RelatedListNative from '@/Components/RelatedListNative.vue';
export default{
    components:{
        RelatedListNative,
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection
    },

    props:{
        customRecord: Object,
        relatedList: Object
    },
    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('box.destroy', this.customRecord.id));
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
    <AppLayout title="Dashboard">
        <template #header>
            Detalle de la caja 
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <inertia-link :href="route('box.edit', customRecord.id)">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0"> Editar Caja </PrimaryButton>
                </inertia-link>

                <PrimaryButton @click="eliminar" class="mb-3 ml-3 lg:ml-1"> 
                    Eliminar <svg class="ml-1 -mt-0.5 h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                </PrimaryButton>

                <FormSection >

                    <template #title>
                        {{customRecord.name}}
                    </template>
                    <template #description>
                        {{customRecord.box_date}}<br/><br/>
                        {{customRecord.description}}<br/>
                    </template>
                    <template #details>
                        <br/>
                        <el-descriptions title="Detalle del Gasto" :column="1" border>
                            <el-descriptions-item label="Concepto" label-align="right" align="left" width="80px">
                                <span >{{customRecord.name}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha" label-align="right" align="left" width="80px">
                                <span >{{customRecord.box_date}}</span>
                            </el-descriptions-item>
                            
                            <el-descriptions-item label="Periodo" label-align="right" align="left" width="80px">

                                <el-tag :type="(customRecord.status == 'close') ? 'danger' : 'primary'">
                                    {{customRecord.status}}
                                </el-tag>
                            </el-descriptions-item> 
                            <el-descriptions-item label="Monto Apertura en caja" label-align="right" align="left" width="80px">
                                <b><span class="text-green-600">$ {{customRecord.open_amount}} MXN</span></b>
                            </el-descriptions-item> 
                            <el-descriptions-item label="Monto de apertura en fondo de caja" label-align="right" align="left" width="80px">
                                <b><span class="text-green-600">$ {{customRecord.open_foundbox}} MXN</span></b>
                            </el-descriptions-item> 

                            <el-descriptions-item label="Fondo de caja" label-align="right" align="left" width="80px">
                                <b><span >$ {{customRecord.founds_box}} MXN</span></b>
                            </el-descriptions-item> 
                            <el-descriptions-item label="Monto en caja" label-align="right" align="left" width="80px">
                                <b><span >$ {{customRecord.amount}} MXN</span></b>
                            </el-descriptions-item> 

                            <el-descriptions-item label="Monto de cierre en caja" label-align="right" align="left" width="80px">
                                <b><span class="text-red-700">$ {{(customRecord.close_amount == null) ? 0 : customRecord.close_amount}} MXN</span></b>
                            </el-descriptions-item> 
                            <el-descriptions-item label="Monto de cierre en fondo de caja" label-align="right" align="left" width="80px">
                                <b><span class="text-red-700">$ {{(customRecord.close_foundbox == null) ? 0 : customRecord.close_foundbox}} MXN</span></b>
                            </el-descriptions-item> 

                            <el-descriptions-item label="Tienda" label-align="right" align="left" width="80px">
                                <span >{{customRecord.store.name}}</span>
                            </el-descriptions-item> 
                            <el-descriptions-item label="Creado por" label-align="right" align="left" width="80px">
                                <span >{{customRecord.created_by.name}}</span>
                            </el-descriptions-item> 
                            <el-descriptions-item label="Editado por ultima vez" label-align="right" align="left" width="80px">
                                <span >{{customRecord.edited_by.name}}</span>
                            </el-descriptions-item> 
                            <el-descriptions-item label="Cajero" label-align="right" align="left" width="80px">
                                <span >{{customRecord.seller.name}}</span>
                            </el-descriptions-item> 
                        </el-descriptions>
                        
                    </template>
                    <template #relatedlist>
                        <div v-for="(m, o) in relatedList">

                            <RelatedListNative 
                                :customRecordsRelated ="customRecord.box_cut"
                                :title                ="m['title']"
                                :titleModel           ="m['titleModel']"
                                :visibleColumns       ="m['visibleColumns']"
                                :formFields           ="m['formFields']" 
                                :table                ="m['table']"
                                :origin               ="m['origin']"
                                :origin_field         ="m['origin_field']"
                                :currentRecordId      ="m['currentRecordId']"
                                :showNewRecordButton  ="m['showNewRecordButton']"
                                classCard             ="'my-2 -pr-0 w-[470px]'" />
                        </div>
                    </template>
                </FormSection>
            </div>
        </div>

    </AppLayout>
</template>
