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
                  <data-tables :data="items"  @row-click="handleTourClick" :table-props="tableProps" :search-def="bookingSearchDef" :pagination-def="paginationTourDef" :header-row-class-name="tableHeadClass">
                      <el-table-column
                              label="qt_id"
                              prop="ext_q_id"
                              sortable
                              width="100">
                      </el-table-column>
                      <el-table-column
                              label="Dossier"
                              width="80">
                          <template  slot-scope="scope">
                              <p>{{scope.row.dossier}}</p>
                          </template>
                      </el-table-column>
                      <el-table-column
                              label="created"
                              prop="created_at"
                              sortable
                              width="120">
                      </el-table-column>
                      created_at
                      <el-table-column
                              label="Tour name"
                              prop="tour_name"
                              width="155">
                      </el-table-column>
                      <el-table-column
                              label="Client"
                              prop="client_name"
                              width="150">

                      </el-table-column>
                      <el-table-column
                              label="Period"
                              width="140">
                          <template  slot-scope="scope">
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
                              label="PAX"
                              prop="people"
                              width="100">
                          <template  slot-scope="scope">
                              <!--<p>{{scope.row.people}}+{{getOptionsField('ftl_number',scope.row.options)}}</p>-->
                              <p>{{scope.row.people}}<span v-if="(scope.row.ftl_number !='')&&(scope.row.ftl_number !='0')">+{{scope.row.ftl_number}}</span></p>

                          </template>
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
    created() {
        this.getTours().then(function(){
            this.addSpeshialFields();
        }.bind(this));
    },
    mounted() {
        this.$bus.$on('created-tour-by-q',function(params){
            console.log('Tour created!');
            this.getTours().then(function(){
                this.addSpeshialFields();
            }.bind(this));
        }.bind(this));

    },
    data() {
      return {
        activeTabName:'tours',
        one_items:[
           {ext_q_id:1,created_at:'01-01-2017',tour_name:'Test tour',client_name:"Client-name-here",date_from:'01-12-2017',date_to:'01-03-2018',people:'???',cities_str:'MSK-?,St.Piter-?',sales_user_name:'test_manager',booking_user_name:'admin_manager'}
        ],
        items:[
            {ext_q_id:1,created_at:'01-01-2017',tour_name:'Winter Group Series 2017-2018',client_name:"Client-name-here",date_from:'01-11-2017',date_to:'31-03-2018',people:'31',cities_str:'MSK,St.Piter',sales_user_name:'aaa',booking_user_name:'bbb'}
        ],
        tableProps: {
          defaultSort: {
              prop: 'id',
              order: 'ascending' //'descending'
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
        tableHeadClass(row, rowIndex) {
            console.log('tableHeadClass index:',rowIndex);
            return "booking-table-head";
        },
        handleTabClick(tab, event) {
            console.log(tab, event);
        },
        handleTourClick(row, event, column) {
            //console.log(row,event,column);
            this.$router.push({ name: 'tour-program',params:{
                tour_id:row.id,
                dossier:row.dossier,
                nights:row.nights,
                ftl_number:row.ftl_number,
                //currency_type_str:row.currency_type_str
                currency_type_str:'RUB'
            }
            })
        },
        createNew() {
            this.$refs.createTourByQ.show();
        },
        addSpeshialFields() {
            for (let i=0;i<this.items.length;i++) {
                this.items[i].options=JSON.parse(this.items[i].options);
                this.items[i].ftl_number=this.getOptionsField('ftl_number',this.items[i].options);
                this.items[i].dossier=this.getOptionsField('dossierNr',this.items[i].options);
                //this.items[i].dossier='';
                if ((this.items[i].options != undefined)&&(this.items[i].options.dossier != undefined)) {
                    this.items[i].dossier=this.items[i].options.dossier;
                }

                //console.log('i='+i+' qt_id='+this.items[i].ext_q_id+'  dossier='+this.items[i].dossier);
            }
            //console.log('After ADDSpeshialFields:',this.items);
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
                //console.log('getTours:',this.items);
                //console.log('getTours options last:',this.items[this.items.length-1].options);
            } catch(e) {
                this.items=this.one_items;
            }

            // Fetch the user.
            //await this.$store.dispatch('fetchUsers',{users:data.data});
        },
        getOptionsField(name,items_json) {
            if (items_json == undefined){return ''}
            for (let i=0;i<items_json.length;i++) {
                if (items_json[i].key ==name) {
                    return items_json[i].value;
                }
            }
            return '';
        }
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
.booking-table-head {
    background-color: #6c757d;
}
</style>
