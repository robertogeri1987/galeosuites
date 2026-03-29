import('imagemin').then((
	{ default: imagemin }
) => {

	import('imagemin-svgo').then((
		{ default: imageminSvgo }
	) => {

		imagemin(['./svg-sprites/*.svg'], './theme/assets/svg', {
			use: [
				imageminSvgo({
					plugins: [
						{ cleanupIDs: { remove: false } },
						{ cleanupNumericValues: { floatPrecision: 2 } },
						{ removeStyleElement: true },
						{ removeTitle: true }
					],
					multipass: true
				})
			]
		}).then(function () {
			console.log('SVG-Icons were successfully optimized');
		});


	})

})
