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
        productsb2b: Array
    },
    methods:{
        changeStatus(id, status){
            console.log('cambio de estado', id, status);

            axios.post('/b2b/productsb2b/changestatus', {
                id: id,
                status: status
            }).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            });
        },
        changingCateogory(data){
            console.log('cambiando categoria', data)
            axios.post('/b2b/productsb2b/changing/cateogry', data).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            });
        },
        updateCategories(visible, recordId, categories){
            if(!visible){
                let preparingData = {id: recordId};
                for(let i = 0; i < categories.length; i++){
                    preparingData[categories[i]] = true;
                }
                for(let i = 0; i < this.virtualCategories.length; i++){
                    if(!categories.includes(this.virtualCategories[i].value)){
                        preparingData[this.virtualCategories[i].value] = false;
                    }
                }

                console.log(preparingData);
                this.changingCateogory(preparingData);
            }

        },
        deleteProduct(id){
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                axios.delete(route('productsb2b.destroy', id))
                .then(response => {
                    console.log(response.data);
                    this.$inertia.visit(route('productsb2b.index'));
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        updateOrder(id, order){
            console.log('cambio de orden', id, order);
            let preparingData = {id: id, order: order};
            axios.post('/b2b/productsb2b/changing/cateogry', preparingData).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            });
        }
    },
    data(){
        return {
            rowCollectionSelected: new Array(),
            search:'',
            virtualProducts:[],
            virtualCategories:[
                {label: 'Granel', value: 'bulkSale'},
                {label: 'Bebidas', value: 'drinks'},
                {label: 'Botanas', value: 'snacks'},
                {label: 'Abarrotes', value: 'groceries'},
                {label: 'Limpieza', value: 'cleaning'},
                {label: 'Bajo zorro', value: 'underFox'},
                {label: 'Cigarros', value: 'cigars'}
            ]

        }
    },
    mounted(){
        this.virtualProducts = this.productsb2b;
        for(let i = 0; i < this.virtualProducts.length; i++){
            this.virtualProducts[i].virtualCategories = [];

            if(this.virtualProducts[i].bulkSale){
                this.virtualProducts[i].virtualCategories.push('bulkSale')
            }

            if(this.virtualProducts[i].drinks){
                this.virtualProducts[i].virtualCategories.push('drinks')
            }

            if(this.virtualProducts[i].snacks){
                this.virtualProducts[i].virtualCategories.push('snacks')
            }

            if(this.virtualProducts[i].groceries){
                this.virtualProducts[i].virtualCategories.push('groceries')
            }

            if(this.virtualProducts[i].cleaning){
                this.virtualProducts[i].virtualCategories.push('cleaning')
            }

            if(this.virtualProducts[i].underFox){
                this.virtualProducts[i].virtualCategories.push('underFox')
            }

            if(this.virtualProducts[i].cigars){
                this.virtualProducts[i].virtualCategories.push('cigars')
            }

        }
        console.log('mounted',this.productsb2b);
    },
    computed: {
        filterTableData() {
            return this.productsb2b.filter(
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
            <h3 class="text-lg text-gray-900"> Listado de productos - # {{ productsb2b.length }}</h3>
            <p class="text-sm text-gray">Catalogo de productos registrados </p>
        </template>



        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-2">
            <div class="flex flex-row m-1">
                <div class="basis-1/2">
                    <inertia-link :href="route('productsb2b.create')" class="m-1">
                        <PrimaryButton >
                            Nuevo Producto
                        </PrimaryButton>
                    </inertia-link>
                </div>
                <div class="basis-1/2">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </div>
            </div>

            <br/>


            <el-table :data="filterTableData" class="shadow-lg m-1" stripe  >
                <el-table-column width="90" label="Editar" >
                    <template #default="scope" >
                        <a :href="route('productsb2b.edit', scope.row.id)" class="text-blue-500 hover:text-blue-600" >
                            Editar
                        </a>
                    </template>
                </el-table-column>
                <el-table-column width="100" label="Eliminar" >
                    <template #default="scope" >
                        <el-button size="small" color="#dc2626" @click="deleteProduct(scope.row.id)" >
                            X
                        </el-button>
                    </template>
                </el-table-column>
                <el-table-column align="left" width="385" label="Nombre producto" fixed="left">
                    <template #default="scope">
                        <a :href="route('productsb2b.show', scope.row.id)" class="text-blue-500 hover:text-blue-600" >
                            <b>{{scope.row.name}}</b>
                        </a>
                    </template>
                </el-table-column>
                <el-table-column width="140" label="Publicar" >
                    <template #default="scope" >
                        <el-checkbox v-model="scope.row.is_public" @change="changeStatus(scope.row.id, scope.row.is_public)" :label="(scope.row.is_public) ? 'Publico' : 'Oculto'" />
                    </template>
                </el-table-column>
                <el-table-column width="280" label="Categorías" >
                    <template #default="scope" >
                        <el-select-v2
                            v-model="scope.row.virtualCategories"
                            :options="virtualCategories"
                            placeholder="Seleccionar categorias"
                            style="width: 240px"
                            multiple
                            clearable
                            filterable
                            @visible-change="updateCategories($event, scope.row.id, scope.row.virtualCategories)"
                        />
                    </template>
                </el-table-column>
                <el-table-column width="90" label="Orden" sortable >
                    <template #default="scope" >
                        <el-input v-model="scope.row.order" style="width: 50px" placeholder="Orden" @blur="updateOrder(scope.row.id, scope.row.order)" />
                    </template>
                </el-table-column>
                <el-table-column width="180" label="Fecha de creación" sortable >
                    <template #default="scope" >
                        {{ scope.row.created_at }}
                    </template>
                </el-table-column>

            </el-table>
            <br/>

        </div>

    </AppLayout>
</template>
<style >
span.el-select-v2__tags-text{
    color: red !important;
}
</style>

