<template>
    <div class="m-3 p-2 border-red-600 border bg-white">
        <div class="-mt-5 -mr-5">
            <el-button type="danger" size="small" :icon="Delete" circle />
        </div>
        <div class="flex pb-1 pt-2">
            <div class="w-8/12">
                <span class="text-lg font-bold text-red-600 ">{{product.name}}</span>
            </div>
            <div class="w-4/12">
                <span class="text-sm underline font-bold text-gray-600 ">${{product.total}} MXN</span>
            </div>
        </div>
        <div class="flex bg-red-100 -m-2 p-2">
            <div class="w-8/12">
                <el-input-number v-model="product.quantity" :min="0" :max="100"  />
            </div>
            <div class="w-4/12">
                ID: {{ product.id }}
            </div>
        </div>
    </div>

</template>
<style >
    .el-input-number__decrease, .el-input-number__increase {
        background: #dc2626 !important;
        color: white !important;
    }
</style>
<script>

export default {
    name: 'PosItem',
    props: {
        product_d: Object
    },
    components: {

    },
    data() {
        return {
            product: {
                name: 'Coca cola 2 L',
                id: 2824,
                price: 2.5,
                quantity: 1,
                total: 2.555
            }
        }
    },
    methods: {
        togglePosts() {
            // This method toggles the visibility state
            this.showPosts = !this.showPosts;
        }
    },
    mounted() {
        console.log('mounting wall item component');
        console.log(this.task);
    }
}
</script>