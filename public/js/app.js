/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 140);
/******/ })
/************************************************************************/
/******/ ({

/***/ 10:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 140:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(5);
__webpack_require__(9);
__webpack_require__(10);
module.exports = __webpack_require__(8);


/***/ }),

/***/ 2:
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ 5:
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {

/*
 LiveData objects refresh data automatically
 - source_uri provides the data source. Normally an API
 - interval sets the interval between data pulls
 - type sets the type of data we're fetching and defines
 what the callback method will do with new data
 - target defines the element where the callback
 function will put the new data
 */

var liveDataObjects = [];

global.LiveData = function (source_uri, interval, callback, target) {
    liveDataObjects += this;
    this.source_uri = source_uri;
    this.interval = interval * 1000;
    this.callback = callback;
    this.target = target;
    this.runner = null;
    this.refs = new Array();
    return this;
};

LiveData.prototype.run = function () {
    var ld = this;
    ld.fetchData(ld);
    this.runner = setInterval(function () {
        ld.fetchData(ld);
    }, this.interval);
};

LiveData.prototype.fetchData = function (ld) {
    $.ajax({
        url: ld.source_uri,
        type: 'GET',
        error: function error() {
            ld.callback(false, 'error', ld);
        },
        success: function success(data) {
            ld.callback(true, data, ld);
        }
    });
};

LiveData.prototype.cleanupRefs = function () {
    $.each(this.refs, function () {
        this.remove();
    });

    this.refs = new Array();
};

LiveData.prototype.stop = function () {
    clearInterval(this.runner);
};

window.submit_form = function (e) {
    var _callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : undefined;

    console.log(e);
    e.preventDefault();

    if ($(e.target).data('prevent-submit-on-enter') === true && e.keyCode === 13) {
        return false;
    }

    var btns = $('button[type=submit]:enabled');
    btns.attr('disabled', 'disabled');
    var callback = $(e.target).data('callback') || _callback;
    var redirect_success = $(e.target).data('redirect-success');
    /*
     * Fix for empty data when using
     * PUT with FormData
     */
    var data = null;
    if ($(e.target).data('user-formdata')) {
        data = new FormData(this);
    } else {
        data = $(e.target).serialize();
    }

    var content_type = 'application/x-www-form-urlencoded';
    if ($(e.target).data('ignore-enctype')) {
        content_type = false;
    }

    var form_id = $(e.target).prop('id');
    var form = this;

    return $.ajax({
        url: $(e.target).prop('action'),
        type: $(e.target).data('method'),
        data: data,
        cache: false,
        contentType: content_type,
        processData: false,
        xhr: function xhr() {
            // Custom XMLHttpRequest
            var xhr = $.ajaxSettings.xhr();
            if (xhr.upload) {
                // Check if upload property exists
                xhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
            }
            return xhr;
        },
        success: function success(data) {
            btns.removeAttr('disabled');

            if (!$(e.target).data('no-confirm')) {
                window.notification('<i class="material-icons">check</i>', 'teal darken-1 text-white');
            }

            window.eventHubVue.$emit('FormSubmitReturnedSuccess', {
                'source_id': form_id,
                'data': data
            });
            if (callback !== undefined) {
                if (typeof callback === 'string') {
                    window[callback](data);
                } else {
                    callback(data);
                }
            }

            if (redirect_success !== undefined) {
                if (redirect_success == 'auto') {
                    if (data.meta.redirect.uri !== undefined) {
                        window.setTimeout(function () {
                            window.location.replace(data.meta.redirect.uri);
                        }, 1000);
                    }
                } else {
                    window.setTimeout(function () {
                        window.location.replace(redirect_success);
                    }, 1000);
                }
            }
        },
        error: function error(data) {
            btns.removeAttr('disabled');
            var msg = 'Unknown';
            if (data.responseJSON !== undefined) msg = data.responseJSON.error.message;
            window.notification('Error ' + data.status + '<br />' + data.statusText + ':<br />' + msg, 'orange darken-2 text-white');
        }
    });
};

var progressHandlingFunction = function progressHandlingFunction(e) {
    if (e.lengthComputable) {
        $('.form-progress-bar').data('valuenow', e.loaded / e.total * 100);
        $('.form-progress-bar').css('width', e.loaded / e.total * 100 + '%');
    }
};

window.notification = function (text, cssClass, length) {
    length = length || 5000;
    cssClass = cssClass || null;
    Materialize.toast(text, length, cssClass);
};

window.runPage = function () {
    $('select').material_select();

    $('.dropdown-button').dropdown();

    $('.button-collapse').sideNav();

    $('.tooltipped').tooltip({ delay: 50 });

    // SideNav collapse active
    var active_headers = $('.collapsible-body ul li.active').parent().parent().parent();
    active_headers.addClass('active');
    active_headers.children('.collapsible-body').css('display', 'block');

    $('form').submit(window.submit_form);

    $('[data-livedata="true"]').each(function () {
        new LiveData($(this).data('livedatasource'), $(this).data('livedatainterval'), domCallbacks[$(this).data('livedatacallback')], this).run();
    });

    /* Enable tabs to update url with tab hash and
     * force rerender of masonry grids */
    $('ul.tabs').tabs({
        onShow: function onShow(event, ui) {
            location.hash = $(this).attr('href');
            var grid = $('.masonry-grid');
            if (grid !== undefined) {
                grid.masonry('layout');
                grid.masonry('reloadItems');
            }
            window.eventHubVue.$emit('ForceRerender');

            /*
             * Avoid scrolling when anchor is in url
             * @TODO: find better solution, it's quite sloppy
             */
            setTimeout(function () {
                window.scrollTo(0, 0);
            }, 1);
        }
    });
};

String.prototype.toUnderscoreCase = function () {
    return this.replace(/\.?([A-Z])/g, function (x, y) {
        return "_" + y.toLowerCase();
    }).replace(/^_/, "");
};

Date.prototype.toYmd = function () {
    var month = this.getMonth() + 1;
    var date = this.getDate();
    return this.getFullYear() + '-' + (month > 9 ? month : '0' + month) + '-' + (date > 9 ? date : '0' + date);
};
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ }),

/***/ 8:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 9:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })

/******/ });