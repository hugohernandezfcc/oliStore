<template>
    <el-input v-model="search" placeholder="Busca tus productos dentro de tu orden" />
    <el-table :data="filterTableData" style="width: 100%">
        <el-table-column align="left" width="70">
            
            <template #default="scope">
            <!-- <el-button size="small" @click="handleEdit(scope.$index, scope.row)">
                Edit
            </el-button> -->
            <el-button
                size="small"
                type="danger"
                class="touch-manipulation"
                @click="handleDelete(scope.$index, scope.row)"
            >
                <el-icon><CircleCloseFilled /></el-icon>
            </el-button>
            </template>
        </el-table-column>
        <el-table-column label="Producto" width="130">
            <template #default="scope">
                <center>
                    <img :src="scope.row.image" :alt="scope.row.name" class="w-8 h-8 rounded-lg">
                </center>
                <span class="text-xs">{{scope.row.name}}</span>
            </template>
        </el-table-column>

        <el-table-column label="Precio"  >
            <template #default="scope">
                <b>$ {{ scope.row.price }} </b>
            </template>
        </el-table-column>
        <el-table-column label="# " width="80" prop="quantity" />
        
    </el-table>
  </template>
  
  <script lang="ts">
  import { defineComponent } from 'vue'
  import {CircleCloseFilled} from '@element-plus/icons-vue'
  interface producto {
    name: string
    description: string
    price: number
    quantity: number
    image: string
  }
  
  export default defineComponent({
    name: 'ProductTable',
    components: {
      CircleCloseFilled
    },
    props: {
      tableData: {
        type: Array as () => producto[],
        required: true
      }
    },
  
    data() {
      return {
        search: ''
      }
    },
  
    computed: {
      filterTableData(): producto[] {
        return this.tableData.filter((data) => {
          return (
            !this.search ||
            data.name.toLowerCase().includes(this.search.toLowerCase())
          )
        })
      },
    },
  
    methods: {
      handleEdit(index: number, row: producto) {
        console.log('Edit', index, row)
      },
      handleDelete(index: number, row: producto) {
        console.log('Deleted', index, row)
        this.tableData.splice(index, 1);
      },
    },
  })
  </script>