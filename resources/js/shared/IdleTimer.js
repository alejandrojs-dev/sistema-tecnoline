import store from '../store/store'

export class IdleTimer {
  IDLE_TIMEOUT = 7200
  INTERVAL_TIMEOUT = 1000
  idleTimerId = null
  idleSecondsCounter = 0

  constructor(document) {
    document.onclick = () => {
      this.idleSecondsCounter = 0
    }
  }

  init() {
    this.idleTimerId = window.setInterval(async () => {
      this.idleSecondsCounter++

      if (this.idleSecondsCounter >= this.IDLE_TIMEOUT) {
        window.clearInterval(this.idleTimerId)

        const response = await store.dispatch('auth/logout')

        if (response.ok) {
          const message = 'Sesión expirada. Porfavor inicie sesión nuevamente'
          store.dispatch('auth/setTokenIsValid', { isValid: false })
          store.dispatch('actions/setError', { throwError: true })
          store.dispatch('actions/setAlertInfoData', {
            type: 'warning',
            message,
            icon: 'mdi-account-clock',
          })
          store.dispatch('login/openLoginForm')
        }
      }
    }, this.INTERVAL_TIMEOUT)

    store.dispatch('actions/setIdleTimerId', { idleTimerId: this.idleTimerId })
  }
}
