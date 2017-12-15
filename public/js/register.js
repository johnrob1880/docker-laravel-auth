webpackJsonp([1],{

/***/ 1:
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// this module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate
    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ 14:
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),

/***/ 15:
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(51)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction) {
  isProduction = _isProduction

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[data-vue-ssr-id~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),

/***/ 46:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(47);


/***/ }),

/***/ 47:
/***/ (function(module, exports, __webpack_require__) {

var Vue = __webpack_require__(2);

var LoadingIcon = __webpack_require__(48);
var BusyPanel = __webpack_require__(54);
var LoremIpsum = __webpack_require__(72);

new Vue({
    el: '#register',
    components: {
        'loading-icon': LoadingIcon,
        'busy-panel': BusyPanel,
        'lorem-ipsum': LoremIpsum
    },
    data: function data() {
        return {
            isLoading: false,
            status: ''
        };
    },
    mounted: function mounted() {
        var self = this;

        window.onSubmit = function () {
            self.isLoading = true;
            var $form = $('#registerForm');

            $form.find(':submit').attr('disabled', 'disabled');
            $form.find(':submit').html($form.find('#working').html());
            $form.submit();
        };

        // $('form').submit(function (event) {
        //     event.preventDefault()

        //     //$("html, body").animate({ scrollTop: 0 }, "slow");

        //     self.isLoading = true

        //     if (!$(this).hasClass('submitted')) {
        //         $(this).addClass('submitted')
        //         $(this).find(':submit').attr('disabled', 'disabled')
        //         this.submit()
        //     }
        // });

        window.Holder && Holder.addTheme("default", {
            bg: "#F2F2F2",
            fg: "#CCCCCC",
            size: 8,
            font: "Raleway",
            fontweight: "normal"
        });
    },

    computed: {},
    methods: {
        busy: function busy() {
            this.isLoading = true;
        }
    }
});

/***/ }),

/***/ 48:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(49)
}
var normalizeComponent = __webpack_require__(1)
/* script */
var __vue_script__ = __webpack_require__(52)
/* template */
var __vue_template__ = __webpack_require__(53)
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-91efa72e"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\LoadingIcon.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] LoadingIcon.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-91efa72e", Component.options)
  } else {
    hotAPI.reload("data-v-91efa72e", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 49:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(50);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(15)("78d45488", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-91efa72e\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./LoadingIcon.vue", function() {
     var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-91efa72e\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./LoadingIcon.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 50:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(14)(undefined);
// imports


// module
exports.push([module.i, "\n@keyframes lds-spinner-data-v-91efa72e {\n0% {\r\n    opacity: 1;\n}\n100% {\r\n    opacity: 0;\n}\n}\n@-webkit-keyframes lds-spinner-data-v-91efa72e {\n0% {\r\n    opacity: 1;\n}\n100% {\r\n    opacity: 0;\n}\n}\n.lds-spinner[data-v-91efa72e] {\r\n  position: relative;\n}\n.lds-spinner div[data-v-91efa72e] {\r\n  left: 94px;\r\n  top: 48px;\r\n  position: absolute;\r\n  -webkit-animation: lds-spinner-data-v-91efa72e linear 1s infinite;\r\n  animation: lds-spinner-data-v-91efa72e linear 1s infinite;\r\n  background: #fdfdfd;\r\n  width: 12px;\r\n  height: 24px;\r\n  border-radius: 40%;\r\n  -webkit-transform-origin: 6px 52px;\r\n  transform-origin: 6px 52px;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(1) {\r\n  -webkit-transform: rotate(0deg);\r\n  transform: rotate(0deg);\r\n  -webkit-animation-delay: -0.916666666666667s;\r\n  animation-delay: -0.916666666666667s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(2) {\r\n  -webkit-transform: rotate(30deg);\r\n  transform: rotate(30deg);\r\n  -webkit-animation-delay: -0.833333333333333s;\r\n  animation-delay: -0.833333333333333s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(3) {\r\n  -webkit-transform: rotate(60deg);\r\n  transform: rotate(60deg);\r\n  -webkit-animation-delay: -0.75s;\r\n  animation-delay: -0.75s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(4) {\r\n  -webkit-transform: rotate(90deg);\r\n  transform: rotate(90deg);\r\n  -webkit-animation-delay: -0.666666666666667s;\r\n  animation-delay: -0.666666666666667s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(5) {\r\n  -webkit-transform: rotate(120deg);\r\n  transform: rotate(120deg);\r\n  -webkit-animation-delay: -0.583333333333333s;\r\n  animation-delay: -0.583333333333333s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(6) {\r\n  -webkit-transform: rotate(150deg);\r\n  transform: rotate(150deg);\r\n  -webkit-animation-delay: -0.5s;\r\n  animation-delay: -0.5s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(7) {\r\n  -webkit-transform: rotate(180deg);\r\n  transform: rotate(180deg);\r\n  -webkit-animation-delay: -0.416666666666667s;\r\n  animation-delay: -0.416666666666667s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(8) {\r\n  -webkit-transform: rotate(210deg);\r\n  transform: rotate(210deg);\r\n  -webkit-animation-delay: -0.333333333333333s;\r\n  animation-delay: -0.333333333333333s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(9) {\r\n  -webkit-transform: rotate(240deg);\r\n  transform: rotate(240deg);\r\n  -webkit-animation-delay: -0.25s;\r\n  animation-delay: -0.25s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(10) {\r\n  -webkit-transform: rotate(270deg);\r\n  transform: rotate(270deg);\r\n  -webkit-animation-delay: -0.166666666666667s;\r\n  animation-delay: -0.166666666666667s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(11) {\r\n  -webkit-transform: rotate(300deg);\r\n  transform: rotate(300deg);\r\n  -webkit-animation-delay: -0.083333333333333s;\r\n  animation-delay: -0.083333333333333s;\n}\n.lds-spinner div[data-v-91efa72e]:nth-child(12) {\r\n  -webkit-transform: rotate(330deg);\r\n  transform: rotate(330deg);\r\n  -webkit-animation-delay: 0s;\r\n  animation-delay: 0s;\n}\n.lds-spinner[data-v-91efa72e] {\r\n  width: 30px !important;\r\n  height: 30px !important;\r\n  -webkit-transform: translate(-15px, -15px) scale(0.15) translate(15px, 15px);\r\n  transform: translate(-15px, -15px) scale(0.15) translate(15px, 15px);\n}\r\n", ""]);

// exports


/***/ }),

/***/ 51:
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),

