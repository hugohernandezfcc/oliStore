<template>
    <div class="statistic-card">
        <el-statistic :value="'$ '+salesByStore[store_str] +' MXN'">
            <template #title>
                <div style="display: inline-flex; align-items: center">
                    Tienda:  <el-select v-model="store_str" placeholder="Select" size="small"  style="width: 100%;">
                        <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value" />
                    </el-select>
                </div>
            </template>
        </el-statistic>
        <div class="statistic-footer">
            <div class="footer-item">
                <span class="cursor-pointer underline text-red-600 " @click="openLink">{{titleFooter.title}}</span>
                <span class="green">
                    <!-- {{mainValueFooter}}% -->
                </span>
            </div>
        </div>
    </div>
</template>
<script >  


import { ElNotification } from 'element-plus';





export default {
  name: 'DashboardCardSales',
  components: {  ElNotification},
  props:{

    mainValueFooter: Number,
    title: String,
    titleFooter: Object
  },
  data(){
    return {
        link: '',
        options : [],
        paramLink: [],
        mount: 0,
        salesByStore: [],
        store_str: 'total',
    }
  },
  methods:{
    openLink(){
      this.link = '/sales/' + this.titleFooter.start+'/'+this.titleFooter.end+'/results/'+(this.paramLink[this.store_str] == undefined ? 3 : this.paramLink[this.store_str]);
      window.open(this.link, '_blank');
    }
  },
  mounted(){
    this.salesByStore['total'] = 0;


    axios.post(route('card.sales')).then((res) => {
    //   console.log(res);
      res.data['stores'].forEach((element) => {
          this.options.push({label: element.name, value: element.name});
          
          
          this.paramLink[element.name] = element.id;

      });
      
      res.data['sales'].forEach((element) => {
        if(this.salesByStore[element.store] == undefined)
            this.salesByStore[element.store] = parseFloat(element.total);
        else
            this.salesByStore[element.store] += parseFloat(element.total);

        this.salesByStore['total'] += parseFloat(element.total);
      });

      
    }).catch((error) => {
      console.log(error);
    });

    this.link = '/sales/' + this.titleFooter.start+'/'+this.titleFooter.end+'/results/'+this.paramLink[this.store_str];
    console.log(this.titleFooter);
    console.log(this.link);
  }
}
</script>

<style scoped>
:global(h2#card-usage ~ .example .example-showcase) {
  background-color: var(--el-fill-color) !important;
}

.el-statistic {
  --el-statistic-content-font-size: 28px;
}

.statistic-card {
  height: 100%;
  padding: 20px;
  border-radius: 4px;
  background-color: var(--el-bg-color-overlay);
}

.statistic-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  font-size: 12px;
  color: var(--el-text-color-regular);
  margin-top: 16px;
}

.statistic-footer .footer-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.statistic-footer .footer-item span:last-child {
  display: inline-flex;
  align-items: center;
  margin-left: 4px;
}

.green {
  color: var(--el-color-success);
}
.red {
  color: var(--el-color-error);
}
</style>