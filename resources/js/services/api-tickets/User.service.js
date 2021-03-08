import api from '../../http/axios.http.api-tickets'
import web from '../../http/axios.http.tecnoline'

class UserService {
  async getFromApi() {
    return await api.get('users/all')
  }

  async getFromWeb() {
    return await web.get('/users')
  }

  async getByGroup(groupId) {
    return await api.get(`users/byGroup/${groupId}`)
  }
}

export default new UserService()
