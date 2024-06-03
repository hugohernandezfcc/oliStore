<template>

    <el-button class="w-full" type="warning" @click="validateToOpen" >
        {{statusBox ?  'Abrir Caja' : 'Cerrar Caja' }}
    </el-button>

    <el-dialog v-model="openBox" :title="formLabels.statusBox" width="500" v-loading="loading">

        <InputLabel for="amountOpen" :value="formLabels.statusAmount" />
        <TextInput
            id="amountOpen"
            ref="inbound_amount"
            v-model="form.amount"
            type="number"
            step="0.01"
            class="mt-1 block w-full text-base text-green-700"/>

        <InputLabel for="amountOpen" :value="formLabels.foundsBox" />
        <TextInput
            id="amountOpen"
            ref="inbound_amount"
            v-model="form.founds_box"
            type="number"
            step="0.01"
            class="mt-1 block w-full text-base text-green-700"/>

      <template #footer>
        <div class="dialog-footer">

          <el-button type="danger" @click="validate">
            {{formLabels.statusButton}}
          </el-button>
        </div>
      </template>
    </el-dialog>
  </template>

<script >
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel   from    '@/Components/InputLabel.vue';
import TextInput    from    '@/Components/TextInput.vue';

export default{
    components:{
        AppLayout,
        PrimaryButton,
        SecondaryButton,
        InputLabel,
        TextInput
    },

    props:{

    },
    methods:{
        isOpenBox(){
            
            axios.get(route('box.is.open')).then(response => {
                console.log(response.data);
                this.statusBox = response.data.requireOpenBox;
                this.openBox = response.data.requireOpenBox;
                this.boxResponse = response.data;
            });
        },
        validate(){
            if(this.statusBox){
                this.saveBox()
            }else{
                this.closeBox()
            }
        },
        validateToOpen(){
            this.openBox = true;
            if(!this.statusBox){
                this.formLabels.statusBox = 'Cierre de caja';
                this.formLabels.statusAmount = 'Monto de cierre';
                this.formLabels.statusButton = 'Cerrar Caja';
            }else{
                this.formLabels.statusBox = 'Apertura de caja';
                this.formLabels.statusAmount = 'Monto de apertura';
                this.formLabels.statusButton = 'Abrir Caja';
            }
        },
        saveBox(){
            this.loading = true;
            axios.post(route('box.open'),this.form).then(response => {

                console.log(response.data);
                this.boxResponse = response.data;

                this.loading = false;
                if(response.data.status == 'open'){
                    this.openBox = false;
                    this.formLabels.statusBox = 'Cierre de caja';
                    this.formLabels.statusAmount = 'Monto de cierre';
                    this.formLabels.statusButton = 'Cerrar Caja';
                    this.statusBox = false;
                } 
                
            });
        },
        closeBox(){
            
            console.log(this.boxResponse.box);
            axios.post(route('box.close', { id: this.boxResponse.box.id }), this.form)
            .then(response => {
                console.log(response.data);
                this.openBox = false;
                this.formLabels.statusBox = 'Apertura de caja';
                this.formLabels.statusAmount = 'Monto de apertura';
                this.formLabels.statusButton = 'Abrir Caja';
                this.form.amount = 0;
                this.form.founds_box = 0;
                this.isOpenBox();
            })
            .catch(error => {
                console.error(error);
            });
        }
    },
    data(){
        return {
            boxResponse: {},
            openBox: false,
            statusBox: false,
            search:'',
            form:{
                amount:0,
                founds_box:0
            },
            loading:false,
            formLabels:{
                statusBox:      'Apertura de caja',
                statusAmount:   'Monto de apertura',
                foundsBox:      'Fondo de caja',
                statusButton:   'Abrir Caja'
            }
            
        }
    },
    mounted(){
        this.isOpenBox();
    },
    computed: {
        filterTableData() {
            return this.liabilities.filter(
                (data) =>
                !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
            );
        },
    }

}
</script>