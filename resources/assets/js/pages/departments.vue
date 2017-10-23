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
          <el-col :span="12" :offset="6" >
              <el-tree :data="department_tree" :props="defaultProps"  node-key="id" @node-click="handleNodeClick" :render-content="renderTreeContent" ></el-tree>
          </el-col>
          <el-col :offset="6"></el-col>
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
                  id:1,
                  label: 'Отдел маркетинга',
                  dep_type:'dep',
                  children: [{
                      id:12,
                      label: 'Сотрудники',
                      dep_type:'users',
                      dep_id:1,
                      children: [{
                          label: 'Имя 1-1-1'
                      }]
                  }]
              }, {
                  id:2,
                  label: 'Отдел продаж',
                  dep_type:'dep',
                  children: [{
                      id:13,
                      label: 'Account',
                      dep_type:'dep',
                      children: [{
                          id:21,
                          label: 'Сотрудники',
                          dep_type:'users',
                          dep_id:3,
                      }]
                  }, {
                      id:14,
                      label: 'Sales Development',
                      dep_type:'dep',
                      children: [{
                          id:22,
                          label: 'Сотрудники',
                          dep_type:'users',
                          dep_id:10,
                          children: [{
                                label: 'Name-1'
                          }],
                      }]
                  }]
              }, {
                  id:3,
                  label: 'Разработка ПО',
                  dep_type:'dep',
                  children: [{
                      id:15,
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
                  id:4,
                  label: 'Отдел Бронирования',
                  dep_type:'dep',
                  children: [{
                      id:16,
                      label: 'Сотрудники',
                      dep_type:'users',
                      dep_id:6,
                      children: [{
                          label: 'Имя-1'
                      }]
                  }]
              }, {
                  id:5,
                  label: 'Бухгалтерия',
                  dep_type:'dep',
                  children: [{
                      id:17,
                      label: 'Сотрудники',
                      dep_type:'users',
                      dep_id:7,
                      children: [{
                          label: 'Имя бухгалтер-1'
                      }]
                  }]

              }, {
                  id:6,
                  label: 'Удаленные сотрудники',
                  dep_type:'dep',
                  children: [{
                      id:18,
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
                  id:7,
                  label: 'MSK OLTA/TF',
                  dep_type:'dep',
                  children: [{
                      id:19,
                      label: 'TF',
                      dep_type:'dep',
                      dep_id:11,
                      children: [{
                          id:23,
                          label: 'Сотрудники',
                          dep_type:'users',
                      }]
                  }, {
                      id:20,
                      label: 'Бухгалтерия MSK',
                      dep_type:'dep',
                      dep_id:11,
                      children: [{
                          id:24,
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
            return;
//            if (data.dep_type =='users') {
//                let res=this.getDepartmentUsers(data.dep_id);
//                console.log(res);
//
//            }
//            console.log('data.dep_type='+data.dep_type);
//            console.log('data.$treeNodeId='+data.$treeNodeId);
//            if (data.children != undefined) {
//                console.log('data.children.length='+data.children.length);
//            }

        },
        async getDepartments(){
            console.log('Get departments...');
            //const { data } = await axios({method:'post',url:'/api/users',withcredantial:true});
            try {
                const { data } = await axios.post('/api/departments',{withCredantial:true});

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
                this.department_users=[{'id':1,'name':'error'}];
            }

            // Fetch the user.
            //await this.$store.dispatch('fetchDepartments',{departments:data.data});
        },
        makeTreeItems() {
            root_item = this.department_items.find(function(item){if (item.parent==0) return item;});
            department_tree=[];
            root_child_arr =this.department_items.map(function(item){if (item.parent==root_item.id) return item;});
        },
        renderTreeContent(h, { node, data, store }) {
          if (data.dep_type=='users') {
              return (
                  <span>
                    <span>
                        <span>{node.label}</span>
                          <span  style="float: left; margin-left: 20px">
                              <el-button size="mini" on-click={ () => this.showDepartmentUsers(store, data) }>Show</el-button>
                          </span>
                    </span>

                    </span>
          );
          } else {
              return (
                  <span>
                        <span>
                          <span>{node.label}</span>
                        </span>
                    </span>
          );
          }

        },
        showDepartmentUsers(store, data) {
            console.log('showDepartmentUsers data.department_id=',data.dep_id);
            console.log('Tree store.data=',store.data);
            console.log('Tree store.data.length='+store.data.length);
            //if (store.data.length >0) {
                //store=[];
                let self=this;
                let data_res=this.getDepartmentUsers(data.dep_id);
                console.log("getDepartmentUsers done,",data_res);
                console.log("getDepartmentUsers self.department_users=,",self.department_users);
                console.log("getDepartmentUsers self.department_users[0]['userfio']=,",self.department_users[0]['userfio']);
                for (let i=0;i <= self.department_users.length-1;i++) {
                    store.append({id: data.id++,user_id:self.department_users[i]['id'],dep_type:'user',label:self.department_users[i]['userfio'],children: []})
                }

            //}

            //store.append({ id: id++, label: 'testtest', children: [] }, data);
        },
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
