import { io } from 'socket.io-client'
import config from '../config/app'
import TicketService from '../services/api-tickets/Ticket.service'
import Toastr from './Toastr'
import store from '../store/store'

class SocketClient {
  constructor() {
    this._socketClient = io(config.TICKETS_WEB_SOCKETS.URI_API)
    this._socketClient.on('disconnect', reason => this._socketClient.connect())
    this._socketClient.on('connect_error', error => {
      this._socketClient.connect()
    })
  }

  userConnectSocketsEmit(userId) {
    this._socketClient.emit('user:connect-login', { userId })
  }

  userConnectSocketsOn() {
    this._socketClient.on('user:connect-login', response => {})
  }

  userConnectSocketsRefreshPageEmit(userId) {
    this._socketClient.emit('user:connect-refresh-page', { userId })
  }

  userConnectSocketsRefreshPageOn() {
    this._socketClient.on('user:connect-refresh-page', response => {})
  }

  async saveTSTicketEmit({ tsTicket, ticketGroupUserIds, userCreate }) {
    const { data } = await TicketService.saveTSTicket(tsTicket)
    if (data.ok) {
      this._socketClient.emit('TSTicket:created', { ticketGroupUserIds, userCreate })
      this._socketClient.emit('tickets-tray:refresh', { ticketGroupUserIds })
      return data
    }
  }

  saveTSTicketOn() {
    this._socketClient.on('TSTicket:created', response => {
      const { notifications, notificationsCount, ticketsTrayCount, userCreate } = response

      const newNotification = new Toastr(
        `Tienes un ticket por autorizar de ${userCreate}`,
        'Nuevo ticket por autorizar',
        7000,
        'toast-top-right',
        0,
        true,
        'fadeOut',
        'fadeIn',
        'fadeOut',
        1000,
        'notification',
      )

      store.dispatch('users/setUserTicketsTrayNotifications', { notifications, notificationsCount, ticketsTrayCount })

      newNotification.launch()
    })
  }

  async saveTACTicketEmit({ tacTicket, usersWhoAuthorize, userCreate }) {
    const { data } = await TicketService.saveTACTicket(tacTicket, usersWhoAuthorize)
    if (data.ok) {
      this._socketClient.emit('TACTicket:created', { usersWhoAuthorize, userCreate })
      this._socketClient.emit('authorized-tickets-tray:refresh', { usersWhoAuthorize })
      return data
    }
  }

  saveTACTicketOn() {
    this._socketClient.on('TACTicket:created', response => {
      const { notifications, notificationsCount, authTicketsCount, userCreate } = response

      const newNotification = new Toastr(
        `Tienes un ticket por autorizar de ${userCreate}`,
        'Nuevo ticket por autorizar',
        7000,
        'toast-top-right',
        0,
        true,
        'fadeOut',
        'fadeIn',
        'fadeOut',
        1000,
        'notification',
      )

      store.dispatch('users/setUserAuthTicketsNotifications', { notifications, notificationsCount, authTicketsCount })

      newNotification.launch()
    })
  }

  refreshTicketsTrayOn() {
    this._socketClient.on('tickets-tray:refresh', response => {
      const { tickets } = response
      store.commit('tickets/UPDATE_TICKETS', { tickets })
    })
  }

  refreshAuthTicketsTrayOn() {
    this._socketClient.on('authorized-tickets-tray:refresh', response => {
      if (response.ok) {
        const { ticketsToAuthorize } = response
        store.commit('tickets/UPDATE_AUTHORIZE_TICKETS', { ticketsToAuthorize })
      }
    })
  }

  refreshAllUserNotificationsOn() {
    this._socketClient.on('all-users-notifications:refresh', response => {
      if (response.ok) {
        const { notifications, notificationsCount, authTicketsCount, ticketsTrayCount } = response
        store.dispatch('users/setUserNotifications', {
          notifications,
          notificationsCount,
          authTicketsCount,
          ticketsTrayCount,
        })
      }
    })
  }

  async takeEmit({ ticketId, status, userTakeTicketId, semaphoreColor, isViewed, ticketGroupUserIds }) {
    const { data } = await TicketService.take(ticketId, status, userTakeTicketId, semaphoreColor, isViewed)
    if (data.ok) {
      const { ticket } = data

      if (ticket.taken) {
        this._socketClient.emit('ticket:take', { ticket, ticketGroupUserIds })
      }

      return data
    }
  }

