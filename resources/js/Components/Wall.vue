<template>
     <el-collapse model-value="0" accordion >
        <el-collapse-item :title="task.name" :name="index" v-for="(task, index) in tasks" :key="index" >
            <el-card class="m-3">
                <template #header>
                <div class="card-header">
                    <span> Tarea de <b class="text-red-600 ">{{task.assigned_to.name}}</b></span> || <span> {{task.status}}</span>
                </div>
                </template>
                {{ task.name }}<br/>
                <span class="text-sm " ><b>Abierta en </b> {{task.created_at.split('T')[0] }}</span>
                <el-collapse v-model="activeNam2" accordion >
                    <el-collapse-item :title="'Comentarios >> '"  >
                        <Posts  :target_id="task.id" :key="index"/>
                    </el-collapse-item>
                </el-collapse>

            </el-card>
        </el-collapse-item>
    </el-collapse>

</template>
<style>
.el-collapse-item__header{
    border-bottom-color: rgb(220 38 38);
    background-color: rgb(253, 246, 246);
}
.el-collapse-item__header.is-active{
    border-bottom-color: rgb(80, 143, 4);
}

</style>
<script>
    import Posts from './Posts.vue';
    

    export default {
        name: 'Wall',
        components: {
            Posts,
        },
        data() {
            return {
                showPosts: false,
                tasks: Array,
                activeName: ['0'],
                activeNam2: ['0']
            }
        },
        methods:{
            getAll(){
                axios.get( route('social.get.all') ).then(response => {
                    console.log(response.data)
                    for (let i = 0; i < response.data.length; i++) {
                        response.data[i].status = response.data[i].status == 'open' ? 'Tarea abierta' : 'Tarea Finalizada';
                    }
                    this.tasks = response.data;
                }).catch(error => {
                    console.log(error)
                })
            },
            togglePosts() {
                // This method toggles the visibility state
                this.showPosts = !this.showPosts;
            }
        },
        mounted() {
            console.log('mounting wall component');
            this.getAll();
        },
    }
</script>