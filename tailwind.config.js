/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.tsx",
    "./templates/**/*.twig",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('flowbite/plugin'),
  ],
};
