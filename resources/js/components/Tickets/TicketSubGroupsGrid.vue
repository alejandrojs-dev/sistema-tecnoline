<template>
  <div>
    <TicketSubGroupForm />
    <DialogDeleteConfirmTS :id="ticketSubGroupId" />
    <v-container fluid>
      <v-row>
        <v-col cols="12">
          <v-btn color="blue" dark small @click="create()">
            Nuevo Subgrupo
            <v-icon dark right>mdi-plus-circle</v-icon>
          </v-btn>
        </v-col>
        <v-col cols="12">
          <v-text-field v-model="searchCriteria" append-icon="mdi-magnify" label="Buscar" single-line hide-details class="mb-2"></v-text-field>
        </v-col>
        <v-col cols="12">
          <v-data-table
            id="dtTicketSubGroups"
            :headers="headers"
            :items="ticketSubGroups"
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
            <template v-slot:[`header.subGroupName`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.serviceLevel`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.numberAuth`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.groupName`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.ticketType`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.active`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`header.actions`]="{ header }">{{ header.text.toUpperCase() }}</template>
            <template v-slot:[`item.active`]="{ item }">
              <v-chip :color="setColorActive(item.active)" dark>{{ setActiveText(item.active) }}</v-chip>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon color="carbon" class="mr-2" @click="fillForm(item.id, item.ticketTypeId)">mdi-pencil</v-icon>
              <v-icon color="carbon" class="mr-2" @click="openDialogDelete(item)">mdi-delete</v-icon>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import TicketSubGroupForm from '../../components/Tickets/TicketSubGroupForm'
import DialogDeleteConfirmTS from '../../components/Tickets/DialogDeleteConfirmTS'
import EventBus from '../../shared/EventBus'
export default {
  name: 'TicketSubGroupsGrid',
  components: { TicketSubGroupForm, DialogDeleteConfirmTS },
  data: () => ({
    ticketSubGroupId: null,
    searchCriteria: '',
    overlay: true,
    loadingDataTable: true,
    headerProps: {
      sortByText: 'Ordenar por',
    },
    headers: [
      { text: 'Nombre', align: 'start', value: 'subGroupName' },
      { text: 'Service level', value: 'serviceLevel', sortable: false, align: 'center' },
      {
        text: 'Número autorizaciones',
        value: 'numberAuth',
        sortable: false,
        align: 'center',
      },
      { text: 'Grupo de ticket', value: 'groupName', align: 'center' },
      { text: 'Tipo de ticket', value: 'ticketType', align: 'center' },
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
      ticketSubGroups: 'ticketSubGroups/ticketSubGroups',
    }),
  },
  methods: {
    setColorActive(value) {
      return value ? 'green' : 'red'
    },
    setActiveText(value) {
      return value ? 'SI' : 'NO'
    },
    async fillForm(id, ticketTypeId) {
      this.$store.dispatch('ticketSubGroups/edit', { edit: true })

      const response = await this.$store.dispatch('ticketSubGroups/getById', { id, ticketTypeId })

      if (response.ok) {
        const ticketSubGrop = response.data

        EventBus.$emit('fillFormts', ticketSubGrop)
        this.create()
      }
    },
    openDialogDelete(item) {
      this.ticketSubGroupId = item.id
      this.$store.dispatch('ticketSubGroups/openDialogDelete')
    },
    create() {
      this.$store.dispatch('ticketSubGroups/openForm')
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
