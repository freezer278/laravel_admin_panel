const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    entry: './src/resources/assets/js/admin.js',
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        }
    },
    module: {
        rules: [
            { test: /\.js$/, use: 'babel-loader' },
            { test: /\.vue$/, use: 'vue-loader' },
            { test: /\.css$/, use: ['vue-style-loader', 'css-loader']},
        ]
    },
    plugins: [
        new VueLoaderPlugin(),
    ]
};