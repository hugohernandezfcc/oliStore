<template>
    <div  v-for="(task, index) in tasks" :key="index" v-loading="loading" element-loading-text="Cargando elementos ..." element-loading-background="#ff000054">
        <WallItem :task="task" />
    </div>
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
    import WallItem from './WallItem.vue';
    import { ElLoading } from 'element-plus';

    export default {
        name: 'Wall',
        components: {
            Posts, WallItem, ElLoading
        },
        data() {
            return {
                showPosts: false,
                tasks: Array,
                loading: false,
            }
        },
        methods:{
            getAll(){
                this.loading = true;
                axios.get( route('social.get.all') ).then(response => {
                    console.log(response.data)
                    for (let i = 0; i < response.data.length; i++) {
                        response.data[i].status = response.data[i].status == 'open' ? 'Tarea abierta' : 'Tarea Finalizada';
                    }
                    this.tasks = response.data;
                    this.loading = false;
                }).catch(error => {
                    console.log(error)
                    this.loading = false;
                })
            },
            
        },
        mounted() {
            console.log('mounting wall component');
            this.getAll();
        },
    }
</script>