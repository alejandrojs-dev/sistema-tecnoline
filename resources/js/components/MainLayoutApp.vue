<template>
  <div>
    <LoginForm />
    <Overlay :overlay="overlay" />
    <DialogTicketDetails v-if="openClickNotification" />
    <v-navigation-drawer width="340" v-model="drawer" app v-if="isUserLogged">
      <v-list nav dense>
        <v-list-item-content>
          <img id="imgLogoMenu" src="../../assets/nuevolg.png" width="" />
        </v-list-item-content>
        <v-list-item two-line>
          <v-list-item-content :style="{ 'text-align': 'center' }">
            <v-list-item-title>Tecnoline</v-list-item-title>
            <v-list-item-subtitle>Departamento de desarrollo</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
        <v-list>
          <v-list-item @click="redirectHome()">
            <v-list-item-icon>
              <v-icon color="carbon">mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Inicio</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
        <v-list-group v-for="module in userMenu" :key="module.id" no-action>
          <template v-slot:activator>
            <v-list-item-icon>
              <v-icon color="carbon">{{ module.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{ module.name }}</v-list-item-title>
            </v-list-item-content>
          </template>
          <v-list-item v-for="submodule in module.submodules" :key="submodule.id" :to="{ path: submodule.path }" link>
            <v-list-item-icon>
              <v-icon color="carbon" class="mt-1">{{ submodule.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{ submodule.name }}</v-list-item-title>
            </v-list-item-content>
            <v-list-item-action v-if="submodule.isTray && hayTicketsTray">
              <v-chip color="red" class="white--text" small>{{ ticketsTrayCount }}</v-chip>
            </v-list-item-action>
            <v-list-item-action v-if="submodule.isAuthTray && hayAuthTickets">
              <v-chip color="red" class="white--text" small>{{ authTicketsCount }}</v-chip>
            </v-list-item-action>
          </v-list-item>
        </v-list-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar color="white" app>
      <v-app-bar-nav-icon @click="drawer = !drawer" v-if="isUserLogged"></v-app-bar-nav-icon>
      <img id="logo" src="../../assets/logo.png" />
      <v-toolbar-title>Tecnoline</v-toolbar-title>

      <v-spacer></v-spacer>

      <div v-if="userAccessTickets">
        <v-menu transition="slide-y-transition" max-width="500" :close-on-click="true" offset-y left v-if="hayNotifications">
          <template v-slot:activator="{ on, attrs }">
            <v-btn class="mr-2" icon color="carbon" v-bind="attrs" v-on="on">
              <v-badge overlap color="red" :content="notificationsCount" :value="notificationsCount">
                <v-icon>mdi-bell</v-icon>
              </v-badge>
            </v-btn>
          </template>
          <v-list two-line>
            <v-subheader>Mis notificaciones</v-subheader>
            <v-virtual-scroll :items="notifications" :item-height="90" height="300" width="500">
              <template v-slot:default="{ item }">
                <v-list-item
                  :class="item.backGroundColor"
                  :key="item.id"
                  three-line
                  dense
                  @click="clickNotification(item.id, item.ticketGroupUserIds, item.reassigned)"
                >
                  <v-list-item-avatar color="carbon" size="62" v-if="!item.userImageProfile">
                    <span class="white--text headline">{{ userInitials }}</span>
                  </v-list-item-avatar>
                  <v-list-item-avatar size="62" v-else>
                    <v-img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John"></v-img>
                  </v-list-item-avatar>

                  <v-list-item-content>
                    <v-list-item-title>{{ item.subGroupName }}({{ item.groupName }})</v-list-item-title>
                    <v-list-item-subtitle
                      ><strong>{{ item.username }}</strong> ha creado un nuevo ticket: "{{ item.description }}"</v-list-item-subtitle
                    >
                    <v-list-item-subtitle class="text-caption"
                      >{{ formatNotificationDate(item.createdDate) }}
                      <v-chip class="ml-1" :color="item.statusColor" text-color="white" dark small>{{ item.status }}</v-chip>
                    </v-list-item-subtitle>
                  </v-list-item-content>
                  <v-list-item-action>
                    <v-chip class="ma-2 white--text headline" color="grey">
                      {{ item.type }}
                    </v-chip>
                  </v-list-item-action>
                </v-list-item>
              </template>
            </v-virtual-scroll>
          </v-list>
        </v-menu>
        <v-menu offset-y left transition="slide-y-transition" max-width="500" :close-on-click="true" v-else>
          <template v-slot:activator="{ on, attrs }">
            <v-btn class="mr-2" icon text color="carbon" v-bind="attrs" v-on="on">
              <v-icon>mdi-bell</v-icon>
            </v-btn>
          </template>
          <v-list two-line>
            <v-list-item>
              <v-list-item-content>
                <v-icon>mdi-bell-off</v-icon>
                <v-list-item-subtitle class="text-center mt-2">No tienes notificaciones nuevas</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>

      <v-avatar class="mr-2" color="carbon" size="48" v-if="isUserLogged">
        <span class="white--text headline">{{ userInitials }}</span>
      </v-avatar>

      <v-menu offset-y left v-if="user" :close-on-content-click="false" transition="slide-y-transition">
        <template v-slot:activator="{ on, attrs }">
          <v-btn v-bind="attrs" v-on="on" icon color="carbon">
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>
        <v-list dense>
          <v-list-item>
            <v-list-item-avatar color="carbon" size="48">
              <span class="white--text headline">{{ userInitials }}</span>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title> {{ user.username }}</v-list-item-title>
              <v-list-item-subtitle>{{ user.department.name }}</v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action>
              <img id="logo" src="../../assets/logo.png" />
            </v-list-item-action>
          </v-list-item>
        </v-list>
        <v-divider></v-divider>
        <v-list dense>
          <v-list-item @click="getProfile()">
            <v-list-item-icon>
              <v-icon color="carbon">mdi-face-profile</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Mi perfil</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click="logout()">
            <v-list-item-icon>
              <v-icon color="carbon">mdi-logout</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Cerrar sesión</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>
      <v-btn color="carbon" text large @click="openLoginForm()" v-else>
        Iniciar sesión
        <v-icon right>mdi-login</v-icon>
      </v-btn>
    </v-app-bar>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { HumanDateFormatter } from '../shared/HumanDateFormatter'
import socketClient from '../shared/SocketClient'
import LoginForm from '../components/LoginForm'
import Overlay from '../components/Overlay'
import DialogTicketDetails from '../components/Tickets/DialogTicketDetails'
export default {
  name: 'MainLayoutApp',
  components: { LoginForm, Overlay, DialogTicketDetails },
  data: () => ({
    drawer: false,
    overlay: false,
    ticket: null,
    users: [],
    ticketStatus: null,
    openClickNotification: false,
  }),
  computed: {
    ...mapGetters({
      dataUser: 'auth/dataUser',
      userInitials: 'users/userInitials',
      notifications: 'users/notifications',
      notificationsCount: 'users/notificationsCount',
      authTicketsCount: 'users/authTicketsCount',
      ticketsTrayCount: 'users/ticketsTrayCount',
      idleTimerId: 'actions/idleTimerId',
    }),
    userMenu() {
      return this.dataUser.user.modules
    },
    user() {
      return this.dataUser.user
    },
    isUserLogged() {
      if (this.dataUser.token.value == null) return false
      return true
    },
    userAccessTickets() {
      if (this.isUserLogged) {
        if (this.dataUser.user.hasPermissionTickets) return true
        else false
      } else return false
    },
    currentPathRoute() {
      return this.$route.path
    },
    hayNotifications() {
      return this.notifications.length > 0
    },
    hayTicketsTray() {
      return this.ticketsTrayCount > 0
    },
    hayAuthTickets() {
      return this.authTicketsCount > 0
    },
  },
  methods: {
    goHome() {
      this.$router.push({ path: '/inicio' })
    },
    formatNotificationDate(date) {
      const humanDateFormatter = new HumanDateFormatter()
      return humanDateFormatter.formatToHumanDialect(date)
    },
    setUserInitials(username) {
      this.$store.dispatch('users/setUserInitials', { username })
    },
    openLoginForm() {
      this.$store.dispatch('login/openLoginForm')
    },
    resetAppState() {
      this.$store.dispatch('auth/resetState')
      this.$store.dispatch('login/resetState')
      this.$store.dispatch('users/resetState')
      this.$store.dispatch('actions/resetState')
      this.$store.dispatch('ticketGroups/resetState')
      this.$store.dispatch('ticketTypes/resetState')
      this.$store.dispatch('processes/resetState')
    },
    redirectLogin() {
      if (this.currentPathRoute != '/inicio') {
        this.$router.push({ name: 'Home' })
      }
    },
    clearIdleTimer(idleTimerId) {
      window.clearInterval(idleTimerId)
    },
    getProfile() {
      console.log('Mi perfil')
    },
    async clickNotification(id, ticketGroupUserIds, reassigned) {
      try {
        const isViewed = true
        if (reassigned) {
          this.ticketStatus = 5
        }

        const payload = {
          id,
          isViewed,
          ticketGroupUserIds,
        }

        const response = await this.$store.dispatch('tickets/clickOnNotification', payload)

        const { ticket } = response

        if (response.ok) {
          this.$store.dispatch('tickets/setTicketDetails', ticket)
          this.openClickNotification = true
          this.$store.dispatch('tickets/openDialogDetails')
        }
      } catch (error) {}
    },
    async logout() {
      try {
        this.overlay = true
        const response = await this.$store.dispatch('auth/logout')
        if (response.ok) {
          this.overlay = false
          this.clearIdleTimer()
          this.redirectLogin()
          this.resetAppState()
        }
      } catch (error) {}
    },
    async getUserNotifications() {
      try {
        if (this.user) {
          const { id } = this.user
          await this.$store.dispatch('users/getUserNotifications', { userId: id })
        }
      } catch (error) {}
    },
  },
  created() {
    if (this.isUserLogged) {
      if (this.userAccessTickets) {
        this.getUserNotifications()
      }
      this.setUserInitials(this.user.username)
    }

    socketClient.saveTSTicketOn()
    socketClient.saveTACTicketOn()
    socketClient.clickNotificationOn()
    socketClient.refreshAllUserNotificationsOn()
    socketClient.ticketCompletelyAuthorizedOn()
  },
}
</script>

<style scoped>
img#imgLogoMenu {
  width: 50px;
}
#logo {
  width: 36px;
  height: 36px;
  margin: 10px;
  margin-right: 10px;
}
span.v-badge.v-badge--overlap.theme--light {
  margin-top: 7px;
}
.isViewed {
  background-color: #ffffff;
}
.isNotViewed {
  background-color: #e3f2fd;
}
</style>
