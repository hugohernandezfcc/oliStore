
<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';


import SecondaryButton from '@/Components/SecondaryButton.vue'; 
import Field from '@/Components/Field.vue';
import { ElNotification } from 'element-plus';
import { ElLoading } from 'element-plus';
import { ElMessageBox } from 'element-plus';

  export default{
      components:{
        PrimaryButton, SecondaryButton,  Field, ElMessageBox
      },
      props:{
          TypeForm: String
      },

      data(){

        return {
            activeTicket: 0,
            activeProduct: 0,
            activeStock: 0,
            totalProducts: 0,
            form:{
                noTicket: '',
                dateTimeIssued: '',
                whoIssuedTicket: '',
                provider: '',
                total: '',
                id: '',
                quantityItems: []
            },
            quantities:{
                quantity: null,
                money: null,
                producto: ''
            },
            search:'',
            showMessage : false,
            options: [
            {
                value: 'Hugo Hernández',
                label: 'Hugo Hernández',
            },
            {
                value: 'Xanat Gómez',
                label: 'Xanat Gómez',
            },
            {
                value: 'Tania Gómez',
                label: 'Tania Gómez',
            },
            {
                value: 'Eduardo Vargas',
                label: 'Eduardo Vargas',
            },
            {
                value: 'Jose Antonio',
                label: 'Jose Antonio',
            },
            ],
            cantContinue : false            
        }
      },
        methods:{
            saveTicket() {
                this.activeTicket = 0;

                axios.post('/storeticket', this.form, {
                    headers: {
                        scheme: 'https'
                    }
                }).then((res) => {

                    console.log(res.data);
                    
                    this.form.noTicket = '';
                    this.form.dateTimeIssued = '';
                    this.form.whoIssuedTicket = '';
                    this.form.provider = '';
                    this.form.total = '';
                    this.form.quantityItems = []
                
                   
                }).catch((error) => {
                    console.log(error);
                });

            },
            
            nextTicket() {

                // for(var k in this.form){
                //     console.log(this.form[k].length);
                //     if(this.form[k].length == 0 && k != 'quantityItems' && this.activeTicket == 0){
                //         this.cantContinue = true;
                //     }
                // }

               
                
                // if(!this.cantContinue){

                    if(this.activeTicket == 0 && !this.cantContinue){

                        axios.get('/tickets/check/' + this.form.noTicket, {
                            headers: {
                                scheme: 'https'
                            }
                        }).then((res) => {
                            console.log(res);
                            if(res.data != ''){
                                this.form.id = res.data.id;
                                this.form.noTicket = res.data.noTicket;
                                this.form.dateTimeIssued = res.data.date_time_issued;
                                this.form.whoIssuedTicket = res.data.who_issued_ticket;
                                this.form.provider = res.data.provider;
                                this.form.total = res.data.total;
                                this.form.quantityItems = [];
                                for (let index = 0; index < res.data.ticket_items.length; index++) {
                                    const element = res.data.ticket_items[index];
                                    this.form.quantityItems.push({
                                        quantity: element.quantity,
                                        money: element.cost_customer,
                                        producto: element.bar_code
                                    })
                                }
                                this.showMessage = true;
                            }else{
                                this.showMessage = false;
                            }

                            if (this.activeTicket++ > 2) 
                                this.activeTicket = 0;

                            

                        }).catch((error) => {
                            console.log(error);
                        });

                    }else{
                        if (this.activeTicket++ > 2) 
                            this.activeTicket = 0;
                    }
                    console.log('>>>',this.activeTicket);

                    
                // }else{
                //     ElMessageBox.confirm('Debes llenar todos los campos')
                //     .then(() => {
                //         console.log('se ha notificado que faltan cambios')
                //         this.cantContinue = false;
                //     }).catch(() => {
                //     // catch error
                //     })
                // }
                

            },
            clearTicket(){
                this.form.noTicket = '';
                this.form.dateTimeIssued = '';
                this.form.whoIssuedTicket = '';
                this.form.provider = '';
                this.form.total = '';
                this.form.quantityItems = [];
                this.activeTicket = 0;
                this.showMessage = false;

            },
            nextProduct() {
                if (this.activeProduct++ > 2) 
                    this.activeProduct = 0;
            },
            backToTicket() {
                this.activeTicket = 0;
            },
            nextStock() {
                if (this.activeStock++ > 2) 
                    this.activeStock = 0;
            },
            onEnter(e){
                if (e.keyCode === 13) {
                    this.addItemToList();
            }
        },
          addItemToList(){
            this.totalProducts = Number(this.totalProducts)+Number(this.quantities.quantity);
            this.form.quantityItems.push({
                quantity: this.quantities.quantity,
                money: this.quantities.money,
                producto: this.quantities.producto
            })

            this.quantities.quantity = null;
            this.quantities.money    = 0;
            this.quantities.producto = '';


            console.log(this.form);
            this.$refs.quantity.value = '';
            this.$refs.quantity.focus();
            

          },
          handleDelete(param1, param2){
            console.log(param1);
            console.log(param2);
            
            this.totalProducts = Number(this.totalProducts)-Number(param1.quantity);

            var index = this.form.quantityItems.indexOf(param2);
            if (index !== -1) {
                this.form.quantityItems.splice(index, 1);
            }
          }
        },
      
        mounted(){

        },
        computed: {
            filterTableData() {
                return this.form.quantityItems.filter(
                    (data) =>
                    !this.search || data.producto.toLowerCase().includes(this.search.toLowerCase())
                );
            },
        }
  }
</script>

