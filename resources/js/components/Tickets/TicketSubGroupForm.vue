<template>
  <div>
    <v-dialog persistent max-width="600" v-model="openForm" :retain-focus="false">
      <v-card>
        <v-card-title class="headline">Crear subgrupo</v-card-title>
        <v-divider class="mx-3 mb-3" :inset="false"></v-divider>
        <Alert
          v-if="error"
          :alertType="alertInfoData.type"
          :alertMessage="alertInfoData.message"
          :alertIcon="alertInfoData.icon"
          :alertErrors="alertInfoData.errors"
        />
        <v-form id="frmTicketSubGroup" @submit.prevent="save()">
          <v-card-text>
            <div class="smallDiv"><small class="smallColor">*Campos obligatorios</small></div>

            <v-select
              v-model="ticketGroupId"
              :items="ticketGroups"
              label="Grupo de ticket*"
              outlined
              dense
              prepend-icon="mdi-account-group"
              item-value="id"
              item-text="description"
              :error-messages="ticketGroupIdErrors"
              :disabled="disableSelectTicketGroup"
            >
              <template v-slot:item="{ item }">
                <v-list-item-content>
                  <v-list-item-title>{{ item.description }} ({{ item.name }})</v-list-item-title>
                </v-list-item-content>
              </template>
            </v-select>
            <v-select
              v-model="ticketTypeId"
              :items="ticketTypes"
              label="Tipo de ticket*"
              outlined
              dense
              prepend-icon="mdi-tag"
              item-value="id"
              item-text="type"
              :error-messages="ticketTypeIdErrors"
              :disabled="disableSelectTicketType"
              @change="showComponents($event)"
            >
              <template v-slot:item="{ item }">
                <v-list-item-content>
                  <v-list-item-title>{{ item.type }}</v-list-item-title>
                </v-list-item-content>
              </template>
            </v-select>
            <v-text-field
              v-model="name"
              dense
              label="Nombre de subgrupo*"
              outlined
              autocomplete="off"
              clearable
              clear-icon="mdi-close-circle"
              prepend-icon="mdi-clipboard-edit"
              :error-messages="nameErrors"
              :disabled="disableName"
            ></v-text-field>
            <v-text-field
              v-model="serviceLevel"
              dense
              outlined
              clearable
              autocomplete="off"
              label="Service level*"
              clear-icon="mdi-close-circle"
              prepend-icon="mdi-room-service"
              :disabled="disableServiceLevel"
              :error-messages="serviceLevelErrors"
              @input="$v.serviceLevel.$touch()"
              @blur="$v.serviceLevel.$touch()"
            ></v-text-field>

            <v-text-field
              v-model="icon"
              dense
              outlined
              autocomplete="off"
              label="Icono identificador"
              clear-icon="mdi-close-circle"
              prepend-icon="mdi-information"
              :disabled="disableIcon"
            ></v-text-field>

            <v-text-field
              v-if="rNumberAuth"
              v-model="numberAuth"
              dense
              outlined
              clearable
              autocomplete="off"
              label="Número de autorizaciones*"
              clear-icon="mdi-close-circle"
              prepend-icon="mdi-check-bold"
              :disabled="disableNumberAuth"
              :error-messages="numberAuthErrors"
              @input="$v.numberAuth.$touch()"
              @blur="$v.numberAuth.$touch()"
            ></v-text-field>

            <v-autocomplete
              v-if="rUsersWhoAuthorize"
              v-model="usersWhoAuthorize"
              :disabled="disableAutocomplete"
              :items="users"
              :error-messages="usersWhoAuthorizeErrors"
              prepend-icon="mdi-account-group"
              item-value="user_id"
              item-text="username"
              color="primary"
              label="Usuarios quienes van a autorizar*"
              multiple
              chips
              small-chips
              outlined
              dense
            >
              <template v-slot:selection="data">
                <v-chip v-bind="data.attrs" :input-value="data.selected" @click:close="deleteUser(data.item)" close>
                  <v-avatar left size="10">
                    <v-img :src="require('../../../assets/logo.png')"></v-img>
                  </v-avatar>
                  {{ data.item.username }}
                </v-chip>
              </template>
              <template v-slot:item="data">
                <template v-if="typeof data.item !== 'object'">
                  <v-list-item-content>{{ data.item }}</v-list-item-content>
                </template>
                <template v-else>
                  <v-list-item-avatar size="30">
                    <v-img :src="require('../../../assets/logo.png')"></v-img>
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{ data.item.username }}</v-list-item-title>
                  </v-list-item-content>
                </template>
              </template>
            </v-autocomplete>

            <v-row>
              <v-switch class="mx-2" label="Activo" v-model="active" color="primary" :disabled="disableActive" flat></v-switch>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red" class="white--text" @click="closeForm()" :disabled="loading">
              Cancelar
              <v-icon right dark>mdi-close-circle</v-icon>
            </v-btn>
            <v-btn type="submit" color="green" class="white--text" :disabled="loading" :loading="loading">
              Guardar
              <template v-slot:loader>
                <span>Espere...</span>
              </template>
              <v-icon right dark>mdi-check-circle</v-icon>
            </v-btn>
          </v-card-actions>
        </v-form>
        <v-progress-linear color="primary" indeterminate height="6" v-if="loading"></v-progress-linear>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { required, numeric, requiredIf } from 'vuelidate/lib/validators'
