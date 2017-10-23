<template >

  <section class="departments">
        <el-row :justify="center" :align="middle">

            <el-col :span="6" :offset="9">
                <el-card class="root-card">
                    <div slot="header" class="header-card">
                        <span style="line-height: 36px;">{{department_root.name}}</span>

                    </div>
                    <el-row>
                        <el-col :span="6">
                            <img :src="department_root.lead_photo" class="my-avatar"></img>
                        </el-col>
                        <el-col :span="18">
                            <p>{{department_root.lead_fio}}</p>
                            <p>{{department_root.lead_position}}</p>
                        </el-col>
                    </el-row>
                </el-card>
            </el-col>
            <el-col :offset="9"></el-col>
        </el-row>
      <el-row :justify="center" :align="middle">
          <el-col :span="12" :offset="6">
              <div style="text-align: center"><i class="el-icon-caret-bottom"></i></div></el-col>
      </el-row>
      <el-row :justify="center" :align="top">
          <el-col :span="16" :offset="4" >
              <el-tree :data="department_tree" :props="defaultProps" @node-click="handleNodeClick"  ></el-tree>
          </el-col>
          <el-col :offset="4"></el-col>
      </el-row>
      <!--<table  class="table table-bordered table-hover table-responsive">-->
          <!--<thead class="table-head">-->
          <!--<th>#</th>-->
          <!--<th>Department Name</th>-->
          <!--<th v-if="department_items_control.length >0">Controls</th>-->
          <!--</thead>-->
          <!--<tbody>-->
          <!--<tr v-for="item in department_items">-->
              <!--<th scope="row">{{item.id}}</th>-->

              <!--<td>{{item.name}}</td>-->
              <!--<td  v-if="department_items_control.length >0" >-->
                  <!--<el-button v-if="can_department('edit')" size="small" type="success" @click="departmentEdit(item)">-->
                      <!--Edit-->
                  <!--</el-button>-->
                  <!--&lt;!&ndash;<el-button v-if="can_department('delete')" size="small" type="danger"  @click="departmentDeactivate(item)">&ndash;&gt;-->
                  <!--&lt;!&ndash;Deactivate&ndash;&gt;-->
                  <!--&lt;!&ndash;</el-button>&ndash;&gt;-->
              <!--</td>-->

          <!--</tr>-->
          <!--</tbody>-->
      <!--</table>-->

  </section>

</template>

