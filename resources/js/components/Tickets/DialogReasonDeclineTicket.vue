<template>
  <div>
    <v-dialog v-model="openDialogReasonDecline" max-width="500" persistent :retain-focus="false">
      <v-card>
        <v-card-title class="headline">Declinar Ticket</v-card-title>
        <v-divider class="mx-3 mb-3" :inset="false"></v-divider>
        <v-card-text>
          <v-form>
            <v-textarea
              label="Motivo de declinación"
              outlined
              clearable
              prepend-icon="mdi-comment"
              :disabled="disableDeclineComment"
              :error-messages="declineCommentErros"
              v-model="declineComment"
            ></v-textarea>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="white--text" @click="closeDialog()" :disabled="loading">
            <v-icon left dark>mdi-close-thick</v-icon>
            Cancelar
          </v-btn>
          <v-btn color="green" class="white--text" @click="decline()" :disabled="loading" :loading="loading">
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
  name: 'DialogReasonDeclineTicket',
  data: () => ({
    loading: false,
    declineComment: null,
    disableDeclineComment: false,
  }),
  validations: {
    declineComment: { required },
  },
  computed: {
    ...mapGetters({
      openDialogReasonDecline: 'tickets/openDialogReasonDecline',
      declineTicketData: 'tickets/declineTicketData',
    }),
    declineCommentErros() {
      const errors = []
      if (!this.$v.declineComment.$dirty) return errors
      if (!this.$v.declineComment.required && errors.push('El campo motivo de declinación es obligatorio')) return errors
    },
  },
  methods: {
    closeDialog() {
      this.$store.dispatch('tickets/openDialogReasonDecline')
    },
    disableFormFields() {
      this.disableDeclineComment = true
    },
    enableFormFields() {
      this.disableDeclineComment = false
    },
    cleanFormFields() {
      this.declineComment = null
      this.$v.$reset()
    },
    async decline() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.disableFormFields()
        try {
          const payload = {
            id: this.declineTicketData.id,
            status: this.declineTicketData.status,
            userDeclineId: this.declineTicketData.userDeclineId,
            declineComment: this.declineComment,
          }

          const response = await this.$store.dispatch('tickets/decline', payload)

          if (response.ok) {
            this.loading = false
            this.cleanFormFields()
            this.enableFormFields()
            this.closeDialog()
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
