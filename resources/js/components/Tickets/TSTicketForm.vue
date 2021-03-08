<template>
  <div>
    <v-dialog persistent max-width="600" v-model="openTSTicketForm" :retain-focus="false">
      <v-card>
        <v-card-title class="headline">Crear ticket (TS)</v-card-title>
        <v-divider class="mx-3 mb-3" :inset="false"></v-divider>
        <Alert
          v-if="error"
          :alertType="alertInfoData.type"
          :alertMessage="alertInfoData.message"
          :alertIcon="alertInfoData.icon"
          :alertErrors="alertInfoData.errors"
        />
        <v-form id="frmTSTicket">
          <v-card-text>
            <div class="smallDiv"><small class="smallColor">*Campos obligatorios</small></div>
            <v-list class="ml-3 mb-3">
              <v-list-item two-line>
                <v-list-item-content>
                  <v-list-item-title>Grupo</v-list-item-title>
                  <v-list-item-subtitle>{{ sharedTicketModalData.ticketGroupName }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item two-line>
                <v-list-item-content>
                  <v-list-item-title>Requerimiento</v-list-item-title>
                  <v-list-item-subtitle>{{ sharedTicketModalData.ticketSubGroupName }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>

            <v-select
              :items="priorities"
              label="Prioridad*"
              outlined
              dense
              prepend-icon="mdi-account-group"
              item-value="id"
              item-text="description"
              v-model="priorityId"
              :error-messages="priorityIdErrors"
              :disabled="disableSelectPriority"
            >
              <template v-slot:item="{ item }">
                <v-list-item-content>
                  <v-list-item-title>{{ item.description }}</v-list-item-title>
                </v-list-item-content>
              </template>
            </v-select>
            <v-textarea
              label="Descripcion"
              outlined
              clearable
              prepend-icon="mdi-comment"
              :disabled="disableDescription"
              :error-messages="descriptionErrors"
              v-model="description"
            ></v-textarea>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red" class="white--text" @click="closeForm()" :disabled="loading">
              Cancelar
              <v-icon right dark>mdi-close-circle</v-icon>
            </v-btn>
            <v-btn color="green" class="white--text" @click="save()" :disabled="loading" :loading="loading">
              Guardar
              <template v-slot:loader>
                <span>Espere...</span>
              </template>
              <v-icon right dark>mdi-check-circle</v-icon>
            </v-btn>
          </v-card-actions>
        </v-form>
        <v-progress-linear color="primary" indeterminate height="6" v-if="loading"></v-progress-linear>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { required } from 'vuelidate/lib/validators'
import Alert from '../../components/Alert'
import TSTicket from '../../models/TSTicket.model'
import Toastr from '../../shared/Toastr'
export default {
  name: 'TSTicketForm',
  components: { Alert },
  data: () => ({
    description: null,
    priorityId: null,
    disableSelectPriority: false,
    disableDescription: false,
    loading: false,
    ticketGroupIds: [],
  }),
  validations: {
    priorityId: { required },
    description: { required },
  },
  computed: {
    ...mapGetters({
      openTSTicketForm: 'tickets/openTSTicketForm',
      error: 'actions/error',
      alertInfoData: 'actions/alertInfo',
      sharedTicketModalData: 'tickets/sharedTicketModalData',
      dataUser: 'auth/dataUser',
      priorities: 'tickets/priorities',
    }),
    priorityIdErrors() {
      const errors = []
      if (!this.$v.priorityId.$dirty) return errors
      if (!this.$v.priorityId.required && errors.push('Debes seleccionar la prioridad del ticket')) return errors
    },
    descriptionErrors() {
      const errors = []
      if (!this.$v.description.$dirty) return errors
      if (!this.$v.description.required && errors.push('El campo descripción es obligatorio')) return errors
    },
  },
  methods: {
    cleanForm() {
      this.description = null
      this.priorityId = null
      this.$v.$reset()
    },
    disableFormFields() {
      this.disableSelectPriority = true
      this.disableDescription = true
    },
    enableFormFields() {
      this.disableSelectPriority = false
      this.disableDescription = false
    },
    setError(payload) {
      this.$store.dispatch('actions/setError', payload)
    },
    closeForm() {
      this.cleanForm()
      this.setError({ error: false })
      this.$store.dispatch('tickets/openTSTicketForm')
    },
    showToast(message) {
      const toastr = new Toastr(message, '¡Éxito!', 3000, 'toast-top-right', 0, true, 'fadeOut', 'fadeIn', 'fadeOut', 1000, 'toastr', null)
      toastr.launch()
    },
    async getPriorities() {
      try {
        await this.$store.dispatch('tickets/getPriorities')
      } catch (error) {}
    },
    async save() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.disableFormFields()
        if (this.error) this.setError({ error: false })
        try {
          const ticketGroupUserIds = this.sharedTicketModalData.ticketGroupUserIds
          const userCreate = this.dataUser.user.username

          const tsTicket = new TSTicket(
            this.sharedTicketModalData.ticketGroupId,
            this.sharedTicketModalData.ticketSubGroupId,
            this.sharedTicketModalData.ticketTypeId,
            this.priorityId,
            this.description,
            this.sharedTicketModalData.userTicketCreate,
          )

          const response = await this.$store.dispatch('tickets/saveTSTicket', {
            tsTicket,
            ticketGroupUserIds,
            userCreate,
          })

          if (response.ok) {
            const { message } = response
            this.loading = false
            this.enableFormFields()
            this.closeForm()
            this.showToast(message)
          }
        } catch (error) {
          this.loading = false
          this.enableFormFields()
        }
      }
    },
  },
  created() {
    this.getPriorities()
  },
}
</script>

<style scoped>
.smallColor {
  color: #ff5252;
}
.smallDiv {
  margin-top: 3px;
  margin-bottom: 10px;
}
</style>
