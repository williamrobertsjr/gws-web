/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./views/**/*.{html,js,twig,php}"],
  theme: {
    extend: {
      colors: {
        'black': {
          DEFAULT: '#222222',
          'med': '#5f6166',
          'light': '#a0a1a5',
        },
        'white': '#f7f7f7',
        'dark-blue': '#003262',
        'light-blue': '#457cbf',
        'pale-blue': '#d1e3fa',
        'gray': '#a7a8a9',
        'dark-gray': '#333333',
      },
      backgroundColor: theme => ({
        ...theme('colors'),
      }),
      borderColor: theme => ({
        ...theme('colors'),
      }),
      container: {
        center: true,
        padding: '2rem',
      },
    },
  },
  plugins: [],
};
