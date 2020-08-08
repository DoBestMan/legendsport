const mix = require("laravel-mix");

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
    // App
    .js("resources/js/app/app.ts", "public/app/js")
    .sass("resources/sass/app/app.scss", "public/app/css")

    // Backstage
    .js("resources/js/backstage/pages/config.js", "public/backstage/js")
    .js("resources/js/backstage/pages/tournaments.js", "public/backstage/js")
    .js("resources/js/backstage/pages/admins.js", "public/backstage/js")
    .sass("resources/sass/backstage/backstage.scss", "public/backstage/css")

    // General
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
                        appendTsSuffixTo: [/.vue$/],
                    },
                },
            ],
        },
        resolve: {
            extensions: [".js", ".vue", ".ts"],
        },
    });
