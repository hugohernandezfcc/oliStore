<template>
    <div class="statistic-card">
        <el-statistic :value="mainValue">
            <template #title>
                <div style="display: inline-flex; align-items: center">
                    {{title}}
                <el-tooltip
                    effect="dark"
                    content="Number of users who logged into the product in one day"
                    placement="top"
                >
                    <el-icon style="margin-left: 4px" :size="12">
                        <Warning />
                    </el-icon>
                </el-tooltip>
                </div>
            </template>
        </el-statistic>
        <div class="statistic-footer">
            <div class="footer-item">
                <span>{{titleFooter}}</span>
                <span class="green">
                    {{mainValueFooter}}%
                    <el-icon>
                        <CaretTop />
                    </el-icon>
                </span>
            </div>
        </div>
    </div>
</template>
<script >


import { ElNotification } from 'element-plus';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import BarChart from '@/Components/BarChart.vue';
import ChartPoligono from '@/Components/ChartPoligono.vue';
import Doughnut from '@/Components/Doughnut.vue';

export default {
  name: 'CardStatic',
  components: { BarChart, TextInput, InputLabel, ElNotification, InputError},
  props:{
    mainValue: Number,
    mainValueFooter: Number,
    title: String,
    titleFooter: String
  },
  data(){

        return {
            form:{
                quantityItems: []
            },
        }
    },
  computed: {
            
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