<template>
  <div>
    <v-alert v-if="alertErrors" color="error" class="ml-6 mr-6 mb-1 mt-5" dense dismissible outlined text>
      <h2 class="headline">{{ alertMessage }} <v-icon class="error-text">mdi-close-thick</v-icon></h2>
      <v-divider class="mt-3 error"></v-divider>
      <v-list disabled dense>
        <v-subheader class="error-text">*Surgieron los siguientes errores:</v-subheader>
        <v-list-item v-for="(error, i) in alertErrors" :key="i" class="ml-2">
          <v-list-item-icon class="mr-2">
            <v-icon class="error-text">mdi-alert-octagon</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title class="error-text">{{ errorString(error) }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-alert>
    <v-alert :type="alertType" :icon="alertIcon" class="ml-6 mr-6 mb-3 mt-5" dense dismissible text outlined v-else>
      {{ alertMessage }}
    </v-alert>
  </div>
</template>

<script>
export default {
  name: 'Alert',
  props: ['alertType', 'alertMessage', 'alertIcon', 'alertErrors'],
  methods: {
    errorString(error) {
      return error.toString()
    },
  },
}
</script>

<style scoped>
.error-text {
  color: #ff5252 !important;
  caret-color: #ff5252 !important;
}
</style>
