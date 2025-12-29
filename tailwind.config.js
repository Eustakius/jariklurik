/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/*.php",
    "./app/Views/**/*.php",
    "./public/**/*.js",
    "./src/**/*.js"
  ],
  safelist: [
    // Dynamic color classes for mass actions
    { pattern: /^(bg|text|hover:bg|hover:text|border)-(success|danger|warning|info|primary)-(50|100|600|700|800)$/ },
    // Arbitrary selector patterns
    { pattern: /^\[&_/ },
    // Common dynamic classes
    'btn-mass-action',
    'hidden',
    'flex',
    'inset-0',
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
