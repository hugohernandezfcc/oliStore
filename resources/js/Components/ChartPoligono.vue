<template>

    <Line
      id="my-chart-id"
      :options="chartOptions"
      :data="chartData"
    />
  </template>
  
  <script>
  import { Line } from 'vue-chartjs';
  import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js';
  
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
)
  
  export default {
    name: 'LineChart',
    components: { Line },
    props:{
        labels: Array,
        datasets: Array
    },
    data() {
      return {
        chartData: {
            
            labels: this.labels,
            datasets: this.datasets
        },
        chartOptions: {
          responsive: true
        }
      }
    }
  }
  </script>