/***/ 52:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'loading-icon',
  props: {
    color: {
      type: String,
      default: '#ffffff'
    }
  }
});

/***/ }),

/***/ 53:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      staticClass: "lds-css ng-scope",
      staticStyle: { display: "inline-block" }
    },
    [
      _c("div", { staticClass: "lds-spinner" }, [
        _c("div", { style: { background: _vm.color } }),
        _c("div"),
        _c("div"),
        _c("div"),
        _c("div"),
        _c("div"),
        _c("div"),
        _c("div"),
        _c("div"),
        _c("div"),
        _c("div"),
        _c("div")
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-91efa72e", module.exports)
  }
}

/***/ }),

/***/ 54:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(55)
}
var normalizeComponent = __webpack_require__(1)
/* script */
var __vue_script__ = __webpack_require__(57)
/* template */
var __vue_template__ = __webpack_require__(58)
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-d3164fc2"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\BusyPanel.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] BusyPanel.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-d3164fc2", Component.options)
  } else {
    hotAPI.reload("data-v-d3164fc2", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 55:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(56);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(15)("7413171a", content, false);
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-d3164fc2\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./BusyPanel.vue", function() {
     var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-d3164fc2\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./BusyPanel.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 56:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(14)(undefined);
// imports


// module
exports.push([module.i, "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n/* Center the loader */\n#loader[data-v-d3164fc2] {\n    position: absolute;\n    left: 50%;\n    top: 50%;\n    z-index: 1;\n    width: 150px;\n    height: 150px;\n    margin: -75px 0 0 -75px;\n    border: 16px solid #00539b;\n    border-radius: 50%;\n    border-top: 16px solid #0096d7;\n    border-bottom: 16px solid #0096d7;\n    width: 120px;\n    height: 120px;\n    -webkit-animation: spin-data-v-d3164fc2 2s linear infinite;\n    animation: spin-data-v-d3164fc2 2s linear infinite;\n}\n@-webkit-keyframes spin-data-v-d3164fc2 {\n0% { -webkit-transform: rotate(0deg);\n}\n100% { -webkit-transform: rotate(360deg);\n}\n}\n@keyframes spin-data-v-d3164fc2 {\n0% { transform: rotate(0deg);\n}\n100% { transform: rotate(360deg);\n}\n}\n", ""]);

// exports


/***/ }),

/***/ 57:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    name: 'busy-panel',
    props: {
        busy: {
            type: Boolean,
            default: false
        },
        opacity: {
            type: String,
            default: '0'
        },
        color: {
            type: String,
            default: '#3498db'
        }
    },
    computed: {
        getPanelStyle: function getPanelStyle() {
            return {
                'opacity': parseFloat(this.busy ? this.opacity : "1")
            };
        },
        getLoaderStyle: function getLoaderStyle() {
            return {
                'border-top': this.color
            };
        }
    }
});

/***/ }),

/***/ 58:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _vm.busy ? _c("div", { attrs: { id: "loader" } }) : _vm._e(),
    _vm._v(" "),
    _c(
      "div",
      { style: _vm.getPanelStyle, attrs: { id: "panelContent" } },
      [_vm._t("default")],
      2
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-d3164fc2", module.exports)
  }
}

/***/ }),

/***/ 72:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(1)
/* script */
var __vue_script__ = __webpack_require__(73)
/* template */
var __vue_template__ = __webpack_require__(74)
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\LoremIpsum.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key.substr(0, 2) !== "__"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] LoremIpsum.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5721f9e9", Component.options)
  } else {
    hotAPI.reload("data-v-5721f9e9", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 73:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        paragraphs: {
            type: Number,
            default: 1
        }
    },
    mounted: function mounted() {
        console.log('Component mounted.');
    }
});

/***/ }),

/***/ 74:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    _vm._l(_vm.paragraphs, function(i) {
      return _c("p", { key: i, staticClass: "lorem-ipsum" }, [
        _vm._v(
          "\n        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque at lacus rutrum, ornare ipsum eu, sollicitudin libero. Vestibulum nunc justo, mattis sed augue vitae, auctor ultricies lectus. Donec ultricies venenatis sapien, blandit luctus dui aliquam sit amet. Donec ac euismod sem, vel elementum nibh. Donec at nibh turpis. Phasellus fermentum malesuada sagittis. Mauris lacinia tellus nisl, in condimentum sapien interdum vitae.\n    "
        )
      ])
    })
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-5721f9e9", module.exports)
  }
}

/***/ })

},[46]);