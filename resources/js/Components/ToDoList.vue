<template>


    <div class="shadow bg-slate-200 md:rounded-md p-4" v-loading="loading" element-loading-text="Cargando elementos ..." element-loading-background="#ff000054">
      <form @submit.prevent="addTask">
        <el-select
                v-model="assigned_to"
                placeholder="Select"
                class="w-full p-2"
            >
            <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
            />
        </el-select>
        <el-row :gutter="20" class="p-2">
            <el-col :span="19">
                <el-input
                    v-model="newTask"
                    :placeholder="placeholder"
                    clearable/>
            </el-col>
            <el-col :span="2">
                <el-button type="danger" native-type="submit" plain>
                    <el-icon :size="17" :color="'#dc2626'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M352 480h320a32 32 0 1 1 0 64H352a32 32 0 0 1 0-64"></path><path fill="currentColor" d="M480 672V352a32 32 0 1 1 64 0v320a32 32 0 0 1-64 0"></path><path fill="currentColor" d="M512 896a384 384 0 1 0 0-768 384 384 0 0 0 0 768m0 64a448 448 0 1 1 0-896 448 448 0 0 1 0 896"></path></svg>
                    </el-icon>
                </el-button>
            </el-col>
        </el-row>
      </form>
      <br/>
      
    <div v-for="(task, index) in tasks" :label="`${index+1}`" :key="index">
      <table class="bg-slate-50 m-2 ">
        <tr>
          <td >
            <el-button type="danger" @click="deleteTask(index)" plain v-if="!task.completed">
                <el-icon :size="17" :color="'#dc2626'">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                </el-icon>
            </el-button>
            <el-button type="success"  plain v-if="task.completed" disabled>
              <el-icon :size="20" :color="'#3a953ad6'" >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M512 64a448 448 0 1 1 0 896 448 448 0 0 1 0-896m-55.808 536.384-99.52-99.584a38.4 38.4 0 1 0-54.336 54.336l126.72 126.72a38.272 38.272 0 0 0 54.336 0l262.4-262.464a38.4 38.4 0 1 0-54.272-54.336z"></path></svg>
              </el-icon>
            </el-button>
          </td>
          <td class="w-full" @click="toggleTask(index)" :class="{ completed: task.completed, noncompleted: !task.completed}">&nbsp; {{index+1}}.-  {{ task.text }}</td>
        </tr>
      </table>

    </div>
    

    </div>
  </template>
  
  <script>
import axios from 'axios';
import { ElLoading } from 'element-plus';


  export default {
    components: {
      ElLoading
    }, 
    props:{
        defaultvalue: Boolean,
        placeholder: String
    },
    data() {
      return {
        newTask: '',
        tasks: [],
        assigned_to: '',
        options: [],
        loading: false
      };
    },
    methods: { 
      addTask() {
        if (this.newTask.trim() === '') return;

        this.loading = true;
        axios.post(route('core.add.task'), {
          name: this.newTask.trim(),
          assigned_to: this.assigned_to
        }).then(response => {
          this.tasks.push({ text: this.newTask, completed: false });
          this.newTask = '';
          this.loading = false;
        }).catch(error => {
          console.log(error);
          this.loading = false;
          alert('Algo ha salido mal, avisale a Hugo.');
        });

  
      },
      toggleTask(index) {
        if(this.tasks[index].completed) {
          this.tasks[index].completed = false;
          this.openTask(this.tasks[index]);
        } else {
          this.tasks[index].completed = true;
          this.closeTask(this.tasks[index]);
        }
        // this.tasks[index].completed = !this.tasks[index].completed;
      },

      openTask(localTask) {
        axios.post(route('core.open.task'), {
          task: localTask.text,
          status: 'open'
        }).then(response => {
          console.log(response.data);
        }).catch(error => {
          console.log(error);
        });
      },

      closeTask(localTask) {
        axios.post(route('core.close.task'), {
          task: localTask.text,
          status: 'completed'
        }).then(response => {
          console.log(response.data);
        }).catch(error => {
          console.log(error);
          alert('Algo ha salido mal, avisale a Hugo.');
        });
      },
      
      deleteTask(index) {
        this.loading = true;
        axios.post(route('core.delete.task'), {
          task: this.tasks[index].text
        }).then(response => {
          console.log(response.data);
          if(response.data.status == 'error') {
            alert('No puedes eliminar tareas, contacta al responsable para que te ayude');
            this.loading = false;
            return;
          }
          this.tasks.splice(index, 1);
          this.loading = false;
        }).catch(error => {
          console.log(error);
          this.loading = false;
          alert('Algo ha salido mal, avisale a Hugo.');
        });
      }

    },
    mounted() {

      if(this.defaultvalue){
        this.assigned_to = 1;
      }
     
      this.loading = true;
      axios.get(route('core.active.users')).then(response => {
        console.group('users');
          console.log(response.data);
        console.groupEnd();
        for (let i = 0; i < response.data.length; i++) {
          this.options.push({ value: response.data[i].id, label: response.data[i].name });
        }

      }).catch(error => {
        console.log(error);
        
      });

      axios.get(route('core.tasks')).then(response => {
        console.log(response.data);
        for (let i = 0; i < response.data.length; i++) {
          this.tasks.push({ text: response.data[i].name, completed: (response.data[i].status == 'completed') ? true : false });
        }
        this.loading = false;
      }).catch(error => {
        console.log(error);
        this.loading = false;
      });
    },
  };
  </script>
  
  <style scoped>
  .completed {
    text-decoration: line-through ;
    text-decoration-color: red;
    cursor: pointer;
    color: rgb(150, 150, 150)
  }
  .noncompleted {
    cursor: pointer;
  }
  
  </style>





