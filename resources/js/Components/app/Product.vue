<template>

    <div class="grid grid-cols-8 gap-4 shadow-lg bg-white rounded-lg mx-2 my-1  px-2 py-1">
        <div class="p-2 col-span-2">
            <img :src="product.image" alt="product image" class="h-20 w-20 md:h-32 md:w-32 rounded-lg">
        </div>
        <div class="p-2 col-span-6">
            <span class="font-bold text-green-600 pr-24">$ {{ product.price }} MXN</span>
                <el-button  size="small"   @click="sendByWhatsapp(product.name, product.description, product.image, product.id)">
                    <svg height="15px" width="15px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 512 512" xml:space="preserve">
                    <path style="fill:#EDEDED;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
                        S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z"/>
                    <path style="fill:#55CD6C;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
                        c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
                        c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z"/>
                    <path style="fill:#FEFEFE;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
                        c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
                        c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
                        c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
                        c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
                        l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945"/>
                    </svg>
                </el-button>

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
                    @change="(product.package) ? product.quantity = product.quantity * product.bundle : product.quantity = this.validDecimalValues(product.quantity, product.bundle) "
                />
            </el-tooltip>

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
        product: Object,
        account: Object
    },
    components: {

    },
    data() {
        return {
            select: '1'
        }
    },
    methods: {
        validDecimalValues(param1, param2){
            const result = param1 / param2;
            if(result % 1 !== 0)
                return 0;
            else
                return result;

        },
        sendByWhatsapp(title, Description, idProduct){
            const phone = '5522539923'
            const message = `https://olistore.mx/app/wa/${idProduct}/${phone}
            Da click en la imagen para finalizar tu pedido de ${title}

            Precio: *$ ${this.product.price} MXN*
            Precio por caja: *$ ${this.product.price * this.product.bundle} MXN*

            ${Description}`

            const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`
            window.open(url, '_blank');
        }
    },
    mounted() {
        console.log(this.account);
        // console.log('mounting wall item component');

    }
}
</script>
