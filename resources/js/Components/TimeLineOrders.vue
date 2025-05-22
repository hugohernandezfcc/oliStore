<template>
    <el-timeline class="w-[90%] pt-24" >
        <el-timeline-item v-for="activity in activities" :timestamp="activity.created_at.split('T')[0]" placement="top">
            <el-card>
                <el-tag class="mb-2 font-bold" :type="activity.status != 'Abierta' ? 'danger' : 'success'">{{ activity.status }}</el-tag>
                <div class="text-base ">
                    <h4><b>Folio de pedido:</b> #{{ activity.id }}</h4>
                    <p><b>Hora:</b> {{ activity.created_at.split('T')[1].split('.')[0].split(':')[0] }}:{{ activity.created_at.split('T')[1].split('.')[0].split(':')[1] }}</p>
                    <p><b>Total:</b> <span class="text-green-600 font-bold">$ {{ activity.total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }) }}</span></p>
                </div>
                <br/>
                <el-button id="checkout" @click="showOrder(activity.id)">Productos</el-button>
                <!-- <el-button id="checkout2" @click="showOrder(activity.id)">Pedir de nuevo</el-button> -->
            </el-card>
        </el-timeline-item>
    </el-timeline>
    <el-dialog v-model="dialogTableVisible" width="95%" :show-close="false" >
        <template #header="{ close, titleId, titleClass }">
            <div class="my-header">
                <h4 :id="titleId" :class="titleClass">Productos del pedido </h4>
                <el-button id="checkout" @click="close">
                    <el-icon ><CircleCloseFilled /></el-icon>
                </el-button>
            </div>
        </template>
        <el-table :data="gridData">
            <el-table-column label="Producto" width="165" >
                <template #default="scope">
                    <span class="text-sm">{{ scope.row.productb2b.name }} </span>
                </template>
            </el-table-column>
            <el-table-column label="Cantidad" width="85" >
                <template #default="scope">
                    <span class="text-sm">{{ scope.row.quantity }}</span>
                </template>
            </el-table-column>
            <el-table-column label="Precio" width="80" >
                <template #default="scope">
                    <span class="text-sm">$ {{ scope.row.unit_price }}</span>
                </template>
            </el-table-column>
        </el-table>
    </el-dialog>
  </template>

<script >
import { CircleCloseFilled } from '@element-plus/icons-vue';
export default {
    name: 'TimeLineOrders',
    components: {
        CircleCloseFilled
    },
    props: {
        account: Object,
    },
    data() {
        return {
            activities: [],
            dialogTableVisible: false,
            gridData: []
        }
    },
    methods: {

        showOrder(id){
            axios.get('/app/salesorder/osa/products/' + id).then(response => {
                console.log(response.data);
                this.gridData = response.data;
                this.dialogTableVisible = true;
            });
        },

        getOrdersByAccount() {
            axios.get('/app/salesorder/osa/' + this.account.id).then(response => {
                console.log(response.data);
                this.activities = response.data;

            });
        }
    },
    mounted() {
        this.getOrdersByAccount();
    }
}
</script>
<style>
    #checkout{
        background-color: #dc2626 !important;
        color: white !important;
        border: none !important;
    }
    #checkout2{
        background-color: #16a34a !important;
        color: white !important;
        border: none !important;
    }
</style>
