<template>
  <div>
    <Overlay :overlay="overlay" />
    <TSTicketForm />
    <TACTicketForm />
    <v-sheet class="mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid>
        <v-row>
          <TicketGroup
            v-for="ticketGroup in ticketGroupsR"
            :key="ticketGroup.id"
            :name="ticketGroup.name"
            :description="ticketGroup.description"
            :icon="ticketGroup.icon"
            :ticketSubGroups="ticketGroup.ticketSubGroups"
            :ticketGroupId="ticketGroup.id"
            :users="ticketGroup.users"
          />
        </v-row>
      </v-container>
    </v-sheet>
  </div>
</template>

<script>
import TicketGroup from '../../components/Tickets/TicketGroup'
import TSTicketForm from '../../components/Tickets/TSTicketForm'
import TACTicketForm from '../../components/Tickets/TACTicketForm'
import Overlay from '../../components/Overlay'
import { mapGetters } from 'vuex'
export default {
  name: 'CrearTicket',
  title: 'Crear ticket | Tecnoline 2.0',
  data: () => ({
    overlay: true,
  }),
  computed: {
    ...mapGetters({
      ticketGroupsR: 'ticketGroups/ticketGroupsR',
    }),
  },
  components: { TicketGroup, TSTicketForm, TACTicketForm, Overlay },
  methods: {
    async getTicketGroups() {
      await this.$store.dispatch('ticketGroups/getWithRelationships')
      this.overlay = false
    },
  },
  mounted() {
    this.getTicketGroups()
  },
}
</script>

<style></style>
