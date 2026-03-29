import * as $ from 'jquery';
import { _setCookie, _getCookie } from './Utilities.js';

class Wishlist {

	constructor() {
		this.wishListBoxes = document.querySelectorAll('[data-wishlist-box]');
		this.isLoggedIn = likeParams.logged_in;
		this.cookieSet = _getCookie('ap_wp_wishlist');
		this.widgetCount = document.querySelector('[data-wishlist-widget-count]');
		if (this.wishListBoxes.length > 0) this.events();
	}

	events() {
		for (const box of this.wishListBoxes) {
			box.addEventListener("click", this.clickDispatcher.bind(this));
		}
	}

	reload() {
		this.wishListBoxes = document.querySelectorAll('[data-wishlist-box]');
		if (this.wishListBoxes.length > 0) this.events();
	}

	clickDispatcher(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		let currentLikeBox = e.target.closest("[data-wishlist-box]");

		if (currentLikeBox.dataset.exists == 'yes') {
			let currentIcon = currentLikeBox.querySelector('[data-wishlist-icon-liked]');
			currentIcon.classList.add('pulse');
			this.deleteLike(currentLikeBox, currentIcon);
		} else {
			let currentIcon = currentLikeBox.querySelector('[data-wishlist-icon-like]');
			currentIcon.classList.add("pulse");
			this.createLike(currentLikeBox, currentIcon);
		}
	}

	syncCreate(currentLikeBox) {
		for (const b of this.wishListBoxes) {
			if (b.dataset.prodId == currentLikeBox.dataset.prodId) {
				let allLikes
				b.dataset.allLikes ? allLikes = parseInt(b.dataset.allLikes) : allLikes = 0;
				allLikes++;
				b.dataset.allLikes = allLikes;
				b.querySelector('[data-wishlist-count]').innerHTML = allLikes;
				let addText = b.querySelector('[data-addtext]');
				let removeText = b.querySelector('[data-removetext]');
				b.dataset.exists = 'yes';
				addText.style.display = 'none';
				removeText.style.display = 'block';
			}
		}
	}

	syncDelete(currentLikeBox) {
		for (const b of this.wishListBoxes) {
			if (b.dataset.prodId == currentLikeBox.dataset.prodId) {
				let allLikes
				b.dataset.allLikes ? allLikes = parseInt(b.dataset.allLikes) : allLikes = 0;
				if (allLikes > 0) allLikes--;
				b.dataset.allLikes = allLikes;
				b.querySelector('[data-wishlist-count]').innerHTML = allLikes;
				let addText = b.querySelector('[data-addtext]');
				let removeText = b.querySelector('[data-removetext]');
				b.dataset.exists = '';
				addText.style.display = 'block';
				removeText.style.display = 'none';
			}
		}
	}

	createLike(currentLikeBox, currentIcon) {
		// if the user is not logged in, just use a cookie instead of user_meta, and update product meta async
		let allLikes
		currentLikeBox.dataset.allLikes ? allLikes = parseInt(currentLikeBox.dataset.allLikes) : allLikes = 0;
		allLikes++;
		currentLikeBox.dataset.allLikes = allLikes;
		currentLikeBox.querySelector('[data-wishlist-count]').innerHTML = allLikes;
		let addText = currentLikeBox.querySelector('[data-addtext]');
		let removeText = currentLikeBox.querySelector('[data-removetext]');

		if (!this.isLoggedIn) {

			if (!this.cookieSet) {
				_setCookie('ap_wp_wishlist', [currentLikeBox.dataset.prodId], 30 * 12);
				currentLikeBox.dataset.exists = 'yes';
				this.cookieSet = [currentLikeBox.dataset.prodId];
				if (this.widgetCount) this.widgetCount.innerHTML = 1;
				currentIcon.classList.remove('pulse');
			}

			// Convert to array if it is not
			if (!Array.isArray(this.cookieSet)) this.cookieSet = this.cookieSet.split(",");

			this.cookieSet.push(currentLikeBox.dataset.prodId);

			this.cookieSet = this.cookieSet.filter((v, i, a) => a.indexOf(v) === i); // de-duplicates array
			_setCookie('ap_wp_wishlist', [this.cookieSet], 30 * 12);
			currentLikeBox.dataset.exists = 'yes';
			currentIcon.classList.remove('pulse');
			if (this.widgetCount) this.widgetCount.innerHTML = this.cookieSet.length;
			addText.style.display = 'none';
			removeText.style.display = 'block';
			this.syncCreate(currentLikeBox);

		}

		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', likeParams.nonce);
			},
			url: likeParams.root_url,
			type: 'POST',
			data: {
				action: 'wishlist_manage_like',
				_nonce: likeParams.nonce,
				'prodid': currentLikeBox.dataset.prodId,
				'userId': likeParams.userId,
				'manageType': 'post'
			},
			success: (res) => {

				if (this.isLoggedIn) {
					currentIcon.classList.remove('pulse');
					currentLikeBox.dataset.exists = 'yes';
					if (this.widgetCount) this.widgetCount.innerHTML = res;
					addText.style.display = 'none';
					removeText.style.display = 'block';
					this.syncCreate(currentLikeBox);
				}

			},
			error: (res) => {
				console.log(res)
			}
		});
	}

	deleteLike(currentLikeBox, currentIcon) {
		let allLikes
		currentLikeBox.dataset.allLikes ? allLikes = parseInt(currentLikeBox.dataset.allLikes) : allLikes = 0;
		if (allLikes > 0) allLikes--;
		currentLikeBox.dataset.allLikes = allLikes;
		currentLikeBox.querySelector('[data-wishlist-count]').innerHTML = allLikes;
		let addText = currentLikeBox.querySelector('[data-addtext]');
		let removeText = currentLikeBox.querySelector('[data-removetext]');

		if (!this.isLoggedIn) {

			// Convert to array if it is not
			if (!Array.isArray(this.cookieSet)) this.cookieSet = this.cookieSet.split(",");
			// Remove the current like
			this.cookieSet = this.cookieSet.filter(i => i !== currentLikeBox.dataset.prodId);
			_setCookie('ap_wp_wishlist', [this.cookieSet], 30 * 12);
			currentIcon.classList.remove('pulse');
			if (this.widgetCount) this.widgetCount.innerHTML = this.cookieSet.length;

			currentLikeBox.dataset.removeDiv == 'yes' ? currentLikeBox.closest('li.product').remove() : currentLikeBox.dataset.exists = '';
			addText.style.display = 'block';
			removeText.style.display = 'none';
			this.syncDelete(currentLikeBox);
		}

		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', likeParams.nonce);
			},
			url: likeParams.root_url,
			type: 'POST',
			data: {
				action: 'wishlist_manage_like',
				_nonce: likeParams.nonce,
				'prodid': currentLikeBox.dataset.prodId,
				'userId': likeParams.userId,
				'manageType': 'delete'
			},
			success: (res) => {

				if (this.isLoggedIn) {
					currentIcon.classList.remove('pulse');
					if (this.widgetCount) this.widgetCount.innerHTML = res;
					currentLikeBox.dataset.removeDiv == 'yes' ? currentLikeBox.closest('li.product').remove() : currentLikeBox.dataset.exists = '';
					addText.style.display = 'block';
					removeText.style.display = 'none';
					this.syncDelete(currentLikeBox);
				}

			},
			error: (res) => {
				console.log(res)
			}
		});
	}
}

export default Wishlist;
