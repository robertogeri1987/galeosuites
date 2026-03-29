const fetchPolyfill = []

if (!window.fetch) {
  fetchPolyfill.push(import(/* webpackChunkName: "polyfill-fetch" */ 'whatwg-fetch'))
}

export default fetchPolyfill;
