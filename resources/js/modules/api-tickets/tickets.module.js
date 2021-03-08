import TicketService from '../../services/api-tickets/Ticket.service'
import PriorityService from '../../services/api-tickets/Priority.service'
import socketClient from '../../shared/SocketClient'
import { ServiceLevelTimer } from '../../shared/ServiceLevelTimer'

const defaultState = () => {
  return {
    // ticketGroupId: null,
    // ticketSubGroupId: null,
    // ticketTypeId: null,
    // ticketGroupName: null,
    // ticketSubGroupName: null,
    // userTicketCreate: null,
    // ticketGroupUserIds: [],
    // usersWhoAuthorize: [],
    tickets: [],
    ticketsToAuthorize: [],
    authorizeUsersByTicket: [],
    sharedTicketModalData: {},
    declineTicketData: {},
    takeTicketData: {},
    cancelTicketData: {},
    reassignTicketData: {},
    pauseTicketData: {},
    openTSTicketForm: false,
    openTACTicketForm: false,
    openDialogReasonDecline: false,
    openDialogReasonCancel: false,
    openDialogDetails: false,
    openDialogDetailsAuth: false,
    openDialogTakeTicket: false,
    openDialogReassignTicket: false,
    openDialogPauseTicket: false,
    priorities: [],
    ticketDetails: {},
    serviceLevelTimerData: {
      timerTextContent: null,
      semaphoreColor: 'green',
      expired: false,
      changedColor: false,
    },
  }
}

const state = defaultState()

const mutations = {
  RESET_STATE: state => {
    Object.assign(state, defaultState())
  },
  SET_PRIORITIES: (state, payload) => {
    state.priorities = payload.data
  },
  SET_SHARED_TICKET_MODAL_DATA: (state, payload) => {
    state.sharedTicketModalData = payload.sharedTicketModalData
  },
  SET_SHARED_TICKET_DECLINE_DATA: (state, payload) => {
    state.declineTicketData = payload
  },
  SET_AUTHORIZE_USERS_BY_TICKET: (state, payload) => {
    state.authorizeUsersByTicket = payload.data
  },
  OPEN_TS_TICKET_FORM: state => {
    state.openTSTicketForm = !state.openTSTicketForm
  },
  OPEN_TAC_TICKET_FORM: state => {
    state.openTACTicketForm = !state.openTACTicketForm
  },
  OPEN_DIALOG_REASON_CANCEL: state => {
    state.openDialogReasonCancel = !state.openDialogReasonCancel
  },
  OPEN_DIALOG_REASON_DECLINE: state => {
    state.openDialogReasonDecline = !state.openDialogReasonDecline
  },
  OPEN_DIALOG_DETAILS: state => {
    state.openDialogDetails = !state.openDialogDetails
  },
  OPEN_DIALOG_DETAILS_AUTH: state => {
    state.openDialogDetailsAuth = !state.openDialogDetailsAuth
  },
  SET_TAKE_TICKET_DATA: (state, payload) => {
    state.takeTicketData = payload
  },
  SET_CANCEL_TICKET_DATA: (state, payload) => {
    state.cancelTicketData = payload
  },
  SET_REASSIGN_TICKET_DATA: (state, payload) => {
    state.reassignTicketData = payload
  },
  OPEN_DIALOG_TAKE_TICKET: state => {
    state.openDialogTakeTicket = !state.openDialogTakeTicket
  },
  OPEN_DIALOG_REASSIGN_TICKET: state => {
    state.openDialogReassignTicket = !state.openDialogReassignTicket
  },
  OPEN_DIALOG_PAUSE_TICKET: state => {
    state.openDialogPauseTicket = !state.openDialogPauseTicket
  },
  SET_PAUSE_TICKET_DATA: (state, payload) => {
    state.pauseTicketData = payload
  },
  SET_DECLINE_TICKET_DATA: (state, payload) => {
    state.declineTicketData = payload
  },
  SET_TICKET_DETAILS: (state, payload) => {
    state.ticketDetails = payload
  },
  INIT_SERVICE_LEVEL_TIMER_TICKET: (state, payload) => {
    const serviceLevelTimer = new ServiceLevelTimer()
    serviceLevelTimer.start(payload.ticket)
  },
  SET_TICKETS: (state, payload) => {
    state.tickets = payload.data
  },
  SET_AUTHORIZE_TICKETS: (state, payload) => {
    state.ticketsToAuthorize = payload.data
  },
  UPDATE_SERVICE_LEVEL_DATA_TICKET: (state, payload) => {
    const ticket = state.tickets.find(t => t.id === payload.id)
    switch (payload.option) {
      case 1:
        ticket.timerTextContent = payload.timerTextContent
        break
      case 2:
        ticket.semaphoreColor = payload.semaphoreColor
        break
      case 3:
        ticket.semaphoreColor = payload.semaphoreColor
        ticket.expired = payload.expired
        ticket.timerTextContent = payload.timerTextContent
        break
    }
  },
  UPDATE_TICKET: (state, payload) => {
    const ticket = state.tickets.find(t => t.id === payload.ticket.id)
    Object.assign(ticket, payload.ticket)
  },
  UPDATE_AUTHORIZE_TICKET: (state, payload) => {
    const ticket = state.ticketsToAuthorize.find(t => t.id === payload.ticket.id)
    Object.assign(ticket, payload.ticket)
  },
  UPDATE_TICKETS: (state, payload) => {
    state.tickets = payload.tickets
  },
  UPDATE_AUTHORIZE_TICKETS: (state, payload) => {
    state.ticketsToAuthorize = payload.ticketsToAuthorize
  },
}

