<template>
    <div class="content">

        <el-tabs v-model="activeTabName" @tab-click="handleTabClick">
            <el-tab-pane label="Users" name="users">
                <el-row>
                    <el-col :span="24">
                        <div class="grid-content bg-purple-dark">
                            <el-button
                                    type="primary"
                                    @click="createNew()">Create new
                            </el-button>
                            <el-button
                                    type="primary"
                                    @click="checkNewBitrixUser()">B-Sync users
                            </el-button>
                        </div>
                    </el-col>

                </el-row>

                <data-tables :data="items" :table-props="tableProps"  :search-def="searchDef"   :action-col-def="actionColDef" :checkbox-filter-def="checkboxFilterDef">
                    <!--<el-table-column v-for="title in titles" :prop="title.prop" :label="title.label" :key="title.label" sortable="custom">-->
                    <el-table-column
                    label="avatar"
                    width="120">
                        <template scope="scope">
                                <img  v-if="scope.row.photo != ''" class="my-avatar" :src="'images/avatar/'+scope.row.avatar"></img>
                                <img v-else class="my-avatar" src="images/default-avatar.png"></img>

                        </template>
                    </el-table-column>
                    <el-table-column
                        label="Fio"
                        label-class-name="fio-class"
                        width="260">
                        <template scope="scope">
                            <el-popover trigger="click" placement="top">
                                <p><el-icon name="information" style="display: inline-block;float:left"></el-icon></p>
                                <p><strong>Id:</strong> {{ scope.row.id }}</p>
                                <p><strong>B-Id:</strong> {{ scope.row.bitrix_id }}</p>
                                <p><strong>Name:</strong> {{ scope.row.name }}</p>
                                <p v-if="scope.row.birthday != ''"><strong>Birthday:</strong> {{ scope.row.birthday}}</p>
                                <p v-if="scope.row.address != ''"><strong>Address:</strong> {{ scope.row.address}}</p>
                                <p v-if="scope.row.info != ''"><strong>Info:</strong> {{ scope.row.info}}</p>
                                <div slot="reference" class="name-wrapper">
                                <el-tag>{{ scope.row.userfio }}</el-tag>
                                </div>
                            </el-popover>
                        <span style="margin-left: 10px">{{ scope.row.position }}</span>
                        </template>

                        </el-table-column>
                        <el-table-column
                            label="Info"
                            width="320">
                            <template scope="scope">
                                <p>Email: <span class="personal-contact">{{ scope.row.email }}</span></p>
                                <p v-if="scope.row.skype">Skype: <span class="personal-contact">{{ scope.row.skype }}</span></p>
                                <p v-if="scope.row.tel">Tel: <span class="personal-contact">{{ scope.row.tel }}</span></p>
                                <p v-if="scope.row.tel_work">Tel work: <span class="personal-contact">{{ scope.row.tel_work }}</span></p>
                                <p v-if="scope.row.interests">Interests: <span class="personal-contact">{{ scope.row.interests }}</span></p>
                                <p>Department: <span class="personal-contact">{{ scope.row.department }}</span></p>

                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Department"
                            prop="department"
                            sortable
                            width="210">
                            <template scope="scope">
                                <el-icon name="document"></el-icon>
                                <span style="margin-left: 10px">{{ scope.row.department }}</span>
                                <p v-if="scope.row.head ==1" class="leader-wrap"><span class="leader">Leader</span></p>
                            </template>
                        </el-table-column>
                </data-tables>

            </el-tab-pane>
            <el-tab-pane label="Company structure" name="departments">
                <departments></departments>

            </el-tab-pane>

        </el-tabs>

        <el-row>


            <!--<el-col :span="24">-->
                <!--<div class="grid-content bg-purple-dark">-->
                    <!--<el-radio-group v-model="users_view">-->
                        <!--<el-radio-button label="Users"></el-radio-button>-->
                        <!--<el-radio-button label="Departments"></el-radio-button>-->
                    <!--</el-radio-group>-->
            <!--</div>-->
            <!--</el-col>-->


        </el-row>

    <!--<el-table v-if="users_view =='Users'"-->
            <!--:data="items"-->
            <!--border-->
            <!--style="width: 100%"-->
            <!--:row-class-name="tableRowClassName" ref="users-table" >-->

        <!--<el-table-column-->
                <!--label="avatar"-->
                <!--width="120">-->
            <!--<template scope="scope">-->
                <!--<img  v-if="scope.row.photo != ''" class="my-avatar" :src="scope.row.photo"></img>-->
                <!--<img v-else class="my-avatar" src="images/default-avatar.png"></img>-->
                <!--&lt;!&ndash;<img class="my-avatar" :src="scope.row.photo"></img>&ndash;&gt;-->
            <!--</template>-->
        <!--</el-table-column>-->
        <!--<el-table-column-->
                <!--label="Fio"-->
                <!--width="250">-->
            <!--<template scope="scope">-->
                <!--<el-popover trigger="hover" placement="top">-->
                    <!--<p><el-icon name="information" style="display: inline-block;float:left"></el-icon>Id: {{ scope.row.id }}</p>-->
                    <!--<p><strong>Name:</strong> {{ scope.row.name }}</p>-->
                    <!--<p v-if="scope.row.birthday != ''"><strong>Birthday:</strong> {{ scope.row.birthday}}</p>-->
                    <!--<p v-if="scope.row.address != ''"><strong>Address:</strong> {{ scope.row.address}}</p>-->
                    <!--<p v-if="scope.row.info != ''"><strong>Info:</strong> {{ scope.row.info}}</p>-->
                    <!--<div slot="reference" class="name-wrapper">-->
                        <!--<el-tag>{{ scope.row.userfio }}</el-tag>-->
                    <!--</div>-->
                <!--</el-popover>-->
                <!--<span style="margin-left: 10px">{{ scope.row.position }}</span>-->
            <!--</template>-->

        <!--</el-table-column>-->

        <!--<el-table-column-->
                <!--label="Info"-->
                <!--width="300">-->
            <!--<template scope="scope">-->

                <!--<p>Email:<span class="personal-contact">{{ scope.row.email }}</span></p>-->

                <!--<p v-if="scope.row.skype">Skype:<span class="personal-contact">{{ scope.row.skype }}</span></p>-->
                <!--<p v-if="scope.row.tel">Tel:<span class="personal-contact">{{ scope.row.tel }}</span></p>-->
                <!--<p v-if="scope.row.tel_work">Tel work:<span class="personal-contact">{{ scope.row.tel_work }}</span></p>-->
                <!--<p>Department:<span class="personal-contact">{{ scope.row.department }}</span></p>-->

            <!--</template>-->
        <!--</el-table-column>-->
        <!--<el-table-column-->
                <!--label="Department"-->
                <!--prop="department"-->
                <!--sortable-->
                <!--width="180">-->
            <!--<template scope="scope">-->
                <!--<el-icon name="document"></el-icon>-->
                <!--<span style="margin-left: 10px">{{ scope.row.department }}</span>-->
            <!--</template>-->
        <!--</el-table-column>-->



        <!--<el-table-column-->
                <!--label="Operations">-->
            <!--<template scope="scope">-->
                <!--&lt;!&ndash;<el-button v-if="can('edit')" size="small" type="success" @click="userEdit(scope.$index, scope.row)">&ndash;&gt;-->
                    <!--&lt;!&ndash;Edit&ndash;&gt;-->
                <!--&lt;!&ndash;</el-button>&ndash;&gt;-->
                <!--&lt;!&ndash;<el-button v-if="can('delete')" size="small" type="danger"  @click="userDeactivate(scope.$index, scope.row)">&ndash;&gt;-->
                    <!--&lt;!&ndash;Deactiv&ndash;&gt;-->
                <!--&lt;!&ndash;</el-button>&ndash;&gt;-->
                <!--&lt;!&ndash;<el-button size="small" type="info">&ndash;&gt;-->
                    <!--&lt;!&ndash;Tasks&ndash;&gt;-->
                <!--&lt;!&ndash;</el-button>&ndash;&gt;-->
                <!--<el-button size="small" type="info" @click="userBitrixSync(scope.row.id)">-->
                    <!--B-Sync-->
                <!--</el-button>-->
            <!--</template>-->
        <!--</el-table-column>-->
    <!--</el-table>-->

        <!--EDIT  Dialogs-->
        <el-dialog :title="'Edit user: #'+change_user_id" :visible.sync="dialogFormVisible">
            <el-form :model="form" ref="editForm" >
                <el-input type="hidden" :value="change_user_id"/>

                <el-form-item label="Name" :label-width="formLabelWidth" prop="name">
                    <el-input v-model="form.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="Fio" :label-width="formLabelWidth">
                    <el-input v-model="form.userfio" auto-complete="off"></el-input>
                </el-form-item>
                <!-- email -->
                <el-form-item label="Email" :label-width="formLabelWidth">
                    <el-input  v-model="form.email"></el-input>
                </el-form-item>
                <el-form-item label="Position" :label-width="formLabelWidth">
                    <el-input  v-model="form.position"></el-input>
                </el-form-item>
                <el-form-item label="Department" :label-width="formLabelWidth">
                    <el-select v-model="form.department_id" placeholder="Please select a department">
                        <el-option v-for="item in department_items"  :key="item.id" :label="item.name" :value="item.id"></el-option>

                    </el-select>
                </el-form-item>
                <!-- tel -->
                <el-form-item label="Tel" :label-width="formLabelWidth">
                    <el-input  v-model="form.tel"></el-input>
                </el-form-item>
                <!-- tel work-->
                <el-form-item label="Tel work:" :label-width="formLabelWidth">
                    <el-input  v-model="form.tel_work"></el-input>
                </el-form-item>
                <el-form-item label="Information" :label-width="formLabelWidth">
                    <el-input v-model="form.info" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="Active" :label-width="formLabelWidth">
                    <el-checkbox v-model="form.active"></el-checkbox>
                </el-form-item>
            </el-form>
             <span slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">Cancel</el-button>
                <!--<el-button type="primary" @click="dialogFormVisible = false">Save</el-button>-->
                <el-button type="primary" @click="submitForm('editForm')">Save</el-button>
             </span>
        </el-dialog>
        <el-dialog
                :title="'Deactivate: #'+change_user_id"
                :visible.sync="dialogDeactivateVisible"
                size="tiny"
                >
            <span>Deactivate user:({{form.name}})?</span>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogDeactivateVisible = false">No</el-button>
                <el-button type="primary" @click="userDeactivateOnServer">Yes</el-button>
            </span>
        </el-dialog>
        <create-new-dialog ref="createNewDialog" v-bind:type="users_view" v-bind:department_items="department_items" :user_id="change_user_id"></create-new-dialog>
        <notify></notify>
 </div>
