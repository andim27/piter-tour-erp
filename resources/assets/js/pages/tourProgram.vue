<template >

  <section class="tour-program">
       <router-link :to="{ name: 'booking' }">
         <el-button type="primary" icon="el-icon-arrow-left">Back</el-button>
        </router-link>
    <h1>Tour-program(Dossie)#{{tour_id}}</h1>
      <div class="content">
        <div v-for="(item,index) in items">
           <h3>Day {{item.day_index}} of {{nights}}: {{item.day_title}}</h3>
             <el-table
                :data="item.services"
                border
                style="width: 100%">
                <el-table-column
                  prop="time_from"
                  label="Time from"
                  width="150">
                    <template slot-scope="scope">
                        <el-time-select
                                v-model="scope.row.time_from"
                                size="small"
                                width="120"
                                style="width:120px"
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
                  width="150">
                    <template slot-scope="scope">
                        <el-time-select
                                v-model="scope.row.time_to"
                                size="small"
                                width="120"
                                style="width:120px"
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
                    label="Service name">
                    <template slot-scope="scope">
                        <span class="city-service-wrapp">{{scope.row.city_name}}:</span>
                        <span>{{scope.row.service_name}}</span>
                    </template>

                </el-table-column>
                <el-table-column
                         label="Supplement"
                         width="130">
                    <template slot-scope="scope">
                        <!--<el-checkbox v-model="scope.row.is_transport">Car</el-checkbox>-->
                        <el-switch
                                style="margin:10px"
                                v-model="scope.row.is_transport"
                                active-color="#409EFF"
                                inactive-color="#D8DCE5">
                        </el-switch>
                    </template>
                 </el-table-column>
              </el-table>
              <!--Supplement-->
              <div  v-if="item.supplements != undefined" class="supplement-wrap">
                  <el-table
                    :data="item.supplements"
                    style="width: 100%"
                    :show-header="true"
                    :row-class-name="tableRowClassName">
                    <el-table-column
                          label="Time from"
                          width="150">
                        <template slot-scope="scope">
                            <el-time-select
                                    placeholder="Start time"
                                    v-model="scope.row.time_from"
                                    size="mini"
                                    style="width:120px"
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
                              width="150">
                          <template slot-scope="scope">
                              <el-time-select
                                      placeholder="End time"
                                      v-model="scope.row.time_to"
                                      size="mini"
                                      style="width:120px"
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
      </div>
  </section>

</template>

<script >
  import axios from 'axios'
  export default  {
    name: 'tour-program',
    props: [],
    mounted() {
      this.initParams();
      this.getTourId();
    },
    data() {
      return {
        tour_id:'',
        currency_type_str:'RUB',
        people_str:'31+1',
        nights:1,
        items:[
            {day_index:1,day_title:'Moscow, 5 May Friday',
                services:[
                  {time_from:'8-30',time_to:'9-30',city_name:'MSK',service_name:'Arrival/Depature: Arriving at the airport',is_transport:false},
                  {time_from:'8-30',time_to:'9-30',city_name:'MSK',service_name:'Transfer: Transfer from the Airport',is_transport:false}
                ],
                supplements:[
                    {service_name:'transport',service_hours:4,service_price:100,service_sum:400},
                    {service_name:'guide',service_hours:7,service_price:50,service_sum:350},
                ]
            },
            {day_index:2,day_title:'Moscow, 6 May Saturday',
                services:[
                    {time_from:'9-00',time_to:'9-30',city_name:'MSK',service_name:'Meal: Breakfast at the hotel',is_transport:false},
                    {time_from:'9-30',time_to:'11-30',city_name:'MSK',service_name:'Excursion: City tour',is_transport:true}
                ],
                supplements:[
                    {service_name:'transport',service_hours:8,service_price:120,service_sum:960,is_transport:true},
                    {service_name:'guide',service_hours:2,service_price:50,service_sum:100,is_transport:false},
                ]
            },
            {day_index:3,day_title:'Moscow, St.Petersburg, 7 May Sunday',
                services:[
                    {time_from:'9-00',time_to:'9-30',city_name:'MSK',service_name:'Meal: Breakfast at the hotel',is_transport:false},
                    {time_from:'9-30',time_to:'11-30',city_name:'MSK',service_name:'Transfer: Transfer to the Airport',is_transport:true},
                    {time_from:'11-30',time_to:'12-30',city_name:'MSK',service_name:'Transport: Hourly',is_transport:true}
                ],
                supplements:[
                    {service_name:'transport',service_hours:8,service_price:120,service_sum:960,is_transport:true},
                    {service_name:'Guide: 1/2 day (4 hours)',service_hours:2,service_price:50,service_sum:100,is_transport:false}
                ]
            }
        ]
      }
    },
    methods: {
        initParams() {
          this.nights     = this.$route.params.nights;
          this.people_str = this.$route.params.people+'';
          if (this.$route.params.ftl_number !='0') {
              this.people_str =this.people_str +'+'+this.$route.params.ftl_number;
          }
          this.currency_type_str = this.$route.params.currency_type_str;
        },
        getTourId() {
            this.tour_id=this.$route.params.tour_id;
        },
        getCurrency() {
          return this.currency_type_str;
        },
        tableRowClassName({row, rowIndex}) {
            if ((row.is_transport != undefined) && (row.is_transport ==true)) {
                return 'transport-row';
            } else {
                return 'guide-row';
            }
            return '';
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
</style>
