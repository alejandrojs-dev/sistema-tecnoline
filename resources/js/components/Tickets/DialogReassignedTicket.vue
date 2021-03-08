<template>
  <div>
    <v-dialog v-model="openDialogReassignTicket" max-width="500" persistent>
      <v-card>
        <v-card-title class="headline">Reasignar ticket</v-card-title>
        <v-card-text>
          <v-row no-gutters class="ml-20">
            <v-col cols="12" md="12">
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
              </v-card-text>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="white--text" @click="closeDialogReassign()" :disabled="loading">
            <v-icon left dark>mdi-close-circle</v-icon>
            Cancelar
          </v-btn>
          <v-btn color="blue" class="white--text" @click="reassignTicket()" :disabled="loading" :loading="loading">
            <v-icon left dark>mdi-account-switch</v-icon>
            Asignar
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
  name: 'DialogReassignedTicket',
  data: () => ({
    loading: false,
    userIdToAssign: null,
    disableAutocomplete: false,
  }),
  validations: {
    userIdToAssign: { required },
  },
  computed: {
    ...mapGetters({
      openDialogReassignTicket: 'tickets/openDialogReassignTicket',
      dataUser: 'auth/dataUser',
      reassignTicketData: 'tickets/reassignTicketData',
    }),
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
    disableFormFields() {
      this.disableAutocomplete = true
    },
    enableFormFields() {
      this.disableAutocomplete = false
    },
    cleanForm() {
      this.userIdToAssign = null
      this.$v.$reset()
    },
    closeDialogReassign() {
      this.cleanForm()
      this.$store.dispatch('tickets/openDialogReassignTicket')
    },
    async reassignTicket() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
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
            this.enableFormFields()
            this.closeDialogReassign()
          }
        } catch (error) {
          this.loading = false
        }
      }
    },
  },
  created() {},
}
</script>

<style></style>