const actions = {
  async getPriorities({ commit }) {
    const { data } = await PriorityService.get()
    commit('SET_PRIORITIES', data)
    return data
  },
  async getTickets({ commit }, payload) {
    const { data } = await TicketService.get(payload.userId)
    commit('SET_TICKETS', data)
    return data
  },
  async getToAuthorizeByUser({ commit }, payload) {
    const { data } = await TicketService.getToAuthorizeByUser(payload)
    commit('SET_AUTHORIZE_TICKETS', data)
    return data
  },
  async getAuthorizeUsersByTicket({ commit }, payload) {
    const { data } = await TicketService.getAuthorizeUsersByTicket(payload.ticketId)
    commit('SET_AUTHORIZE_USERS_BY_TICKET', data)
  },
  async saveTSTicket({ commit }, payload) {
    const response = await socketClient.saveTSTicketEmit(payload)
    return response
  },
  async saveTACTicket({ commit }, payload) {
    const response = await socketClient.saveTACTicketEmit(payload)
    return response
  },
  async take({ commit }, payload) {
    const response = await socketClient.takeEmit(payload)
    return response
  },
  async cancel({ commit }, payload) {
    const response = await socketClient.cancelEmit(payload)
    return response
  },
  async reassign({ commit }, payload) {
    const response = await socketClient.reassignEmit(payload)
    return response
  },
  async pause({ commit }, payload) {
    const response = await socketClient.pauseEmit(payload)
    return response
  },
  async resume({ commit }, payload) {
    const response = await socketClient.resumeEmit(payload)
    return response
  },
  async authorize({ commit }, payload) {
    const response = await socketClient.authorizeEmit(payload)
    return response
  },
  async decline({ commit }, payload) {
    const response = await socketClient.declineEmit(payload)
    return response
  },
  async clickOnNotification({ commit }, payload) {
    const response = await socketClient.clickNotificationEmit(payload)
    return response
  },
  setSharedTicketModalData({ commit }, payload) {
    commit('SET_SHARED_TICKET_MODAL_DATA', payload)
  },
  setDeclineTicketData({ commit }, payload) {
    commit('SET_DECLINE_TICKET_DATA', payload)
  },
  openTSTicketForm({ commit }) {
    commit('OPEN_TS_TICKET_FORM')
  },
  openTACTicketForm({ commit }) {
    commit('OPEN_TAC_TICKET_FORM')
  },
  openDialogReasonDecline({ commit }) {
    commit('OPEN_DIALOG_REASON_DECLINE')
  },
  openDialogReasonCancel({ commit }) {
    commit('OPEN_DIALOG_REASON_CANCEL')
  },
  openDialogTakeTicket({ commit }) {
    commit('OPEN_DIALOG_TAKE_TICKET')
  },
  openDialogReassignTicket({ commit }) {
    commit('OPEN_DIALOG_REASSIGN_TICKET')
  },
  openDialogPauseTicket({ commit }) {
    commit('OPEN_DIALOG_PAUSE_TICKET')
  },
  openDialogDetails({ commit }) {
    commit('OPEN_DIALOG_DETAILS')
  },
  openDialogDetailsAuth({ commit }) {
    commit('OPEN_DIALOG_DETAILS_AUTH')
  },
  resetState({ commit }) {
    commit('RESET_STATE')
  },
  setTakeTicketData({ commit }, payload) {
    commit('SET_TAKE_TICKET_DATA', payload)
  },
  setCancelTicketData({ commit }, payload) {
    commit('SET_CANCEL_TICKET_DATA', payload)
  },
  setReassignTicketData({ commit }, payload) {
    commit('SET_REASSIGN_TICKET_DATA', payload)
  },
  setPauseTicketData({ commit }, payload) {
    commit('SET_PAUSE_TICKET_DATA', payload)
  },
  setTicketDetails({ commit }, payload) {
    commit('SET_TICKET_DETAILS', payload)
  },
  initServiceLevelTimerTicket({ commit }, payload) {
    commit('INIT_SERVICE_LEVEL_TIMER_TICKET', payload)
  },
}

const getters = {
  openTSTicketForm: state => state.openTSTicketForm,
  openTACTicketForm: state => state.openTACTicketForm,
  openDialogReasonDecline: state => state.openDialogReasonDecline,
  openDialogReasonCancel: state => state.openDialogReasonCancel,
  openDialogDetails: state => state.openDialogDetails,
  openDialogDetailsAuth: state => state.openDialogDetailsAuth,
  openDialogTakeTicket: state => state.openDialogTakeTicket,
  openDialogReassignTicket: state => state.openDialogReassignTicket,
  openDialogPauseTicket: state => state.openDialogPauseTicket,
  priorities: state => state.priorities,
  sharedTicketModalData: state => state.sharedTicketModalData,
  declineTicketData: state => state.declineTicketData,
  takeTicketData: state => state.takeTicketData,
  cancelTicketData: state => state.cancelTicketData,
  reassignTicketData: state => state.reassignTicketData,
  pauseTicketData: state => state.pauseTicketData,
  authorizeUsersByTicket: state => state.authorizeUsersByTicket,
  ticketDetails: state => state.ticketDetails,
  serviceLevelTimerData: state => state.serviceLevelTimerData,
  tickets: state => state.tickets,
  ticketsToAuthorize: state => state.ticketsToAuthorize,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
