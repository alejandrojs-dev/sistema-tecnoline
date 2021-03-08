import { io } from 'socket.io-client'
import config from '../config/app'
import store from '../store/store'

class TecnolineSocketClient {
  constructor() {
    this._socketClient = io(config.TECNOLINE_WEB_SOCKETS.URI_API)

    this._socketClient.on('disconnect', reason => {
      this._socketClient.connect()
      // console.log(reason)
      // if (reason === 'io server disconnect') {
      //   this._socketClient.connect()
      // }
    })

    this._socketClient.on('connect_error', error => {
      this._socketClient.connect()
    })
  }

  getDashboardDataEmit(id) {
    this._socketClient.emit('dashboard-data:get', { id })
  }

  getDashboardDataOn() {
    this._socketClient.on('dashboard-data:get', response => {
      store.commit('dashboards/SET_DYNAMIC_DATA_CONTAINER', { response })
    })
  }
}

const tecnolineSocketClient = new TecnolineSocketClient()

export default tecnolineSocketClient
