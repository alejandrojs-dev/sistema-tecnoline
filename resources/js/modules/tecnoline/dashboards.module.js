import DashboardService from '../../services/tecnoline/Dashboard.service'
import DepartmentService from '../../services/tecnoline/Department.service'

const defaultState = () => {
  return {
    dashboards: [],
    edit: false,
    openDialogDelete: false,
    openForm: false,
    dashboardsByDep: [],
    onlyDashboards: [],
    currentDashboard: {},
    dynamicDataContainer: null,
    currentIndexCarousel: null,
  }
}

const state = defaultState()

const mutations = {
  SET_DASHBOARDS: (state, payload) => {
    state.dashboards = payload.dashboards
  },
  SET_DASHBOARDS_BY_DEP: (state, payload) => {
    state.dashboardsByDep = payload.dashboards
    state.onlyDashboards = payload.dashboards.filter(d => d.isSlide === 1)
  },
  ADD_DASHBOARD_TABLE: (state, payload) => {
    state.dashboards.push(payload.dashboard)
  },
  UPDATE_DASHBOARD_TABLE: (state, payload) => {
    const dashboard = state.dashboards.find(d => d.id === payload.dashboard.id)
    if (dashboard) Object.assign(dashboard, payload.dashboard)
  },
  DELETE_DASHBOARD_TABLE: (state, payload) => {
    const index = state.dashboards.findIndex(d => d.id === payload.id)
    if (index > -1) {
      state.dashboards.splice(index, 1)
    }
  },
  EDIT: (state, payload) => {
    state.edit = payload.edit
  },
  OPEN_DIALOG_DELETE: (state, payload) => {
    state.openDialogDelete = !state.openDialogDelete
  },
  OPEN_FORM: (state, payload) => {
    state.openForm = !state.openForm
  },
  SET_CURRENT_DASHBOARD_ID: (state, payload) => {
    state.currentDashboard = payload
  },
  SET_DYNAMIC_DATA_CONTAINER: (state, payload) => {
    state.dynamicDataContainer = payload.response.data
  },
  SET_CURRENT_INDEX_CAROUSEL: (state, payload) => {
    state.currentIndexCarousel = payload.index
  },
  CLEAN_DYNAMIC_DATA_CONTAINER: (state, payload) => {
    state.dynamicDataContainer = null
  },
}

const actions = {
  async get({ commit }, payload) {
    const { data } = await DashboardService.getAll()
    commit('SET_DASHBOARDS', data)
  },
  async getById({ commit }, payload) {
    const { data } = await DashboardService.getById(payload)
    return data
  },
  async getByDepartment({ commit }, payload) {
    const { data } = await DashboardService.getByDep(payload)
    commit('SET_DASHBOARDS_BY_DEP', data)
    return data
  },
  async getBIData({ commit }, payload) {
    const { data } = await DashboardService.getBIData(payload)
    commit('SET_DYNAMIC_DATA_CONTAINER', data)
    return data
  },
  async getDepartments({ commit }, payload) {
    const { data } = await DepartmentService.getAll()
    return data
  },
  async save({ commit }, payload) {
    const { data } = await DashboardService.save(payload)
    return data
  },
  async update({ commit }, payload) {
    const { data } = await DashboardService.update(payload)
    return data
  },
  async delete({ commit }, payload) {
    const { data } = await DashboardService.delete(payload)
    return data
  },
  async createVueFileComponent({ commit }, payload) {
    const { data } = await DashboardService.createVueFileComponent(payload)
    return data
  },
  addDashboardTable({ commit }, payload) {
    commit('ADD_DASHBOARD_TABLE', payload)
  },
  updateDashboardTable({ commit }, payload) {
    commit('UPDATE_DASHBOARD_TABLE', payload)
  },
  deleteDashboardTable({ commit }, payload) {
    commit('DELETE_DASHBOARD_TABLE', payload)
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
  setCurrentDashboardId({ commit }, payload) {
    commit('SET_CURRENT_DASHBOARD_ID', payload)
  },
  setCurrentIndexCarousel({ commit }, payload) {
    commit('SET_CURRENT_INDEX_CAROUSEL', payload)
  },
  cleanDynamicDataContainer({ commit }, payload) {
    commit('CLEAN_DYNAMIC_DATA_CONTAINER')
  },
}

const getters = {
  dashboards: state => state.dashboards,
  dashboardsByDep: state => state.dashboardsByDep,
  edit: state => state.edit,
  openForm: state => state.openForm,
  openDialogDelete: state => state.openDialogDelete,
  onlyDashboards: state => state.onlyDashboards,
  currentDashboard: state => state.currentDashboard,
  dynamicDataContainer: state => state.dynamicDataContainer,
  currentIndexCarousel: state => state.currentIndexCarousel,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
