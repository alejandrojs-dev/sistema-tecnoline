<template>
  <div>
    <v-sheet class="mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid>
        <v-row>
          <Process
            v-for="permission in userProductionPermissions"
            :key="permission.id"
            :processId="permission.process_id"
            :processName="permission.name"
            :processSlug="permission.slug"
            :mdiIconProcess="permission.icon"
            :pathRoute="permission.path"
          />
        </v-row>
      </v-container>
    </v-sheet>
  </div>
</template>

<script>
import Process from '../../components/Production/Process.vue'
import { mapGetters } from 'vuex'
export default {
  name: 'Processes',
  title: 'Procesos | Tecnoline 2.0',
  components: { Process },
  computed: {
    ...mapGetters({
      dataUser: 'auth/dataUser',
    }),
    userProductionPermissions() {
      const { token } = this.dataUser
      if (token.value != null) {
        const { modules } = this.dataUser.user
        const { childSubmodules } = modules.find(m => m.slug === 'produccion').submodules[0]
        return childSubmodules
      }
    },
  },
  methods: {},
  created() {},
}
</script>

<style></style>
