let mix = require('laravel-mix').webpackConfig({
    watchOptions: {
        ignored: /node_modules/,
    }
}).version();

mix
    .options({
        publicPath: "public/assets/vendor/",
        resourceRoot: "/assets/vendor/"
    })
    .js("resources/assets/js/app.js", "js/app.js")
    .sass("resources/assets/sass/vendor.scss", "css/vendor.css")
    .sass("resources/assets/sass/app.scss", "css/app.css")
    .extract([
        "./resources/assets/js/bootstrap.js",
        "jquery",
        "jquery-placeholder",
        "bootstrap",
        "bootstrap-select",
        "animate.css",
        "autosize",
        "datatables.net",
        "datatables.net-rowgroup",
        "google-material-color",
        "malihu-custom-scrollbar-plugin",
        "node-waves",
        "bootstrap-notify",
    ])
    .autoload({
        "jquery": ['$', 'jQuery', "window.jQuery"],
        "node-waves": ["Waves", "window.Waves"],
        "autosize": ["autosize", "window.autosize"],
    });