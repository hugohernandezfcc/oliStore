<script >
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

export default{
    components:{
         PrimaryButton, SecondaryButton
    },
    props:{
        selectedProducts: Number,
        product: Object,
        products: Array
    },
    methods:{

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
                    <SecondaryButton @click="selectedRecords">
                        + Agregar Inventario
                    </SecondaryButton>
                </div>
                <div class="lg:basis-2/6 m-1" v-if="selectedProducts < 2">
                    <inertia-link :href="route('products.show', product.id)" >
                        <SecondaryButton >
                            ver detalle
                        </SecondaryButton>
                    </inertia-link>
                    
                </div>
                <div class="lg:basis-1/6 m-1">
                    {{selectedProducts}} productos seleccionados
                </div>
            <!-- </div> -->

    </section>
</template>