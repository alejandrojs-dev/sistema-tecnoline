<template>
  <div>
    <v-dialog v-model="openDialogDelete" max-width="335" persistent>
      <v-card>
        <v-card-title class="headline">Eliminar subgrupo</v-card-title>
        <v-card-text>
          ¿ Está seguro que desea eliminar el registro ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="white--text" @click="closeDialogDelete()" :disabled="loading">
            <v-icon left dark>mdi-close-circle</v-icon>
            Cancelar
          </v-btn>
          <v-btn color="green" class="white--text" @click="deleteTS()" :disabled="loading" :loading="loading">
            <v-icon left dark>mdi-delete</v-icon>
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
import Toastr from '../../shared/Toastr'
export default {
  name: 'DialogDeleteConfirmTS',
  data: () => ({
    loading: false,
  }),
  props: ['id'],
  computed: {
    ...mapGetters({ openDialogDelete: 'ticketSubGroups/openDialogDelete' }),
  },
  methods: {
    closeDialogDelete() {
      this.$store.dispatch('ticketSubGroups/openDialogDelete')
    },
    showToast(message) {
      const toastr = new Toastr(message, '¡Éxito!', 3000, 'toast-bottom-center', 0, true, 'fadeOut', 'fadeIn', 'fadeOut', 1000, 'toastr', null)
      toastr.launch()
    },
    deleteTSTable(id) {
      this.$store.dispatch('ticketSubGroups/deleteTSTable', { id })
    },
    async deleteTS() {
      try {
        this.loading = true
        const response = await this.$store.dispatch('ticketSubGroups/delete', { id: this.id })
        if (response.ok) {
          const { message } = response
          this.loading = false
          this.deleteTSTable(this.id)
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
