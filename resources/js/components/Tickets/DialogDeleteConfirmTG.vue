<template>
  <div>
    <v-dialog v-model="openDialogDelete" max-width="335" persistent>
      <v-card>
        <v-card-title class="headline">Eliminar grupo de ticket</v-card-title>
        <v-card-text>
          ¿ Está seguro que desea eliminar el registro ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" class="white--text" @click="closeDialogDelete()" :disabled="loading">
            Cancelar
            <v-icon right dark>mdi-close-circle</v-icon>
          </v-btn>
          <v-btn color="red" class="white--text" @click="deleteTG()" :disabled="loading" :loading="loading">
            Aceptar
            <template v-slot:loader>
              <span>Espere...</span>
            </template>
            <v-icon right dark>mdi-delete</v-icon>
          </v-btn>
        </v-card-actions>
        <v-progress-linear color="primary" indeterminate height="6" v-if="loading"></v-progress-linear>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Toastr from '../../shared/Toastr'
export default {
  name: 'DialogDeleteConfirmTG',
  data: () => ({
    loading: false,
  }),
  props: ['id'],
  computed: {
    ...mapGetters({ openDialogDelete: 'ticketGroups/openDialogDelete' }),
  },
  methods: {
    closeDialogDelete() {
      this.$store.dispatch('ticketGroups/openDialogDelete')
    },
    showToast(message) {
      const toastr = new Toastr(message, '¡Éxito!', 3000, 'toast-bottom-center', 0, true, 'fadeOut', 'fadeIn', 'fadeOut', 1000, 'toastr', null)
      toastr.launch()
    },
    deleteTGTable(id) {
      this.$store.dispatch('ticketGroups/deleteTGTable', { id })
    },
    async deleteTG() {
      try {
        this.loading = true
        const response = await this.$store.dispatch('ticketGroups/delete', {
          id: this.id,
        })
        if (response.ok) {
          const { message } = response
          this.loading = false
          this.deleteTGTable(this.id)
          this.closeDialogDelete()
          this.showToast(message)
        }
      } catch (error) {
        this.loading = false
      }
    },
  },
}
</script>

<style></style>
