const mix = require('laravel-mix');

// Indica que los archivos estarán en /formato-evaluacion/
// mix.setResourceRoot('/Programa de estimulos al desempeño docente/')
mix.setResourceRoot('/formato-evaluacion/')
   .setPublicPath('public');
   
mix.react('resources/js/', 'public/js');

mix.js('resources/js/app.js', 'public/js')
   .vue()
   .version()
   .sourceMaps(); // Enable source maps




