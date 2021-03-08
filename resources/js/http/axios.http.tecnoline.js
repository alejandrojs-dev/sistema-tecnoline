import axios from 'axios'
import config from '../config/app'
import store from '../store/store'

const showAlert = (message, errors = null) => {
  store.dispatch('actions/setError', { error: true })
  store.dispatch('actions/setAlertInfoData', {
    type: 'error',
    message: message,
    icon: 'mdi-close-thick',
    errors,
  })
}

const showAlertExpiredSession = message => {
  store.dispatch('login/openLoginForm')
  store.dispatch('actions/setError', { error: true })
  store.dispatch('actions/setAlertInfoData', {
    type: 'warning',
    message,
    icon: 'mdi-account-clock',
  })
}

const web = axios.create({
  baseURL: `${config.WEB_SYSTEM.URI_API}`,
  headers: {
    Accept: 'application/json',
    'Content-type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
})

web.interceptors.request.use(
  config => {
    if (sessionStorage.getItem('dataAuth')) {
      const { auth } = JSON.parse(sessionStorage.getItem('dataAuth'))
      const { token } = auth.dataUser
      if (token.value) config.headers.common['Authorization'] = `Bearer ${token.value}`
    }
    return config
  },
  error => {
    return Promise.reject(error)
  },
)

web.interceptors.response.use(
  response => {
    return response
  },
  error => {
    const { data, status } = error.response

    if (status === 401) {
      //Unauthorized
      showAlert(data.message)
    }

    if (status === 422) {
      showAlert(data.message, data.errors)
    }

    if ((status === 400 || status === 403) && !data.token.isValid) {
      showAlertExpiredSession('Sesión expirada. Porfavor inicie sesión nuevamente')
      store.dispatch('auth/resetState')
    }

    return Promise.reject(error)
  },
)

export default web
