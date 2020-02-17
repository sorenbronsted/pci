const webpack = require('webpack');
const path = require('path');

const config = {
	mode: "development",
	entry: './public/web/src/main.js',
	output: {
		path: path.resolve(__dirname, './public/web/js'),
		filename: 'main.bundle.js'
	}
};

module.exports = config;