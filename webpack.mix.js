const { mix } = require('laravel-mix'),
    prod = process.argv.indexOf('-p') !== -1;

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
if (prod) {
    mix.js('resources/assets/js/app.js', 'public/js/app.min.js')
        .js('resources/assets/js/echo.js', 'public/js/vendors/echo.min.js')
        .js('resources/assets/js/vue.js', 'public/js/vendors/vue.min.js')
        .sass('resources/assets/sass/materialize.scss', 'public/css/vendors/materialize.min.css')
        .sass('resources/assets/sass/materialize_dark.scss', 'public/css/vendors/materialize_dark.min.css')
        .sass('resources/assets/sass/timeline.scss', 'public/css/vendors/timeline.min.css');
}
else {
    mix.js('resources/assets/js/app.js', 'public/js')
        .js('resources/assets/js/echo.js', 'public/js/vendors')
        .js('resources/assets/js/vue.js', 'public/js/vendors')
        .sass('resources/assets/sass/materialize.scss', 'public/css/vendors')
        .sass('resources/assets/sass/materialize_dark.scss', 'public/css/vendors')
        .sass('resources/assets/sass/timeline.scss', 'public/css/vendors');
}
