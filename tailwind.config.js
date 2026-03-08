/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",              // Tous les fichiers PHP à la racine
    "./includes/**/*.php",    // Tout dans le dossier includes
    "./pages/**/*.php",       // Tout dans le dossier pages
    "./src/**/*.{js,ts}",     // Tes fichiers JS ou TS
    "./public/**/*.html",     // Si tu as du HTML dans public
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

