<template>
    <div>
      <el-steps :active="activeStep" finish-status="success">
        <el-step title="Subir CSV" />
        <el-step title="Asignar Columnas CSV a Campos" />
        <el-step title="Revisar y Insertar" />
      </el-steps>

      <!-- Step 1: Upload -->
      <div v-if="activeStep === 0">
        <el-upload
          class="upload-demo"
          drag
          :auto-upload="true"
          :before-upload="handleFileChange"
        >
          <i class="el-icon-upload"></i>
          <div class="el-upload__text">Arrastra o haz click para subir CSV</div>
        </el-upload>
      </div>

      <!-- Step 2: Map -->
      <div v-if="activeStep === 1">
        <h3>Asignar Columnas CSV a Campos</h3>
        <br/>
        <el-form :model="mapping">

            <center>
                <table class="w-8/12" v-for="(field, index) in modelFields" :key="index">
                    <tr class="border-b border-gray-300 ">
                        <td class="w-1/3 " align="right"><b>{{ field }}:</b></td>
                        <td class="w-2/3 " align="center">
                            <el-select v-model="mapping[index]" placeholder="Select CSV column">
                                <el-option v-for="(header, i) in csvHeaders" :key="i" :label="header" :value="header"/>
                            </el-select>
                        </td>
                    </tr>
                </table>
            </center>


        </el-form>
      </div>

      <center>
            <!-- Step 3: Insert -->
            <div v-if="activeStep === 2">
                <h3>Revisar y Insertar</h3>
                <el-button type="danger" @click="submitData">Insertar Registros</el-button>
            </div>

            <!-- Navigation Buttons -->
            <el-button @click="prevStep" :disabled="activeStep === 0">Atrás</el-button>
            <el-button type="danger" class="bg-red-600" @click="nextStep" v-if="activeStep < 2">Continuar</el-button>
        </center>
    </div>
  </template>
<script>
import Papa from 'papaparse'
import { ElMessage } from 'element-plus'
import { router } from '@inertiajs/vue3'

export default {
    name: 'ImporterWizard',
    props:{
        modelFields: Array
    },
    components:{
        Papa
    },
  data() {
    return {
      activeStep: 0,
      file: null,
      csvHeaders: [],
      csvData: [],
      mapping: {},

    }
  },
  methods: {
    handleFileChange(file) {
      this.file = file

      Papa.parse(file, {
        header: true,
        skipEmptyLines: true,
        complete: (results) => {
          this.csvHeaders = Object.keys(results.data[0] || {})
          console.log(this.csvHeaders);
          this.csvData = results.data
          ElMessage.success('CSV loaded successfully!')
          this.nextStep()
        },
        error: () => {
          ElMessage.error('Failed to parse CSV')
        }
      })
      console.log('file', this.file);
      return false // Prevent auto-upload
    },
    nextStep() {
      if (this.activeStep < 2) this.activeStep++
    },
    prevStep() {
      if (this.activeStep > 0) this.activeStep--
    },
    submitData() {
        console.log(this.csvData);
      const transformedData = this.csvData.map(row => {

        const item = {}
        for (const [key, value] of Object.entries(this.mapping)) {
            item[key] = row[value];
        }

        return item
      })

      console.log(transformedData);
      axios.post(route('importProducts'), {
        records: transformedData
      }).then(response => {
        console.log(response.data);
        ElMessage.success(response.data.message)
        this.activeStep = 0
      }).catch(error => {
        console.log(error);
        ElMessage.error('Failed to insert data.')
      })
    }
  },
  mounted() {
    console.log(this.modelFields);
  }

}
</script>
