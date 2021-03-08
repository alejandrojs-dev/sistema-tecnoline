import TicketTypeService from '../../services/api-tickets/TicketType.service'

const defaultState = () => {
  return {
    ticketTypes: [],
  }
}

const state = defaultState()

const mutations = {
  SET_TICKET_TYPES: (state, payload) => {
    state.ticketTypes = payload.data
  },
  RESET_STATE: state => {
    Object.assign(state, defaultState())
  },
}

const actions = {
  async get({ commit }) {
    const { data } = await TicketTypeService.all()
    commit('SET_TICKET_TYPES', data)
  },
  resetState({ commit }) {
    commit('RESET_STATE')
  },
}

const getters = {
  ticketTypes: state => state.ticketTypes,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
