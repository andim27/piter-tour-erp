<template>
  <card :title="$t('dashboard')">

      <div class="row justify-content-center">
          <div class="col" v-for="(item,index) in appItems">
              <router-link :to="{ name: item.url }">
                  <div class="card text-center">
                      <div class="card-header">
                          {{item.label}}
                      </div>
                      <div class="card-block">
                          <!--<img class="card-img-top" v-bind:src="'../../icons/'+item.img" v-bind:alt="item.name">-->

                          <img class="img-app" v-bind:src="'/images/'+item.img" width="70px" height="70px"  v-bind:title="'Service:'+item.name"  v-bind:alt="item.name">
                      </div>
                  </div>
              </router-link>
          </div>
      </div>
  </card>
</template>

<script>
export default {
  metaInfo () {
    return { title: this.$t('home') }
  },
    data: () => ({
    title: window.config.appName,
    appItems:window.config.appItems

    })
}
</script>
