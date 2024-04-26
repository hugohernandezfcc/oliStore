<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                OliStore
            </h2>
        </template>
        <!-- <PosItem /> -->
        <div class="m-4">
            <h3 class="text-lg text-gray-900"> Listado de Tareas - #{{ tasks.length }}</h3>
            <p class="text-sm text-gray">Listado de tareas por colaborador - Ordenado por fecha de creación / colaborador </p>
        </div>
        
        <div class="shadow overflow-x-auto">

                <div class="flex " v-loading="loading" element-loading-text="Cargando elementos ..." element-loading-background="#ff000054">
                    <div class="bg-white md:rounded-md w-full p-0 m-2 md:w-4/6 h-[30rem] overflow-y-auto sm:h-[80rem]" v-for="(user, index) in Object.keys(usersBoard)" :key="index">
                        {{ user }}

                        <div  v-for="task in usersBoard[user]" :key="index" >
                            <WallItem :task="task" :withComments="false" :showAvatar="false" />
                        </div>
                        
                    </div>
                    
                </div>

        </div>
    </AppLayout>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import WallItem from '@/Components/WallItem.vue';
    import { ElLoading } from 'element-plus';
    import PosItem from '@/Components/PosItem.vue';

    export default {
        components:{
            AppLayout,
            WallItem,
            ElLoading, PosItem
        },
        props: { 
            tasks: Array
        },
        data() {
            return {
                usersBoard:[],
                board:{},
                loading: true,
            }
        },
        methods: {
           
            test(a) {
              
            }
        },
        mounted() {
            console.log(this.tasks)
            for (let i = 0; i < this.tasks.length; i++) {
                if(this.usersBoard[this.tasks[i].assigned_to.name] !== undefined){
                    this.usersBoard[this.tasks[i].assigned_to.name].push(this.tasks[i])
                }else{
                    this.usersBoard[this.tasks[i].assigned_to.name] = [];
                    this.usersBoard[this.tasks[i].assigned_to.name].push(this.tasks[i])
                }
            }

            console.log(Object.keys(this.usersBoard));
            this.loading = false;

        }
    }
</script>