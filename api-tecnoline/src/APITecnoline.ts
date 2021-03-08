import { Server as SocketIO } from 'socket.io'
import App from './classes/App'
import dashboardRoutes from './routes/dashboard.routes'
import dbConnection from './utils/DatabaseConnection'
import SocketServer from './utils/SocketServer'
import Enviroment from './utils/Enviroment'

export default class APITecnoline extends App {
  constructor(port: number) {
    super(port)
    this.initDependencies()
  }

  public initDataBaseConnection(): void {
    dbConnection.init()
  }

  public initRoutes(): void {
    this._app.use('/v1/dashboards', dashboardRoutes)
  }

  public initSocketServer() {
    const {
      socketIO: {
        cors: { origin, credentials, methods, allowedHeaders },
        pingTimeout,
      },
    } = Enviroment.local()

    const io: SocketIO = new SocketIO(this._server, {
      cors: { origin, credentials, methods, allowedHeaders },
      pingTimeout,
    })

    const socketServer: SocketServer = new SocketServer(io)
  }

  public initDependencies() {
    this.initDataBaseConnection()
    this.initMiddlewares()
    this.initRoutes()
    this.initSocketServer()
  }
}
