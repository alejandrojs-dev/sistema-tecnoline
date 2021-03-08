import axios from 'axios'
import config from '../config/app'
import store from '../store/store'
import $ from 'jquery'

const showAlertErrors = (message, errors = null) => {
  store.dispatch('actions/setError', { error: true })
  store.dispatch('actions/setAlertInfoData', {
    type: 'error',
    message: message,
    icon: 'mdi-alert-circle',
    errors,
  })
}

const api = axios.create({
  baseURL: `${config.API_TICKETS.URI_API}`,
  headers: {
    Accept: 'application/json',
    'Content-type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': $('meta[name="x-csrf-token"]').attr('content'),
  },
})

api.interceptors.request.use(
  config => {
    return config
  },
  error => {
    return Promise.reject(error)
  },
)

api.interceptors.response.use(
  response => {
    return response
  },
  error => {
    const { status } = error.response
    if (status === 422) {
      //errores de validacion
      const { message, errors } = error.response.data
      showAlertErrors(message, errors)
    }

    if (status === 400 || status === 409) {
      //validacion para extension de imagenes (400) y entidad ya creada (409)
      const { message } = error.response.data
      showAlertErrors(message)
    }
    return Promise.reject(error)
  },
)

export default api
