<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ElMessage } from 'element-plus';
import LookupField from  '@/Components/LookupField.vue';
import { ElLoading } from 'element-plus';
import { CirclePlusFilled, CloseBold, CaretTop, CaretBottom } from '@element-plus/icons-vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';


export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        ElMessage,
        LookupField,
        ElLoading,
        CirclePlusFilled,
        CloseBold,
        CaretTop,
        CaretBottom,
        InputLabel,
        TextInput
    },
    props:{
        users: Array,
        stores: Array
    },
    data(){
        return {
            store: null,
            search: '',
            input2: 0,
            products: [],
            localProducts: [],
            regexp: /android|iPhone/i,
            details : navigator.userAgent,
            mobileButtonLabel: 'Agregar productos',
            dialogVisible: false,
            productsSelected: [],
            counterSearch: 0,
            loading: false,
            temporalSearch: [],
            providerName:   '',
            fromValidation: {
                isValid: true,
                errorMessage: 'ok'
            },
            form:{
                who_issued_ticket: '',
                provider_id: null,
                noTicket: '',
                date_time_issued: '',
                total: '',
                products: []
            }
        }
    },
    methods:{
        openInNewTab() {
            const url = this.route('products.create');
            window.open(url, '_blank');
        },
        productsList(){
            axios.get(route('products.list')).then(response => {
                for (let index = 0; index < response.data.length; index++) {
                    response.data[index].quantity = null;
                    response.data[index].original_price_list = response.data[index].price_list;
                }
                
                this.products = response.data;
            }).catch(error => {
                console.log(error)
            });
        },
        changeCheckbox(event, product){

            console.log(event)
            console.log(product)
            this.visible = false;
            if(event){
                
                for (let index = 0; index < this.products.length; index++) {
                    const element = this.products[index];
                    if(element.stores == undefined){
                        element.stores = [];
                        element.distributed = false;
                        element.visible = false;
                        element.priceByProduct = element.quantity * parseFloat(element.price_list);
                    }

                    if(element.folio == product.target.defaultValue){
                        for (let o = 0; o < this.stores.length; o++) {
                            element.stores.push(
                                {
                                    store_id: this.stores[o].id,
                                    store_name: this.stores[o].name,
                                    quantity: 0,
                                }
                            );
                        }

                        this.productSelected(element);
                        break;
                    }
                }
                
                
            }else{
                for (let index = 0; index < this.products.length; index++) {
                    const element = this.products[index];
                    if(element.folio == product.target.defaultValue){
                        this.removeSelectedProduct(element);
                        break;
                    }
                }
            }
            console.log(this.productsSelected);
            
        },
        productSelected(id){
            console.log(id);
            this.productsSelected.push(id);
        },
        assignTicketItem(producto, distribution){
            if(producto.quantity != distribution){
                ElMessage({
                    showClose: true,
                    message: 'La cantidad de productos seleccionados no coincide con la cantidad a distribuir.',
                    type: 'error',
                })

                return;
            }else{

                if(producto.distributed == false){
                    producto.distributed = true;
                    producto.visible = false;

                    for (let index = 0; index < producto.stores.length; index++) {
                        console.log(producto.stores[index]);
                        this.form.products.push({
                            product_id: producto.id,
                            quantity:   producto.stores[index].quantity,
                            price_list: producto.priceByProduct/producto.quantity,
                            stores:     producto.stores[index].store_id
                        });
                    }
                }else{
                    producto.distributed = true;
                    producto.visible = false;
                    this.form.products = this.form.products.filter(product => product.product_id !== producto.id);

                    for (let index = 0; index < producto.stores.length; index++) {
                        console.log(producto.stores[index]);
                        this.form.products.push({
                            product_id: producto.id,
                            quantity:   producto.stores[index].quantity,
                            price_list: producto.price_list,
                            stores:     producto.stores[index].store_id
                        });
                    }

                }

            }
            console.log('ticket', this.form);
            
        },

        saveTicket(){

            this.fromValidation = this.validateForm();

            if(this.fromValidation.isValid){
                axios.post(route('tickets2.store'), this.form).then((res) => {
                    console.log(res);
                    ElMessage({
                        showClose: true,
                        message: 'Ticket guardado correctamente.',
                        type: 'success',
                    })
                }).catch((error) => {
                    console.log(error);
                    ElMessage({
                        showClose: true,
                        message: 'Error al guardar el ticket.',
                        type: 'error',
                    })
                });
            }else{
                
            }
            
        },
        validateForm(){

            this.fromValidation = {
                isValid: true,
                errorMessage: 'ok'
            }

            let fields = [
                'noTicket',
                'who_issued_ticket',
                'provider_id',
                'date_time_issued',
                'total'
            ];

            let fieldLabels = {
                'noTicket': 'No. Ticket',
                'who_issued_ticket': '¿Quien lo compro?',
                'provider_id': 'Proveedor',
                'date_time_issued': 'Fecha de compra',
                'total': 'Total (MXN)'
            };

            for (let field of fields) {
                if (this.form[field] === null || this.form[field] === '') {
                    return {
                        isValid: false,
                        errorMessage: 'Verifica el valor de ' + fieldLabels[field]
                    };
                }
            }

            return {
                isValid: true,
                errorMessage: 'ok'
            };
        },
        removeSelectedProduct(id){
            this.productsSelected = this.productsSelected.filter(item => item !== id);
        },
        deleteDirectly(id){
            for (let index = 0; index < this.products.length; index++) {
                const element = this.products[index];
                if(element.folio == id)
                    this.removeSelectedProduct(element);
            }
        }
    },
    mounted(){
        console.log('this.users', this.users);
        console.log('this.stores', this.stores);
        console.log(navigator.userAgent);
        this.productsList();
        console.log('this.tickets', this.regexp.test(this.details));
    },
    computed: {
        filterTableData() {
            
            let validateComidin = (this.search.indexOf('*') > -1) ? this.search.split('*')[0] : this.search;

            let beforeReturn = Object.values(this.products).filter(
                (data) =>
                !validateComidin || JSON.stringify(data).toLowerCase().includes(validateComidin.toLowerCase() )
            );
            
            console.log('filterTableData', beforeReturn.length)

            let limit = (this.search.split('*').length == 2) ? 0 : beforeReturn.length;
            console.log('limit', limit);
            if(limit == 0 && this.search.length > 0)
                {
                    this.loading = true;

                    axios.post(route('products.list.search'), {search: validateComidin}).then((res) => {
                        console.log(res);
                        for (let index = 0; index < res.data.length; index++) {
                            res.data[index].quantity = null;
                            res.data[index].original_price_list = res.data[index].price_list;
                        }

                        this.products = [
                            ...res.data,
                            ...this.products
                        ];

                        this.counterSearch++;
                        this.search = this.search.split('*')[0];

                        // if(this.counterSearch == 2 && limit == 0 && this.search != ''){
                        //     this.search = '';
                            // ElMessage({
                            //     showClose: true,
                            //     message: 'No se encontraron resultados, busca de nuevo.',
                            //     type: 'info',
                            // })
                        // }

                    }).catch((error) => { console.log(error); });

                }
        
            setTimeout(() => {
                this.loading = false;
            }, 3000);
            
            console.log('beforeReturn',  beforeReturn);
            // console.log('toReturn',  toReturn);
            
            // beforeReturn = Object.values(toReturn);
            return beforeReturn;
            
        }
    }
}
</script>

