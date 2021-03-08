<template>
  <div>
    <v-sheet class="mt-200 mr-2 ml-2" outlined>
      <v-container class="mx-auto">
        <v-row dense>
          <v-col cols="12" lg="12" class="align-center">
            <v-icon color="red" size="150">mdi-alert</v-icon>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col cols="12" lg="12" class="align-center">
            <div class="text">No estás autorizado para ver ésta página</div>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col cols="12" lg="12" class="align-center">
            <v-btn color="primary" class="ma-2 white--text" @click.stop="gotoHome()">
              Ir a inicio
              <v-icon right dark>mdi-home</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </div>
</template>

<script>
export default {
  name: 'Unauthorized',
  methods: {
    gotoHome() {
      this.$router.push({ name: 'Home' })
    },
  },
}
</script>

<style scoped>
.text {
  font-size: 40px;
  color: #ff5252;
}
.mt-200 {
  margin-top: 200px;
}
.align-center {
  text-align: center;
}
</style>
