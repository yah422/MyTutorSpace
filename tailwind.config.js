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
        primary: '#1B4255',
        secondary: '#567C8D',
        accent: '#FFD700',
        background: '#ECECEC',
        highlight: '#70F9D9'
      
      },
      screens: {
        'md': '768px',
        'lg': '1024px',
      },
    },
  },
  plugins: [],
}

