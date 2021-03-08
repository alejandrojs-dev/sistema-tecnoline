<template>
  <div>
    <v-app style="background-color: #F7F7F7;">
      <MainLayoutApp />
      <v-main>
        <v-container fluid>
          <router-view></router-view>
        </v-container>
      </v-main>
    </v-app>
  </div>
</template>

<script>
import MainLayoutApp from './components/MainLayoutApp'
import { mapGetters } from 'vuex'
import socketClient from './shared/SocketClient'
export default {
  name: 'App',
  components: { MainLayoutApp },
  computed: {
    ...mapGetters({
      dataUser: 'auth/dataUser',
    }),
  },
  methods: {
    async getTicketGroups() {
      await this.$store.dispatch('ticketGroups/getWithRelationships')
    },
  },
  created() {
    if (this.dataUser.user) {
      socketClient.userConnectSocketsRefreshPageEmit(this.dataUser.user.id)
    }
  },
}
</script>

<style></style>
