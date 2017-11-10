<template >

  <section class="tour-program">
       <router-link :to="{ name: 'booking' }">
         <el-button type="primary" icon="el-icon-arrow-left">Back</el-button>
        </router-link>

          <el-row>
              <el-col :span="6">
                  <el-tooltip :content="'tour_id='+tour_id" placement="bottom" effect="light">
                    <h1>Dossie#({{dossier}})</h1>
                  </el-tooltip>
              </el-col>
              <el-col :span="6" :offset="12">
                  <el-switch
                          @change="changeQuotes"
                          v-model="is_quotes"
                          active-text="Show quotes"
                          inactive-text="Hide quotes">
                  </el-switch>
              </el-col>
          </el-row>


      <div class="content">
        <div v-if="items.length >0"  v-for="(item,index) in items">
           <h3>Day {{item.day_index}} of {{nights}}: {{item.day_title}}</h3>
            <!--Services -->
            <el-table
                :data="item.services"
                border
                style="width: 100%">
                <el-table-column
                  prop="time_from"
                  label="Time from"
                  fixed
                  width="120">
                    <template slot-scope="scope">
                        <el-time-select
                                v-model="scope.row.time_from"
                                size="small"
                                style="width:100px"
                                :picker-options="{
                                    start: '08:00',
                                    step: '00:15',
                                    end: '23:30'
                                  }"
                                placeholder="Select time">
                        </el-time-select>
                    </template>
                </el-table-column>
                <el-table-column
                  prop="time_to"
                  label="Time to"
                  fixed
                  width="120">
                    <template slot-scope="scope">
                        <el-time-select
                                v-model="scope.row.time_to"
                                size="small"
                                style="width:100px"
                                :picker-options="{
                                    start: '08:00',
                                    step: '00:15',
                                    end: '23:30'
                                  }"
                                placeholder="Select time">
                        </el-time-select>
                    </template>
                </el-table-column>
                <el-table-column
                         width="30"
                         label="s">
                     <template slot-scope="scope">

                         <el-tooltip  placement="top" effect="light">
                             <div slot="content">Status:<span>{{scope.row.service_state}}</span><br/>Date:</div>
                             <i class="el-icon-document"></i>
                         </el-tooltip>
                     </template>
                </el-table-column>
                <el-table-column
                    label="Service name">
                    <template slot-scope="scope">
                        <p>

                        <el-checkbox style="display:inline-block;" v-model="scope.row.visible_comment"></el-checkbox>
                        <span class="city-service-wrapp">{{scope.row.city_name}}:</span>
                        <el-button type="text" >{{scope.row.service_name}}</el-button>
                        </p>
                        <p v-if="(scope.row.visible_comment)||(scope.row.comment != null)" class="my-service-comment">
                            <el-input  style="width:80%;height:28px;" placeholder="Comment..." size="small" v-model="scope.row.comment"></el-input>
                            <el-button @keyup.enter="saveComment(scope.row)" @click="saveComment(scope.row)"  type="primary" plain size="small" icon="el-icon-edit"></el-button>
                        </p>
                    </template>

                </el-table-column>
                 <el-table-column
                         v-if="(is_quotes==true)"
                         width="35"
                         label="Qty">
                     <template slot-scope="scope">
                         <p>{{scope.row.qty}}</p>
                     </template>
                 </el-table-column>
                 <el-table-column
                         v-if="(is_quotes==true)"
                         label="Price"
                         width="120">
                         <template slot-scope="scope">
                             <p><span>Q:</span><span class="my-q-field">{{scope.row.q_price}}</span></p>
                             <p><span>  </span><el-input  class="my-input-price" v-model="scope.row.service_price"  size="mini"></el-input></p>

                         </template>
                 </el-table-column>
                 <el-table-column
                         v-if="(is_quotes==true)"
                         label="Hours"
                         width="100">
                     <template slot-scope="scope">
                         <p class="my-wrap-price"><span>Q:</span><span class="my-q-field">{{scope.row.q_hours}}</span></p>
                         <p class="my-wrap-price"><span>  </span><el-input  class="my-input-price" v-model="scope.row.service_hours"  size="mini"></el-input></p>

                     </template>
                 </el-table-column>
                 <el-table-column
                         v-if="(is_quotes==true)"
                         label="Sum"
                         width="120">
                     <template slot-scope="scope">
                         <p>
                             <span>Q:</span><span class="my-q-field">{{scope.row.q_sum}}</span>
                         </p>
                         <p>
                             <span>  </span>
                             <el-input  class="my-input-price" v-model="scope.row.service_sum"  size="mini"></el-input>
                         </p>

                     </template>
                 </el-table-column>
                 <el-table-column
                         v-if="(is_quotes==true)"
                         label="Curr"
                         width="50">
                     <template slot-scope="scope">
                        {{scope.row.currency_type_str}}
                     </template>
                 </el-table-column>
                <el-table-column
                         label="T"
                         width="70">
                    <template slot-scope="scope">
                        <!--<el-checkbox v-model="transport" >{{scope.row.is_transport}}</el-checkbox>-->
                        <p v-if="(scope.row.is_transport==1)||(scope.row.is_transport==true)"><span >yes</span></p>
                        <p v-else><span>no</span></p>
                        <el-switch
                                style="margin:2px"
                                v-model="scope.row.is_transport"
                                active-color="#409EFF"
                                inactive-color="#D8DCE5">
                        </el-switch>
                    </template>
                 </el-table-column>
                 <el-table-column
                     label="Pay"
                     width="50">
                 <template slot-scope="scope">
                     <span v-if="scope.row.service_pay_date == null">No</span>
                     <span v-else>{{scope.row.service_pay_date}}</span>
                 </template>
             </el-table-column>
              </el-table>
            <!--Supplement-->
            <div  v-if="(item.supplements != undefined) &&(item.supplements.length >0)" class="supplement-wrap">
                  <el-table
                    :data="item.supplements"
                    style="width: 100%"
                    :show-header="true">
                    <el-table-column
                          label="Time from"
                          width="100">
                        <template slot-scope="scope">
                            <el-time-select
                                    placeholder="Start time"
                                    v-model="scope.row.time_from"
                                    size="mini"
                                    style="width:100px"
                                    :picker-options="{
                                              start: '06:00',
                                              step: '00:15',
                                              end: '24:00'
                                            }">
                            </el-time-select>

                        </template>
                      </el-table-column>
                      <el-table-column
                              label="Time to"
                              width="100">
                          <template slot-scope="scope">
                              <el-time-select
                                      placeholder="End time"
                                      v-model="scope.row.time_to"
                                      size="mini"
                                      style="width:100px"
                                      :picker-options="{
                                              start: '08:30',
                                              step: '00:15',
                                              end: '18:30',
                                              minTime: scope.row.time_from
                                            }">
                              </el-time-select>
                          </template>
                      </el-table-column>

                        <el-table-column
                          prop="service_name"
                          label="Service name">
                        </el-table-column>
                        <el-table-column
                          prop="service_hours"
                          label="Hours"
                          width="100">
                        </el-table-column>
                        <el-table-column
                          prop="service_price"
                          label="Price"
                          width="100">
                        </el-table-column>
                        <el-table-column
                          prop="service_sum"
                          label="sum"
                          width="100">
                        </el-table-column>

                  </el-table>
              </div>
        </div>
        <div v-else>
              <i class="el-icon-loading" style="display:inline-block"></i> <span>Waiting data...</span>
          </div>
      </div>
  </section>

