<script >
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

export default{
    components:{
         PrimaryButton, SecondaryButton
    },
    props:{
        selectedSales: Number,
        sale: Object,
        sales: Array,
        isAdmin: Boolean
    },
    methods:{
        destroy(){
            if(confirm('¿Deseas eliminar '+this.selectedSales+' productos seleccionados?')){
                for (let index = 0; index < this.sales.length; index++) {
                    const element = this.sales[index];
                    // this.$inertia.delete(this.route('products.destroy', element.id));
                }

                setTimeout(()=>{
                    location.reload();
                },(this.sales.length*1000));
            }
        }
    },
    data(){
        return {

        }
    },
    mounted(){
        console.log(this.isAdmin);

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
                    <PrimaryButton  @click.prevent="destroy" v-if="isAdmin">
                        eliminar {{selectedProducts}} venta 
                    </PrimaryButton>
                </div>

                
            <!-- </div> -->

    </section>
</template>