require('./bootstrap')

import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/store'
import vuetify from './vuetify'
import Vuelidate from 'vuelidate'
import setTitlePages from './shared/setTitlePages'

Vue.use(Vuelidate)
Vue.mixin(setTitlePages)

const app = new Vue({
  el: '#app',
  components: {
    App,
  },
  router,
  store,
  vuetify,
})

export { app }
