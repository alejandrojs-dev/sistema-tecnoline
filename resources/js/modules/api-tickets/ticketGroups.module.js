import TicketGroupService from '../../services/api-tickets/TicketGroup.service'
import UserService from '../../services/api-tickets/User.service'

const defaultState = () => {
  return {
    ticketGroup: {},
    edit: false,
    openForm: false,
    openDialogDelete: false,
    ticketGroups: [],
    ticketGroupsR: [],
  }
}

const state = defaultState()

const mutations = {
  SET_TICKET_GROUPS: (state, payload) => {
    state.ticketGroups = payload.data
  },
  SET_TICKET_GROUPS_RELATIONSHIPS: (state, payload) => {
    state.ticketGroupsR = payload.data
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
  ADD_TG_TABLE: (state, payload) => {
    state.ticketGroups.push(payload.ticketGroup)
  },
  UPDATE_TG_TABLE: (state, payload) => {
    const ticketGroup = state.ticketGroups.find(tg => tg.id === payload.ticketGroup.id)
    if (ticketGroup) Object.assign(ticketGroup, payload.ticketGroup)
  },
  DELETE_TG_TABLE: (state, payload) => {
    const index = state.ticketGroups.findIndex(tg => tg.id === payload.id)
    if (index > -1) state.ticketGroups.splice(index, 1)
  },
  RESET_STATE: state => {
    Object.assign(state, defaultState())
  },
}

const actions = {
  async save({ commit }, payload) {
    const { data } = await TicketGroupService.save(payload.ticketGroup, payload.assignedUsers)
    return data
  },
  async getWithRelationships({ commit }) {
    const { data } = await TicketGroupService.getWithRelationShips()
    commit('SET_TICKET_GROUPS_RELATIONSHIPS', data)
    return data
  },
  async get({ commit }) {
    const { data } = await TicketGroupService.get()
    commit('SET_TICKET_GROUPS', data)
  },
  async getUsers({ commit }) {
    const { data } = await UserService.get()
    return data
  },
  async getUsersByGroup({ commit }, payload) {
    const { data } = await UserService.getByGroup(payload.groupId)
    return data
  },
  async update({ commit }, payload) {
    const { data } = await TicketGroupService.update(payload.id, payload.ticketGroup, payload.assignedUsers)
    return data
  },
  async delete({ commit }, payload) {
    const { data } = await TicketGroupService.delete(payload.id)
    return data
  },
  async getById({ commit }, payload) {
    const { data } = await TicketGroupService.getById(payload.id)
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
  addTGTable({ commit }, payload) {
    commit('ADD_TG_TABLE', payload)
  },
  updateTGTable({ commit }, payload) {
    commit('UPDATE_TG_TABLE', payload)
  },
  deleteTGTable({ commit }, payload) {
    commit('DELETE_TG_TABLE', payload)
  },
}

const getters = {
  ticketGroup: state => state.ticketGroup,
  ticketGroups: state => state.ticketGroups,
  ticketGroupsR: state => state.ticketGroupsR,
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
