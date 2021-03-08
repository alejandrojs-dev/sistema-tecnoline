import OrderService from '../../services/tecnoline/Order.service'

const defaultState = () => {
  return {
    orders: [],
  }
}

const state = defaultState()

const mutations = {
  SET_ORDERS: (state, payload) => {
    state.orders = payload
  },
}

const actions = {
  async getOrders({ commit }) {
    const { data } = await OrderService.getAll()
    return data
  },
}

const getters = {}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
