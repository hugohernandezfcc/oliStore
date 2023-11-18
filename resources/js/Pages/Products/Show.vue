<script >
import AppLayout from '@/Layouts/AppLayout.vue';
 
export default{
    components:{
        AppLayout
    },
    props:{
        product: Object

    },

    mounted(){

    }
}



</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                OliStore
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px0">
                            <h3 class="text-lg text-gray-900"> Detalle de un producto </h3>
                            <br/>
                            <table>
                                <tr>
                                    <td><b>ID DB :</b></td>
                                    <td>{{product.id}}</td>
                                </tr>
                                <tr>
                                    <td><b>NOMBRE PRODUCTO:</b></td>
                                    <td>{{product.name}}</td>
                                </tr>
                                <tr>
                                    <td><b>CODIGO DE BARRAS:</b></td>
                                    <td>{{product.folio}}</td>
                                </tr>
                                <tr>
                                    <td><b>DESCRIPCION:</b></td>
                                    <td>{{product.Description}}</td>
                                </tr>
                                <tr>
                                    <td><b>UNIDAD DE MEDIDA:</b></td>
                                    <td>{{product.unit_measure}}</td>
                                </tr>
                                <tr>
                                    <td><b>PRECIO DE LISTA:</b></td>
                                    <td>$ {{product.price_list}}</td>
                                </tr>
                                <tr>
                                    <td><b>PRECIO CLIENTE:</b></td>
                                    <td>$ {{product.price_customer}}</td>
                                </tr>
                                <tr>
                                    <td><b>PORCENTAJE (GANANCIA):</b></td>
                                    <td>{{product.profit_percentage}} %</td>
                                </tr>
                                <tr>
                                    <td><b>FECHA DE CADUCIDAD:</b></td>
                                    <td>{{product.expiry_date}}</td>
                                </tr>
                                <tr>
                                    <td><b>CREADO POR:</b></td>
                                    <td>{{product.created_by_id}}</td>
                                </tr>
                                <tr>
                                    <td><b>ULTIMA ACTUALIZACIÓN:</b></td>
                                    <td>{{product.edited_by_id}}</td>
                                </tr>
                                <tr>
                                    <td ><b>Código de barras:  </b></td>
                                    <td class="p-2">
                                        <div v-html="product.bar_code" /> 
                                        {{product.folio}}
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="shadow bg-white md:rounded-md p-4">
                            <h2>{{product.name}} // {{product.Description}}</h2>
                            <hr class="my-6"/>
                            <table>
                                <tr>
                                    <td ><b>Código de barras:  </b></td>
                                    <td class="p-2">
                                        <div v-html="product.bar_code" /> 
                                        {{product.folio}}
                                    </td>
                                </tr>
                            </table>

                            <table>
                                <tr>
                                    <td><b>Stock (Inventario):</b></td>
                                    <td>{{product.id}}</td>
                                </tr>
                            </table>
                            
                            <hr class="my-6"/>
                            <inertia-link :href="route('products.index')">
                                Regresar
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
