/** @type {import('tailwindcss').Config} */
export default {
  content: ["./resources/**/*.blade.php", "./resources/**/*.js", "./resources/**/*.vue"],
  theme: {
    extend: {
      colors: {
        'azul-profesional': '#1E3A8A',
        'verde-salud': '#108981',
      },
      fontFamily: {
        sans: ['Poppins', 'ui-sans-serif', 'system-ui'],
      },
    },
  },
  plugins: [],
};
