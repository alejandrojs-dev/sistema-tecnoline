<template>
  <div>
    <v-dialog v-model="openDialogDelete" max-width="335" persistent>
      <v-card>
        <v-card-title class="headline">Eliminar Dashboard</v-card-title>
        <v-card-text>
          ¿ Está seguro que desea eliminar el dashboard ?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="white--text" @click="closeDialog()" :disabled="loading">
            <v-icon left dark>mdi-close-circle</v-icon>
            Cancelar
          </v-btn>
          <v-btn color="green" class="white--text" @click="deleteDashboard()" :disabled="loading" :loading="loading">
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
  name: 'DialogDeleteDashboard',
  data: () => ({
    loading: false,
  }),
  props: ['id'],
  computed: {
    ...mapGetters({
      openDialogDelete: 'dashboards/openDialogDelete',
    }),
  },
  methods: {
    closeDialog() {
      this.$store.dispatch('dashboards/openDialogDelete')
    },
    showTast(message) {
      const toastr = new Toastr(message, '¡Éxito!', 3000, 'toast-bottom-center', 0, true, 'fadeOut', 'fadeIn', 'fadeOut', 1000, 'toastr', null)
      toastr.launch()
    },
    async deleteDashboard() {
      try {
        this.loading = true
        const response = await this.$store.dispatch('dashboards/delete', { id: this.id })
        if (response.ok) {
          this.loading = false
          this.$store.dispatch('dashboards/deleteDashboardTable', { id: this.id })
          this.showTast(response.message)
          this.closeDialog()
        }
      } catch (error) {
        this.loading = false
      }
    },
  },
  created() {},
}
</script>

<style></style>
