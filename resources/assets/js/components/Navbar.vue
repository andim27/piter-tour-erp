<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <div class="top-left links">
            <router-link :to="{ name: 'home' }" class="navbar-brand">
                <img src="../../images/Logo.png"class="img-thumbnail" ></img>
            </router-link>

        </div>
      <!--<router-link :to="{ name: 'welcome' }" class="navbar-brand">-->
        <!--{{ appName }}-->
      <!--</router-link>-->

      <button class="navbar-toggler" type="button" data-toggle="collapse"
        data-target="#navbarToggler" aria-controls="navbarToggler"
        aria-expanded="false" :aria-label="$t('toggle_navigation')"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarToggler">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li> -->
        </ul>

        <ul class="navbar-nav">
          <!-- Authenticated -->
          <template v-if="authenticated">

              <li class="nav-item">
                      <el-popover
                              ref="popoverAppsList"
                              placement="bottom"
                              width="300"
                              trigger="click"
                              v-model="popoverAppState"
                              content="">
                                 <div class="card">
                                   <div class="container">
                                          <!--<h5 class="card-header text-center">Services</h5>-->
                                          <div class="row align-items-start"  >

                                                  <div v-for="(item,index) in popoverItems" class="col col-6 col-padding-15" >
                                                      <div class="card text-center" @click="clickListApps(index)">
                                                          <h6 class="card-title">
                                                              {{item.label}}
                                                          </h6>
                                                          <div class="card-block" >
                                                              <img class="img-app" v-bind:src="'/images/'+item.img"
                                                                   width="50px" height="50px"
                                                                   v-bind:title="'Service:'+item.name"
                                                                   v-bind:alt="item.name">
                                                          </div>
                                                      </div>
                                                  </div>
                                                 <div v-if="index % 2 ===0" class="clearfix hidden-sm-up"></div>


                                          </div>
                                      </div>
                                  </div>

                      </el-popover>
                  <a class="nav-link">
                      <i class="el-icon-menu nav-item"  v-popover:popoverAppsList ></i>
                  </a>
              </li>
              <!--<img  v-popover:popoverAppsList src="../../icons/table.svg"  width="30px" height="30px" class="img-thumbnail" ></img>-->
            <router-link :to="{ name: 'settings.profile' }" tag="li" class="nav-item">
              <a class="nav-link">
                {{ user.name }}
              </a>
            </router-link>
            <li class="nav-item">
              <a @click.prevent="logout" href="#" class="nav-link">
                {{ $t('logout') }}
              </a>
            </li>
          </template>
          <!-- Guest -->
          <template v-else>
            <li class="nav-item">
              <router-link :to="{ name: 'login' }" class="nav-link" active-class="active">
                {{ $t('login') }}
              </router-link>
            </li>
            <!--<li class="nav-item">-->
              <!--<router-link :to="{ name: 'register' }" class="nav-link" active-class="active">-->
                <!--{{ $t('register') }}-->
              <!--</router-link>-->
            <!--</li>-->
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'
//import { Popover, Button, Icon } from 'element-ui'

export default {
  components :{
    //Popover, Button,  Icon
  },
  data: () => ({
    appName: window.config.appName,
    popoverAppState:false,
    popoverItems:window.config.appItems
  }),

  computed: mapGetters({
    user: 'authUser',
    authenticated: 'authCheck'
  }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('logout')

      // Redirect to login.
      this.$router.push({ name: 'welcome' })
    },
    clickListApps(index) {
        //alert('click apps');
        //this.$refs.popoverAppsList.hide();
        this.popoverAppState=false
        this.$router.push({name:this.popoverItems[index].url})
    }
  }
}
</script>

<style scoped>
.navbar {
  border: 1px solid #d3e0e9;
}
.col-padding-15 {
    padding: 15px;
}
</style>
