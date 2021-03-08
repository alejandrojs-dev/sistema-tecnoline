<template>
  <div>
    <TicketGroupForm />
    <DialogDeleteConfirmTG :id="ticketGroupId" />
    <v-container fluid>
      <v-row>
        <v-col cols="12">
          <v-btn color="blue" dark small @click="create()">
            Nuevo Grupo
            <v-icon dark right>mdi-plus-circle</v-icon>
          </v-btn>
        </v-col>
        <v-col cols="12">
          <v-text-field v-model="searchCriteria" append-icon="mdi-magnify" label="Buscar" single-line hide-details class="mb-2"></v-text-field>
        </v-col>
        <v-col cols="12">
          <v-data-table
            :headers="headers"
            :items="ticketGroups"
            :items-per-page="10"
            :footer-props="footerProps"
            :search="searchCriteria"
            :header-props="headerProps"
            item-key="id"
            loading-text="Cargando... Espere un momento"
            no-data-text="No existen datos"
            no-results-text="No se encontraron resultados"
            class="elevation-1"
          >
            <template v-slot:[`header.name`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.description`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.active`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.actions`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`item.active`]="{ item }">
              <v-chip :color="setColorActive(item.active)" dark>{{ setActiveText(item.active) }}</v-chip>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon color="carbon" class="mr-2" @click.stop="fillForm(item)">mdi-pencil</v-icon>
              <v-icon color="carbon" class="mr-2" @click.stop="openDialogDelete(item.id)">mdi-delete</v-icon>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import TicketGroupForm from '../../components/Tickets/TicketGroupForm'
import DialogDeleteConfirmTG from '../../components/Tickets/DialogDeleteConfirmTG'
import EventBus from '../../shared/EventBus'
export default {
  name: 'TicketGroupsGrid',
  components: { TicketGroupForm, DialogDeleteConfirmTG },
  data: () => ({
    ticketGroupId: null,
    searchCriteria: '',
    loadingDataTable: true,
    headerProps: {
      sortByText: 'Ordenar por',
    },
    headers: [
      { text: 'Nombre', value: 'name', align: 'start' },
      { text: 'Description', value: 'description', sortable: false },
      { text: 'Activo', value: 'active', filterable: false },
      { text: 'Acciones', value: 'actions', sortable: false },
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
      ticketGroups: 'ticketGroups/ticketGroups',
    }),
  },
  methods: {
    setColorActive(value) {
      return value ? 'green' : 'red'
    },
    setActiveText(value) {
      return value ? 'SI' : 'NO'
    },
    fillForm(ticketGroup) {
      this.$store.dispatch('ticketGroups/edit', { edit: true })
      EventBus.$emit('fillFormtg', ticketGroup)
      this.create()
    },
    openDialogDelete(id) {
      this.ticketGroupId = id
      this.$store.dispatch('ticketGroups/openDialogDelete')
    },
    create() {
      this.$store.dispatch('ticketGroups/openForm')
    },
  },
  created() {},
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
