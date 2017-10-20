<template >

  <section class="departments">
      <table  class="table table-bordered table-hover table-responsive">
          <thead class="table-head">
          <th>#</th>
          <th>Department Name</th>
          <th v-if="department_items_control.length >0">Controls</th>
          </thead>
          <tbody>
          <tr v-for="item in department_items">
              <th scope="row">{{item.id}}</th>

              <td>{{item.name}}</td>
              <td  v-if="department_items_control.length >0" >
                  <el-button v-if="can_department('edit')" size="small" type="success" @click="departmentEdit(item)">
                      Edit
                  </el-button>
                  <!--<el-button v-if="can_department('delete')" size="small" type="danger"  @click="departmentDeactivate(item)">-->
                  <!--Deactivate-->
                  <!--</el-button>-->
              </td>

          </tr>
          </tbody>
      </table>

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
      }
    },
    methods: {
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
}
</style>
