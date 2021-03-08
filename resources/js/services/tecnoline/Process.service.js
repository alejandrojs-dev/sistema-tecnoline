import web from '../../http/axios.http.tecnoline'

class ProcessService {
  async getCountItemsByProcess(processId, alias) {
    return web.get(`/processes/countItemsByProcess/${processId}/${alias}`)
  }

  async getItemsByOrder(orderId, processId) {
    return web.get(`/processes/itemsByOrder/${orderId}/${processId}`)
  }

  async sentItemToNextProcess(itemId, nextProcessId) {
    return web.put(`/processes/sentItemToNextProcess/${itemId}`, { next_process_id: nextProcessId })
  }
}

export default new ProcessService()
