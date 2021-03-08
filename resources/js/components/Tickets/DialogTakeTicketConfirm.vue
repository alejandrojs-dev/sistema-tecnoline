<template>
  <div>
    <v-dialog v-model="openDialogTakeTicket" max-width="335" persistent>
      <v-card>
        <v-card-title class="headline">Tomar ticket</v-card-title>
        <v-card-text>
          ¿ Está seguro que desea tomar el ticket ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="white--text" @click="closeTakeDialog()" :disabled="loading">
            <v-icon left dark>mdi-close-circle</v-icon>
            Cancelar
          </v-btn>
          <v-btn color="green" class="white--text" @click="takeTicket()" :disabled="loading" :loading="loading">
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
export default {
  name: 'DialogTakeTicketConfirm',
  data: () => ({
    loading: false,
    isViewed: false,
  }),
  computed: {
    ...mapGetters({
      openDialogTakeTicket: 'tickets/openDialogTakeTicket',
      takeTicketData: 'tickets/takeTicketData',
    }),
  },
  methods: {
    closeTakeDialog() {
      this.$store.dispatch('tickets/openDialogTakeTicket')
    },
    async takeTicket() {
      try {
        this.loading = true
        this.isViewed = true

        const ticketGroupUserIds = this.takeTicketData.users

        const payload = {
          ticketId: this.takeTicketData.ticketId,
          status: this.takeTicketData.status,
          userTakeTicketId: this.takeTicketData.userTakeTicketId,
          semaphoreColor: this.takeTicketData.semaphoreColor,
          isViewed: this.isViewed,
          ticketGroupUserIds: ticketGroupUserIds,
        }

        const response = await this.$store.dispatch('tickets/take', payload)

        if (response.ok) {
          this.loading = false
          this.closeTakeDialog()
        }
      } catch (error) {
        this.loading = false
        console.log(error)
      }
    },
  },
}
</script>

<style></style>
