import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import { i18n } from '~/plugins'
import App from '~/components/App'

import '~/components'

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import { Button, Popover, Icon, Table, Dialog, Tabs, Notification, Tree, DatePicker } from 'element-ui'
import { DataTables } from 'vue-data-tables'
import  lang  from 'element-ui/lib/locale/lang/en'
import  locale  from 'element-ui/lib/locale'

//Vue.use(ElementUI, { locale })
locale.use(lang)
Vue.use(ElementUI)
Vue.use(Button)
Vue.use(Popover)
Vue.use(Icon)
Vue.use(Table)
Vue.use(Dialog)
Vue.use(Tabs)
Vue.use(Notification)
Vue.use(DataTables)
Vue.use(Tree)
Vue.use(DatePicker)

var VueEventBus = require('vue-event-bus')
Vue.use(VueEventBus)

Vue.config.productionTip = false
window.config.appItems = [
    { name: 'Users', label:'Company', img: 'users.png', url: 'users' },
    { name: 'Leads', label:'Leads', img: 'leads.png', url: 'leads' },
    { name: 'Deals', label:'Deals', img: 'deals.png', url: 'deals' },
    { name: 'Tasks', label:'Tasks', img: 'tasks.png', url: 'tasks' },
    { name: 'Reports', label:'Reports', img: 'reports.png', url: 'reports' },
    { name: 'Booking', label:'Booking', img: 'reserve.png', url: 'booking' }
]
new Vue({
  i18n,
  store,
  router,
  ...App
})
