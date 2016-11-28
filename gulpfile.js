var elixir = require('laravel-elixir');

require('laravel-elixir-browserify-official');
require('laravel-elixir-vueify-2.0');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.browserify('app.js');
    mix.browserify('echo.js');
    mix.browserify('vue.js');
    mix.sass([
        'materialize.scss'
    ], 'public/css/materialize.css');
    mix.sass('app.scss');
});