</template>

<script>

import axios from 'axios'

import CreateNewDialog from'~/components/CreateNewDialog'
import departments from'~/pages/departments'
//import { Notification } from 'element-ui'

//import Vue from 'vue'
//Vue.use(Notification)

export default {
  components:{
      CreateNewDialog,departments
  },
  metaInfo () {
    return { title: this.$t('users') }
  },
  created() {
      self=this;
      this.$notify.duration=0;
      this.$notify.visible=false;
      this.$notify.close();
      this.$bus.$on('created-new',function(params){
          console.log('1)EVENT created:',params);
          try {
              if (self.users_view =='Departments') {
                  console.log('1.1)EVENT self.getDepartments:');
                  self.getDepartments();
              }
              if (self.users_view =='Users') {
                  self.getUsers();
              }
              self.$refs.createNewDialog.cancel();
          } catch (e) {
              console.error('$ON create-new',e.name);
          }


          console.log('2)EVENT created:');

      })
  },
  mounted() {
    this.$notify.close();
    this.getUsers();
    this.getDepartments();
  },
  data() {
    return {
        activeTabName:'users',
        one_items:[
            {"id":"1","name":"admin.","email":"admin@oltatravel.ru","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"admin","tel":null,"tel_work":null,"skype":null,"position":"admin","department":"admin","info":null,"active":"1"},
        ],
        items: [
            {"id":"1","name":"clairemarine.","email":"clairemarine.berthelot@oltatravel.ru","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"Berthelot Claire-Marine","tel":null,"tel_work":null,"skype":null,"position":"\u043c\u0430\u0440\u043a\u0435\u0442\u043e\u043b\u043e\u0433","department":"\u041e\u0442\u0434\u0435\u043b \u043c\u0430\u0440\u043a\u0435\u0442\u0438\u043d\u0433\u0430","info":null,"active":"1"},
            {"id":"2","name":"Gregoire.Choi","email":"Gregoire.Choiselat@oltatravel.ru","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"Choiselat Gregoire","tel":"+33 6 38 95 89 07 (Whatsapp)","tel_work":null,"skype":null,"position":"\u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440 \u043f\u043e \u043f\u0440\u043e\u0434\u0430\u0436\u0430\u043c","department":"\u041e\u0442\u0434\u0435\u043b \u043f\u0440\u043e\u0434\u0430\u0436","info":null,"active":"1"},
            {"id":"3","name":"carlos.garcia","email":"carlos.garcia@oltatravel.ru","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"Garcia Carlos","tel":"+7 911 107 4950","tel_work":null,"skype":null,"position":"\u041c\u0435\u043d\u0435\u0434\u0436\u0435\u0440 \u043e\u0442\u0434\u0435\u043b\u0430 \u043f\u0440\u043e\u0434\u0430\u0436","department":"Account","info":null,"active":"1"},
            {"id":"4","name":"anna.zevakina","email":"anna.zevakina@oltatravel.ru","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"\u0410\u0431\u0440\u0430\u043c\u0435\u043d\u043a\u043e \u0410\u043d\u043d\u0430","tel":"79111697263","tel_work":null,"skype":"anna.zevakina","position":null,"department":"\u041e\u0442\u0434\u0435\u043b \u043f\u0440\u043e\u0434\u0430\u0436","info":null,"active":"1"},
            {"id":"5","name":"eminat.agaspar","email":"eminat.agasparova@toursfrance.ru","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"\u0410\u0433\u0430\u0441\u043f\u0430\u0440\u043e\u0432\u0430 \u0415\u043c\u0438\u043d\u0430\u0442","tel":null,"tel_work":null,"skype":null,"position":null,"department":"TF","info":null,"active":"1"},
            {"id":"6","name":"aleksande","email":"aleksanderbabayev@gmail.com","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"\u0411\u0430\u0431\u0430\u0435\u0432 \u0410\u043b\u0435\u043a\u0441\u0430\u043d\u0434\u0440","tel":null,"tel_work":null,"skype":null,"position":"\u0420\u0443\u043a\u043e\u0432\u043e\u0434\u0438\u0442\u0435\u043b\u044c \u0440\u0430\u0437\u0440\u0430\u0431\u043e\u0442\u043a\u0438 \u041f\u041e","department":"\u0420\u0430\u0437\u0440\u0430\u0431\u043e\u0442\u043a\u0430 \u041f\u041e","info":null,"active":"1"},
            {"id":"7","name":"margarita.bal","email":"margarita.balikhina@oltatravel.ru","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"\u0411\u0430\u043b\u0438\u0445\u0438\u043d\u0430 \u041c\u0430\u0440\u0433\u0430\u0440\u0438\u0442\u0430","tel":"79118478839","tel_work":"+78123043759 (\u0434\u043e\u043c\u0430\u0448\u043d\u0438\u0439); +79214437377 (\u043b\u0438\u0447\u043d\u044b\u0439)","skype":"margarita.balikhina.olta","position":"\u041c\u0435\u043d\u0435\u0434\u0436\u0435\u0440 \u043e\u0442\u0434\u0435\u043b\u0430 \u043f\u0440\u043e\u0434\u0430\u0436","department":"Account","info":null,"active":"1"},
            {"id":"8","name":"ekaterina.vod","email":"ekaterina.vodolazova@oltatravel.ru","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"\u0412\u043e\u0434\u043e\u043b\u0430\u0437\u043e\u0432\u0430 \u0415\u043a\u0430\u0442\u0435\u0440\u0438\u043d\u0430","tel":null,"tel_work":null,"skype":null,"position":"\u043e\u0444\u0438\u0441-\u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440","department":"\u041e\u0442\u0434\u0435\u043b \u0411\u0440\u043e\u043d\u0438\u0440\u043e\u0432\u0430\u043d\u0438\u044f","info":null,"active":"1"},
            {"id":"9","name":"kseniya.galoc","email":"kseniya.galochkina@oltatravel.ru","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"\u0413\u0430\u043b\u043e\u0447\u043a\u0438\u043d\u0430 \u041a\u0441\u0435\u043d\u0438\u044f","tel":"79111074947","tel_work":null,"skype":"ksenia.galochkina","position":"\u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440 \u043e\u0442\u0434\u0435\u043b\u0430 \u043f\u0440\u043e\u0434\u0430\u0436","department":"Account","info":null,"active":"1"},
            {"id":"10","name":"stampvita","email":"stampvital@gmail.com","password":"$2y$10$hPuY\/N0HciCAksgj293gPu6szKPdlsns9IwVCo.zyqZ967RKFY.Ba","remember_token":null,"created_at":null,"updated_at":null,"userfio":"\u0414\u043e\u043c\u0438\u043d\u043e\u0432 \u0412\u0438\u0442\u0430\u043b\u0438\u0439","tel":"79119395496","tel_work":null,"skype":"dominov73","position":null,"department":"\u0411\u0443\u0445\u0433\u0430\u043b\u0442\u0435\u0440\u0438\u044f","info":null,"active":"1"}
        ],
        titles:[
            {
                prop: "id",
                label: "id"
            }, {
                prop: "userfio",
                label: "userfio"
            }, {
                prop: "position",
                label: "position"
            },{
                prop: "department",
                label: "department"
            }
        ],
        tableProps: {
            defaultSort: {
                prop: 'id',
                order: 'descending'
            }
        },
        searchDef: {
            props: 'userfio',
            colProps:{
                span:10,
                offset:0
            },
            inputProps: {
                placeholder: 'User name search'
            }
        },
        actionColDef: {
            label: 'Actions',
            def: [ {
                icon: 'upload',
                type: 'text',
                handler: row => {
                    //this.$notify('B-sync in row clicked ')
                    console.log(row);
                    this.userBitrixSync(row.id);
                },
                name: 'B-sync'
            }]
        },
        checkboxFilterDef: {
            def: [{
                'code': 1,
                'name': 'Company'
            }, {
                'code': 0,
                'name': 'Non active'
            }
            ],
            filterFunction(el, filter) {
                //if (el.name =='All') {return}
                return el['active'] === filter.vals[0]
            }
        },
        department_items: [
            {"id":1,"name":'one'},
            {"id":2,"name":'two'},
            {"id":3,"name":'three'},
        ],
        items_control:[],
        department_items_control:[],
        users_view:'Users',
        dialogFormVisible:false,
        dialogDeactivateVisible:false,
        change_user_id:null,
        form: {
            user_id:null,
            name: '',
            userfio: '',
            active:false,
            department_id: null,
            info:'',
            position:'',
            delivery: false,
            type: [],
            resource: '',
            desc: ''
        },
        formLabelWidth: '120px',
        form_rules: {
            name: [
                { required: true, message: 'Please input  name', trigger: 'blur' },
                { min: 3, max: 55, message: 'Length should be 3 to 55', trigger: 'blur' }
            ]
        }
    }
  },
    methods: {
        handleTabClick(tab, event) {
            console.log(tab, event);
        },
        actionsDef: {
            def: [{
                name: 'new user',
                handler: () => {
                    this.$notify({title:"new clicked"})
                }
            }, {
                name: 'B-sync-users',
                handler: () => {
                    this.$notify({title:"import clicked"})
                },
                icon: 'upload',
                buttonProps: {
                    type: 'text'
                }
            }]
        },
        tableRowClassName(row, index) {
            if (row.active === 0) {
                return 'deactive-row';
            }
            return '';
        },
        tableRowClick(row, event, column) {
            console.log('Row.active='+row.active);
        },
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    //alert('submit!');
                    this.updateUser();
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },
        async updateUser() {
            try {
                var params = new URLSearchParams();
                params.append('name', this.form.name);
                params.append('id', this.form.user_id);
                params.append('userfio', this.form.userfio);
                params.append('position', this.form.position);
                params.append('department_id', this.form.department_id);
                params.append('tel', this.form.tel);
                params.append('tel_work', this.form.tel_work);
                params.append('info', this.form.info);
                params.append('active', this.form.active?1:0);

                const { data } =  await axios.post('/api/users/update',params,{withCredantial:true});
                //console.log('Update DATA:',data)
                if (data.errors != undefined) {
                    console.log("Errors:",data.errors);
                } else {
                    //--update local items--
                    this.updateItemInItems(data);
                    this.dialogFormVisible = false;
                    this.getUsers();
                    //this.$refs.users-table.data=this.items;
                }
            } catch(e) {
                    console.log('ERROR',e);
            }
        },
        async getUsers(){
            //const { data } = await axios({method:'post',url:'/api/users',withcredantial:true});
            console.log('Get users...');
            try {
            const { data } = await axios.post('/api/users');

                this.items =data.data;
                this.items_control =data.controls;
                //console.log('getUsers'+this.items);
            } catch(e) {
                this.items=this.one_items;
            }

            // Fetch the user.
            //await this.$store.dispatch('fetchUsers',{users:data.data});
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
        createNew() {
            this.$refs.createNewDialog.show();
        },
        filterTag(value, row) {
            return row.department.indexOf(value)>0;
        },
        updateItemInItems(item) {
          for (let i=0;this.items.length-1;i++) {
              if (this.items[i].id=item.id) {
                  this.items[i]=item;
                  console.log('item replaced...id='+item.id);
                  this.$nextTick();
                  return;
              }
          }
          this.form.row=item;
        },
        userBitrixSync(user_id) {
            this.syncUser(user_id);
        },
        async syncUser(user_id) {
            try {
                var params = new URLSearchParams();

                params.append('user_id', user_id);


                const { data } =  await axios.post('/bitrix/syncUser',params,{withCredantial:true});
                if (data.error) {
                    console.log("Error:",data.error);
                    if (data.error_description) {
                        this.$notify.error({
                            title: data.error,
                            message: data.error_description,
                            duration:4500
                        });
                    }

                }
                console.log('SyncDATA:',data)
                if (data.errors != undefined) {
                    console.log("Errors:",data.errors);
                } else {
                    //--update local items--
                    this.getUsers();
                    this.$notify.success({
                        title: 'Bitrix sync',
                        message: 'Done!',
                        duration:4500,
                        offset: 100
                    });
                }
            } catch(e) {
                console.log('ERROR',e);
            }
        },
        async checkNewBitrixUser() {
            try {
                var params = new URLSearchParams();

                //params.append('user_id', user_id);


                const { data } =  await axios.post('/bitrix/syncNewUsers',params,{withCredantial:true});
                if (data.error) {
                    console.log("Error:",data.error);
                    if (data.error_description) {
                        this.$notify.error({
                            title: data.error,
                            message: data.error_description
                        });
                    }

                }
                if (data.errors) {
                    console.log("Errors:",data.errors);
                } else {
                    //--update local items--
                    console.log(' checkNewBitrix SyncDATA:',data);
                    this.getUsers();
                    this.$notify.success({
                        title: 'Bitrix new users sync',
                        message: 'Done!',
                        offset: 50
                    });

                }
            } catch(e) {
                console.log('ERROR',e);
            }
        },
        userEdit(index,row) {
            //alert('user edit'+row.name);
          this.dialogFormVisible = true;
          this.change_user_id=row.id;
          this.form.user_id=row.id;
          this.form.name = row.name;
          this.form.email = row.email;
          this.form.userfio = row.userfio;
          this.form.position = row.position;
          this.form.department_id = row.department_id;
          this.form.tel = row.tel;
          this.form.tel_work = row.tel_work;
          this.form.info = row.info;
          this.form.active = (row.active ==1)?true:false;
          this.form.row=row;
        },
        userDeactivate(index,row) {
            this.change_user_id=row.id;
            this.form.user_id=row.id;
            this.form.name = row.name;
            this.form.active = (row.active ==1)?true:false;
            this.dialogDeactivateVisible = true;
        },
        async userDeactivateOnServer() {
            try {
                var params = new URLSearchParams();
                params.append('id', this.form.user_id);
                params.append('active', this.form.active?1:0);

                const { data } =  await axios.post('/api/users/deactivate',params,{withCredantial:true});
                console.log('Deactivate DATA:',data);
                if (data.errors != undefined) {
                    console.log("Errors:",data.errors);
                } else {
                    //--update local items--

                    this.dialogDeactivateVisible = false;
                    this.getUsers();
                    //this.$refs.users-table.data=this.items;
                }
            } catch(e) {
                console.log('ERROR',e);
            }
        },
        can(action) {
            if (this.items_control.indexOf(action) !=-1) {
                return true
            }
            return false;
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

    }
}
</script>
<style>
.el-table .deactive-row {
    background: #f3e3c2;
}
.el-row {
    margin-bottom: 20px;
&:last-child {
     margin-bottom: 0;
 }
}
.table-head {
    background-color: #eef1f6;
}
.my-avatar {
    border-radius: 50%;
    display: block;
    float: left;
    height: 50px;
    margin: 10px;
    width: 50px;
}
.my-def-avatar {
    background: #535c6a  no-repeat center;
    background-size: auto auto;
    background-size: 50%;
}
.personal-contact {
    color:#2067b0;
}
.leader-wrap {
    text-align: center;
}
.leader {
    margin: 10px;
    border-radius: 5px;
    background-color: orange;
    display: inline-block;
    font-size: 13px;
    font-weight: bold;
    line-height: 17px;
    margin-top: 3px;
    min-width: 93px;
    padding: 2px 7px;
    text-align: center;
}
.fio-class {
    cursor:pointer;
}
</style>
