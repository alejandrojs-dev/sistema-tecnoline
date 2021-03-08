import AuthService from '../services/tecnoline/Auth.service'
import UserService from '../services/api-tickets/User.service'

const defaultState = () => {
  return {
    userInitials: '',
    usersApi: [],
    usersWeb: [],
    notifications: [],
    notificationsCount: null,
    authTicketsCount: null,
    ticketsTrayCount: null,
  }
}

const state = defaultState()

const mutations = {
  SET_USER_INITIALS: (state, payload) => {
    state.userInitials = ''
    let first = ''
    let second = ''
    let initials = ''

    const { username } = payload

    if (username.includes('.')) {
      const splitUsername = username.split('.')
      first = splitUsername[0].charAt(0).toUpperCase()
      second = splitUsername[1].charAt(0).toUpperCase()
      initials = `${first}${second}`
    } else {
      first = username.charAt(0)
      second = username.slice(-1)
      initials = `${first}${second}`
    }
    state.userInitials = initials
  },
  SET_USER_NOTIFICATIONS: (state, payload) => {
    state.notifications = payload.notifications
    state.notificationsCount = payload.notificationsCount
    state.authTicketsCount = payload.authTicketsCount
    state.ticketsTrayCount = payload.ticketsTrayCount
  },
  SET_USER_AUTH_TICKETS_NOTIFICATIONS: (state, payload) => {
    state.notifications = payload.notifications
    state.notificationsCount = payload.notificationsCount
    state.authTicketsCount = payload.authTicketsCount
  },
  SET_USER_TICKETS_TRAY_NOTIFICATIONS: (state, payload) => {
    state.notifications = payload.notifications
    state.notificationsCount = payload.notificationsCount
    state.ticketsTrayCount = payload.ticketsTrayCount
  },
  SET_USERS_API: (state, payload) => {
    state.usersApi = payload.data
  },
  SET_USERS_WEB: (state, payload) => {
    state.usersWeb = payload.users
  },
  RESET_STATE: state => {
    Object.assign(state, defaultState())
  },
}

const actions = {
  setUserInitials({ commit }, payload) {
    commit('SET_USER_INITIALS', payload)
  },
  resetState({ commit }, payload) {
    commit('RESET_STATE')
  },
  async getUserNotifications({ commit }, payload) {
    const { data } = await AuthService.getUserNotifications(payload.userId)
    commit('SET_USER_NOTIFICATIONS', data)
  },
  async getFromApi({ commit }, payload) {
    const { data } = await UserService.getFromApi()
    commit('SET_USERS_API', data)
  },
  async getFromWeb({ commit }, payload) {
    const { data } = await UserService.getFromWeb()
    commit('SET_USERS_WEB', data)
  },
  setUserNotifications({ commit }, payload) {
    commit('SET_USER_NOTIFICATIONS', payload)
  },
  setUserAuthTicketsNotifications({ commit }, payload) {
    commit('SET_USER_AUTH_TICKETS_NOTIFICATIONS', payload)
  },
  setUserTicketsTrayNotifications({ commit }, payload) {
    commit('SET_USER_TICKETS_TRAY_NOTIFICATIONS', payload)
  },
}

const getters = {
  userInitials: state => state.userInitials,
  usersApi: state => state.usersApi,
  usersWeb: state => state.usersWeb,
  notifications: state => state.notifications,
  notificationsCount: state => state.notificationsCount,
  authTicketsCount: state => state.authTicketsCount,
  ticketsTrayCount: state => state.ticketsTrayCount,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
