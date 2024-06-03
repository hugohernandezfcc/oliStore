<template>
    <el-button class="w-full" type="danger" @click="drawer = true" >
        Corte de caja
    </el-button>

    
    <el-drawer v-model="drawer" :direction="direction"  :before-close="handleClose" :size="drawerWidth">
        <template #header>
            <span class="text-xl text-red-600 ">
                <b>Corte de caja</b>
            </span>
        </template>
        <template #default>
            <div class="shadow bg-slate-200 md:rounded-md p-1">      
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="products" value="Concepto" />
                    <TextInput
                        id="products"
                        ref="products"
                        v-model="form.concept"
                        type="text"
                        class="mt-1 block w-full text-base text-green-950"
                        required
                        autocomplete="name"

                    />
                </div>
                <el-select @change="getParamsToCalculate" v-model="storeId" placeholder="Select" size="large" id="storeId" class="mt-1 w-full " >
                        <el-option v-for="item in stores" :key="item.id" :label="item.name" :value="item.id" />
                </el-select>
            </div>
            <div v-if="showForms">
                <span class="text-xl text-red-600 ">
                    <b>Efectivo</b>
                </span>
                <div class="flex flex-row flex-wrap bg-slate-200 md:rounded-md p-1">
                    <div class="basis-1/3 p-1" >
                        <InputLabel for="inbound_amount" value="Contado" />
                        <TextInput
                            id="inbound_amount"
                            ref="inbound_amount"
                            v-model="form.cash_counted"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full text-base text-yellow-700"
                            v-on:keyup="calculateExchange"
                        />
                    </div>
                    <div class="basis-1/3 p-1" >
                        <InputLabel for="outbound_amount" value="Calculado" />
                        <TextInput
                            id="outbound_amount"
                            ref="outbound_amount"
                            v-model="form.cash_calculated"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full text-base text-black"
                            disabled="true"

                        />
                    </div>
                    <div class="basis-1/3 p-1 " >
                        <InputLabel for="inbound_amount" value="Diferencia" />
                        <TextInput
                            id="inbound_amount"
                            ref="inbound_amount"
                            v-model="form.cash_diff"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full text-base text-green-700"
                            disabled="true"
                        />
                    </div>
                </div>
                <span class="text-xl text-red-600 mt-1">
                    <b>Tarjeta</b>
                </span>
                <div class="flex flex-row flex-wrap bg-slate-200 md:rounded-md p-1">
                    <div class="basis-1/3 p-1" >
                        <InputLabel for="inbound_amount" value="Contado" />
                        <TextInput
                            id="inbound_amount"
                            ref="inbound_amount"
                            v-model="form.card_counted"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full text-base text-yellow-70"
                            v-on:keyup="calculateExchange"
                        />
                    </div>
                    <div class="basis-1/3 p-1" >
                        <InputLabel for="inbound_amount" value="Calculado" />
                        <TextInput
                            id="inbound_amount"
                            ref="inbound_amount"
                            v-model="form.card_calculated"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full text-base text-black"
                            disabled="true"

                        />
                    </div>
                    <div class="basis-1/3 p-1 " >
                        <InputLabel for="inbound_amount" value="Diferencia" />
                        <TextInput
                            id="inbound_amount"
                            ref="inbound_amount"
                            v-model="form.card_diff"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full text-base text-green-700"
                            disabled="true"
                        />
                    </div>
                </div>
                <br/>
                <el-descriptions title="Detalle del retiro" :column="1" border>
                    <el-descriptions-item label="RETIRO ORIGINAL" label-align="right" align="left" label-class-name="my-label" class-name="my-content" width="150px">
                        <span class="text-green-600 text-xl">$ {{details.retiro}} MXN</span>
                    </el-descriptions-item>
                    <el-descriptions-item label="GASTOS POR RETIRO (CR)" label-align="right" align="left" label-class-name="my-label" class-name="my-content" width="150px">
                        <span class="text-red-600 text-base">$ {{details.gastosRetiro}} MXN</span>
                    </el-descriptions-item>
                    <el-descriptions-item label="MONTO LIBERADO (CN)" label-align="right" align="left" label-class-name="my-label" class-name="my-content" width="150px">
                        <span class="text-yellow-600 text-base">$ {{details.disponible}} MXN</span>
                    </el-descriptions-item>
                </el-descriptions>
            </div>
            

            
            
        </template>
        <template #footer >
            <el-button class="w-full mt-2" type="danger" link @click="handleClose">Cancelar</el-button>
            <br/>
            <br/>
            <el-button class="w-full mt-2" type="danger" @click="confirmClick">Cortar caja</el-button>
            
        </template>
    </el-drawer>

</template>
  
