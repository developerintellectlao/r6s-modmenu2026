/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/shortcode/blockIcon.js":
/*!************************************!*\
  !*** ./src/shortcode/blockIcon.js ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/escape-html */ "@wordpress/escape-html");
/* harmony import */ var _wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);


const el = _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement;
const icons = {};
icons.spwpcpIcon = el("img", {
  src: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__.escapeAttribute)(sp_wp_carousel_pro.url + "Admin/views/GutenbergBlock/assets/wp-carousel-icon.svg")
});
/* harmony default export */ __webpack_exports__["default"] = (icons);

/***/ }),

/***/ "./src/shortcode/dynamicShortcode.js":
/*!*******************************************!*\
  !*** ./src/shortcode/dynamicShortcode.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/escape-html */ "@wordpress/escape-html");
/* harmony import */ var _wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/**
 * Shortcode select component.
 */



const el = _wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement;

const DynamicShortcodeInput = _ref => {
  let {
    attributes: {
      shortcode
    },
    shortcodeList,
    shortcodeUpdate
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.Fragment, null, el('div', {
    className: 'spwpcp-gutenberg-shortcode editor-styles-wrapper'
  }, el('select', {
    className: 'spwpcp-shortcode-selector',
    onChange: e => shortcodeUpdate(e),
    value: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__.escapeAttribute)(shortcode)
  }, el('option', {
    value: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__.escapeAttribute)('0')
  }, (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__.escapeHTML)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('-- Select a shortcode --', 'wp-carousel-pro'))), shortcodeList.map(shortcode => {
    var title = shortcode.title.length > 35 ? shortcode.title.substring(0, 35) + '.... #(' + shortcode.id + ')' : shortcode.title + ' #(' + shortcode.id + ')';
    return el('option', {
      value: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__.escapeAttribute)(shortcode.id.toString()),
      key: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__.escapeAttribute)(shortcode.id.toString())
    }, (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_0__.escapeHTML)(title));
  }))));
};

/* harmony default export */ __webpack_exports__["default"] = (DynamicShortcodeInput);

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/escape-html":
/*!************************************!*\
  !*** external ["wp","escapeHtml"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["escapeHtml"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _shortcode_blockIcon__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./shortcode/blockIcon */ "./src/shortcode/blockIcon.js");
/* harmony import */ var _shortcode_dynamicShortcode__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./shortcode/dynamicShortcode */ "./src/shortcode/dynamicShortcode.js");
/* harmony import */ var _wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/escape-html */ "@wordpress/escape-html");
/* harmony import */ var _wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_7__);








const ServerSideRender = wp.serverSideRender;
const el = _wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement;
/**
 * Register: aa Gutenberg Block.
 */

