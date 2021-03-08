const defaultState = () => {
  return {
    alertInfo: {},
    error: false,
    showDialogDeleteConfirm: false,
    notifications: [],
    //notification: null,
    showNotification: false,
    intervals: [],
    ticket: null,
    idleTimerId: null,
    showLoader: true,
    dataNotification: {},
  }
}

const state = defaultState()

const mutations = {
  SET_ALERT_INFO_DATA: (state, payload) => {
    state.alertInfo.type = payload.type
    state.alertInfo.message = payload.message
    state.alertInfo.icon = payload.icon
    state.alertInfo.errors = payload.errors
  },
  SET_ERROR: (state, payload) => {
    state.error = payload.error
  },
  SHOW_DIALOG_DELETE_CONFIRM: state => {
    state.showDialogDeleteConfirm = !state.showDialogDeleteConfirm
  },
  SET_NOTIFICATION: (state, payload) => {
    state.dataNotification.notification = payload.notification
    state.dataNotification.show = payload.show
  },
  SAVE_INTERVAL: (state, payload) => {
    state.intervals.push(payload)
  },
  DELETE_INTERVAL: (state, payload) => {
    const { objInterval } = payload
    const index = state.intervals.indexOf(objInterval)

    if (index > -1) {
      state.intervals.splice(index, 1)
      clearInterval(objInterval.interval)
    }
  },
  RESET_STATE: state => {
    Object.assign(state, defaultState())
  },
  SET_IDLE_TIMER_ID: (state, payload) => {
    state.idleTimerId = payload.idleTimerId
  },
}

const actions = {
  resetState({ commit }) {
    commit('RESET_STATE')
  },
  setAlertInfoData({ commit }, payload) {
    commit('SET_ALERT_INFO_DATA', payload)
  },
  setError({ commit }, payload) {
    commit('SET_ERROR', payload)
  },
  showDialogDeleteConfirm({ commit }) {
    commit('SHOW_DIALOG_DELETE_CONFIRM')
  },
  setNotification({ commit }, payload) {
    commit('SET_NOTIFICATION', payload)
  },
  setNotifications({ commit }, payload) {
    commit('SET_NOTIFICATIONS', payload)
  },
  setIdleTimerId({ commit }, payload) {
    commit('SET_IDLE_TIMER_ID', payload)
  },
}

const getters = {
  alertInfo: state => state.alertInfo,
  error: state => state.error,
  showDialogDeleteConfirm: state => state.showDialogDeleteConfirm,
  notifications: state => state.notifications,
  notification: state => state.notification,
  showNotification: state => state.showNotification,
  intervals: state => state.intervals,
  ticket: state => state.ticket,
  idleTimerId: state => state.idleTimerId,
  dataNotification: state => state.dataNotification,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
