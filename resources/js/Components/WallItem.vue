<template>
    <div class="bg-white p-3 m-2 border-red-600 border">
        <el-row>
            <el-col :span="2">
                <el-avatar
                class="mr-2"
                :size="30"
                :src="task.assigned_to.profile_photo_url"
                />
            </el-col>
            <el-col :span="16"><span class="text-lg font-bold text-red-600 ">{{task.assigned_to.name}}</span></el-col>
            <el-col :span="6" class="align-right">
                <el-icon :size="15" :color="'#ec0505'" class="m-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M512 896a384 384 0 1 0 0-768 384 384 0 0 0 0 768m0 64a448 448 0 1 1 0-896 448 448 0 0 1 0 896"></path><path fill="currentColor" d="M480 256a32 32 0 0 1 32 32v256a32 32 0 0 1-64 0V288a32 32 0 0 1 32-32"></path><path fill="currentColor" d="M480 512h256q32 0 32 32t-32 32H480q-32 0-32-32t32-32"></path></svg>
                </el-icon>
                <span class="text-sm ">&nbsp;{{task.created_at.split('T')[0] }}</span>
            </el-col>
        </el-row>

        <el-row>
            <el-col :span="24">
                <span class="text-sm">{{task.name}}</span><br/>
                <span> Tarea de <b class="text-red-600 ">{{task.assigned_to.name}}</b></span> || <span> {{task.status}}</span>
            </el-col>
        </el-row>
        <br/>
        <div class="bg-gray-100 p-3 -m-3">
            <span class="pb-1 text-xs"> Creado por <b class="text-red-600 ">{{task.created_by.name}}</b></span>
            <Posts  :target_id="task.id" :key="index"/>
        </div>
    </div>

</template>
<script>
import Posts from './Posts.vue';
export default {
    name: 'WallItem',
    props: {
        task: Object,
    },
    components: {
        Posts
    },
    data() {
        return {
            showPosts: false
        }
    },
    methods: {
        togglePosts() {
            // This method toggles the visibility state
            this.showPosts = !this.showPosts;
        }
    },
    mounted() {
        console.log('mounting wall item component');
        console.log(this.task);
    }
}
</script>