<script>  
    import axios from 'axios';
    import { ElNotification } from 'element-plus';
    import { ElLoading } from 'element-plus';
    import { ElMessageBox } from 'element-plus';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    export default {
        name: 'BarChart',
        components: { ElNotification, ElLoading, ElMessageBox,InputLabel, TextInput },
        props:{
            type: String,
            stores: Array
        },
        data() {
      
            return { 
                drawer: false,
                drawerWidth: 80,
                direction: 'btt',
                tienda: null,
                form : {
                    cash_counted: 0,
                    cash_calculated: null,
                    cash_diff: null,
                    card_counted: 0,
                    card_calculated: null,
                    card_diff: null,
                    concept: '',
                    store: null
                },
                details:{
                    retiro: 0,
                    gastosRetiro: 0,
                    disponible: 0,
                },
                storeId:null,
                showForms: false,
                porcentange: 0
            }
        },

        
        methods:{
            openDrawer(){
                this.drawer = true;
                this.form.cash_counted = 0;
                this.form.cash_calculated = null;
                this.form.cash_diff = null;
                this.form.card_counted = 0;
                this.form.card_calculated = null;
                this.form.card_diff = null;
                this.form.concept =  '';
                this.form.store = null;
                this.storeId = null;
            },
            calculateExchange(){
                this.form.cash_diff = this.form.cash_calculated - this.form.cash_counted;
                this.form.card_diff = this.form.card_calculated - this.form.card_counted;
                
                this.details.retiro = parseFloat(this.form.cash_counted) + parseFloat(this.form.card_counted);
                console.log(this.details.retiro);
                console.log(this.porcentange);
                console.log((this.details.retiro*this.porcentange)/100);
                this.details.gastosRetiro = Math.ceil((this.details.retiro*this.porcentange)/100);
                this.details.disponible = Math.ceil(this.details.retiro + this.details.gastosRetiro);
            },
            validForm(){
                const fields = [
                    'cash_counted',
                    'cash_calculated',
                    'cash_diff',
                    'card_counted',
                    'card_calculated',
                    'card_diff',
                    'concept',
                    'store'
                ];

                let fieldLabels = {
                    'cash_counted': 'Efectivo contado',
                    'cash_calculated': 'Efectivo calculado',
                    'cash_diff': 'Diferencia en efectivo',
                    'card_counted': 'Tarjeta contado',
                    'card_calculated': 'Tarjeta calculado',
                    'card_diff': 'Diferencia en tarjeta',
                    'concept': 'Concepto',
                    'store': 'tienda'
                };

                for (let field of fields) {
                    if (this.form[field] === null || this.form[field] === '') {
                        return {
                            isValid: false,
                            errorMessage: 'Verifica elvalor de ' + fieldLabels[field]
                        };
                    }
                }

                return {
                    isValid: true,
                    errorMessage: 'ok'
                };
            },
            handleClose(){

                ElMessageBox.confirm('¿Seguro que deseas cancelar el corte?')
                .then(() => {
                    this.drawer = false;
                })
                .catch(() => {
                    // catch error
                })

            },
            getParamsToCalculate(){
                this.showForms = false;
                this.form.store = this.storeId;
                axios.get(route('boxcut.show', this.storeId)).then(response => {
                    console.log(response.data);
                    this.form.cash_calculated = response.data.cashCalculatedAmount;
                    this.form.card_calculated = response.data.debitCalculatedAmount;
                    this.porcentange = response.data.calculatedDescounts;
                    this.showForms = true;

                    
                }).catch(error => {
                    console.log(error);
                });


                console.log(this.storeId);
            },
            confirmClick(){



        


                let localValidation = this.validForm();
                if(localValidation.isValid){

                    let submitBoxCut = {
                        store_id: this.storeId,
                        cash_box: parseFloat(this.form.cash_calculated)-parseFloat(this.form.cash_diff),
                        cash_calculated: parseFloat(this.form.cash_calculated),
                        cash_diff: parseFloat(this.form.cash_diff),
                        cash_withdrawal: parseFloat(this.form.cash_counted),
                        name: this.form.concept,
                        withdrawal_expenses: this.details.gastosRetiro,
                        amount_released:    this.details.disponible,
                        card_box:           parseFloat(this.form.card_calculated)-parseFloat(this.form.card_diff),   
                        card_calculated:    parseFloat(this.form.card_calculated),
                        card_diff:          parseFloat(this.form.card_diff),
                        card_withdrawal:    parseFloat(this.form.card_counted),
                        calculated: parseFloat(this.form.cash_calculated)+parseFloat(this.form.card_calculated),    
                        
                        diff: parseFloat(this.form.cash_diff)+parseFloat(this.form.card_diff),
                        withdrawal:parseFloat(this.form.cash_counted)+parseFloat(this.form.card_counted)



                        // cash_calculated: parseFloat(this.form.cash_calculated)+parseFloat(this.form.card_calculated),
                        // cash_box: parseFloat(this.form.cash_diff)+parseFloat(this.form.card_diff),
                    };

                    console.log(submitBoxCut);

                    axios.post(route('box.cut.save'), submitBoxCut).then(response => {
                        console.log(response.data);
                        this.drawer = false;
                        ElNotification.success({
                            title: 'success',
                            message: response.data.message,
                            offset: 100,
                        });
                        this.form.cash_counted = 0;
                        this.form.cash_calculated = null;
                        this.form.cash_diff = null;
                        this.form.card_counted = 0;
                        this.form.card_calculated = null;
                        this.form.card_diff = null;
                        this.form.concept =  '';
                        this.form.store = null;
                        this.storeId = null;
                    }).catch(error => {
                        console.log(error);
                    });

                }else{
                    ElNotification.warning({
                        title: 'warning',
                        message: localValidation.errorMessage,
                        offset: 100,
                    });

                }
                console.log(this.form);
            }
        },
        beforeMount() {
            this.direction      = (window.innerWidth <= 650) ? 'btt' : 'rtl' ;
            this.drawerWidth    = (window.innerWidth <= 650) ? '90%' : '50%' ;



            //axios.get('/api/data') .then(response => { }).catch(error => { });
        }
    }
</script>