<script >
import axios from 'axios'

  export default  {
    name: 'departments',
    props: [],
    mounted() {
        this.getDepartments();
    },
    data() {
      return {
          department_items: [
              {"id":1,"name":'one'},
              {"id":2,"name":'two'},
              {"id":3,"name":'three'},
          ],
          department_items_control:[],
          department_root:{
              name:'OLTA-travel',
             // lead_photo:'https://cdn.bitrix24.ru/b2480613/main/6db/6dbe2b0b193d64e7b47dc6f0a75c3a4d/20150414_b_olta_283.JPG',
              lead_photo:'images/avatar/20150414_b_olta_283.JPG',
              lead_fio:'Мировщикова Татьяна',
              lead_position:'Директор'
          },
          department_users:[],
          department_cur_id:'',
          department_tree:[
              {
                  label: 'Отдел маркетинга',
                  dep_type:'dep',
                  children: [{
                      label: 'Сотрудники',
                      dep_type:'users',
                      dep_id:1,
                      children: [{
                          label: 'Имя 1-1-1'
                      }]
                  }]
              }, {
                  label: 'Отдел продаж',
                  dep_type:'dep',
                  children: [{
                      label: 'Account',
                      dep_type:'dep',
                      children: [{
                          label: 'Сотрудники',
                          dep_type:'users',
                          dep_id:3,
                      }]
                  }, {
                      label: 'Sales Development',
                      dep_type:'dep',
                      children: [{
                          label: 'Сотрудники',
                          dep_type:'users',
                          dep_id:10,
                          children: [{
                                label: 'Name-1'
                          }],
                      }]
                  }]
              }, {
                  label: 'Разработка ПО',
                  dep_type:'dep',
                  children: [{
                      label: 'Сотрудники',
                      dep_type:'users',
                      dep_id:5,
                      children: [{
                          label: 'Бабаев Александр'
                      },{
                          label: 'AndreyM'
                      }
                      ]
                  }]
              }, {
                  label: 'Отдел Бронирования',
                  dep_type:'dep',
                  children: [{
                      label: 'Сотрудники',
                      dep_type:'users',
                      dep_id:6,
                      children: [{
                          label: 'Имя-1'
                      }]
                  }]
              }, {
                  label: 'Бухгалтерия',
                  dep_type:'dep',
                  children: [{
                      label: 'Сотрудники',
                      dep_type:'users',
                      dep_id:7,
                      children: [{
                          label: 'Имя бухгалтер-1'
                      }]
                  }]

              }, {
                  label: 'Удаленные сотрудники',
                  dep_type:'dep',
                  children: [{
                      label: 'Сотрудники',
                      dep_type:'users',
                      dep_id:9,
                      children: [{
                          label: 'Имя сотрудника-1'
                      },
                      {
                          label: 'Имя сотрудника-2'
                      }
                      ]
                  }]
              }, {
                  label: 'MSK OLTA/TF',
                  dep_type:'dep',
                  children: [{
                      label: 'TF',
                      dep_type:'dep',
                      dep_id:11,
                      children: [{
                          label: 'Сотрудники',
                          dep_type:'users',
                      }]
                  }, {
                      label: 'Бухгалтерия MSK',
                      dep_type:'dep',
                      dep_id:11,
                      children: [{
                          label: 'Сотрудники',
                          dep_type:'users',
                      }]
                  }]
              }
          ]
      }
    },
    methods: {
        handleNodeClick(data) {
            if (data.dep_type =='users') {
                let res=this.getDepartmentUsers(data.dep_id);
                console.log(res);

            }
            console.log('data.dep_type='+data.dep_type);
            console.log('data.$treeNodeId='+data.$treeNodeId);
            if (data.children != undefined) {
                console.log('data.children.length='+data.children.length);
            }

        },
        async getDepartments(){
            console.log('Get departments...');
            //const { data } = await axios({method:'post',url:'/api/users',withcredantial:true});
            try {
                const { data } = await axios.post('/api/departments');

                this.department_items =data.data;
                this.department_items_control =data.controls;
                //console.log('getUsers'+this.items);
            } catch(e) {
                this.items=[{'id':1,'name':'error'}];
            }

            // Fetch the user.
            //await this.$store.dispatch('fetchDepartments',{departments:data.data});
        },
        async getDepartmentUsers(department_id){
            console.log('Get department Users...');
            this.department_cur_id =department_id;
            //const { data } = await axios({method:'post',url:'/api/users',withcredantial:true});
            try {
                var params = new URLSearchParams();
                params.append('department_id', department_id);
                const { data } = await axios.post('/api/departments/getusers',params,{withCredantial:true});
                if (data.error != undefined) {
                    this.$notify.error({
                        title: 'Error',
                        message: data.error //data.error_description
                    });
                } else {
                    this.department_users =data;
                }

                return data;

                //console.log('getUsers'+this.items);
            } catch(e) {
                this.items=[{'id':1,'name':'error'}];
            }

            // Fetch the user.
            //await this.$store.dispatch('fetchDepartments',{departments:data.data});
        },
        makeTreeItems() {
            root_item = this.department_items.find(function(item){if (item.parent==0) return item;});
            department_tree=[];
            root_child_arr =this.department_items.map(function(item){if (item.parent==root_item.id) return item;});
        },
//        renderTreeContent(h, { node, data, store }) {
//            return (
//                <span>
//                    <span>
//                        <span><strong>{node.label}</strong></span>
//                    </span>
//                </span>
//        )
//        },
        departmentEdit(item) {

        },
        departmentDeactivate(item) {

        },
        can_department(action) {
            if (this.department_items_control.indexOf(action) !=-1) {
                return true
            }
            return false;
        },
    },
    computed: {

    }
}
</script>

<style >
.departments {
    background-color: white;
    padding-top: 10px;
    padding-bottom: 10px;
}
.box-card {
    width: 180px;
}
.my-avatar {
    border-radius: 50%;
    display: inline-block;
    float: left;
    height: 50px;
    margin: 10px;
    width: 50px;
}
</style>
