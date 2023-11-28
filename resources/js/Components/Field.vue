<script>
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';

    export default{
        components:{
            InputLabel, TextInput
        },
        emits: ['update:modelValue'],
        props:{
            id: String,
            label: String,
            typeField: String,
            modelValue: String,
            required: Boolean
        },
        methods:{
        },
        data(){
            return {
            }
        },
        mounted(){

        }
}

</script>
<template>
    <div class="md:w-full lg:px-[20%]">

        <InputLabel :for="id" :value="label" />
        <TextInput
        :id="id"
        :type="typeField"
        class="mt-1 block w-full "
        :required="required"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>
</template>