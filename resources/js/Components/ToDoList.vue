<template>


    <div class="shadow bg-slate-200 md:rounded-md p-4" v-loading="loading" element-loading-text="Cargando elementos ..." element-loading-background="#ff000054">
      <form @submit.prevent="addTask">
        <el-select v-model="assigned_to" placeholder="Select" class="w-full p-2">
          <el-option v-for="item in options"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"/>
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
      <!-- <div v-loading="loading2"></div> -->
    <div v-for="(task, index) in tasks" :label="`${index+1}`" :key="index">
      <table class="bg-slate-50 m-2 ">
        <tr>
          <td >
            <el-button  type="danger" @click="openModal(index)" plain v-if="!task.completed" >
            <!-- <el-button type="danger" @click="deleteTask(index)" plain v-if="!task.completed"> -->
                <el-icon :size="17" :color="'#dc2626'" >
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M389.44 768a96.064 96.064 0 0 1 181.12 0H896v64H570.56a96.064 96.064 0 0 1-181.12 0H128v-64zm192-288a96.064 96.064 0 0 1 181.12 0H896v64H762.56a96.064 96.064 0 0 1-181.12 0H128v-64zm-320-288a96.064 96.064 0 0 1 181.12 0H896v64H442.56a96.064 96.064 0 0 1-181.12 0H128v-64z"></path></svg>
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
        <tr :class="task.color">
            <td colspan="2" class="text-xs text-gray-500"><b class="text-red-600">Día: </b>{{ task.created_date }} | <b class="text-red-600">Asignación: </b>{{ task.assigned_to_name }}<span v-if="task.color == 'bg-yellow-200'" class="underline text-red-600 "> || Aun pendiente</span></td>
          </tr>
      </table>
    </div>

    <div v-if="isAdmin && typeItem == 'tasks'">
      <br/>
      <div class="h-[2px] bg-red-500 w-full ">Elementos asignados a otros</div>
      <br/>



      <div v-for="(task, index) in anotherTasks" :label="`${index+1}`" :key="index">
        
        <table class="bg-slate-50 m-1 w-full">
          <tr >              
            <td class="w-full"  :class="{ completed: task.completed, noncompleted: !task.completed}">
              <el-button  type="danger" @click="openModal2(index)" plain>
                <el-icon :size="17" :color="'#dc2626'" >
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M389.44 768a96.064 96.064 0 0 1 181.12 0H896v64H570.56a96.064 96.064 0 0 1-181.12 0H128v-64zm192-288a96.064 96.064 0 0 1 181.12 0H896v64H762.56a96.064 96.064 0 0 1-181.12 0H128v-64zm-320-288a96.064 96.064 0 0 1 181.12 0H896v64H442.56a96.064 96.064 0 0 1-181.12 0H128v-64z"></path></svg>
                </el-icon>
              </el-button>
              &nbsp; <span class="text-sm">{{index+1}}.-  {{ task.text }}</span></td>
          </tr>
          <tr :class="task.color">
            <td colspan="1" class="text-xs text-gray-500"><b class="text-red-600">Día: </b>{{ task.created_date }} | <b class="text-red-600">Asignación: </b>{{ task.assigned_to_name }}<span v-if="task.color == 'bg-yellow-200'" class="underline text-red-600 "> || Aun pendiente</span></td>
          </tr>
          
        </table>

      </div>
    </div>
  </div>

  <!-- modal -->
    <el-dialog v-model="dialogVisible"  :before-close="beforeCloseModal" :width="'80%'" :center="true">

      <el-page-header @back="closeModal" >
        <template #breadcrumb>
          <el-breadcrumb separator="/">
            <el-breadcrumb-item @click="closeModal" class="cursor-pointer font-bold ">
              Punto de venta
            </el-breadcrumb-item>
            <el-breadcrumb-item @click="closeModal" class="cursor-pointer font-bold ">
              Compras
            </el-breadcrumb-item>
            <el-breadcrumb-item>{{modalData.title}}</el-breadcrumb-item>
          </el-breadcrumb>
        </template>


        <template #content>
          <div class="flex items-center">
            <el-avatar
              class="mr-3"
              :size="32"
              src="https://cube.elemecdn.com/0/88/03b0d39583f48206768a7534e55bcpng.png"
            />
            <span class="text-base font-600 mr-1"> {{modalData.assigned_to_name}} </span>
            
          </div>
        </template>
        <template #extra>
        <div class="flex items-center">
          <el-button type="danger" plain class="ml-1" @click="deleteTask2(modalData.index)" >
            <el-icon :size="17" :color="'#dc2626'">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M160 256H96a32 32 0 0 1 0-64h256V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64h-64v672a32 32 0 0 1-32 32H192a32 32 0 0 1-32-32zm448-64v-64H416v64zM224 896h576V256H224zm192-128a32 32 0 0 1-32-32V416a32 32 0 0 1 64 0v320a32 32 0 0 1-32 32m192 0a32 32 0 0 1-32-32V416a32 32 0 0 1 64 0v320a32 32 0 0 1-32 32"></path></svg>
            </el-icon>
          </el-button>
          <el-button type="success" plain>
            <el-icon :size="20" :color="'#3a953ad6'" @click="toggleTask(modalData.index)">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" data-v-ea893728=""><path fill="currentColor" d="M512 64a448 448 0 1 1 0 896 448 448 0 0 1 0-896m-55.808 536.384-99.52-99.584a38.4 38.4 0 1 0-54.336 54.336l126.72 126.72a38.272 38.272 0 0 0 54.336 0l262.4-262.464a38.4 38.4 0 1 0-54.272-54.336z"></path></svg>
              </el-icon>
          </el-button>
        </div>
      </template>
        <br/>
        <div class="flex flex-wrap">
          <div class="w-full sm:w-1/2"><b>Estado: </b> <el-tag>{{modalData.status}}</el-tag></div>
          <div class="w-full sm:w-1/2"><b>Asignado a: </b> {{modalData.assigned_to_name}}</div>
          <div class="w-full "><b>Descripción: </b> {{modalData.description}}</div>
        </div>
       
        
      </el-page-header>
      <el-divider content-position="left">Comentarios</el-divider>
      <Posts :target_id="modalData.id" />
    </el-dialog>
  <!-- /modal -->
  </template>
  
  <script>
  import axios from 'axios';
  import { ElLoading } from 'element-plus';
  import Posts from '@/Components/Posts.vue';
  export default {
    components: {ElLoading, Posts }, 
    props:{
        defaultvalue: Boolean,
        placeholder: String,
        typeItem: String,
        isAdmin: Boolean
    },
    data() {
      return {
        dialogVisible: false,
        newTask: '',
        tasks: [],
        anotherTasks: [],
        dates_tasks: [],
        assigned_to: '',
        options: [],
        loading: false,
        loading2: false,
        matchUsers: [],
        modalData: {
          id: null,
          title: '',
          description: '',
          status: '',
          assigned_to: '',
          assigned_to_name: '',
          index: 0
        },
      };
    },
    methods: { 

      beforeCloseModal() {
        this.dialogVisible = false;

        // this.$confirm('Are you sure to close this dialog?')
        //   .then(_ => {
        //     done();
        //   })
        //   .catch(_ => {});
      },
      

      addTask() {
        if (this.newTask.trim() === '') return;

        this.loading = true;
        console.log(this.assigned_to);
        axios.post(route('core.add.task'), {
          name: this.newTask.trim(),
          assigned_to: this.assigned_to,
          recordType: this.typeItem
        }).then(response => {
          console.log(response.data);
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
        console.log(this.tasks[index].completed);

        if(this.tasks[index].completed) {
          this.tasks[index].completed = false;
          this.openTask(this.tasks[index]);
        } else {
          this.tasks[index].completed = true;
          this.closeTask(this.tasks[index]);
        }
        // this.tasks[index].completed = !this.tasks[index].completed;
        this.dialogVisible = false;
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
          this.dialogVisible = false;
        }).catch(error => {
          console.log(error);
          this.loading = false;
          this.dialogVisible = false;
          alert('Algo ha salido mal, avisale a Hugo.');
        });
      },
      deleteTask2(index) {
        this.loading = true;
        axios.post(route('core.delete.task'), {
          task: this.anotherTasks[index].text
        }).then(response => {
          console.log(response.data);
          if(response.data.status == 'error') {
            alert('No puedes eliminar tareas, contacta al responsable para que te ayude');
            this.loading = false;
            return;
          }
          this.anotherTasks.splice(index, 1);
          this.loading = false;
          this.dialogVisible = false;
        }).catch(error => {
          console.log(error);
          this.loading = false;
          this.dialogVisible = false;
          alert('Algo ha salido mal, avisale a Hugo.');
        });
      },

      fetchActiveUsers() {
        return axios.get(route('core.active.users')).then(response => {
          const options = [];
          const matchUsers = {};

          for (let i = 0; i < response.data.length; i++) {
            options.push({ value: response.data[i].id, label: response.data[i].name });
            matchUsers[response.data[i].id] = response.data[i].name;
          }

          return { options, matchUsers }; // Return both for further use
        });
      },
      fetchTasks(url) {
        return axios.get(url).then(response => {
          console.log(response.data);
          
          if (url.includes('another')) { // For 'anotherTasks'

            return response.data.map(task => ({
              text: task.name,
              assigned_to_name: this.matchUsers[task.assigned_to_id],
              completed: task.status == 'completed',
              created_date: task.created_at.split('T')[0].slice(5),
              color: task.created_at.split('T')[0].slice(5) == new Date().toISOString().slice(5, 10) ? 'red' : 'bg-yellow-200'
            }));

          } else { // For 'tasks'
            console.log('tere');
            return response.data.map(task => ({
              text: task.name,
              completed: task.status == 'completed',
              readonly: task.readOnly,
              assigned_to_name: this.matchUsers[task.assigned_to_id],
              created_date: task.created_at.split('T')[0].slice(5),
              color: task.created_at.split('T')[0].slice(5) == new Date().toISOString().slice(5, 10) ? 'red' : 'bg-yellow-200'
            }));
          }
        });
      },
      openModal(index) {

this.modalData.title = this.tasks[index].text;
this.loading2 = true;
axios.get(`core/get/single/task/${this.modalData.title.replaceAll('/', '@')}`).then(response => {
  console.log(response.data);
  this.modalData.description = response.data.description;
  this.modalData.status = response.data.status;
  this.modalData.assigned_to = response.data.assigned_to;
  this.modalData.assigned_to_name = this.matchUsers[response.data.assigned_to_id];
  this.modalData.index = index;
  this.modalData.id = response.data.id;
  this.dialogVisible = true;
  this.loading2 = false;
  console.log(response.data);
}).catch(error => {
  this.loading2 = false;
  console.log(error);
});
},
openModal2(index) {

this.modalData.title = this.anotherTasks[index].text;
this.loading2 = true;
axios.get(`core/get/single/task/${this.modalData.title.replaceAll('/', '@')}`).then(response => {
  console.log(response.data);
  this.modalData.description = response.data.description;
  this.modalData.status = response.data.status;
  this.modalData.assigned_to = response.data.assigned_to;
  this.modalData.assigned_to_name = this.matchUsers[response.data.assigned_to_id];
  this.modalData.index = index;
  this.modalData.id = response.data.id;
  this.dialogVisible = true;
  this.loading2 = false;
  console.log(response.data);
}).catch(error => {
  this.loading2 = false;
  console.log(error);
});
},
      closeModal() {
        this.dialogVisible = false;
      },
    },
    mounted() {

      if(this.defaultvalue){
        this.assigned_to = 1;
      }

      this.loading = true;
      this.fetchActiveUsers().then(({ options, matchUsers }) => {
        this.options = options; // Assuming 'this' is accessible
        this.matchUsers = matchUsers;
        console.log(this.matchUsers);
        return Promise.all(
          [ 
            this.fetchTasks(`core/get/task/${this.typeItem}`), 
            this.fetchTasks(`core/get/another/task/${this.typeItem}`) 
          ]
        );
      }).then(([tasks, anotherTasks]) => {

        this.tasks = tasks;
        this.anotherTasks = anotherTasks;

        console.log(this.tasks);
        console.log(this.anotherTasks);
        this.loading = false;
      }).catch(error => {
        console.log(error);
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





