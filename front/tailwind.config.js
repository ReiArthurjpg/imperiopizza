/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./public/assets/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        navy: '#173f69',
        blue: '#1f6fb2',
        blue2: '#2e86ce',
        'blue-soft': '#eaf5ff',
        orange: '#f28c28',
        'orange-soft': '#fff1df',
        green: '#2f9e64',
        'green-soft': '#e1f5ec',
        red: '#d93838',
        'red-soft': '#fce8e8',
        background: '#090a0b',
        card: '#111315',
        surface: '#1c1f22',
        border: '#2a2d31',
        text: '#f1f3f5',
        'text-muted': '#a0aab5'
      }
    },
  },
  plugins: [],
}
