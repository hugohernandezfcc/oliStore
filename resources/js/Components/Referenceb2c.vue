<template>
    <div class="bg-green-600 text-white text-center font-bold rounded-full " >ESTE PRODUCTO TIENE UNA REFERENCIA EN B2C</div>
    <el-row :gutter="16">
      <el-col :span="8">
        <div class="statistic-card">
          <el-statistic :value=" `$${props.cost} MXN` ">
            <template #title>
              <div style="display: inline-flex; align-items: center">
                Precio de lista B2C
                <el-tooltip effect="dark" content="PRECIO DE LISTA AL QUE LO ADQUIRIMOS" placement="top">
                  <el-icon style="margin-left: 4px" :size="12"> <Warning /> </el-icon>
                </el-tooltip>
              </div>
            </template>
          </el-statistic>
          <div class="statistic-footer">
            <div class="footer-item">
              <!-- <span>than yesterday</span>
              <span class="green">
                24%
                <el-icon>
                  <CaretTop />
                </el-icon>
              </span> -->
            </div>
          </div>
        </div>
      </el-col>
      <el-col :span="8">
        <div class="statistic-card">
            <el-statistic :value=" `$${props.price} MXN` ">
            <template #title>
              <div style="display: inline-flex; align-items: center">
                Precio cliente B2C
                <el-tooltip effect="dark" content="PRECIO SUGERIDO AL CLIENTE" placement="top">
                  <el-icon style="margin-left: 4px" :size="12">
                    <Warning />
                  </el-icon>
                </el-tooltip>
              </div>
            </template>
          </el-statistic>
          <div class="statistic-footer">
            <div class="footer-item">
              <!-- <span>month on month</span>
              <span class="red">
                12%
                <el-icon>
                  <CaretBottom />
                </el-icon>
              </span> -->
            </div>
          </div>
        </div>
      </el-col>
      <el-col :span="8">
        <div class="statistic-card">
            <el-statistic :value=" `${parseFloat(props.porcentage).toFixed(2)} % ` ">
            <template #title>
              <div style="display: inline-flex; align-items: center">
                Porcentaje de ganancia
              </div>
            </template>
          </el-statistic>
          <div class="statistic-footer">
            <div class="footer-item">
              <!-- <span>than yesterday</span>
              <span class="green">
                16%
                <el-icon>
                  <CaretTop />
                </el-icon>
              </span> -->
            </div>
          </div>
        </div>
      </el-col>
    </el-row>
  </template>
  
  <script lang="ts" setup>
  import {
    ArrowRight,
    CaretBottom,
    CaretTop,
    Warning
  } from '@element-plus/icons-vue';
  import { defineProps } from 'vue';
  
  const props = defineProps({
    cost: {
      type: String,
      required: true
    },
    price: {
      type: String,
      required: true
    },
    porcentage: {
      type: String,
      required: true
    },
  });
  console.log(props);
  

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
  