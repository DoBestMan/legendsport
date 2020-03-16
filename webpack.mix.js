const mix = require('laravel-mix');

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

mix
    .js('resources/js/app/pages/home.ts', 'public/app/js')
    .js('resources/js/app/pages/tournament.js', 'public/app/js')
    .js('resources/js/backstage/pages/config.js', 'public/backstage/js')
    .js('resources/js/backstage/pages/tournaments.js', 'public/backstage/js')
    .less('resources/sass/app/home.less', 'public/app/css')
    .less('resources/sass/app/tournament.less', 'public/app/css')
    .less('resources/sass/backstage/config.less', 'public/backstage/css')
    .less('resources/sass/backstage/home.less', 'public/backstage/css')
    .less('resources/sass/backstage/tournaments.less', 'public/backstage/css')
    .extract()
    .version()
    .disableNotifications()
    .webpackConfig({
        module: {
            rules: [
                {
                    test: /\.ts$/,
                    loader: "ts-loader",
                    exclude: /node_modules/,
                    options: {
                        appendTsSuffixTo: [/.vue$/]
                    }
                }
            ]
        },
        resolve: {
            extensions: [".js", ".vue", ".ts", ".tsx"]
        }
    });
