/**
 * A script to add variation gallery functionality in woocommerce
 * @author Alessio Pangos
 * */

(function (document, $, undefined) {

	function ap_wp_theme_tw_wc_admin_js() {
		const addVariationImagesButtons = document.querySelectorAll('.add_variation_images');
		for (const button of addVariationImagesButtons) {
			button.addEventListener('click', function (event) {
				event.preventDefault();
				const target = this.dataset.target;
				const uploader = wp.media({
					title: this.dataset.uploaderTitle,
					button: {
						text: this.dataset.uploaderButtonText,
					},
					multiple: true,
					library: { type: ['image', 'video'] }
				});
				uploader.on('select', function () {
					const selection = uploader.state().get('selection');
					const attachments = [];
					selection.map(function (attachment) {
						attachment = attachment.toJSON();
						attachments.push(attachment);
					});
					const prevAttachments = document.querySelector(target).value.split(',');
					const newAttachments = attachments.map(attach => attach.id);
					const allAttachments = prevAttachments.concat(newAttachments);
					document.querySelector(target).value = allAttachments;
					const container = document.querySelector(target + ' + .variation_image_gallery_container');

					for (const attachment of attachments) {
						let attachmentElement;
						if (attachment.type === 'image') {
							attachmentElement = document.createElement('img');
							attachmentElement.src = attachment.url;
						} else {
							attachmentElement = document.createElement('video');
							attachmentElement.src = attachment.url;
							attachmentElement.controls = true;
						}
						container.appendChild(attachmentElement);
					}
					// Enable variations changes to be saved after adding images
					document.querySelector('.save-variation-changes').disabled = false;
					const variations = document.querySelectorAll('.woocommerce_variation');
					for (const variation of variations) {
						if (variation.classList.contains('open')) {
							if (!variation.classList.contains('variation-needs-update')) {
								variation.classList.add('variation-needs-update')
							}
						}
					}
				});
				uploader.open();
			});
		}

		const variationImageContainers = document.querySelectorAll('.variation_image_gallery_container');
		for (const container of variationImageContainers) {
			container.addEventListener('click', function (event) {
				if (event.target.tagName === 'IMG' || event.target.tagName === 'VIDEO') {
					const attachments = this.children;
					for (let i = 0; i < attachments.length; i++) {
						if (attachments[i] === event.target) {
							attachments[i].remove();
							const input = this.previousElementSibling;
							const attachmentIds = input.value.split(',').filter(attachId => attachId);
							attachmentIds.splice(i, 1);
							input.value = attachmentIds.join(',');
							// Enable variations changes to be saved after deleting images
							document.querySelector('.save-variation-changes').disabled = false;
							const variations = document.querySelectorAll('.woocommerce_variation');
							for (const variation of variations) {
								if (variation.classList.contains('open')) {
									if (!variation.classList.contains('variation-needs-update')) {
										variation.classList.add('variation-needs-update')
									}
								}
							}
							break;
						}
					}
				}
			});
		}

	}

	$('#woocommerce-product-data').on('woocommerce_variations_loaded', () => {
		$(document).ajaxComplete(() => {
			ap_wp_theme_tw_wc_admin_js();
		});
	});

})(document, jQuery);
