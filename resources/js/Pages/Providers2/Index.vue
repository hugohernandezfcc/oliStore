<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Calendar } from '@element-plus/icons-vue';
import Field from '@/Components/Field.vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Calendar,
        Field
    },

    props:{
        providers: Array 
    },
    methods:{
        showMapWeek(){
            this.showSchedule = !this.showSchedule;
        },
        openModal(order){
            this.dialog = true;
            console.log(order);
            this.localOrder = order;
        },
        renderOrders(){
            axios.get(route('schedule.orders')).then(response => {
                console.log(response.data);


                for (let index = 0; index < response.data.length; index++) {
                    const element = response.data[index];
                    
                    if(element.weekday.indexOf('LUNES') > -1){
                        this.weekDays[0].orders.push(element);
                    }

                    if(element.weekday.indexOf('MARTES') > -1){
                        this.weekDays[1].orders.push(element);
                    }

                    if(element.weekday.indexOf('MIERCOLES') > -1){
                        this.weekDays[2].orders.push(element);
                    }

                    if(element.weekday.indexOf('JUEVES') > -1){
                        this.weekDays[3].orders.push(element);
                    }

                    if(element.weekday.indexOf('VIERNES') > -1){
                        this.weekDays[4].orders.push(element);
                    }

                    if(element.weekday.indexOf('SABADO') > -1){
                        this.weekDays[5].orders.push(element);
                    }

                    if(element.weekday.indexOf('DOMINGO') > -1){
                        this.weekDays[6].orders.push(element);
                    }
                }

                

            }).catch(error => {
                console.log(error);
            });
        }
    },
    data(){
        return {
            searchQuery: '',
            checklistItems: [
                { id: 1, label: 'Item 1', checked: false },
                { id: 2, label: 'Item 2', checked: false },
                { id: 3, label: 'Item 3', checked: false },
                { id: 4, label: 'Item 4', checked: false },
                { id: 5, label: 'Item 5', checked: false },
            ],
            orderForm:{
                name: '',
                description: ''
            },
            localOrder: {},
            dialog: false,
            rowCollectionSelected: new Array(),
            search:'',
            showSchedule: false,
            ventaCSS: 'bg-red-600 text-white p-1 rounded-t-lg',
            preventaCSS: 'bg-yellow-600 text-white p-1 rounded-t-lg',
            weekDays:[
                {
                    day: 'LUNES',      
                    orders: []
                },
                {
                    day: 'MARTES',     
                    orders: []
                },
                {
                    day: 'MIERCOLES',  
                    orders: []
                },
                {
                    day: 'JUEVES',     
                    orders: []
                },
                {
                    day: 'VIERNES',    
                    orders: []
                },
                {
                    day: 'SABADO',     
                    orders: []
                },
                {
                    day: 'DOMINGO',    
                    orders: []
                }
            ]
        }
    },
    mounted(){
        console.log('mounted',this.providers);
        this.renderOrders();
    },
    computed: {
        filterTableData() {

            return this.providers.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        },
        filteredItems() {
            return this.checklistItems.filter(item =>
                item.label.toLowerCase().includes(this.searchQuery.toLowerCase())
            )
        }
    }

}
</script>
<template>
    <AppLayout title="Proveedores">
        <template #header>
            <h3 class="text-lg text-gray-900"> Listado de Proveedores - # {{ providers.length }}</h3>
            <p class="text-sm text-gray">Catalogo de Proveedores registrados </p>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-2">
            <div class="flex flex-row ">
                <div class="basis-1/2 mx-1">
                    <inertia-link :href="route('providers.create')" class="m-1"> 
                        <PrimaryButton >
                            Nuevo proveedor
                        </PrimaryButton>
                    </inertia-link> 
                    
                    <el-button size="normal" color="#dc2626" @click="showMapWeek"> 
                        <svg v-if="!showSchedule" class="h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M128 384v512h768V192H768v32a32 32 0 1 1-64 0v-32H320v32a32 32 0 0 1-64 0v-32H128v128h768v64zm192-256h384V96a32 32 0 1 1 64 0v32h160a32 32 0 0 1 32 32v768a32 32 0 0 1-32 32H96a32 32 0 0 1-32-32V160a32 32 0 0 1 32-32h160V96a32 32 0 0 1 64 0zm-32 384h64a32 32 0 0 1 0 64h-64a32 32 0 0 1 0-64m0 192h64a32 32 0 1 1 0 64h-64a32 32 0 1 1 0-64m192-192h64a32 32 0 0 1 0 64h-64a32 32 0 0 1 0-64m0 192h64a32 32 0 1 1 0 64h-64a32 32 0 1 1 0-64m192-192h64a32 32 0 1 1 0 64h-64a32 32 0 1 1 0-64m0 192h64a32 32 0 1 1 0 64h-64a32 32 0 1 1 0-64"></path></svg>
                        <svg v-if="showSchedule" class="h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M685.248 104.704a64 64 0 0 1 0 90.496L368.448 512l316.8 316.8a64 64 0 0 1-90.496 90.496L232.704 557.248a64 64 0 0 1 0-90.496l362.048-362.048a64 64 0 0 1 90.496 0z"></path></svg>
                    </el-button>

                </div>
                <div class="basis-1/2">
                    <el-input v-model="search"  placeholder="Buscar proveedor" class="shadow-2xl" v-if="!showSchedule" />
                    <el-input v-model="search"  placeholder="Buscar en la programación" class="shadow-2xl" v-if="showSchedule"/>
                </div>
            </div>

            <br/>
            <el-table  v-if="!showSchedule" :data="filterTableData" height="600" class="shadow-lg m-1"  stripe :default-sort="{ prop: 'updated_at', order: 'descending' }" >
                <el-table-column align="left" width="70" fixed="left">
                    <template #default="scope">
                        <inertia-link :href="route('providers.show', scope.row.id)" >
                            <el-button size="small" color="#dc2626"> 
                                <svg class="h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M512 160c320 0 512 352 512 352S832 864 512 864 0 512 0 512s192-352 512-352m0 64c-225.28 0-384.128 208.064-436.8 288 52.608 79.872 211.456 288 436.8 288 225.28 0 384.128-208.064 436.8-288-52.608-79.872-211.456-288-436.8-288zm0 64a224 224 0 1 1 0 448 224 224 0 0 1 0-448m0 64a160.192 160.192 0 0 0-160 160c0 88.192 71.744 160 160 160s160-71.808 160-160-71.744-160-160-160"></path></svg>
                            </el-button>
                        </inertia-link>
                    </template>
                </el-table-column>


                <el-table-column prop="representative"   label="Representante"  width="210" sortable/>
                <el-table-column prop="phone"            label="Teléfono"       width="180" sortable/>
                <el-table-column prop="whatsapp"         label="Whatsapp"       width="180" sortable/>
                <el-table-column prop="company"          label="Compañia"       width="200" sortable/>
                <el-table-column prop="visit_day"        label="Días visita"    width="200" sortable/>
                <el-table-column prop="updated_at" label="ULTIMA ACTUALIZACIÓN" width="220" sortable/>
               
            </el-table>
            <div v-if="showSchedule" class="flex flex-wrap gap-1 font-mono text-white text-sm font-bold leading-6 bg-stripes-indigo rounded-lg ">
                <div v-for="item in weekDays" class="w-full sm:w-full md:w-full lg:w-44 rounded-lg bg-gray-700 shadow-lg px-1">
                    <div class="text-white bold px-4"> {{item.day}} </div>
                    <div v-for="order in item.orders" >
                        <div :class="(order.type == 'Venta') ? ventaCSS : preventaCSS  " id="header">
                            {{ order.company }}
                        </div>
                        <div class="bg-white rounded-b-lg border p-2 border-red-600 text-black mb-1 " id="body">
                            {{ (order.status == 'Pendiente') ? order.order : 'Entregado' }}
                            <center>
                                <el-button size="small" color="#dc2626" @click="openModal(order)">
                                    <svg class="h-4 w-4 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 480h320a32 32 0 1 1 0 64H352a32 32 0 0 1 0-64"></path><path fill="currentColor" d="M480 672V352a32 32 0 1 1 64 0v320a32 32 0 0 1-64 0"></path><path fill="currentColor" d="M512 896a384 384 0 1 0 0-768 384 384 0 0 0 0 768m0 64a448 448 0 1 1 0-896 448 448 0 0 1 0 896"></path></svg>
                                </el-button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <br/>

        </div>

    </AppLayout>
    <el-drawer
        v-model="dialog"
        :title="'Generación de órden de compra para : ' + localOrder.company "
        :before-close="handleClose"
        direction="btt"
        class=" border-red-600  rounded-3xl"
        size="80%"
    >
        <div class="demo-drawer__content ">

            <el-input v-model="orderForm.description"   placeholder="Escribe comentarios" class=" m-2 shadow-lg shadow-red-100 border-red-300 border " />
            
            <el-input v-model="searchQuery"             placeholder="Filtrar producto" class=" m-2 shadow-lg shadow-red-100 border-red-300 border " />
            

            <div v-for="item in filteredItems" :key="item.id"  >
                <label class="bg-red-600 text-white p-1 rounded-lg" ><input type="checkbox" v-model="item.checked" /> {{ item.label }}</label>
            </div>
        


        <!-- <div class="demo-drawer__footer"> -->
            <el-button @click="cancelForm">Cancel</el-button>
            <el-button type="primary" :loading="loading" @click="onClick">
            {{ loading ? 'Submitting ...' : 'Submit' }}
            </el-button>
        <!-- </div> -->
        </div>
    </el-drawer>
</template>
