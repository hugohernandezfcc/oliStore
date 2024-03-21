<script>
import { Edit } from '@element-plus/icons-vue';

export default {
  components: {
    Edit
  },
  props:{
      path: String,
      
  },
  data() {
    return {
      state: '',
      links: [],
    } 
  },
  methods: {
    querySearch(queryString, cb) {
      const results = queryString
        ? this.links.filter(this.createFilter(queryString))
        : this.links
      cb(results)
    },
    createFilter(queryString) {
      return (linkItem) => {
        return linkItem.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0
      }
    },
    handleSelect(item) {
      console.log(item)
    },
    handleIconClick(ev) {
      console.log(ev)
    },
    loadAll() {
      return [
        { value: 'vue', link: 'https://github.com/vuejs/vue' },
        { value: 'element', link: 'https://github.com/ElemeFE/element' },
        { value: 'cooking', link: 'https://github.com/ElemeFE/cooking' },
        { value: 'mint-ui', link: 'https://github.com/ElemeFE/mint-ui' },
        { value: 'vuex', link: 'https://github.com/vuejs/vuex' },
        { value: 'vue-router', link: 'https://github.com/vuejs/vue-router' },
        { value: 'babel', link: 'https://github.com/babel/babel' }
      ]
    }
  },
  mounted() {
    this.links = this.loadAll()
  },
  
}
</script>

<template>
    <el-autocomplete
      v-model="state"
      :fetch-suggestions="querySearch"
      popper-class="my-autocomplete"
      placeholder="Please input"
      @select="handleSelect"
    >
      <template #suffix>
        <el-icon class="el-input__icon" @click="handleIconClick">
          <edit />
        </el-icon>
      </template>
      <template #default="{ item }">
        <div class="value">{{ item.value }}</div>
        <span class="link">{{ item.link }}</span>
      </template>
    </el-autocomplete>
  </template>
  
<style>
  .my-autocomplete li {
    line-height: normal;
    padding: 7px;
  }
  .my-autocomplete li .name {
    text-overflow: ellipsis;
    overflow: hidden;
  }
  .my-autocomplete li .addr {
    font-size: 12px;
    color: #b4b4b4;
  }
  .my-autocomplete li .highlighted .addr {
    color: #ddd;
  }
</style>
  