<template>
    <AppLayout title="Productos">
        <template #header>
            <h3 class="text-lg text-gray-900"> tesd</h3>
            <p class="text-sm text-gray">Catalogo de tickets registrados </p>
        </template>
        
        <el-dialog
            v-model="dialogVisible"
            title="Lista de Productos"
            :width="'100%'"
            class="font-mono  text-sm font-bold leading-6 bg-stripes-indigo rounded "
            center
        >
        
            <div class="py-2 basis-1/4 rounded-lg bg-gray-500 shadow-lg -mx-4">
                <el-input v-model="search" type="text"  placeholder="buscar producto" class="p-2"/>
                <div class="py-1" v-for="product in filterTableData" >

                    <div class="mx-2 bg-white rounded-t-lg text-black  ">
                        <table>
                            <tr>
                                <td colspan="2" class="pl-2">
                                    {{ (product.Description == 'EXPRESS CREATION') ? product.name : product.Description }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1">
                                    <el-checkbox :disabled="(product.quantity > 0 && product.quantity != null) ? false:true" :true-value="product.id" :label="product.folio" size="large" border :key="product.id"  @change="changeCheckbox" />
                                </td>
                                <td class="  text-base p-2 ">
                                P.C.  $ {{product.price_customer}} MXN 
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="mx-2  bg-red-600 rounded-b-lg">
                        <div class="flex">
                            <div class="p-2 w-32">
                                <el-input v-model="product.quantity" type="number"  placeholder="Cantidad"/>
                            </div>
                            <div class=" text-white  text-sm p-2 ">
                                <el-input v-model="product.price_list" 
                                    :formatter="(value) => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                    :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" placeholder="Please Input"/>
                            </div>
                        </div>
                        
                    </div>

                </div>
            </div>
            <template #footer>
            <div class="dialog-footer">
                <el-button @click="dialogVisible = false">Cancel</el-button>
                <el-button type="primary" @click="centerDialogVisible = false">
                Confirm
                </el-button>
            </div>
            </template>
        </el-dialog>
        

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-2">
            <el-row :gutter="24" class="font-mono text-white text-sm font-bold leading-6 bg-stripes-indigo rounded">
                <el-col :lg="8" :md="10" :sm="10" :xs="24">

                    <div class="p-2 basis-1/4 rounded-t-lg flex items-center justify-center bg-gray-500 shadow-lg">
                        <PrimaryButton  v-if="!regexp.test(details)" class=" ml-3 lg:ml-0 transition transform hover:scale-105 focus:scale-95 active:scale-90" @click="openInNewTab"> 
                            <el-icon><CirclePlusFilled /></el-icon>
                        </PrimaryButton>&nbsp;
                        <el-input v-model="search" type="text" placeholder="Please Input" v-if="!regexp.test(details)"/>
                        <PrimaryButton v-if="regexp.test(details)" class="w-full -ml-2 transition transform hover:scale-105 focus:scale-95 active:scale-90" type="primary" @click="dialogVisible = true">
                            {{ mobileButtonLabel }}
                        </PrimaryButton>
                    </div>
                    <div class="p-2 basis-1/4 rounded-b-lg bg-gray-500 shadow-lg" v-if="!regexp.test(details)" v-loading="loading">

                        <div class="py-1" v-for="product in filterTableData" >

                            <div class="sm:mr-3 md:mr-3 bg-white rounded-t-lg text-black  ">
                                <table>
                                    <tr>
                                        <td colspan="2" class="pl-2">

                                            <el-popover
                                                placement="top-start"
                                                :title="product.name"
                                                :width="200"
                                                trigger="hover"
                                                :content="product.Description"
                                            >
                                                <template #reference>
                                                    {{ (product.Description == 'EXPRESS CREATION') ? product.name : product.Description }}
                                                </template>
                                            </el-popover>

                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">
                                            <el-checkbox :disabled="(product.quantity > 0 && product.quantity != null) ? false:true" :true-value="product.id" :label="product.folio" size="large" border :key="product.id"  @change="changeCheckbox" />
                                        </td>
                                        <td class="  text-lg p-2 ">
                                          P.C.  ${{product.price_customer}}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="sm:mr-3 md:mr-3  bg-red-600 rounded-b-lg">
                                <div class="flex">
                                    <div class="p-2">
                                        <el-input-number v-model="product.quantity" :min="0" :precision="(product.take_portion) ? 3 : 0 " :step="(product.take_portion) ? 0.001 : 1 "/>
                                    </div>
                                    <div class="  text-lg p-2 ">
                                        <el-input v-model="product.price_list" 
                                                :formatter="(value) => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                                :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" placeholder="Please Input"/>

                                    </div>
                                </div>
                                
                            </div>

                        </div>
                    </div>
                </el-col>
                <el-col :lg="16" :md="14" :sm="14" :xs="24">
                    
                    
                    <div class=" basis-3/4 rounded-lg flex flex-wrap  text-black p-3 bg-white shadow-lg">
                        <el-alert :title="fromValidation.errorMessage" type="error" effect="dark" v-if="!fromValidation.isValid" :closable="false"/>
                        <div class="p-1">
                            <InputLabel class="py-2" for="noTicket" value="No. Ticket" />
                            <el-input id="noTicket" v-model="form.noTicket" placeholder="Please Input" size="large"/>

                        </div>
                        <div class="p-1">
                            <InputLabel class="py-2" for="users" value="¿Quien lo compro?" />
                            <el-select  id="users" size="large" v-model="form.who_issued_ticket" placeholder="¿Quien lo compro?" class="text-xl block w-48 "  >
                                <el-option v-for="option in users" :key="id" :label="option.name" :value="option.id"/>
                            </el-select>
                        </div>
                        <div class="p-1">
                            <InputLabel class="py-2" for="users" value="Proveedor" />
                            <LookupField v-model="providerName" :firstLine="'company'" size="large" :secondLine="'whatsapp'"  ref="lookupProduct" :likeDataFx="{
                                fields: ['id', 'company', 'whatsapp'],
                                table: 'providers',
                                limit: 50,
                                orderBy: 'updated_at',
                                order: 'asc',
                                where: {
                                    field: 'company',
                                    operator: 'LIKE',
                                }
                            }" style="width: 200px"
                            @updateValue="(newValue) => {
                                console.log(newValue)
                                form.provider_id = newValue.id;
                                this.providerName = newValue.company;
                            }"/>
                        </div>

                        <div class="p-1">
                            <InputLabel class="py-2" for="users" value="Fecha de compra" />
                            <el-date-picker size="large"
                                v-model="form.date_time_issued"
                                type="datetime"
                                placeholder="Fecha/hora de surtido"
                            />
                        </div>
                        <div class="p-1">
                            <InputLabel class="py-2"  for="total" value="Total (MXN)" />
                            <el-input v-model="form.total" id="total" size="large"
                                    :formatter="(value) => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                    :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" placeholder="Please Input"/>
                        </div>
                        <div class="p-1">
                            <PrimaryButton class="m-9" @click="saveTicket">
                                Nueva Tienda
                            </PrimaryButton>
                        </div>
                    </div>
                    
                    <br/>

                    <div class="p-2 basis-3/4 rounded-lg  items-center justify-center bg-gray-400 shadow-lg">
                        
                        <el-empty description="NO HAY PRODUCTOS SELECCIONADOS" style="color: red;" v-if="productsSelected.length == 0"/>
                        # {{productsSelected.length}} productos seleccionados. 
                        <div class="w-full my-1 px-1 " v-for="p in productsSelected">
                            <div class="-mb-3 ml-1">
                                <el-button type="danger" @click="deleteDirectly(p.folio)" style="background-color: #dc2626;"  circle class="transition transform hover:scale-105 focus:scale-95 active:scale-90" >
                                    <el-icon><CloseBold /></el-icon>
                                </el-button>
                            </div> 
                            <div class="flex flex-wrap bg-gray-600 rounded-lg">



                                <div class="p-4">
                                    <el-input v-model="p.quantity" style="width: 100px" type="number" placeholder="Please Input"/>
                                </div>



                                <div class="  text-lg p-4 ">
                                    <el-icon color="#00ff11" class="mr-1" v-if="p.original_price_list > p.price_list "><CaretBottom /></el-icon>
                                    <el-icon color="#ff3a3a" class="mr-1" v-if="p.original_price_list < p.price_list "><CaretTop /></el-icon>
                                    
                                    <el-input v-model="p.priceByProduct"  style="width: 100px;" 
                                    :formatter="(value) => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                    :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" placeholder="Please Input"/>
                                </div>



                                <div class="p-4">
                                    {{(p.Description == 'EXPRESS CREATION') ? p.name : p.Description}} 
                                </div>





                                <div class="p-4">
                                    <el-popover :visible="p.visible" placement="bottom" :width="350" trigger="click">
                                    <template #reference>
                                        <PrimaryButton :class="(p.distributed) ? 'bg-red-600' : 'bg-sky-500'" @click="p.visible = true">{{(p.distributed) ? 'Distribuido' : 'Pendiente' }}</PrimaryButton>
                                    </template>
                                    Distribuye {{p.quantity}} {{ (p.take_portion) ? ' kilogramos ' : ' unidades ' }} de este producto entre las siguientes tiendas.
                                        <div v-for="st in p.stores">
                                            <el-divider :content-position="'left'" >{{st.store_name}}</el-divider>
                                            <el-input-number v-model="st.quantity" :min="0" :precision="(p.take_portion) ? 3 : 0 " :step="(p.take_portion) ? 0.001 : 1 "/>
                                        </div>  
                                        <br/>
                                        <center>
                                            <PrimaryButton @click="() =>{

                                                let quantityglobal = Object.keys(p.stores).reduce((acc, key) => {
                                                    return acc + p.stores[key].quantity;
                                                }, 0);
                                                console.log(this.productsSelected);
                                                console.log('quantityglobal', quantityglobal);
                                                this.assignTicketItem(p, quantityglobal);
                                                
                                            }">Finalizar</PrimaryButton>
                                        &nbsp;
                                            <PrimaryButton class="bg-sky-500" @click="p.visible = false">Cerrar</PrimaryButton>
                                        </center>
                                    </el-popover>
                                </div>
                            </div>

                        </div>

                    </div>
                </el-col>
            </el-row>
            
            
        </div>

    </AppLayout>
</template>
<style>
    .el-empty__description p {
        color: white !important;
    }
    .el-input-number__decrease, .el-input-number__increase {
        background: rgb(107 114 128) !important;
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
</style>
