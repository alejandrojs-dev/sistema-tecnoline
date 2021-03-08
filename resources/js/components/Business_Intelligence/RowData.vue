<template>
  <div style="position:relative;height:100%;">
    <v-data-table :headers="headers" :items="rowData" :items-per-page="5" class="elevation-1" v-if="!loading"> </v-data-table>
    <v-container class="mt-10" style="height: 400px;" v-if="loading">
      <v-row class="mt-10" align-content="center" justify="center">
        <v-col cols="12" class="subtitle-1 text-center">
          Cargando...
        </v-col>
        <v-col cols="6">
          <v-progress-linear indeterminate color="green" height="6"></v-progress-linear>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'RowData',
  data: () => ({
    headers: [],
    rowData: [],
    loading: false,
  }),
  computed: {
    ...mapGetters({}),
  },
  methods: {
    async getRowData(payload) {
      try {
        this.loading = true
        const res = await this.$store.dispatch('dashboards/getBIData', payload)
        if (res.ok) {
          this.headers = res.response.headers
          this.rowData = res.response.rowData
          this.loading = false
        }
      } catch (error) {
        this.loading = false
      }
    },
  },
  created() {},
}
</script>

<style scoped></style>
