<template >

  <section class="create-new-dialog">

      <el-dialog
              v-bind:title="titleDialog"
              :visible.sync="isVisibleDialog"
              size="small"
      >

          <div class="container">
              <form @submit.prevent="createNew" @keydown="form.onKeydown($event)">
                  <alert-success :form="form" :message="$t('created')"></alert-success>
                  <!-- Name -->
                  <div class="form-group row">
                      <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
                      <div class="col-md-7">
                          <input v-model="form.name" type="text" name="name" class="form-control" @focus="form.errors.clear()"
                                 :class="{ 'is-invalid': form.errors.has('name') }">
                          <has-error :form="form" field="name"></has-error>
                      </div>
                  </div>

                  <!--if user-->
                  <div v-if="$props.type =='Users'"  >
                      <!-- userfio -->
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label text-md-right">{{ $t('fio') }}</label>
                          <div class="col-md-7">
                              <input v-model="form.userfio" type="text" name="tel" class="form-control"
                                     :class="{ 'is-invalid': form.errors.has('userfio') }">
                              <has-error :form="form" field="userfio"></has-error>
                          </div>
                      </div>
                      <!-- Email -->
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
                          <div class="col-md-7">
                              <input v-model="form.email" type="email" name="email" class="form-control"
                                     :class="{ 'is-invalid': form.errors.has('email') }">
                              <has-error :form="form" field="email"></has-error>
                          </div>
                      </div>
                      <!-- position -->
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label text-md-right">{{ $t('position') }}</label>
                          <div class="col-md-7">
                              <input v-model="form.position" type="text" name="tel" class="form-control"
                                     :class="{ 'is-invalid': form.errors.has('position') }">
                              <has-error :form="form" field="position"></has-error>
                          </div>
                      </div>
                      <!-- Department -->
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label text-md-right">{{ $t('department') }}</label>
                          <div class="col-md-7">
                                  <select class="form-control" v-model="form.department" placeholder="Please select a department" :class="{ 'is-invalid': form.errors.has('department')}">
                                      <option v-for="(item,index) in $props.department_items"  v-bind:label="item.name" v-bind:value="item.id">{{item.name}}</option>

                                  </select>
                              <has-error :form="form" field="department"></has-error>
                          </div>
                      </div>

                      <!-- Tel -->
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label text-md-right">{{ $t('tel') }}</label>
                          <div class="col-md-7">
                              <input v-model="form.tel" type="text" name="tel" class="form-control"
                                     :class="{ 'is-invalid': form.errors.has('tel') }">
                              <has-error :form="form" field="tel"></has-error>
                          </div>
                      </div>
                      <!-- Tel work -->
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label text-md-right">{{ $t('tel_work') }}</label>
                          <div class="col-md-7">
                              <input v-model="form.tel_work" type="text" name="tel_work" class="form-control"
                                     :class="{ 'is-invalid': form.errors.has('tel_work') }">
                              <has-error :form="form" field="tel_work"></has-error>
                          </div>
                      </div>
                      <!-- Info -->
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label text-md-right">{{ $t('info') }}</label>
                          <div class="col-md-7">
                              <input v-model="form.info" type="text" name="info" class="form-control"
                                     :class="{ 'is-invalid': form.errors.has('info') }">
                              <has-error :form="form" field="info"></has-error>
                          </div>
                      </div>
                  </div>
              </form>
          </div>

          <span slot="footer" class="dialog-footer">
                <el-button @click="cancel">Cancel</el-button>
                <el-button type="primary" :loading="form.busy" @click="createNew">Create</el-button>
            </span>
      </el-dialog>
  </section>

</template>

<script lang="js">
  import Form from 'vform'
  export default  {
    name: 'create-new-dialog',
    props: ['type','department_items','user_id'],
    mounted() {
        //console.log('Departments from store:'+this.$store.departments);
    },
    beforeUpdate() {
      //console.log('Departments from store:'+this.$store.departments);
      //console.log("PROPS:"+this.$props.type);
      if (this.$props.type =='Users') {
          this.titleDialog = 'Create user:'
      }
      if (this.$props.type =='Departments') {
          this.titleDialog = 'Create department:'
      }
    },
    data() {
      return {
          state:'close',
          what_create:'',
          isVisibleDialog:false,
          titleDialog:'user',
          form: new Form({
              name: '',
              userfio: '',
              email:'',
              tel:'',
              tel_work:'',
              info:'',
              department: '',
              position: ''
          }),
      }
    },
      computed() {
//        return {
//            titleDialog() {
//                return 'Title';
//                if (this.state=='open') {return true;}
//                return false;
//            }
//        }

      },
    methods: {
        show() {
            this.state='open';
            this.isVisibleDialog=true;
        },
        cancel() {
            this.state='close';
            this.isVisibleDialog=false;
            this.form.clear();
            this.form.reset();
        },
        async createNew() {

            if (this.$props.type =='Users') {

                const { data } = await this.form.post('/api/users/create')
            }
            if (this.$props.type =='Departments') {
                const { data } = await this.form.post('/api/departments/create')
            }
            this.$bus.$emit('created-new',{'type':this.$props.type});
//            if (data.success) {
//                console.log('Created...');
//                this.form.successful=true;
//                self=this;
//
                setTimeout(function() { this.state='close'; }.bind(this), 1500);
//            } else {
//                if (data.errors.length>0) {
//                    this.form.errors.all();
//                }
//
//            }
        }
    },
    computed: {

    }
}
</script>

<style scoped>
  .create-new-dialog {

  }
</style>
