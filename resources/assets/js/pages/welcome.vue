<template>
  <div>

    <div class="top-right links">
      <template v-if="authenticated">
        <router-link :to="{ name: 'home' }">
          {{ $t('home') }}
        </router-link>
      </template>
      <!--<template v-else>-->
        <!--<router-link :to="{ name: 'login' }">-->
          <!--{{ $t('login') }}-->
        <!--</router-link>-->
        <!--<router-link :to="{ name: 'register' }">-->
          <!--{{ $t('register') }}-->
        <!--</router-link>-->
      <!--</template>-->
    </div>
    <div class="container wellcom-container">


      <div class="row justify-content-center">
          <div class="col col-4 text-center">
              <img src="../../images/Logo.png" ></img>
          </div>
      </div>
      <div class="row justify-content-center">

          <div class="col col-12 text-center">
              <!--<div class="title mb-4">-->
                  <!--{{ title }}-->
              <!--</div>-->
              <h2>{{title}}</h2>
          </div>

      </div>
      <div class="row justify-content-center">
          <div class="col col-12 text-center">
                <login></login>
          </div>
      </div>
                    <!--<div class="row justify-content-center">-->
                        <!--<div class="col" v-for="(item,index) in appItems">-->
                            <!--<router-link :to="{ name: item.url }">-->
                                <!--<div class="card text-center">-->
                                    <!--<div class="card-header">-->
                                        <!--{{item.name}}-->
                                     <!--</div>-->
                                    <!--<div class="card-block">-->
                                        <!--&lt;!&ndash;<img class="card-img-top" v-bind:src="'../../icons/'+item.img" v-bind:alt="item.name">&ndash;&gt;-->

                                        <!--<img class="img-app" v-bind:src="'/images/'+item.img" width="70px" height="70px"  v-bind:title="'Service:'+item.name"  v-bind:alt="item.name">-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</router-link>-->
                        <!--</div>-->
                    <!--</div>-->

        </div>
    </div>

</template>

<script>
import { mapGetters } from 'vuex'
import Login from '~/pages/auth/login.vue'

export default {
  layout: 'default',
  components: {
        Login
  },
  mounted() {
        //console.log('Wellcome, notify:',this.$notify);
        //this.$notify({duration:0});
  },
  metaInfo () {
    return { title: this.$t('home') }
  },

  computed: mapGetters({
    authenticated: 'authCheck'
  }),

  data: () => ({
    title: window.config.appName,
    appItems:window.config.appItems
//    appItems:[
//        {name:'Users',img:'users.png'},
//        {name:'Leads',img:'leads.png'},
//        {name:'Deals',img:'deals.png'},
//        {name:'Tasks',img:'tasks.png'},
//        {name:'Reports',img:'reports.png'},
//        {name:'Reserve',img:'reserve.png'},
//    ]
  })
}
</script>

<style scoped>
.wellcom-container {
    width:768px;
}
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}
.img-app {
    margin:5px;
}
</style>
