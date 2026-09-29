/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./views/**/*.php",
    "./public/**/*.php",
    "./public/assets/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        teal: {
          DEFAULT: '#2FA89D',
          hover: '#27958B',
          50: '#F0F9F8',
          100: '#D6F0ED',
          500: '#2FA89D',
          600: '#27958B',
          700: '#1F7A71',
        },
        sidebar: '#171717',
        canvas: '#F5F6F8',
        card: '#FFFFFF',
        main: '#2B2B2B',
        secondary: '#8A8F98',
        border: '#E8EAED',
        danger: '#E56B6F'
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
      boxShadow: {
        'soft': '0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.03)',
      }
    },
  },
  plugins: [],
}
