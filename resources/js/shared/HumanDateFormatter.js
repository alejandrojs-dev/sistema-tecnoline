import moment from 'moment'
import 'moment/locale/es'

export class HumanDateFormatter {
  ONE_DAY_SECONDS = 86400
  TWO_DAYS_SECONDS = 172800
  THREE_DAYS_SECONDS = 259200
  ONE_MINUTE_SECONDS = 60

  formatToHumanDialect(date) {
    let humanizedDate = ''
    const notificationDate = moment(new Date(date))
    const todayDate = moment(new Date())
    const diffSeconds = todayDate.diff(notificationDate, 'seconds')

    if (diffSeconds > this.THREE_DAYS_SECONDS) {
      return (humanizedDate = moment(notificationDate).format('D MMM [a las] h:mm a'))
    }

    if (diffSeconds < this.THREE_DAYS_SECONDS && diffSeconds > this.TWO_DAYS_SECONDS) {
      return (humanizedDate = moment(notificationDate).format('[Antier a las] h:mm a'))
    }

    if (diffSeconds < this.TWO_DAYS_SECONDS && diffSeconds > this.ONE_DAY_SECONDS) {
      return (humanizedDate = moment(notificationDate).format('[Ayer a las] h:mm a'))
    }

    if (diffSeconds > this.ONE_MINUTE_SECONDS) {
      return (humanizedDate = moment()
        .subtract(diffSeconds, 'seconds')
        .fromNow())
    }

    if (diffSeconds < this.ONE_MINUTE_SECONDS && diffSeconds > 0) {
      return (humanizedDate = moment()
        .subtract(diffSeconds, 'seconds')
        .fromNow())
    }
  }
}
