<template>
  <div>
    <Overlay :overlay="overlay" />
    <v-sheet class="pa-4 mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid v-if="!overlay">
        <v-row>
          <v-col cols="6">
            <div class="mt-5">
              <span class="panelTitle">Herramientas</span>
            </div>
          </v-col>
        </v-row>
        <v-divider></v-divider>
        <v-row>
          <v-col cols="12">
            <v-tabs color="carbon" icons-and-text v-model="tab">
              <v-tab>Grupos de ticket <v-icon>mdi-account-group</v-icon></v-tab>
              <v-tab>Subgrupos de ticket <v-icon>mdi-ticket</v-icon></v-tab>
            </v-tabs>
            <v-tabs-items v-model="tab">
              <v-tab-item>
                <TicketGroupsGrid />
              </v-tab-item>
              <v-tab-item>
                <TicketSubGroupsGrid />
              </v-tab-item>
            </v-tabs-items>
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </div>
</template>

<script>
import TicketGroupsGrid from '../../components/Tickets/TicketGroupsGrid'
import TicketSubGroupsGrid from '../../components/Tickets/TicketSubGroupsGrid'
import TicketSubGroupForm from '../../components/Tickets/TicketSubGroupForm.vue'
import TicketGroupForm from '../../components/Tickets/TicketGroupForm.vue'
import Overlay from '../../components/Overlay'
export default {
  name: 'TicketTools',
  title: 'Herramientas | Tecnoline 2.0',
  components: { TicketGroupForm, TicketSubGroupForm, TicketGroupsGrid, TicketSubGroupsGrid, Overlay },
  data: () => ({
    tab: null,
    overlay: true,
  }),
  computed: {},
  methods: {
    async loadData() {
      try {
        await this.$store.dispatch('ticketGroups/get')
        await this.$store.dispatch('ticketSubGroups/get')
        await this.$store.dispatch('ticketTypes/get')
        await this.$store.dispatch('users/getFromApi')
        this.overlay = false
      } catch (error) {}
    },
  },
  created() {
    this.loadData()
  },
}
</script>

<style scoped>
.panelTitle {
  font-size: 25px;
  margin-top: 15px;
}
</style>
