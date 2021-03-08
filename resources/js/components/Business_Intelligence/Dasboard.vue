<template>
  <div style="position:relative;height:100%;" v-if="!loading">
    <div id="dashboard-div-to-expand" style="height:100%;">
      <v-btn
        color="carbon"
        class="float-right expand white--text"
        @click="toggleFullScreen()"
        depressed
        small
        v-if="!isFullScreen && showExpandButton"
      >
        <v-icon>mdi-open-in-new</v-icon>
      </v-btn>
      <v-btn
        color="carbon"
        class="float-right expand white--text"
        @click="toggleFullScreen()"
        depressed
        small
        v-if="isFullScreen && !showExpandButton"
      >
        <v-icon>mdi-window-minimize</v-icon>
      </v-btn>
      <component :is="currentComponentName" :ref="currentComponentName" />
      <v-progress-linear indeterminate color="green" height="6" v-if="loading"></v-progress-linear>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'Dashboard',
  props: ['currentComponentName'],
  data: () => ({
    isFullScreen: false,
    loading: false,
    showExpandButton: false,
  }),
  computed: {
    ...mapGetters({
      currentDashboard: 'dashboards/currentDashboard',
    }),
  },
  methods: {
    getFullScreenElement() {
      return document.fullscreenElement || document.webkitFullscreenElement || document.mozFullscreenElement || document.msFullscreenElement
    },
    toggleFullScreen() {
      if (this.getFullScreenElement()) {
        this.isFullScreen = false
        this.showExpandButton = true
        document.exitFullscreen()
      } else {
        this.isFullScreen = true
        this.showExpandButton = false
        document
          .getElementById('dashboard-div-to-expand')
          .requestFullscreen()
          .catch(e => console.log(e))
      }
    },
    async getBIData({ id, dataType }) {
      try {
        this.showExpandButton = false
        const payload = { id, dataType }
        await this.$store.dispatch('dashboards/getBIData', payload)
        this.showExpandButton = true
      } catch (error) {}
    },
  },
  created() {},
  beforeCreate() {
    const dataAuth = JSON.parse(sessionStorage.getItem('dataAuth'))
    const {
      dashboards: { dashboardsByDep },
    } = dataAuth

    let components = dashboardsByDep.filter(d => d.isSlide === 1).map(d => d.componentName)

    for (let x = 0; x < components.length; x++) {
      const componentName = components[x]

      this.$options.components[`${componentName}`] = require(`../Dashboards/${componentName}`).default
    }
  },
  mounted() {
    const self = this
    document.addEventListener('keypress', e => {
      const key = e.key || e.keyCode
      if (self.isFullScreen) if (key == 'Enter' || key === 13) self.toggleFullScreen()
    })
  },
}
</script>

<style scoped>
#div-to-expand.fullscreen {
  z-index: 9999;
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.expand {
  position: absolute;
  z-index: 9999;
  right: 10px;
  top: 10px;
}
</style>
