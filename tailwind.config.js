/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/*.php",
    "./app/Views/**/*.php",
    "./public/**/*.js",
    "./src/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          DEFAULT: "#FF5722",  // warna utama
          dark: "#E64A19",     // hover
        },
      },
      fontFamily: {
        rhd: ['"Red Hat Display"', 'sans-serif'],
        inter: ["Inter", "sans-serif"],
      },
    },
  },
  plugins: [],
}
