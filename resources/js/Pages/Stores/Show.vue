<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import moment           from 'moment';
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
    name: 'StoresShow',
    props:{
        customRecord: Object,
        relatedListConfig: Object,
        relatedList: Object
    },

    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('stores.destroy', this.customRecord.id));
            }
        },
        lineItems(){

            for (let i = 0; i < this.customRecord.line_any_items.length; i++) {
                console.log(this.customRecord.line_any_items[i]);

                if (!this.lineItemsObject[this.customRecord.line_any_items[i].type]) {
                    this.lineItemsObject[this.customRecord.line_any_items[i].type] = [];
                }
                

                this.lineItemsObject[this.customRecord.line_any_items[i].type].push(
                    this.customRecord.line_any_items[i][this.customRecord.line_any_items[i].target_id.replace('_id', '')]
                );
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
            Detalle de la tienda
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <inertia-link :href="route('stores.edit', customRecord.id)">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0"> Editar Tienda </PrimaryButton>
                </inertia-link>


                <FormSection >

                    <template #title>
                        {{customRecord.name}}
                    </template>
                    <template #description>
                        {{customRecord.workin_hours}}<br/>
                        {{customRecord.created_by.name}}

                        

                    </template>
                    <template #details>
                        <br/>
                        <el-descriptions title="Detalle del Gasto" :column="1" border>
                            <el-descriptions-item label="Calle" label-align="right" align="left" width="80px">
                                <span >{{customRecord.street}} {{ customRecord.external_number }}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Código Postal" label-align="right" align="left" width="80px">
                                <span >{{customRecord.postal_code}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Estado" label-align="right" align="left" width="80px">
                                <span >{{customRecord.state}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Colonia" label-align="right" align="left" width="80px">
                                <span >{{customRecord.town}} {{customRecord.country}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Ultima actualización por" label-align="right" align="left" width="80px">
                                <span >{{customRecord.updated_by.name}}</span>
                            </el-descriptions-item>
                        </el-descriptions>
                        <br/>

                        <el-descriptions title="Detalles de contacto" :column="1" border>
                            <el-descriptions-item label="Website" label-align="right" align="left" width="80px">
                                <span >{{customRecord.website}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="E-mail" label-align="right" align="left" width="80px">
                                <span >{{customRecord.email}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="phone" label-align="right" align="left" width="80px">
                                <span >{{customRecord.phone}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="No. Proveedores" label-align="right" align="left" width="80px">
                                <span >{{customRecord.no_providers}}</span>
                            </el-descriptions-item>
                        </el-descriptions>

                        <el-descriptions title="Detalles de servicios" :column="1" border>
                            <el-descriptions-item label="Supervisor" label-align="right" align="left" width="80px">
                                <span >{{customRecord.owner.name}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="No. de servicio CFE" label-align="right" align="left" width="80px">
                                <span >{{customRecord.no_servicio_cfe}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de pago CFE" label-align="right" align="left" width="80px">
                                <span >{{customRecord.fecha_pago_cfe}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="No. de servicio Agua" label-align="right" align="left" width="80px">
                                <span >{{customRecord.no_servicio_agua}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de pago Agua" label-align="right" align="left" width="80px">
                                <span >{{customRecord.fecha_pago_agua}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="No. de servicio Wifi" label-align="right" align="left" width="80px">
                                <span >{{customRecord.no_servicio_internet}}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de pago Wifi" label-align="right" align="left" width="80px">
                                <span >{{customRecord.fecha_pago_internet}}</span>
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
                                classCard             ="'my-2 -pr-0 w-[470px]'" />
                        </div>

                        <div v-for="(m, o) in relatedList">

                            <RelatedListNative 
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
                                classCard             ="'my-2 -pr-0 w-[470px]'" />
                        </div>

                        <!-- RelatedListNative -->

                    </template>
                    

                </FormSection>

                
            </div>
        </div>

    </AppLayout>
</template>
