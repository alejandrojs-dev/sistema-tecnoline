<template>
  <div>
    <Overlay :overlay="overlay" />
    <v-sheet class="mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid v-if="!loadingDataTable">
        <v-row>
          <v-col cols="12" md="8">
            <div class="tableTitleContainer">
              <span class="tableTitle">Pedidos en Producción</span>
            </div>
          </v-col>
          <v-spacer></v-spacer>
          <v-col cols="12" md="4">
            <v-text-field v-model="criteriaSearch" append-icon="mdi-magnify" label="Buscar" single-line hide-details class="mb-2"></v-text-field>
          </v-col>
          <v-col cols="12" md="12">
            <v-data-table
              id="dtOrders"
              class="elevation-1"
              :headers="headers"
              :items="orders"
              :items-per-page="10"
              :footer-props="footerProps"
              :search="criteriaSearch"
              :header-props="headerProps"
              item-key="id"
              loading-text="Cargando..."
              no-data-text="Sin pedidos"
              no-results-text="No se encontró el pedido"
            >
              <template v-slot:[`header.id`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.perfileria`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.cut`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.assembling`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.leveling`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.packaging`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.shipment`]="{ header }">{{ header.text.toUpperCase() }}</template>
            </v-data-table>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col cols="12" lg="12">
            <v-btn color="carbon" class="mx-2" fab dark @click="backtoProcesses()" v-if="!overlay">
              <v-icon dark>mdi-arrow-left-circle</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </div>
</template>

<script>
import Overlay from '../Overlay'
export default {
  name: 'OrdersGrid',
  components: { Overlay },
  data: () => ({
    orders: [],
    overlay: true,
    loadingDataTable: true,
    criteriaSearch: null,
    headerProps: {
      sortByText: 'Ordenar por',
    },
    headers: [
      { text: 'Pedido', value: 'id', align: 'center', sortable: false, filterable: false },
      { text: 'Perfileria', value: 'perfileria', align: 'center', sortable: true, filterable: true },
      { text: 'Corte', value: 'cut', align: 'center', sortable: true, filterable: true },
      { text: 'Armado', value: 'assembling', align: 'center', sortable: true, filterable: true },
      { text: 'Nivelación', value: 'leveling', align: 'center', sortable: true, filterable: true },
      { text: 'Empaquetado', value: 'packaging', align: 'center', sortable: true, filterable: true },
      { text: 'Embarque', value: 'shipment', align: 'center', sortable: true, filterable: true },
    ],
    footerProps: {
      showFirstLastPage: true,
      firstIcon: 'mdi-arrow-collapse-left',
      lastIcon: 'mdi-arrow-collapse-right',
      prevIcon: 'mdi-minus',
      nextIcon: 'mdi-plus',
    },
  }),
  computed: {},
  methods: {
    async getOrders() {
      try {
        const response = await this.$store.dispatch('orders/getOrders')
        if (response.ok) {
          this.orders = response.orders
          this.overlay = false
          this.loadingDataTable = false
        }
      } catch (error) {
        this.overlay = false
        this.loadingDataTable = false
      }
    },
    backtoProcesses() {
      this.$router.push({ name: 'Procesos' })
    },
  },
  created() {
    this.getOrders()
  },
}
</script>

<style scoped>
.tableTitle {
  font-size: 25px;
}
.tableTitleContainer {
  margin-top: 20px;
}
</style>
