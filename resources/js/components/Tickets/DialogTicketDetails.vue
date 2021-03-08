<template>
  <div>
    <v-dialog v-model="openDialogDetails" width="700" persistent :retain-focus="false">
      <v-card>
        <v-card-title class="headline">
          <v-row>
            <v-col cols="12" md="7">
              Detalles de ticket
            </v-col>
            <v-col cols="12" md="5">
              <div class="subtitle-1 black--text" v-if="ticketDetails.timerTextContent && !ticketDetails.canceled && !ticketDetails.paused">
                Tiempo restante:
                <v-chip :color="ticketDetails.semaphoreColor" text-color="white" small>
                  {{ ticketDetails.timerTextContent }}
                </v-chip>
              </div>
            </v-col>
          </v-row>
        </v-card-title>

        <v-divider class="mx-4"></v-divider>

        <v-card-text>
          <v-tabs color="carbon" v-model="tab">
            <v-tab>Detalles</v-tab>
            <v-tab
              v-if="
                (!ticketDetails.canceled && ticketDetails.taken && ticketDetails.type === 'TS') ||
                  (ticketDetails.reassigned && ticketDetails.type === 'TS')
              "
              >Acciones</v-tab
            >
            <v-tab v-if="ticketDetails.type === 'TAC'">Usuarios que autorizan</v-tab>
          </v-tabs>

          <v-tabs-items v-model="tab">
            <v-tab-item>
              <v-container fluid>
                <v-row>
                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Grupo</div>
                    <v-chip small>{{ ticketDetails.groupName }}</v-chip>
                  </v-col>
                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Tipo de ticket</div>
                    <v-chip :color="setColorChipType(ticketDetails.ticketTypeId)" text-color="white" small>
                      {{ ticketDetails.type }}
                    </v-chip>
                  </v-col>
                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Descripción general</div>
                    <div class="greyText">{{ ticketDetails.subGroupName }}</div>
                  </v-col>

                  <v-col cols="3" v-if="ticketDetails.canceled">
                    <div class="subtitle-1 black--text">Estatus</div>
                    <v-chip :color="ticketDetails.statusColor" text-color="white" small>
                      {{ ticketDetails.status }}
                    </v-chip>
                    <span class="greyText">(Por {{ ticketDetails.userCancelTicket }})</span>
                  </v-col>
                  <v-col cols="3" v-else>
                    <div class="subtitle-1 black--text">Estatus</div>
                    <v-chip :color="ticketDetails.statusColor" text-color="white" small>
                      {{ ticketDetails.status }}
                    </v-chip>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Prioridad</div>
                    <v-chip :color="ticketDetails.priorityColor" text-color="white" small>
                      {{ ticketDetails.priority }}
                    </v-chip>
                  </v-col>

                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Creado por</div>
                    <div class="greyText">{{ ticketDetails.userCreateTicket }}</div>
                  </v-col>

                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Asignado a</div>
                    <div class="greyText">{{ ticketDetails.userTakenTicket }}</div>
                  </v-col>

                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Fecha de creación</div>
                    <div class="greyText">{{ formatDate(ticketDetails.createdAt) }}</div>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Fecha de cancelación</div>
                    <div class="greyText">{{ formatDate(ticketDetails.dateCancelTicket) }}</div>
                  </v-col>

                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Fecha de asignación</div>
                    <div class="greyText">{{ formatDate(ticketDetails.dateTakenTicket) }}</div>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col cols="12">
                    <div class="subtitle-1 black--text">Descripción</div>
                    <div class="greyText">{{ ticketDetails.description }}</div>
                  </v-col>
                </v-row>
              </v-container>
            </v-tab-item>

            <v-tab-item v-if="ticketDetails.type === 'TS'">
              <v-container fluid>
                <h3>Reasignación de ticket</h3>
                <v-row>
                  <v-col cols="8">
                    <v-card-text>
                      <v-autocomplete
                        v-model="userIdToAssign"
                        :disabled="disableAutocomplete"
                        :items="reassignTicketData.users"
                        :error-messages="userIdToAssignErrors"
                        prepend-icon="mdi-account"
                        item-value="user_id"
                        item-text="username"
                        color="primary"
                        label="Reasignar ticket a"
                        chips
                        small-chips
                        outlined
                        dense
                      >
                        <template v-slot:selection="data">
                          <v-chip v-bind="data.attrs" :input-value="data.selected" @click:close="removeUserIdToAssign()" close>
                            <v-avatar left size="10">
                              <v-img :src="require('../../../assets/logo.png')"></v-img>
                            </v-avatar>
                            {{ data.item.username }}
                          </v-chip>
                        </template>
                        <template v-slot:item="data">
                          <template>
                            <v-list-item-avatar size="30">
                              <v-img :src="require('../../../assets/logo.png')"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                              <v-list-item-title>{{ data.item.username }}</v-list-item-title>
                            </v-list-item-content>
                          </template>
                        </template>
                      </v-autocomplete>
                      <p class="ml-8 black--text" v-if="ticketDetails.reassigned">Reasignado a {{ ticketDetails.userTakenTicket }}</p>
                    </v-card-text>
                  </v-col>

                  <v-col cols="4">
                    <v-card-text>
                      <v-btn color="blue" class="white--text" @click="reassignTicket()" :disabled="loading" :loading="loading" depressed>
                        Reasignar
                      </v-btn>
                    </v-card-text>
                  </v-col>
                </v-row>
              </v-container>
            </v-tab-item>

            <v-tab-item v-if="ticketDetails.type === 'TAC'">
              <v-row>
                <v-col cols="12">
                  <v-simple-table>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-left">USUARIO</th>
                          <th class="text-left">HA AUTORIZADO</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="user in authorizeUsersByTicket" :key="user.id">
                          <td>
                            <v-row>
                              <v-col cols="2">
                                <v-avatar size="30">
                                  <v-img :src="require('../../../assets/logo.png')"></v-img>
                                </v-avatar>
                              </v-col>
                              <v-col cols="10">
                                {{ user.username }}
                              </v-col>
                            </v-row>
                          </td>
                          <td>
                            <v-chip text-color="white" :color="setColorChipAuthorize(user.hasAuthorized)" small>{{
                              setAuthorizeText(user.hasAuthorized)
                            }}</v-chip>
                          </td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" class="text-center">
                  <v-btn color="green" class="white--text" @click="refreshAuthUsers(ticketDetails.id)" depressed>
                    <v-icon left dark>mdi-refresh</v-icon> Refrescar
                  </v-btn>
                </v-col>
              </v-row>
            </v-tab-item>
          </v-tabs-items>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="white--text" @click="closeDetailsDialog()">
            <v-icon left dark>mdi-close-circle</v-icon>
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { required } from 'vuelidate/lib/validators'
import moment from 'moment'
import 'moment/locale/es'
export default {
  name: 'DialogTicketDetails',
  data: () => ({
    disableAutocomplete: false,
    userIdToAssign: null,
    loading: false,
    showMdiCheck: false,
    declineComment: '',
    panel: [0],
    tab: null,
  }),
  validations: {
    userIdToAssign: { required },
  },
  computed: {
    ...mapGetters({
      openDialogDetails: 'tickets/openDialogDetails',
      dataUser: 'auth/dataUser',
      ticketDetails: 'tickets/ticketDetails',
      authorizeUsersByTicket: 'tickets/authorizeUsersByTicket',
      reassignTicketData: 'tickets/reassignTicketData',
      serviceLevelTimerData: 'tickets/serviceLevelTimerData',
    }),
    loggedUserId() {
      return this.dataUser.user.id
    },
    userIdToAssignErrors() {
      const errors = []
      if (!this.$v.userIdToAssign.$dirty) return errors
      if (!this.$v.userIdToAssign.required && errors.push('Debes seleccionar un usuario para la asignación')) return errors
    },
  },
  methods: {
    removeUserIdToAssign() {
      this.userIdToAssign = null
    },
    formatDate(date) {
      const notificationDate = moment(new Date(date))
      return moment(notificationDate).format('D MMM [a las] h:mm a')
    },
    disableFormFields() {
      this.disableAutocomplete = true
    },
    enableFormFields() {
      this.disableAutocomplete = false
    },
    cleanForm() {
      this.userIdToAssign = null
      if (this.showMdiCheck) {
        this.showMdiCheck = false
      }
      this.$v.$reset()
    },
    setColorChipType(ticketTypeId) {
      let color = ''
      switch (ticketTypeId) {
        case 1:
          color = 'primary'
          break

        case 2:
          color = 'grey'
          break
      }
      return color
    },
    setColorChipAuthorize(hasAuthorized) {
      return hasAuthorized ? 'green' : 'red'
    },
    setAuthorizeText(hasAuthorized) {
      return hasAuthorized ? 'SI' : 'NO'
    },
    async reassignTicket() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.showMdiCheck = false
        this.disableFormFields()
        try {
          const ticketGroupUserIds = this.reassignTicketData.users.map(u => u.user_id)

          const payload = {
            ticketId: this.reassignTicketData.ticketId,
            userIdWhoAssign: this.reassignTicketData.userIdWhoAssign,
            userIdToAssign: this.userIdToAssign,
            status: this.reassignTicketData.status,
            isViewed: this.reassignTicketData.isViewed,
            userWhoReassign: this.reassignTicketData.userWhoReassign,
            ticketGroupUserIds: ticketGroupUserIds,
          }

          const response = await this.$store.dispatch('tickets/reassign', payload)

          if (response.ok) {
            this.loading = false
            this.showMdiCheck = true
            this.enableFormFields()
          }
        } catch (error) {
          this.loading = false
          this.enableFormFields()
        }
      }
    },
    async refreshAuthUsers(id) {
      await this.$parent.getAuthorizeUsersByTicket(id)
    },
    closeDetailsDialog() {
      this.cleanForm()
      this.$store.dispatch('tickets/openDialogDetails')
      this.$parent.ticket = null
    },
    openExpansionPanel() {
      this.panel = []
    },
  },
}
</script>

<style scoped>
.greyText {
  color: #666666;
}
.column {
  width: 50%;
}
.ml-20 {
  margin-left: 20px;
}
</style>
