<template>
  <div style="position:relative;height:100%;">
    <div id="pdi" v-if="!loading">
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
      <v-carousel v-model="carousel">
        <v-carousel-item v-for="dashboard in onlyDashboards" :key="dashboard.id" eager>
          <component :is="dashboard.componentName" />
        </v-carousel-item>
      </v-carousel>
    </div>
    <v-container style="height: 400px;" v-if="loading">
      <v-row class="fill-height" align-content="center" justify="center">
        <v-col class="subtitle-1 text-center" cols="12">
          Cargando dashboard
        </v-col>
        <v-col cols="6">
          <v-progress-linear color="green" indeterminate rounded height="6"></v-progress-linear>
        </v-col>
      </v-row>
    </v-container>

    <div id="slider-div-to-expand" v-show="isFullScreen">
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
      <v-carousel v-model="carousel" style="height:100%;">
        <v-carousel-item v-for="dashboard in onlyDashboards" :key="dashboard.id" class="itemExpand" eager>
          <component :is="dashboard.componentName" :ref="dashboard.componentName" />
        </v-carousel-item>
      </v-carousel>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'Slider',
  data: () => ({
    carousel: null,
    isFullScreen: false,
    vWindowItem: null,
    vCarouselItem: null,
    showExpandButton: false,
    loading: false,
  }),
  computed: {
    ...mapGetters({
      onlyDashboards: 'dashboards/onlyDashboards',
      currentIndexCarousel: 'dashboards/currentIndexCarousel',
    }),
  },
  methods: {
    setIndexCarousel(index) {
      this.carousel = index
    },
    getFullScreenElement() {
      return document.fullscreenElement || document.webkitFullscreenElement || document.mozFullscreenElement || document.msFullscreenElement
    },
    toggleFullScreen() {
      if (this.getFullScreenElement()) {
        this.isFullScreen = false
        this.showExpandButton = true
        this.vCarouselItem.style.height = '500px'
        document.exitFullscreen()
      } else {
        this.isFullScreen = true
        this.showExpandButton = false
        this.$nextTick(() => {
          this.vWindowItem = document.getElementsByClassName('v-window-item')[1]
          this.vCarouselItem = this.vWindowItem.firstChild
          this.vCarouselItem.style.height = '100%'
          document
            .getElementById('slider-div-to-expand')
            .requestFullscreen()
            .catch(e => console.log(e))
        })
      }
    },
    async getBIData({ id, dataType }) {
      try {
        const payload = { id, dataType }
        this.loading = true
        this.$store.dispatch('dashboards/cleanDynamicDataContainer')
        this.carousel = this.indexCarousel
        this.showExpandButton = false
        await this.$store.dispatch('dashboards/getBIData', payload)
        this.showExpandButton = true
        this.loading = false
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
      if (self.isFullScreen) if (key === 'Enter' || key === 13) self.toggleFullScreen()
    })

    const elements = document.getElementsByClassName('itemExpand')

    for (let element of elements) {
      const firstChild = element.firstChild
      firstChild.style.height = '100%'
    }
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
