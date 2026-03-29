const path = require('path');

module.exports = [
	// Legacy browsers build
	{
		entry: {
			App: ['core-js/stable/', 'regenerator-runtime/runtime', './javascript/script.js']
		},
		output: {
			path: path.resolve(__dirname, "./theme/js/legacy"),
			filename: "scripts-bundled-legacy.js",
			chunkFilename: "chunk_[name].js"
		},
		externals: {
			jquery: 'jQuery'
		},
		target: ['web', 'es5'],
		module: {
			rules: [
				{
					test: /(\.(t|j)s(x?))$/,
					exclude: [/@babel(?:\/|\\{1,2})runtime|core-js/, /node_modules\/(?!(swiper|ssr-window|dom7)\/).*/],
					use: {
						loader: 'babel-loader',
						options: {
							presets: [
								[
									"@babel/preset-env",
									{
										modules: false,
										targets: {
											browsers: ["ie >= 11"]
										},
										useBuiltIns: 'usage',
										corejs: '3',
									}
								]
							],
						}
					}
				},
				{
					test: /\.css$/i,
					use: ["style-loader", "css-loader"],
				},
			]
		},
		mode: 'production',
		performance: { hints: false }
	},
	// Modern browsers production build
	{
		entry: {
			App: ['./javascript/script.js']
		},
		output: {
			path: path.resolve(__dirname, "./theme/js"),
			filename: "script.min.js",
			chunkFilename: "chunk_[name].min.js"
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
						options: {
							presets: [
								[
									"@babel/preset-env",
									{
										targets: {
											browsers: [">0.25%"]
										},
										"bugfixes": true,
										useBuiltIns: 'usage',
										corejs: '3',
									}
								]
							],
						}
					}
				},
				{
					test: /\.css$/i,
					use: ["style-loader", "css-loader"],
				},
			]
		},
		mode: 'production'
	}
]
