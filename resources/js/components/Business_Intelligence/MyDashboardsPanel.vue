<template>
  <div>
    <v-sheet class="pa-4 mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid>
        <v-row>
          <v-col cols="6">
            <div class="mt-5">
              <span class="panelTitle">Panel de dashboards</span>
            </div>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="4">
            <v-card class="mx-auto" max-width="550">
              <v-card-title class="white--text carbon">
                Mis dashboards
              </v-card-title>

              <v-card-text class="pt-4">
                Una lista de dashboards en base a su departamento
              </v-card-text>

              <v-divider></v-divider>

              <v-virtual-scroll :items="dashboardsByDep" :item-height="50" height="300" v-if="hayDashboards">
                <template v-slot:default="{ item, index }">
                  <v-list-item>
                    <v-list-item-icon v-if="item.isSlide">
                      <v-icon>mdi-view-dashboard</v-icon>
                    </v-list-item-icon>

                    <v-list-item-icon v-if="item.isRowData">
                      <v-icon>mdi-table</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                      <v-list-item-title>{{ item.name }}</v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-action v-if="item.isSlide" style="display:block;">
                      <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn color="carbon" icon v-bind="attrs" v-on="on" @click="sWithOutSlider(item)">
                            <v-icon>
                              mdi-bullseye
                            </v-icon>
                          </v-btn>
                        </template>
                        <span>Individual</span>
                      </v-tooltip>

                      <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn color="carbon" icon v-bind="attrs" v-on="on" @click="sSlider(index, item)">
                            <v-icon>
                              mdi-eye
                            </v-icon>
                          </v-btn>
                        </template>
                        <span>Ver Slider</span>
                      </v-tooltip>
                    </v-list-item-action>

                    <v-list-item-action v-if="item.isRowData" style="display:block;">
                      <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn color="carbon" icon v-bind="attrs" v-on="on" @click="sRowData(item.id)">
                            <v-icon>mdi-eye</v-icon>
                          </v-btn>
                        </template>
                        <span>Ver Row Data</span>
                      </v-tooltip>
                    </v-list-item-action>
                  </v-list-item>
                </template>
              </v-virtual-scroll>

              <v-container class="mx-auto text-center" v-else>
                <v-row>
                  <v-col cols="12">
                    <v-icon size="30" style="opacity:0.5;">mdi-view-dashboard</v-icon>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <p style="opacity:0.5;">Sin dashboards para mostrar</p>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-col>
          <v-col cols="8">
            <Dashboard v-show="showWithOutSlider" ref="dashboard" :currentComponentName="currentComponentName" />
            <Slider v-show="showSlide" ref="slider" />
            <RowData v-show="showRowData" ref="rowData" />

            <v-container class="mx-auto" v-if="!showWithOutSlider && !showSlide && !showRowData">
              <v-row>
                <v-col class="12">
                  <v-container class="legendContainer" fluid>
                    <v-row>
                      <v-col cols="12">
                        <v-icon class="iconLegend" size="75">mdi-eye</v-icon>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <p class="legend">Visor de dashboard</p>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-col>
              </v-row>
            </v-container>
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </div>
</template>

<script>
import Slider from '../Business_Intelligence/Slider'
import RowData from '../Business_Intelligence/RowData'
import Dashboard from '../Business_Intelligence/Dasboard'
import Overlay from '../Overlay'
import { mapGetters } from 'vuex'
import tecnolineSocketClient from '../../shared/TecnolineSocketClient'
export default {
  name: 'MyDashboardsPanel',
  components: { Slider, RowData, Dashboard, Overlay },
  data: () => ({
    dashboards: [],
    showWithOutSlider: false,
    showSlide: false,
    showRowData: false,
    componentName: null,
    loading: true,
    currentComponentName: null,
  }),
  computed: {
    ...mapGetters({
      dataUser: 'auth/dataUser',
      dashboardsByDep: 'dashboards/dashboardsByDep',
    }),
    hayDashboards() {
      return this.dashboardsByDep.length
    },
  },
  methods: {
    sWithOutSlider(component) {
      const payload = {
        id: component.id,
        dataType: component.isSlide ? 'vista' : 'rowData',
        componentName: component.componentName,
      }

      this.showWithOutSlider = true
      this.showSlide = false
      this.showRowData = false
      this.currentComponentName = component.componentName
      this.$store.dispatch('dashboards/setCurrentDashboardId', payload)
      this.$refs.dashboard.getBIData(payload)
    },
    sSlider(index, component) {
      const payload = {
        id: component.id,
        dataType: 'vista',
        componentName: component.componentName,
      }

      this.showSlide = true
      this.showWithOutSlider = false
      this.showRowData = false
      this.$refs.slider.getBIData(payload, index)
    },
    sRowData(id) {
      const payload = {
        id: id,
        dataType: 'rowData',
      }

      this.showRowData = true
      this.showSlide = false
      this.showWithOutSlider = false
      this.$refs.rowData.getRowData(payload)
    },
  },
  created() {
    tecnolineSocketClient.getDashboardDataOn()
  },
}
</script>

<style scoped>
.panelTitle {
  font-size: 25px;
  margin-top: 15px;
}
p {
  margin-top: 15px;
}
.legendContainer {
  height: 250px;
  text-align: center;
}
.iconLegend {
  opacity: 0.5;
  font-size: 50px;
}
.legend {
  font-size: 50px;
  opacity: 0.3;
}
</style>
