import { Server as SocketIO, Socket } from 'socket.io'
import { dashboardController } from '../inversify.config'

class SocketServer {
  private _io: SocketIO

  constructor(io: SocketIO) {
    this._io = io
    this.registerEvents()
  }

  private registerEvents(): void {
    this._io.on('connection', (socket: Socket) => {
      socket.on('error', error => console.error(error))
      socket.on('disconnect', reason => console.log(reason))

      this.getDashboardData(socket)
    })
  }

  private getDashboardData(socket: Socket): void {
    socket.on('dashboard-data:get', async data => {
      const { id } = data

      const result = await dashboardController.getDashboardData(parseInt(id))

      this._io.emit('dashboard-data:get', {
        ok: true,
        message: 'data recuperada con éxito',
        data: result[0],
      })
    })
  }
}

export default SocketServer
