import ProcessService from '../../services/tecnoline/Process.service'

const defaultState = () => {
  return {
    process: {
      id: null,
      name: null,
      slug: null,
      icon: null,
    },
    checksMap: [],
    forms: [],
  }
}

const state = defaultState()

const mutations = {
  SET_PROCESS: (state, payload) => {
    state.process.id = payload.processId
    state.process.name = payload.processName
    state.process.slug = payload.processSlug
    state.process.icon = payload.processIcon
  },
  SET_CHECKS: (state, payload) => {
    state.forms = payload.order.items.map(item => {
      const checks = item.checks.map(c => {
        return {
          id: `chk-${item.itemNumber}-${c.id}`,
          description: c.description,
          isRequired: c.isRequired,
          isChecked: c.isChecked,
          isInvalid: c.isInvalid,
        }
      })
      return {
        id: item.itemNumber,
        isValid: false,
        checks: checks,
      }
    })
  },
  ADD_KEY_BY_CHECK: (state, payload) => {
    const form = state.forms.find(f => f.id === payload.itemNumber)
    const check = form.checks.find(c => c.id === payload.id)
    if (check.isRequired) {
      check.isChecked = !check.isChecked
      if (!check.isChecked) {
        check.isInvalid = true
      } else {
        check.isInvalid = false
      }
    }
  },
  DELETE_FORM: (state, payload) => {
    const index = state.forms.findIndex(f => (f.id = payload.itemNumber))
    if (index > -1) {
      state.forms.splice(index, 1)
    }
  },
  SET_CHECK_ISINVALID: (state, payload) => {
    const form = state.forms.find(f => f.id === payload.itemNumber)
    const check = form.checks.find(c => c.id === payload.id)
    check.isInvalid = true
  },
  RESET_STATE: state => {
    Object.assign(state, defaultState())
  },
}

const actions = {
  setProcess({ commit }, payload) {
    commit('SET_PROCESS', payload)
  },
  resetState({ commit }) {
    commit('RESET_STATE')
  },
  addKeyByCheck({ commit }, payload) {
    commit('ADD_KEY_BY_CHECK', payload)
  },
  setCheckIsInvalid({ commit }, payload) {
    commit('SET_CHECK_ISINVALID', payload)
  },
  deleteForm({ commit }, payload) {
    commit('DELETE_FORM', payload)
  },
  async getCountItemsByProcess({ commit }, payload) {
    const { data } = await ProcessService.getCountItemsByProcess(payload.processId, payload.alias)
    return data
  },
  async getItemsByOrder({ commit }, payload) {
    const { data } = await ProcessService.getItemsByOrder(payload.orderId, payload.processId)
    commit('SET_CHECKS', data)
    return data
  },
  async sentItemToNextProcess({ commit }, payload) {
    const { data } = await ProcessService.sentItemToNextProcess(payload.itemId, payload.nextProcessId)
    return data
  },
}

const getters = {
  process: state => state.process,
  forms: state => state.forms,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
