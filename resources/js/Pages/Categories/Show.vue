<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import FormSection      from '@/Components/FormSection.vue';
import RelatedList      from '@/Components/RelatedList.vue';
import RelatedListNative from '@/Components/RelatedListNative.vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection,
        RelatedList,
        RelatedListNative
    },
    name: 'CategoriesShow',
    props:{
        customRecord: Object,
        relatedListConfig: Object,
        relatedList: Object
    },

    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('categories.destroy', this.customRecord.id));
            }
        },
        lineItems(){

            for (let i = 0; i < this.customRecord.line_any_items.length; i++) {


                if (!this.lineItemsObject[this.customRecord.line_any_items[i].type]) {
                    this.lineItemsObject[this.customRecord.line_any_items[i].type] = [];
                }

                this.lineItemsObject[this.customRecord.line_any_items[i].type].push(
                    (this.customRecord.line_any_items[i][this.customRecord.line_any_items[i].target_id.replace('_id', '')] == undefined) ? this.customRecord.line_any_items[i][this.customRecord.line_any_items[i].target_id.replace('_id', 's')] : this.customRecord.line_any_items[i][this.customRecord.line_any_items[i].target_id.replace('_id', '')]
                );
                this.lineItemsObject[this.customRecord.line_any_items[i].type][this.lineItemsObject[this.customRecord.line_any_items[i].type].length-1]['_id'] = this.customRecord.line_any_items[i].id;
            }

            console.log(this.lineItemsObject)
        }
    },
    data(){
        return {
            lineItemsObject: new Array(),
            
            date: new Date()
        }
    },
    mounted(){
        let globalResults = [];
        this.lineItems();
        console.log('componente montado', this.customRecord)
        console.log('testing', this.lineItemsObject)
        console.log('testing', this.customRecord.boxes)

    },
    computed: {
        
    }

}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            Detalle de la Categoría
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <inertia-link :href="route('stores.edit', customRecord.id)">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0"> Editar Categoría </PrimaryButton>
                </inertia-link>&nbsp;&nbsp;
                <PrimaryButton @click="eliminar" class="mb-3 ml-3 lg:ml-1"> 
                    Eliminar <svg class="ml-1 -mt-0.5 h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                </PrimaryButton>


                <FormSection >

                    <template #title>
                        {{customRecord.name}}
                    </template>
                    <template #description>

                        {{customRecord.created_by.name}}

                        

                    </template>
                    <template #details>

                        <br/>
                        <el-descriptions title="Detalle del Gasto" :column="1" border>
                            <el-descriptions-item label="Categoría" label-align="right" align="left" width="80px">
                                <span >{{customRecord.name}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Descripción" label-align="right" align="left" width="80px">
                                <span >{{customRecord.description}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Icono" label-align="right" align="left" width="80px">
                                <el-image
                                    style="width: 40px;"
                                    :src="customRecord.image" />
                            </el-descriptions-item>
                            <el-descriptions-item label="Ultima actualización por" label-align="right" align="left" width="80px">
                                <span >{{customRecord.updated_by.name}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de creación" label-align="right" align="left" width="80px">
                                <span >{{customRecord.created_at}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Ultima actualización " label-align="right" align="left" width="80px">
                                <span >{{customRecord.updated_at}}</span>
                            </el-descriptions-item>
                        </el-descriptions>
                        <br/>

                       

                    </template>
                    <template #relatedlist>

                        <div   v-for="(c, i) in relatedListConfig">

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

                        <div v-for="(m, o) in relatedList">

                            <!-- <RelatedListNative 
                                :customRecordsRelated ="customRecord.boxes"
                                :title                ="m['title']"
                                :titleModel           ="m['titleModel']"
                                :visibleColumns       ="m['visibleColumns']"
                                :formFields           ="m['formFields']" 
                                :table                ="m['table']"
                                :origin               ="m['origin']"
                                :origin_field         ="m['origin_field']"
                                :currentRecordId      ="m['currentRecordId']"
                                :showNewRecordButton  ="m['showNewRecordButton']"
                                classCard             ="'my-2 -pr-0 w-[470px]'" /> -->
                        </div>

                        <!-- RelatedListNative -->

                    </template>
                    

                </FormSection>

                
            </div>
        </div>

    </AppLayout>
</template>
