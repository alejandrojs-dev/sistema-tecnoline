<template>
  <div>
    <Overlay :overlay="overlay" />
    <v-sheet class="mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid v-if="!loadingDataTable">
        <v-row>
          <v-col cols="12" md="8">
            <div class="tableTitleContainer">
              <span class="tableTitle">Partidas en {{ process.name }}</span>
            </div>
          </v-col>
          <v-spacer></v-spacer>
          <v-col cols="12" md="4">
            <v-text-field v-model="criteriaSearch" append-icon="mdi-magnify" label="Buscar" single-line hide-details class="mb-2"></v-text-field>
          </v-col>
          <v-col cols="12">
            <v-data-table
              id="dtPerfileria"
              :headers="headers"
              :items="orders"
              :items-per-page="10"
              :footer-props="footerProps"
              :search="criteriaSearch"
              :header-props="headerProps"
              item-key="id"
              class="elevation-1"
              loading-text="Cargando..."
              no-data-text="No hay pedidos"
              no-results-text="No se encontró el pedido"
            >
              <template v-slot:[`header.id`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.${process.slug}`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`header.actions`]="{ header }">{{ header.text.toUpperCase() }}</template>
              <template v-slot:[`item.actions`]="{ item }">
                <v-btn small color="carbon" class="ma-2 white--text" @click="gotoProcess(item.id)">
                  Ir a proceso
                  <v-icon right dark>mdi-redo</v-icon>
                </v-btn>
              </template>
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
import { mapGetters } from 'vuex'
import Overlay from '../../components/Overlay'
export default {
  name: 'ProcessGrid',
  components: { Overlay },
  data: () => ({
    orders: [],
    criteriaSearch: null,
    loadingDataTable: true,
    valor: 2,
    overlay: true,
    headerProps: {
      sortByText: 'Ordenar por',
    },
    headers: [],
    footerProps: {
      showFirstLastPage: true,
      firstIcon: 'mdi-arrow-collapse-left',
      lastIcon: 'mdi-arrow-collapse-right',
      prevIcon: 'mdi-minus',
      nextIcon: 'mdi-plus',
    },
  }),
  computed: {
    ...mapGetters({
      process: 'processes/process',
      dataUser: 'auth/dataUser',
    }),
  },
  methods: {
    buildHeaders() {
      this.headers.push(
        { text: 'Pedido', value: 'id', align: 'center', sortable: false, filterable: false },
        { text: 'Número de partidas', value: this.process.slug, align: 'center', sortable: true, filterable: true },
        { text: 'Acciones', value: 'actions', sortable: false, align: 'center' },
      )
    },
    async getItemsCount() {
      try {
        const response = await this.$store.dispatch('processes/getCountItemsByProcess', {
          processId: this.process.id,
          alias: this.process.slug,
        })
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
    gotoProcess(orderId) {
      this.$router.push({ name: 'Perfileria Detalles', params: { id: orderId } })
    },
    backtoProcesses() {
      this.$router.push({ name: 'Procesos' })
    },
  },
  created() {
    this.buildHeaders()
    this.getItemsCount()
  },
}
</script>

<style scope>
.tableTitle {
  font-size: 25px;
}
.tableTitleContainer {
  margin-top: 20px;
}
</style>
