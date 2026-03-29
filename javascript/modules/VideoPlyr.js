class VideoPlyr {

	constructor() {

		this.els = document.querySelectorAll('.video-js');

		if (this.els.length > 0) {

			import('plyr/dist/plyr.css').then(() => {

				import('plyr').then((
					{ default: Plyr }
				) => {

					for (const el of this.els) {

						new Plyr(el, {
						});

					}

				});

			})



		}

	}

}

export default VideoPlyr;
