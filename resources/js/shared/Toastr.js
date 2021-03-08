import toastr from 'toastr'

export default class Toastr {
  #toastr = null
  id = 0
  message = ''
  title = ''
  timeOut = 0
  positionClass = ''
  extendedTimeOut = 0
  closeButton = false
  closeMethod = ''
  showMethod = ''
  hideMethod = ''
  hideDuration = 0

  constructor(message, title, timeOut, positionClass, extendedTimeOut, closeButton, closeMethod, showMethod, hideMethod, hideDuration, type) {
    this.#toastr = toastr
    this.message = message
    this.title = title
    this.showMethod = showMethod
    this.timeOut = timeOut
    this.positionClass = positionClass
    this.extendedTimeOut = extendedTimeOut
    this.closeButton = closeButton
    this.closeMethod = closeMethod
    this.hideMethod = hideMethod
    this.hideDuration = hideDuration
    this.type = type
  }

  launch() {
    if (this.type === 'notification') {
      this.#toastr.info(this.message, this.title, {
        timeOut: this.timeOut,
        positionClass: this.positionClass,
        extendedTimeOut: this.extendedTimeOut,
        closeButton: this.closeButton,
        closeMethod: this.closeMethod,
        showMethod: this.showMethod,
        hideMethod: this.hideMethod,
        hideDuration: this.hideDuration,
      })
    }

    if (this.type === 'toastr') {
      this.#toastr.success(this.message, this.title, {
        timeOut: this.timeOut,
        positionClass: this.positionClass,
        extendedTimeOut: this.extendedTimeOut,
        closeButton: this.closeButton,
        closeMethod: this.closeMethod,
        showMethod: this.showMethod,
        hideMethod: this.hideMethod,
        hideDuration: this.hideDuration,
      })
    }
  }
}
