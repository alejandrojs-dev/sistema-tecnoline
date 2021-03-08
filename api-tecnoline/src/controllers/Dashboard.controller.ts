import 'reflect-metadata'
import { injectable, inject } from 'inversify'
import TYPES from '../utils/types'
import DashboardService from '../services/Dashboard.service'

@injectable()
class DashboardController {
  private _dashboardService: DashboardService

  constructor(@inject(TYPES.DashboardService) dashboardService: DashboardService) {
    this._dashboardService = dashboardService
  }

  public async getDashboardData(id: number): Promise<any[]> {
    return await this._dashboardService.getDashboardData(id)
  }
}

export default DashboardController
