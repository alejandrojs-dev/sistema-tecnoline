<template>
  <v-col cols="4" sm="6" md="6" lg="3">
    <v-card class="mx-auto" max-width="344" width="344" outlined>
      <v-card-title class="justify-center">
        <v-avatar color="carbon" size="100">
          <v-icon dark style="font-size:80px;">{{ mdiIconProcess }}</v-icon>
        </v-avatar>
      </v-card-title>
      <v-card-subtitle class="text-center mt-2 subtitleProcess">{{ processName }}</v-card-subtitle>
      <v-card-actions class="justify-center">
        <v-btn dark block color="green" @click="enterRoute(pathRoute)">
          Entrar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-col>
</template>

<script>
export default {
  name: 'Process',
  props: ['processId', 'processName', 'processSlug', 'mdiIconProcess', 'pathRoute'],
  data: () => ({}),
  methods: {
    enterRoute(pathRoute) {
      this.$store.dispatch('processes/setProcess', {
        processId: this.processId,
        processName: this.processName,
        processSlug: this.processSlug,
        processIcon: this.mdiIconProcess,
      })
      this.$router.push({ path: pathRoute })
    },
  },
  created() {},
}
</script>

<style scoped>
.subtitleProcess {
  font-size: 25px;
}
</style>
