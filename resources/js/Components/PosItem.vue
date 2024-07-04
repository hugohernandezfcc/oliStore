<template>
    <div class="m-3 p-2 border-red-600 border bg-white">
        <div class="-mt-5 -mr-5">
            <el-button type="danger" style="background-color: #dc2626;" size="small" circle >X</el-button>
        </div>
        <div class="flex pb-1 pt-0">
            <div class="w-8/12">
                <span class="text-lg font-bold text-red-600 ">{{product.name}}</span>
            </div>
            <div class="w-4/12">
                <span class="text-sm underline font-bold text-gray-600 ">${{product.total}} MXN</span>
            </div>
        </div>
        <div class="flex bg-red-100 -m-2 p-1">
            <div class="w-8/12">
                <el-input-number v-model="product.quantity" :min="0" :max="100" v-if="product.granel == false" />
                <el-input v-model="input3" style="width: 220px" placeholder="Please input" class="input-with-select" v-if="product.granel == true">
                    <template #append>
                        <el-select v-model="select" placeholder="Select" style="width: 90px; color: white;">
                        <el-option label="Gramos" value="1" />
                        <el-option label="Dinero" value="2" />
                        </el-select>
                    </template>
                </el-input>
            </div>
            <div class="w-4/12">
                ID: {{ product.id }}
            </div>
        </div>
    </div>

</template>
<style >
    .el-input-number__decrease, .el-input-number__increase {
        background: red !important;
        color: white !important;
    }
    .el-input-group--append .el-input-group__append .el-select .el-input .el-input__wrapper {
        background-color: red !important;
        color: white !important;
    }
</style>
<script>

export default {
    name: 'PosItem',
    props: {
        product: Object
    },
    components: {

    },
    data() {
        return {
            product_d: {
                name: 'Coca cola 2 L',
                id: 2824,
                price: 2.5,
                quantity: 1,
                total: 2.555,
                granel: true
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