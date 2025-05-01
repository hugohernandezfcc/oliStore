<template>
    
    <el-card  shadow="never" v-loading="loading" element-loading-text="Cargando elementos ...">
        <PrimaryButton v-if="somethingChange" class="mr-1 " @click="savePriceBookEntry"> Guardar lista de precios </PrimaryButton>
        <SecondaryButton @click="addForm" class="mb-3 ml-3 lg:ml-0">Agregar </SecondaryButton>
        <div v-for="(form, index) in forms"
        :key="index"
        class="mb-2 border p-1 rounded"
        
        >
        <el-row>
            <el-col :span="5" class="mx-1">
                <InputLabel for="pricebook" value="L. Precio" />
                <el-select id="pricebook" v-model="form.pricebook" placeholder="Seleccionar  ..." @change="assigningPorcentage(form.pricebook)" clearable>
                    <el-option v-for="item in pricebooks" :key="item.id" :label="item.name" :value="item.id"/>
                </el-select>
            </el-col>
            <el-col :span="5" class="mx-1">
                <InputLabel for="cost" value=" Costo" />
                <el-input v-model="form.cost" placeholder="Costo interno" clearable  v-on:keyup="() =>{
                    form.price = calculatePrice(form.cost);
                }"  :formatter="(value) => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')" :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" />
                <!-- <TextInput id="cost" v-model="form.cost" type="text" class="mt-1 block w-full" autocomplete="cost"/> -->
            </el-col>
            <el-col :span="5" class="mx-1">
                <InputLabel for="price" value=" P. Cliente" />
                <el-input v-model="form.price" placeholder="Precio cliente" clearable  :formatter="(value) => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')" :parser="(value) => value.replace(/\$\s?|(,*)/g, '')"/>

            </el-col>
            <el-col :span="3" class="mx-1">
                <center>
                <InputLabel for="isActive" value=" Activar " />
                    <el-switch id="isActive" class="mt-1" v-model="form.isActive" />
                </center>
            </el-col>
            <el-col :span="2" >

                <center class="mt-5">
                    <el-button type="danger" circle @click="deletePriceBookentry(form.id)" v-if="form.id != null">
                        <el-icon><Delete /></el-icon>
                    </el-button>
                </center>
            </el-col>
        </el-row>

        
    </div>
    </el-card>
    
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
            currentPriceBookEntries: Array

        },
        data() {
            return {
                forms: [
                     // Initial form row
                ],
                pricebooks: [],
                loading: false,
                pricebookProfit: 0,
                somethingChange: false
            }
        },
        methods:{
            addForm() {
                for(let i = 0; i < this.forms.length; i++) 
                    this.forms[i].isActive = false;
                

                this.forms.push({ pricebook: '', cost: null, price: null, isActive: true })
                this.somethingChange = true;
            },
            getPriceBooks(){
                this.loading = true;
                axios.get( route('pricebooks.index') ).then(response => {
                    console.log(response.data)
                    this.pricebooks = response.data;
                    this.loading = false;
                }).catch(error => {
                    console.log(error)
                    this.loading = false;
                })
            },
            assigningPorcentage(valueId){
                console.log('valueId', valueId);
                for (let i = 0; i < this.pricebooks.length; i++) {
                    if (this.pricebooks[i].id == valueId) {
                        this.pricebookProfit = this.pricebooks[i].profit;
                        break;
                    }
                }
                this.somethingChange = true;
                console.log('this.pricebookProfit', this.pricebookProfit);
            },
            calculatePrice(value){
                console.log('value', value);
                let opt1 = value * this.pricebookProfit;
                opt1 = opt1 / 100;
                return parseFloat(value) + parseFloat(opt1);
            },
            savePriceBookEntry(){

                this.loading = true;
                let smoveOn = true;
                for(let i = 0; i < this.forms.length; i++) {

                    if(this.forms[i].pricebook != null && this.forms[i].pricebook != '' && this.forms[i].cost != null && this.forms[i].cost != '' && this.forms[i].price != null && this.forms[i].price != '') {
                        smoveOn = true;
                        this.forms[i].cost = parseFloat(this.forms[i].cost)
                        this.forms[i].price = parseFloat(this.forms[i].price)
                    } else {
                        smoveOn = false;
                    }

                }

                
                if(smoveOn) {
                    axios.post( route('pricebooksentry.storefromb2b'), {
                        pricebookentries: this.forms,
                        pricebookProfit: this.pricebookProfit,
                        parentRecordId: this.parentRecord.id
                    }).then(response => {
                        console.log(response.data)
                        this.loading = false;
                        this.somethingChange = false;
                    }).catch(error => {
                        console.log(error)
                    })
                    
                }else {
                    this.loading = false;
                    alert('Hay campos vacios, por favor verifique');
                }
                
            },
            deletePriceBookentry(id){
                this.loading = true;
                for(let i = 0; i < this.forms.length; i++) {
                    if(this.forms[i].id == id) {
                        axios.delete( route('pricebooksentry.destroy', id) ).then(response => {
                            console.log(response.data)
                            this.forms.splice(i, 1);
                            this.loading = false;
                        }).catch(error => {
                            console.log(error)
                            this.loading = false;
                        })
                        this.somethingChange = false;

                        break;
                    }
                }

            }
        },
        mounted() {
            console.log('mounting wall component');
            this.getPriceBooks();
            for(let i = 0; i < this.currentPriceBookEntries.length; i++) {
                this.forms.push({ pricebook: this.currentPriceBookEntries[i].pricebook_id, cost: this.currentPriceBookEntries[i].cost, price: this.currentPriceBookEntries[i].price, isActive: this.currentPriceBookEntries[i].is_active, id: this.currentPriceBookEntries[i].id });
            }
        },
    }
</script>