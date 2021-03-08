<template>
  <div>
    <DashboardForm />
    <v-sheet class="pa-4 mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid>
        <v-row>
          <v-col cols="12">
            <div class="mt-5">
              <span class="panelTitle">Panel de administración</span>
            </div>
          </v-col>
        </v-row>
        <v-divider></v-divider>
        <v-row>
          <v-col cols="12">
            <v-tabs color="carbon" icons-and-text v-model="tab">
              <v-tab>Creación de Dashboard <v-icon>mdi-view-dashboard</v-icon> </v-tab>
            </v-tabs>
            <v-tabs-items v-model="tab">
              <v-tab-item>
                <DashboardGrid />
              </v-tab-item>
            </v-tabs-items>
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </div>
</template>

<script>
import DashboardForm from '../../components/Business_Intelligence/DashboardForm'
import DashboardGrid from '../../components/Business_Intelligence/DashboardGrid'
export default {
  name: 'DashboardAdminPanel',
  components: { DashboardForm, DashboardGrid },
  data: () => ({
    tab: null,
  }),
}
</script>

<style scoped>
.panelTitle {
  font-size: 25px;
  margin-top: 15px;
}
</style>
