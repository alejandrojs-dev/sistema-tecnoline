import TicketSubGroupService from '../../services/api-tickets/TicketSubGroup.service'

const defaultState = () => {
  return {
    ticketSubGroup: {},
    ticketSubGroups: [],
    edit: false,
    openForm: false,
    openDialogDelete: false,
  }
}

const state = defaultState()

const compare = (a, b) => {
  if (a.subGroupName < b.subGroupName) return -1
  if (a.subGroupName > b.subGroupName) return -1
  return 0
}

const mutations = {
  SET_TICKET_SUBGROUPS: (state, payload) => {
    state.ticketSubGroups = payload.data
  },
  EDIT: (state, payload) => {
    state.edit = payload.edit
  },
  OPEN_FORM: (state, payload) => {
    state.openForm = !state.openForm
  },
  OPEN_DIALOG_DELETE: (state, payload) => {
    state.openDialogDelete = !state.openDialogDelete
  },
  ADD_TS_TABLE: (state, payload) => {
    const { ticketSubGroup } = payload
    const objTS = {}
    objTS.subGroupName = ticketSubGroup.name
    objTS.serviceLevel = ticketSubGroup.service_level
    objTS.numberAuth = ticketSubGroup.number_auth
    objTS.groupName = ticketSubGroup.ticketGroup.name
    objTS.ticketType = ticketSubGroup.ticketType.type
    objTS.active = ticketSubGroup.active
    state.ticketSubGroups.push(objTS)
    state.ticketSubGroups.sort(compare)
  },
  UPDATE_TS_TABLE: (state, payload) => {
    const { ticketSubGroup } = payload
    const ticketSubGroupArray = state.ticketSubGroups.find(ts => ts.id === ticketSubGroup.id)
    const objTS = {}
    objTS.subGroupName = ticketSubGroup.name
    objTS.serviceLevel = ticketSubGroup.service_level
    objTS.numberAuth = ticketSubGroup.number_auth
    objTS.groupName = ticketSubGroup.ticketGroup.name
    objTS.ticketType = ticketSubGroup.ticketType.type
    objTS.active = ticketSubGroup.active
    Object.assign(ticketSubGroupArray, objTS)
  },
  DELETE_TS_TABLE: (state, payload) => {
    const index = state.ticketSubGroups.findIndex(ts => ts.id === payload.id)
    if (index > -1) state.ticketSubGroups.splice(index, 1)
  },
  RESET_STATE: state => {
    Object.assign(state, defaultState())
  },
}

const actions = {
  async save({ commit }, payload) {
    const { data } = await TicketSubGroupService.save(payload.ticketSubGroup, payload.userId, payload.usersWhoAuthorize)
    return data
  },
  async get({ commit }) {
    const { data } = await TicketSubGroupService.get()
    commit('SET_TICKET_SUBGROUPS', data)
    return data
  },
  async update({ commit }, payload) {
    const { data } = await TicketSubGroupService.update(payload.id, payload.ticketSubGroup, payload.userId, payload.usersWhoAuthorize)
    return data
  },
  async delete({ commit }, payload) {
    const { data } = await TicketSubGroupService.delete(payload.id)
    return data
  },
  async getById({ commit }, payload) {
    const { data } = await TicketSubGroupService.getById(payload.id, payload.ticketTypeId)
    return data
  },
  resetState({ commit }) {
    commit('RESET_STATE')
  },
  edit({ commit }, payload) {
    commit('EDIT', payload)
  },
  openForm({ commit }, payload) {
    commit('OPEN_FORM')
  },
  openDialogDelete({ commit }, payload) {
    commit('OPEN_DIALOG_DELETE')
  },
  addTSTable({ commit }, payload) {
    commit('ADD_TS_TABLE', payload)
  },
  updateTSTable({ commit }, payload) {
    commit('UPDATE_TS_TABLE', payload)
  },
  deleteTSTable({ commit }, payload) {
    commit('DELETE_TS_TABLE', payload)
  },
}

const getters = {
  ticketSubGroup: state => state.ticketSubGroup,
  ticketSubGroups: state => state.ticketSubGroups,
  openForm: state => state.openForm,
  openDialogDelete: state => state.openDialogDelete,
  edit: state => state.edit,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
