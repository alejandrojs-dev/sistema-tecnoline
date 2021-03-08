import api from '../../http/axios.http.api-tickets'

class PriorityService {
  async get() {
    return await api.get('priorities/all')
  }
}

export default new PriorityService()
