<template>
    {{ hola }}
    <Bar
      id="my-chart-id"
      :options="chartOptions"
      :data="chartData"
    />
  </template>
  
  <script>
  import { Bar } from 'vue-chartjs';
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';
  
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
  
  export default {
    name: 'BarChart',
    components: { Bar },
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