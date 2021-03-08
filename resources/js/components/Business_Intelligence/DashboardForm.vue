<template>
  <div>
    <v-row justify="center">
      <v-dialog v-model="openForm" persistent max-width="600" :retain-focus="false">
        <v-card>
          <v-card-title class="headline">Crear Dashboard</v-card-title>
          <v-divider></v-divider>
          <Alert
            v-if="error"
            :alertType="alertInfo.type"
            :alertMessage="alertInfo.message"
            :alertIcon="alertInfo.icon"
            :alertErrors="alertInfo.errors"
          />
          <v-form id="frmDashboard" ref="frmDashboard" @submit.prevent="save()" lazy-validation>
            <v-card-text>
              <div class="smallDiv">
                <small class="smallColor">*Campos obligatorios</small>
              </div>

              <v-autocomplete
                v-model="allowedDepartments"
                :disabled="disableDepartments"
                :items="departments"
                :error-messages="allowedDepartmentsErrors"
                prepend-icon="mdi-account-group"
                item-value="id"
                item-text="name"
                color="carbon"
                label="Departamentos permitidos*"
                multiple
                chips
                small-chips
                outlined
                dense
              >
                <template v-slot:selection="data">
                  <v-chip v-bind="data.attrs" :input-value="data.selected" @click:close="deleteDepartment(data.item.id)" close>
                    <v-avatar left size="50" color="carbon">
                      <v-icon dark small>{{ data.item.icon }}</v-icon>
                    </v-avatar>
                    {{ data.item.name }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <template v-if="typeof data.item !== 'object'">
                    <v-list-item-content>{{ data.item }}</v-list-item-content>
                  </template>
                  <template v-else>
                    <v-list-item-avatar size="50" color="carbon">
                      <v-icon dark>{{ data.item.icon }}</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title>{{ data.item.name }}</v-list-item-title>
                    </v-list-item-content>
                  </template>
                </template>
              </v-autocomplete>
              <v-text-field
                type="text"
                color="carbon"
                label="Nombre*"
                v-model.trim="name"
                prepend-icon="mdi-clipboard-text"
                clear-icon="mdi-close-circle"
                :error-messages="nameErrors"
                :disabled="disableName"
                required
                outlined
                clearable
                dense
                autocomplete="off"
              ></v-text-field>
              <v-text-field
                type="text"
                color="carbon"
                label="Descripción*"
                v-model.trim="description"
                prepend-icon="mdi-clipboard-text"
                clear-icon="mdi-close-circle"
                :error-messages="descriptionErrors"
                :disabled="disableDescription"
                required
                outlined
                clearable
                dense
                autocomplete="off"
              ></v-text-field>

              <v-select
                v-model="type"
                :items="types"
                label="Tipo"
                prepend-icon="mdi-cube"
                clear-icon="mdi-close-circle"
                outlined
                clearable
                dense
                :error-messages="typeErrors"
                :disabled="disableTypeSelect"
                @change="showIsInCarouselOption($event)"
              >
              </v-select>
              <v-checkbox v-if="show" color="carbon" v-model="isInCarousel" label="En carrusel" :disabled="disableIsInCarousel" required>
              </v-checkbox>
              <v-checkbox color="carbon" v-model="active" label="Activo" :disabled="disableActive" required> </v-checkbox>
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
          <v-progress-linear color="carbon" indeterminate height="6" v-if="loading"></v-progress-linear>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { required, requiredIf } from 'vuelidate/lib/validators'
import EventBus from '../../shared//EventBus'
import Dashboard from '../../models/Dashboard.model'
import Alert from '../../components/Alert'
import Toastr from '../../shared/Toastr'
export default {
  name: 'DashboardForm',
  components: { Alert },
  data: () => ({
    name: null,
    dashboardId: null,
    description: null,
    isInCarousel: true,
    active: true,
    allowedDepartments: [],
    departments: [],
    types: ['Vista', 'Row Data'],
    loading: false,
    disableName: false,
    disableDescription: false,
    disableIsInCarousel: false,
    disableActive: false,
    disableDepartments: false,
    disableTypeSelect: false,
    rAllowedDepartments: true,
    show: true,
    type: null,
    isSlide: false,
    isRowData: false,
  }),
  computed: {
    ...mapGetters({
      openForm: 'dashboards/openForm',
      error: 'actions/error',
      alertInfo: 'actions/alertInfo',
      dataUser: 'auth/dataUser',
      edit: 'dashboards/edit',
    }),
    nameErrors() {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      if (!this.$v.name.required && errors.push('El nombre es obligatorio')) return errors
    },
    descriptionErrors() {
      const errors = []
      if (!this.$v.description.$dirty) return errors
      if (!this.$v.description.required && errors.push('La descripción es obligatoria')) return errors
    },
    allowedDepartmentsErrors() {
      const errors = []
      if (!this.$v.allowedDepartments.$dirty) return errors
      if (!this.$v.allowedDepartments.required && errors.push('Debe asignar al menos un departamento al dashboard')) return errors
    },
    typeErrors() {
      const errors = []
      if (!this.$v.type.$dirty) return errors
      if (!this.$v.type.required && errors.push('Debe seleccionar un tipo de dashboard')) return errors
    },
  },
  validations: {
    name: { required },
    description: { required },
    type: { required },
    allowedDepartments: {
      required: requiredIf(function() {
        return this.rAllowedDepartments
      }),
    },
  },
  methods: {
    showToast(message) {
      const toastr = new Toastr(message, '¡Éxito!', 3000, 'toast-bottom-center', 0, true, 'fadeOut', 'fadeIn', 'fadeOut', 1000, 'toastr', null)
      toastr.launch()
    },
    closeForm() {
      this.cleanForm()
      this.setError({ error: false })
      this.$store.dispatch('dashboards/openForm')
      if (this.edit) this.$store.dispatch('dashboards/edit', { edit: false })
    },
    setError(payload) {
      this.$store.dispatch('actions/setError', payload)
    },
    cleanForm() {
      this.name = null
      this.description = null
      this.isInCarousel = true
      this.active = true
      this.allowedDepartments = []
      this.type = null
      this.$v.$reset()
    },
    disableFormFields() {
      this.disableName = true
      this.disableDescription = true
      this.disableSelectDepartment = true
      this.disableIsInCarousel = true
      this.disableActive = true
      this.disableTypeSelect = true
    },
    enableFormFields() {
      this.disableName = false
      this.disableDescription = false
      this.disableSelectDepartment = false
      this.disableIsInCarousel = false
      this.disableActive = false
      this.disableTypeSelect = false
    },
    fillDashboardData(dashboard) {
      this.dashboardId = dashboard.id
      this.name = dashboard.name
      this.description = dashboard.description
      this.isInCarousel = dashboard.isInCarousel
      this.active = dashboard.active
      this.allowedDepartments = dashboard.departments.map(department => department.id_department)
      this.show = this.isInCarousel
      if (dashboard.isSlide) this.type = 'Vista'
      if (dashboard.isRowData) this.type = 'Row Data'
    },
    deleteDepartment(id) {
      const index = this.allowedDepartments.findIndex(element => element === id)
      if (index > -1) {
        this.allowedDepartments.splice(index, 1)
      }
    },
    showIsInCarouselOption(e) {
      const type = e

      if (type == 'Vista') {
        this.show = true
        this.isInCarousel = true
        this.isSlide = true
        this.isRowData = false
      }

      if (type == 'Row Data') {
        this.show = false
        this.isInCarousel = false
        this.isRowData = true
        this.isSlide = false
      }
    },
    async getDepartments() {
      try {
        const response = await this.$store.dispatch('dashboards/getDepartments')
        if (response.ok) {
          this.departments = response.departments
        }
      } catch (error) {}
    },
    async save() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.disableFormFields()
        if (this.error) this.setError({ error: false })
        try {
          const dashboard = new Dashboard(
            this.name,
            this.description,
            this.isInCarousel,
            this.active,
            this.allowedDepartments,
            this.isSlide,
            this.isRowData,
          )

          let response = null

          if (this.edit) {
            response = await this.$store.dispatch('dashboards/update', { id: this.dashboardId, dashboard })
            if (response.ok) {
              this.$store.dispatch('dashboards/updateDashboardTable', { dashboard: response.dashboard })
              this.$store.dispatch('dashboards/edit', { edit: false })
            }
          } else {
            response = await this.$store.dispatch('dashboards/save', { dashboard })
            if (response.ok) {
              this.$store.dispatch('dashboards/addDashboardTable', { dashboard: response.dashboard })

              if (this.isSlide) {
                const { id } = response.dashboard

                const payload = {
                  dashboardId: id,
                }

                const res = await this.$store.dispatch('dashboards/createVueFileComponent', payload)

                if (res.ok) {
                  console.log(res.created)
                }
              }
            }
          }

          const { message } = response

          this.loading = false
          this.enableFormFields()
          this.closeForm()
          this.showToast(message)
        } catch (error) {
          this.loading = false
          this.enableFormFields()
        }
      }
    },
  },
  created() {
    this.getDepartments()
  },
  mounted() {
    EventBus.$on('fillDashboardData', dashboard => {
      this.fillDashboardData(dashboard)
    })
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
