import web from '../../http/axios.http.tecnoline'

class DeparmentService {
  async getAll() {
    return await web.get('/departments')
  }
}

export default new DeparmentService()
