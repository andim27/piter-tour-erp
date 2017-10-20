import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import { i18n } from '~/plugins'
import App from '~/components/App'

import '~/components'

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import { Button, Popover, Icon, Table, Dialog } from 'element-ui'

Vue.use(ElementUI)
Vue.use(Button)
Vue.use(Popover)
Vue.use(Icon)
Vue.use(Table)
Vue.use(Dialog)


var VueEventBus = require('vue-event-bus')
Vue.use(VueEventBus)

Vue.config.productionTip = false
window.config.appItems = [
    { name: 'Users', img: 'users.png', url: 'users' },
    { name: 'Leads', img: 'leads.png', url: 'leads' },
    { name: 'Deals', img: 'deals.png', url: 'deals' },
    { name: 'Tasks', img: 'tasks.png', url: 'tasks' },
    { name: 'Reports', img: 'reports.png', url: 'reports' },
    { name: 'Reserve', img: 'reserve.png', url: 'reserve' }
]
new Vue({
  i18n,
  store,
  router,
  ...App
})
