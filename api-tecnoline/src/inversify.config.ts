import { Container } from 'inversify'
import TYPES from './utils/types'
import DashboardController from './controllers/Dashboard.controller'
import DashboardService from './services/Dashboard.service'

const container = new Container()
container
  .bind<DashboardController>(TYPES.DashboardController)
  .to(DashboardController)
  .inSingletonScope()

container
  .bind<DashboardService>(TYPES.DashboardService)
  .to(DashboardService)
  .inSingletonScope()

const dashboardController = container.resolve(DashboardController)

export { dashboardController }
