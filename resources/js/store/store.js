import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersistence from 'vuex-persist'
import auth from '../modules/tecnoline/auth.module'
import login from '../modules/login.module'
import users from '../modules/users.module'
import actions from '../modules/actions.module'
import ticketGroups from '../modules/api-tickets/ticketGroups.module'
import ticketSubGroups from '../modules/api-tickets/ticketSubGroups.module'
import ticketTypes from '../modules/api-tickets/ticketTypes.module'
import tickets from '../modules/api-tickets/tickets.module'
import orders from '../modules/tecnoline/orders.module'
import processes from '../modules/tecnoline/processes.module'
import dashboards from '../modules/tecnoline/dashboards.module'

Vue.use(Vuex)

const vuexAuth = new VuexPersistence({
  key: 'dataAuth',
  storage: window.sessionStorage,
  modules: ['auth', 'processes', 'dashboards', 'ticketGroups'],
})

const store = new Vuex.Store({
  modules: {
    auth,
    login,
    users,
    actions,
    ticketGroups,
    ticketSubGroups,
    ticketTypes,
    tickets,
    orders,
    processes,
    dashboards,
  },
  strict: process.env.NODE_ENV !== 'production',
  plugins: [vuexAuth.plugin],
})

export default store