<template>

  

    <div v-if="TypeForm == 'ticketForm'">
        <el-steps :active="activeTicket" finish-status="success" >
            <el-step title="Ticket" />
            <el-step title="Stock" />
            <el-step title="Guardar" />
        </el-steps>
        <hr class="my-6"/>

        <br/>
        <!-- #1 FORM -->
        <div v-if="activeTicket == 0" align-center>

            
            Ticket No.<br/>
            <el-input v-model="form.noTicket" placeholder="001210222"  class="w-full shadow-2xl"/><br/><br/>
            Fecha/hora que fue surtido<br/>
            <el-date-picker
                v-model="form.dateTimeIssued"
                type="datetime"
                placeholder="Select date and time"
                :style="{ width: '100%' }"
            /><br/><br/>
            ¿Quien surtió el ticket?<br/>
            <el-select
                v-model="form.whoIssuedTicket"
                placeholder="Select"
                class="w-full shadow-2xl"
            >
                <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
                />
            </el-select><br/><br/>
            Proveedor<br/>
            <el-input v-model="form.provider" placeholder="Zorro, coca, tortillas.." class="w-full shadow-2xl"/><br/><br/>
            $ Total MXN<br/>
            <el-input v-model="form.total" placeholder="$ 20 MXN" class="w-full shadow-2xl"/>
        </div>
        


        <div v-if="activeTicket == 1" align-center>
            <div v-if="showMessage == true">
                Ticket encontrado: &nbsp;&nbsp;
                <el-button size="small" type="info" round>Editar</el-button> <el-button size="small" @click="clearTicket" type="warning" round>Limpiar</el-button>
                <el-divider content-position="center">
                    Ticket {{ this.form.noTicket }} 
                </el-divider>
            </div>
            <el-row :gutter="20">
                <el-col :span="6">Cantidad
                    <el-input v-model="quantities.quantity" ref="quantity" placeholder="#" autofocus/>
                </el-col>
                <el-col :span="8">Total
                    <el-input v-model="quantities.money" placeholder="$ MXN" />
                </el-col>
                <el-col :span="10">Producto
                    <el-input v-model="quantities.producto" placeholder="Frijoles..." v-on:keyup="onEnter"/>
                </el-col>
            </el-row>
            <el-divider content-position="center">
                <el-button :type="'primary'" link @click="addItemToList">Agregar</el-button>
            </el-divider>
            

            <el-table :data="filterTableData" height="250" >
                <el-table-column prop="quantity" label="#" width="50" />
                <el-table-column prop="money" label="$ Total" width="80" />
                <el-table-column prop="producto" label="Barras" width="140" />

                    <el-table-column align="right" fixed="right" width="100">
                        <template #header>
                            <el-input v-model="search" size="small" placeholder="Type to search" />
                        </template>
                        <template #default="scope">
                            <el-button
                            size="small"
                            type="danger"
                            @click="handleDelete(scope.$index, scope.row)"
                            >Delete</el-button
                            >
                        </template>
                    </el-table-column>

            </el-table>
            <el-divider content-position="center">
                # {{ totalProducts }} productos.
            </el-divider>
        </div>



        <div v-if="activeTicket == 2" align-center>
            
            

            <table style="border: solid 1px black;">
                <tr>
                    <td style="width: 80px; border: solid 1px black;"><b>Ticket No.:</b> </td>
                    <td>{{form.noTicket}}</td>
                </tr>
                <tr>
                    <td style="width: 50px;border: solid 1px black;"><b>Proveedor:</b> </td>
                    <td>{{form.provider}}</td>
                </tr>
                <tr>
                    <td style="width: 50px;border: solid 1px black;"><b>Surtido en:</b> </td>
                    <td>{{form.dateTimeIssued}}</td>
                </tr>
                <tr>
                    <td style="width: 50px;border: solid 1px black;"><b>Surtido por:</b> </td>
                    <td>{{form.whoIssuedTicket}}</td>
                </tr>
                <tr>
                    <td style="width: 50px;border: solid 1px black;"><b>Total:</b> </td>
                    <td>{{form.total}}</td>
                </tr>
                <tr>
                    <td style="width: 50px;border: solid 1px black;" colspan="2"><b>Productos:</b> </td>
                </tr>
                    <td colspan="2">
                        <table >
                            <tr>
                                <td style="width: 80px;"><b>Cantidad</b></td>
                                <td style="width: 80px;"><b>Total</b></td>
                                <td style="width: 80px;"><b>C. Barras</b></td>
                            </tr>
                            <tr v-for="i in form.quantityItems">
                                <td># {{ i.quantity }} </td>
                                <td>${{ i.money }} MXN </td>
                                <td>{{ i.producto }} </td>
                            </tr>
                        </table>

                    </td>
            </table>
                
        </div>
        <!--/ #1 FORM -->
    
        <el-button style="margin-top: 12px" @click="backToTicket" v-if="activeTicket == 1">Atras</el-button>
        <el-button style="margin-top: 12px" @click="nextTicket" v-if="activeTicket != 2">Siguiente</el-button>
        <el-button type="danger" style="margin-top: 12px" @click="saveTicket" v-if="activeTicket == 2">Guardar Ticket</el-button>
    </div>
    <div v-if="TypeForm == 'productQuery'">
        Consultar Producto:<br/>
        <el-input v-model="form.provider" placeholder="750168412315" style="width: 220px"/>
    </div>

    <div v-if="TypeForm == 'requestStock'">
        Surtir producto:<br/>
        <el-input v-model="form.provider" placeholder="750168412315" style="width: 220px"/>
    </div>
</template>
  
