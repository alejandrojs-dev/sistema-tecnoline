export default class TicketSubGroup {
  constructor(ticketGroupId, ticketTypeId, name, serviceLevel, numberAuth, icon, active) {
    this.ticketGroupId = ticketGroupId
    this.ticketTypeId = ticketTypeId
    this.name = name
    this.serviceLevel = serviceLevel
    this.numberAuth = numberAuth
    this.icon = icon
    this.active = active
  }
}
