import api from '../../http/axios.http.api-tickets'

class TicketSubGroupService {
  async save(ticketSubGroup, userId, usersWhoAuthorize) {
    return await api.post('ticketSubGroups/save', { ticketSubGroup, userId, usersWhoAuthorize })
  }

  async get() {
    return await api.get('ticketSubGroups/all')
  }

  async getById(id, ticketTypeId) {
    return await api.get(`ticketSubGroups/show/${id}/${ticketTypeId}`)
  }

  async update(id, ticketSubGroup, userId, usersWhoAuthorize) {
    return await api.put(`ticketSubGroups/update/${id}`, {
      ticketSubGroup,
      userId,
      usersWhoAuthorize,
    })
  }

  async delete(id) {
    return await api.delete(`ticketSubGroups/delete/${id}`)
  }
}

export default new TicketSubGroupService()
