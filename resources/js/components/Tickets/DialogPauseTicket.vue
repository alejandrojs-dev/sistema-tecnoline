<template>
  <div>
    <v-dialog v-model="openDialogPauseTicket" max-width="500" persistent>
      <v-card>
        <v-card-title class="headline">Detener Ticket</v-card-title>
        <v-divider class="mx-3 mb-3" :inset="false"></v-divider>
        <v-card-text>
          <v-form>
            <v-textarea
              label="Motivo de pausa"
              outlined
              clearable
              prepend-icon="mdi-comment"
              :disabled="disablePauseComment"
              :error-messages="pauseCommentsErrors"
              v-model="pauseComment"
            ></v-textarea>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="white--text" @click="closePauseDialog()" :disabled="loading">
            <v-icon left dark>mdi-close-thick</v-icon>
            Cancelar
          </v-btn>
          <v-btn color="green" class="white--text" @click="pauseTicket()" :disabled="loading" :loading="loading">
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
  name: 'DialogPauseTicket',
  data: () => ({
    loading: false,
    pauseComment: '',
    disablePauseComment: false,
  }),
  validations: {
    pauseComment: { required },
  },
  computed: {
    ...mapGetters({
      openDialogPauseTicket: 'tickets/openDialogPauseTicket',
      intervals: 'actions/intervals',
      pauseTicketData: 'tickets/pauseTicketData',
    }),
    pauseCommentsErrors() {
      const errors = []
      if (!this.$v.pauseComment.$dirty) return errors
      if (!this.$v.pauseComment.required && errors.push('El campo motivo de pausa es obligatorio')) return errors
    },
  },
  methods: {
    closePauseDialog() {
      this.$store.dispatch('tickets/openDialogPauseTicket')
    },
    disableFormFields() {
      this.disablePauseComment = true
    },
    enableFormFields() {
      this.disablePauseComment = false
    },
    cleanFormFields() {
      this.pauseComment = ''
      this.$v.$reset()
    },
    async pauseTicket() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.disableFormFields()
        try {
          const payload = {
            ticketId: this.pauseTicketData.ticketId,
            status: this.pauseTicketData.status,
            userPauseTicketId: this.pauseTicketData.userPauseTicketId,
            pauseComment: this.pauseComment,
            ticketGroupUserIds: this.pauseTicketData.users,
          }

          const response = await this.$store.dispatch('tickets/pause', payload)

          if (response.ok) {
            this.loading = false
            this.cleanFormFields()
            this.enableFormFields()
            this.closePauseDialog()
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
