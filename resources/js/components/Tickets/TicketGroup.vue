<template>
  <v-col cols="4" sm="6" md="6" lg="4" xl="3">
    <v-card class="mx-auto" max-width="344" width="344">
      <v-card-title class="justify-center">
        <v-avatar color="carbon" size="80">
          <v-icon dark x-large>{{ icon }}</v-icon>
        </v-avatar>
      </v-card-title>
      <v-card-subtitle class="pb-0 mt-1 mb-2 text-center">{{ description }}</v-card-subtitle>
      <v-divider></v-divider>
      <v-list nav v-if="haySubGroups" dense>
        <v-menu transition="scale-transition" :close-on-click="true" :offset-y="true" :offset-x="false">
          <template v-slot:activator="{ on, attrs }">
            <v-list-item v-bind="attrs" v-on="on">
              <v-list-item-icon>
                <v-icon color="carbon">mdi-format-list-bulleted-square</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Tipos de ticket</v-list-item-title>
              </v-list-item-content>
              <v-list-item-icon>
                <v-icon color="carbon">mdi-menu-down</v-icon>
              </v-list-item-icon>
            </v-list-item>
          </template>
          <v-list dense>
            <v-list-item v-for="ticketSubGroup in ticketSubGroups" :key="ticketSubGroup.id" @click="openForm(ticketSubGroup)">
              <v-list-item-icon>
                <v-icon v-if="ticketSubGroup.icon" color="carbon">{{ ticketSubGroup.icon }}</v-icon>
                <v-icon v-else color="carbon">mdi-cog</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>{{ ticketSubGroup.name }}</v-list-item-title>
              </v-list-item-content>
              <v-chip class="ma-2" color="carbon" text-color="white" v-if="ticketSubGroup.ticketType.type === 'TAC'">
                {{ ticketSubGroup.ticketType.type }}
              </v-chip>
              <v-chip class="ma-2" color="grey" text-color="white" v-if="ticketSubGroup.ticketType.type === 'TS'">
                {{ ticketSubGroup.ticketType.type }}
              </v-chip>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-list>
      <v-list nav dense v-else>
        <v-list-item style="padding:2.5px">
          <v-list-item-content>
            <v-list-item-title class="text-center opacity">Sin tipos de tickets</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-card>
  </v-col>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'TicketGroupList',
  props: ['name', 'description', 'icon', 'ticketSubGroups', 'ticketGroupId', 'users'],
  data: () => ({
    overlay: true,
    ticketGroupUserIds: [],
  }),
  computed: {
    ...mapGetters({
      dataUser: 'auth/dataUser',
    }),
    user() {
      return this.dataUser.user
    },
    haySubGroups() {
      return this.ticketSubGroups.length
    },
  },
  methods: {
    openForm(ticketSubGroup) {
      const sharedTicketModalData = {}

      sharedTicketModalData.ticketGroupId = this.ticketGroupId
      sharedTicketModalData.ticketSubGroupId = ticketSubGroup.id
      sharedTicketModalData.ticketTypeId = ticketSubGroup.ticketType.id
      sharedTicketModalData.type = ticketSubGroup.ticketType.type
      sharedTicketModalData.ticketGroupName = this.name
      sharedTicketModalData.ticketSubGroupName = ticketSubGroup.name
      sharedTicketModalData.userTicketCreate = this.user.id
      sharedTicketModalData.ticketGroupUserIds = this.users.map(u => u.user_id)
      sharedTicketModalData.usersWhoAuthorize = ticketSubGroup.usersWhoAuthorize.map(u => u.user_id)

      this.$store.dispatch('tickets/setSharedTicketModalData', { sharedTicketModalData })

      // objSharedTicketModalData.ticketGroupId =
      // const userTicketCreate = this.user.id
      // console.log(type)
      // this.$store.dispatch('tickets/setSharedTicketModalData', {
      //   ticketGroupId,
      //   ticketSubGroupId,
      //   ticketTypeId,
      //   type,
      //   ticketGroupName,
      //   ticketSubGroupName,
      //   userTicketCreate,
      //   ticketGroupUserIds,
      //   usersWhoAuthorize,
      // })
      if (ticketSubGroup.ticketType.type == 'TAC') {
        this.$store.dispatch('tickets/openTACTicketForm')
      }

      if (ticketSubGroup.ticketType.type == 'TS') {
        this.$store.dispatch('tickets/openTSTicketForm')
      }
    },
  },
}
</script>

<style scoped>
.opacity {
  opacity: 0.3;
}
</style>