  takeOn() {
    this._socketClient.on('ticket:take', response => {
      if (response.ok) {
        const { ticket, notifications, notificationsCount } = response
        store.dispatch('users/setUserNotifications', {
          notifications,
          notificationsCount,
        })

        store.commit('tickets/UPDATE_TICKET', { ticket })
        store.commit('tickets/INIT_SERVICE_LEVEL_TIMER_TICKET', { ticket })
      }
    })
  }

  async cancelEmit({ ticketId, status, userCancelTicketId, cancelComment, expired, ticketGroupUserIds }) {
    const { data } = await TicketService.cancel(ticketId, status, userCancelTicketId, cancelComment, expired)
    if (data.ok) {
      const { ticket } = data

      if (ticket.canceled) {
        this._socketClient.emit('ticket:cancel', {
          ticket,
          ticketGroupUserIds,
        })
        this._socketClient.emit('all-users-notifications:refresh', { ticketGroupUserIds })
      }
      return data
    }
  }

  cancelOn() {
    this._socketClient.on('ticket:cancel', response => {
      if (response.ok) {
        const { ticket, notifications, notificationsCount } = response
        store.dispatch('users/setUserNotifications', {
          notifications,
          notificationsCount,
        })

        store.commit('tickets/UPDATE_TICKET', { ticket })

        const intervals = store.getters['actions/intervals']
        const objInterval = intervals.find(i => i.id === ticket.id)

        if (objInterval) {
          store.commit('actions/DELETE_INTERVAL', { ticket, objInterval })
        }
      }
    })
  }

  async reassignEmit({ ticketId, userIdWhoAssign, userIdToAssign, status, ticketGroupUserIds, isViewed, userWhoReassign }) {
    const { data } = await TicketService.reassign(ticketId, userIdWhoAssign, userIdToAssign, status, isViewed)
    if (data.ok) {
      const { ticket } = data

      if (ticket.reassigned) {
        this._socketClient.emit('ticket:reassign', { ticket, ticketGroupUserIds, userWhoReassign })
        this._socketClient.emit('all-users-notifications:refresh', { ticketGroupUserIds })
      }
      return data
    }
  }

  reassignOn() {
    this._socketClient.on('ticket:reassign', response => {
      if (response.ok) {
        const { ticket, notificationsCount, notifications, userWhoReassign } = response

        const newNotification = new Toastr(
          `Tienes una notificación de ${userWhoReassign}`,
          'Nueva notificación',
          7000,
          'toast-bottom-right',
          0,
          true,
          'fadeOut',
          'fadeIn',
          'fadeOut',
          1000,
          'notification',
        )

        store.dispatch('users/setUserNotifications', {
          notifications,
          notificationsCount,
        })

        newNotification.launch()

        store.commit('tickets/UPDATE_TICKET', { ticket })
      }
    })
  }

  async pauseEmit({ ticketId, status, userPauseTicketId, pauseComment, ticketGroupUserIds }) {
    const { data } = await TicketService.pause(ticketId, status, userPauseTicketId, pauseComment)
    if (data.ok) {
      const { ticket } = data

      if (ticket.paused) {
        this._socketClient.emit('ticket:pause', { ticket, ticketGroupUserIds })
      }
      return data
    }
  }

  pauseOn() {
    this._socketClient.on('ticket:pause', response => {
      if (response.ok) {
        const { ticket, notificationsCount, notifications } = response
        store.dispatch('users/setUserNotifications', {
          notifications,
          notificationsCount,
        })

        store.commit('tickets/UPDATE_TICKET', { ticket })

        const intervals = store.getters['actions/intervals']
        const objInterval = intervals.find(i => i.id === ticket.id)

        if (objInterval) {
          store.commit('actions/DELETE_INTERVAL', { ticket, objInterval })
        }
      }
    })
  }

  async resumeEmit({ ticketId, userResumeTicketId, ticketGroupUserIds }) {
    const { data } = await TicketService.resume(ticketId, userResumeTicketId)
    if (data.ok) {
      const { ticket, resumed } = data //resumed no es un status de ticket, es una bandera para validar si el ticket regresó a su status anterior

      if (resumed) {
        this._socketClient.emit('ticket:resume', { ticket, ticketGroupUserIds })
      }

      return data
    }
  }

