<script >
import wizardForm from '@/Components/TicketForm.vue';

export default {
	components: {
		wizardForm
	},
	props: {
		dialogVisible: { 
			type: Boolean
		},

		title: {
			type: String
		},

		typeOf: {
			type: String
		},
	},
	methods:{
		openModalForm(id, editItem){
            console.log(id);
            console.log(editItem);
			this.loading = true;
            switch (editItem) {
                case 'editItem':

					axios.get(`ticketItem/show/${id}`).then(response => {
						console.log(response.data);
						this.form.bar_code = response.data.bar_code;
						this.form.cost_customer = response.data.cost_customer;
						this.form.product_name = (response.data.product_name == null) ? 'Producto no encontrado' : response.data.product_name;
						this.form.quantity = response.data.quantity;
						this.form.id = response.data.id;
						this.loading = false;
					}).catch(error => {
						this.loading = false;
						console.log(error);
					});

                    this.modal.show = true;
                    this.modal.title = 'Editar producto del ticket';
                    this.modal.typeOf = 'editItem';
                    break;
                case 'ticketForm':
                    this.modal.show = true;
                    this.modal.title = 'Documentar ticket';
                    this.modal.typeOf = 'ticketForm';
                    break;
                default:
                    break;


            }
        },
		updateItem(){
			this.loading = true;
			axios.post(`ticketItem/update/${this.form.id}`, this.form).then(response => {
				console.log(response.data);
				this.loading = false;
				this.modal.show = false;
				this.$emit('updateTicket');
			}).catch(error => {
				this.loading = false;
				console.log(error);
			});
		
		}
	},
	data(){
		return {
			modal: {
				show: false,
				title: '',
				typeOf: ''
			},
			form: {
				bar_code: '',
				cost_customer: '',
				product_name: '',
				quantity: '',
				id: null,
				ticket: null
			},
			loading: false
		}
	}
};
</script>

<template>
	<el-dialog v-model="modal.show" :title="modal.title" width="500" draggable>
		<wizardForm :TypeForm="'ticketForm'" v-if="modal.typeOf == 'ticketForm'"/>
		<div v-if="modal.typeOf == 'editItem'" v-loading="loading">
			Ticket No.<br/>
            <el-input v-model="form.bar_code" 		placeholder="Cargando datos ..."  class="w-full shadow-2xl"/><br/><br/>
            <el-input v-model="form.quantity" 		placeholder="Cargando datos ..."  class="w-full shadow-2xl"/><br/><br/>
            <el-input v-model="form.cost_customer"  :formatter="(value) => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')" :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" 	placeholder="Cargando datos ..."  class="w-full shadow-2xl"/><br/><br/>
            <el-input v-model="form.product_name" 	placeholder="Cargando datos ..."  class="w-full shadow-2xl text-lg " disabled/><br/><br/>
			<el-button type="primary" @click="updateItem">Actualizar Producto en ticket</el-button>				
		</div>
		
	</el-dialog>
</template>
