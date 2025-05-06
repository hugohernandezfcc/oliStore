<template>

    <div class="grid grid-cols-8 gap-4 bg-white rounded-lg mx-2 my-1 px-2 py-1">
        <div class="p-2 col-span-2">
            <img :src="product.image" alt="product image" class="h-20 w-20 md:h-32 md:w-32 rounded-lg">
        </div>
        <div class="p-2 col-span-6">
            <span class="font-bold text-green-600 ">$ {{ product.price }} MXN</span>
            <br/>
            <span class=" font-semibold text-red-600 text-sm ">{{ product.name }}</span>
            <span class="block break-words text-xs ">
                {{ product.description }}
            </span>
            <span class="text-xs font-bold text-blue-700" v-if="product.bundle != null && product.bundle > 0">*Paquete con {{ product.bundle }} piezas = ${{ (product.price * product.bundle).toFixed(1) }} MXN</span>
            <br/>
            <el-input-number class="touch-manipulation" style="width: 120px;"  v-model="product.quantity" :min="0" :max="100" v-if="product.unit_type == 'unit'" />
            <el-input v-model="product.quantity" style="width: 150px; color:white;" placeholder="Please input" class="input-with-select"  v-if="product.unit_type == 'grams'">
                <template #append>
                    <el-select v-model="product.unit_subtype" placeholder="PESOS" style="width: 80px; color: white;" id="unitType">
                        <el-option label="$" value="1" />
                        <el-option label="KG" value="2" />
                    </el-select>
                </template>
            </el-input>
            <el-tooltip
                class="box-item"
                effect="dark"
                content="Pedido por caja o pieza"
                placement="top-end"
            >
                <el-switch
                    v-if="product.quantity > 0 && product.unit_type == 'unit'"
                    v-model="product.package"
                    class="ml-2 touch-manipulation"
                    inline-prompt
                    style="--el-switch-on-color: #13ce66; --el-switch-off-color: #ff4949"
                    active-text=" Caja "
                    inactive-text=" Pieza "
                    @change="(product.package) ? product.quantity = product.quantity * product.bundle : product.quantity = product.quantity / product.bundle"
                />
            </el-tooltip>
        </div>

    </div>

    <!-- <article class="text-wrap border border-red-400 bg-white  rounded-lg mx-2 my-1 px-2 py-1">
        <div class="grid grid-rows-2 grid-flow-col ">
            <div class="w-20 md:w-32 row-span-3 md:row-span-4 py-2 px-1 md:m-1 ">
                <center>
                    <img :src="product.image" alt="product image" class="h-16 w-16 md:h-32 md:w-32 rounded-lg">
                </center>
            </div>
            <div class=" col-span-3 ">
                <span class="font-bold text-green-600 ">$ {{ product.price }} MXN</span> <br/> <span class=" font-semibold text-red-600  ">{{ product.name }}</span>
            </div>
            <div class=" col-span-3 ">

                <div class="w-[250px] md:w-[400px]">
                    <span class="block break-words">
                        {{ product.description }}
                    </span>
                </div>
            </div>
            <div class=" col-span-3 ">
                <el-input-number class="touch-manipulation" v-model="product.quantity" :min="0" :max="100" v-if="product.unit_type == 'unit'" />
                <el-input v-model="product.quantity" style="width: 220px; color:white;" placeholder="Please input" class="input-with-select"  v-if="product.unit_type == 'grams'">
                    <template #append>
                        <el-select v-model="product.unit_subtype" placeholder="PESOS" style="width: 120px; color: white;" id="unitType">
                            <el-option label="PESOS" value="1" />
                            <el-option label="KG" value="2" />
                        </el-select>
                    </template>
                </el-input>
            </div>
        </div>
    </article> -->

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
    button {
  touch-action: manipulation !important;
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
        // console.log('mounting wall item component');

    }
}
</script>