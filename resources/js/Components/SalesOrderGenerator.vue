<template>
    
    <el-card  shadow="never" v-loading="loading" element-loading-text="Cargando elementos ...">
        <PrimaryButton v-if="somethingChange" class="mr-1 " @click="saveSalesOrder"> Guardar </PrimaryButton>
        <SecondaryButton @click="addForm" class="mb-3 ml-3 lg:ml-0" v-if="!somethingChange">Agregar </SecondaryButton>

        <div v-for="(form, index) in forms" :key="index" class="mb-2 border p-1 rounded mt-1">
        <el-row>
            <el-col :span="10" class="mx-1">
                <InputLabel for="status" value="Estado" />
                <el-select id="status" v-model="form.status" placeholder="Seleccionar  ..." clearable>
                    <el-option v-for="item in [
                        {name: 'Abierta',       key: 'Abierta'},
                        {name: 'En progreso',   key: 'En progreso'},
                        {name: 'Cerrada entregada',       key: 'Cerrada entregada'},
                        {name: 'Cancelada',     key: 'Cancelada'}
                    ]" :key="item.id" :label="item.name" :value="item.key"/>
                </el-select>
            </el-col>
            <el-col :span="12" class="mx-1">
                <InputLabel for="note" value=" Nota" />
                <el-input id="note" v-model="form.note" placeholder="Nota generada extraordinaria" />
            </el-col>
            
            
        </el-row>

        
    </div>

    </el-card>
    <br/>
    <el-table :data="readerOrders" style="width: 100%;" border >
        <el-table-column align="left" width="70" fixed="left">
            <template #default="scope">
                <inertia-link :href="route('salesorder.show', scope.row.id)" >
                    <el-button size="small" color="#dc2626"> 
                        <svg class="h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M512 160c320 0 512 352 512 352S832 864 512 864 0 512 0 512s192-352 512-352m0 64c-225.28 0-384.128 208.064-436.8 288 52.608 79.872 211.456 288 436.8 288 225.28 0 384.128-208.064 436.8-288-52.608-79.872-211.456-288-436.8-288zm0 64a224 224 0 1 1 0 448 224 224 0 0 1 0-448m0 64a160.192 160.192 0 0 0-160 160c0 88.192 71.744 160 160 160s160-71.808 160-160-71.744-160-160-160"></path></svg>
                    </el-button>
                </inertia-link>
            </template>
        </el-table-column>

        <el-table-column prop="status" label="Estado" width="80" />
        <el-table-column prop="note" label="Nota" />
    </el-table>
    <!-- <pre class="mt-4">{{ forms }}</pre> -->
</template>

<script>
    import {Delete} from '@element-plus/icons-vue';
    import { ElLoading } from 'element-plus';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';



    export default {
        name: 'QuickEntries',
        components: {
            PrimaryButton,
            SecondaryButton,
            ElLoading,
            InputError,
            InputLabel,
            TextInput,
            Delete
        },
        props: {
            parentRecord: Object,
            salesOrders: Array
        },
        data() {
            return {
                forms: [
                    
                ],
                readerOrders: [],
                loading: false,
                somethingChange: false
            }
        },
        methods:{
            addForm() {
                this.somethingChange = true;
                
                this.forms.push({ 
                    note: '',
                    status: 'open',
                    account_id: this.parentRecord.id,
                 })
                
            }, 
           
            
            saveSalesOrder(){

                this.loading = true;
                console.log('saving sales order', this.forms.at(-1))
                axios.post( route('salesorder.store'), this.forms.at(-1)).then(response => {
                    this.addOneMoreOrder(response.data)
                    this.loading = false;
                    this.somethingChange = false;
                }).catch(error => {
                    console.log(error)
                    this.somethingChange = false;
                })
                
            },
           
            addReaderOrders(){
                this.readerOrders = this.salesOrders;
            },
            addOneMoreOrder(order){
                this.readerOrders.push(order);
            }
        },
        mounted() {

            console.log('mounting wall component', this.parentRecord);
            console.log('mounting wall component', this.salesOrders);
            this.addReaderOrders();
        },
    }
</script>