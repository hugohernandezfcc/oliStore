<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { h } from 'vue';
import { ElNotification } from 'element-plus';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const open1 = () => {
  ElNotification({
    title: 'Title',
    message: h('i', { style: 'color: teal' }, 'This is a reminder'),
  })
}

window.location.replace("/sales");
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <InputLabel for="consultar_precio" value="Consultar Precio" />
                <TextInput
                    id="consultar_precio"
                    ref="consultar_precio"

                    type="text"
                    class="mt-1 block w-full"
                />
                <!-- <InputError :message="form.errors.current_password" class="mt-2" /> -->
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <el-button plain @click="open1"> Closes automatically </el-button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
