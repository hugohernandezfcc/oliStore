<template>
    <div class="flex">
        <div class="flex-1 w-64 ..."></div>
        <div class="flex-1 w-64 ..."></div>
        <div class="flex-1 w-16 m-3 p-2 border-red-600 border bg-white shadow-md ">
            <div class="flex flex-row">
                <div class="basis-4/6 pr-4 pl-3   ">
                    <el-input v-model="scanner" placeholder="Please input" class="border-red-600 border font-bold  ">
                        <template #prefix>
                            <svg fill="#000000" height="25px" width="30px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                viewBox="0 0 60 60" xml:space="preserve">
                                <g>
                                    <path d="M5,49c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v37C4,48.553,4.448,49,5,49z"/>
                                    <path d="M55,49c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v37C54,48.553,54.448,49,55,49z"/>
                                    <path d="M9,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C8,40.553,8.448,41,9,41z"/>
                                    <path d="M12,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C11,40.553,11.448,41,12,41z"/>
                                    <path d="M17,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C16,40.553,16.448,41,17,41z"/>
                                    <path d="M20,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C19,40.553,19.448,41,20,41z"/>
                                    <path d="M23,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C22,40.553,22.448,41,23,41z"/>
                                    <path d="M29,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C28,40.553,28.448,41,29,41z"/>
                                    <path d="M34,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C33,40.553,33.448,41,34,41z"/>
                                    <path d="M37,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C36,40.553,36.448,41,37,41z"/>
                                    <path d="M42,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C41,40.553,41.448,41,42,41z"/>
                                    <path d="M45,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C44,40.553,44.448,41,45,41z"/>
                                    <path d="M51,41c0.552,0,1-0.447,1-1V11c0-0.553-0.448-1-1-1s-1,0.447-1,1v29C50,40.553,50.448,41,51,41z"/>
                                    <path d="M9,47c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C8,46.553,8.448,47,9,47z"/>
                                    <path d="M12,47c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C11,46.553,11.448,47,12,47z"/>
                                    <path d="M15,47c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C14,46.553,14.448,47,15,47z"/>
                                    <path d="M17,45v1c0,0.553,0.448,1,1,1s1-0.447,1-1v-1c0-0.553-0.448-1-1-1S17,44.447,17,45z"/>
                                    <path d="M20,45v1c0,0.553,0.448,1,1,1s1-0.447,1-1v-1c0-0.553-0.448-1-1-1S20,44.447,20,45z"/>
                                    <path d="M23,45v1c0,0.553,0.448,1,1,1s1-0.447,1-1v-1c0-0.553-0.448-1-1-1S23,44.447,23,45z"/>
                                    <path d="M27,47c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C26,46.553,26.448,47,27,47z"/>
                                    <path d="M29,45v1c0,0.553,0.448,1,1,1s1-0.447,1-1v-1c0-0.553-0.448-1-1-1S29,44.447,29,45z"/>
                                    <path d="M33,47c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C32,46.553,32.448,47,33,47z"/>
                                    <path d="M35,46c0,0.553,0.448,1,1,1s1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1V46z"/>
                                    <path d="M38,46c0,0.553,0.448,1,1,1s1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1V46z"/>
                                    <path d="M42,47c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C41,46.553,41.448,47,42,47z"/>
                                    <path d="M45,47c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C44,46.553,44.448,47,45,47z"/>
                                    <path d="M48,47c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C47,46.553,47.448,47,48,47z"/>
                                    <path d="M51,47c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C50,46.553,50.448,47,51,47z"/>
                                    <path d="M0,5v50h60V5H0z M58,53H2V7h56V53z"/>
                                </g>
                            </svg>
                        </template>
                    </el-input>
                </div>
                <div class="basis-2/6">
                    <span class="text-lg font-bold text-red-600 underline">Total: 55.55545 MXN</span>
                </div>
            </div>
            <div class="" v-for="(product, index) in products" :key="index">
                <PosItem :product="product" />
            </div>
        </div>
    </div>
    
    <el-drawer
        v-model="drawer"
        title="Busqueda de productos"
        :direction="'ttb'"
        :before-close="handleBeforeClose"
    >
    <span>Hi, there!</span>
    </el-drawer>
</template>
<style >
    .el-input-number__decrease, .el-input-number__increase {
        background: #dc2626 !important;
        color: white !important;
    }
    .el-input-group--append .el-input-group__append .el-select .el-input .el-input__wrapper {
        background-color: #dc2626 !important;
        color: white !important;
    }
</style>
<script>
import PosItem from './PosItem.vue';

export default {
    name: 'PosList',
    props: {
        product_d: Object
    },
    components: {
        PosItem
    },
    data() {
        return {
            scanner: '',
            products: [{
                name: 'Coca cola 2 L',
                id: 2824,
                price: 2.5,
                quantity: 1,
                total: 2.555,
                granel: false
            },{
                name: 'Coca cola 3 L',
                id: 2824,
                price: 2.5,
                quantity: 1,
                total: 2.555,
                granel: true
            },{
                name: 'Coca cola 1 L',
                id: 2824,
                price: 2.5,
                quantity: 1,
                total: 2.555,
                granel: false
            },],
            drawer: false
        }
    },
    methods: {
        handleBeforeClose() {
            console.log('handleBeforeClose') 
            this.drawer = false;
        }
    },
    mounted() {
        console.log('mounting wall item component');
        console.log(this.task);
        setTimeout(() => {
            this.drawer = true;
        }, 2000);
    }
}
</script>
