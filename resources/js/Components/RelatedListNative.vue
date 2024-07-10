<template>

    <el-card :class="classCard" >
        <template #header>
          <div class="card-header">
            <div class="flex flex-row">
                <div class="basis-1/4">
                    <button @click="dialogFormVisible = true" v-if="showNewRecordButton">
                        nuevo
                    </button> 
                </div>
                <div class="basis-1/2">
                    <h2>{{ title }}</h2>
                </div>
                <div class="basis-1/2">
                    <el-input v-model="search"  placeholder="Type to search" class="shadow-2xl"/>
                </div>
            </div>
          </div>
        </template>
        <el-table :data="filterTableData" class="shadow-lg -m-5" stripe style="width: 100%; height: 400px;" v-loading="loading">
            <el-table-column align="left" width="70" fixed="left">
                <template #default="scope">
                    <inertia-link :href="route(table+'.show', scope.row.id)" class="transition transform hover:scale-105 focus:scale-95 active:scale-90">
                        <el-button size="small" color="#dc2626" > 
                            <svg class="h-5 w-5 text-white " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M512 160c320 0 512 352 512 352S832 864 512 864 0 512 0 512s192-352 512-352m0 64c-225.28 0-384.128 208.064-436.8 288 52.608 79.872 211.456 288 436.8 288 225.28 0 384.128-208.064 436.8-288-52.608-79.872-211.456-288-436.8-288zm0 64a224 224 0 1 1 0 448 224 224 0 0 1 0-448m0 64a160.192 160.192 0 0 0-160 160c0 88.192 71.744 160 160 160s160-71.808 160-160-71.744-160-160-160"></path></svg>
                        </el-button>
                    </inertia-link>&nbsp;
                    <el-button size="small" @click="deleteRecord(scope.row._id)" v-if="scope.row._id != null" class="transition transform hover:scale-105 focus:scale-95 active:scale-90"> 
                        <svg class="h-5 w-5 text-black " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" ><path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path></svg>
                    </el-button>
                </template>
            </el-table-column>
            
            <el-table-column v-for="field in visibleColumns"  :label="field.label" :key="field.prop" :width="field.width" sortable>
                <template #default="scope">
                    <span v-if="field.prop == 'status'">
                        <el-tag :type="(scope.row[field.prop] == 'close') ? 'danger' : 'primary'">{{scope.row[field.prop]}}</el-tag>
                    </span>
                    <span v-if="field.type == 'currency'">
                        <b>$ {{ formatCurrency(scope.row[field.prop]) }}</b>
                    </span>
                    <span v-if="field.type == 'text' && field.prop != 'status'">
                        {{scope.row[field.prop]}}
                    </span>
                    <!--  -->
                </template>                       
            </el-table-column>
        </el-table>
      </el-card>
    
    </template>
    <style>
        .el-collapse-item__header{
            background-color: rgb(255, 214, 214);
            padding: 5px;
            margin-bottom: 5px;
        }
        .card-header{
            margin: -10px;    
            background-color: rgb(255, 214, 214);
        }
        .card-header h2{
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 5px;
    
        }
        .el-table{
            max-width: 105%;
            width: 105% !important;
        }
        .card-header {
            padding: 3px;
        }
        .card-header button{
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #dc2626;
            color: white;
            padding-left:5%;
            padding-right:5%;
            border-radius: 5px;
            margin: 5px;
        }
    </style>
    <script>
    import axios from 'axios';
    import LookupField from './LookupField.vue';
    import { ElMessage } from 'element-plus';
    
    export default {
        name: 'RelatedListNative',
        components: {
            LookupField, ElMessage
        },
        props: {
            customRecordsRelated: {
                type: Array
            },
            formFields: {
                type: Array
            },
            visibleColumns: {
                type: Array
            },
            classCard: {
                type: String
            },
            title: {
                type: String
            },
            titleModel: {
                type: String
            },
            table: {
                type: String
            },
            origin: {
                type: String
            },
            origin_field: {
                type: String
            },
            currentRecordId:{
                type: Number
            },
            showNewRecordButton:{
                type: Boolean
            }
        },
        data() {
            return {
                loading: true,
                showPosts: false,
                dialogFormVisible: false,
                search: '',
                toRenderValues: [],
                formLabelWidth: '140px',
                activeAction: ['1'],
                form: [],
                recordToRelate: null,
                detailToRelate:[],
                renderDetails : false,
                justCreateRelationship: false
            }
        },
        methods: {
            deleteRecord(id) {
                this.loading = true;

                axios.delete(route('relatedlist.delete', id)).then(response => {
                    console.log(response.data);
                    window.location.reload();
                    this.loading = false;
                    
                }).catch(error => {
                    console.log(error)
                });
            },
            formatCurrency(value) {
                if (!value) return '';
                return parseFloat(value).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            },
            closeFormDialog() {
                this.dialogFormVisible = false;
            },
            confirmFormDialog() {
                
    
                let record = {};
                for (let i = 0; i < this.form.length; i++) {
                    record[this.form[i].prop] = this.form[i].value;
                }
    
                record.table             = this.table;
                record.origin            = this.origin;
                record.origin_field      = this.origin_field;
                record.target_field      = this.target_field;
                record.currentRecordId   = this.currentRecordId;
                
                if(this.justCreateRelationship)
                    record.target_id         = this.recordToRelate.id;   
    
                record.justCreateRelationship = this.justCreateRelationship;
                axios.post(route('relatedlist.store'), record).then(response => {
                    console.log(response.data)
                    for (let i = 0; i < this.form.length; i++) {
                        this.form[i].value = '';
                    }
                    this.justCreateRelationship = false;
                    
                    ElMessage({
                        showClose: true,
                        message: 'Registro creado correctamente',
                        type: 'success',
                    })
    
                    this.toRenderValues.push(response.data[0]);
    
                }).catch(error => {
                    console.log(error)
                });
                this.dialogFormVisible      = false;
                console.log(record);
            }
        },
        computed: {
            filterTableData() {
                return this.toRenderValues.filter(
                    (data) =>
                    !this.search || JSON.stringify(data).toLowerCase().includes(this.search.toLowerCase() )
                );
            },
        },
        mounted() {
            for (let i = 0; i < this.formFields.length; i++) {
                this.detailToRelate.push(this.formFields[i].prop);
                let field = this.formFields[i];
                field.value = '';
    
                if(field.prop != 'id')
                    this.form.push(field);
            }
            setTimeout(() => {
                if(this.customRecordsRelated !== undefined){
                    console.log('FromRelatedListNative', this.customRecordsRelated);
                    console.log('visibleColumns', this.visibleColumns);

                    for (let i = 0; i < this.customRecordsRelated.length; i++) {
                        let customRecord = {}
                        
                        for (let j = 0; j < this.visibleColumns.length; j++) 
                            customRecord[this.visibleColumns[j].prop] = this.customRecordsRelated[i][this.visibleColumns[j].prop];
            
                        this.toRenderValues.push(this.customRecordsRelated[i]);
                    }
                }
                this.loading = false;
            }, 1500);
        }
    }
    </script>