  resumeOn() {
    this._socketClient.on('ticket:resume', response => {
      if (response.ok) {
        const { ticket, notificationsCount, notifications } = response
        store.dispatch('users/setUserNotifications', {
          notifications,
          notificationsCount,
        })

        store.commit('tickets/UPDATE_TICKET', { ticket })
        store.commit('tickets/INIT_SERVICE_LEVEL_TIMER_TICKET', { ticket })
      }
    })
  }

  async authorizeEmit({ id, status, subGroupId, userAuthorizeId, groupId }) {
    const { data } = await TicketService.authorize(id, status, subGroupId, userAuthorizeId)
    if (data.ok) {
      const { isCompletelyAuthorized, ticket } = data
      this._socketClient.emit('ticket:authorize', { ticket })

      if (isCompletelyAuthorized) {
        const { users } = store.getters['ticketGroups/ticketGroupsR'].find(t => t.id === groupId)
        const ticketGroupUserIds = users.map(u => u.user_id)

        this._socketClient.emit('ticket:completely-authorized', { ticket, ticketGroupUserIds })
        this._socketClient.emit('tickets-tray:refresh', { ticketGroupUserIds })
      }

      return data
    }
  }

  authorizeOn() {
    this._socketClient.on('ticket:authorize', response => {
      if (response.ok) {
        const { ticket, notifications, notificationsCount, authTicketsCount } = response

        store.dispatch('users/setUserAuthTicketsNotifications', {
          notifications,
          notificationsCount,
          authTicketsCount,
        })

        store.commit('tickets/UPDATE_AUTHORIZE_TICKET', { ticket })
      }
    })
  }

  async declineEmit({ id, status, userDeclineId, declineComment }) {
    const { data } = await TicketService.decline(id, status, userDeclineId, declineComment)

    if (data.ok) {
      const { ticket, declined } = data

      if (declined) {
        this._socketClient.emit('ticket-auth:decline', { ticket })
      }

      return data
    }
  }

  declineAuthOn() {
    this._socketClient.on('ticket-auth:decline', response => {
      if (response.ok) {
        const { ticket, notifications, notificationsCount, authTicketsCount } = response

        store.dispatch('users/setUserNotifications', {
          notifications,
          notificationsCount,
          authTicketsCount,
        })

        store.commit('tickets/UPDATE_AUTHORIZE_TICKET', { ticket })
      }
    })
  }

  declineOn() {
    this._socketClient.on('ticket:decline', response => {
      if (response.ok) {
        const { ticket, notifications, notificationsCount } = response
        if (response.ok) {
          store.dispatch('users/setUserNotifications', {
            notifications,
            notificationsCount,
          })

          store.commit('tickets/UPDATE_TICKET', { ticket })
        }
      }
    })
  }

  async clickNotificationEmit({ id, isViewed, ticketGroupUserIds }) {
    const { data } = await TicketService.updateStatusNotification(id, isViewed)
    if (data.ok) {
      this._socketClient.emit('notification:click', { ticketGroupUserIds })
      return data
    }
  }

  clickNotificationOn() {
    this._socketClient.on('notification:click', response => {
      const { notificationsCount, notifications } = response
      store.dispatch('users/setUserNotifications', { notifications, notificationsCount })
    })
  }

  ticketCompletelyAuthorizedOn() {
    this._socketClient.on('ticket:completely-authorized', response => {
      if (response.ok) {
        const { ticket, notifications, notificationsCount, authTicketsCount, ticketsTrayCount } = response

        const newNotification = new Toastr(
          `Tienes una notificación de ${ticket.userCreate}`,
          'Nueva notificación',
          7000,
          'toast-bottom-right',
          0,
          true,
          'fadeOut',
          'fadeIn',
          'fadeOut',
          1000,
          'notification',
        )

        store.dispatch('users/setUserNotifications', {
          notifications,
          notificationsCount,
          authTicketsCount,
          ticketsTrayCount,
        })

        newNotification.launch()
      }
    })
  }
}

const socketClient = new SocketClient()

export default socketClient
