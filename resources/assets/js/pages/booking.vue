<template >

  <section class="booking">
    <h1>Booking</h1>
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
                  <data-tables :data="items" :table-props="tableProps" :search-def="bookingSearchDef" >
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
                              prop="client"
                              width="150">

                      </el-table-column>
                      <el-table-column
                              label="Period"
                              prop="period"
                              width="150">
                          <template scope="scope">
                              <p>{{date_from}}</p>
                              <p>{{date_to}}</p>
                          </template>
                      </el-table-column>
                      <el-table-column
                              label="Cities"
                              prop="cities"
                              width="150">

                      </el-table-column>
                      <el-table-column
                              label="People"
                              prop="people"
                              width="100">

                      </el-table-column>
                      <el-table-column
                              label="Sales manager"
                              prop="sales_manager"
                              label-class-name="fio-class"
                              width="180">
                      </el-table-column>
                      <el-table-column
                              label="Booking manager"
                              prop="booking_manager"
                              label-class-name="fio-class"
                              width="180">
                      </el-table-column>
                  </data-tables>
              </el-tab-pane>
              <el-tab-pane label="Transport" name="transport">
                <h2>Transport services:</h2>

              </el-tab-pane>
          </el-tabs>
      </div>
  </section>

</template>

<script >
  export default  {
    name: 'booking',
    props: [],
    mounted() {

    },
    data() {
      return {
        activeTabName:'tours',
        items:[
            {id:1,tour_name:'Winter Group Series 2017-2018',client_name:"Client-name-here",date_from:'01-11-2017',date_to:'31-03-2018',people:'31',cities:'MSK,St.Piter',sales_manager:'aaa',booking_manager:'bbb'}
        ],
        tableProps: {
          defaultSort: {
              prop: 'id',
              order: 'descending'
          }
        },
        bookingSearchDef: {
              show: false
        }
      }
    },
    methods: {
        createNew() {
            //this.$refs.createNewTour.show();
            alert('CreateNewTour');
        },
    },
    computed: {

    }
}
</script>

<style scoped>
  .booking {

  }
</style>
