/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunk"] = self["webpackChunk"] || []).push([["polyfill-svg-classlist"],{

/***/ "./node_modules/svg-classlist-polyfill/polyfill.js":
/*!*********************************************************!*\
  !*** ./node_modules/svg-classlist-polyfill/polyfill.js ***!
  \*********************************************************/
/***/ (() => {

eval("// Inject polyfill if classList not supported for SVG elements.\nif (!('classList' in SVGElement.prototype)) {\n  Object.defineProperty(SVGElement.prototype, 'classList', {\n    get: function get() {\n      var _this = this\n\n      return {\n        contains: function contains(className) {\n          return _this.className.baseVal.split(' ').indexOf(className) !== -1\n        },\n        add: function add(className) {\n          var newClass = (_this.getAttribute('class') + ' ' + className).trim()\n          return _this.setAttribute('class', newClass)\n        },\n        remove: function remove(className) {\n          var classes = _this.getAttribute('class') || ''\n          var regex = new RegExp('(?:^|\\\\s)' + className + '(?!\\\\S)', 'g')\n          classes = classes.replace(regex, '').trim()\n          _this.setAttribute('class', classes)\n        },\n        toggle: function toggle(className) {\n          if (this.contains(className)) {\n            this.remove(className)\n          } else {\n            this.add(className)\n          }\n        },\n      }\n    },\n  })\n}\n\n\n//# sourceURL=webpack:///./node_modules/svg-classlist-polyfill/polyfill.js?");

/***/ })

}]);