(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__.registerBlockType)("sp-wp-carousel-pro/shortcode", {
  title: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("WP Carousel Pro", "wp-carousel-pro")),
  description: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Use WP Carousel to insert a carousel or gallery in your page.", "wp-carousel-pro")),
  icon: _shortcode_blockIcon__WEBPACK_IMPORTED_MODULE_0__["default"].spwpcpIcon,
  category: "common",
  supports: {
    html: true
  },
  edit: props => {
    const {
      attributes,
      setAttributes
    } = props;
    var shortcodeList = sp_wp_carousel_pro.shortcodeList;

    let scriptLoad = shortcodeId => {
      let spwpcpBlockLoaded = false;
      let spwpcpBlockLoadedInterval = setInterval(function () {
        let uniqId = jQuery(".wpcp-wrapper-" + shortcodeId).parents().attr('id');

        if (document.getElementById(uniqId)) {
          //Actual functions goes here
          jQuery.getScript(sp_wp_carousel_pro.loadBxSlider);
          jQuery.getScript(sp_wp_carousel_pro.lazyLoad);
          jQuery.getScript(sp_wp_carousel_pro.loadScript);
          jQuery('#wpcp-preloader-' + shortcodeId).animate({
            opacity: 0
          }, 600).remove();
          jQuery('#sp-wp-carousel-pro-id-' + shortcodeId).animate({
            opacity: 1
          }, 600);
          spwpcpBlockLoaded = true;
          uniqId = '';
        }

        if (spwpcpBlockLoaded) {
          clearInterval(spwpcpBlockLoadedInterval);
        }

        if (0 == shortcodeId) {
          clearInterval(spwpcpBlockLoadedInterval);
        }
      }, 10);
    };

    let updateShortcode = updateShortcode => {
      setAttributes({
        shortcode: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeAttribute)(updateShortcode.target.value)
      });
    };

    let shortcodeUpdate = e => {
      updateShortcode(e);
      let shortcodeId = (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeAttribute)(e.target.value);
      scriptLoad(shortcodeId);
    };

    document.addEventListener('readystatechange', event => {
      if (event.target.readyState === "complete") {
        let shortcodeId = (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeAttribute)(attributes.shortcode);
        scriptLoad(shortcodeId);
      }
    });

    if (attributes.preview) {
      return el("div", {
        className: "spwpcp_shortcode_block_preview_image"
      }, el("img", {
        src: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeAttribute)(sp_wp_carousel_pro.url + "Admin/views/GutenbergBlock/assets/wpc-block-preview.svg")
      }));
    }

    if (shortcodeList.length === 0) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.Fragment, null, el("div", {
        className: "components-placeholder components-placeholder is-large"
      }, el("div", {
        className: "components-placeholder__label"
      }, el("img", {
        className: "block-editor-block-icon",
        src: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeAttribute)(sp_wp_carousel_pro.url + "Admin/views/GutenbergBlock/assets/wp-carousel-icon.svg")
      }), (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("WP Carousel Pro", "wp-carousel-pro"))), el("div", {
        className: "components-placeholder__instructions"
      }, (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("No shortcode found. ", "wp-carousel-pro")), el("a", {
        href: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeAttribute)(sp_wp_carousel_pro.link)
      }, (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Create a shortcode now!", "wp-carousel-pro"))))));
    }

    if (!attributes.shortcode || attributes.shortcode == 0) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_7__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
        title: "Select a shortcode"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_shortcode_dynamicShortcode__WEBPACK_IMPORTED_MODULE_1__["default"], {
        shortcodeList: shortcodeList,
        attributes: attributes,
        shortcodeUpdate: shortcodeUpdate
      })))), el("div", {
        className: "components-placeholder components-placeholder is-large"
      }, el("div", {
        className: "components-placeholder__label"
      }, el("img", {
        className: "block-editor-block-icon",
        src: (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeAttribute)(sp_wp_carousel_pro.url + "Admin/views/GutenbergBlock/assets/wp-carousel-icon.svg")
      }), (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("WP Carousel Pro", "wp-carousel-pro"))), el("div", {
        className: "components-placeholder__instructions"
      }, (0,_wordpress_escape_html__WEBPACK_IMPORTED_MODULE_2__.escapeHTML)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Select a shortcode", "wp-carousel-pro"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_shortcode_dynamicShortcode__WEBPACK_IMPORTED_MODULE_1__["default"], {
        shortcodeList: shortcodeList,
        attributes: attributes,
        shortcodeUpdate: shortcodeUpdate
      })));
    }

    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_7__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelBody, {
      title: "Select a shortcode"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(_shortcode_dynamicShortcode__WEBPACK_IMPORTED_MODULE_1__["default"], {
      shortcodeList: shortcodeList,
      attributes: attributes,
      shortcodeUpdate: shortcodeUpdate
    })))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.createElement)(ServerSideRender, {
      block: "sp-wp-carousel-pro/shortcode",
      attributes: attributes
    }));
  },

  save() {
    // Rendering in PHP
    return null;
  }

});
}();
/******/ })()
;
