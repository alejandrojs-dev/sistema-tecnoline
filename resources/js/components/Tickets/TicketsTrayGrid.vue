<template>
  <div>
    <Overlay :overlay="overlay" />
    <DialogTakeTicketConfirm />
    <DialogReasonCancelTicket />
    <DialogReassignedTicket />
    <DialogTicketDetails />
    <DialogPauseTicket />
    <v-sheet class="mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid>
        <v-row>
          <v-col cols="12" md="8">
            <div class="tableTitleContainer">
              <span class="tableTitle">Tickets</span>
            </div>
          </v-col>
          <v-spacer></v-spacer>
          <v-col cols="12" md="4">
            <v-text-field v-model="criteriaSearch" append-icon="mdi-magnify" label="Buscar" single-line hide-details class="mb-2"></v-text-field>
          </v-col>
          <v-col cols="12" md="12">
            <v-data-table
              v-if="!loadingDataTable"
              id="dtTickets"
              :headers="headers"
              :items="tickets"
              :items-per-page="10"
              :footer-props="footerProps"
              :search="criteriaSearch"
              :header-props="headerProps"
              item-key="id"
              class="elevation-1"
              loading-text="Cargando... Espere un momento"
              no-data-text="No hay tickets"
              no-results-text="No se encontró el ticket"
            >
              <template v-slot:[`header.type`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.groupName`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.subGroupName`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.status`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.userTakenTicket`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.userCancelTicket`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.priority`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.userCreateTicket`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.serviceLevel`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.actions`]="{ header }">
                {{ header.text.toUpperCase() }}
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon color="blue" dark small v-bind="attrs" v-on="on" class="mb-1">
                      mdi-help-circle
                    </v-icon>
                  </template>
                  <span>
                    <v-icon small dark>mdi-hand-right</v-icon> Tomar <br />
                    <v-icon small dark>mdi-clipboard-text</v-icon> Detalles
                    <br />
                    <v-icon small dark>mdi-close-circle</v-icon> Cancelar <br />
                    <v-icon small dark>mdi-account-switch</v-icon> Reasignar
                    <br />
                    <v-icon small dark>mdi-pause-circle</v-icon> Pausar <br />
                    <v-icon small dark>mdi-play-circle</v-icon> Reanudar
                  </span>
                </v-tooltip>
              </template>

              <template v-slot:[`item.userCreateTicketId`]="{ item }">
                <td class="d-block d-sm-table-cell" v-if="isOwnTicket(item.userCreateTicketId) && !item.canceled && !item.declined">
                  <v-icon color="carbon">mdi-account</v-icon>
                </td>
                <td class="d-block d-sm-table-cell" v-else-if="item.canceled">
                  <v-icon color="red">mdi-close-thick</v-icon>
                </td>
                <td class="d-block d-sm-table-cell" v-else-if="item.declined">
                  <v-icon color="red">mdi-minus-circle</v-icon>
                </td>
                <td class="d-block d-sm-table-cell" v-else-if="item.isAuthorized">
                  <v-icon color="green">mdi-check-bold</v-icon>
                </td>
                <td class="d-block d-sm-table-cell" v-else-if="isNotOwnTicket(item.userCreateTicketId) && !item.canceled">
                  <v-icon color="carbon">mdi-account-group</v-icon>
                </td>
              </template>

              <template v-slot:[`item.status`]="{ item }">
                <v-chip :color="item.statusColor" text-color="white" dark small>{{ item.status }}</v-chip>
              </template>
              <template v-slot:[`item.priority`]="{ item }">
                <v-chip :color="item.priorityColor" text-color="white" dark small>{{ item.priority }}</v-chip>
              </template>

              <template v-slot:[`item.serviceLevel`]="{ item }">
                <v-icon
                  :color="item.semaphoreColor"
                  v-if="(item.taken && !item.canceled && !item.expired) || (item.reassigned && !item.expired && !item.canceled)"
                  >mdi-timer</v-icon
                >
                <v-icon disabled v-else-if="item.expired || item.canceled || item.paused">mdi-timer-off</v-icon>
              </template>

              <template v-slot:[`item.actions`]="{ item }">
                <v-icon
                  v-if="existsTicketGroup(item.ticketGroupId) && !item.inAuthorization"
                  :disabled="item.taken || item.canceled || item.reassigned || item.paused"
                  color="carbon"
                  class="mr-2"
                  @click="openTakeDialog(item.id, item.ticketGroupId)"
                  >mdi-hand-right</v-icon
                >
                <v-icon color="carbon" class="mr-2" @click="openDetailsDialog(item)">mdi-clipboard-text</v-icon>
                <v-icon :disabled="item.canceled" color="carbon" class="mr-2" @click="openReasonCancelDialog(item.id, item.ticketGroupId)"
                  >mdi-close-circle</v-icon
                >
                <v-icon
                  :disabled="item.canceled || item.paused || (!item.taken && !item.reassigned)"
                  color="carbon"
                  class="mr-2"
                  @click="openReassignDialog(item.id, item.ticketGroupId)"
                >
                  mdi-account-switch
                </v-icon>
                <v-icon
                  v-if="!item.paused"
                  :disabled="item.paused || (!item.taken && !item.reassigned)"
                  color="carbon"
                  class="mr-2"
                  @click="openPauseTicketDialog(item.id, item.ticketGroupId)"
                  >mdi-pause-circle</v-icon
                >
                <v-icon :disabled="item.taken" color="carbon" class="mr-2" @click="resumeTicket(item.id, item.ticketGroupId)" v-else
                  >mdi-play-circle</v-icon
                >
              </template>
            </v-data-table>
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import socketClient from '../../shared/SocketClient'
import Overlay from '../../components/Overlay'
import DialogTakeTicketConfirm from '../../components/Tickets/DialogTakeTicketConfirm'
import DialogReasonCancelTicket from '../../components/Tickets/DialogReasonCancelTicket'
import DialogTicketDetails from '../../components/Tickets/DialogTicketDetails'
import DialogReassignedTicket from '../../components/Tickets/DialogReassignedTicket'
import DialogPauseTicket from '../../components/Tickets/DialogPauseTicket'
export default {
  name: 'TicketsTrayGrid',
  components: {
    Overlay,
    DialogTakeTicketConfirm,
    DialogReasonCancelTicket,
    DialogTicketDetails,
    DialogReassignedTicket,
    DialogPauseTicket,
  },
  data: () => ({
    users: [],
    authorizeUsersTicket: [],
    criteriaSearch: '',
    overlay: true,
    loadingDataTable: true,
    ticket: null,
    semaphoreColor: '',
    serviceLevelTimer: null,
    headerProps: {
      sortByText: 'Ordenar por',
    },
    headers: [
      {
        text: '',
        value: 'userCreateTicketId',
        align: 'start',
        sortable: false,
        filterable: false,
      },
      { text: 'Tipo', value: 'type', sortable: true, filterable: true },
      { text: 'Grupo', value: 'groupName', sortable: true, filterable: true },
      { text: 'Subgrupo', value: 'subGroupName', sortable: true, filterable: true },
      { text: 'Status', value: 'status', sortable: true, filterable: true },
      { text: 'Asignado a', value: 'userTakenTicket', sortable: true, filterable: true },
      { text: 'Cancelado por', value: 'userCancelTicket', sortable: true, filterable: true },
      { text: 'Prioridad', value: 'priority', sortable: true, filterable: true },
      { text: 'Creado por', value: 'userCreateTicket', sortable: false, filterable: true },
      { text: 'SL', value: 'serviceLevel', sortable: false, filterable: false },
      { text: 'Acciones', value: 'actions', sortable: false, align: 'center' },
    ],
    footerProps: {
      showFirstLastPage: true,
      firstIcon: 'mdi-arrow-collapse-left',
      lastIcon: 'mdi-arrow-collapse-right',
      prevIcon: 'mdi-minus',
      nextIcon: 'mdi-plus',
    },
  }),
  computed: {
    ...mapGetters({
      dataUser: 'auth/dataUser',
      intervals: 'actions/intervals',
      authorizeUsersByTicket: 'tickets/authorizeUsersByTicket',
      ticketGroupsR: 'ticketGroups/ticketGroupsR',
      serviceLevelTimerData: 'tickets/serviceLevelTimerData',
      tickets: 'tickets/tickets',
    }),
    loggedUserId() {
      return this.dataUser.user.id
    },
    userTicketGroups() {
      return this.dataUser.user.ticketGroups
    },
  },
  methods: {
    existsTicketGroup(id) {
      //valida que aparezca icono de tomar ticket cuando el usuario logueado pertenece al grupo del ticket
      let exists = false
      const ticketGroup = this.userTicketGroups.find(tg => tg.id_ticket_group === id)
      if (ticketGroup) exists = true
      return exists
    },
    isOwnTicket(userCreateTicketId) {
      let isOwn = false
      if (this.loggedUserId === userCreateTicketId) isOwn = true
      return isOwn
    },
    isNotOwnTicket(userCreateTicketId) {
      let isNotOwn = false
      if (this.loggedUserId !== userCreateTicketId) isNotOwn = true
      return isNotOwn
    },
    openTakeDialog(id, ticketGroupId) {
      let { users } = this.ticketGroupsR.find(t => t.id === ticketGroupId)
      users = users.map(u => u.user_id)
      const payload = {
        ticketId: id,
        status: 2,
        semaphoreColor: 'green',
        userTakeTicketId: this.loggedUserId,
        users: users,
      }

      this.$store.dispatch('tickets/setTakeTicketData', payload)
      this.$store.dispatch('tickets/openDialogTakeTicket')
    },
    openReasonCancelDialog(id, ticketGroupId) {
      let { users } = this.ticketGroupsR.find(t => t.id === ticketGroupId)
      users = users.map(u => u.user_id)
      const payload = {
        ticketId: id,
        status: 7,
        expired: true,
        userCancelTicketId: this.loggedUserId,
        users: users,
      }

      this.$store.dispatch('tickets/setCancelTicketData', payload)
      this.$store.dispatch('tickets/openDialogReasonCancel')
    },
    openReassignDialog(id, ticketGroupId) {
      const userWhoReassign = this.dataUser.user.username
      const { users } = this.ticketGroupsR.find(t => t.id === ticketGroupId)
      const payload = {
        ticketId: id,
        status: 5,
        userIdWhoAssign: this.loggedUserId,
        isViewed: false,
        users: users,
        userWhoReassign: userWhoReassign,
      }

      this.$store.dispatch('tickets/setReassignTicketData', payload)
      this.$store.dispatch('tickets/openDialogReassignTicket')
    },
    openPauseTicketDialog(id, ticketGroupId) {
      let { users } = this.ticketGroupsR.find(t => t.id === ticketGroupId)
      users = users.map(u => u.user_id)
      const payload = {
        ticketId: id,
        status: 3,
        userPauseTicketId: this.loggedUserId,
        users: users,
      }

      this.$store.dispatch('tickets/setPauseTicketData', payload)
      this.$store.dispatch('tickets/openDialogPauseTicket')
    },
    async resumeTicket(id, ticketGroupId) {
      try {
        this.overlay = true
        let { users } = this.ticketGroupsR.find(t => t.id === ticketGroupId)
        users = users.map(u => u.user_id)
        const payload = {
          ticketId: id,
          userResumeTicketId: this.loggedUserId,
          ticketGroupUserIds: users,
        }

        const response = await this.$store.dispatch('tickets/resume', payload)

        if (response.ok) {
          this.overlay = false
        }
      } catch (error) {
        this.overlay = false
      }
    },
    async openDetailsDialog(ticket) {
      const userWhoReassign = this.dataUser.user.username
      const { users } = this.ticketGroupsR.find(t => t.id === ticket.ticketGroupId)

      if (ticket.inAuthorization || ticket.declined) {
        await this.getAuthorizeUsersByTicket(ticket.id)
      }

      const payload = {
        ticketId: ticket.id,
        status: 5,
        users: users,
        isViewed: false,
        userIdWhoAssign: this.loggedUserId,
        userWhoReassign: userWhoReassign,
      }

      this.$store.dispatch('tickets/setTicketDetails', ticket)
      this.$store.dispatch('tickets/setReassignTicketData', payload)
      this.$store.dispatch('tickets/openDialogDetails')
    },
    async getAuthorizeUsersByTicket(ticketId) {
      await this.$store.dispatch('tickets/getAuthorizeUsersByTicket', { ticketId })
    },
    async loadDataTable() {
      try {
        const response = await this.$store.dispatch('tickets/getTickets', {
          userId: this.loggedUserId,
        })
        if (response.ok) {
          this.overlay = false
          this.loadingDataTable = false
          for (const ticket of this.tickets) {
            if ((ticket.taken && !ticket.expired) || ticket.reassigned) {
              this.$store.dispatch('tickets/initServiceLevelTimerTicket', { ticket })
            }
          }
        }
      } catch (error) {
        this.overlay = false
        this.loadingDataTable = false
      }
    },
  },
  created() {
    const self = this

    self.loadDataTable()

    socketClient.takeOn()
    socketClient.cancelOn()
    socketClient.reassignOn()
    socketClient.pauseOn()
    socketClient.resumeOn()
    socketClient.declineOn()
    socketClient.refreshTicketsTrayOn()
    socketClient.ticketCompletelyAuthorizedOn()
  },
}
</script>

<style scoped>
.tableTitle {
  font-size: 25px;
}
.tableTitleContainer {
  margin-top: 20px;
}
</style>
