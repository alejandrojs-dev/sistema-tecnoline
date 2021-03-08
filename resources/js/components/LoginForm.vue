<template>
  <div>
    <v-row justify="center">
      <v-dialog persistent max-width="600" :value="openLoginForm">
        <v-card>
          <v-card-title class="headline justify-center">
            Iniciar sesión
          </v-card-title>
          <v-divider class="mx-3 mb-3"></v-divider>
          <Alert
            v-if="error"
            :alertType="alertInfo.type"
            :alertMessage="alertInfo.message"
            :alertIcon="alertInfo.icon"
            :alertErrors="alertInfo.errors"
          />
          <v-card-text>
            <div class="smallDiv">
              <small class="smallColor">*Campos obligatorios</small>
            </div>
            <v-form id="frmLogin" @submit.prevent="login()">
              <v-text-field
                label="Nombre de usuario*"
                color="carbon"
                prepend-icon="mdi-account-circle"
                clear-icon="mdi-close-circle"
                autocomplete="off"
                :disabled="disableUsername"
                :error-messages="usernameErrors"
                required
                outlined
                clearable
                dense
                @keyup.enter="login()"
                v-model.trim="username"
              ></v-text-field>
              <v-text-field
                label="Contraseña*"
                color="carbon"
                prepend-icon="mdi-lock"
                clear-icon="mdi-close-circle"
                autocomplete="off"
                :error-messages="passwordErrors"
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show ? 'text' : 'password'"
                :disabled="disablePassword"
                required
                outlined
                dense
                clearable
                @click:append="show = !show"
                @keyup.enter="login()"
                v-model.trim="password"
              ></v-text-field>
              <v-btn type="submit" color="carbon" class="white--text" block :loading="loading" :disabled="loading"
                >Iniciar sesión
                <template v-slot:loader>
                  <span>
                    Espere...
                  </span>
                </template>
                <v-icon right dark>mdi-login-variant</v-icon>
              </v-btn>
            </v-form>
          </v-card-text>
          <v-progress-linear color="primary" indeterminate height="6" v-if="loading"></v-progress-linear>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { required } from 'vuelidate/lib/validators'
import { IdleTimer } from '../shared/IdleTimer'
import socketClient from '../shared/SocketClient'
import Login from '../models/Login.model'
import Alert from '../components/Alert'
import Toastr from '../shared/Toastr'
export default {
  name: 'LoginForm',
  components: { Alert },
  data: () => ({
    username: '',
    password: '',
    show: false,
    valid: true,
    loading: false,
    disableUsername: false,
    disablePassword: false,
  }),
  validations: {
    username: { required },
    password: { required },
  },
  computed: {
    ...mapGetters({
      openLoginForm: 'login/openLoginForm',
      error: 'actions/error',
      alertInfo: 'actions/alertInfo',
      dataUser: 'auth/dataUser',
    }),
    usernameErrors() {
      const errors = []
      if (!this.$v.username.$dirty) return errors
      if (!this.$v.username.required && errors.push('El campo nombre del usuario es obligatorio')) return errors
    },
    passwordErrors() {
      const errors = []
      if (!this.$v.password.$dirty) return errors
      if (!this.$v.password.required && errors.push('El campo contraseña es obligatorio')) return errors
    },
  },
  methods: {
    cleanForm() {
      this.username = ''
      this.password = ''
      this.$v.$reset()
    },
    setUserInitials(username) {
      this.$store.dispatch('users/setUserInitials', { username })
    },
    closeLoginForm() {
      this.$store.dispatch('login/openLoginForm')
    },
    setError(payload) {
      this.$store.dispatch('actions/setError', payload)
    },
    setTokenIsValid(isValid) {
      this.$store.dispatch('auth/setTokenIsValid', { isValid })
    },
    disableFormFields() {
      this.disableUsername = true
      this.disablePassword = true
    },
    enableFormFields() {
      this.disableUsername = false
      this.disablePassword = false
    },
    getUserNotifications(userId) {
      this.$store.dispatch('users/getUserNotifications', { userId })
    },
    userConnectSockets(userId) {
      socketClient.userConnectSocketsEmit(userId)
      socketClient.userConnectSocketsOn()
    },
    showToast(message) {
      const toastr = new Toastr(message, 'Bienvenido', 3000, 'toast-bottom-center', 0, true, 'fadeOut', 'fadeIn', 'fadeOut', 1000, 'toastr', null)
      toastr.launch()
    },
    async getDashboardsByUserDep(departmentId) {
      await this.$store.dispatch('dashboards/getByDepartment', { departmentId })
    },
    async getTicketGroups() {
      await this.$store.dispatch('ticketGroups/getWithRelationships')
    },
    async login() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.disableFormFields()
        if (this.error) this.setError({ error: false })
        try {
          const login = new Login(this.username, this.password)
          const response = await this.$store.dispatch('auth/login', login)
          if (response.ok) {
            const {
              user: {
                id,
                username,
                hasPermissionTickets,
                department: { id_department },
              },
              token: { isValid },
              message,
            } = response

            this.loading = false
            this.cleanForm()
            this.enableFormFields()
            this.closeLoginForm()
            this.setError({ error: false })
            this.setUserInitials(username)

            if (hasPermissionTickets) {
              this.getUserNotifications(id)
            }

            this.userConnectSockets(id)
            this.showToast(message)
            this.setTokenIsValid(isValid)

            this.getDashboardsByUserDep(id_department)
            this.getTicketGroups()

            const idleTimer = new IdleTimer(document)
            idleTimer.init()
          }
        } catch (error) {
          this.loading = false
          this.enableFormFields()
        }
      }
    },
  },
  created() {},
}
</script>

<style>
.smallColor {
  color: #ff5252;
}
.smallDiv {
  margin-top: 3px;
  margin-bottom: 10px;
}
</style>
