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
        destroy(){
            if(confirm('¿Deseas eliminar '+this.selectedProducts+' productos seleccionados?')){
                for (let index = 0; index < this.products.length; index++) {
                    const element = this.products[index];
                    this.$inertia.delete(this.route('products.destroy', element.id));
                }

                setTimeout(()=>{
                    location.reload();
                },(this.products.length*1000));
            }
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

                <div class="lg:basis-1/6 m-1" >
                    <PrimaryButton  @click.prevent="destroy">
                        eliminar {{selectedProducts}} productos seleccionados
                    </PrimaryButton>
                </div>

                
            <!-- </div> -->

    </section>
</template>