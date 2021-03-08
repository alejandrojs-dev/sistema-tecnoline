<template>
  <div>
    <v-dialog v-model="openDialogDetailsAuth" width="700" persistent :retain-focus="false">
      <v-card>
        <v-card-title class="headline">
          <v-row>
            <v-col cols="12">
              Detalles de ticket
            </v-col>
          </v-row>
        </v-card-title>

        <v-divider class="mx-4"></v-divider>

        <v-card-text>
          <v-tabs color="carbon" v-model="tab">
            <v-tab>Detalles</v-tab>
            <v-tab>Acciones</v-tab>
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
                    <v-chip :color="setColorChip(ticketDetails.typeId)" text-color="white" small>
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
                    <span class="greyText">(Por {{ ticketDetails.userCancel }})</span>
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
                    <div class="greyText">{{ ticketDetails.userCreate }}</div>
                  </v-col>

                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Fecha de creación</div>
                    <div class="greyText">{{ formatDate(ticketDetails.createdAt) }}</div>
                  </v-col>

                  <v-col cols="3">
                    <div class="subtitle-1 black--text">Fecha de cancelación</div>
                    <div class="greyText">{{ formatDate(ticketDetails.cancelAt) }}</div>
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
            <v-tab-item>
              <v-container fluid>
                <v-row>
                  <v-col cols="4">
                    <v-list>
                      <v-list-item-group>
                        <v-list-item @click="showPanel('authorize')">
                          <v-list-item-icon>
                            <v-icon>mdi-check-bold</v-icon>
                          </v-list-item-icon>
                          <v-list-item-content>
                            <v-list-item-title>Autorizar</v-list-item-title>
                          </v-list-item-content>
                        </v-list-item>
                        <v-list-item @click="showPanel('decline')" :disabled="ticketDetails.declined">
                          <v-list-item-icon>
                            <v-icon>mdi-close-thick</v-icon>
                          </v-list-item-icon>
                          <v-list-item-content>
                            <v-list-item-title>Declinar</v-list-item-title>
                          </v-list-item-content>
                        </v-list-item>
                      </v-list-item-group>
                    </v-list>
                  </v-col>

                  <v-col cols="8" v-if="showAuthorizePanel">
                    <v-btn color="blue" class="white--text" @click="authorize(ticketDetails)" :disabled="loading" :loading="loading" block depressed>
                      Autorizar
                      <template v-slot:loader>
                        <span>Espere...</span>
                      </template>
                    </v-btn>

                    <p class="mt-5 black--text" v-if="ticketDetails.authorized">
                      Estatus: Autorizado
                    </p>
                    <p class="mt-5 black--text" v-else>
                      Estatus: Sin autorizar
                    </p>
                  </v-col>

                  <v-col cols="8" v-if="showDeclinePanel">
                    <v-form>
                      <v-textarea
                        label="Motivo de declinación"
                        outlined
                        clearable
                        :disabled="disableDeclineComment"
                        :error-messages="declineCommentErros"
                        v-model="declineComment"
                      ></v-textarea>
                      <v-btn
                        color="green"
                        class="white--text"
                        @click="decline(ticketDetails.id)"
                        :disabled="loading"
                        :loading="loading"
                        block
                        depressed
                      >
                        Aceptar
                        <template v-slot:loader>
                          <span>Espere...</span>
                        </template>
                      </v-btn>
                    </v-form>
                  </v-col>
                </v-row>
              </v-container>
            </v-tab-item>
          </v-tabs-items>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="white--text" @click="closeDialog()" depressed>
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
import DialogReasonDeclineTicket from '../../components/Tickets/DialogReasonDeclineTicket'
import moment from 'moment'
import 'moment/locale/es'
export default {
  name: 'DialogAuthTicketDetails',
  components: { DialogReasonDeclineTicket },
  props: ['ticket'],
  data: () => ({
    loading: false,
    showText: false,
    tab: null,
    showAuthorizePanel: true,
    showDeclinePanel: false,
    disableDeclineComment: false,
    declineComment: null,
    classStatusText: null,
    statusText: null,
  }),
  validations: {
    declineComment: { required },
  },
  computed: {
    ...mapGetters({
      openDialogDetailsAuth: 'tickets/openDialogDetailsAuth',
      dataUser: 'auth/dataUser',
      ticketDetails: 'tickets/ticketDetails',
    }),
    loggedUserId() {
      return this.dataUser.user.id
    },
    declineCommentErros() {
      const errors = []
      if (!this.$v.declineComment.$dirty) return errors
      if (!this.$v.declineComment.required && errors.push('El campo motivo de declinación es obligatorio')) return errors
    },
  },
  methods: {
    formatDate(date) {
      const notificationDate = moment(new Date(date))
      return moment(notificationDate).format('D MMM [a las] h:mm a')
    },
    setColorChip(typeId) {
      let color = ''
      switch (typeId) {
        case 1:
          color = 'primary'
          break

        case 2:
          color = 'grey'
          break
      }
      return color
    },
    async authorize({ id, statusId, subGroupId, groupId }) {
      try {
        this.loading = true

        const userAuthorizeId = this.loggedUserId

        const payload = {
          id,
          status: statusId,
          subGroupId,
          userAuthorizeId,
          groupId,
        }

        const response = await this.$store.dispatch('tickets/authorize', payload)

        if (response.ok) {
          this.loading = false
        }
      } catch (error) {
        this.loading = false
      }
    },
    openDialogDecline() {
      this.ticketStatus = 8
      this.$store.dispatch('tickets/openDialogReasonDecline')
      this.$store.dispatch('actions/openDialogDetails')
    },
    showPanel(panel) {
      switch (panel) {
        case 'authorize':
          this.showAuthorizePanel = true
          this.showDeclinePanel = false
          break

        case 'decline':
          this.showDeclinePanel = true
          this.showAuthorizePanel = false
          break
      }
    },
    enableFormFields() {
      this.disableDeclineComment = false
    },
    disableFormFields() {
      this.disableDeclineComment = true
    },
    cleanFormFields() {
      this.disableDeclineComment = null
      this.$v.reset()
    },
    async decline(id) {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.disableFormFields()
        try {
          const expired = true

          const payload = {
            id: id,
            status: 8,
            userDeclineId: this.loggedUserId,
            declineComment: this.declineComment,
            expired: expired,
          }

          const response = await this.$store.dispatch('tickets/decline', payload)

          if (response.ok) {
            this.loading = false
            this.cleanFormFields()
            this.enableFormFields()
          }
        } catch (error) {
          this.loading = false
          this.enableFormFields()
        }
      }
    },
    closeDialog() {
      this.$store.dispatch('tickets/openDialogDetailsAuth')
      this.$parent.ticket = null
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
