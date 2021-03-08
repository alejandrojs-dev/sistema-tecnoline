const defaultState = () => {
  return {
    openLoginForm: false,
  }
}

const state = defaultState()

const mutations = {
  OPEN_LOGIN_FORM: (state, payload) => {
    state.openLoginForm = !state.openLoginForm
  },
  RESET_STATE: state => {
    Object.assign(state, defaultState())
  },
}

const actions = {
  openLoginForm({ commit }, payload) {
    commit('OPEN_LOGIN_FORM')
  },
  resetState({ commit }, payload) {
    commit('RESET_STATE')
  },
}

const getters = {
  openLoginForm: state => state.openLoginForm,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
