import { IdleTimer } from '../shared/IdleTimer'
import store from '../store/store'

function transformStringPermission(stringPermission) {
  if (stringPermission.includes(' ')) {
    stringPermission = stringPermission.replace('-', ' ')
  }
  stringPermission = stringPermission.toLowerCase()
  return stringPermission
}

async function verifyUserHasPermission(permission) {
  try {
    const { hasPermission } = await store.dispatch('auth/verifyUserHasPermission', {
      permission,
    })
    return hasPermission
  } catch (error) {}
}

async function verifyTokenIsValid() {
  try {
    const { token } = await store.dispatch('auth/verifyTokenIsValid')
    return token.isValid
  } catch (error) {}
}

function initIdleTimer() {
  const idleTimerId = store.getters['actions/idleTimerId']
  if (idleTimerId) {
    window.clearInterval(idleTimerId)
  }
  const idleTimer = new IdleTimer(document)
  idleTimer.init()
}

const protectAppRoutes = async (to, from, next) => {
  const { token } = store.getters['auth/dataUser']
  if (to.matched.some(record => record.meta.requiresAuth) && token.value != null) {
    if (to.path != '/inicio') {
      const isValid = await verifyTokenIsValid()
      if (isValid) {
        const permission = transformStringPermission(to.name)
        const hasPermission = await verifyUserHasPermission(permission)

        initIdleTimer()

        if (hasPermission) {
          next()
        } else {
          next({ path: '/sin-acceso' })
        }
      } else {
        next({ path: '/inicio', params: { nextUrl: to.fullPath } })
      }
    } else {
      next()
    }
  } else {
    if (to.path != '/inicio') {
      next({ path: '/inicio' })
    }
  }
}

export { protectAppRoutes }
