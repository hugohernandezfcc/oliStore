<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import FormSection      from '@/Components/FormSection.vue';
import RelatedList      from '@/Components/RelatedList.vue';
import RelatedListNative from '@/Components/RelatedListNative.vue';
import { ArrowLeftBold } from '@element-plus/icons-vue';
import SalesOrderGenerator from '@/Components/SalesOrderGenerator.vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection,
        RelatedList,
        RelatedListNative,
        ArrowLeftBold, 
        SalesOrderGenerator

    },
    name: 'AccountShow',
    props:{
        account: Object,
        contacts: Array,
        salesOrders: Array,
    },

    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                axios.delete(route('accounts.destroy', this.account.id)).then(response => {
                    console.log(response.data);
                    this.$inertia.visit(route('accounts.index'));
                }).catch(error => {
                    console.log(error);
                });
            }
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
        console.log('componente montado', this.account)
        console.log('componente montado', this.contacts)
        console.log('componente montado', this.salesOrders)
        // console.log('componente montado', this.referenceb2c)
    },
    computed: {
        
    }

}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <PrimaryButton  class="mb-3 ml-3 lg:ml-0" @click="$inertia.visit(route('accounts.index'))"> 
                <el-icon><ArrowLeftBold /></el-icon>
            </PrimaryButton>&nbsp;Detalle del cliente
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <inertia-link :href="route('accounts.edit', account.id)">
                    <PrimaryButton  class="mb-3 ml-3 lg:ml-0"> Editar cliente </PrimaryButton>
                </inertia-link>&nbsp;&nbsp;
                <PrimaryButton @click="eliminar" class="mb-3 ml-3 lg:ml-1"> 
                    Eliminar <svg class="ml-1 -mt-0.5 h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                </PrimaryButton>


                <FormSection >

                    <template #title>
                        {{account.name}}
                    </template>
                    <template #description>

                        {{account.address}}<br/>
                        Creador: {{account.created_by.name}} 

                        

                    </template>
                    <template #details>

                        <br/>
                        <el-descriptions title="Detalle del producto" :column="1" border>
                            <el-descriptions-item label="Nombre Cuenta" label-align="right" align="left" >
                                <span >{{account.name}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Dirección" label-align="right" align="left" >
                                <span >{{account.address}}, {{ account.city }}, {{ account.state }}, {{ account.zip }}, {{ account.country }} </span>
                            </el-descriptions-item>
                            <!-- <el-descriptions-item label="Imagen de producto" label-align="right" align="left" >
                                <el-image style="width: 50px;" :src="customRecord.image" />
                            </el-descriptions-item> -->
                            <el-descriptions-item label="Actualizado por" label-align="right" align="left" >
                                <!-- <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=Av.+Paseo+de+la+Reforma+123,+Ciudad+de+México,+CDMX,+México">
                                    prueba
                                </a> -->
                                <span > {{account.edited_by.name}} </span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Fecha de creación" label-align="right" align="left" >
                                <span >{{ account.created_at.split('T')[0] }}</span>
                            </el-descriptions-item>
                            <el-descriptions-item label="Ultima actualización " label-align="right" align="left" >
                                <span >{{account.updated_at.split('T')[0]}}</span>
                            </el-descriptions-item>
                        </el-descriptions>
                        <br/>

                        <!-- <Referenceb2cElement 
                        :cost="referenceb2c.price_list"
                        :price="referenceb2c.price_customer"
                        :porcentage="((referenceb2c.price_customer - referenceb2c.price_list) / referenceb2c.price_list) * 100"
                         v-if="referenceb2c != null"></Referenceb2cElement> -->

                    </template>
                    <template #relatedlist>
                        <SalesOrderGenerator :parentRecord="account" :salesOrders="salesOrders" />

                    </template>
                    

                </FormSection>

                
            </div>
        </div>

    </AppLayout>
</template>
