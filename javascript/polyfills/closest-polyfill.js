const closestPolyfill = []

if (!Element.prototype.closest) {
    closestPolyfill.push(import(/* webpackChunkName: "polyfill-closest" */ 'element-closest-polyfill'));
}

export default closestPolyfill;
