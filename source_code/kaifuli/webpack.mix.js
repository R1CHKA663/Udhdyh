const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .css("resources/css/app.css", "public/css")
    .vue();
mix.js("resources/components/admin/app.js", "public/admin")
    .css("resources/components/admin/app.css", "public/admin")
    .vue();

mix.copy("resources/img", "public/img");
mix.copy("resources/sound", "public/sound");
