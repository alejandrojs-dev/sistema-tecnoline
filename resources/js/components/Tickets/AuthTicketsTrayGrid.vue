<template>
  <div>
    <Overlay :overlay="overlay" />
    <DialogReasonDeclineTicket />
    <DialogAuthTicketDetails />
    <v-sheet class="mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid v-if="!loadingDataTable">
        <v-row>
          <v-col cols="12" md="8">
            <div class="tableTitleContainer">
              <span class="tableTitle">Tickets por autorizar</span>
            </div>
          </v-col>
          <v-spacer></v-spacer>
          <v-col cols="12" md="4">
            <v-text-field v-model="criteriaSearch" append-icon="mdi-magnify" label="Buscar" single-line hide-details class="mb-2"></v-text-field>
          </v-col>
          <v-col cols="12" md="12">
            <v-data-table
              id="dtticketsToAuthorize"
              :headers="headers"
              :items="ticketsToAuthorize"
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
              <template v-slot:[`header.priority`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.userCreate`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.actions`]="{ header }">
                {{ header.text.toUpperCase() }}
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon color="blue" dark small v-bind="attrs" v-on="on" class="mb-1">
                      mdi-help-circle
                    </v-icon>
                  </template>
                  <span>
                    <v-icon small dark>mdi-check-circle</v-icon> Autorizar
                    <br />
                  </span>
                  <span> <v-icon small dark>mdi-minus-circle</v-icon> Declinar <br /> </span>
                  <span><v-icon small dark>mdi-clipboard-text</v-icon> Detalles <br /></span>
                </v-tooltip>
              </template>

              <template v-slot:[`item.id`]="{ item }">
                <td class="d-block d-sm-table-cell" v-if="item.authorized && item.userAuthorizeId === loggedUserId && !item.isCompletelyAuthorized">
                  <v-icon color="green">mdi-check-circle</v-icon>
                </td>

                <td
                  class="d-block d-sm-table-cell"
                  v-else-if="(item.declined && item.userDeclineId === loggedUserId) || (item.declined && item.userDeclineId != loggedUserId)"
                >
                  <v-icon color="red">mdi-minus-circle</v-icon>
                </td>

                <td class="d-block d-sm-table-cell" v-else-if="item.isCompletelyAuthorized">
                  <v-icon color="yellowDarken">mdi-star</v-icon>
                </td>
              </template>

              <template v-slot:[`item.status`]="{ item }">
                <v-chip :color="item.statusColor" text-color="white" dark small>{{ item.status }}</v-chip>
              </template>
              <template v-slot:[`item.priority`]="{ item }">
                <v-chip :color="item.priorityColor" text-color="white" dark small>{{ item.priority }}</v-chip>
              </template>

              <template v-slot:[`item.actions`]="{ item }">
                <v-icon
                  v-if="!item.isCompletelyAuthorized"
                  color="carbon"
                  class="mr-2"
                  @click="authorize(item.id, item.statusId, item.subGroupId, item.groupId)"
                  :disabled="(item.authorized && item.userAuthorizeId === loggedUserId) || (item.declined && item.userDeclineId != loggedUserId)"
                  >mdi-check-circle</v-icon
                >

                <v-icon
                  v-if="!item.isCompletelyAuthorized"
                  color="carbon"
                  class="mr-2"
                  @click="openDeclineDialog(item.id, item.userCreateId)"
                  :disabled="(item.declined && item.userDeclineId === loggedUserId) || (item.declined && item.userAuthorizeId != loggedUserId)"
                  >mdi-minus-circle</v-icon
                >

                <v-icon color="carbon" class="mr-2" @click="openDialogDetails(item)">mdi-clipboard-text</v-icon>
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
import Overlay from '../../components/Overlay'
import socketClient from '../../shared/SocketClient'
import DialogReasonDeclineTicket from '../../components/Tickets/DialogReasonDeclineTicket'
import DialogAuthTicketDetails from '../../components/Tickets/DialogAuthTicketDetails'
export default {
  name: 'AuthTicketsTrayGrid',
  components: { Overlay, DialogReasonDeclineTicket, DialogAuthTicketDetails },
  data: () => ({
    overlay: true,
    loadingDataTable: true,
    criteriaSearch: null,
    headerProps: {
      sortByText: 'Ordenar por',
    },
    headers: [
      { text: '', value: 'id', align: 'start', sortable: false, filterable: false },
      { text: 'Tipo', value: 'type', sortable: true, filterable: true },
      { text: 'Grupo', value: 'groupName', sortable: true, filterable: true },
      { text: 'Subgrupo', value: 'subGroupName', sortable: true, filterable: true },
      { text: 'Status', value: 'status', sortable: true, filterable: true },
      { text: 'Prioridad', value: 'priority', sortable: true, filterable: true },
      {
        text: 'Autorización por',
        value: 'userCreate',
        sortable: false,
        filterable: true,
      },
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
      ticketsToAuthorize: 'tickets/ticketsToAuthorize',
    }),
    loggedUserId() {
      return this.dataUser.user.id
    },
  },
  methods: {
    async authorize(id, status, subGroupId, groupId) {
      try {
        this.overlay = true
        const userAuthorizeId = this.loggedUserId
        const payload = {
          id,
          status,
          subGroupId,
          userAuthorizeId,
          groupId,
        }

        const response = await this.$store.dispatch('tickets/authorize', payload)

        if (response.ok) {
          this.overlay = false
        }
      } catch (error) {
        this.overlay = false
      }
    },
    openDeclineDialog(id, userCreateId) {
      const userDeclineId = this.loggedUserId
      const status = 8
      const payload = {
        id,
        status,
        userDeclineId,
      }

      this.$store.dispatch('tickets/setDeclineTicketData', payload)
      this.$store.dispatch('tickets/openDialogReasonDecline')
    },
    openDialogDetails(ticket) {
      this.$store.dispatch('tickets/setTicketDetails', ticket)
      this.$store.dispatch('tickets/openDialogDetailsAuth')
    },
    async loadDataTable() {
      try {
        await this.$store.dispatch('tickets/getToAuthorizeByUser', {
          userId: this.loggedUserId,
        })

        this.overlay = false
        this.loadingDataTable = false
      } catch (error) {
        this.overlay = false
        this.loadingDataTable = false
      }
    },
  },
  created() {
    const self = this

    self.loadDataTable()

    socketClient.authorizeOn()
    socketClient.declineAuthOn()
    socketClient.refreshAuthTicketsTrayOn()
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
