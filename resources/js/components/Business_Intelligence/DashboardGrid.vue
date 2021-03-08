<template>
  <div>
    <DialogDeleteDashboard :id="dashboardId" />
    <v-container fluid v-if="!loadingDataTable">
      <v-row>
        <v-col cols="12">
          <v-btn color="blue" dark small @click="create()">
            Nuevo Dashboard
            <v-icon dark right>mdi-plus-circle</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="8">
          <div class="tableTitleContainer">
            <span class="tableTitle">Dashboards</span>
          </div>
        </v-col>
        <v-col cols="4">
          <div>
            <v-text-field
              id="txtCriteriaSearch"
              color="carbon"
              class="mb-2"
              v-model="criteriaSearch"
              append-icon="mdi-magnify"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
          </div>
        </v-col>
        <v-col cols="12">
          <v-data-table
            id="dtDashboards"
            class="elevation-1"
            :headers="headers"
            :items="dashboards"
            :items-per-page="10"
            :footer-props="footerProps"
            :search="criteriaSearch"
            :header-props="headerProps"
            item-key="id"
            loading-text="Cargando..."
            no-data-text="Sin dashboards"
            no-results-text="No se encontró el dashboard"
          >
            <template v-slot:[`header.name`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.description`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.isInCarousel`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.isSlide`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.isRowData`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.department.name`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`item.isSlide`]="{ item }">
              <v-chip :color="setColor(item.isSlide)" text-color="white" dark small>{{ setText(item.isSlide) }}</v-chip>
            </template>
            <template v-slot:[`item.isRowData`]="{ item }">
              <v-chip :color="setColor(item.isRowData)" text-color="white" dark small>{{ setText(item.isRowData) }}</v-chip>
            </template>
            <template v-slot:[`item.isInCarousel`]="{ item }">
              <v-chip :color="setColor(item.isInCarousel)" text-color="white" dark small>{{ setText(item.isInCarousel) }}</v-chip>
            </template>
            <template v-slot:[`item.active`]="{ item }">
              <v-chip :color="setColor(item.active)" text-color="white" dark small>{{ setText(item.active) }}</v-chip>
            </template>
            <template v-slot:[`header.actions`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon color="carbon" class="mr-2" @click="editDashboard(item)">mdi-pencil</v-icon>
              <v-icon color="carbon" class="mr-2" @click="deleteDasboard(item)">mdi-delete</v-icon>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
    <v-container class="text-center mt-15" fluid v-if="loadingDataTable">
      <v-row>
        <v-col cols="12">
          <v-progress-circular color="carbon" indeterminate size="64"></v-progress-circular>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import EventBus from '../../shared/EventBus'
import Overlay from '../../components/Overlay'
import DialogDeleteDashboard from '../../components/Business_Intelligence/DialogDeleteDashboard'
export default {
  name: 'DashboardGrid',
  components: { Overlay, DialogDeleteDashboard },
  data: () => ({
    dashboardId: null,
    loadingDataTable: true,
    criteriaSearch: null,
    headerProps: {
      sortByText: 'Ordenar por',
    },
    headers: [
      { text: 'Nombre', value: 'name', sortable: true, filterable: true },
      { text: 'Descripción', value: 'description', sortable: true, filterable: true },
      { text: 'Es Slide', value: 'isSlide', sortable: true, filterable: true },
      { text: 'Es Row Data', value: 'isRowData', sortable: true, filterable: true },
      { text: 'En carrusel', value: 'isInCarousel', sortable: true, filterable: true },
      { text: 'Activo', value: 'active', sortable: true, filterable: true },
      { text: 'Acciones', value: 'actions', sortable: false, align: 'center' },
    ],
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
      dashboards: 'dashboards/dashboards',
      edit: 'dashboards/edit',
    }),
  },
  methods: {
    async loadDataTable() {
      try {
        await this.$store.dispatch('dashboards/get')
        this.loadingDataTable = false
      } catch (error) {
        this.loadingDataTable = false
      }
    },
    create() {
      this.$store.dispatch('dashboards/openForm')
    },
    backtoProcesses() {
      this.$router.push({ name: 'Procesos' })
    },
    setColor(value) {
      return value ? 'green' : 'red'
    },
    setText(value) {
      return value ? 'SI' : 'NO'
    },
    async editDashboard(dashboard) {
      this.$store.dispatch('dashboards/edit', { edit: true })
      EventBus.$emit('fillDashboardData', dashboard)
      this.create()
    },
    deleteDasboard(dashboard) {
      this.dashboardId = dashboard.id
      this.$store.dispatch('dashboards/openDialogDelete')
    },
  },
  created() {
    this.loadDataTable()
  },
}
</script>

<style scoped></style>
