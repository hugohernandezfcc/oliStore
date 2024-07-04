<template>
    <article class="text-wrap border border-red-400  rounded-lg mx-2 my-1 px-2 py-1">
        <div class="grid grid-rows-4 grid-flow-col ">
            <div class=" row-span-4 py-2 pl-2">
                <center>
                    <img :src="product.image" alt="product image" class="w-32 h-32 rounded-lg">
                </center>
            </div>
            <div class=" col-span-3 pt-2">
                <span class="text-lg font-bold text-green-600 ">$ {{ product.price }} MXN</span> | <span class="text-lg font-semibold text-red-600  ">{{ product.name }}</span>
            </div>
            <div class=" col-span-3 ">
                <span> {{ product.description }} </span>
            </div>
            <div class=" col-span-3 ">
                <el-input-number v-model="product.quantity" :min="0" :max="100" v-if="product.unit_type == 'unit'" />
                <el-input v-model="product.quantity" style="width: 220px; color:white;" placeholder="Please input" class="input-with-select"  v-if="product.unit_type == 'grams'">
                    <template #append>
                        <el-select v-model="select" placeholder="PESOS" style="width: 120px; color: white;" id="unitType">
                            <el-option label="PESOS" value="1" />
                            <el-option label="GRAMOS" value="2" />
                        </el-select>
                    </template>
                </el-input>
            </div>
        </div>
    </article>

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
    #unitType{
        background-color: red !important;
        color: white !important;
    }
    i > svg {
        color: white;
    }
</style>
<script>

export default {
    name: 'ProductItem',
    props: {
        product: Object
    },
    components: {

    },
    data() {
        return {
            select: '1'
        }
    },
    methods: {
        
    },
    mounted() {
        console.log('mounting wall item component');

    }
}
</script>