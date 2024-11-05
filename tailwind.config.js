/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.css",
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        gold: '#D4AF37',
        darkGray: '#1D1D1D',
      },
    },
  },
  plugins: [],
}

