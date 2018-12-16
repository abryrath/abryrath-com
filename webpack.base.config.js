module.exports = {
    "mode": "development",
    "entry": __dirname+"/assets/js/App.js",
    "output": {
        "path": __dirname+'/web/',
        "filename": "[name].js"
    },
    "module": {
        "rules": [
            // {
            //     "enforce": "pre",
            //     "test": /\.(js|jsx)$/,
            //     "exclude": /node_modules/,
            //     "use": "eslint-loader"
            // },
            {
                "test": /\.js$/,
                "exclude": /node_modules/,
                "use": {
                    "loader": "babel-loader",
                    "options": {
                        "presets": [
                            "@babel/preset-env"
                        ]
                    }
                }
            },
            {
                "test": /\.scss$/,
                "use": [
                    "style-loader",
                    "css-loader",
                    "sass-loader"
                ]
            }
        ]
    },
    "devtool": "source-map"
};

//export default config;