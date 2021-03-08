import api from '../../http/axios.http.api-tickets'

class TicketGroupService {
  // async save(formData) {
  //     return await api.post('ticketGroups/save', formData, {
  //         headers: { 'Content-Type': 'multipart/form-data' },
  //     })
  // }

  async save(ticketGroup, assignedUsers) {
    return await api.post('ticketGroups/save', { ticketGroup, assignedUsers })
  }

  async get() {
    return await api.get('ticketGroups/all')
  }

  async getById(id) {
    return await api.get(`ticketGroups/show/${id}`)
  }

  async getWithRelationShips() {
    return await api.get('ticketGroups/allWithRelationShips')
  }

  // async update(id, formData) {
  //     return await api.put(`ticketGroups/update/${id}`, formData, {
  //         headers: { 'Content-Type': 'multipart/form-data' },
  //     })
  // }

  async update(id, ticketGroup, assignedUsers) {
    return await api.put(`ticketGroups/update/${id}`, { ticketGroup, assignedUsers })
  }

  async delete(id) {
    return await api.delete(`ticketGroups/delete/${id}`)
  }
}

export default new TicketGroupService()
