
<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';


import SecondaryButton from '@/Components/SecondaryButton.vue'; 
import Field from '@/Components/Field.vue';
import { ElNotification } from 'element-plus';
import { ElLoading } from 'element-plus';

  export default{
      components:{
        PrimaryButton, SecondaryButton,  Field
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
                quantityItems: []
            },
            quantities:{
                quantity: 0,
                money: 0,
                producto: ''
            },
            search:'',
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
            ]            
        }
      },
      methods:{
        saveTicket() {
            this.activeTicket = 0;
        },
          nextTicket() {

              if (this.activeTicket++ > 2) 
                this.activeTicket = 0;
          },
          nextProduct() {
              if (this.activeProduct++ > 2) 
                this.activeProduct = 0;
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
        
        <!-- #1 FORM -->
        <div v-if="activeTicket == 0" align-center>
            Ticket No.<br/>
            <el-input v-model="form.noTicket" placeholder="001210222" style="width: 220px"/><br/>
            Fecha/hora que fue surtido<br/>
            <el-date-picker
                v-model="form.dateTimeIssued"
                type="datetime"
                placeholder="Select date and time"
            /><br/>
            ¿Quien surtió el ticket?<br/>
            <el-select
                v-model="form.whoIssuedTicket"
                placeholder="Select"

                style="width: 220px"
            >
                <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
                />
            </el-select><br/>
            Proveedor<br/>
            <el-input v-model="form.provider" placeholder="Zorro, coca, tortillas.." style="width: 220px"/><br/>
            $ Total MXN<br/>
            <el-input v-model="form.total" placeholder="$ 20 MXN" style="width: 220px"/>
        </div>
        


        <div v-if="activeTicket == 1" align-center>
            <el-row :gutter="20">
                <el-col :span="6"># Cantidad
                    <el-input v-model="quantities.quantity" ref="quantity" placeholder="#" autofocus/>
                </el-col>
                <el-col :span="6">$ Total
                    <el-input v-model="quantities.money" placeholder="$ MXN" />
                </el-col>
                <el-col :span="12">Producto
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
  
