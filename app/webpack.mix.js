const mix = require('laravel-mix');

mix.react('resources/js/', 'public/js');

mix.js('resources/js/app.js', 'public/js')
   .vue()
   .version()
   .sourceMaps(); // Enable source maps




