const svgClassListPolyfill = []

if (!Element.prototype.closest) {
    svgClassListPolyfill.push(import(/* webpackChunkName: "polyfill-svg-classlist" */ 'svg-classlist-polyfill'));
}

export default svgClassListPolyfill;
