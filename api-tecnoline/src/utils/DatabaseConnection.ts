import 'reflect-metadata'
import { Sequelize } from 'sequelize'
import Enviroment from './Enviroment'

class DatabaseConnection {
  private _sequelize: Sequelize

  public constructor(sequelize: Sequelize) {
    this._sequelize = sequelize
  }

  get sequelize() {
    return this._sequelize
  }

  set sequelize(value) {
    this._sequelize = value
  }

  public async init(): Promise<void> {
    try {
      await this._sequelize.authenticate()
      console.log('Database connection successfully')
    } catch (e) {
      console.error('Unable to connect to the database: ', e)
    }
  }
}

const {
  database: { name, username, password, host },
} = Enviroment.local()

const sequelize: Sequelize = new Sequelize(name, username, password, { host, dialect: 'mysql' })
const dbConnection: DatabaseConnection = new DatabaseConnection(sequelize)

export default dbConnection
