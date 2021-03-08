import web from '../../http/axios.http.tecnoline'
import { IBaseService } from '../../interfaces/IBaseService.interface'

class DashboardService extends IBaseService {
  async getAll() {
    return await web.get('/dashboards')
  }

  async getById({ id }) {
    return await web.get(`/dashboards/${id}`)
  }

  async getByDep({ departmentId }) {
    return await web.get(`/business_intelligence/dashboardsByDep/${departmentId}`)
  }

  async getBIData({ id, dataType }) {
    return await web.get(`business_intelligence/getBIData/${id}/${dataType}`)
  }

  async save({ dashboard }) {
    return await web.post('/dashboards', dashboard)
  }

  async update({ id, dashboard }) {
    return await web.put(`/dashboards/${id}`, dashboard)
  }

  async delete({ id }) {
    return await web.delete(`/dashboards/${id}`)
  }

  async createVueFileComponent({ dashboardId }) {
    return await web.post('/business_intelligence/createVueFileComponent', { dashboardId })
  }
}

export default new DashboardService()
