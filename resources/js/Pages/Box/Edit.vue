<script >
import AppLayout        from '@/Layouts/AppLayout.vue';
import PrimaryButton    from '@/Components/PrimaryButton.vue';
import SecondaryButton  from '@/Components/SecondaryButton.vue';
import Footer           from '@/Components/Footer.vue';
import moment           from 'moment';
import FormSection      from '@/Components/FormSection.vue';
import RelatedListNative from '@/Components/RelatedListNative.vue';
import InputLabel        from '@/Components/InputLabel.vue';
import TextInput         from '@/Components/TextInput.vue';

export default{
    components:{
        RelatedListNative,
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        Footer,
        FormSection,
        InputLabel,
        TextInput
    },

    props:{
        customRecord: Object,
        relatedList: Object
    },
    methods:{
        eliminar() {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                this.$inertia.delete(route('box.destroy', this.customRecord.id));
            }
        },
        update(){
            axios.post(route('update.stamdard', this.customRecord.id), this.form).then(response => {
                console.log(response.data)

                setTimeout(() => {
                    window.location.replace(route('box.show', this.customRecord.id));
                }, 1000);

            }).catch(error => {
                console.log(error)
            });
        }
    },
    data(){
        return {
            reportResults8: new Array(),
            form:{
                name:       this.customRecord.name,
                description:this.customRecord.description,
                box_date:   this.customRecord.box_date,
                status:     this.customRecord.status,
                founds_box: this.customRecord.founds_box,
                amount:     this.customRecord.amount,
            },
            date: new Date()
        }
    },
    mounted(){
        let globalResults = [];

        console.log('componente montado', this.customRecord)

    },
    computed: {
        
    }

}
</script>
<template>
    <AppLayout title="Dashboard">
        <template #header>
            Detalle de la caja 
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                <PrimaryButton  @click="update" class="mb-3 ml-3 lg:ml-0"> Actualizar cambios en Caja </PrimaryButton>


                

                <FormSection >

                    <template #title>
                        {{customRecord.name}}
                    </template>
                    <template #description>
                        {{customRecord.box_date}}<br/><br/>
                        {{customRecord.description}}<br/><br/>
                        <b>Creación: </b>{{customRecord.created_at}}<br/><br/>
                        <b>Actualización: </b>{{customRecord.updated_at}}<br/><br/>

                    </template>
                    <template #details>
                        <br/>
                        <div class="col-span-3 ">
                            <InputLabel class="py-2" for="name" value="Caja " />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" autocomplete="name"/>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel class="py-2" for="Description" value="Descripción" />
                            <textarea id="Description" v-model="form.description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="Description" >
                            </textarea>
                        </div>
                        <div class="col-span-3 ">
                            <InputLabel class="py-2" for="name" value="Fecha" />
                            <TextInput id="name" v-model="form.box_date" type="date" class="mt-1 block w-full" autocomplete="name"/>
                        </div>
                        <div class="col-span-3">
                            <InputLabel for="status" value="Estatus" />
                            <el-select id="status" v-model="form.unit_measure" placeholder="Seleccionar status" class="mt-1 block w-full " size="large">
                                <el-option v-for="item in [
                                        { label: 'Caja abierta', value: 'open' },
                                        { label: 'Caja cerrada', value: 'close' },
                                    ]" :key="item.value" :label="item.label" :value="form.status.value"/>
                            </el-select>
                        </div>
                        
                        <div class="col-span-3 ">
                            <InputLabel class="py-2" for="name" value="Fondo de caja" />
                            <TextInput id="name" v-model="form.founds_box" type="number" class="mt-1 block w-full" autocomplete="name"/>
                        </div>

                        <div class="col-span-3 ">
                            <InputLabel class="py-2" for="name" value="Monto en caja registradora" />
                            <TextInput id="name" v-model="form.amount" type="number" class="mt-1 block w-full" autocomplete="name"/>
                        </div>

                        <div class="col-span-3 ">
                            <InputLabel class="py-2" for="name" value="Tienda" />
                            <TextInput id="name" v-model="customRecord.store.name" type="text" disabled="true" class="mt-1 block w-full" autocomplete="name"/>
                        </div>

                        <div class="col-span-3 ">
                            <InputLabel class="py-2" for="name" value="Vendedor" />
                            <TextInput id="name" v-model="customRecord.seller.name" type="text" disabled="true" class="mt-1 block w-full" autocomplete="name"/>
                        </div>

                    </template>
                    <template #relatedlist>
                        <div v-for="(m, o) in relatedList">

                            <RelatedListNative 
                                :customRecordsRelated ="customRecord.box_cut"
                                :title                ="m['title']"
                                :titleModel           ="m['titleModel']"
                                :visibleColumns       ="m['visibleColumns']"
                                :formFields           ="m['formFields']" 
                                :table                ="m['table']"
                                :origin               ="m['origin']"
                                :origin_field         ="m['origin_field']"
                                :currentRecordId      ="m['currentRecordId']"
                                :showNewRecordButton  ="m['showNewRecordButton']"
                                classCard             ="'my-2 -pr-0 w-[470px]'" />
                        </div>
                    </template>
                </FormSection>
            </div>
        </div>

    </AppLayout>
</template>
