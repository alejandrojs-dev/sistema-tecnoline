import web from '../../http/axios.http.tecnoline'

class OrderService {
  async getAll() {
    return await web.get('/orders')
  }
}

export default new OrderService()
