import web from '../../http/axios.http.tecnoline'
import api from '../../http/axios.http.api-tickets'

class AuthService {
  async login(data) {
    return await web.post('/login', data)
  }

  async logout() {
    return await web.get('/logout')
  }

  async verifyTokenIsValid() {
    return await web.get('/getToken')
  }

  async verifyUserHasPermission(permission) {
    return await web.post('/verifyUserHasPermission', { permission })
  }

  async getUserNotifications(userId) {
    return await api.get(`/tickets/notificationsByUser/${userId}`)
  }
}

export default new AuthService()
