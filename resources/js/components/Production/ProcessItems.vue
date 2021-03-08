<template>
  <div>
    <Overlay :overlay="overlay" />
    <v-sheet class="pa-4 mt-10 mr-2 ml-2" outlined>
      <v-container class="mx-auto" fluid v-if="!overlay">
        <v-row>
          <v-col cols="6">
            <div class="mt-5">
              <span class="tableTitle">Proceso: {{ processName }}</span>
            </div>
          </v-col>
          <v-col cols="6">
            <div>
              <v-btn color="carbon" class="mx-2 float-right" fab dark @click="backtoOrders()">
                <v-icon dark>mdi-arrow-left-circle</v-icon>
              </v-btn>
            </div>
          </v-col>
        </v-row>
        <v-divider></v-divider>
        <v-row>
          <v-col cols="3" xs="12" sm="12" lg="3">
            <v-container class="processInfo" fluid>
              <v-row justify="center">
                <p class="titleCard">Información del pedido</p>
              </v-row>
              <v-row>
                <v-col cols="3" sm="2" md="1" lg="12">
                  <v-icon class="separate mb-1">mdi-package-variant-closed</v-icon>
                  <span>{{ order.id }}</span>
                </v-col>
                <v-col cols="3" sm="4" md="5" lg="12">
                  <v-icon class="separate mb-1">mdi-account</v-icon> <span>{{ order.client }}</span>
                </v-col>
                <v-col cols="3" sm="3" md="3" lg="12">
                  <v-icon class="separate mb-1">mdi-factory</v-icon> <span>{{ order.factory }}</span>
                </v-col>
                <v-col cols="3" sm="3" md="3" lg="12">
                  <v-icon class="separate mb-1">mdi-calendar-month</v-icon> <span>{{ formattedOrderDate }}</span>
                </v-col>
              </v-row>
            </v-container>
          </v-col>
          <v-col cols="9" xs="12" sm="12" lg="9" v-if="itemsNumber > 0">
            <v-expansion-panels v-model="panels" focusable multiple>
              <v-expansion-panel v-for="(item, index) in itemsOrder" :key="item.id">
                <v-expansion-panel-header expand-icon="mdi-menu-down">
                  Partida: {{ item.itemNumber }} - {{ item.product }} - {{ item.article }}
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-form :id="`form-${item.itemNumber}`">
                    <v-list>
                      <v-list-item v-for="(check, i) in forms[index].checks" :key="check.id">
                        <v-list-item-content>
                          <v-list-item-title>{{ i + 1 }}.- {{ check.description }}</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-action v-if="check.isInvalid">
                          <v-icon color="red" dark>mdi-alert-circle</v-icon>
                        </v-list-item-action>
                        <v-list-item-action>
                          <v-checkbox :id="check.id" :value="check.id" @change="checking(item.itemNumber, check)" color="carbon"></v-checkbox>
                        </v-list-item-action>
                      </v-list-item>
                    </v-list>
                    <v-btn
                      class="btnSend"
                      color="carbon"
                      dark
                      small
                      style="float:right;"
                      @click="sentItemToNextProcess(item.itemNumber, item.id, item.nextProcessId, `loading${index + 1}`)"
                      :loading="loadings[`loading${index + 1}`]"
                    >
                      Siguiente proceso
                    </v-btn>
                  </v-form>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-col>

          <v-col cols="9" xs="12" sm="12" lg="9" v-else>
            <v-container class="legendContainer" fluid>
              <v-row>
                <v-col cols="12">
                  <v-icon class="iconLegend" size="75">{{ processIcon }}</v-icon>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <p class="legend">Sin partidas</p>
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
import { mapGetters } from 'vuex'
import Overlay from '../../components/Overlay'
import moment from 'moment'
import Toastr from '../../shared/Toastr'
import tecnolineSocketClient from '../../shared/TecnolineSocketClient'
import 'moment/locale/es'
export default {
  name: 'ProcessDetail',
  data: () => ({
    itemsOrder: [],
    overlay: true,
    order: null,
    panels: [],
    showDialogError: false,
    loading: false,
    loadings: [],
  }),
  components: {
    Overlay,
  },
  computed: {
    ...mapGetters({
      process: 'processes/process',
      forms: 'processes/forms',
    }),
    formattedOrderDate() {
      const orderDate = moment(new Date(this.order.createdAt))
      return moment(orderDate).format('YYYY-MM-DD')
    },
    itemsNumber() {
      return this.itemsOrder.length
    },
    processName() {
      return this.process.name
    },
    processIcon() {
      return this.process.icon
    },
  },
  methods: {
    checking(itemNumber, check) {
      this.$store.dispatch('processes/addKeyByCheck', { id: check.id, itemNumber })
      const checkboxDom = document.getElementById(check.id)
      const iElement = checkboxDom.previousSibling
      if (check.isInvalid) {
        iElement.style.color = '#FF5252'
        iElement.style.caretColor = '#FF5252'
      } else {
        if (iElement.hasAttribute('style')) iElement.removeAttribute('style')
      }
    },
    backtoOrders() {
      this.$router.push({ name: 'Perfileria' })
    },
    async getItemsByOrder() {
      try {
        const response = await this.$store.dispatch('processes/getItemsByOrder', {
          orderId: this.$route.params.id,
          processId: this.process.id,
        })
        if (response.ok) {
          this.order = response.order
          this.itemsOrder = response.order.items
          this.overlay = false
          for (let index = 0; index < this.itemsOrder.length; index++) {
            const key = `loading${index + 1}`
            this.$set(this.loadings, key, false)
            this.panels.push(index)
          }
        }
      } catch (error) {
        this.overlay = false
      }
    },
    async sentItemToNextProcess(itemNumber, itemId, nextProcessId, key) {
      this.loadings[key] = !this.loadings[key]

      const form = this.forms.find(f => f.id === itemNumber)
      const checks = form.checks
      let counter = 0

      for (const i in checks) {
        const id = checks[i].id
        const check = checks[i]

        if (check.isRequired && !check.isChecked) {
          this.$store.dispatch('processes/setCheckIsInvalid', { itemNumber, id: check.id })
          const checkboxDom = document.getElementById(id)
          const iElement = checkboxDom.previousSibling
          iElement.style.color = '#FF5252'
          iElement.style.caretColor = '#FF5252'
          counter++
        }
      }

      if (counter > 0) {
        this.loadings[key] = !this.loadings[key]
        return false
      } else {
        const response = await this.$store.dispatch('processes/sentItemToNextProcess', { itemId, nextProcessId })
        if (response.ok) {
          const toastr = new Toastr(response.message, '¡Éxito!', 3000, 'toast-bottom-center', 0, true, 'fadeOut', 'fadeIn', 'fadeOut', 1000, 'toastr')
          toastr.launch()
          const index = this.itemsOrder.findIndex(item => item.itemNumber === itemNumber)
          if (index > -1) {
            this.itemsOrder.splice(index, 1)
            this.$store.dispatch('processes/deleteForm', { itemNumber })
            this.forms.map((item, i) => this.panels.push(i))
          }
          tecnolineSocketClient.getDashboardDataEmit(1)
          this.loadings[key] = !this.loadings[key]
        }
      }
    },
  },
  created() {
    this.getItemsByOrder()
  },
  mounted() {},
}
</script>

<style scoped>
.processInfo {
  position: fixed;
  width: 300px;
  border: thin solid rgba(0, 0, 0, 0.12);
  border-radius: 4px;
  padding-bottom: 24px;
}
.separate {
  margin-right: 10px;
}
.legend {
  font-size: 50px;
  opacity: 0.3;
  /*margin-top: 50px;*/
}
.alignSubheader {
  width: 280px;
  padding: 0 0px;
}
.legendContainer {
  height: 250px;
  text-align: center;
}
.iconLegend {
  opacity: 0.5;
  font-size: 50px;
}
.titleCard {
  font-size: 0.875em;
  font-weight: 400;
  color: rgba(0, 0, 0, 0.6);
}
.tableTitle {
  font-size: 25px;
  margin-top: 15px;
}
.errorText {
  color: #ff5252 !important;
  caret-color: #ff5252 !important;
}
@media (max-width: 1024px) {
  .processInfo {
    position: initial;
    width: 100%;
  }
}
</style>
