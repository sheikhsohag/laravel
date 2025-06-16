/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/views/**/*.blade.php", // Explicitly include views folder
    "./storage/framework/views/*.php", // Laravel compiled views
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}