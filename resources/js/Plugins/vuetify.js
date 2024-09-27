import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import colors from 'vuetify/util/colors'
import { aliases, mdi } from 'vuetify/iconsets/mdi'

const vuetify = createVuetify({
  components: {
    ...components,
  },
  directives,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
  theme: {
    themes: {
      light: {
        dark: false,
        colors: {
          primary: colors.teal.darken4,
          accent: colors.teal.lighten1,
          secondary: colors.teal.lighten4,
          error: colors.red.darken2,
          warning: colors.amber.darken2,
          info: colors.blue.darken2,
          success: colors.green.darken2,
        },
      },
    },
  },
})

export default vuetify
