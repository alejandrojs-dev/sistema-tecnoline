<template>
  <div>
    <v-dialog v-model="openDialogReasonCancel" max-width="500" persistent>
      <v-card>
        <v-card-title class="headline">Cancelar Ticket</v-card-title>
        <v-divider class="mx-3 mb-3" :inset="false"></v-divider>
        <v-card-text>
          <v-form>
            <v-textarea
              label="Motivo de cancelación"
              outlined
              clearable
              prepend-icon="mdi-comment"
              :disabled="disableCancelComment"
              :error-messages="cancelCommentErrors"
              v-model="cancelComment"
            ></v-textarea>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="white--text" @click="closeDialogReasonCancel()" :disabled="loading">
            <v-icon left dark>mdi-close-thick</v-icon>
            Cancelar
          </v-btn>
          <v-btn color="green" class="white--text" @click="cancelTicket()" :disabled="loading" :loading="loading">
            <v-icon left dark>mdi-check-circle</v-icon>
            Aceptar
            <template v-slot:loader>
              <span>Espere...</span>
            </template>
          </v-btn>
        </v-card-actions>
        <v-progress-linear color="primary" indeterminate height="6" v-if="loading"></v-progress-linear>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { required } from 'vuelidate/lib/validators'
export default {
  name: 'DialogReasonCancelTicket',
  data: () => ({
    loading: false,
    cancelComment: '',
    disableCancelComment: false,
  }),
  validations: {
    cancelComment: { required },
  },
  computed: {
    ...mapGetters({
      openDialogReasonCancel: 'tickets/openDialogReasonCancel',
      intervals: 'actions/intervals',
      dataUser: 'auth/dataUser',
      cancelTicketData: 'tickets/cancelTicketData',
    }),
    loggedUserId() {
      return this.dataUser.user.id
    },
    cancelCommentErrors() {
      const errors = []
      if (!this.$v.cancelComment.$dirty) return errors
      if (!this.$v.cancelComment.required && errors.push('El campo motivo de cancelación es obligatorio')) return errors
    },
  },
  methods: {
    closeDialogReasonCancel() {
      this.$store.dispatch('tickets/openDialogReasonCancel')
    },
    disableFormFields() {
      this.disableCancelComment = true
    },
    enableFormFields() {
      this.disableCancelComment = false
    },
    cleanFormFields() {
      this.cancelComment = ''
      this.$v.$reset()
    },
    stopServiceLevelTicketTimer(ticketId) {
      const objInterval = this.intervals.find(i => i.ticketId === ticketId)
      if (objInterval) {
        this.$store.dispatch('actions/deleteInterval', { objInterval })
        clearInterval(objInterval.interval)
      }
    },
    async cancelTicket() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.disableFormFields()
        try {
          const ticketGroupUserIds = this.cancelTicketData.users
          const existInArray = ticketGroupUserIds.includes(this.loggedUserId)

          if (!existInArray) {
            ticketGroupUserIds.push(this.loggedUserId)
          }

          const payload = {
            ticketId: this.cancelTicketData.ticketId,
            status: this.cancelTicketData.status,
            userCancelTicketId: this.cancelTicketData.userCancelTicketId,
            cancelComment: this.cancelComment,
            expired: this.cancelTicketData.expired,
            ticketGroupUserIds: ticketGroupUserIds,
          }

          const response = await this.$store.dispatch('tickets/cancel', payload)

          if (response.ok) {
            this.loading = false
            this.stopServiceLevelTicketTimer(this.cancelTicketData.ticketId)
            this.cleanFormFields()
            this.enableFormFields()
            this.closeDialogReasonCancel()
          }
        } catch (error) {
          this.loading = false
          this.enableFormFields()
        }
      }
    },
  },
}
</script>

<style></style>