import TicketSubGroup from '../../models/TicketSubGroup.model'
import Alert from '../../components/Alert'
import EventBus from '../../shared/EventBus'
import Toastr from '../../shared/Toastr'
export default {
  name: 'TicketSubGroupForm',
  components: { Alert },
  data: () => ({
    ticketSubGroups: [],
    ticketSubGroupId: null,
    ticketGroupId: null,
    ticketTypeId: null,
    name: null,
    serviceLevel: null,
    numberAuth: null,
    icon: null,
    active: true,
    loading: false,
    disableSelectTicketGroup: false,
    disableSelectTicketType: false,
    disableAutocomplete: false,
    disableName: false,
    disableServiceLevel: false,
    disableNumberAuth: false,
    disableIcon: false,
    disableActive: false,
    usersWhoAuthorize: [],
    rUsersWhoAuthorize: false,
    rNumberAuth: false,
  }),
  validations: {
    ticketGroupId: { required },
    ticketTypeId: { required },
    name: { required },
    serviceLevel: { required, numeric },
    usersWhoAuthorize: {
      required: requiredIf(function() {
        return this.rUsersWhoAuthorize
      }),
    },
    numberAuth: {
      required: requiredIf(function() {
        return this.rNumberAuth
      }),
      numeric,
    },
  },
  computed: {
    ...mapGetters({
      openForm: 'ticketSubGroups/openForm',
      error: 'actions/error',
      alertInfoData: 'actions/alertInfo',
      dataUserGetter: 'auth/dataUser',
      ticketSubGroup: 'ticketSubGroups/ticketSubGroup',
      ticketTypes: 'ticketTypes/ticketTypes',
      ticketGroups: 'ticketGroups/ticketGroups',
      users: 'users/usersApi',
      edit: 'ticketSubGroups/edit',
    }),
    usersWhoAuthorizeErrors() {
      const errors = []
      if (!this.$v.usersWhoAuthorize.$dirty) return errors
      if (!this.$v.usersWhoAuthorize.required && errors.push('Debe seleccionar a los usuarios quienes van a autorizar el ticket')) return errors
    },
    ticketGroupIdErrors() {
      const errors = []
      if (!this.$v.ticketGroupId.$dirty) return errors
      if (!this.$v.ticketGroupId.required && errors.push('Debes seleccionar un ticket de grupo')) return errors
    },
    ticketTypeIdErrors() {
      const errors = []
      if (!this.$v.ticketTypeId.$dirty) return errors
      if (!this.$v.ticketTypeId.required && errors.push('Debes seleccionar un tipo de ticket')) return errors
    },
    nameErrors() {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      if (!this.$v.name.required && errors.push('El nombre es obligatorio')) return errors
    },
    serviceLevelErrors() {
      const errors = []
      if (!this.$v.serviceLevel.$dirty) return errors
      if (!this.$v.serviceLevel.required && errors.push('El service level es obligatorio')) return errors
      if (!this.$v.serviceLevel.numeric && errors.push('El service level debe ser un valor numérico')) return errors
    },
    numberAuthErrors() {
      const errors = []
      if (!this.$v.numberAuth.$dirty) return errors
      if (!this.$v.numberAuth.required && errors.push('El número de autorizaciones es obligatorio')) return errors
      if (!this.$v.numberAuth.numeric && errors.push('El número de autorizaciones debe ser un valor numérico')) return errors
    },
    loggedUserId() {
      return this.dataUserGetter.user.id
    },
  },
  methods: {
    fillFormEvent() {
      EventBus.$on('fillFormts', ticketSubGroup => {
        this.showComponents(ticketSubGroup.ticketType.id)
        this.fillForm(ticketSubGroup)
      })
    },
    cleanForm() {
      this.ticketSubGroupId = 0
      this.ticketGroupId = null
      this.ticketTypeId = null
      this.name = null
      this.serviceLevel = null
      this.numberAuth = null
      this.icon = null
      this.active = true
      this.usersWhoAuthorize = []
      this.rUsersWhoAuthorize = false
      this.rNumberAuth = false
      this.$v.$reset()
    },
    closeForm() {
      this.cleanForm()
      this.setError({ error: false })
      this.$store.dispatch('ticketSubGroups/openForm')
    },
    setError(payload) {
      this.$store.dispatch('actions/setError', payload)
    },
    fillForm(ticketSubGroup) {
      this.ticketSubGroupId = ticketSubGroup.id
      this.ticketGroupId = ticketSubGroup.ticketGroup.id
      this.ticketTypeId = ticketSubGroup.ticketType.id
      this.name = ticketSubGroup.name
      this.serviceLevel = ticketSubGroup.service_level
      this.numberAuth = ticketSubGroup.number_auth
      this.icon = ticketSubGroup.icon
      this.active = ticketSubGroup.active

      if (ticketSubGroup.usersWhoAuthorize) {
        this.usersWhoAuthorize = ticketSubGroup.usersWhoAuthorize.map(u => u.user_id)
      } else {
        this.usersWhoAuthorize = []
      }
    },

    disableFormFields() {
      this.disableSelectTicketGroup = true
      this.disableSelectTicketType = true
      this.disableName = true
      this.disableServiceLevel = true
      this.disableNumberAuth = true
      this.disableIcon = true
      this.disableActive = true
      this.disableAutocomplete = true
    },
    enableFormFields() {
      this.disableSelectTicketGroup = false
      this.disableSelectTicketType = false
      this.disableName = false
      this.disableServiceLevel = false
      this.disableNumberAuth = false
      this.disableIcon = false
      this.disableActive = false
      this.disableAutocomplete = false
    },
    showComponents(ticketTypeId) {
      if (ticketTypeId === 1) {
        this.rUsersWhoAuthorize = true
        this.rNumberAuth = true
      }
      if (ticketTypeId === 2) {
        this.rUsersWhoAuthorize = false
        this.rNumberAuth = false
      }
    },
    deleteUser(user) {
      const index = this.usersWhoAuthorize.indexOf(user.user_id)
      if (index > -1) {
        this.usersWhoAuthorize.splice(index, 1)
      }
    },
    showToast(message) {
      const toastr = new Toastr(message, '¡Éxito!', 3000, 'toast-bottom-center', 0, true, 'fadeOut', 'fadeIn', 'fadeOut', 1000, 'toastr', null)
      toastr.launch()
    },
    async save() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.disableFormFields()
        if (this.error) this.setError({ error: false })
        try {
          this.serviceLevel = Number(this.serviceLevel)
          this.numberAuth = Number(this.numberAuth)

          const ticketSubGroup = new TicketSubGroup(
            this.ticketGroupId,
            this.ticketTypeId,
            this.name,
            this.serviceLevel,
            this.numberAuth,
            this.icon,
            this.active,
          )

          let response = null

          if (this.edit) {
            response = await this.$store.dispatch('ticketSubGroups/update', {
              id: this.ticketSubGroupId,
              ticketSubGroup,
              userId: this.loggedUserId,
              usersWhoAuthorize: this.usersWhoAuthorize,
            })

            if (response.ok) {
              this.$store.dispatch('ticketSubGroups/updateTSTable', { ticketSubGroup: response.data })
              this.$store.dispatch('ticketSubGroups/edit', { edit: false })
            }
          } else {
            response = await this.$store.dispatch('ticketSubGroups/save', {
              ticketSubGroup,
              userId: this.loggedUserId,
              usersWhoAuthorize: this.usersWhoAuthorize,
            })

            if (response.ok) this.$store.dispatch('ticketSubGroups/addTSTable', { ticketSubGroup: response.data })
          }

          const { message } = response

          this.loading = false
          this.enableFormFields()
          this.closeForm()
          this.setError({ error: false })
          this.showToast(message)
        } catch (error) {
          this.loading = false
          this.enableFormFields()
        }
      }
    },
    async loadData() {
      try {
        await this.getTicketGroups()
        await this.getTicketTypes()
        await this.getUsers()
        this.fillFormEvent()
        this.overlay = false
      } catch (error) {
        this.overlay = false
      }
    },
    async getTicketGroups() {
      await this.$store.dispatch('ticketGroups/get')
      // const response = await this.$store.dispatch('ticketGroups/get')
      // if (response.ok) this.ticketSubGroups = response.data
    },
    async getTicketTypes() {
      await this.$store.dispatch('ticketTypes/get')
      // const response = await this.$store.dispatch('ticketTypes/get')
      // if (response.ok) this.ticketTypes = response.data
    },
    // async getUsers() {
    //   const response = await this.$store.dispatch('users/get')
    //   if (response.ok) {
    //     this.users = data.data
    //   }
    // },
  },
  created() {
    this.fillFormEvent()
  },
}
</script>

<style scoped>
.smallColor {
  color: #ff5252;
}
.smallDiv {
  margin-top: 3px;
  margin-bottom: 10px;
}
</style>
