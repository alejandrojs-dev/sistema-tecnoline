import api from '../../http/axios.http.api-tickets'

class TicketService {
  async get(userId) {
    return await api.get(`tickets/allByUserId/${userId}`)
  }

  async getToAuthorizeByUser({ userId }) {
    return await api.get(`tickets/toAuthorizeByUser/${userId}`)
  }

  async getAuthorizeUsersByTicket(ticketId) {
    return await api.get(`tickets/authorizeUsersByTicket/${ticketId}`)
  }

  async getById(id) {
    return await api.get(`tickets/show/${id}`)
  }

  async saveTSTicket(ticket) {
    return await api.post('tickets/saveTSTicket', { ticket })
  }

  async saveTACTicket(ticket, usersWhoAuthorize) {
    return await api.post('tickets/saveTACTicket', { ticket, usersWhoAuthorize })
  }

  async update(id, ticket) {
    return await api.put(`tickets/update/${id}`, ticket)
  }

  async take(id, status, userTakeTicketId, semaphoreColor, isViewed) {
    return await api.put(`tickets/take/${id}`, {
      status,
      userTakeTicketId,
      semaphoreColor,
      isViewed,
    })
  }

  async cancel(id, status, userCancelTicketId, cancelComment, expired) {
    return await api.put(`tickets/cancel/${id}`, {
      status,
      userCancelTicketId,
      cancelComment,
      expired,
    })
  }

  async reassign(id, userIdWhoAssign, userIdToAssign, status, isViewed) {
    return await api.put(`tickets/reassign/${id}`, {
      userIdWhoAssign,
      userIdToAssign,
      status,
      isViewed,
    })
  }

  async pause(id, status, userPauseTicketId, pauseComment) {
    return await api.put(`tickets/pause/${id}`, {
      status,
      userPauseTicketId,
      pauseComment,
    })
  }

  async resume(id, userResumeTicketId) {
    return await api.put(`tickets/resume/${id}`, { userResumeTicketId })
  }

  async authorize(id, status, subGroupId, userAuthorizeId) {
    return await api.put(`tickets/authorize/${id}`, { status, userAuthorizeId, subGroupId })
  }

  async decline(id, status, userDeclineId, declineComment) {
    return await api.put(`tickets/decline/${id}`, { status, userDeclineId, declineComment })
  }

  async updateSemaphoreColor({ id, semaphoreColor, expired }) {
    return await api.put(`tickets/updateSemaphoreColor/${id}`, { semaphoreColor, expired })
  }

  async updateStatusNotification(id, isViewed) {
    return await api.put(`tickets/updateViewedStatus/${id}`, { isViewed })
  }

  async getSumDeadTimesTicket(id) {
    return await api.get(`tickets/sumDeadTimesTicket/${id}`)
  }
}

export default new TicketService()
