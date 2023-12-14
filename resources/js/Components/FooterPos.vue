<script >
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SecondaryButtonPay from '@/Components/SecondaryButtonPay.vue';
import axios from 'axios';
import { ElNotification } from 'element-plus';

export default{
    components:{
         PrimaryButton, SecondaryButtonPay, axios
    },
    emits: ["destroy", "clearEverything"],
    props:{
        selectedProducts: Number,
        sale: Object,
        products: Array,
        total: Number
        
    },
    methods:{
        destroyLocal(){
            this.methodThatForcesUpdate();
            if(confirm('¿Deseas eliminar '+this.selectedProducts+' productos seleccionados?')){
                this.$emit("destroy");
            }
        },
        pay(){
            console.log(this.$parent.form);
            console.log(this.sale)
            console.log(this.products)
            if(parseFloat(this.$parent.form.inbound_amount) >= this.total){
                
                axios.post(this.route('sales.store'), this.sale, {
                    headers: {
                        scheme: 'https'
                    }
                }).then((res) => {
                    ElNotification.success({
                        title: 'Success',
                        message: 'Compra guardada',
                        offset: 100,
                    })
                    // setTimeout(() => {
                    //     location.reload();
                    // }, 1000);
                    this.$emit("clearEverything");

                }).catch((error) => {
                    console.log(error);
                });
                
            }else{
                ElNotification.warning({
                    title: 'warning',
                    message: 'Especifica el dinero recibido',
                    offset: 100,
                });
            }
            
        },
        methodThatForcesUpdate() {
            // ...
            this.$forceUpdate();  // Notice we have to use a $ here
            // ...
        }
    },
    data(){
        return {

        }
    },
    mounted(){

    }
}
</script>
<template>
    <section class="md:h-[99px] bg-white w-full flex flex-wrap-reverse items-center justify-center fixed bottom-0 left-0 border-t border-b text-black">

            <!-- <div class="flex flex-wrap-reverse"> -->
                <div class="lg:basis-2/6 m-1">
                    <h1 class="underline text-green-600 text-2xl decoration-double">$ {{ total }} MXN -  Total</h1>
                </div>
                <div class="lg:basis-2/6 m-1" v-if="total > 0">

                    <SecondaryButtonPay @click.prevent="pay" >
                        Finalizar Venta
                    </SecondaryButtonPay>

                    
                </div>

                <div class="lg:basis-1/6 m-1" v-if="selectedProducts > 0">
                    <PrimaryButton  @click.prevent="destroyLocal">
                        eliminar {{selectedProducts}} productos seleccionados
                    </PrimaryButton>
                </div>

                
            <!-- </div> -->

    </section>
</template>