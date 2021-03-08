import 'reflect-metadata'
import { injectable } from 'inversify'
import { Sequelize } from 'sequelize'
import dbConnection from '../utils/DatabaseConnection'

@injectable()
class DashboardService {
  private _sequelize: Sequelize

  constructor() {
    this._sequelize = dbConnection.sequelize
  }

  public async getDashboardData(id: number): Promise<any[]> {
    const result: any = await this._sequelize.query('CALL spr_generate_row_data(:id)', { replacements: { id } })
    return result
  }
}

export default DashboardService
