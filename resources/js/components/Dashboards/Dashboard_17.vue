<template>
  <div style="height:100%;">
    <v-container class="mx-auto fill" fluid>
      <v-row style="height:100%;">
        <v-col cols="6" class="cube green">
          <p class="white--text">1</p>
        </v-col>
        <v-col cols="6" class="cube amber">
          <p class="white--text">2</p>
        </v-col>
        <v-col cols="6" class="cube cyan">
          <p class="white--text">3</p>
        </v-col>
        <v-col cols="6" class="cube blue">
          <p class="white--text">4</p>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import EventBus from '../../shared/EventBus'
export default {
  name: 'Dashboard_17',
  props: [],
  data: () => ({}),
  computed: {},
  methods: {
    getBIData() {
      console.log('getting data...')
    },
  },
  created() {},
  mounted() {},
}
</script>

<style scoped>
.fill {
  position: relative;
  height: 100%;
  padding: 0px 10px;
}
.cube {
  width: 100px;
  height: 50%;
}
.red {
  background-color: #f44336;
}
.orange {
  background-color: #ff9800;
}
.green {
  background-color: #4caf50;
}
.amber {
  background-color: #ffc107;
}
.cyan {
  background-color: #00bcd4;
}
.blue {
  background-color: #2196f3;
}
</style>
