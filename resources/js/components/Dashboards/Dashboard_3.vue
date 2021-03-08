<template>
  <div style="height:100%;">
    <v-container class="mx-auto fill" fluid>
      <v-row style="height:100%;">
        <v-col cols="12" class="cube red">
          <p class="white--text">1</p>
        </v-col>
        <v-col cols="12" class="cube amber">
          <p class="white--text">2</p>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  name: 'Dashboard_3',
  data: () => ({}),
  computed: {},
  methods: {
    getBIData() {
      console.log('getting data...')
    },
  },
  created() {},
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
