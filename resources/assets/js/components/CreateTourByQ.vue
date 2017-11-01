<template >

  <section class="create-tour-by-q">
      <el-dialog
              v-bind:title="titleDialog"
              :visible.sync="isVisibleDialog"
              size="small"
      >
          <div class="container">
              <form @submit.prevent="createNew" @keydown="form.onKeydown($event)">
                  <alert-success :form="form" :message="$t('created')"></alert-success>
                  <alert-error :form="form" :message="error_message"></alert-error>
                  <!-- Quotation number -->
                  <div class="form-group row">
                      <label class="col-md-3 col-form-label text-md-right">Quotation N:</label>
                      <div class="col-md-7">
                          <input v-model="form.ext_q_id" type="text" name="ext_q_id" class="form-control" @focus="form.errors.clear()"
                                 :class="{ 'is-invalid': form.errors.has('ext_q_id') }">
                          <has-error :form="form" field="ext_q_id"></has-error>
                      </div>
                  </div>

                      <!-- people -->
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label text-md-right">PAX:</label>
                          <div class="col-md-7">
                              <input v-model="form.people" type="text" name="people" class="form-control"
                                     :class="{ 'is-invalid': form.errors.has('people') }">
                              <has-error :form="form" field="people"></has-error>
                          </div>
                      </div>
                      <!-- Date -->
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label text-md-right">Date:</label>
                          <div class="my-date-wrappper"  :class="{ 'border-is-invalid': form.errors.has('work_date') }">
                              <el-date-picker
                                  v-model="form.work_date"
                                  type="date"
                                  format="yyyy-MM-dd"
                                  placeholder="">
                              </el-date-picker>
                          </div>
                          <has-error :form="form" field="work_date"></has-error>
                          <!--<div class="col-md-7">-->
                              <!--<input v-model="form.date" type="text" name="date" class="form-control"-->
                                     <!--:class="{ 'is-invalid': form.errors.has('date') }">-->
                              <!--<has-error :form="form" field="date"></has-error>-->
                          <!--</div>-->
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
  import { HasError, AlertError } from 'vform'
  export default  {

    name: 'create-tour-by-q',
    props: [],
    components: {
        AlertError
    },
    mounted() {

    },
    data() {
      return {
          titleDialog:'Create Dossier by quotation:',
          state:'close',
          isVisibleDialog:false,
          form: new Form({
              ext_q_id: '',
              people: '',
              work_date:'',
          }),
          error_message:'Error from external source.Problem with your input.'
      }
    },
    methods: {
        show() {
            this.form.errors.all();
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
            const { data } = await this.form.post('/api/tours/create-q');
            if (data.success) {
                if (data.data.success ==false) {//--answer from external api
                    console.log('ERROR Tour by quotation:',data.data.message);
                    if (data.message != undefined) {
                        this.error_message=data.message;
                    }

                    this.form.errors.set({message:data.message});
                    this.form.errors.all();
                } else {
                    console.log('Tour by quotation Created...');
                    this.form.successful=true;
                    self=this;
                    this.$bus.$emit('created-tour-by-q');
                }

            } else { //---error
                console.log('ERROR Tour by quotation:',data);
                this.form.successful=false;
                this.form.errors.set({message:data.message});
                if (data.message != undefined) {
                    this.error_message=data.message;
                }
                //if (data.errors.length>0) {
                    this.form.errors.all();
                //}
            }
        }
    },
    computed: {

    }
}
</script>

<style scoped>
.create-tour-by-q {

}
.my-date-wrappper {
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
.border-is-invalid {
    border-color: #dc3545;
}
</style>