</template>

<script >
  import axios from 'axios'
  export default  {
    name: 'tour-program',
    props: [],
    created() {
        this.initParams();
        this.getTourId();
        this.getProgram();
    },
    mounted() {

    },
    data() {
      return {
        tour_id:'',
        dossier:'',
        currency_type_str:'RUB',
        people_str:'31+1',
        nights:1,
        transport:true,
        is_quotes:true,
        tour_records:[],
        items_one:[{day_index:1,day_title:'Waite...',services:[], supplements:[]}],
        items:[
            {day_index:1,day_title:'Moscow, 5 May Friday',cities:[
                    {"city_name":"MSK","services":[
                        {time_from:'8-30',time_to:'9-30',city_name:'MSK',service_name:'Arrival/Depature: Arriving at the airport',comment:'test-1',q_hours:0,q_price:0.00,q_sum:0.00,is_transport:false},
                        {time_from:'8-30',time_to:'9-30',city_name:'MSK',service_name:'Transfer: Transfer from the Airport',comment:'test-2',is_transport:false}
                        ]
                    }
                ]
            },
            {day_index:2,day_title:'Moscow, 6 May Saturday',cities:[
                {"city_name":"MSK","services":[
                    {time_from:'9-00',time_to:'9-30',city_name:'MSK',service_name:'Meal: Breakfast at the hotel',qty:1,q_hours:0,q_price:0.00,q_sum:0.00,is_transport:false},
                    {time_from:'9-30',time_to:'11-30',city_name:'MSK',service_name:'Excursion: City tour',qty:1,q_hours:0,q_price:0.00,q_sum:0.00,is_transport:true}

                    ]
                },
                {"city_name":"SPB","services":[
                    {time_from:'10-00',time_to:'10-30',city_name:'SPB',service_name:'Meal: Breakfast at the hotel',qty:1,q_hours:0,q_price:0.00,q_sum:0.00,is_transport:false},
                    {time_from:'10-30',time_to:'11-30',city_name:'SPB',service_name:'Excursion: City tour',qty:1,q_hours:0,q_price:0.00,q_sum:0.00,is_transport:true}

                ]
                }
            ]}
        ],
        items_day_service:[
            {day_index:1,day_title:'Moscow, 5 May Friday',
                services:[
                  {time_from:'8-30',time_to:'9-30',city_name:'MSK',service_name:'Arrival/Depature: Arriving at the airport',comment:'test-1',q_hours:0,q_price:0.00,q_sum:0.00,is_transport:false},
                  {time_from:'8-30',time_to:'9-30',city_name:'MSK',service_name:'Transfer: Transfer from the Airport',comment:'test-2',is_transport:false}
                ],
                supplements:[
                    {service_name:'transport',service_hours:4,service_price:100,service_sum:400},
                    {service_name:'guide',service_hours:7,service_price:50,service_sum:350},
                ]
            },
            {day_index:2,day_title:'Moscow, 6 May Saturday',
                services:[
                    {time_from:'9-00',time_to:'9-30',city_name:'MSK',service_name:'Meal: Breakfast at the hotel',qty:1,q_hours:0,q_price:0.00,q_sum:0.00,is_transport:false},
                    {time_from:'9-30',time_to:'11-30',city_name:'MSK',service_name:'Excursion: City tour',qty:1,q_hours:0,q_price:0.00,q_sum:0.00,is_transport:true}
                ],
                supplements:[
                    {service_name:'transport',service_hours:8,service_price:120,service_sum:960,is_transport:true},
                    {service_name:'guide',service_hours:2,service_price:50,service_sum:100,is_transport:false},
                ]
            },
            {day_index:3,day_title:'Moscow, St.Petersburg, 7 May Sunday',
                services:[
                    {time_from:'9-00',time_to:'9-30',city_name:'MSK',service_name:'Meal: Breakfast at the hotel',q_hours:0,q_price:0.00,q_sum:0.00,is_transport:false},
                    {time_from:'9-30',time_to:'11-30',city_name:'MSK',service_name:'Transfer: Transfer to the Airport',q_hours:0,q_price:0.00,q_sum:0.00,is_transport:true},
                    {time_from:'11-30',time_to:'12-30',city_name:'MSK',service_name:'Transport: Hourly',q_hours:0,q_price:0.00,q_sum:0.00,is_transport:true}
                ],
                supplements:[
                    {service_name:'transport',service_hours:8,service_price:120,service_sum:960,is_transport:true},
                    {service_name:'Guide: 1/2 day (4 hours)',service_hours:2,service_price:50,service_sum:100,is_transport:false}
                ]
            }
        ]
      }
    },
//    computed: {
//        calc_is_transport(value) {
//            if (value == 1) {
//                return true;
//            } else {
//                return false;
//            }
//        }
//    },
    methods: {
        initParams() {
          this.nights     = this.$route.params.nights;
          this.people_str = this.$route.params.people+'';
          if (this.$route.params.ftl_number !='0') {
              this.people_str =this.people_str +'+'+this.$route.params.ftl_number;
          }
          this.currency_type_str = this.$route.params.currency_type_str;
          this.dossier = this.$route.params.dossier;
        },
        getTourId() {
            this.tour_id=this.$route.params.tour_id;
        },
        async getProgram() {

            try {
                //this.tour_id=this.$route.params.tour_id;
                var params = new URLSearchParams();
                //params.token = this.$route.params.token;
                console.log('Get trogram...tour_id=',this.tour_id);
                params.append('tour_id', this.tour_id);

                const { data } = await axios.post('/api/tours/program',params,{withCredantial:true});
                if (data.success) {
                    //this.tour_records =data.data;
                    this.items =data.data;
                    //this.correctItems();
                    //this.$nextTick(function(){
                        console.log('TOUR PROGRAM:', JSON.stringify(this.items));
                    //}.bind(this));


                } else {
                    this.$notify.error({
                        title: data.error,
                        message: data.error_description,
                        duration:4500
                    });
                }


            } catch(e) {
                this.items=[];
                this.$notify.error({
                    title: 'Get tour program',
                    message: 'error',
                    duration:4500
                });

            }
        },
        correctItems() {
            if (this.items.length ==0) {
                this.$notify.error({
                    title: 'Error',
                    message: "Can't create tour program...",
                    duration:2500
                });
            } else {
                // -- convert:(visible_comment, is_transport ) tour records(items) for UI view ---
                console.log('Correct items...')
                for (var i=0;i<this.items.length;i++) {
                   var day_items=this.items[i];
                   for (var j=0;j<day_items.length;j++) {
                       var service_items=day_items[j]['services'];
                       console.log('Check service_items:',service_items);
                       for (var k=0;k<service_items.length;k++) {
                           service=service_items[k];
                           console.log('Correct, check service:',service);
                           if (service.comment == undefined) {
                               this.items[i][j]['services'][k].visible_comment=false;

                           }
                           if (service.comment.length >0) {
                               this.items[i][j]['services'][k].visible_comment=true;
                                console.log('(c)Items corrected: day:'+i+' service:'+service.service_name);
                           }
                           if (service.is_transport == undefined) {
                               this.items[i][j]['services'][k].is_transport=false;
                           }
                           if ((service.is_transport==1)||(service.is_transport=='1')) {
                               this.items[i][j]['services'][k].is_transport=true;
                               console.log('(T)Items corrected: day:'+i+' service:'+service.service_name);
                           }

                       }
                   }
                }

            }
        },
        clickComment(row) {
//          if (row.visible_comment == undefined) {
//              row.visible_comment=false;
//          }
//          row.visible_comment=!row.visible_comment;
          //this.$nextTick();
        },
        async saveComment(row) {
            try {
                var params = new URLSearchParams();
                //params.token = this.$route.params.token;
                params.append('program_id', row.id);
                params.append('comment', row.comment);
                const { data } = await axios.post('/api/tours/program/save-comment',params,{withCredantial:true});
                if (data.success) {
                    this.$notify.info({
                        title: 'Yes',
                        message: 'Comment saved!',
                        duration:2500
                    });
                } else {
                    this.$notify.error({
                        title: 'Error',
                        message: 'Save comment,service_id:'+id,
                        duration:4500
                    });
                }
            } catch(e) {

                this.$notify.error({
                    title: 'Error',
                    message: 'Save comment,service_id:'+id,
                    duration:4500
                });

            }
        },
        changeQuotes() {
           console.log(this.is_quotes);
           //this.$nextTick();
        },
        getCurrency() {
          return this.currency_type_str;
        }
    },
    computed: {

    }
}
</script>

<style scoped >
.tour-program {

}
.supplement-wrap {
    margin-top: 10px;
}
.city-service-wrapp {
    display: inline-block;
    font-weight: bold;
}
/*.el-table .cell {*/
    /*box-sizing: border-box;*/
    /*white-space: normal;*/
    /*word-break: break-all;*/
    /*line-height: 33px;*/
/*}*/


.el-table .transport-row {
    background: oldlace;
}

.el-table .guide-row {
    background: #f0f9eb;
}
.my-service-comment {
    width:100%;
    display: inline-block;
    font-size: small;
    color: #4bb1b1;
    height: 28px;
}
.my-input-price {
    width: 80px;
    background-color: #2e6da4;
}
.my-wrap-price {
    width: 90px;
    margin-left: 5px;
    margin-right: 5px;
}
.my-q-field {
    color:#66b1ff;
}
</style>
