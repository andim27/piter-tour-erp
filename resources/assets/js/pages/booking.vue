<template >

  <section class="booking">
    <h1 class="booking-title">Booking</h1>
      <div class="content">
          <el-tabs v-model="activeTabName" @tab-click="handleTabClick">
              <el-tab-pane label="Tours" name="tours">
                  <el-row>
                      <el-col :span="24">
                          <div class="grid-content bg-purple-dark">
                              <el-button
                                      type="primary"
                                      @click="createNew()">Create tour by quotation
                              </el-button>

                          </div>
                      </el-col>

                  </el-row>
                  <data-tables :data="items" :table-props="tableProps" :search-def="bookingSearchDef" :pagination-def="paginationTourDef" >
                      <el-table-column
                              label="id"
                              prop="id"
                              width="50">
                      </el-table-column>
                      <el-table-column
                              label="Tour name"
                              prop="tour_name"
                              width="150">
                      </el-table-column>
                      <el-table-column
                              label="Client"
                              prop="client_name"
                              width="150">

                      </el-table-column>
                      <el-table-column
                              label="Period"
                              width="140">
                          <template  scope="scope">
                              <p>{{scope.row.date_from}}</p>
                              <p>{{scope.row.date_to}}</p>
                          </template>
                      </el-table-column>
                      <el-table-column
                              label="Cities"
                              prop="cities_str"
                              width="150">

                      </el-table-column>
                      <el-table-column
                              label="People"
                              prop="people"
                              width="100">

                      </el-table-column>
                      <el-table-column
                              label="Sales manager"
                              prop="sales_user_name"
                              label-class-name="fio-class"
                              width="180">
                      </el-table-column>
                      <el-table-column
                              label="Booking manager"
                              prop="booking_user_name"
                              label-class-name="fio-class"
                              width="180">
                      </el-table-column>
                  </data-tables>
                  <create-tour-by-q  ref="createTourByQ"></create-tour-by-q>
              </el-tab-pane>
              <el-tab-pane label="Transport" name="transport">
                <h3>Transport services:</h3>

              </el-tab-pane>
          </el-tabs>
      </div>
  </section>

</template>

<script >
    import axios from 'axios'
    import CreateTourByQ from'~/components/CreateTourByQ'

  export default  {
    components:{
      CreateTourByQ
    },
    name: 'booking',
    props: [],
    mounted() {
        this.$bus.$on('created-tour-by-q',function(params){
            console.log('Tour created!');
            this.getTours();
        }.bind(this));
    },
    data() {
      return {
        activeTabName:'tours',
        one_items:[
           {id:1,tour_name:'Test tour',client_name:"Client-name-here",date_from:'01-12-2017',date_to:'01-03-2018',people:'???',cities_str:'MSK-?,St.Piter-?',sales_user_name:'test_manager',booking_user_name:'admin_manager'}
        ],
        items:[
            {id:1,tour_name:'Winter Group Series 2017-2018',client_name:"Client-name-here",date_from:'01-11-2017',date_to:'31-03-2018',people:'31',cities_str:'MSK,St.Piter',sales_user_name:'aaa',booking_user_name:'bbb'}
        ],
        tableProps: {
          defaultSort: {
              prop: 'id',
              order: 'descending'
          }
        },
        paginationTourDef: {
          show: false
            //  pageSize: 1,
            //  pageSizes: [1, 2, 3],
            //  currentPage: 2
        },
        bookingSearchDef: {
              show: false
        }
      }
    },
    methods: {
        handleTabClick(tab, event) {
            console.log(tab, event);
        },
        createNew() {
            this.$refs.createTourByQ.show();
        },
        async getTours(){
            //const { data } = await axios({method:'post',url:'/api/users',withcredantial:true});
            console.log('Get tours...');
            try {
                var params = new URLSearchParams();
                params.token = this.$route.params.token;
                const { data } = await axios.post('/api/tours',params,{withCredantial:true});

                this.items =data.data;
                //this.items_control =data.controls;
                //console.log('getUsers'+this.items);
            } catch(e) {
                this.items=this.one_items;
            }

            // Fetch the user.
            //await this.$store.dispatch('fetchUsers',{users:data.data});
        },
    },
    computed: {

    }
}
</script>

<style scoped>
.booking {

}
.booking-title {
  color:#4db3ff;
  text-shadow: silver;
}

</style>
