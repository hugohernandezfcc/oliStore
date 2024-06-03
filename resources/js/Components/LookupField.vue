<script>
import { Edit } from '@element-plus/icons-vue';
import axios from 'axios';
import { orderBy } from 'element-plus/es/components/table/src/util';


export default {
  components: {
    Edit
  },
  props:{
      path: String,
      externalCollection: {
        type: Array,
        default: () => []
      },
      likeDataFx: {
        type: Function,
        default: () => {}
      },
      firstLine: {
        type: String,
        default: ''
      },
      secondLine:{
        type: String,
        default: ''
      },
      lastLine:{
        type: String,
        default: ''
      }
  },
  data() {
    return {
      state: '',
      records: [],
    } 
  },
  methods: {
    querySearch(queryString, cb) {
      
      setTimeout(() => {
      console.log(queryString)
      const results = queryString
        ? this.records.filter(this.createFilter(queryString))
        : this.records;

        if(results.length <= 5){
          this.likeDataFx.where.value = queryString;
          axios.post(route('lookup.field'), this.likeDataFx).then(response => {
              console.log(response.data)
              this.records = response.data;
              cb(response.data);
          }).catch(error => {
              console.log(error)
          });
        }else{
          cb(results);
        }
      }, 1000);
    },
    createFilter(queryString) {
      return (record) => {
        return JSON.stringify(record).toLowerCase().includes(queryString.toLowerCase())
      }
    },
    handleSelect(item) {
      console.log(item)
      this.state = item[this.firstLine];
      this.$emit('updateValue', item);
    },
    handleIconClick(ev) {
      console.log(ev)
    },
    clear() {
      this.state = '';
    },
    loadAll() {
      console.log(this.externalCollection)
      console.log(this.likeDataFx)
      console.log(this.value)
      if (this.externalCollection.length > 0) {
        return this.externalCollection
      }else{
        axios.post(route('lookup.field'), {
          fields: this.likeDataFx.fields,
          table: this.likeDataFx.table,
          orderBy: this.likeDataFx.orderBy,
          order: this.likeDataFx.order
        }).then(response => {
            console.log(response.data)
            this.records = response.data;
            return response.data;
        }).catch(error => {
            console.log(error)
        });

     
      }
      
    }
  },
  mounted() {
    this.records = this.loadAll()
  },
  
}
</script>

<template>
    <el-autocomplete
      v-model="state"
      :fetch-suggestions="querySearch"
      popper-class="lookup-field"
      placeholder="Nombre del producto"
      @select="handleSelect"

    >
      <template #suffix>
        <el-icon class="el-input__icon" @click="handleIconClick">
          <edit />
        </el-icon>
      </template>
      <template #default="{ item }">
        <div class="text-black underline font-bold">{{ item[firstLine] }}</div>
        <span class="text-black text-xs ">{{ item[secondLine] }}</span>
        <br />
        <span class="text-black text-xs ">{{ item[lastLine] }}</span>
      </template>
    </el-autocomplete>
  </template>
  
<style>
  .lookup-field li {
    line-height: normal;
    padding: 5px;
  }
  .lookup-field li .name {
    text-overflow: ellipsis;
    overflow: hidden;
  }
  .lookup-field li .addr {
    font-size: 12px;
    color: #b4b4b4;
  }
  .lookup-field li .highlighted .addr {
    color: #ddd;
  }
  
</style>
  