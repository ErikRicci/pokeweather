const mix = require('laravel-mix');
/*mix.js('resources/js/app.js', 'public/js')
.sass('resources/sass/app.scss', 'public/css');*/
mix.scripts(
  [
   'node_modules/jquery/dist/jquery.slim.js',
   'node_modules/jquery-typeahead/dist/jquery.typeahead.min.js'
  ], 'public/js/libs.js')
  .styles([
   'node_modules/jquery-typeahead/dist/jquery.typeahead.min.css'
  ], 'public/css/libs.css');
