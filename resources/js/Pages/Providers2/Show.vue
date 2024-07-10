<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import moment           from 'moment';
import FormSection      from '@/Components/FormSection.vue';
import RelatedList      from '@/Components/RelatedList.vue';
import { ArrowLeftBold } from '@element-plus/icons-vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection,
        RelatedList,
        ArrowLeftBold
    },

    props:{
        customRecord: Object,
        relatedListConfig: Object
    },
    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('providers.destroy', this.customRecord.id));
                setTimeout(() => {
                    this.$inertia.visit(route('providers.index'));
                }, 1000);
            }
        },
        lineItems(){

            for (let i = 0; i < this.customRecord.line_any_items.length; i++) {
                console.log(this.customRecord.line_any_items[i]);

                let inverse = (this.customRecord.line_any_items[i].type.split("_")[1] == 'providers') ? true : false;

                if (!this.lineItemsObject[this.customRecord.line_any_items[i].type]) {
                    this.lineItemsObject[this.customRecord.line_any_items[i].type] = [];
                }
        
                this.lineItemsObject[this.customRecord.line_any_items[i].type].push(
                    this.customRecord.line_any_items[i][this.customRecord.line_any_items[i][(inverse) ? 'origin_id' : 'target_id'].replace('_id', '')]
                );
                this.lineItemsObject[this.customRecord.line_any_items[i].type][this.lineItemsObject[this.customRecord.line_any_items[i].type].length-1]['_id'] = this.customRecord.line_any_items[i].id;

            }

            console.log('this.lineItemsObject', this.lineItemsObject);
        }
    },
    data(){
        return { 
            lineItemsObject: new Array(),
            search:'',
            date: new Date()
        }
    },
    mounted(){
        let globalResults = [];
        this.lineItems();
        console.log('componente montado', this.customRecord)
    },
    computed: {
        // filterTableData() {
        //     return this.customRecord.prices.filter(
        //         (data) =>
        //         !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
        //     );
        // }
    }

}
</script>
<template>
    <AppLayout title="Dashboard">
        <template #header>
            <PrimaryButton  class="mb-3 ml-3 lg:ml-0 transition transform hover:scale-105 focus:scale-95 active:scale-90" @click="$inertia.visit(route('providers.index'))"> 
                <el-icon><ArrowLeftBold /></el-icon>
            </PrimaryButton>&nbsp;Detalle de Proveedor 
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <inertia-link :href="route('providers.edit', customRecord.id)">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0 transition transform hover:scale-105 focus:scale-95 active:scale-90"> Editar Proveedor </PrimaryButton>
                </inertia-link>

                <PrimaryButton @click="eliminar" class="mb-3 ml-3 lg:ml-1 transition transform hover:scale-105 focus:scale-95 active:scale-90"> 
                    Eliminar <svg class="ml-1 -mt-0.5 h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                </PrimaryButton>

                <FormSection >

                    <template #title>
                        {{customRecord.representative}}
                    </template>
                    <template #description>
                        {{customRecord.description}}
                    </template>
                    <template #details>
                        <br/>
                        
                        <el-descriptions :title="'Detalle del Proveedor >> ' + customRecord.company" :column="1" border>
                        
                            <el-descriptions-item label="Representante" label-align="right" align="left" width="80px">
                                <span >{{customRecord.representative}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Compañía" label-align="right" align="left" width="80px">
                                <span >{{customRecord.company}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Descripción" label-align="right" align="left" width="80px">
                                <span >{{customRecord.description}}</span>
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
                                <span >{{customRecord.edited_by.name}}</span>
                            </el-descriptions-item>
                        </el-descriptions>
                        
                    </template>

                    <template #relatedlist>
                        <div v-for="(c, i) in relatedListConfig">

                        <RelatedList 
                            :customRecordsRelated ="lineItemsObject[i]"
                            :title                ="c['title']"
                            :titleModel           ="c['titleModel']"
                            :visibleColumns       ="c['visibleColumns']"
                            :formFields           ="c['formFields']" 
                            :table                ="c['table']"
                            :origin               ="c['origin']"
                            :origin_field         ="c['origin_field']"
                            :target_field         ="c['target_field']"
                            :currentRecordId      ="c['currentRecordId']"
                            :searchIn             ="c['searchIn']"
                            :secondLine           ="c['secondLine']"
                            :lastLine             ="c['lastLine']"
                            classCard             ="'my-2 -pr-0 w-[470px]'" />
                        </div>
                    </template>

                </FormSection>
            </div>
        </div>

    </AppLayout>
</template>
