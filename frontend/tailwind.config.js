const colors = require('tailwindcss/colors')

module.exports = {
  purge: false, //['./src/**/*.{js, jsx, ts, tsx}'],
  darkMode: false, // or 'media' or 'class'
  theme: {
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      success: colors.teal,
      green: colors.green,
      info: colors.sky,
      danger: colors.rose,
      white: colors.white,
      primary: colors.indigo,
      neutral: colors.coolGray,
      gray: colors.coolGray,
      warning: colors.orange,
    },
    extend: {},
  },
  variants: {
    extend: {},
  },
  // plugins: [require('@tailwindcss/forms')],
  plugins: [],
}
