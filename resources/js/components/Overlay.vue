<template>
  <div id="overlay">
    <v-overlay :value="overlay">
      <v-container style="height: 400px;">
        <v-row class="fill-height" align-content="center" justify="center">
          <v-col class="subtitle-1 text-center" cols="12">
            Cargando...
          </v-col>
          <v-col cols="12">
            <v-progress-linear color="green" indeterminate height="6"></v-progress-linear>
          </v-col>
        </v-row>
      </v-container>
    </v-overlay>
  </div>
</template>

<script>
export default {
  name: 'Overlay',
  props: ['overlay'],
}
</script>

<style></style>
