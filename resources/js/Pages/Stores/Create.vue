<script >
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
 
export default{

    components:{
        AppLayout, FormSection, PrimaryButton, InputError, InputLabel, TextInput, SecondaryButton, ActionMessage
    },
    props:{
        store: Object

    },
    data(){

        return {
            form: {
                name: '',
                street: '', 
                postal_code: '', 
                state: '', 
                town: '', 
                country: '', 
                external_number: '', 
                workin_hours: '', 
                phone: '',
                email: '',
                website: '',
                no_providers: ''
            }
        }
    },
    methods:{
        submit(){
            this.$inertia.post(this.route('stores.store'), this.form);
        }
    },
    mounted() {
        console.log(import.meta.env.VITE_DB_CONNECTION)
    }
}



</script> 

<template>
    <AppLayout title="Profile">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de la tienda
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <FormSection >
                    <template #title>
                        Crear nueva Tienda
                    </template>

                    <template #description>
                        Aquí puedes registrar una tienda nueva
                    </template>
                    <template #form>
                        
                        
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Nombre de la tienda" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="name"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="street" value="Calle" />
                            <TextInput
                                id="street"
                                v-model="form.street"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="street"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="postal_code" value="Código Postal" />
                            <TextInput
                                id="postal_code"
                                v-model="form.postal_code"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="postal_code"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="state" value="Estado" />
                            <TextInput
                                id="state"
                                v-model="form.state"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="state"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="town" value="Colonía" />
                            <TextInput
                                id="town"
                                v-model="form.town"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="town"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="country" value="País" />
                            <TextInput
                                id="country"
                                v-model="form.country"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="country"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="external_number" value="Número externo" />
                            <TextInput
                                id="external_number"
                                v-model="form.external_number"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="external_number"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="workin_hours" value="Horas de trabajo" />
                            <TextInput
                                id="workin_hours"
                                v-model="form.workin_hours"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="workin_hours"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="phone" value="Phone" />
                            <TextInput
                                id="phone"
                                v-model="form.phone"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="phone"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="email" value="email" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="email"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="website" value="website" />
                            <TextInput
                                id="website"
                                v-model="form.website"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="website"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="no_providers" value="Número de proveedores" />
                            <TextInput
                                id="no_providers"
                                v-model="form.no_providers"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="no_providers"
                            />
                        </div>

                    </template>
                    <template #actions>
                        <ActionMessage :on="true" class="me-3">
                            Saved.
                        </ActionMessage>
            <!-- :class="{ 'opacity-25': form.processing }" :disabled="form.processing" -->
                        <PrimaryButton @click="submit">
                            create
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>
    </AppLayout>
   
</template>
