<template>
  <div>
    <v-dialog persistent max-width="600" v-model="openForm" :retain-focus="false">
      <v-card>
        <v-card-title class="headline">Crear grupo</v-card-title>
        <v-divider class="mx-3 mb-3"></v-divider>
        <Alert
          v-if="error"
          :alertType="alertInfoData.type"
          :alertMessage="alertInfoData.message"
          :alertIcon="alertInfoData.icon"
          :alertErrors="alertInfoData.errors"
        />
        <!-- <v-form enctype="multipart/form-data">-->
        <v-form id="frmTicketGroup" @submit.prevent="save()">
          <v-card-text>
            <div class="smallDiv">
              <small class="smallColor">*Campos obligatorios</small>
            </div>
            <v-text-field
              dense
              label="Nombre de grupo*"
              outlined
              autocomplete="off"
              clearable
              clear-icon="mdi-close-circle"
              prepend-icon="mdi-clipboard-edit"
              v-model="name"
              :error-messages="nameErrors"
              :disabled="disableName"
            ></v-text-field>
            <v-text-field
              dense
              label="Descripción*"
              outlined
              autocomplete="off"
              clearable
              clear-icon="mdi-close-circle"
              prepend-icon="mdi-clipboard-edit"
              v-model="description"
              :error-messages="descriptionErrors"
              :disabled="disableName"
            ></v-text-field>

            <v-text-field
              v-model="icon"
              dense
              label="Icono identificador"
              outlined
              autocomplete="off"
              clearable
              clear-icon="mdi-close-circle"
              prepend-icon="mdi-information"
              :disabled="disableIcon"
            >
            </v-text-field>

            <v-autocomplete
              v-model="assignedUsers"
              :disabled="disableAutocomplete"
              :items="users"
              prepend-icon="mdi-account-group"
              item-value="user_id"
              item-text="username"
              color="primary"
              label="Asignar usuarios"
              multiple
              chips
              small-chips
              outlined
              dense
            >
              <template v-slot:selection="data">
                <v-chip v-bind="data.attrs" :input-value="data.selected" @click:close="unassignUser(data.item)" close>
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
            <!--<v-file-input
                            v-model="img"
                            color="primary"
                            label="Imagen de grupo"
                            placeholder="Selecciona una imagen"
                            accept="image/*"
                            prepend-icon="mdi-image"
                            dense
                            outlined
                            chips
                            show-size
                            counter
                            :rules="inputImgRules"
                            :disabled="disableImg"
                            @change="imagePreview($event)"
                            @click:close="cleanPreview()"
                        >
                            <template v-slot:selection="{ index, text }">
                                <v-chip v-if="index < 2" color="primary" dark label small>{{
                                    text
                                }}</v-chip>
                            </template>
                        </v-file-input>

                        <v-row>
                            <v-col cols="12" lg="12">
                                <v-card class="mx-auto" max-width="344" outlined v-if="fileUrl">
                                    <v-card-title class="justify-center"
                                        >Vista previa de imagen</v-card-title
                                    >
                                    <v-card-text>
                                        <div class="preview">
                                            <v-img
                                                :src="fileUrl"
                                                max-height="180"
                                                max-width="180"
                                                class="mx-auto"
                                            ></v-img>
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>-->

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
import { required } from 'vuelidate/lib/validators'
import TicketGroup from '../../models/TicketGroup.model'
import Alert from '../../components/Alert'
import EventBus from '../../shared/EventBus'
import Toastr from '../../shared/Toastr'
export default {
  name: 'TicketGroupForm',
  components: { Alert },
  data: () => ({
    ticketGroups: null,
    ticketGroupId: null,
    name: null,
    description: null,
    icon: null,
    img: null,
    active: true,
    loading: false,
    disableName: false,
    disableDescription: false,
    disableActive: false,
    disableIcon: false,
    disableAutocomplete: false,
    inputImgRules: [value => !value || value.size < 2000000 || 'El tamaño de la imagen debe ser menor de 2 MB'],
    assignedUsers: [],
    file: null,
    fileUrl: null,
    loading: false,
  }),
  validations: {
    name: { required },
    description: { required },
  },
  computed: {
    ...mapGetters({
      openForm: 'ticketGroups/openForm',
      error: 'actions/error',
      alertInfoData: 'actions/alertInfo',
      dataUser: 'auth/dataUser',
      ticketGroupData: 'ticketGroups/ticketGroupData',
      edit: 'ticketGroups/edit',
      users: 'users/usersApi',
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
      return errors
    },
  },
  methods: {
    unassignUser(user) {
      const index = this.assignedUsers.indexOf(user.user_id)
      if (index >= 0) this.assignedUsers.splice(index, 1)
    },
    fillFormEvent() {
      EventBus.$on('fillFormtg', ticketGroup => this.fillForm(ticketGroup))
    },
    fillForm(ticketGroup) {
      this.ticketGroupId = ticketGroup.id
      this.name = ticketGroup.name
      this.description = ticketGroup.description
      // //this.fileUrl = this.ticketGroupData.file.base64Encoded
      // //this.img = this.createFileFromBase64EncodedImage()
      this.active = ticketGroup.active
      this.assignedUsers = ticketGroup.users.map(user => user.user_id)
    },
    createFileFromBase64EncodedImage() {
      const base64Encoded = this.ticketGroupData.file.base64Encoded.split(',')[1] //obtenemos la imagen codificada en base 64
      const decodedBase64 = atob(base64Encoded) //datos binarios(bytes) de la imagen
      const buffer = new ArrayBuffer(decodedBase64.length) //Creamos buffer para el flujo de datos binarios pasando como parametro el numero de bytes de la imagen
      const blobArray = new Uint8Array(buffer) // creamos array de bytes
      for (let index = 0; index < decodedBase64.length; index++) {
        blobArray[index] = decodedBase64.charCodeAt(index) //llenamos el array de bytes con la data binaria de la imagen(bytes)
      }
      const file = new File([blobArray], this.ticketGroupData.file.originalName, {
        type: this.ticketGroupData.file.mimeType,
      }) //creamos un archivo en base a la data de la imagen(array de bytes, nombre original y su tipo mime)
      return file
    },
    cleanForm() {
      this.name = null
      this.description = null
      this.img = null
      this.assignedUsers = null
      this.active = true
      this.fileUrl = null
      this.$v.$reset()
    },
    disableFormFields() {
      this.disableName = true
      this.disableDescription = true
      this.disableIcon = true
      this.disableActive = true
      this.disableAutocomplete = true
    },
    enableFormFields() {
      this.disableName = false
      this.disableDescription = false
      this.disableIcon = false
      this.disableActive = false
      this.disableAutocomplete = false
    },
    showToast(message) {
      const toastr = new Toastr(message, '¡Éxito!', 3000, 'toast-bottom-center', 0, true, 'fadeOut', 'fadeIn', 'fadeOut', 1000, 'toastr', null)
      toastr.launch()
    },
    imagePreview(file) {
      if (file) {
        const fileReader = new FileReader()
        fileReader.readAsDataURL(file)
        fileReader.onload = event => {
          this.fileUrl = event.target.result
        }
      } else {
        this.img = null
        this.fileUrl = null
      }
    },
    closeForm() {
      this.cleanForm()
      this.setError({ error: false })
      this.$store.dispatch('ticketGroups/openForm')
    },
    setError(payload) {
      this.$store.dispatch('actions/setError', payload)
    },
    async save() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        this.disableFormFields()
        if (this.error) this.setError({ error: false })
        try {
          // const formData = new FormData()
          // formData.append('name', this.name)
          // formData.append('description', this.description)
          // formData.append('active', this.active ? 1 : 0)
          // formData.append('assignedUsers', this.assignedUsers)
          // formData.append('file', this.img)
          //No olvidar quitar el hardcode
          const ticketGroup = new TicketGroup(this.name, this.description, this.icon, this.active)

          let response = null

          if (this.edit) {
            response = await this.$store.dispatch('ticketGroups/update', { id: this.ticketGroupId, ticketGroup, assignedUsers: this.assignedUsers })
            if (response.ok) {
              this.$store.dispatch('ticketGroups/updateTGTable', { ticketGroup: response.data })
              this.$store.dispatch('ticketGroups/edit', { edit: false })
            }
          } else {
            response = await this.$store.dispatch('ticketGroups/save', { ticketGroup, assignedUsers: this.assignedUsers })
            if (response.ok) this.$store.dispatch('ticketGroups/addTGTable', { ticketGroup: response.data })
          }

          const { message } = response

          this.loading = false
          this.cleanForm()
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
    // async getTicketGroups() {
    //   const response = await this.$store.dispatch('ticketGroups/get')
    //   if (response.ok) this.ticketGroups = response.data
    // },
    // async getUsers() {
    //   const response = await this.$store.dispatch('users/get')
    //   if (response.ok) this.users = response.data
    // },
    async loadData() {
      try {
        this.fillFormEvent()
        await this.getTicketGroups()
        await this.getUsers()
      } catch (error) {}
    },
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
