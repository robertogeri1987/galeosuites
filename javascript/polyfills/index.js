import closestPolyfill from './closest-polyfill';
import fetchPolyfill from './fetch-polyfill';
import svgClassListPolyfill from './svg-classlist-polyfill';

export default [
  ...closestPolyfill,
  ...fetchPolyfill,
  ...svgClassListPolyfill
]
