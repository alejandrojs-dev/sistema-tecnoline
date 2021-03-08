import AuthService from '../../services/tecnoline/Auth.service'

const defaultState = () => {
  return {
    dataUser: {
      user: null,
      token: {
        value: null,
        isValid: null,
      },
    },
  }
}

const state = defaultState()

const mutations = {
  SET_USER_DATA: (state, data) => {
    state.dataUser.user = data.user
    state.dataUser.token = data.token
  },
  SET_TOKEN_IS_VALID: (state, payload) => {
    state.dataUser.token.isValid = payload.isValid
  },
  RESET_STATE: state => {
    Object.assign(state, defaultState())
  },
}

const actions = {
  async login({ commit }, payload) {
    const { data } = await AuthService.login(payload)
    commit('SET_USER_DATA', data)
    return data
  },
  async logout({ commit }) {
    const { data } = await AuthService.logout()
    return data
  },
  async verifyTokenIsValid() {
    const { data } = await AuthService.verifyTokenIsValid()
    return data
  },
  async verifyUserHasPermission({ commit }, payload) {
    const { data } = await AuthService.verifyUserHasPermission(payload.permission)
    return data
  },
  setTokenIsValid({ commit }, payload) {
    commit('SET_TOKEN_IS_VALID', payload)
  },
  resetState({ commit }) {
    commit('RESET_STATE')
  },
}

const getters = {
  dataUser: state => state.dataUser,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
