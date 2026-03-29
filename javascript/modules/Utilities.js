// Utility to set cookies
export const _setCookie = (name, value, days) => {
	let expires;
	let date;
	if (days) {
		date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toGMTString();
	}
	else expires = "";
	document.cookie = name + "=" + value + expires + "; path=/";
}

// Utility to get cookies
export const _getCookie = (name) => {
	let dc = document.cookie;
	let prefix = name + "=";
	let begin = dc.indexOf("; " + prefix);
	let end;
	if (begin == -1) {
		begin = dc.indexOf(prefix);
		if (begin != 0) return null;
	}
	else {
		begin += 2;
		end = document.cookie.indexOf(";", begin);
		if (end == -1) {
			end = dc.length;
		}
	}

	return decodeURI(dc.substring(begin + prefix.length, end));
}

// Utility to find out the display property of an element by ID
export const _getDisplayValueById = ($id) => {
	let element = document.getElementById($id),
		style = window.getComputedStyle(element);
	return style.getPropertyValue('display');
}

// Utility to find out whether we are on mobile or desktop based on the menu toggler button
export const _isDesktop = () => {
	let element = document.querySelector('[data-hamburger]'),
		style = window.getComputedStyle(element);
	return (style.getPropertyValue('display') === 'none') ? true : false;
}

// Utility to find out whether we are on the medium breakpoint
export const _isMdBreakpoint = () => {
	let element = document.getElementById('md-breakpoint-check'),
		style = window.getComputedStyle(element);
	return (style.getPropertyValue('display') === 'none') ? true : false;
}

// Utility to find out whether we are on the medium breakpoint
export const _isLgBreakpoint = () => {
	let element = document.getElementById('lg-breakpoint-check'),
		style = window.getComputedStyle(element);
	return (style.getPropertyValue('display') === 'none') ? true : false;
}

// Utility to find out whether we are on the xl breakpoint
export const _isXlBreakpoint = () => {
	let element = document.getElementById('xl-breakpoint-check'),
		style = window.getComputedStyle(element);
	return (style.getPropertyValue('display') === 'none') ? true : false;
}

// Utility to remove events from an element
export const _removeEvents = (element) => {
	// clone the button to remove all events
	let newElement = element.cloneNode(true);
	element.parentNode.replaceChild(newElement, element);
	return newElement;
}

// Utility to find the closest element with the same class
export const _closestByClass = (el, clazz) => {
	while (el.tagName.toLowerCase() !== "html") {
		if (el.classList.length > 0 && el.classList.contains(clazz)) {
			return true
		}
		else {
			el = el.parentNode;
		}
	}
	return false;
};
