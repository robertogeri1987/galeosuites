const path = require('path');

module.exports = [
	// Modern browsers development build
	{
		entry: {
			App: ['./javascript/script.js']
		},
		output: {
			path: path.resolve(__dirname, "./theme/js"),
			filename: "script.min.js",
			chunkFilename: "chunk_[name].js"
		},
		externals: {
			jquery: 'jQuery'
		},
		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: /node_modules/,
					use: {
						loader: 'babel-loader',
					}
				},
				{
					test: /\.css$/i,
					use: ["style-loader", "css-loader"],
				},
			]
		},
		mode: 'development'
	}
]
