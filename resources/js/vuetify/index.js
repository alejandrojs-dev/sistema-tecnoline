import Vue from 'vue'
import Vuetify from 'vuetify'
import vuetifyColors from 'vuetify/lib/util/colors'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
  theme: {
    themes: {
      light: {
        //primary: vuetifyColors.lightGreen.darken1, // #E53935
        primary: vuetifyColors.shades.black,
        grey: vuetifyColors.blueGrey.darken4,
        white: vuetifyColors.white,
        carbon: vuetifyColors.grey.darken3,
        red: vuetifyColors.red.darken1,
        redAccent1: vuetifyColors.red.accent1,
        green: vuetifyColors.green.darken1,
        lowBlue: vuetifyColors.blue,
        blue: vuetifyColors.blue.darken4,
        teal: vuetifyColors.teal,
        orange: vuetifyColors.orange,
        indigo: vuetifyColors.indigo,
        amber: vuetifyColors.amber,
        greyLow: vuetifyColors.grey,
        yellowDarken: vuetifyColors.yellow.darken1,
      },
      dark: {
        primary: vuetifyColors.blue.lighten3,
      },
    },
  },
}

export default new Vuetify(opts)
