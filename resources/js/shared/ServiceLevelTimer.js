import { addSeconds } from 'date-fns'
import TicketService from '../services/api-tickets/Ticket.service'
import store from '../store/store'

export class ServiceLevelTimer {
  interval = null
  yellowSemaphoreSeconds = 0
  redSemaphoreSeconds = 0
  timerTextContent = ''
  expired = false
  semaphoreColor = 'green'

  constructor() {}

  getInterval() {
    return this.interval
  }

  async calculateTimeRemaining(finalServiceLevelDate, ticketId) {
    const todayDate = new Date().getTime()
    const elapsedTimeInSeconds = Math.floor((finalServiceLevelDate - todayDate) / 1000)
    const elapsedTime = finalServiceLevelDate - todayDate
    const days = Math.floor(elapsedTime / (1000 * 60 * 60 * 24))
    const hours = Math.floor((elapsedTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
    const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60))
    const seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000)

    this.timerTextContent = `${days}d ${hours}h ${minutes}m ${seconds}s`

    store.commit('tickets/UPDATE_SERVICE_LEVEL_DATA_TICKET', { timerTextContent: this.timerTextContent, option: 1, id: ticketId })

    if (elapsedTimeInSeconds == this.yellowSemaphoreSeconds && elapsedTimeInSeconds > this.redSemaphoreSeconds) {
      this.semaphoreColor = 'orange'

      const payload = {
        id: ticketId,
        semaphoreColor: this.semaphoreColor,
        expired: this.expired,
      }

      await TicketService.updateSemaphoreColor(payload)

      store.commit('tickets/UPDATE_SERVICE_LEVEL_DATA_TICKET', { semaphoreColor: this.semaphoreColor, option: 2, id: ticketId })
    }

    if (elapsedTimeInSeconds == this.redSemaphoreSeconds) {
      this.semaphoreColor = 'red'

      const payload = {
        id: ticketId,
        semaphoreColor: this.semaphoreColor,
        expired: this.expired,
      }

      await TicketService.updateSemaphoreColor(payload)

      store.commit('tickets/UPDATE_SERVICE_LEVEL_DATA_TICKET', { semaphoreColor: this.semaphoreColor, option: 2, id: ticketId })
    }

    if (elapsedTimeInSeconds <= 0) {
      clearInterval(this.interval)

      this.semaphoreColor = 'grey'
      this.timerTextContent = 'Service level expirado'
      this.expired = true

      const payload = {
        id: ticketId,
        semaphoreColor: this.semaphoreColor,
        expired: this.expired,
      }

      await TicketService.updateSemaphoreColor(payload)

      store.commit('tickets/UPDATE_SERVICE_LEVEL_DATA_TICKET', {
        semaphoreColor: this.semaphoreColor,
        timerTextContent: this.timerTextContent,
        expired: this.expired,
        option: 3,
        id: ticketId,
      })
    }
  }

  async start({ id, serviceLevel, dateTakenTicket }) {
    const parsedDateTakenTicket = Date.parse(dateTakenTicket)
    let finalServiceLevelDate = addSeconds(parsedDateTakenTicket, serviceLevel)

    const { data } = await TicketService.getSumDeadTimesTicket(id)

    if (data.ok) {
      const { sumDeadTimesTicket } = data
      finalServiceLevelDate = addSeconds(finalServiceLevelDate, sumDeadTimesTicket)
    }

    this.yellowSemaphoreSeconds = Math.floor(serviceLevel / 2)
    this.redSemaphoreSeconds = Math.floor(this.yellowSemaphoreSeconds / 2)

    this.interval = setInterval(async () => {
      await this.calculateTimeRemaining(finalServiceLevelDate, id)
    }, 1000)

    store.commit('actions/SAVE_INTERVAL', { interval: this.interval, id })
  }
}
