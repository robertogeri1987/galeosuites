/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunk"] = self["webpackChunk"] || []).push([["polyfill-closest"],{

/***/ "./node_modules/element-closest-polyfill/index.js":
/*!********************************************************!*\
  !*** ./node_modules/element-closest-polyfill/index.js ***!
  \********************************************************/
/***/ (() => {

eval("if (typeof Element !== \"undefined\") {\n    if (!Element.prototype.matches) {\n        Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;\n    }\n\n    if (!Element.prototype.closest) {\n        Element.prototype.closest = function (s) {\n            var el = this;\n\n            do {\n                if (el.matches(s)) return el;\n                el = el.parentElement || el.parentNode;\n            } while (el !== null && el.nodeType === 1);\n            \n            return null;\n        };\n    }\n}\n\n\n//# sourceURL=webpack:///./node_modules/element-closest-polyfill/index.js?");

/***/ })

}]);