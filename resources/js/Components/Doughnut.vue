<template>

    <Doughnut
      id="my-chart-id"
      :options="chartOptions"
      :data="chartData"
    />
  </template>
  
  <script>
    import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
    import { Doughnut } from 'vue-chartjs';
    
    ChartJS.register(ArcElement, Tooltip, Legend)
  
    export default {
        name: 'App',
        components: {
            Doughnut
        },
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