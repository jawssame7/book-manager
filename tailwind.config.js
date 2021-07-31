const defaultTheme = require('tailwindcss/defaultTheme');

// module.exports = {

//   purge: [
//     './storage/framework/views/*.php',
//     './resources/**/*.blade.php',
//     './resources/**/*.js',
//     './resources/**/*.vue',
//   ],

//   theme: {
//       extend: {
//           fontFamily: {
//               sans: ['Nunito', ...defaultTheme.fontFamily.sans],
//           },
//       },
//   },

//   variants: {
//       opacity: ['responsive', 'hover', 'focus', 'disabled'],
//   },

//   plugins: [require('@tailwindcss/ui')],
// };

module.exports = {
  purge: [
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [require('@tailwindcss/forms'),],
}
