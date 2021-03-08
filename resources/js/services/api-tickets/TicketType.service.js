import api from '../../http/axios.http.api-tickets'

class TicketTypeService {
  async all() {
    return await api.get('ticketTypes/all')
  }
}

export default new TicketTypeService()
