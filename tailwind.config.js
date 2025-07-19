/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // ⬅️ WAJIB kalau ingin pakai dark:...
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './resources/**/*.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'), // optional untuk styling input dll
  ],
}
