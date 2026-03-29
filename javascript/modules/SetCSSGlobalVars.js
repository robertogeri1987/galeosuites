class SetCSSGlobalVars {

	constructor() {
		this.setViewHeight();
		window.addEventListener("resize", this.setViewHeight);
	}

	setViewHeight() {
		const vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty("--vh", `${vh}px`);
		const scrollbarWidth = window.innerWidth - document.body.clientWidth
		document.body.style.setProperty("--scrollbarWidth", `${scrollbarWidth}px`)
	}

}

export default SetCSSGlobalVars;
