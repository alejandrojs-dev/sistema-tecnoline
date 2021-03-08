<template>
  <div style="height:100%;">
    <v-container class="mx-auto fill" fluid v-if="dynamicDataContainer">
      <v-row style="height:100%;">
        <v-col cols="4" class="cube red">
          <v-row>
            <v-col cols="12" class="white--text text-center">
              Perfileria
            </v-col>
            <v-col cols="12" class="white--text text-center text-h2 mt-3">
              <p>{{ dynamicDataContainer.countPerfileria }}</p>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="4" class="cube orange">
          <v-row>
            <v-col cols="12" class="white--text text-center">
              Corte
            </v-col>
            <v-col cols="12" class="white--text text-center text-h2 mt-3">
              <p>{{ dynamicDataContainer.countCut }}</p>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="4" class="cube green">
          <v-row>
            <v-col cols="12" class="white--text text-center">
              Armado
            </v-col>
            <v-col cols="12" class="white--text text-center text-h2 mt-3">
              <p>{{ dynamicDataContainer.countAssembling }}</p>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="4" class="cube amber">
          <v-row>
            <v-col cols="12" class="white--text text-center">
              Nivelación
            </v-col>
            <v-col cols="12" class="white--text text-center text-h2 mt-3">
              <p>{{ dynamicDataContainer.countLeveling }}</p>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="4" class="cube cyan">
          <v-row>
            <v-col cols="12" class="white--text text-center">
              Empaquetado
            </v-col>
            <v-col cols="12" class="white--text text-center text-h2 mt-3">
              <p>{{ dynamicDataContainer.countPackaging }}</p>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="4" class="cube blue">
          <v-row>
            <v-col cols="12" class="white--text text-center">
              Embarque
            </v-col>
            <v-col cols="12" class="white--text text-center text-h2 mt-3">
              <p>{{ dynamicDataContainer.countShipment }}</p>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>

    <v-container class="mt-10" v-if="loading">
      <v-row align-content="center" justify="center" class="mt-10">
        <v-col cols="12" class="subtitle-1 text-center">
          Cargando...
        </v-col>
        <v-col cols="6">
          <v-progress-linear indeterminate color="green" height="6"></v-progress-linear>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'Dashboard_1',
  data: () => ({
    loading: false,
  }),
  computed: {
    ...mapGetters({
      dynamicDataContainer: 'dashboards/dynamicDataContainer',
    }),
  },
  methods: {},
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
