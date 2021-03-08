export default class TSTicket {
  constructor(ticketGroupId, ticketSubGroupId, ticketTypeId, priorityId, description, userTicketCreate) {
    this.ticketGroupId = ticketGroupId
    this.ticketSubGroupId = ticketSubGroupId
    this.ticketTypeId = ticketTypeId
    this.priorityId = priorityId
    this.description = description
    this.userTicketCreate = userTicketCreate
  }
}
