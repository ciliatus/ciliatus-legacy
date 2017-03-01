(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
module.exports = { "default": require("core-js/library/fn/json/stringify"), __esModule: true };
},{"core-js/library/fn/json/stringify":2}],2:[function(require,module,exports){
var core  = require('../../modules/_core')
  , $JSON = core.JSON || (core.JSON = {stringify: JSON.stringify});
module.exports = function stringify(it){ // eslint-disable-line no-unused-vars
  return $JSON.stringify.apply($JSON, arguments);
};
},{"../../modules/_core":3}],3:[function(require,module,exports){
var core = module.exports = {version: '2.4.0'};
if(typeof __e == 'number')__e = core; // eslint-disable-line no-undef
},{}],4:[function(require,module,exports){
// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };

},{}],5:[function(require,module,exports){
var Vue // late bind
var map = window.__VUE_HOT_MAP__ = Object.create(null)
var installed = false
var isBrowserify = false
var initHookName = 'beforeCreate'

exports.install = function (vue, browserify) {
  if (installed) return
  installed = true

  Vue = vue
  isBrowserify = browserify

  // compat with < 2.0.0-alpha.7
  if (Vue.config._lifecycleHooks.indexOf('init') > -1) {
    initHookName = 'init'
  }

  exports.compatible = Number(Vue.version.split('.')[0]) >= 2
  if (!exports.compatible) {
    console.warn(
      '[HMR] You are using a version of vue-hot-reload-api that is ' +
      'only compatible with Vue.js core ^2.0.0.'
    )
    return
  }
}

/**
 * Create a record for a hot module, which keeps track of its constructor
 * and instances
 *
 * @param {String} id
 * @param {Object} options
 */

exports.createRecord = function (id, options) {
  var Ctor = null
  if (typeof options === 'function') {
    Ctor = options
    options = Ctor.options
  }
  makeOptionsHot(id, options)
  map[id] = {
    Ctor: Vue.extend(options),
    instances: []
  }
}

/**
 * Make a Component options object hot.
 *
 * @param {String} id
 * @param {Object} options
 */

function makeOptionsHot (id, options) {
  injectHook(options, initHookName, function () {
    map[id].instances.push(this)
  })
  injectHook(options, 'beforeDestroy', function () {
    var instances = map[id].instances
    instances.splice(instances.indexOf(this), 1)
  })
}

/**
 * Inject a hook to a hot reloadable component so that
 * we can keep track of it.
 *
 * @param {Object} options
 * @param {String} name
 * @param {Function} hook
 */

function injectHook (options, name, hook) {
  var existing = options[name]
  options[name] = existing
    ? Array.isArray(existing)
      ? existing.concat(hook)
      : [existing, hook]
    : [hook]
}

function tryWrap (fn) {
  return function (id, arg) {
    try { fn(id, arg) } catch (e) {
      console.error(e)
      console.warn('Something went wrong during Vue component hot-reload. Full reload required.')
    }
  }
}

exports.rerender = tryWrap(function (id, fns) {
  var record = map[id]
  record.Ctor.options.render = fns.render
  record.Ctor.options.staticRenderFns = fns.staticRenderFns
  record.instances.slice().forEach(function (instance) {
    instance.$options.render = fns.render
    instance.$options.staticRenderFns = fns.staticRenderFns
    instance._staticTrees = [] // reset static trees
    instance.$forceUpdate()
  })
})

exports.reload = tryWrap(function (id, options) {
  makeOptionsHot(id, options)
  var record = map[id]
  record.Ctor.extendOptions = options
  var newCtor = Vue.extend(options)
  record.Ctor.options = newCtor.options
  record.Ctor.cid = newCtor.cid
  if (newCtor.release) {
    // temporary global mixin strategy used in < 2.0.0-alpha.6
    newCtor.release()
  }
  record.instances.slice().forEach(function (instance) {
    if (instance.$parent) {
      instance.$parent.$forceUpdate()
    } else {
      console.warn('Root or manually mounted instance modified. Full reload required.')
    }
  })
})

},{}],6:[function(require,module,exports){
(function (process){
/*!
 * vue-i18n v4.7.3
 * (c) 2016 kazuya kawaguchi
 * Released under the MIT License.
 */
'use strict';

var babelHelpers = {};
babelHelpers.typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) {
  return typeof obj;
} : function (obj) {
  return obj && typeof Symbol === "function" && obj.constructor === Symbol ? "symbol" : typeof obj;
};
babelHelpers;

/**
 * warn
 *
 * @param {String} msg
 * @param {Error} [err]
 *
 */

function warn(msg, err) {
  if (window.console) {
    console.warn('[vue-i18n] ' + msg);
    if (err) {
      console.warn(err.stack);
    }
  }
}

function Asset (Vue, langVM) {
  /**
   * Register or retrieve a global locale definition.
   *
   * @param {String} id
   * @param {Object | Function | Promise} definition
   * @param {Function} cb
   */

  Vue.locale = function (id, definition, cb) {
    if (definition === undefined) {
      // gettter
      return langVM.locales[id];
    } else {
      // setter
      if (definition === null) {
        langVM.locales[id] = undefined;
        delete langVM.locales[id];
      } else {
        setLocale(id, definition, function (locale) {
          if (locale) {
            langVM.locales[id] = locale;
          } else {
            warn('failed set `' + id + '` locale');
          }
          cb && cb();
        });
      }
    }
  };
}

function setLocale(id, definition, cb) {
  var _this = this;

  if ((typeof definition === 'undefined' ? 'undefined' : babelHelpers.typeof(definition)) === 'object') {
    // sync
    cb(definition);
  } else {
    (function () {
      var future = definition.call(_this);
      if (typeof future === 'function') {
        if (future.resolved) {
          // cached
          cb(future.resolved);
        } else if (future.requested) {
          // pool callbacks
          future.pendingCallbacks.push(cb);
        } else {
          (function () {
            future.requested = true;
            var cbs = future.pendingCallbacks = [cb];
            future(function (locale) {
              // resolve
              future.resolved = locale;
              for (var i = 0, l = cbs.length; i < l; i++) {
                cbs[i](locale);
              }
            }, function () {
              // reject
              cb();
            });
          })();
        }
      } else if (isPromise(future)) {
        // promise
        future.then(function (locale) {
          // resolve
          cb(locale);
        }, function () {
          // reject
          cb();
        }).catch(function (err) {
          console.error(err);
          cb();
        });
      }
    })();
  }
}

/**
 * Forgiving check for a promise
 *
 * @param {Object} p
 * @return {Boolean}
 */

function isPromise(p) {
  return p && typeof p.then === 'function';
}

function Override (Vue, langVM, version) {
  function update(vm) {
    if (version > 1) {
      vm.$forceUpdate();
    } else {
      var i = vm._watchers.length;
      while (i--) {
        vm._watchers[i].update(true); // shallow updates
      }
    }
  }

  // override _init
  var init = Vue.prototype._init;
  Vue.prototype._init = function (options) {
    var _this = this;

    init.call(this, options);

    if (!this.$parent) {
      // root
      this.$lang = langVM;
      this._langUnwatch = this.$lang.$watch('$data', function (val, old) {
        update(_this);
      }, { deep: true });
    }
  };

  // override _destroy
  var destroy = Vue.prototype._destroy;
  Vue.prototype._destroy = function () {
    if (!this.$parent && this._langUnwatch) {
      this._langUnwatch();
      this._langUnwatch = null;
      this.$lang = null;
    }

    destroy.apply(this, arguments);
  };
}

/**
 * Observer
 */

var Watcher = void 0;
/**
 * getWatcher
 *
 * @param {Vue} vm
 * @return {Watcher}
 */

function getWatcher(vm) {
  if (!Watcher) {
    var unwatch = vm.$watch('__watcher__', function (a) {});
    Watcher = vm._watchers[0].constructor;
    unwatch();
  }
  return Watcher;
}

var Dep = void 0;
/**
 * getDep
 *
 * @param {Vue} vm
 * @return {Dep}
 */

function getDep(vm) {
  if (!Dep && vm && vm._data && vm._data.__ob__ && vm._data.__ob__.dep) {
    Dep = vm._data.__ob__.dep.constructor;
  }
  return Dep;
}

var fallback = void 0; // fallback lang
var missingHandler = null; // missing handler
var i18nFormatter = null; // custom formatter

function Config (Vue, langVM, lang) {
  var bind = Vue.util.bind;

  var Watcher = getWatcher(langVM);
  var Dep = getDep(langVM);

  function makeComputedGetter(getter, owner) {
    var watcher = new Watcher(owner, getter, null, {
      lazy: true
    });

    return function computedGetter() {
      watcher.dirty && watcher.evaluate();
      Dep && Dep.target && watcher.depend();
      return watcher.value;
    };
  }

  // define Vue.config.lang configration
  Object.defineProperty(Vue.config, 'lang', {
    enumerable: true,
    configurable: true,
    get: makeComputedGetter(function () {
      return langVM.lang;
    }, langVM),
    set: bind(function (val) {
      langVM.lang = val;
    }, langVM)
  });

  // define Vue.config.fallbackLang configration
  fallback = lang;
  Object.defineProperty(Vue.config, 'fallbackLang', {
    enumerable: true,
    configurable: true,
    get: function get() {
      return fallback;
    },
    set: function set(val) {
      fallback = val;
    }
  });

  // define Vue.config.missingHandler configration
  Object.defineProperty(Vue.config, 'missingHandler', {
    enumerable: true,
    configurable: true,
    get: function get() {
      return missingHandler;
    },
    set: function set(val) {
      missingHandler = val;
    }
  });

  // define Vue.config.i18Formatter configration
  Object.defineProperty(Vue.config, 'i18nFormatter', {
    enumerable: true,
    configurable: true,
    get: function get() {
      return i18nFormatter;
    },
    set: function set(val) {
      i18nFormatter = val;
    }
  });
}

/**
 * utilites
 */

/**
 * isNil
 *
 * @param {*} val
 * @return Boolean
 */
function isNil(val) {
  return val === null || val === undefined;
}

/**
 *  String format template
 *  - Inspired:
 *    https://github.com/Matt-Esch/string-template/index.js
 */

var RE_NARGS = /(%|)\{([0-9a-zA-Z_]+)\}/g;

function Format (Vue) {
  var hasOwn = Vue.util.hasOwn;

  /**
   * template
   *
   * @param {String} string
   * @param {Array} ...args
   * @return {String}
   */

  function template(string) {
    for (var _len = arguments.length, args = Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      args[_key - 1] = arguments[_key];
    }

    if (args.length === 1 && babelHelpers.typeof(args[0]) === 'object') {
      args = args[0];
    } else {
      args = {};
    }

    if (!args || !args.hasOwnProperty) {
      args = {};
    }

    return string.replace(RE_NARGS, function (match, prefix, i, index) {
      var result = void 0;

      if (string[index - 1] === '{' && string[index + match.length] === '}') {
        return i;
      } else {
        result = hasOwn(args, i) ? args[i] : match;
        if (isNil(result)) {
          return '';
        }

        return result;
      }
    });
  }

  return template;
}

/**
 *  Path paerser
 *  - Inspired:  
 *    Vue.js Path parser
 */

// cache
var pathCache = Object.create(null);

// actions
var APPEND = 0;
var PUSH = 1;
var INC_SUB_PATH_DEPTH = 2;
var PUSH_SUB_PATH = 3;

// states
var BEFORE_PATH = 0;
var IN_PATH = 1;
var BEFORE_IDENT = 2;
var IN_IDENT = 3;
var IN_SUB_PATH = 4;
var IN_SINGLE_QUOTE = 5;
var IN_DOUBLE_QUOTE = 6;
var AFTER_PATH = 7;
var ERROR = 8;

var pathStateMachine = [];

pathStateMachine[BEFORE_PATH] = {
  'ws': [BEFORE_PATH],
  'ident': [IN_IDENT, APPEND],
  '[': [IN_SUB_PATH],
  'eof': [AFTER_PATH]
};

pathStateMachine[IN_PATH] = {
  'ws': [IN_PATH],
  '.': [BEFORE_IDENT],
  '[': [IN_SUB_PATH],
  'eof': [AFTER_PATH]
};

pathStateMachine[BEFORE_IDENT] = {
  'ws': [BEFORE_IDENT],
  'ident': [IN_IDENT, APPEND]
};

pathStateMachine[IN_IDENT] = {
  'ident': [IN_IDENT, APPEND],
  '0': [IN_IDENT, APPEND],
  'number': [IN_IDENT, APPEND],
  'ws': [IN_PATH, PUSH],
  '.': [BEFORE_IDENT, PUSH],
  '[': [IN_SUB_PATH, PUSH],
  'eof': [AFTER_PATH, PUSH]
};

pathStateMachine[IN_SUB_PATH] = {
  "'": [IN_SINGLE_QUOTE, APPEND],
  '"': [IN_DOUBLE_QUOTE, APPEND],
  '[': [IN_SUB_PATH, INC_SUB_PATH_DEPTH],
  ']': [IN_PATH, PUSH_SUB_PATH],
  'eof': ERROR,
  'else': [IN_SUB_PATH, APPEND]
};

pathStateMachine[IN_SINGLE_QUOTE] = {
  "'": [IN_SUB_PATH, APPEND],
  'eof': ERROR,
  'else': [IN_SINGLE_QUOTE, APPEND]
};

pathStateMachine[IN_DOUBLE_QUOTE] = {
  '"': [IN_SUB_PATH, APPEND],
  'eof': ERROR,
  'else': [IN_DOUBLE_QUOTE, APPEND]
};

/**
 * Check if an expression is a literal value.
 *
 * @param {String} exp
 * @return {Boolean}
 */

var literalValueRE = /^\s?(true|false|-?[\d.]+|'[^']*'|"[^"]*")\s?$/;
function isLiteral(exp) {
  return literalValueRE.test(exp);
}

/**
 * Strip quotes from a string
 *
 * @param {String} str
 * @return {String | false}
 */

function stripQuotes(str) {
  var a = str.charCodeAt(0);
  var b = str.charCodeAt(str.length - 1);
  return a === b && (a === 0x22 || a === 0x27) ? str.slice(1, -1) : str;
}

/**
 * Determine the type of a character in a keypath.
 *
 * @param {Char} ch
 * @return {String} type
 */

function getPathCharType(ch) {
  if (ch === undefined) {
    return 'eof';
  }

  var code = ch.charCodeAt(0);

  switch (code) {
    case 0x5B: // [
    case 0x5D: // ]
    case 0x2E: // .
    case 0x22: // "
    case 0x27: // '
    case 0x30:
      // 0
      return ch;

    case 0x5F: // _
    case 0x24: // $
    case 0x2D:
      // -
      return 'ident';

    case 0x20: // Space
    case 0x09: // Tab
    case 0x0A: // Newline
    case 0x0D: // Return
    case 0xA0: // No-break space
    case 0xFEFF: // Byte Order Mark
    case 0x2028: // Line Separator
    case 0x2029:
      // Paragraph Separator
      return 'ws';
  }

  // a-z, A-Z
  if (code >= 0x61 && code <= 0x7A || code >= 0x41 && code <= 0x5A) {
    return 'ident';
  }

  // 1-9
  if (code >= 0x31 && code <= 0x39) {
    return 'number';
  }

  return 'else';
}

/**
 * Format a subPath, return its plain form if it is
 * a literal string or number. Otherwise prepend the
 * dynamic indicator (*).
 *
 * @param {String} path
 * @return {String}
 */

function formatSubPath(path) {
  var trimmed = path.trim();
  // invalid leading 0
  if (path.charAt(0) === '0' && isNaN(path)) {
    return false;
  }

  return isLiteral(trimmed) ? stripQuotes(trimmed) : '*' + trimmed;
}

/**
 * Parse a string path into an array of segments
 *
 * @param {String} path
 * @return {Array|undefined}
 */

function parse(path) {
  var keys = [];
  var index = -1;
  var mode = BEFORE_PATH;
  var subPathDepth = 0;
  var c = void 0,
      newChar = void 0,
      key = void 0,
      type = void 0,
      transition = void 0,
      action = void 0,
      typeMap = void 0;

  var actions = [];

  actions[PUSH] = function () {
    if (key !== undefined) {
      keys.push(key);
      key = undefined;
    }
  };

  actions[APPEND] = function () {
    if (key === undefined) {
      key = newChar;
    } else {
      key += newChar;
    }
  };

  actions[INC_SUB_PATH_DEPTH] = function () {
    actions[APPEND]();
    subPathDepth++;
  };

  actions[PUSH_SUB_PATH] = function () {
    if (subPathDepth > 0) {
      subPathDepth--;
      mode = IN_SUB_PATH;
      actions[APPEND]();
    } else {
      subPathDepth = 0;
      key = formatSubPath(key);
      if (key === false) {
        return false;
      } else {
        actions[PUSH]();
      }
    }
  };

  function maybeUnescapeQuote() {
    var nextChar = path[index + 1];
    if (mode === IN_SINGLE_QUOTE && nextChar === "'" || mode === IN_DOUBLE_QUOTE && nextChar === '"') {
      index++;
      newChar = '\\' + nextChar;
      actions[APPEND]();
      return true;
    }
  }

  while (mode != null) {
    index++;
    c = path[index];

    if (c === '\\' && maybeUnescapeQuote()) {
      continue;
    }

    type = getPathCharType(c);
    typeMap = pathStateMachine[mode];
    transition = typeMap[type] || typeMap['else'] || ERROR;

    if (transition === ERROR) {
      return; // parse error
    }

    mode = transition[0];
    action = actions[transition[1]];
    if (action) {
      newChar = transition[2];
      newChar = newChar === undefined ? c : newChar;
      if (action() === false) {
        return;
      }
    }

    if (mode === AFTER_PATH) {
      keys.raw = path;
      return keys;
    }
  }
}

/**
 * External parse that check for a cache hit first
 *
 * @param {String} path
 * @return {Array|undefined}
 */

function parsePath(path) {
  var hit = pathCache[path];
  if (!hit) {
    hit = parse(path);
    if (hit) {
      pathCache[path] = hit;
    }
  }
  return hit;
}

function Path (Vue) {
  var _Vue$util = Vue.util;
  var isObject = _Vue$util.isObject;
  var isPlainObject = _Vue$util.isPlainObject;
  var hasOwn = _Vue$util.hasOwn;


  function empty(target) {
    if (target === null || target === undefined) {
      return true;
    }

    if (Array.isArray(target)) {
      if (target.length > 0) {
        return false;
      }
      if (target.length === 0) {
        return true;
      }
    } else if (isPlainObject(target)) {
      /* eslint-disable prefer-const */
      for (var key in target) {
        if (hasOwn(target, key)) {
          return false;
        }
      }
      /* eslint-enable prefer-const */
    }

    return true;
  }

  /**
   * Get value from path string
   *
   * @param {Object} obj
   * @param {String} path
   * @return value
   */

  function getValue(obj, path) {
    if (!isObject(obj)) {
      return null;
    }

    var paths = parsePath(path);
    if (empty(paths)) {
      return null;
    }

    var length = paths.length;
    var ret = null;
    var last = obj;
    var i = 0;
    while (i < length) {
      var value = last[paths[i]];
      if (value === undefined) {
        last = null;
        break;
      }
      last = value;
      i++;
    }

    ret = last;
    return ret;
  }

  return getValue;
}

/**
 * extend
 *
 * @param {Vue} Vue
 * @return {Vue}
 */

function Extend (Vue) {
  var _Vue$util = Vue.util;
  var isObject = _Vue$util.isObject;
  var bind = _Vue$util.bind;

  var format = Format(Vue);
  var getValue = Path(Vue);

  function parseArgs() {
    for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    var lang = Vue.config.lang;
    var fallback = Vue.config.fallbackLang;

    if (args.length === 1) {
      if (isObject(args[0]) || Array.isArray(args[0])) {
        args = args[0];
      } else if (typeof args[0] === 'string') {
        lang = args[0];
      }
    } else if (args.length === 2) {
      if (typeof args[0] === 'string') {
        lang = args[0];
      }
      if (isObject(args[1]) || Array.isArray(args[1])) {
        args = args[1];
      }
    }

    return { lang: lang, fallback: fallback, params: args };
  }

  function interpolate(locale, key, args) {
    if (!locale) {
      return null;
    }

    var val = getValue(locale, key);
    if (Array.isArray(val)) {
      return val;
    }
    if (isNil(val)) {
      val = locale[key];
    }
    if (isNil(val)) {
      return null;
    }

    // Check for the existance of links within the translated string
    if (val.indexOf('@:') >= 0) {
      // Match all the links within the local
      // We are going to replace each of
      // them with its translation
      var matches = val.match(/(@:[\w|.]+)/g);
      for (var idx in matches) {
        var link = matches[idx];
        // Remove the leading @:
        var linkPlaceholder = link.substr(2);
        // Translate the link
        var translatedstring = interpolate(locale, linkPlaceholder, args);
        // Replace the link with the translated string
        val = val.replace(link, translatedstring);
      }
    }

    return !args ? val : Vue.config.i18nFormatter ? Vue.config.i18nFormatter.apply(null, [val].concat(args)) : format(val, args);
  }

  function translate(getter, lang, fallback, key, params) {
    var res = null;
    res = interpolate(getter(lang), key, params);
    if (!isNil(res)) {
      return res;
    }

    res = interpolate(getter(fallback), key, params);
    if (!isNil(res)) {
      if (process.env.NODE_ENV !== 'production') {
        warn('Fall back to translate the keypath "' + key + '" with "' + fallback + '" language.');
      }
      return res;
    } else {
      return null;
    }
  }

  function warnDefault(lang, key, vm, result) {
    if (!isNil(result)) {
      return result;
    }
    if (process.env.NODE_ENV !== 'production') {
      warn('Cannot translate the value of keypath "' + key + '". ' + 'Use the value of keypath as default');
    }
    Vue.config.missingHandler && Vue.config.missingHandler.apply(null, [lang, key, vm]);
    return key;
  }

  function getAssetLocale(lang) {
    return Vue.locale(lang);
  }

  function getComponentLocale(lang) {
    return this.$options.locales[lang];
  }

  function getOldChoiceIndexFixed(choice) {
    return choice ? choice > 1 ? 1 : 0 : 1;
  }

  function getChoiceIndex(choice, choicesLength) {
    choice = Math.abs(choice);

    if (choicesLength === 2) {
      return getOldChoiceIndexFixed(choice);
    }

    return choice ? Math.min(choice, 2) : 0;
  }

  function fetchChoice(locale, choice) {
    if (!locale && typeof locale !== 'string') {
      return null;
    }
    var choices = locale.split('|');

    choice = getChoiceIndex(choice, choices.length);
    if (!choices[choice]) {
      return locale;
    }
    return choices[choice].trim();
  }

  /**
   * Vue.t
   *
   * @param {String} key
   * @param {Array} ...args
   * @return {String}
   */

  Vue.t = function (key) {
    for (var _len2 = arguments.length, args = Array(_len2 > 1 ? _len2 - 1 : 0), _key2 = 1; _key2 < _len2; _key2++) {
      args[_key2 - 1] = arguments[_key2];
    }

    if (!key) {
      return '';
    }

    var _parseArgs = parseArgs.apply(undefined, args);

    var lang = _parseArgs.lang;
    var fallback = _parseArgs.fallback;
    var params = _parseArgs.params;

    return warnDefault(lang, key, null, translate(getAssetLocale, lang, fallback, key, params));
  };

  /**
   * Vue.tc
   *
   * @param {String} key
   * @param {number|undefined} choice
   * @param {Array} ...args
   * @return {String}
   */

  Vue.tc = function (key, choice) {
    for (var _len3 = arguments.length, args = Array(_len3 > 2 ? _len3 - 2 : 0), _key3 = 2; _key3 < _len3; _key3++) {
      args[_key3 - 2] = arguments[_key3];
    }

    return fetchChoice(Vue.t.apply(Vue, [key].concat(args)), choice);
  };

  /**
   * $t
   *
   * @param {String} key
   * @param {Array} ...args
   * @return {String}
   */

  Vue.prototype.$t = function (key) {
    if (!key) {
      return '';
    }

    for (var _len4 = arguments.length, args = Array(_len4 > 1 ? _len4 - 1 : 0), _key4 = 1; _key4 < _len4; _key4++) {
      args[_key4 - 1] = arguments[_key4];
    }

    var _parseArgs2 = parseArgs.apply(undefined, args);

    var lang = _parseArgs2.lang;
    var fallback = _parseArgs2.fallback;
    var params = _parseArgs2.params;

    var res = null;
    if (this.$options.locales) {
      res = translate(bind(getComponentLocale, this), lang, fallback, key, params);
      if (res) {
        return res;
      }
    }
    return warnDefault(lang, key, this, translate(getAssetLocale, lang, fallback, key, params));
  };

  /**
   * $tc
   *
   * @param {String} key
   * @param {number|undefined} choice
   * @param {Array} ...args
   * @return {String}
   */

  Vue.prototype.$tc = function (key, choice) {
    if (typeof choice !== 'number' && typeof choice !== 'undefined') {
      return key;
    }

    for (var _len5 = arguments.length, args = Array(_len5 > 2 ? _len5 - 2 : 0), _key5 = 2; _key5 < _len5; _key5++) {
      args[_key5 - 2] = arguments[_key5];
    }

    return fetchChoice(this.$t.apply(this, [key].concat(args)), choice);
  };

  return Vue;
}

var langVM = void 0; // singleton

/**
 * plugin
 *
 * @param {Object} Vue
 * @param {Object} opts
 */

function plugin(Vue) {
  var opts = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];

  var version = Vue.version && Number(Vue.version.split('.')[0]) || -1;

  if (process.env.NODE_ENV !== 'production' && plugin.installed) {
    warn('already installed.');
    return;
  }

  if (process.env.NODE_ENV !== 'production' && version < 1) {
    warn('vue-i18n (' + plugin.version + ') need to use vue version 1.0 or later (vue version: ' + Vue.version + ').');
    return;
  }

  var lang = 'en';
  setupLangVM(Vue, lang);

  Asset(Vue, langVM);
  Override(Vue, langVM, version);
  Config(Vue, langVM, lang);
  Extend(Vue);
}

function setupLangVM(Vue, lang) {
  var silent = Vue.config.silent;
  Vue.config.silent = true;
  if (!langVM) {
    langVM = new Vue({ data: { lang: lang, locales: {} } });
  }
  Vue.config.silent = silent;
}

plugin.version = '4.7.3';

if (typeof window !== 'undefined' && window.Vue) {
  window.Vue.use(plugin);
}

module.exports = plugin;
}).call(this,require('_process'))
},{"_process":4}],7:[function(require,module,exports){
/*!
 * vue-peity v0.3.1
 * (c) 2016 Fangdun Cai <cfddream@gmail.com>
 * Released under the MIT License.
 */
!function(t,e){"object"==typeof exports&&"object"==typeof module?module.exports=e():"function"==typeof define&&define.amd?define([],e):"object"==typeof exports?exports.VuePeity=e():t.VuePeity=e()}(this,function(){return function(t){function e(n){if(i[n])return i[n].exports;var r=i[n]={exports:{},id:n,loaded:!1};return t[n].call(r.exports,r,r.exports,e),r.loaded=!0,r.exports}var i={};return e.m=t,e.c=i,e.p="",e(0)}([function(t,e,i){var n,r;n=i(1);var s=i(6);r=n=n||{},"object"!=typeof n["default"]&&"function"!=typeof n["default"]||(r=n=n["default"]),"function"==typeof r&&(r=r.options),r.render=s.render,r.staticRenderFns=s.staticRenderFns,t.exports=n},function(t,e,i){"use strict";function n(t){return t&&t.__esModule?t:{"default":t}}Object.defineProperty(e,"__esModule",{value:!0});var r=i(4),s=n(r),a=["line","bar","pie","donut"];e["default"]={props:{type:{type:String,required:!0,validator:function(t){return a.indexOf(t)>-1}},data:{type:String,required:!0},options:{type:Object,"default":function(){return{}}}},data:function(){return{chart:null}},mounted:function(){this.chart=new s["default"](this.$el,this.type,this.data,this.options),this.chart.draw()},watch:{data:function(t){var e=this;this.$nextTick(function(){e.chart.raw=t,e.chart.draw()})}}}},function(t,e){"use strict";t.exports={options:{delimiter:",",fill:["#4D89F9"],height:16,min:0,padding:.1,width:32},draw:function(t){for(var e=this.values(),i=Math.max.apply(Math,void 0===t.max?e:e.concat(t.max)),n=Math.min.apply(Math,void 0===t.min?e:e.concat(t.min)),r=this.prepare(t.width,t.height),s=r.getBoundingClientRect(),a=s.width,o=s.height,h=i-n,u=t.padding,l=this.fill(),c=this.x=function(t){return t*a/e.length},f=this.y=function(t){return o-(h?(t-n)/h*o:1)},p=0;p<e.length;p++){var d,v=c(p+u),g=c(p+1-u)-v,m=e[p],y=f(m),w=y,x=y;h?m<0?w=f(Math.min(i,0)):x=f(Math.max(n,0)):d=1,d=x-w,0===d&&(d=1,i>0&&h&&w--),r.appendChild(this.svgElement("rect",{fill:l.call(this,m,p,e),x:v,y:w,width:g,height:d}))}}}},function(t,e){"use strict";t.exports={options:{delimiter:",",fill:"#c6d9fd",height:16,min:0,stroke:"#4d89f9",strokeWidth:1,width:32},draw:function(t){var e=this.values();1===e.length&&e.push(e[0]);for(var i=Math.max.apply(Math,void 0===t.max?e:e.concat(t.max)),n=Math.min.apply(Math,void 0===t.min?e:e.concat(t.min)),r=this.prepare(t.width,t.height),s=r.getBoundingClientRect(),a=t.strokeWidth,o=s.width,h=s.height-a,u=i-n,l=this.x=function(t){return t*(o/(e.length-1))},c=this.y=function(t){var e=h;return u&&(e-=(t-n)/u*h),e+a/2},f=c(Math.max(n,0)),p=[0,f],d=0;d<e.length;d++)p.push(l(d),c(e[d]));p.push(o,f),t.fill&&r.appendChild(this.svgElement("polygon",{fill:t.fill,points:p.join(" ")})),a&&r.appendChild(this.svgElement("polyline",{fill:"none",points:p.slice(2,p.length-2).join(" "),stroke:t.stroke,"stroke-width":a,"stroke-linecap":"square"}))}}},function(t,e,i){"use strict";function n(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}var r=function(){function t(t,e){for(var i=0;i<e.length;i++){var n=e[i];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}return function(e,i,n){return i&&t(e.prototype,i),n&&t(e,n),e}}(),s=i(2),a=i(3),o=i(5),h=function(t,e){var i=document.createElementNS("http://www.w3.org/2000/svg",t);for(var n in e)i.setAttribute(n,e[n]);return i},u=function(){function t(e,i,r,s){n(this,t),this.$el=e,this.type=i,this.raw=r,this.options=Object.assign({},t.defaults[this.type],s)}return r(t,[{key:"svgElement",value:function(){return h.apply(void 0,arguments)}},{key:"prepare",value:function(t,e){return this.$svg||(this.$el.style.display="none",this.$svg=h("svg",{"class":"peity"}),this.$el.parentNode.insertBefore(this.$svg,this.$el)),this.$svg.innerHTML="",this.$svg.setAttribute("width",t),this.$svg.setAttribute("height",e),this.$svg}},{key:"fill",value:function(){var t=this.options.fill;return"function"==typeof t?t:function(e,i){return t[i%t.length]}}},{key:"draw",value:function(){t.graphers[this.type].call(this,this.options)}},{key:"values",value:function(){return this.raw.split(this.options.delimiter).map(function(t){return parseFloat(t)})}}]),t}();u.defaults={},u.graphers={},u.register=function(t,e){u.defaults[t]=e.options,u.graphers[t]=e.draw},u.register("bar",s),u.register("donut",o),u.register("line",a),u.register("pie",o),t.exports=u},function(t,e){"use strict";t.exports={options:{fill:["#ff9900","#fff4dd","#ffc66e"],radius:8},draw:function(t){if(!t.delimiter){var e=this.raw.match(/[^0-9\.]/);t.delimiter=e?e[0]:","}var i=this.values().map(function(t){return t>0?t:0});if("/"===t.delimiter){var n=i[0],r=i[1];i=[n,Math.max(0,r-n)]}for(var s=0,a=i.length,o=0;s<a;s++)o+=i[s];o||(a=2,o=1,i=[0,1]);var h=2*t.radius,u=this.prepare(t.width||h,t.height||h),l=u.getBoundingClientRect(),c=l.width,f=l.height,p=c/2,d=f/2,v=Math.min(p,d),g=t.innerRadius;"donut"!==this.type||g||(g=.5*v);var m=Math.PI,y=this.fill(),w=this.scale=function(t,e){var i=t/o*m*2-m/2;return[e*Math.cos(i)+p,e*Math.sin(i)+d]},x=0;for(s=0;s<a;s++){var M,b=i[s],k=b/o;if(0!==k){if(1===k)if(g){var $=p-.01,j=d-v,E=d-g;M=this.svgElement("path",{d:["M",p,j,"A",v,v,0,1,1,$,j,"L",$,E,"A",g,g,0,1,0,p,E].join(" ")})}else M=this.svgElement("circle",{cx:p,cy:d,r:v});else{var A=x+b,C=["M"].concat(w(x,v),"A",v,v,0,k>.5?1:0,1,w(A,v),"L");g?C=C.concat(w(A,g),"A",g,g,0,k>.5?1:0,0,w(x,g)):C.push(p,d),x+=b,M=this.svgElement("path",{d:C.join(" ")})}M.setAttribute("fill",y.call(this,b,s,i)),u.appendChild(M)}}}}},function(module,exports){module.exports={render:function(){with(this)return _m(0)},staticRenderFns:[function(){with(this)return _h("span")}]}}])});
},{}],8:[function(require,module,exports){
(function (process){
/*!
 * Vue.js v2.0.7
 * (c) 2014-2016 Evan You
 * Released under the MIT License.
 */
'use strict';

/*  */

/**
 * Convert a value to a string that is actually rendered.
 */
function _toString (val) {
  return val == null
    ? ''
    : typeof val === 'object'
      ? JSON.stringify(val, null, 2)
      : String(val)
}

/**
 * Convert a input value to a number for persistence.
 * If the conversion fails, return original string.
 */
function toNumber (val) {
  var n = parseFloat(val, 10);
  return (n || n === 0) ? n : val
}

/**
 * Make a map and return a function for checking if a key
 * is in that map.
 */
function makeMap (
  str,
  expectsLowerCase
) {
  var map = Object.create(null);
  var list = str.split(',');
  for (var i = 0; i < list.length; i++) {
    map[list[i]] = true;
  }
  return expectsLowerCase
    ? function (val) { return map[val.toLowerCase()]; }
    : function (val) { return map[val]; }
}

/**
 * Check if a tag is a built-in tag.
 */
var isBuiltInTag = makeMap('slot,component', true);

/**
 * Remove an item from an array
 */
function remove$1 (arr, item) {
  if (arr.length) {
    var index = arr.indexOf(item);
    if (index > -1) {
      return arr.splice(index, 1)
    }
  }
}

/**
 * Check whether the object has the property.
 */
var hasOwnProperty = Object.prototype.hasOwnProperty;
function hasOwn (obj, key) {
  return hasOwnProperty.call(obj, key)
}

/**
 * Check if value is primitive
 */
function isPrimitive (value) {
  return typeof value === 'string' || typeof value === 'number'
}

/**
 * Create a cached version of a pure function.
 */
function cached (fn) {
  var cache = Object.create(null);
  return function cachedFn (str) {
    var hit = cache[str];
    return hit || (cache[str] = fn(str))
  }
}

/**
 * Camelize a hyphen-delmited string.
 */
var camelizeRE = /-(\w)/g;
var camelize = cached(function (str) {
  return str.replace(camelizeRE, function (_, c) { return c ? c.toUpperCase() : ''; })
});

/**
 * Capitalize a string.
 */
var capitalize = cached(function (str) {
  return str.charAt(0).toUpperCase() + str.slice(1)
});

/**
 * Hyphenate a camelCase string.
 */
var hyphenateRE = /([^-])([A-Z])/g;
var hyphenate = cached(function (str) {
  return str
    .replace(hyphenateRE, '$1-$2')
    .replace(hyphenateRE, '$1-$2')
    .toLowerCase()
});

/**
 * Simple bind, faster than native
 */
function bind$1 (fn, ctx) {
  function boundFn (a) {
    var l = arguments.length;
    return l
      ? l > 1
        ? fn.apply(ctx, arguments)
        : fn.call(ctx, a)
      : fn.call(ctx)
  }
  // record original fn length
  boundFn._length = fn.length;
  return boundFn
}

/**
 * Convert an Array-like object to a real Array.
 */
function toArray (list, start) {
  start = start || 0;
  var i = list.length - start;
  var ret = new Array(i);
  while (i--) {
    ret[i] = list[i + start];
  }
  return ret
}

/**
 * Mix properties into target object.
 */
function extend (to, _from) {
  for (var key in _from) {
    to[key] = _from[key];
  }
  return to
}

/**
 * Quick object check - this is primarily used to tell
 * Objects from primitive values when we know the value
 * is a JSON-compliant type.
 */
function isObject (obj) {
  return obj !== null && typeof obj === 'object'
}

/**
 * Strict object type check. Only returns true
 * for plain JavaScript objects.
 */
var toString = Object.prototype.toString;
var OBJECT_STRING = '[object Object]';
function isPlainObject (obj) {
  return toString.call(obj) === OBJECT_STRING
}

/**
 * Merge an Array of Objects into a single Object.
 */
function toObject (arr) {
  var res = {};
  for (var i = 0; i < arr.length; i++) {
    if (arr[i]) {
      extend(res, arr[i]);
    }
  }
  return res
}

/**
 * Perform no operation.
 */
function noop () {}

/**
 * Always return false.
 */
var no = function () { return false; };

/**
 * Generate a static keys string from compiler modules.
 */
function genStaticKeys (modules) {
  return modules.reduce(function (keys, m) {
    return keys.concat(m.staticKeys || [])
  }, []).join(',')
}

/**
 * Check if two values are loosely equal - that is,
 * if they are plain objects, do they have the same shape?
 */
function looseEqual (a, b) {
  /* eslint-disable eqeqeq */
  return a == b || (
    isObject(a) && isObject(b)
      ? JSON.stringify(a) === JSON.stringify(b)
      : false
  )
  /* eslint-enable eqeqeq */
}

function looseIndexOf (arr, val) {
  for (var i = 0; i < arr.length; i++) {
    if (looseEqual(arr[i], val)) { return i }
  }
  return -1
}

/*  */

var config = {
  /**
   * Option merge strategies (used in core/util/options)
   */
  optionMergeStrategies: Object.create(null),

  /**
   * Whether to suppress warnings.
   */
  silent: false,

  /**
   * Whether to enable devtools
   */
  devtools: process.env.NODE_ENV !== 'production',

  /**
   * Error handler for watcher errors
   */
  errorHandler: null,

  /**
   * Ignore certain custom elements
   */
  ignoredElements: null,

  /**
   * Custom user key aliases for v-on
   */
  keyCodes: Object.create(null),

  /**
   * Check if a tag is reserved so that it cannot be registered as a
   * component. This is platform-dependent and may be overwritten.
   */
  isReservedTag: no,

  /**
   * Check if a tag is an unknown element.
   * Platform-dependent.
   */
  isUnknownElement: no,

  /**
   * Get the namespace of an element
   */
  getTagNamespace: noop,

  /**
   * Check if an attribute must be bound using property, e.g. value
   * Platform-dependent.
   */
  mustUseProp: no,

  /**
   * List of asset types that a component can own.
   */
  _assetTypes: [
    'component',
    'directive',
    'filter'
  ],

  /**
   * List of lifecycle hooks.
   */
  _lifecycleHooks: [
    'beforeCreate',
    'created',
    'beforeMount',
    'mounted',
    'beforeUpdate',
    'updated',
    'beforeDestroy',
    'destroyed',
    'activated',
    'deactivated'
  ],

  /**
   * Max circular updates allowed in a scheduler flush cycle.
   */
  _maxUpdateCount: 100,

  /**
   * Server rendering?
   */
  _isServer: process.env.VUE_ENV === 'server'
};

/*  */

/**
 * Check if a string starts with $ or _
 */
function isReserved (str) {
  var c = (str + '').charCodeAt(0);
  return c === 0x24 || c === 0x5F
}

/**
 * Define a property.
 */
function def (obj, key, val, enumerable) {
  Object.defineProperty(obj, key, {
    value: val,
    enumerable: !!enumerable,
    writable: true,
    configurable: true
  });
}

/**
 * Parse simple path.
 */
var bailRE = /[^\w.$]/;
function parsePath (path) {
  if (bailRE.test(path)) {
    return
  } else {
    var segments = path.split('.');
    return function (obj) {
      for (var i = 0; i < segments.length; i++) {
        if (!obj) { return }
        obj = obj[segments[i]];
      }
      return obj
    }
  }
}

/*  */
/* globals MutationObserver */

// can we use __proto__?
var hasProto = '__proto__' in {};

// Browser environment sniffing
var inBrowser =
  typeof window !== 'undefined' &&
  Object.prototype.toString.call(window) !== '[object Object]';

var UA = inBrowser && window.navigator.userAgent.toLowerCase();
var isIE = UA && /msie|trident/.test(UA);
var isIE9 = UA && UA.indexOf('msie 9.0') > 0;
var isEdge = UA && UA.indexOf('edge/') > 0;
var isAndroid = UA && UA.indexOf('android') > 0;
var isIOS = UA && /iphone|ipad|ipod|ios/.test(UA);

// detect devtools
var devtools = inBrowser && window.__VUE_DEVTOOLS_GLOBAL_HOOK__;

/* istanbul ignore next */
function isNative (Ctor) {
  return /native code/.test(Ctor.toString())
}

/**
 * Defer a task to execute it asynchronously.
 */
var nextTick = (function () {
  var callbacks = [];
  var pending = false;
  var timerFunc;

  function nextTickHandler () {
    pending = false;
    var copies = callbacks.slice(0);
    callbacks.length = 0;
    for (var i = 0; i < copies.length; i++) {
      copies[i]();
    }
  }

  // the nextTick behavior leverages the microtask queue, which can be accessed
  // via either native Promise.then or MutationObserver.
  // MutationObserver has wider support, however it is seriously bugged in
  // UIWebView in iOS >= 9.3.3 when triggered in touch event handlers. It
  // completely stops working after triggering a few times... so, if native
  // Promise is available, we will use it:
  /* istanbul ignore if */
  if (typeof Promise !== 'undefined' && isNative(Promise)) {
    var p = Promise.resolve();
    timerFunc = function () {
      p.then(nextTickHandler);
      // in problematic UIWebViews, Promise.then doesn't completely break, but
      // it can get stuck in a weird state where callbacks are pushed into the
      // microtask queue but the queue isn't being flushed, until the browser
      // needs to do some other work, e.g. handle a timer. Therefore we can
      // "force" the microtask queue to be flushed by adding an empty timer.
      if (isIOS) { setTimeout(noop); }
    };
  } else if (typeof MutationObserver !== 'undefined' && (
    isNative(MutationObserver) ||
    // PhantomJS and iOS 7.x
    MutationObserver.toString() === '[object MutationObserverConstructor]'
  )) {
    // use MutationObserver where native Promise is not available,
    // e.g. PhantomJS IE11, iOS7, Android 4.4
    var counter = 1;
    var observer = new MutationObserver(nextTickHandler);
    var textNode = document.createTextNode(String(counter));
    observer.observe(textNode, {
      characterData: true
    });
    timerFunc = function () {
      counter = (counter + 1) % 2;
      textNode.data = String(counter);
    };
  } else {
    // fallback to setTimeout
    /* istanbul ignore next */
    timerFunc = function () {
      setTimeout(nextTickHandler, 0);
    };
  }

  return function queueNextTick (cb, ctx) {
    var func = ctx
      ? function () { cb.call(ctx); }
      : cb;
    callbacks.push(func);
    if (!pending) {
      pending = true;
      timerFunc();
    }
  }
})();

var _Set;
/* istanbul ignore if */
if (typeof Set !== 'undefined' && isNative(Set)) {
  // use native Set when available.
  _Set = Set;
} else {
  // a non-standard Set polyfill that only works with primitive keys.
  _Set = (function () {
    function Set () {
      this.set = Object.create(null);
    }
    Set.prototype.has = function has (key) {
      return this.set[key] !== undefined
    };
    Set.prototype.add = function add (key) {
      this.set[key] = 1;
    };
    Set.prototype.clear = function clear () {
      this.set = Object.create(null);
    };

    return Set;
  }());
}

/* not type checking this file because flow doesn't play well with Proxy */

var hasProxy;
var proxyHandlers;
var initProxy;

if (process.env.NODE_ENV !== 'production') {
  var allowedGlobals = makeMap(
    'Infinity,undefined,NaN,isFinite,isNaN,' +
    'parseFloat,parseInt,decodeURI,decodeURIComponent,encodeURI,encodeURIComponent,' +
    'Math,Number,Date,Array,Object,Boolean,String,RegExp,Map,Set,JSON,Intl,' +
    'require' // for Webpack/Browserify
  );

  hasProxy =
    typeof Proxy !== 'undefined' &&
    Proxy.toString().match(/native code/);

  proxyHandlers = {
    has: function has (target, key) {
      var has = key in target;
      var isAllowed = allowedGlobals(key) || key.charAt(0) === '_';
      if (!has && !isAllowed) {
        warn(
          "Property or method \"" + key + "\" is not defined on the instance but " +
          "referenced during render. Make sure to declare reactive data " +
          "properties in the data option.",
          target
        );
      }
      return has || !isAllowed
    }
  };

  initProxy = function initProxy (vm) {
    if (hasProxy) {
      vm._renderProxy = new Proxy(vm, proxyHandlers);
    } else {
      vm._renderProxy = vm;
    }
  };
}

/*  */


var uid$2 = 0;

/**
 * A dep is an observable that can have multiple
 * directives subscribing to it.
 */
var Dep = function Dep () {
  this.id = uid$2++;
  this.subs = [];
};

Dep.prototype.addSub = function addSub (sub) {
  this.subs.push(sub);
};

Dep.prototype.removeSub = function removeSub (sub) {
  remove$1(this.subs, sub);
};

Dep.prototype.depend = function depend () {
  if (Dep.target) {
    Dep.target.addDep(this);
  }
};

Dep.prototype.notify = function notify () {
  // stablize the subscriber list first
  var subs = this.subs.slice();
  for (var i = 0, l = subs.length; i < l; i++) {
    subs[i].update();
  }
};

// the current target watcher being evaluated.
// this is globally unique because there could be only one
// watcher being evaluated at any time.
Dep.target = null;
var targetStack = [];

function pushTarget (_target) {
  if (Dep.target) { targetStack.push(Dep.target); }
  Dep.target = _target;
}

function popTarget () {
  Dep.target = targetStack.pop();
}

/*  */


var queue = [];
var has$1 = {};
var circular = {};
var waiting = false;
var flushing = false;
var index = 0;

/**
 * Reset the scheduler's state.
 */
function resetSchedulerState () {
  queue.length = 0;
  has$1 = {};
  if (process.env.NODE_ENV !== 'production') {
    circular = {};
  }
  waiting = flushing = false;
}

/**
 * Flush both queues and run the watchers.
 */
function flushSchedulerQueue () {
  flushing = true;

  // Sort queue before flush.
  // This ensures that:
  // 1. Components are updated from parent to child. (because parent is always
  //    created before the child)
  // 2. A component's user watchers are run before its render watcher (because
  //    user watchers are created before the render watcher)
  // 3. If a component is destroyed during a parent component's watcher run,
  //    its watchers can be skipped.
  queue.sort(function (a, b) { return a.id - b.id; });

  // do not cache length because more watchers might be pushed
  // as we run existing watchers
  for (index = 0; index < queue.length; index++) {
    var watcher = queue[index];
    var id = watcher.id;
    has$1[id] = null;
    watcher.run();
    // in dev build, check and stop circular updates.
    if (process.env.NODE_ENV !== 'production' && has$1[id] != null) {
      circular[id] = (circular[id] || 0) + 1;
      if (circular[id] > config._maxUpdateCount) {
        warn(
          'You may have an infinite update loop ' + (
            watcher.user
              ? ("in watcher with expression \"" + (watcher.expression) + "\"")
              : "in a component render function."
          ),
          watcher.vm
        );
        break
      }
    }
  }

  // devtool hook
  /* istanbul ignore if */
  if (devtools && config.devtools) {
    devtools.emit('flush');
  }

  resetSchedulerState();
}

/**
 * Push a watcher into the watcher queue.
 * Jobs with duplicate IDs will be skipped unless it's
 * pushed when the queue is being flushed.
 */
function queueWatcher (watcher) {
  var id = watcher.id;
  if (has$1[id] == null) {
    has$1[id] = true;
    if (!flushing) {
      queue.push(watcher);
    } else {
      // if already flushing, splice the watcher based on its id
      // if already past its id, it will be run next immediately.
      var i = queue.length - 1;
      while (i >= 0 && queue[i].id > watcher.id) {
        i--;
      }
      queue.splice(Math.max(i, index) + 1, 0, watcher);
    }
    // queue the flush
    if (!waiting) {
      waiting = true;
      nextTick(flushSchedulerQueue);
    }
  }
}

/*  */

var uid$1 = 0;

/**
 * A watcher parses an expression, collects dependencies,
 * and fires callback when the expression value changes.
 * This is used for both the $watch() api and directives.
 */
var Watcher = function Watcher (
  vm,
  expOrFn,
  cb,
  options
) {
  if ( options === void 0 ) options = {};

  this.vm = vm;
  vm._watchers.push(this);
  // options
  this.deep = !!options.deep;
  this.user = !!options.user;
  this.lazy = !!options.lazy;
  this.sync = !!options.sync;
  this.expression = expOrFn.toString();
  this.cb = cb;
  this.id = ++uid$1; // uid for batching
  this.active = true;
  this.dirty = this.lazy; // for lazy watchers
  this.deps = [];
  this.newDeps = [];
  this.depIds = new _Set();
  this.newDepIds = new _Set();
  // parse expression for getter
  if (typeof expOrFn === 'function') {
    this.getter = expOrFn;
  } else {
    this.getter = parsePath(expOrFn);
    if (!this.getter) {
      this.getter = function () {};
      process.env.NODE_ENV !== 'production' && warn(
        "Failed watching path: \"" + expOrFn + "\" " +
        'Watcher only accepts simple dot-delimited paths. ' +
        'For full control, use a function instead.',
        vm
      );
    }
  }
  this.value = this.lazy
    ? undefined
    : this.get();
};

/**
 * Evaluate the getter, and re-collect dependencies.
 */
Watcher.prototype.get = function get () {
  pushTarget(this);
  var value = this.getter.call(this.vm, this.vm);
  // "touch" every property so they are all tracked as
  // dependencies for deep watching
  if (this.deep) {
    traverse(value);
  }
  popTarget();
  this.cleanupDeps();
  return value
};

/**
 * Add a dependency to this directive.
 */
Watcher.prototype.addDep = function addDep (dep) {
  var id = dep.id;
  if (!this.newDepIds.has(id)) {
    this.newDepIds.add(id);
    this.newDeps.push(dep);
    if (!this.depIds.has(id)) {
      dep.addSub(this);
    }
  }
};

/**
 * Clean up for dependency collection.
 */
Watcher.prototype.cleanupDeps = function cleanupDeps () {
    var this$1 = this;

  var i = this.deps.length;
  while (i--) {
    var dep = this$1.deps[i];
    if (!this$1.newDepIds.has(dep.id)) {
      dep.removeSub(this$1);
    }
  }
  var tmp = this.depIds;
  this.depIds = this.newDepIds;
  this.newDepIds = tmp;
  this.newDepIds.clear();
  tmp = this.deps;
  this.deps = this.newDeps;
  this.newDeps = tmp;
  this.newDeps.length = 0;
};

/**
 * Subscriber interface.
 * Will be called when a dependency changes.
 */
Watcher.prototype.update = function update () {
  /* istanbul ignore else */
  if (this.lazy) {
    this.dirty = true;
  } else if (this.sync) {
    this.run();
  } else {
    queueWatcher(this);
  }
};

/**
 * Scheduler job interface.
 * Will be called by the scheduler.
 */
Watcher.prototype.run = function run () {
  if (this.active) {
    var value = this.get();
      if (
        value !== this.value ||
      // Deep watchers and watchers on Object/Arrays should fire even
      // when the value is the same, because the value may
      // have mutated.
      isObject(value) ||
      this.deep
    ) {
      // set new value
      var oldValue = this.value;
      this.value = value;
      if (this.user) {
        try {
          this.cb.call(this.vm, value, oldValue);
        } catch (e) {
          process.env.NODE_ENV !== 'production' && warn(
            ("Error in watcher \"" + (this.expression) + "\""),
            this.vm
          );
          /* istanbul ignore else */
          if (config.errorHandler) {
            config.errorHandler.call(null, e, this.vm);
          } else {
            throw e
          }
        }
      } else {
        this.cb.call(this.vm, value, oldValue);
      }
    }
  }
};

/**
 * Evaluate the value of the watcher.
 * This only gets called for lazy watchers.
 */
Watcher.prototype.evaluate = function evaluate () {
  this.value = this.get();
  this.dirty = false;
};

/**
 * Depend on all deps collected by this watcher.
 */
Watcher.prototype.depend = function depend () {
    var this$1 = this;

  var i = this.deps.length;
  while (i--) {
    this$1.deps[i].depend();
  }
};

/**
 * Remove self from all dependencies' subscriber list.
 */
Watcher.prototype.teardown = function teardown () {
    var this$1 = this;

  if (this.active) {
    // remove self from vm's watcher list
    // this is a somewhat expensive operation so we skip it
    // if the vm is being destroyed or is performing a v-for
    // re-render (the watcher list is then filtered by v-for).
    if (!this.vm._isBeingDestroyed && !this.vm._vForRemoving) {
      remove$1(this.vm._watchers, this);
    }
    var i = this.deps.length;
    while (i--) {
      this$1.deps[i].removeSub(this$1);
    }
    this.active = false;
  }
};

/**
 * Recursively traverse an object to evoke all converted
 * getters, so that every nested property inside the object
 * is collected as a "deep" dependency.
 */
var seenObjects = new _Set();
function traverse (val) {
  seenObjects.clear();
  _traverse(val, seenObjects);
}

function _traverse (val, seen) {
  var i, keys;
  var isA = Array.isArray(val);
  if ((!isA && !isObject(val)) || !Object.isExtensible(val)) {
    return
  }
  if (val.__ob__) {
    var depId = val.__ob__.dep.id;
    if (seen.has(depId)) {
      return
    }
    seen.add(depId);
  }
  if (isA) {
    i = val.length;
    while (i--) { _traverse(val[i], seen); }
  } else {
    keys = Object.keys(val);
    i = keys.length;
    while (i--) { _traverse(val[keys[i]], seen); }
  }
}

/*
 * not type checking this file because flow doesn't play well with
 * dynamically accessing methods on Array prototype
 */

var arrayProto = Array.prototype;
var arrayMethods = Object.create(arrayProto);[
  'push',
  'pop',
  'shift',
  'unshift',
  'splice',
  'sort',
  'reverse'
]
.forEach(function (method) {
  // cache original method
  var original = arrayProto[method];
  def(arrayMethods, method, function mutator () {
    var arguments$1 = arguments;

    // avoid leaking arguments:
    // http://jsperf.com/closure-with-arguments
    var i = arguments.length;
    var args = new Array(i);
    while (i--) {
      args[i] = arguments$1[i];
    }
    var result = original.apply(this, args);
    var ob = this.__ob__;
    var inserted;
    switch (method) {
      case 'push':
        inserted = args;
        break
      case 'unshift':
        inserted = args;
        break
      case 'splice':
        inserted = args.slice(2);
        break
    }
    if (inserted) { ob.observeArray(inserted); }
    // notify change
    ob.dep.notify();
    return result
  });
});

/*  */

var arrayKeys = Object.getOwnPropertyNames(arrayMethods);

/**
 * By default, when a reactive property is set, the new value is
 * also converted to become reactive. However when passing down props,
 * we don't want to force conversion because the value may be a nested value
 * under a frozen data structure. Converting it would defeat the optimization.
 */
var observerState = {
  shouldConvert: true,
  isSettingProps: false
};

/**
 * Observer class that are attached to each observed
 * object. Once attached, the observer converts target
 * object's property keys into getter/setters that
 * collect dependencies and dispatches updates.
 */
var Observer = function Observer (value) {
  this.value = value;
  this.dep = new Dep();
  this.vmCount = 0;
  def(value, '__ob__', this);
  if (Array.isArray(value)) {
    var augment = hasProto
      ? protoAugment
      : copyAugment;
    augment(value, arrayMethods, arrayKeys);
    this.observeArray(value);
  } else {
    this.walk(value);
  }
};

/**
 * Walk through each property and convert them into
 * getter/setters. This method should only be called when
 * value type is Object.
 */
Observer.prototype.walk = function walk (obj) {
  var keys = Object.keys(obj);
  for (var i = 0; i < keys.length; i++) {
    defineReactive$$1(obj, keys[i], obj[keys[i]]);
  }
};

/**
 * Observe a list of Array items.
 */
Observer.prototype.observeArray = function observeArray (items) {
  for (var i = 0, l = items.length; i < l; i++) {
    observe(items[i]);
  }
};

// helpers

/**
 * Augment an target Object or Array by intercepting
 * the prototype chain using __proto__
 */
function protoAugment (target, src) {
  /* eslint-disable no-proto */
  target.__proto__ = src;
  /* eslint-enable no-proto */
}

/**
 * Augment an target Object or Array by defining
 * hidden properties.
 *
 * istanbul ignore next
 */
function copyAugment (target, src, keys) {
  for (var i = 0, l = keys.length; i < l; i++) {
    var key = keys[i];
    def(target, key, src[key]);
  }
}

/**
 * Attempt to create an observer instance for a value,
 * returns the new observer if successfully observed,
 * or the existing observer if the value already has one.
 */
function observe (value) {
  if (!isObject(value)) {
    return
  }
  var ob;
  if (hasOwn(value, '__ob__') && value.__ob__ instanceof Observer) {
    ob = value.__ob__;
  } else if (
    observerState.shouldConvert &&
    !config._isServer &&
    (Array.isArray(value) || isPlainObject(value)) &&
    Object.isExtensible(value) &&
    !value._isVue
  ) {
    ob = new Observer(value);
  }
  return ob
}

/**
 * Define a reactive property on an Object.
 */
function defineReactive$$1 (
  obj,
  key,
  val,
  customSetter
) {
  var dep = new Dep();

  var property = Object.getOwnPropertyDescriptor(obj, key);
  if (property && property.configurable === false) {
    return
  }

  // cater for pre-defined getter/setters
  var getter = property && property.get;
  var setter = property && property.set;

  var childOb = observe(val);
  Object.defineProperty(obj, key, {
    enumerable: true,
    configurable: true,
    get: function reactiveGetter () {
      var value = getter ? getter.call(obj) : val;
      if (Dep.target) {
        dep.depend();
        if (childOb) {
          childOb.dep.depend();
        }
        if (Array.isArray(value)) {
          dependArray(value);
        }
      }
      return value
    },
    set: function reactiveSetter (newVal) {
      var value = getter ? getter.call(obj) : val;
      if (newVal === value) {
        return
      }
      if (process.env.NODE_ENV !== 'production' && customSetter) {
        customSetter();
      }
      if (setter) {
        setter.call(obj, newVal);
      } else {
        val = newVal;
      }
      childOb = observe(newVal);
      dep.notify();
    }
  });
}

/**
 * Set a property on an object. Adds the new property and
 * triggers change notification if the property doesn't
 * already exist.
 */
function set (obj, key, val) {
  if (Array.isArray(obj)) {
    obj.length = Math.max(obj.length, key);
    obj.splice(key, 1, val);
    return val
  }
  if (hasOwn(obj, key)) {
    obj[key] = val;
    return
  }
  var ob = obj.__ob__;
  if (obj._isVue || (ob && ob.vmCount)) {
    process.env.NODE_ENV !== 'production' && warn(
      'Avoid adding reactive properties to a Vue instance or its root $data ' +
      'at runtime - declare it upfront in the data option.'
    );
    return
  }
  if (!ob) {
    obj[key] = val;
    return
  }
  defineReactive$$1(ob.value, key, val);
  ob.dep.notify();
  return val
}

/**
 * Delete a property and trigger change if necessary.
 */
function del (obj, key) {
  var ob = obj.__ob__;
  if (obj._isVue || (ob && ob.vmCount)) {
    process.env.NODE_ENV !== 'production' && warn(
      'Avoid deleting properties on a Vue instance or its root $data ' +
      '- just set it to null.'
    );
    return
  }
  if (!hasOwn(obj, key)) {
    return
  }
  delete obj[key];
  if (!ob) {
    return
  }
  ob.dep.notify();
}

/**
 * Collect dependencies on array elements when the array is touched, since
 * we cannot intercept array element access like property getters.
 */
function dependArray (value) {
  for (var e = (void 0), i = 0, l = value.length; i < l; i++) {
    e = value[i];
    e && e.__ob__ && e.__ob__.dep.depend();
    if (Array.isArray(e)) {
      dependArray(e);
    }
  }
}

/*  */

function initState (vm) {
  vm._watchers = [];
  initProps(vm);
  initData(vm);
  initComputed(vm);
  initMethods(vm);
  initWatch(vm);
}

function initProps (vm) {
  var props = vm.$options.props;
  if (props) {
    var propsData = vm.$options.propsData || {};
    var keys = vm.$options._propKeys = Object.keys(props);
    var isRoot = !vm.$parent;
    // root instance props should be converted
    observerState.shouldConvert = isRoot;
    var loop = function ( i ) {
      var key = keys[i];
      /* istanbul ignore else */
      if (process.env.NODE_ENV !== 'production') {
        defineReactive$$1(vm, key, validateProp(key, props, propsData, vm), function () {
          if (vm.$parent && !observerState.isSettingProps) {
            warn(
              "Avoid mutating a prop directly since the value will be " +
              "overwritten whenever the parent component re-renders. " +
              "Instead, use a data or computed property based on the prop's " +
              "value. Prop being mutated: \"" + key + "\"",
              vm
            );
          }
        });
      } else {
        defineReactive$$1(vm, key, validateProp(key, props, propsData, vm));
      }
    };

    for (var i = 0; i < keys.length; i++) loop( i );
    observerState.shouldConvert = true;
  }
}

function initData (vm) {
  var data = vm.$options.data;
  data = vm._data = typeof data === 'function'
    ? data.call(vm)
    : data || {};
  if (!isPlainObject(data)) {
    data = {};
    process.env.NODE_ENV !== 'production' && warn(
      'data functions should return an object.',
      vm
    );
  }
  // proxy data on instance
  var keys = Object.keys(data);
  var props = vm.$options.props;
  var i = keys.length;
  while (i--) {
    if (props && hasOwn(props, keys[i])) {
      process.env.NODE_ENV !== 'production' && warn(
        "The data property \"" + (keys[i]) + "\" is already declared as a prop. " +
        "Use prop default value instead.",
        vm
      );
    } else {
      proxy(vm, keys[i]);
    }
  }
  // observe data
  observe(data);
  data.__ob__ && data.__ob__.vmCount++;
}

var computedSharedDefinition = {
  enumerable: true,
  configurable: true,
  get: noop,
  set: noop
};

function initComputed (vm) {
  var computed = vm.$options.computed;
  if (computed) {
    for (var key in computed) {
      var userDef = computed[key];
      if (typeof userDef === 'function') {
        computedSharedDefinition.get = makeComputedGetter(userDef, vm);
        computedSharedDefinition.set = noop;
      } else {
        computedSharedDefinition.get = userDef.get
          ? userDef.cache !== false
            ? makeComputedGetter(userDef.get, vm)
            : bind$1(userDef.get, vm)
          : noop;
        computedSharedDefinition.set = userDef.set
          ? bind$1(userDef.set, vm)
          : noop;
      }
      Object.defineProperty(vm, key, computedSharedDefinition);
    }
  }
}

function makeComputedGetter (getter, owner) {
  var watcher = new Watcher(owner, getter, noop, {
    lazy: true
  });
  return function computedGetter () {
    if (watcher.dirty) {
      watcher.evaluate();
    }
    if (Dep.target) {
      watcher.depend();
    }
    return watcher.value
  }
}

function initMethods (vm) {
  var methods = vm.$options.methods;
  if (methods) {
    for (var key in methods) {
      vm[key] = methods[key] == null ? noop : bind$1(methods[key], vm);
      if (process.env.NODE_ENV !== 'production' && methods[key] == null) {
        warn(
          "method \"" + key + "\" has an undefined value in the component definition. " +
          "Did you reference the function correctly?",
          vm
        );
      }
    }
  }
}

function initWatch (vm) {
  var watch = vm.$options.watch;
  if (watch) {
    for (var key in watch) {
      var handler = watch[key];
      if (Array.isArray(handler)) {
        for (var i = 0; i < handler.length; i++) {
          createWatcher(vm, key, handler[i]);
        }
      } else {
        createWatcher(vm, key, handler);
      }
    }
  }
}

function createWatcher (vm, key, handler) {
  var options;
  if (isPlainObject(handler)) {
    options = handler;
    handler = handler.handler;
  }
  if (typeof handler === 'string') {
    handler = vm[handler];
  }
  vm.$watch(key, handler, options);
}

function stateMixin (Vue) {
  // flow somehow has problems with directly declared definition object
  // when using Object.defineProperty, so we have to procedurally build up
  // the object here.
  var dataDef = {};
  dataDef.get = function () {
    return this._data
  };
  if (process.env.NODE_ENV !== 'production') {
    dataDef.set = function (newData) {
      warn(
        'Avoid replacing instance root $data. ' +
        'Use nested data properties instead.',
        this
      );
    };
  }
  Object.defineProperty(Vue.prototype, '$data', dataDef);

  Vue.prototype.$set = set;
  Vue.prototype.$delete = del;

  Vue.prototype.$watch = function (
    expOrFn,
    cb,
    options
  ) {
    var vm = this;
    options = options || {};
    options.user = true;
    var watcher = new Watcher(vm, expOrFn, cb, options);
    if (options.immediate) {
      cb.call(vm, watcher.value);
    }
    return function unwatchFn () {
      watcher.teardown();
    }
  };
}

function proxy (vm, key) {
  if (!isReserved(key)) {
    Object.defineProperty(vm, key, {
      configurable: true,
      enumerable: true,
      get: function proxyGetter () {
        return vm._data[key]
      },
      set: function proxySetter (val) {
        vm._data[key] = val;
      }
    });
  }
}

/*  */

var VNode = function VNode (
  tag,
  data,
  children,
  text,
  elm,
  ns,
  context,
  componentOptions
) {
  this.tag = tag;
  this.data = data;
  this.children = children;
  this.text = text;
  this.elm = elm;
  this.ns = ns;
  this.context = context;
  this.functionalContext = undefined;
  this.key = data && data.key;
  this.componentOptions = componentOptions;
  this.child = undefined;
  this.parent = undefined;
  this.raw = false;
  this.isStatic = false;
  this.isRootInsert = true;
  this.isComment = false;
  this.isCloned = false;
  this.isOnce = false;
};

var emptyVNode = function () {
  var node = new VNode();
  node.text = '';
  node.isComment = true;
  return node
};

// optimized shallow clone
// used for static nodes and slot nodes because they may be reused across
// multiple renders, cloning them avoids errors when DOM manipulations rely
// on their elm reference.
function cloneVNode (vnode) {
  var cloned = new VNode(
    vnode.tag,
    vnode.data,
    vnode.children,
    vnode.text,
    vnode.elm,
    vnode.ns,
    vnode.context,
    vnode.componentOptions
  );
  cloned.isStatic = vnode.isStatic;
  cloned.key = vnode.key;
  cloned.isCloned = true;
  return cloned
}

function cloneVNodes (vnodes) {
  var res = new Array(vnodes.length);
  for (var i = 0; i < vnodes.length; i++) {
    res[i] = cloneVNode(vnodes[i]);
  }
  return res
}

/*  */

function mergeVNodeHook (def, hookKey, hook, key) {
  key = key + hookKey;
  var injectedHash = def.__injected || (def.__injected = {});
  if (!injectedHash[key]) {
    injectedHash[key] = true;
    var oldHook = def[hookKey];
    if (oldHook) {
      def[hookKey] = function () {
        oldHook.apply(this, arguments);
        hook.apply(this, arguments);
      };
    } else {
      def[hookKey] = hook;
    }
  }
}

/*  */

function updateListeners (
  on,
  oldOn,
  add,
  remove$$1,
  vm
) {
  var name, cur, old, fn, event, capture;
  for (name in on) {
    cur = on[name];
    old = oldOn[name];
    if (!cur) {
      process.env.NODE_ENV !== 'production' && warn(
        "Invalid handler for event \"" + name + "\": got " + String(cur),
        vm
      );
    } else if (!old) {
      capture = name.charAt(0) === '!';
      event = capture ? name.slice(1) : name;
      if (Array.isArray(cur)) {
        add(event, (cur.invoker = arrInvoker(cur)), capture);
      } else {
        if (!cur.invoker) {
          fn = cur;
          cur = on[name] = {};
          cur.fn = fn;
          cur.invoker = fnInvoker(cur);
        }
        add(event, cur.invoker, capture);
      }
    } else if (cur !== old) {
      if (Array.isArray(old)) {
        old.length = cur.length;
        for (var i = 0; i < old.length; i++) { old[i] = cur[i]; }
        on[name] = old;
      } else {
        old.fn = cur;
        on[name] = old;
      }
    }
  }
  for (name in oldOn) {
    if (!on[name]) {
      event = name.charAt(0) === '!' ? name.slice(1) : name;
      remove$$1(event, oldOn[name].invoker);
    }
  }
}

function arrInvoker (arr) {
  return function (ev) {
    var arguments$1 = arguments;

    var single = arguments.length === 1;
    for (var i = 0; i < arr.length; i++) {
      single ? arr[i](ev) : arr[i].apply(null, arguments$1);
    }
  }
}

function fnInvoker (o) {
  return function (ev) {
    var single = arguments.length === 1;
    single ? o.fn(ev) : o.fn.apply(null, arguments);
  }
}

/*  */

function normalizeChildren (
  children,
  ns,
  nestedIndex
) {
  if (isPrimitive(children)) {
    return [createTextVNode(children)]
  }
  if (Array.isArray(children)) {
    var res = [];
    for (var i = 0, l = children.length; i < l; i++) {
      var c = children[i];
      var last = res[res.length - 1];
      //  nested
      if (Array.isArray(c)) {
        res.push.apply(res, normalizeChildren(c, ns, ((nestedIndex || '') + "_" + i)));
      } else if (isPrimitive(c)) {
        if (last && last.text) {
          last.text += String(c);
        } else if (c !== '') {
          // convert primitive to vnode
          res.push(createTextVNode(c));
        }
      } else if (c instanceof VNode) {
        if (c.text && last && last.text) {
          if (!last.isCloned) {
            last.text += c.text;
          }
        } else {
          // inherit parent namespace
          if (ns) {
            applyNS(c, ns);
          }
          // default key for nested array children (likely generated by v-for)
          if (c.tag && c.key == null && nestedIndex != null) {
            c.key = "__vlist" + nestedIndex + "_" + i + "__";
          }
          res.push(c);
        }
      }
    }
    return res
  }
}

function createTextVNode (val) {
  return new VNode(undefined, undefined, undefined, String(val))
}

function applyNS (vnode, ns) {
  if (vnode.tag && !vnode.ns) {
    vnode.ns = ns;
    if (vnode.children) {
      for (var i = 0, l = vnode.children.length; i < l; i++) {
        applyNS(vnode.children[i], ns);
      }
    }
  }
}

/*  */

function getFirstComponentChild (children) {
  return children && children.filter(function (c) { return c && c.componentOptions; })[0]
}

/*  */

var activeInstance = null;

function initLifecycle (vm) {
  var options = vm.$options;

  // locate first non-abstract parent
  var parent = options.parent;
  if (parent && !options.abstract) {
    while (parent.$options.abstract && parent.$parent) {
      parent = parent.$parent;
    }
    parent.$children.push(vm);
  }

  vm.$parent = parent;
  vm.$root = parent ? parent.$root : vm;

  vm.$children = [];
  vm.$refs = {};

  vm._watcher = null;
  vm._inactive = false;
  vm._isMounted = false;
  vm._isDestroyed = false;
  vm._isBeingDestroyed = false;
}

function lifecycleMixin (Vue) {
  Vue.prototype._mount = function (
    el,
    hydrating
  ) {
    var vm = this;
    vm.$el = el;
    if (!vm.$options.render) {
      vm.$options.render = emptyVNode;
      if (process.env.NODE_ENV !== 'production') {
        /* istanbul ignore if */
        if (vm.$options.template && vm.$options.template.charAt(0) !== '#') {
          warn(
            'You are using the runtime-only build of Vue where the template ' +
            'option is not available. Either pre-compile the templates into ' +
            'render functions, or use the compiler-included build.',
            vm
          );
        } else {
          warn(
            'Failed to mount component: template or render function not defined.',
            vm
          );
        }
      }
    }
    callHook(vm, 'beforeMount');
    vm._watcher = new Watcher(vm, function () {
      vm._update(vm._render(), hydrating);
    }, noop);
    hydrating = false;
    // manually mounted instance, call mounted on self
    // mounted is called for render-created child components in its inserted hook
    if (vm.$vnode == null) {
      vm._isMounted = true;
      callHook(vm, 'mounted');
    }
    return vm
  };

  Vue.prototype._update = function (vnode, hydrating) {
    var vm = this;
    if (vm._isMounted) {
      callHook(vm, 'beforeUpdate');
    }
    var prevEl = vm.$el;
    var prevActiveInstance = activeInstance;
    activeInstance = vm;
    var prevVnode = vm._vnode;
    vm._vnode = vnode;
    if (!prevVnode) {
      // Vue.prototype.__patch__ is injected in entry points
      // based on the rendering backend used.
      vm.$el = vm.__patch__(vm.$el, vnode, hydrating);
    } else {
      vm.$el = vm.__patch__(prevVnode, vnode);
    }
    activeInstance = prevActiveInstance;
    // update __vue__ reference
    if (prevEl) {
      prevEl.__vue__ = null;
    }
    if (vm.$el) {
      vm.$el.__vue__ = vm;
    }
    // if parent is an HOC, update its $el as well
    if (vm.$vnode && vm.$parent && vm.$vnode === vm.$parent._vnode) {
      vm.$parent.$el = vm.$el;
    }
    if (vm._isMounted) {
      callHook(vm, 'updated');
    }
  };

  Vue.prototype._updateFromParent = function (
    propsData,
    listeners,
    parentVnode,
    renderChildren
  ) {
    var vm = this;
    var hasChildren = !!(vm.$options._renderChildren || renderChildren);
    vm.$options._parentVnode = parentVnode;
    vm.$options._renderChildren = renderChildren;
    // update props
    if (propsData && vm.$options.props) {
      observerState.shouldConvert = false;
      if (process.env.NODE_ENV !== 'production') {
        observerState.isSettingProps = true;
      }
      var propKeys = vm.$options._propKeys || [];
      for (var i = 0; i < propKeys.length; i++) {
        var key = propKeys[i];
        vm[key] = validateProp(key, vm.$options.props, propsData, vm);
      }
      observerState.shouldConvert = true;
      if (process.env.NODE_ENV !== 'production') {
        observerState.isSettingProps = false;
      }
      vm.$options.propsData = propsData;
    }
    // update listeners
    if (listeners) {
      var oldListeners = vm.$options._parentListeners;
      vm.$options._parentListeners = listeners;
      vm._updateListeners(listeners, oldListeners);
    }
    // resolve slots + force update if has children
    if (hasChildren) {
      vm.$slots = resolveSlots(renderChildren, vm._renderContext);
      vm.$forceUpdate();
    }
  };

  Vue.prototype.$forceUpdate = function () {
    var vm = this;
    if (vm._watcher) {
      vm._watcher.update();
    }
  };

  Vue.prototype.$destroy = function () {
    var vm = this;
    if (vm._isBeingDestroyed) {
      return
    }
    callHook(vm, 'beforeDestroy');
    vm._isBeingDestroyed = true;
    // remove self from parent
    var parent = vm.$parent;
    if (parent && !parent._isBeingDestroyed && !vm.$options.abstract) {
      remove$1(parent.$children, vm);
    }
    // teardown watchers
    if (vm._watcher) {
      vm._watcher.teardown();
    }
    var i = vm._watchers.length;
    while (i--) {
      vm._watchers[i].teardown();
    }
    // remove reference from data ob
    // frozen object may not have observer.
    if (vm._data.__ob__) {
      vm._data.__ob__.vmCount--;
    }
    // call the last hook...
    vm._isDestroyed = true;
    callHook(vm, 'destroyed');
    // turn off all instance listeners.
    vm.$off();
    // remove __vue__ reference
    if (vm.$el) {
      vm.$el.__vue__ = null;
    }
    // invoke destroy hooks on current rendered tree
    vm.__patch__(vm._vnode, null);
  };
}

function callHook (vm, hook) {
  var handlers = vm.$options[hook];
  if (handlers) {
    for (var i = 0, j = handlers.length; i < j; i++) {
      handlers[i].call(vm);
    }
  }
  vm.$emit('hook:' + hook);
}

/*  */

var hooks = { init: init, prepatch: prepatch, insert: insert, destroy: destroy$1 };
var hooksToMerge = Object.keys(hooks);

function createComponent (
  Ctor,
  data,
  context,
  children,
  tag
) {
  if (!Ctor) {
    return
  }

  var baseCtor = context.$options._base;
  if (isObject(Ctor)) {
    Ctor = baseCtor.extend(Ctor);
  }

  if (typeof Ctor !== 'function') {
    if (process.env.NODE_ENV !== 'production') {
      warn(("Invalid Component definition: " + (String(Ctor))), context);
    }
    return
  }

  // async component
  if (!Ctor.cid) {
    if (Ctor.resolved) {
      Ctor = Ctor.resolved;
    } else {
      Ctor = resolveAsyncComponent(Ctor, baseCtor, function () {
        // it's ok to queue this on every render because
        // $forceUpdate is buffered by the scheduler.
        context.$forceUpdate();
      });
      if (!Ctor) {
        // return nothing if this is indeed an async component
        // wait for the callback to trigger parent update.
        return
      }
    }
  }

  // resolve constructor options in case global mixins are applied after
  // component constructor creation
  resolveConstructorOptions(Ctor);

  data = data || {};

  // extract props
  var propsData = extractProps(data, Ctor);

  // functional component
  if (Ctor.options.functional) {
    return createFunctionalComponent(Ctor, propsData, data, context, children)
  }

  // extract listeners, since these needs to be treated as
  // child component listeners instead of DOM listeners
  var listeners = data.on;
  // replace with listeners with .native modifier
  data.on = data.nativeOn;

  if (Ctor.options.abstract) {
    // abstract components do not keep anything
    // other than props & listeners
    data = {};
  }

  // merge component management hooks onto the placeholder node
  mergeHooks(data);

  // return a placeholder vnode
  var name = Ctor.options.name || tag;
  var vnode = new VNode(
    ("vue-component-" + (Ctor.cid) + (name ? ("-" + name) : '')),
    data, undefined, undefined, undefined, undefined, context,
    { Ctor: Ctor, propsData: propsData, listeners: listeners, tag: tag, children: children }
  );
  return vnode
}

function createFunctionalComponent (
  Ctor,
  propsData,
  data,
  context,
  children
) {
  var props = {};
  var propOptions = Ctor.options.props;
  if (propOptions) {
    for (var key in propOptions) {
      props[key] = validateProp(key, propOptions, propsData);
    }
  }
  var vnode = Ctor.options.render.call(
    null,
    // ensure the createElement function in functional components
    // gets a unique context - this is necessary for correct named slot check
    bind$1(createElement, { _self: Object.create(context) }),
    {
      props: props,
      data: data,
      parent: context,
      children: normalizeChildren(children),
      slots: function () { return resolveSlots(children, context); }
    }
  );
  if (vnode instanceof VNode) {
    vnode.functionalContext = context;
    if (data.slot) {
      (vnode.data || (vnode.data = {})).slot = data.slot;
    }
  }
  return vnode
}

function createComponentInstanceForVnode (
  vnode, // we know it's MountedComponentVNode but flow doesn't
  parent // activeInstance in lifecycle state
) {
  var vnodeComponentOptions = vnode.componentOptions;
  var options = {
    _isComponent: true,
    parent: parent,
    propsData: vnodeComponentOptions.propsData,
    _componentTag: vnodeComponentOptions.tag,
    _parentVnode: vnode,
    _parentListeners: vnodeComponentOptions.listeners,
    _renderChildren: vnodeComponentOptions.children
  };
  // check inline-template render functions
  var inlineTemplate = vnode.data.inlineTemplate;
  if (inlineTemplate) {
    options.render = inlineTemplate.render;
    options.staticRenderFns = inlineTemplate.staticRenderFns;
  }
  return new vnodeComponentOptions.Ctor(options)
}

function init (vnode, hydrating) {
  if (!vnode.child || vnode.child._isDestroyed) {
    var child = vnode.child = createComponentInstanceForVnode(vnode, activeInstance);
    child.$mount(hydrating ? vnode.elm : undefined, hydrating);
  }
}

function prepatch (
  oldVnode,
  vnode
) {
  var options = vnode.componentOptions;
  var child = vnode.child = oldVnode.child;
  child._updateFromParent(
    options.propsData, // updated props
    options.listeners, // updated listeners
    vnode, // new parent vnode
    options.children // new children
  );
}

function insert (vnode) {
  if (!vnode.child._isMounted) {
    vnode.child._isMounted = true;
    callHook(vnode.child, 'mounted');
  }
  if (vnode.data.keepAlive) {
    vnode.child._inactive = false;
    callHook(vnode.child, 'activated');
  }
}

function destroy$1 (vnode) {
  if (!vnode.child._isDestroyed) {
    if (!vnode.data.keepAlive) {
      vnode.child.$destroy();
    } else {
      vnode.child._inactive = true;
      callHook(vnode.child, 'deactivated');
    }
  }
}

function resolveAsyncComponent (
  factory,
  baseCtor,
  cb
) {
  if (factory.requested) {
    // pool callbacks
    factory.pendingCallbacks.push(cb);
  } else {
    factory.requested = true;
    var cbs = factory.pendingCallbacks = [cb];
    var sync = true;

    var resolve = function (res) {
      if (isObject(res)) {
        res = baseCtor.extend(res);
      }
      // cache resolved
      factory.resolved = res;
      // invoke callbacks only if this is not a synchronous resolve
      // (async resolves are shimmed as synchronous during SSR)
      if (!sync) {
        for (var i = 0, l = cbs.length; i < l; i++) {
          cbs[i](res);
        }
      }
    };

    var reject = function (reason) {
      process.env.NODE_ENV !== 'production' && warn(
        "Failed to resolve async component: " + (String(factory)) +
        (reason ? ("\nReason: " + reason) : '')
      );
    };

    var res = factory(resolve, reject);

    // handle promise
    if (res && typeof res.then === 'function' && !factory.resolved) {
      res.then(resolve, reject);
    }

    sync = false;
    // return in case resolved synchronously
    return factory.resolved
  }
}

function extractProps (data, Ctor) {
  // we are only extracting raw values here.
  // validation and default values are handled in the child
  // component itself.
  var propOptions = Ctor.options.props;
  if (!propOptions) {
    return
  }
  var res = {};
  var attrs = data.attrs;
  var props = data.props;
  var domProps = data.domProps;
  if (attrs || props || domProps) {
    for (var key in propOptions) {
      var altKey = hyphenate(key);
      checkProp(res, props, key, altKey, true) ||
      checkProp(res, attrs, key, altKey) ||
      checkProp(res, domProps, key, altKey);
    }
  }
  return res
}

function checkProp (
  res,
  hash,
  key,
  altKey,
  preserve
) {
  if (hash) {
    if (hasOwn(hash, key)) {
      res[key] = hash[key];
      if (!preserve) {
        delete hash[key];
      }
      return true
    } else if (hasOwn(hash, altKey)) {
      res[key] = hash[altKey];
      if (!preserve) {
        delete hash[altKey];
      }
      return true
    }
  }
  return false
}

function mergeHooks (data) {
  if (!data.hook) {
    data.hook = {};
  }
  for (var i = 0; i < hooksToMerge.length; i++) {
    var key = hooksToMerge[i];
    var fromParent = data.hook[key];
    var ours = hooks[key];
    data.hook[key] = fromParent ? mergeHook$1(ours, fromParent) : ours;
  }
}

function mergeHook$1 (a, b) {
  // since all hooks have at most two args, use fixed args
  // to avoid having to use fn.apply().
  return function (_, __) {
    a(_, __);
    b(_, __);
  }
}

/*  */

// wrapper function for providing a more flexible interface
// without getting yelled at by flow
function createElement (
  tag,
  data,
  children
) {
  if (data && (Array.isArray(data) || typeof data !== 'object')) {
    children = data;
    data = undefined;
  }
  // make sure to use real instance instead of proxy as context
  return _createElement(this._self, tag, data, children)
}

function _createElement (
  context,
  tag,
  data,
  children
) {
  if (data && data.__ob__) {
    process.env.NODE_ENV !== 'production' && warn(
      "Avoid using observed data object as vnode data: " + (JSON.stringify(data)) + "\n" +
      'Always create fresh vnode data objects in each render!',
      context
    );
    return
  }
  if (!tag) {
    // in case of component :is set to falsy value
    return emptyVNode()
  }
  if (typeof tag === 'string') {
    var Ctor;
    var ns = config.getTagNamespace(tag);
    if (config.isReservedTag(tag)) {
      // platform built-in elements
      return new VNode(
        tag, data, normalizeChildren(children, ns),
        undefined, undefined, ns, context
      )
    } else if ((Ctor = resolveAsset(context.$options, 'components', tag))) {
      // component
      return createComponent(Ctor, data, context, children, tag)
    } else {
      // unknown or unlisted namespaced elements
      // check at runtime because it may get assigned a namespace when its
      // parent normalizes children
      var childNs = tag === 'foreignObject' ? 'xhtml' : ns;
      return new VNode(
        tag, data, normalizeChildren(children, childNs),
        undefined, undefined, ns, context
      )
    }
  } else {
    // direct component options / constructor
    return createComponent(tag, data, context, children)
  }
}

/*  */

function initRender (vm) {
  vm.$vnode = null; // the placeholder node in parent tree
  vm._vnode = null; // the root of the child tree
  vm._staticTrees = null;
  vm._renderContext = vm.$options._parentVnode && vm.$options._parentVnode.context;
  vm.$slots = resolveSlots(vm.$options._renderChildren, vm._renderContext);
  // bind the public createElement fn to this instance
  // so that we get proper render context inside it.
  vm.$createElement = bind$1(createElement, vm);
  if (vm.$options.el) {
    vm.$mount(vm.$options.el);
  }
}

function renderMixin (Vue) {
  Vue.prototype.$nextTick = function (fn) {
    nextTick(fn, this);
  };

  Vue.prototype._render = function () {
    var vm = this;
    var ref = vm.$options;
    var render = ref.render;
    var staticRenderFns = ref.staticRenderFns;
    var _parentVnode = ref._parentVnode;

    if (vm._isMounted) {
      // clone slot nodes on re-renders
      for (var key in vm.$slots) {
        vm.$slots[key] = cloneVNodes(vm.$slots[key]);
      }
    }

    if (staticRenderFns && !vm._staticTrees) {
      vm._staticTrees = [];
    }
    // set parent vnode. this allows render functions to have access
    // to the data on the placeholder node.
    vm.$vnode = _parentVnode;
    // render self
    var vnode;
    try {
      vnode = render.call(vm._renderProxy, vm.$createElement);
    } catch (e) {
      if (process.env.NODE_ENV !== 'production') {
        warn(("Error when rendering " + (formatComponentName(vm)) + ":"));
      }
      /* istanbul ignore else */
      if (config.errorHandler) {
        config.errorHandler.call(null, e, vm);
      } else {
        if (config._isServer) {
          throw e
        } else {
          console.error(e);
        }
      }
      // return previous vnode to prevent render error causing blank component
      vnode = vm._vnode;
    }
    // return empty vnode in case the render function errored out
    if (!(vnode instanceof VNode)) {
      if (process.env.NODE_ENV !== 'production' && Array.isArray(vnode)) {
        warn(
          'Multiple root nodes returned from render function. Render function ' +
          'should return a single root node.',
          vm
        );
      }
      vnode = emptyVNode();
    }
    // set parent
    vnode.parent = _parentVnode;
    return vnode
  };

  // shorthands used in render functions
  Vue.prototype._h = createElement;
  // toString for mustaches
  Vue.prototype._s = _toString;
  // number conversion
  Vue.prototype._n = toNumber;
  // empty vnode
  Vue.prototype._e = emptyVNode;
  // loose equal
  Vue.prototype._q = looseEqual;
  // loose indexOf
  Vue.prototype._i = looseIndexOf;

  // render static tree by index
  Vue.prototype._m = function renderStatic (
    index,
    isInFor
  ) {
    var tree = this._staticTrees[index];
    // if has already-rendered static tree and not inside v-for,
    // we can reuse the same tree by doing a shallow clone.
    if (tree && !isInFor) {
      return Array.isArray(tree)
        ? cloneVNodes(tree)
        : cloneVNode(tree)
    }
    // otherwise, render a fresh tree.
    tree = this._staticTrees[index] = this.$options.staticRenderFns[index].call(this._renderProxy);
    markStatic(tree, ("__static__" + index), false);
    return tree
  };

  // mark node as static (v-once)
  Vue.prototype._o = function markOnce (
    tree,
    index,
    key
  ) {
    markStatic(tree, ("__once__" + index + (key ? ("_" + key) : "")), true);
    return tree
  };

  function markStatic (tree, key, isOnce) {
    if (Array.isArray(tree)) {
      for (var i = 0; i < tree.length; i++) {
        if (tree[i] && typeof tree[i] !== 'string') {
          markStaticNode(tree[i], (key + "_" + i), isOnce);
        }
      }
    } else {
      markStaticNode(tree, key, isOnce);
    }
  }

  function markStaticNode (node, key, isOnce) {
    node.isStatic = true;
    node.key = key;
    node.isOnce = isOnce;
  }

  // filter resolution helper
  var identity = function (_) { return _; };
  Vue.prototype._f = function resolveFilter (id) {
    return resolveAsset(this.$options, 'filters', id, true) || identity
  };

  // render v-for
  Vue.prototype._l = function renderList (
    val,
    render
  ) {
    var ret, i, l, keys, key;
    if (Array.isArray(val)) {
      ret = new Array(val.length);
      for (i = 0, l = val.length; i < l; i++) {
        ret[i] = render(val[i], i);
      }
    } else if (typeof val === 'number') {
      ret = new Array(val);
      for (i = 0; i < val; i++) {
        ret[i] = render(i + 1, i);
      }
    } else if (isObject(val)) {
      keys = Object.keys(val);
      ret = new Array(keys.length);
      for (i = 0, l = keys.length; i < l; i++) {
        key = keys[i];
        ret[i] = render(val[key], key, i);
      }
    }
    return ret
  };

  // renderSlot
  Vue.prototype._t = function (
    name,
    fallback
  ) {
    var slotNodes = this.$slots[name];
    // warn duplicate slot usage
    if (slotNodes && process.env.NODE_ENV !== 'production') {
      slotNodes._rendered && warn(
        "Duplicate presence of slot \"" + name + "\" found in the same render tree " +
        "- this will likely cause render errors.",
        this
      );
      slotNodes._rendered = true;
    }
    return slotNodes || fallback
  };

  // apply v-bind object
  Vue.prototype._b = function bindProps (
    data,
    value,
    asProp
  ) {
    if (value) {
      if (!isObject(value)) {
        process.env.NODE_ENV !== 'production' && warn(
          'v-bind without argument expects an Object or Array value',
          this
        );
      } else {
        if (Array.isArray(value)) {
          value = toObject(value);
        }
        for (var key in value) {
          if (key === 'class' || key === 'style') {
            data[key] = value[key];
          } else {
            var hash = asProp || config.mustUseProp(key)
              ? data.domProps || (data.domProps = {})
              : data.attrs || (data.attrs = {});
            hash[key] = value[key];
          }
        }
      }
    }
    return data
  };

  // expose v-on keyCodes
  Vue.prototype._k = function getKeyCodes (key) {
    return config.keyCodes[key]
  };
}

function resolveSlots (
  renderChildren,
  context
) {
  var slots = {};
  if (!renderChildren) {
    return slots
  }
  var children = normalizeChildren(renderChildren) || [];
  var defaultSlot = [];
  var name, child;
  for (var i = 0, l = children.length; i < l; i++) {
    child = children[i];
    // named slots should only be respected if the vnode was rendered in the
    // same context.
    if ((child.context === context || child.functionalContext === context) &&
        child.data && (name = child.data.slot)) {
      var slot = (slots[name] || (slots[name] = []));
      if (child.tag === 'template') {
        slot.push.apply(slot, child.children);
      } else {
        slot.push(child);
      }
    } else {
      defaultSlot.push(child);
    }
  }
  // ignore single whitespace
  if (defaultSlot.length && !(
    defaultSlot.length === 1 &&
    (defaultSlot[0].text === ' ' || defaultSlot[0].isComment)
  )) {
    slots.default = defaultSlot;
  }
  return slots
}

/*  */

function initEvents (vm) {
  vm._events = Object.create(null);
  // init parent attached events
  var listeners = vm.$options._parentListeners;
  var on = bind$1(vm.$on, vm);
  var off = bind$1(vm.$off, vm);
  vm._updateListeners = function (listeners, oldListeners) {
    updateListeners(listeners, oldListeners || {}, on, off, vm);
  };
  if (listeners) {
    vm._updateListeners(listeners);
  }
}

function eventsMixin (Vue) {
  Vue.prototype.$on = function (event, fn) {
    var vm = this;(vm._events[event] || (vm._events[event] = [])).push(fn);
    return vm
  };

  Vue.prototype.$once = function (event, fn) {
    var vm = this;
    function on () {
      vm.$off(event, on);
      fn.apply(vm, arguments);
    }
    on.fn = fn;
    vm.$on(event, on);
    return vm
  };

  Vue.prototype.$off = function (event, fn) {
    var vm = this;
    // all
    if (!arguments.length) {
      vm._events = Object.create(null);
      return vm
    }
    // specific event
    var cbs = vm._events[event];
    if (!cbs) {
      return vm
    }
    if (arguments.length === 1) {
      vm._events[event] = null;
      return vm
    }
    // specific handler
    var cb;
    var i = cbs.length;
    while (i--) {
      cb = cbs[i];
      if (cb === fn || cb.fn === fn) {
        cbs.splice(i, 1);
        break
      }
    }
    return vm
  };

  Vue.prototype.$emit = function (event) {
    var vm = this;
    var cbs = vm._events[event];
    if (cbs) {
      cbs = cbs.length > 1 ? toArray(cbs) : cbs;
      var args = toArray(arguments, 1);
      for (var i = 0, l = cbs.length; i < l; i++) {
        cbs[i].apply(vm, args);
      }
    }
    return vm
  };
}

/*  */

var uid = 0;

function initMixin (Vue) {
  Vue.prototype._init = function (options) {
    var vm = this;
    // a uid
    vm._uid = uid++;
    // a flag to avoid this being observed
    vm._isVue = true;
    // merge options
    if (options && options._isComponent) {
      // optimize internal component instantiation
      // since dynamic options merging is pretty slow, and none of the
      // internal component options needs special treatment.
      initInternalComponent(vm, options);
    } else {
      vm.$options = mergeOptions(
        resolveConstructorOptions(vm.constructor),
        options || {},
        vm
      );
    }
    /* istanbul ignore else */
    if (process.env.NODE_ENV !== 'production') {
      initProxy(vm);
    } else {
      vm._renderProxy = vm;
    }
    // expose real self
    vm._self = vm;
    initLifecycle(vm);
    initEvents(vm);
    callHook(vm, 'beforeCreate');
    initState(vm);
    callHook(vm, 'created');
    initRender(vm);
  };
}

function initInternalComponent (vm, options) {
  var opts = vm.$options = Object.create(vm.constructor.options);
  // doing this because it's faster than dynamic enumeration.
  opts.parent = options.parent;
  opts.propsData = options.propsData;
  opts._parentVnode = options._parentVnode;
  opts._parentListeners = options._parentListeners;
  opts._renderChildren = options._renderChildren;
  opts._componentTag = options._componentTag;
  if (options.render) {
    opts.render = options.render;
    opts.staticRenderFns = options.staticRenderFns;
  }
}

function resolveConstructorOptions (Ctor) {
  var options = Ctor.options;
  if (Ctor.super) {
    var superOptions = Ctor.super.options;
    var cachedSuperOptions = Ctor.superOptions;
    var extendOptions = Ctor.extendOptions;
    if (superOptions !== cachedSuperOptions) {
      // super option changed
      Ctor.superOptions = superOptions;
      extendOptions.render = options.render;
      extendOptions.staticRenderFns = options.staticRenderFns;
      options = Ctor.options = mergeOptions(superOptions, extendOptions);
      if (options.name) {
        options.components[options.name] = Ctor;
      }
    }
  }
  return options
}

function Vue$2 (options) {
  if (process.env.NODE_ENV !== 'production' &&
    !(this instanceof Vue$2)) {
    warn('Vue is a constructor and should be called with the `new` keyword');
  }
  this._init(options);
}

initMixin(Vue$2);
stateMixin(Vue$2);
eventsMixin(Vue$2);
lifecycleMixin(Vue$2);
renderMixin(Vue$2);

var warn = noop;
var formatComponentName;

if (process.env.NODE_ENV !== 'production') {
  var hasConsole = typeof console !== 'undefined';

  warn = function (msg, vm) {
    if (hasConsole && (!config.silent)) {
      console.error("[Vue warn]: " + msg + " " + (
        vm ? formatLocation(formatComponentName(vm)) : ''
      ));
    }
  };

  formatComponentName = function (vm) {
    if (vm.$root === vm) {
      return 'root instance'
    }
    var name = vm._isVue
      ? vm.$options.name || vm.$options._componentTag
      : vm.name;
    return (
      (name ? ("component <" + name + ">") : "anonymous component") +
      (vm._isVue && vm.$options.__file ? (" at " + (vm.$options.__file)) : '')
    )
  };

  var formatLocation = function (str) {
    if (str === 'anonymous component') {
      str += " - use the \"name\" option for better debugging messages.";
    }
    return ("\n(found in " + str + ")")
  };
}

/*  */

/**
 * Option overwriting strategies are functions that handle
 * how to merge a parent option value and a child option
 * value into the final value.
 */
var strats = config.optionMergeStrategies;

/**
 * Options with restrictions
 */
if (process.env.NODE_ENV !== 'production') {
  strats.el = strats.propsData = function (parent, child, vm, key) {
    if (!vm) {
      warn(
        "option \"" + key + "\" can only be used during instance " +
        'creation with the `new` keyword.'
      );
    }
    return defaultStrat(parent, child)
  };
}

/**
 * Helper that recursively merges two data objects together.
 */
function mergeData (to, from) {
  if (!from) { return to }
  var key, toVal, fromVal;
  var keys = Object.keys(from);
  for (var i = 0; i < keys.length; i++) {
    key = keys[i];
    toVal = to[key];
    fromVal = from[key];
    if (!hasOwn(to, key)) {
      set(to, key, fromVal);
    } else if (isPlainObject(toVal) && isPlainObject(fromVal)) {
      mergeData(toVal, fromVal);
    }
  }
  return to
}

/**
 * Data
 */
strats.data = function (
  parentVal,
  childVal,
  vm
) {
  if (!vm) {
    // in a Vue.extend merge, both should be functions
    if (!childVal) {
      return parentVal
    }
    if (typeof childVal !== 'function') {
      process.env.NODE_ENV !== 'production' && warn(
        'The "data" option should be a function ' +
        'that returns a per-instance value in component ' +
        'definitions.',
        vm
      );
      return parentVal
    }
    if (!parentVal) {
      return childVal
    }
    // when parentVal & childVal are both present,
    // we need to return a function that returns the
    // merged result of both functions... no need to
    // check if parentVal is a function here because
    // it has to be a function to pass previous merges.
    return function mergedDataFn () {
      return mergeData(
        childVal.call(this),
        parentVal.call(this)
      )
    }
  } else if (parentVal || childVal) {
    return function mergedInstanceDataFn () {
      // instance merge
      var instanceData = typeof childVal === 'function'
        ? childVal.call(vm)
        : childVal;
      var defaultData = typeof parentVal === 'function'
        ? parentVal.call(vm)
        : undefined;
      if (instanceData) {
        return mergeData(instanceData, defaultData)
      } else {
        return defaultData
      }
    }
  }
};

/**
 * Hooks and param attributes are merged as arrays.
 */
function mergeHook (
  parentVal,
  childVal
) {
  return childVal
    ? parentVal
      ? parentVal.concat(childVal)
      : Array.isArray(childVal)
        ? childVal
        : [childVal]
    : parentVal
}

config._lifecycleHooks.forEach(function (hook) {
  strats[hook] = mergeHook;
});

/**
 * Assets
 *
 * When a vm is present (instance creation), we need to do
 * a three-way merge between constructor options, instance
 * options and parent options.
 */
function mergeAssets (parentVal, childVal) {
  var res = Object.create(parentVal || null);
  return childVal
    ? extend(res, childVal)
    : res
}

config._assetTypes.forEach(function (type) {
  strats[type + 's'] = mergeAssets;
});

/**
 * Watchers.
 *
 * Watchers hashes should not overwrite one
 * another, so we merge them as arrays.
 */
strats.watch = function (parentVal, childVal) {
  /* istanbul ignore if */
  if (!childVal) { return parentVal }
  if (!parentVal) { return childVal }
  var ret = {};
  extend(ret, parentVal);
  for (var key in childVal) {
    var parent = ret[key];
    var child = childVal[key];
    if (parent && !Array.isArray(parent)) {
      parent = [parent];
    }
    ret[key] = parent
      ? parent.concat(child)
      : [child];
  }
  return ret
};

/**
 * Other object hashes.
 */
strats.props =
strats.methods =
strats.computed = function (parentVal, childVal) {
  if (!childVal) { return parentVal }
  if (!parentVal) { return childVal }
  var ret = Object.create(null);
  extend(ret, parentVal);
  extend(ret, childVal);
  return ret
};

/**
 * Default strategy.
 */
var defaultStrat = function (parentVal, childVal) {
  return childVal === undefined
    ? parentVal
    : childVal
};

/**
 * Validate component names
 */
function checkComponents (options) {
  for (var key in options.components) {
    var lower = key.toLowerCase();
    if (isBuiltInTag(lower) || config.isReservedTag(lower)) {
      warn(
        'Do not use built-in or reserved HTML elements as component ' +
        'id: ' + key
      );
    }
  }
}

/**
 * Ensure all props option syntax are normalized into the
 * Object-based format.
 */
function normalizeProps (options) {
  var props = options.props;
  if (!props) { return }
  var res = {};
  var i, val, name;
  if (Array.isArray(props)) {
    i = props.length;
    while (i--) {
      val = props[i];
      if (typeof val === 'string') {
        name = camelize(val);
        res[name] = { type: null };
      } else if (process.env.NODE_ENV !== 'production') {
        warn('props must be strings when using array syntax.');
      }
    }
  } else if (isPlainObject(props)) {
    for (var key in props) {
      val = props[key];
      name = camelize(key);
      res[name] = isPlainObject(val)
        ? val
        : { type: val };
    }
  }
  options.props = res;
}

/**
 * Normalize raw function directives into object format.
 */
function normalizeDirectives (options) {
  var dirs = options.directives;
  if (dirs) {
    for (var key in dirs) {
      var def = dirs[key];
      if (typeof def === 'function') {
        dirs[key] = { bind: def, update: def };
      }
    }
  }
}

/**
 * Merge two option objects into a new one.
 * Core utility used in both instantiation and inheritance.
 */
function mergeOptions (
  parent,
  child,
  vm
) {
  if (process.env.NODE_ENV !== 'production') {
    checkComponents(child);
  }
  normalizeProps(child);
  normalizeDirectives(child);
  var extendsFrom = child.extends;
  if (extendsFrom) {
    parent = typeof extendsFrom === 'function'
      ? mergeOptions(parent, extendsFrom.options, vm)
      : mergeOptions(parent, extendsFrom, vm);
  }
  if (child.mixins) {
    for (var i = 0, l = child.mixins.length; i < l; i++) {
      var mixin = child.mixins[i];
      if (mixin.prototype instanceof Vue$2) {
        mixin = mixin.options;
      }
      parent = mergeOptions(parent, mixin, vm);
    }
  }
  var options = {};
  var key;
  for (key in parent) {
    mergeField(key);
  }
  for (key in child) {
    if (!hasOwn(parent, key)) {
      mergeField(key);
    }
  }
  function mergeField (key) {
    var strat = strats[key] || defaultStrat;
    options[key] = strat(parent[key], child[key], vm, key);
  }
  return options
}

/**
 * Resolve an asset.
 * This function is used because child instances need access
 * to assets defined in its ancestor chain.
 */
function resolveAsset (
  options,
  type,
  id,
  warnMissing
) {
  /* istanbul ignore if */
  if (typeof id !== 'string') {
    return
  }
  var assets = options[type];
  var res = assets[id] ||
    // camelCase ID
    assets[camelize(id)] ||
    // Pascal Case ID
    assets[capitalize(camelize(id))];
  if (process.env.NODE_ENV !== 'production' && warnMissing && !res) {
    warn(
      'Failed to resolve ' + type.slice(0, -1) + ': ' + id,
      options
    );
  }
  return res
}

/*  */

function validateProp (
  key,
  propOptions,
  propsData,
  vm
) {
  var prop = propOptions[key];
  var absent = !hasOwn(propsData, key);
  var value = propsData[key];
  // handle boolean props
  if (isBooleanType(prop.type)) {
    if (absent && !hasOwn(prop, 'default')) {
      value = false;
    } else if (value === '' || value === hyphenate(key)) {
      value = true;
    }
  }
  // check default value
  if (value === undefined) {
    value = getPropDefaultValue(vm, prop, key);
    // since the default value is a fresh copy,
    // make sure to observe it.
    var prevShouldConvert = observerState.shouldConvert;
    observerState.shouldConvert = true;
    observe(value);
    observerState.shouldConvert = prevShouldConvert;
  }
  if (process.env.NODE_ENV !== 'production') {
    assertProp(prop, key, value, vm, absent);
  }
  return value
}

/**
 * Get the default value of a prop.
 */
function getPropDefaultValue (vm, prop, key) {
  // no default, return undefined
  if (!hasOwn(prop, 'default')) {
    return undefined
  }
  var def = prop.default;
  // warn against non-factory defaults for Object & Array
  if (isObject(def)) {
    process.env.NODE_ENV !== 'production' && warn(
      'Invalid default value for prop "' + key + '": ' +
      'Props with type Object/Array must use a factory function ' +
      'to return the default value.',
      vm
    );
  }
  // the raw prop value was also undefined from previous render,
  // return previous default value to avoid unnecessary watcher trigger
  if (vm && vm.$options.propsData &&
    vm.$options.propsData[key] === undefined &&
    vm[key] !== undefined) {
    return vm[key]
  }
  // call factory function for non-Function types
  return typeof def === 'function' && prop.type !== Function
    ? def.call(vm)
    : def
}

/**
 * Assert whether a prop is valid.
 */
function assertProp (
  prop,
  name,
  value,
  vm,
  absent
) {
  if (prop.required && absent) {
    warn(
      'Missing required prop: "' + name + '"',
      vm
    );
    return
  }
  if (value == null && !prop.required) {
    return
  }
  var type = prop.type;
  var valid = !type || type === true;
  var expectedTypes = [];
  if (type) {
    if (!Array.isArray(type)) {
      type = [type];
    }
    for (var i = 0; i < type.length && !valid; i++) {
      var assertedType = assertType(value, type[i]);
      expectedTypes.push(assertedType.expectedType);
      valid = assertedType.valid;
    }
  }
  if (!valid) {
    warn(
      'Invalid prop: type check failed for prop "' + name + '".' +
      ' Expected ' + expectedTypes.map(capitalize).join(', ') +
      ', got ' + Object.prototype.toString.call(value).slice(8, -1) + '.',
      vm
    );
    return
  }
  var validator = prop.validator;
  if (validator) {
    if (!validator(value)) {
      warn(
        'Invalid prop: custom validator check failed for prop "' + name + '".',
        vm
      );
    }
  }
}

/**
 * Assert the type of a value
 */
function assertType (value, type) {
  var valid;
  var expectedType = getType(type);
  if (expectedType === 'String') {
    valid = typeof value === (expectedType = 'string');
  } else if (expectedType === 'Number') {
    valid = typeof value === (expectedType = 'number');
  } else if (expectedType === 'Boolean') {
    valid = typeof value === (expectedType = 'boolean');
  } else if (expectedType === 'Function') {
    valid = typeof value === (expectedType = 'function');
  } else if (expectedType === 'Object') {
    valid = isPlainObject(value);
  } else if (expectedType === 'Array') {
    valid = Array.isArray(value);
  } else {
    valid = value instanceof type;
  }
  return {
    valid: valid,
    expectedType: expectedType
  }
}

/**
 * Use function string name to check built-in types,
 * because a simple equality check will fail when running
 * across different vms / iframes.
 */
function getType (fn) {
  var match = fn && fn.toString().match(/^\s*function (\w+)/);
  return match && match[1]
}

function isBooleanType (fn) {
  if (!Array.isArray(fn)) {
    return getType(fn) === 'Boolean'
  }
  for (var i = 0, len = fn.length; i < len; i++) {
    if (getType(fn[i]) === 'Boolean') {
      return true
    }
  }
  /* istanbul ignore next */
  return false
}



var util = Object.freeze({
	defineReactive: defineReactive$$1,
	_toString: _toString,
	toNumber: toNumber,
	makeMap: makeMap,
	isBuiltInTag: isBuiltInTag,
	remove: remove$1,
	hasOwn: hasOwn,
	isPrimitive: isPrimitive,
	cached: cached,
	camelize: camelize,
	capitalize: capitalize,
	hyphenate: hyphenate,
	bind: bind$1,
	toArray: toArray,
	extend: extend,
	isObject: isObject,
	isPlainObject: isPlainObject,
	toObject: toObject,
	noop: noop,
	no: no,
	genStaticKeys: genStaticKeys,
	looseEqual: looseEqual,
	looseIndexOf: looseIndexOf,
	isReserved: isReserved,
	def: def,
	parsePath: parsePath,
	hasProto: hasProto,
	inBrowser: inBrowser,
	UA: UA,
	isIE: isIE,
	isIE9: isIE9,
	isEdge: isEdge,
	isAndroid: isAndroid,
	isIOS: isIOS,
	devtools: devtools,
	nextTick: nextTick,
	get _Set () { return _Set; },
	mergeOptions: mergeOptions,
	resolveAsset: resolveAsset,
	get warn () { return warn; },
	get formatComponentName () { return formatComponentName; },
	validateProp: validateProp
});

/*  */

function initUse (Vue) {
  Vue.use = function (plugin) {
    /* istanbul ignore if */
    if (plugin.installed) {
      return
    }
    // additional parameters
    var args = toArray(arguments, 1);
    args.unshift(this);
    if (typeof plugin.install === 'function') {
      plugin.install.apply(plugin, args);
    } else {
      plugin.apply(null, args);
    }
    plugin.installed = true;
    return this
  };
}

/*  */

function initMixin$1 (Vue) {
  Vue.mixin = function (mixin) {
    this.options = mergeOptions(this.options, mixin);
  };
}

/*  */

function initExtend (Vue) {
  /**
   * Each instance constructor, including Vue, has a unique
   * cid. This enables us to create wrapped "child
   * constructors" for prototypal inheritance and cache them.
   */
  Vue.cid = 0;
  var cid = 1;

  /**
   * Class inheritance
   */
  Vue.extend = function (extendOptions) {
    extendOptions = extendOptions || {};
    var Super = this;
    var SuperId = Super.cid;
    var cachedCtors = extendOptions._Ctor || (extendOptions._Ctor = {});
    if (cachedCtors[SuperId]) {
      return cachedCtors[SuperId]
    }
    var name = extendOptions.name || Super.options.name;
    if (process.env.NODE_ENV !== 'production') {
      if (!/^[a-zA-Z][\w-]*$/.test(name)) {
        warn(
          'Invalid component name: "' + name + '". Component names ' +
          'can only contain alphanumeric characaters and the hyphen.'
        );
      }
    }
    var Sub = function VueComponent (options) {
      this._init(options);
    };
    Sub.prototype = Object.create(Super.prototype);
    Sub.prototype.constructor = Sub;
    Sub.cid = cid++;
    Sub.options = mergeOptions(
      Super.options,
      extendOptions
    );
    Sub['super'] = Super;
    // allow further extension/mixin/plugin usage
    Sub.extend = Super.extend;
    Sub.mixin = Super.mixin;
    Sub.use = Super.use;
    // create asset registers, so extended classes
    // can have their private assets too.
    config._assetTypes.forEach(function (type) {
      Sub[type] = Super[type];
    });
    // enable recursive self-lookup
    if (name) {
      Sub.options.components[name] = Sub;
    }
    // keep a reference to the super options at extension time.
    // later at instantiation we can check if Super's options have
    // been updated.
    Sub.superOptions = Super.options;
    Sub.extendOptions = extendOptions;
    // cache constructor
    cachedCtors[SuperId] = Sub;
    return Sub
  };
}

/*  */

function initAssetRegisters (Vue) {
  /**
   * Create asset registration methods.
   */
  config._assetTypes.forEach(function (type) {
    Vue[type] = function (
      id,
      definition
    ) {
      if (!definition) {
        return this.options[type + 's'][id]
      } else {
        /* istanbul ignore if */
        if (process.env.NODE_ENV !== 'production') {
          if (type === 'component' && config.isReservedTag(id)) {
            warn(
              'Do not use built-in or reserved HTML elements as component ' +
              'id: ' + id
            );
          }
        }
        if (type === 'component' && isPlainObject(definition)) {
          definition.name = definition.name || id;
          definition = this.options._base.extend(definition);
        }
        if (type === 'directive' && typeof definition === 'function') {
          definition = { bind: definition, update: definition };
        }
        this.options[type + 's'][id] = definition;
        return definition
      }
    };
  });
}

var KeepAlive = {
  name: 'keep-alive',
  abstract: true,
  created: function created () {
    this.cache = Object.create(null);
  },
  render: function render () {
    var vnode = getFirstComponentChild(this.$slots.default);
    if (vnode && vnode.componentOptions) {
      var opts = vnode.componentOptions;
      var key = vnode.key == null
        // same constructor may get registered as different local components
        // so cid alone is not enough (#3269)
        ? opts.Ctor.cid + '::' + opts.tag
        : vnode.key;
      if (this.cache[key]) {
        vnode.child = this.cache[key].child;
      } else {
        this.cache[key] = vnode;
      }
      vnode.data.keepAlive = true;
    }
    return vnode
  },
  destroyed: function destroyed () {
    var this$1 = this;

    for (var key in this.cache) {
      var vnode = this$1.cache[key];
      callHook(vnode.child, 'deactivated');
      vnode.child.$destroy();
    }
  }
};

var builtInComponents = {
  KeepAlive: KeepAlive
};

/*  */

function initGlobalAPI (Vue) {
  // config
  var configDef = {};
  configDef.get = function () { return config; };
  if (process.env.NODE_ENV !== 'production') {
    configDef.set = function () {
      warn(
        'Do not replace the Vue.config object, set individual fields instead.'
      );
    };
  }
  Object.defineProperty(Vue, 'config', configDef);
  Vue.util = util;
  Vue.set = set;
  Vue.delete = del;
  Vue.nextTick = nextTick;

  Vue.options = Object.create(null);
  config._assetTypes.forEach(function (type) {
    Vue.options[type + 's'] = Object.create(null);
  });

  // this is used to identify the "base" constructor to extend all plain-object
  // components with in Weex's multi-instance scenarios.
  Vue.options._base = Vue;

  extend(Vue.options.components, builtInComponents);

  initUse(Vue);
  initMixin$1(Vue);
  initExtend(Vue);
  initAssetRegisters(Vue);
}

initGlobalAPI(Vue$2);

Object.defineProperty(Vue$2.prototype, '$isServer', {
  get: function () { return config._isServer; }
});

Vue$2.version = '2.0.7';

/*  */

// attributes that should be using props for binding
var mustUseProp = makeMap('value,selected,checked,muted');

var isEnumeratedAttr = makeMap('contenteditable,draggable,spellcheck');

var isBooleanAttr = makeMap(
  'allowfullscreen,async,autofocus,autoplay,checked,compact,controls,declare,' +
  'default,defaultchecked,defaultmuted,defaultselected,defer,disabled,' +
  'enabled,formnovalidate,hidden,indeterminate,inert,ismap,itemscope,loop,multiple,' +
  'muted,nohref,noresize,noshade,novalidate,nowrap,open,pauseonexit,readonly,' +
  'required,reversed,scoped,seamless,selected,sortable,translate,' +
  'truespeed,typemustmatch,visible'
);

var isAttr = makeMap(
  'accept,accept-charset,accesskey,action,align,alt,async,autocomplete,' +
  'autofocus,autoplay,autosave,bgcolor,border,buffered,challenge,charset,' +
  'checked,cite,class,code,codebase,color,cols,colspan,content,http-equiv,' +
  'name,contenteditable,contextmenu,controls,coords,data,datetime,default,' +
  'defer,dir,dirname,disabled,download,draggable,dropzone,enctype,method,for,' +
  'form,formaction,headers,<th>,height,hidden,high,href,hreflang,http-equiv,' +
  'icon,id,ismap,itemprop,keytype,kind,label,lang,language,list,loop,low,' +
  'manifest,max,maxlength,media,method,GET,POST,min,multiple,email,file,' +
  'muted,name,novalidate,open,optimum,pattern,ping,placeholder,poster,' +
  'preload,radiogroup,readonly,rel,required,reversed,rows,rowspan,sandbox,' +
  'scope,scoped,seamless,selected,shape,size,type,text,password,sizes,span,' +
  'spellcheck,src,srcdoc,srclang,srcset,start,step,style,summary,tabindex,' +
  'target,title,type,usemap,value,width,wrap'
);



var xlinkNS = 'http://www.w3.org/1999/xlink';

var isXlink = function (name) {
  return name.charAt(5) === ':' && name.slice(0, 5) === 'xlink'
};

var getXlinkProp = function (name) {
  return isXlink(name) ? name.slice(6, name.length) : ''
};

var isFalsyAttrValue = function (val) {
  return val == null || val === false
};

/*  */

function genClassForVnode (vnode) {
  var data = vnode.data;
  var parentNode = vnode;
  var childNode = vnode;
  while (childNode.child) {
    childNode = childNode.child._vnode;
    if (childNode.data) {
      data = mergeClassData(childNode.data, data);
    }
  }
  while ((parentNode = parentNode.parent)) {
    if (parentNode.data) {
      data = mergeClassData(data, parentNode.data);
    }
  }
  return genClassFromData(data)
}

function mergeClassData (child, parent) {
  return {
    staticClass: concat(child.staticClass, parent.staticClass),
    class: child.class
      ? [child.class, parent.class]
      : parent.class
  }
}

function genClassFromData (data) {
  var dynamicClass = data.class;
  var staticClass = data.staticClass;
  if (staticClass || dynamicClass) {
    return concat(staticClass, stringifyClass(dynamicClass))
  }
  /* istanbul ignore next */
  return ''
}

function concat (a, b) {
  return a ? b ? (a + ' ' + b) : a : (b || '')
}

function stringifyClass (value) {
  var res = '';
  if (!value) {
    return res
  }
  if (typeof value === 'string') {
    return value
  }
  if (Array.isArray(value)) {
    var stringified;
    for (var i = 0, l = value.length; i < l; i++) {
      if (value[i]) {
        if ((stringified = stringifyClass(value[i]))) {
          res += stringified + ' ';
        }
      }
    }
    return res.slice(0, -1)
  }
  if (isObject(value)) {
    for (var key in value) {
      if (value[key]) { res += key + ' '; }
    }
    return res.slice(0, -1)
  }
  /* istanbul ignore next */
  return res
}

/*  */

var namespaceMap = {
  svg: 'http://www.w3.org/2000/svg',
  math: 'http://www.w3.org/1998/Math/MathML',
  xhtml: 'http://www.w3.org/1999/xhtml'
};

var isHTMLTag = makeMap(
  'html,body,base,head,link,meta,style,title,' +
  'address,article,aside,footer,header,h1,h2,h3,h4,h5,h6,hgroup,nav,section,' +
  'div,dd,dl,dt,figcaption,figure,hr,img,li,main,ol,p,pre,ul,' +
  'a,b,abbr,bdi,bdo,br,cite,code,data,dfn,em,i,kbd,mark,q,rp,rt,rtc,ruby,' +
  's,samp,small,span,strong,sub,sup,time,u,var,wbr,area,audio,map,track,video,' +
  'embed,object,param,source,canvas,script,noscript,del,ins,' +
  'caption,col,colgroup,table,thead,tbody,td,th,tr,' +
  'button,datalist,fieldset,form,input,label,legend,meter,optgroup,option,' +
  'output,progress,select,textarea,' +
  'details,dialog,menu,menuitem,summary,' +
  'content,element,shadow,template'
);

var isUnaryTag = makeMap(
  'area,base,br,col,embed,frame,hr,img,input,isindex,keygen,' +
  'link,meta,param,source,track,wbr',
  true
);

// Elements that you can, intentionally, leave open
// (and which close themselves)
var canBeLeftOpenTag = makeMap(
  'colgroup,dd,dt,li,options,p,td,tfoot,th,thead,tr,source',
  true
);

// HTML5 tags https://html.spec.whatwg.org/multipage/indices.html#elements-3
// Phrasing Content https://html.spec.whatwg.org/multipage/dom.html#phrasing-content
var isNonPhrasingTag = makeMap(
  'address,article,aside,base,blockquote,body,caption,col,colgroup,dd,' +
  'details,dialog,div,dl,dt,fieldset,figcaption,figure,footer,form,' +
  'h1,h2,h3,h4,h5,h6,head,header,hgroup,hr,html,legend,li,menuitem,meta,' +
  'optgroup,option,param,rp,rt,source,style,summary,tbody,td,tfoot,th,thead,' +
  'title,tr,track',
  true
);

// this map is intentionally selective, only covering SVG elements that may
// contain child elements.
var isSVG = makeMap(
  'svg,animate,circle,clippath,cursor,defs,desc,ellipse,filter,font,' +
  'font-face,g,glyph,image,line,marker,mask,missing-glyph,path,pattern,' +
  'polygon,polyline,rect,switch,symbol,text,textpath,tspan,use,view',
  true
);



var isReservedTag = function (tag) {
  return isHTMLTag(tag) || isSVG(tag)
};

function getTagNamespace (tag) {
  if (isSVG(tag)) {
    return 'svg'
  }
  // basic support for MathML
  // note it doesn't support other MathML elements being component roots
  if (tag === 'math') {
    return 'math'
  }
}

var unknownElementCache = Object.create(null);
function isUnknownElement (tag) {
  /* istanbul ignore if */
  if (!inBrowser) {
    return true
  }
  if (isReservedTag(tag)) {
    return false
  }
  tag = tag.toLowerCase();
  /* istanbul ignore if */
  if (unknownElementCache[tag] != null) {
    return unknownElementCache[tag]
  }
  var el = document.createElement(tag);
  if (tag.indexOf('-') > -1) {
    // http://stackoverflow.com/a/28210364/1070244
    return (unknownElementCache[tag] = (
      el.constructor === window.HTMLUnknownElement ||
      el.constructor === window.HTMLElement
    ))
  } else {
    return (unknownElementCache[tag] = /HTMLUnknownElement/.test(el.toString()))
  }
}

/*  */

/**
 * Query an element selector if it's not an element already.
 */
function query (el) {
  if (typeof el === 'string') {
    var selector = el;
    el = document.querySelector(el);
    if (!el) {
      process.env.NODE_ENV !== 'production' && warn(
        'Cannot find element: ' + selector
      );
      return document.createElement('div')
    }
  }
  return el
}

/*  */

function createElement$1 (tagName, vnode) {
  var elm = document.createElement(tagName);
  if (tagName !== 'select') {
    return elm
  }
  if (vnode.data && vnode.data.attrs && 'multiple' in vnode.data.attrs) {
    elm.setAttribute('multiple', 'multiple');
  }
  return elm
}

function createElementNS (namespace, tagName) {
  return document.createElementNS(namespaceMap[namespace], tagName)
}

function createTextNode (text) {
  return document.createTextNode(text)
}

function createComment (text) {
  return document.createComment(text)
}

function insertBefore (parentNode, newNode, referenceNode) {
  parentNode.insertBefore(newNode, referenceNode);
}

function removeChild (node, child) {
  node.removeChild(child);
}

function appendChild (node, child) {
  node.appendChild(child);
}

function parentNode (node) {
  return node.parentNode
}

function nextSibling (node) {
  return node.nextSibling
}

function tagName (node) {
  return node.tagName
}

function setTextContent (node, text) {
  node.textContent = text;
}

function childNodes (node) {
  return node.childNodes
}

function setAttribute (node, key, val) {
  node.setAttribute(key, val);
}


var nodeOps = Object.freeze({
	createElement: createElement$1,
	createElementNS: createElementNS,
	createTextNode: createTextNode,
	createComment: createComment,
	insertBefore: insertBefore,
	removeChild: removeChild,
	appendChild: appendChild,
	parentNode: parentNode,
	nextSibling: nextSibling,
	tagName: tagName,
	setTextContent: setTextContent,
	childNodes: childNodes,
	setAttribute: setAttribute
});

/*  */

var ref = {
  create: function create (_, vnode) {
    registerRef(vnode);
  },
  update: function update (oldVnode, vnode) {
    if (oldVnode.data.ref !== vnode.data.ref) {
      registerRef(oldVnode, true);
      registerRef(vnode);
    }
  },
  destroy: function destroy (vnode) {
    registerRef(vnode, true);
  }
};

function registerRef (vnode, isRemoval) {
  var key = vnode.data.ref;
  if (!key) { return }

  var vm = vnode.context;
  var ref = vnode.child || vnode.elm;
  var refs = vm.$refs;
  if (isRemoval) {
    if (Array.isArray(refs[key])) {
      remove$1(refs[key], ref);
    } else if (refs[key] === ref) {
      refs[key] = undefined;
    }
  } else {
    if (vnode.data.refInFor) {
      if (Array.isArray(refs[key])) {
        refs[key].push(ref);
      } else {
        refs[key] = [ref];
      }
    } else {
      refs[key] = ref;
    }
  }
}

/**
 * Virtual DOM patching algorithm based on Snabbdom by
 * Simon Friis Vindum (@paldepind)
 * Licensed under the MIT License
 * https://github.com/paldepind/snabbdom/blob/master/LICENSE
 *
 * modified by Evan You (@yyx990803)
 *

/*
 * Not type-checking this because this file is perf-critical and the cost
 * of making flow understand it is not worth it.
 */

var emptyNode = new VNode('', {}, []);

var hooks$1 = ['create', 'update', 'remove', 'destroy'];

function isUndef (s) {
  return s == null
}

function isDef (s) {
  return s != null
}

function sameVnode (vnode1, vnode2) {
  return (
    vnode1.key === vnode2.key &&
    vnode1.tag === vnode2.tag &&
    vnode1.isComment === vnode2.isComment &&
    !vnode1.data === !vnode2.data
  )
}

function createKeyToOldIdx (children, beginIdx, endIdx) {
  var i, key;
  var map = {};
  for (i = beginIdx; i <= endIdx; ++i) {
    key = children[i].key;
    if (isDef(key)) { map[key] = i; }
  }
  return map
}

function createPatchFunction (backend) {
  var i, j;
  var cbs = {};

  var modules = backend.modules;
  var nodeOps = backend.nodeOps;

  for (i = 0; i < hooks$1.length; ++i) {
    cbs[hooks$1[i]] = [];
    for (j = 0; j < modules.length; ++j) {
      if (modules[j][hooks$1[i]] !== undefined) { cbs[hooks$1[i]].push(modules[j][hooks$1[i]]); }
    }
  }

  function emptyNodeAt (elm) {
    return new VNode(nodeOps.tagName(elm).toLowerCase(), {}, [], undefined, elm)
  }

  function createRmCb (childElm, listeners) {
    function remove$$1 () {
      if (--remove$$1.listeners === 0) {
        removeElement(childElm);
      }
    }
    remove$$1.listeners = listeners;
    return remove$$1
  }

  function removeElement (el) {
    var parent = nodeOps.parentNode(el);
    // element may have already been removed due to v-html
    if (parent) {
      nodeOps.removeChild(parent, el);
    }
  }

  function createElm (vnode, insertedVnodeQueue, nested) {
    var i;
    var data = vnode.data;
    vnode.isRootInsert = !nested;
    if (isDef(data)) {
      if (isDef(i = data.hook) && isDef(i = i.init)) { i(vnode); }
      // after calling the init hook, if the vnode is a child component
      // it should've created a child instance and mounted it. the child
      // component also has set the placeholder vnode's elm.
      // in that case we can just return the element and be done.
      if (isDef(i = vnode.child)) {
        initComponent(vnode, insertedVnodeQueue);
        return vnode.elm
      }
    }
    var children = vnode.children;
    var tag = vnode.tag;
    if (isDef(tag)) {
      if (process.env.NODE_ENV !== 'production') {
        if (
          !vnode.ns &&
          !(config.ignoredElements && config.ignoredElements.indexOf(tag) > -1) &&
          config.isUnknownElement(tag)
        ) {
          warn(
            'Unknown custom element: <' + tag + '> - did you ' +
            'register the component correctly? For recursive components, ' +
            'make sure to provide the "name" option.',
            vnode.context
          );
        }
      }
      vnode.elm = vnode.ns
        ? nodeOps.createElementNS(vnode.ns, tag)
        : nodeOps.createElement(tag, vnode);
      setScope(vnode);
      createChildren(vnode, children, insertedVnodeQueue);
      if (isDef(data)) {
        invokeCreateHooks(vnode, insertedVnodeQueue);
      }
    } else if (vnode.isComment) {
      vnode.elm = nodeOps.createComment(vnode.text);
    } else {
      vnode.elm = nodeOps.createTextNode(vnode.text);
    }
    return vnode.elm
  }

  function createChildren (vnode, children, insertedVnodeQueue) {
    if (Array.isArray(children)) {
      for (var i = 0; i < children.length; ++i) {
        nodeOps.appendChild(vnode.elm, createElm(children[i], insertedVnodeQueue, true));
      }
    } else if (isPrimitive(vnode.text)) {
      nodeOps.appendChild(vnode.elm, nodeOps.createTextNode(vnode.text));
    }
  }

  function isPatchable (vnode) {
    while (vnode.child) {
      vnode = vnode.child._vnode;
    }
    return isDef(vnode.tag)
  }

  function invokeCreateHooks (vnode, insertedVnodeQueue) {
    for (var i$1 = 0; i$1 < cbs.create.length; ++i$1) {
      cbs.create[i$1](emptyNode, vnode);
    }
    i = vnode.data.hook; // Reuse variable
    if (isDef(i)) {
      if (i.create) { i.create(emptyNode, vnode); }
      if (i.insert) { insertedVnodeQueue.push(vnode); }
    }
  }

  function initComponent (vnode, insertedVnodeQueue) {
    if (vnode.data.pendingInsert) {
      insertedVnodeQueue.push.apply(insertedVnodeQueue, vnode.data.pendingInsert);
    }
    vnode.elm = vnode.child.$el;
    if (isPatchable(vnode)) {
      invokeCreateHooks(vnode, insertedVnodeQueue);
      setScope(vnode);
    } else {
      // empty component root.
      // skip all element-related modules except for ref (#3455)
      registerRef(vnode);
      // make sure to invoke the insert hook
      insertedVnodeQueue.push(vnode);
    }
  }

  // set scope id attribute for scoped CSS.
  // this is implemented as a special case to avoid the overhead
  // of going through the normal attribute patching process.
  function setScope (vnode) {
    var i;
    if (isDef(i = vnode.context) && isDef(i = i.$options._scopeId)) {
      nodeOps.setAttribute(vnode.elm, i, '');
    }
    if (isDef(i = activeInstance) &&
        i !== vnode.context &&
        isDef(i = i.$options._scopeId)) {
      nodeOps.setAttribute(vnode.elm, i, '');
    }
  }

  function addVnodes (parentElm, before, vnodes, startIdx, endIdx, insertedVnodeQueue) {
    for (; startIdx <= endIdx; ++startIdx) {
      nodeOps.insertBefore(parentElm, createElm(vnodes[startIdx], insertedVnodeQueue), before);
    }
  }

  function invokeDestroyHook (vnode) {
    var i, j;
    var data = vnode.data;
    if (isDef(data)) {
      if (isDef(i = data.hook) && isDef(i = i.destroy)) { i(vnode); }
      for (i = 0; i < cbs.destroy.length; ++i) { cbs.destroy[i](vnode); }
    }
    if (isDef(i = vnode.children)) {
      for (j = 0; j < vnode.children.length; ++j) {
        invokeDestroyHook(vnode.children[j]);
      }
    }
  }

  function removeVnodes (parentElm, vnodes, startIdx, endIdx) {
    for (; startIdx <= endIdx; ++startIdx) {
      var ch = vnodes[startIdx];
      if (isDef(ch)) {
        if (isDef(ch.tag)) {
          removeAndInvokeRemoveHook(ch);
          invokeDestroyHook(ch);
        } else { // Text node
          nodeOps.removeChild(parentElm, ch.elm);
        }
      }
    }
  }

  function removeAndInvokeRemoveHook (vnode, rm) {
    if (rm || isDef(vnode.data)) {
      var listeners = cbs.remove.length + 1;
      if (!rm) {
        // directly removing
        rm = createRmCb(vnode.elm, listeners);
      } else {
        // we have a recursively passed down rm callback
        // increase the listeners count
        rm.listeners += listeners;
      }
      // recursively invoke hooks on child component root node
      if (isDef(i = vnode.child) && isDef(i = i._vnode) && isDef(i.data)) {
        removeAndInvokeRemoveHook(i, rm);
      }
      for (i = 0; i < cbs.remove.length; ++i) {
        cbs.remove[i](vnode, rm);
      }
      if (isDef(i = vnode.data.hook) && isDef(i = i.remove)) {
        i(vnode, rm);
      } else {
        rm();
      }
    } else {
      removeElement(vnode.elm);
    }
  }

  function updateChildren (parentElm, oldCh, newCh, insertedVnodeQueue, removeOnly) {
    var oldStartIdx = 0;
    var newStartIdx = 0;
    var oldEndIdx = oldCh.length - 1;
    var oldStartVnode = oldCh[0];
    var oldEndVnode = oldCh[oldEndIdx];
    var newEndIdx = newCh.length - 1;
    var newStartVnode = newCh[0];
    var newEndVnode = newCh[newEndIdx];
    var oldKeyToIdx, idxInOld, elmToMove, before;

    // removeOnly is a special flag used only by <transition-group>
    // to ensure removed elements stay in correct relative positions
    // during leaving transitions
    var canMove = !removeOnly;

    while (oldStartIdx <= oldEndIdx && newStartIdx <= newEndIdx) {
      if (isUndef(oldStartVnode)) {
        oldStartVnode = oldCh[++oldStartIdx]; // Vnode has been moved left
      } else if (isUndef(oldEndVnode)) {
        oldEndVnode = oldCh[--oldEndIdx];
      } else if (sameVnode(oldStartVnode, newStartVnode)) {
        patchVnode(oldStartVnode, newStartVnode, insertedVnodeQueue);
        oldStartVnode = oldCh[++oldStartIdx];
        newStartVnode = newCh[++newStartIdx];
      } else if (sameVnode(oldEndVnode, newEndVnode)) {
        patchVnode(oldEndVnode, newEndVnode, insertedVnodeQueue);
        oldEndVnode = oldCh[--oldEndIdx];
        newEndVnode = newCh[--newEndIdx];
      } else if (sameVnode(oldStartVnode, newEndVnode)) { // Vnode moved right
        patchVnode(oldStartVnode, newEndVnode, insertedVnodeQueue);
        canMove && nodeOps.insertBefore(parentElm, oldStartVnode.elm, nodeOps.nextSibling(oldEndVnode.elm));
        oldStartVnode = oldCh[++oldStartIdx];
        newEndVnode = newCh[--newEndIdx];
      } else if (sameVnode(oldEndVnode, newStartVnode)) { // Vnode moved left
        patchVnode(oldEndVnode, newStartVnode, insertedVnodeQueue);
        canMove && nodeOps.insertBefore(parentElm, oldEndVnode.elm, oldStartVnode.elm);
        oldEndVnode = oldCh[--oldEndIdx];
        newStartVnode = newCh[++newStartIdx];
      } else {
        if (isUndef(oldKeyToIdx)) { oldKeyToIdx = createKeyToOldIdx(oldCh, oldStartIdx, oldEndIdx); }
        idxInOld = isDef(newStartVnode.key) ? oldKeyToIdx[newStartVnode.key] : null;
        if (isUndef(idxInOld)) { // New element
          nodeOps.insertBefore(parentElm, createElm(newStartVnode, insertedVnodeQueue), oldStartVnode.elm);
          newStartVnode = newCh[++newStartIdx];
        } else {
          elmToMove = oldCh[idxInOld];
          /* istanbul ignore if */
          if (process.env.NODE_ENV !== 'production' && !elmToMove) {
            warn(
              'It seems there are duplicate keys that is causing an update error. ' +
              'Make sure each v-for item has a unique key.'
            );
          }
          if (elmToMove.tag !== newStartVnode.tag) {
            // same key but different element. treat as new element
            nodeOps.insertBefore(parentElm, createElm(newStartVnode, insertedVnodeQueue), oldStartVnode.elm);
            newStartVnode = newCh[++newStartIdx];
          } else {
            patchVnode(elmToMove, newStartVnode, insertedVnodeQueue);
            oldCh[idxInOld] = undefined;
            canMove && nodeOps.insertBefore(parentElm, newStartVnode.elm, oldStartVnode.elm);
            newStartVnode = newCh[++newStartIdx];
          }
        }
      }
    }
    if (oldStartIdx > oldEndIdx) {
      before = isUndef(newCh[newEndIdx + 1]) ? null : newCh[newEndIdx + 1].elm;
      addVnodes(parentElm, before, newCh, newStartIdx, newEndIdx, insertedVnodeQueue);
    } else if (newStartIdx > newEndIdx) {
      removeVnodes(parentElm, oldCh, oldStartIdx, oldEndIdx);
    }
  }

  function patchVnode (oldVnode, vnode, insertedVnodeQueue, removeOnly) {
    if (oldVnode === vnode) {
      return
    }
    // reuse element for static trees.
    // note we only do this if the vnode is cloned -
    // if the new node is not cloned it means the render functions have been
    // reset by the hot-reload-api and we need to do a proper re-render.
    if (vnode.isStatic &&
        oldVnode.isStatic &&
        vnode.key === oldVnode.key &&
        (vnode.isCloned || vnode.isOnce)) {
      vnode.elm = oldVnode.elm;
      return
    }
    var i;
    var data = vnode.data;
    var hasData = isDef(data);
    if (hasData && isDef(i = data.hook) && isDef(i = i.prepatch)) {
      i(oldVnode, vnode);
    }
    var elm = vnode.elm = oldVnode.elm;
    var oldCh = oldVnode.children;
    var ch = vnode.children;
    if (hasData && isPatchable(vnode)) {
      for (i = 0; i < cbs.update.length; ++i) { cbs.update[i](oldVnode, vnode); }
      if (isDef(i = data.hook) && isDef(i = i.update)) { i(oldVnode, vnode); }
    }
    if (isUndef(vnode.text)) {
      if (isDef(oldCh) && isDef(ch)) {
        if (oldCh !== ch) { updateChildren(elm, oldCh, ch, insertedVnodeQueue, removeOnly); }
      } else if (isDef(ch)) {
        if (isDef(oldVnode.text)) { nodeOps.setTextContent(elm, ''); }
        addVnodes(elm, null, ch, 0, ch.length - 1, insertedVnodeQueue);
      } else if (isDef(oldCh)) {
        removeVnodes(elm, oldCh, 0, oldCh.length - 1);
      } else if (isDef(oldVnode.text)) {
        nodeOps.setTextContent(elm, '');
      }
    } else if (oldVnode.text !== vnode.text) {
      nodeOps.setTextContent(elm, vnode.text);
    }
    if (hasData) {
      if (isDef(i = data.hook) && isDef(i = i.postpatch)) { i(oldVnode, vnode); }
    }
  }

  function invokeInsertHook (vnode, queue, initial) {
    // delay insert hooks for component root nodes, invoke them after the
    // element is really inserted
    if (initial && vnode.parent) {
      vnode.parent.data.pendingInsert = queue;
    } else {
      for (var i = 0; i < queue.length; ++i) {
        queue[i].data.hook.insert(queue[i]);
      }
    }
  }

  var bailed = false;
  function hydrate (elm, vnode, insertedVnodeQueue) {
    if (process.env.NODE_ENV !== 'production') {
      if (!assertNodeMatch(elm, vnode)) {
        return false
      }
    }
    vnode.elm = elm;
    var tag = vnode.tag;
    var data = vnode.data;
    var children = vnode.children;
    if (isDef(data)) {
      if (isDef(i = data.hook) && isDef(i = i.init)) { i(vnode, true /* hydrating */); }
      if (isDef(i = vnode.child)) {
        // child component. it should have hydrated its own tree.
        initComponent(vnode, insertedVnodeQueue);
        return true
      }
    }
    if (isDef(tag)) {
      if (isDef(children)) {
        var childNodes = nodeOps.childNodes(elm);
        // empty element, allow client to pick up and populate children
        if (!childNodes.length) {
          createChildren(vnode, children, insertedVnodeQueue);
        } else {
          var childrenMatch = true;
          if (childNodes.length !== children.length) {
            childrenMatch = false;
          } else {
            for (var i$1 = 0; i$1 < children.length; i$1++) {
              if (!hydrate(childNodes[i$1], children[i$1], insertedVnodeQueue)) {
                childrenMatch = false;
                break
              }
            }
          }
          if (!childrenMatch) {
            if (process.env.NODE_ENV !== 'production' &&
                typeof console !== 'undefined' &&
                !bailed) {
              bailed = true;
              console.warn('Parent: ', elm);
              console.warn('Mismatching childNodes vs. VNodes: ', childNodes, children);
            }
            return false
          }
        }
      }
      if (isDef(data)) {
        invokeCreateHooks(vnode, insertedVnodeQueue);
      }
    }
    return true
  }

  function assertNodeMatch (node, vnode) {
    if (vnode.tag) {
      return (
        vnode.tag.indexOf('vue-component') === 0 ||
        vnode.tag.toLowerCase() === nodeOps.tagName(node).toLowerCase()
      )
    } else {
      return _toString(vnode.text) === node.data
    }
  }

  return function patch (oldVnode, vnode, hydrating, removeOnly) {
    if (!vnode) {
      if (oldVnode) { invokeDestroyHook(oldVnode); }
      return
    }

    var elm, parent;
    var isInitialPatch = false;
    var insertedVnodeQueue = [];

    if (!oldVnode) {
      // empty mount, create new root element
      isInitialPatch = true;
      createElm(vnode, insertedVnodeQueue);
    } else {
      var isRealElement = isDef(oldVnode.nodeType);
      if (!isRealElement && sameVnode(oldVnode, vnode)) {
        patchVnode(oldVnode, vnode, insertedVnodeQueue, removeOnly);
      } else {
        if (isRealElement) {
          // mounting to a real element
          // check if this is server-rendered content and if we can perform
          // a successful hydration.
          if (oldVnode.nodeType === 1 && oldVnode.hasAttribute('server-rendered')) {
            oldVnode.removeAttribute('server-rendered');
            hydrating = true;
          }
          if (hydrating) {
            if (hydrate(oldVnode, vnode, insertedVnodeQueue)) {
              invokeInsertHook(vnode, insertedVnodeQueue, true);
              return oldVnode
            } else if (process.env.NODE_ENV !== 'production') {
              warn(
                'The client-side rendered virtual DOM tree is not matching ' +
                'server-rendered content. This is likely caused by incorrect ' +
                'HTML markup, for example nesting block-level elements inside ' +
                '<p>, or missing <tbody>. Bailing hydration and performing ' +
                'full client-side render.'
              );
            }
          }
          // either not server-rendered, or hydration failed.
          // create an empty node and replace it
          oldVnode = emptyNodeAt(oldVnode);
        }
        elm = oldVnode.elm;
        parent = nodeOps.parentNode(elm);

        createElm(vnode, insertedVnodeQueue);

        // component root element replaced.
        // update parent placeholder node element.
        if (vnode.parent) {
          vnode.parent.elm = vnode.elm;
          if (isPatchable(vnode)) {
            for (var i = 0; i < cbs.create.length; ++i) {
              cbs.create[i](emptyNode, vnode.parent);
            }
          }
        }

        if (parent !== null) {
          nodeOps.insertBefore(parent, vnode.elm, nodeOps.nextSibling(elm));
          removeVnodes(parent, [oldVnode], 0, 0);
        } else if (isDef(oldVnode.tag)) {
          invokeDestroyHook(oldVnode);
        }
      }
    }

    invokeInsertHook(vnode, insertedVnodeQueue, isInitialPatch);
    return vnode.elm
  }
}

/*  */

var directives = {
  create: updateDirectives,
  update: updateDirectives,
  destroy: function unbindDirectives (vnode) {
    updateDirectives(vnode, emptyNode);
  }
};

function updateDirectives (
  oldVnode,
  vnode
) {
  if (!oldVnode.data.directives && !vnode.data.directives) {
    return
  }
  var isCreate = oldVnode === emptyNode;
  var oldDirs = normalizeDirectives$1(oldVnode.data.directives, oldVnode.context);
  var newDirs = normalizeDirectives$1(vnode.data.directives, vnode.context);

  var dirsWithInsert = [];
  var dirsWithPostpatch = [];

  var key, oldDir, dir;
  for (key in newDirs) {
    oldDir = oldDirs[key];
    dir = newDirs[key];
    if (!oldDir) {
      // new directive, bind
      callHook$1(dir, 'bind', vnode, oldVnode);
      if (dir.def && dir.def.inserted) {
        dirsWithInsert.push(dir);
      }
    } else {
      // existing directive, update
      dir.oldValue = oldDir.value;
      callHook$1(dir, 'update', vnode, oldVnode);
      if (dir.def && dir.def.componentUpdated) {
        dirsWithPostpatch.push(dir);
      }
    }
  }

  if (dirsWithInsert.length) {
    var callInsert = function () {
      dirsWithInsert.forEach(function (dir) {
        callHook$1(dir, 'inserted', vnode, oldVnode);
      });
    };
    if (isCreate) {
      mergeVNodeHook(vnode.data.hook || (vnode.data.hook = {}), 'insert', callInsert, 'dir-insert');
    } else {
      callInsert();
    }
  }

  if (dirsWithPostpatch.length) {
    mergeVNodeHook(vnode.data.hook || (vnode.data.hook = {}), 'postpatch', function () {
      dirsWithPostpatch.forEach(function (dir) {
        callHook$1(dir, 'componentUpdated', vnode, oldVnode);
      });
    }, 'dir-postpatch');
  }

  if (!isCreate) {
    for (key in oldDirs) {
      if (!newDirs[key]) {
        // no longer present, unbind
        callHook$1(oldDirs[key], 'unbind', oldVnode);
      }
    }
  }
}

var emptyModifiers = Object.create(null);

function normalizeDirectives$1 (
  dirs,
  vm
) {
  var res = Object.create(null);
  if (!dirs) {
    return res
  }
  var i, dir;
  for (i = 0; i < dirs.length; i++) {
    dir = dirs[i];
    if (!dir.modifiers) {
      dir.modifiers = emptyModifiers;
    }
    res[getRawDirName(dir)] = dir;
    dir.def = resolveAsset(vm.$options, 'directives', dir.name, true);
  }
  return res
}

function getRawDirName (dir) {
  return dir.rawName || ((dir.name) + "." + (Object.keys(dir.modifiers || {}).join('.')))
}

function callHook$1 (dir, hook, vnode, oldVnode) {
  var fn = dir.def && dir.def[hook];
  if (fn) {
    fn(vnode.elm, dir, vnode, oldVnode);
  }
}

var baseModules = [
  ref,
  directives
];

/*  */

function updateAttrs (oldVnode, vnode) {
  if (!oldVnode.data.attrs && !vnode.data.attrs) {
    return
  }
  var key, cur, old;
  var elm = vnode.elm;
  var oldAttrs = oldVnode.data.attrs || {};
  var attrs = vnode.data.attrs || {};
  // clone observed objects, as the user probably wants to mutate it
  if (attrs.__ob__) {
    attrs = vnode.data.attrs = extend({}, attrs);
  }

  for (key in attrs) {
    cur = attrs[key];
    old = oldAttrs[key];
    if (old !== cur) {
      setAttr(elm, key, cur);
    }
  }
  for (key in oldAttrs) {
    if (attrs[key] == null) {
      if (isXlink(key)) {
        elm.removeAttributeNS(xlinkNS, getXlinkProp(key));
      } else if (!isEnumeratedAttr(key)) {
        elm.removeAttribute(key);
      }
    }
  }
}

function setAttr (el, key, value) {
  if (isBooleanAttr(key)) {
    // set attribute for blank value
    // e.g. <option disabled>Select one</option>
    if (isFalsyAttrValue(value)) {
      el.removeAttribute(key);
    } else {
      el.setAttribute(key, key);
    }
  } else if (isEnumeratedAttr(key)) {
    el.setAttribute(key, isFalsyAttrValue(value) || value === 'false' ? 'false' : 'true');
  } else if (isXlink(key)) {
    if (isFalsyAttrValue(value)) {
      el.removeAttributeNS(xlinkNS, getXlinkProp(key));
    } else {
      el.setAttributeNS(xlinkNS, key, value);
    }
  } else {
    if (isFalsyAttrValue(value)) {
      el.removeAttribute(key);
    } else {
      el.setAttribute(key, value);
    }
  }
}

var attrs = {
  create: updateAttrs,
  update: updateAttrs
};

/*  */

function updateClass (oldVnode, vnode) {
  var el = vnode.elm;
  var data = vnode.data;
  var oldData = oldVnode.data;
  if (!data.staticClass && !data.class &&
      (!oldData || (!oldData.staticClass && !oldData.class))) {
    return
  }

  var cls = genClassForVnode(vnode);

  // handle transition classes
  var transitionClass = el._transitionClasses;
  if (transitionClass) {
    cls = concat(cls, stringifyClass(transitionClass));
  }

  // set the class
  if (cls !== el._prevClass) {
    el.setAttribute('class', cls);
    el._prevClass = cls;
  }
}

var klass = {
  create: updateClass,
  update: updateClass
};

// skip type checking this file because we need to attach private properties
// to elements

function updateDOMListeners (oldVnode, vnode) {
  if (!oldVnode.data.on && !vnode.data.on) {
    return
  }
  var on = vnode.data.on || {};
  var oldOn = oldVnode.data.on || {};
  var add = vnode.elm._v_add || (vnode.elm._v_add = function (event, handler, capture) {
    vnode.elm.addEventListener(event, handler, capture);
  });
  var remove = vnode.elm._v_remove || (vnode.elm._v_remove = function (event, handler) {
    vnode.elm.removeEventListener(event, handler);
  });
  updateListeners(on, oldOn, add, remove, vnode.context);
}

var events = {
  create: updateDOMListeners,
  update: updateDOMListeners
};

/*  */

function updateDOMProps (oldVnode, vnode) {
  if (!oldVnode.data.domProps && !vnode.data.domProps) {
    return
  }
  var key, cur;
  var elm = vnode.elm;
  var oldProps = oldVnode.data.domProps || {};
  var props = vnode.data.domProps || {};
  // clone observed objects, as the user probably wants to mutate it
  if (props.__ob__) {
    props = vnode.data.domProps = extend({}, props);
  }

  for (key in oldProps) {
    if (props[key] == null) {
      elm[key] = '';
    }
  }
  for (key in props) {
    // ignore children if the node has textContent or innerHTML,
    // as these will throw away existing DOM nodes and cause removal errors
    // on subsequent patches (#3360)
    if ((key === 'textContent' || key === 'innerHTML') && vnode.children) {
      vnode.children.length = 0;
    }
    cur = props[key];
    if (key === 'value') {
      // store value as _value as well since
      // non-string values will be stringified
      elm._value = cur;
      // avoid resetting cursor position when value is the same
      var strCur = cur == null ? '' : String(cur);
      if (elm.value !== strCur && !elm.composing) {
        elm.value = strCur;
      }
    } else {
      elm[key] = cur;
    }
  }
}

var domProps = {
  create: updateDOMProps,
  update: updateDOMProps
};

/*  */

var parseStyleText = cached(function (cssText) {
  var res = {};
  var hasBackground = cssText.indexOf('background') >= 0;
  // maybe with background-image: url(http://xxx) or base64 img
  var listDelimiter = hasBackground ? /;(?![^(]*\))/g : ';';
  var propertyDelimiter = hasBackground ? /:(.+)/ : ':';
  cssText.split(listDelimiter).forEach(function (item) {
    if (item) {
      var tmp = item.split(propertyDelimiter);
      tmp.length > 1 && (res[tmp[0].trim()] = tmp[1].trim());
    }
  });
  return res
});

// merge static and dynamic style data on the same vnode
function normalizeStyleData (data) {
  var style = normalizeStyleBinding(data.style);
  // static style is pre-processed into an object during compilation
  // and is always a fresh object, so it's safe to merge into it
  return data.staticStyle
    ? extend(data.staticStyle, style)
    : style
}

// normalize possible array / string values into Object
function normalizeStyleBinding (bindingStyle) {
  if (Array.isArray(bindingStyle)) {
    return toObject(bindingStyle)
  }
  if (typeof bindingStyle === 'string') {
    return parseStyleText(bindingStyle)
  }
  return bindingStyle
}

/**
 * parent component style should be after child's
 * so that parent component's style could override it
 */
function getStyle (vnode, checkChild) {
  var res = {};
  var styleData;

  if (checkChild) {
    var childNode = vnode;
    while (childNode.child) {
      childNode = childNode.child._vnode;
      if (childNode.data && (styleData = normalizeStyleData(childNode.data))) {
        extend(res, styleData);
      }
    }
  }

  if ((styleData = normalizeStyleData(vnode.data))) {
    extend(res, styleData);
  }

  var parentNode = vnode;
  while ((parentNode = parentNode.parent)) {
    if (parentNode.data && (styleData = normalizeStyleData(parentNode.data))) {
      extend(res, styleData);
    }
  }
  return res
}

/*  */

var cssVarRE = /^--/;
var setProp = function (el, name, val) {
  /* istanbul ignore if */
  if (cssVarRE.test(name)) {
    el.style.setProperty(name, val);
  } else {
    el.style[normalize(name)] = val;
  }
};

var prefixes = ['Webkit', 'Moz', 'ms'];

var testEl;
var normalize = cached(function (prop) {
  testEl = testEl || document.createElement('div');
  prop = camelize(prop);
  if (prop !== 'filter' && (prop in testEl.style)) {
    return prop
  }
  var upper = prop.charAt(0).toUpperCase() + prop.slice(1);
  for (var i = 0; i < prefixes.length; i++) {
    var prefixed = prefixes[i] + upper;
    if (prefixed in testEl.style) {
      return prefixed
    }
  }
});

function updateStyle (oldVnode, vnode) {
  var data = vnode.data;
  var oldData = oldVnode.data;

  if (!data.staticStyle && !data.style &&
      !oldData.staticStyle && !oldData.style) {
    return
  }

  var cur, name;
  var el = vnode.elm;
  var oldStyle = oldVnode.data.style || {};
  var style = normalizeStyleBinding(vnode.data.style) || {};

  vnode.data.style = style.__ob__ ? extend({}, style) : style;

  var newStyle = getStyle(vnode, true);

  for (name in oldStyle) {
    if (newStyle[name] == null) {
      setProp(el, name, '');
    }
  }
  for (name in newStyle) {
    cur = newStyle[name];
    if (cur !== oldStyle[name]) {
      // ie9 setting to null has no effect, must use empty string
      setProp(el, name, cur == null ? '' : cur);
    }
  }
}

var style = {
  create: updateStyle,
  update: updateStyle
};

/*  */

/**
 * Add class with compatibility for SVG since classList is not supported on
 * SVG elements in IE
 */
function addClass (el, cls) {
  /* istanbul ignore if */
  if (!cls || !cls.trim()) {
    return
  }

  /* istanbul ignore else */
  if (el.classList) {
    if (cls.indexOf(' ') > -1) {
      cls.split(/\s+/).forEach(function (c) { return el.classList.add(c); });
    } else {
      el.classList.add(cls);
    }
  } else {
    var cur = ' ' + el.getAttribute('class') + ' ';
    if (cur.indexOf(' ' + cls + ' ') < 0) {
      el.setAttribute('class', (cur + cls).trim());
    }
  }
}

/**
 * Remove class with compatibility for SVG since classList is not supported on
 * SVG elements in IE
 */
function removeClass (el, cls) {
  /* istanbul ignore if */
  if (!cls || !cls.trim()) {
    return
  }

  /* istanbul ignore else */
  if (el.classList) {
    if (cls.indexOf(' ') > -1) {
      cls.split(/\s+/).forEach(function (c) { return el.classList.remove(c); });
    } else {
      el.classList.remove(cls);
    }
  } else {
    var cur = ' ' + el.getAttribute('class') + ' ';
    var tar = ' ' + cls + ' ';
    while (cur.indexOf(tar) >= 0) {
      cur = cur.replace(tar, ' ');
    }
    el.setAttribute('class', cur.trim());
  }
}

/*  */

var hasTransition = inBrowser && !isIE9;
var TRANSITION = 'transition';
var ANIMATION = 'animation';

// Transition property/event sniffing
var transitionProp = 'transition';
var transitionEndEvent = 'transitionend';
var animationProp = 'animation';
var animationEndEvent = 'animationend';
if (hasTransition) {
  /* istanbul ignore if */
  if (window.ontransitionend === undefined &&
    window.onwebkittransitionend !== undefined) {
    transitionProp = 'WebkitTransition';
    transitionEndEvent = 'webkitTransitionEnd';
  }
  if (window.onanimationend === undefined &&
    window.onwebkitanimationend !== undefined) {
    animationProp = 'WebkitAnimation';
    animationEndEvent = 'webkitAnimationEnd';
  }
}

var raf = (inBrowser && window.requestAnimationFrame) || setTimeout;
function nextFrame (fn) {
  raf(function () {
    raf(fn);
  });
}

function addTransitionClass (el, cls) {
  (el._transitionClasses || (el._transitionClasses = [])).push(cls);
  addClass(el, cls);
}

function removeTransitionClass (el, cls) {
  if (el._transitionClasses) {
    remove$1(el._transitionClasses, cls);
  }
  removeClass(el, cls);
}

function whenTransitionEnds (
  el,
  expectedType,
  cb
) {
  var ref = getTransitionInfo(el, expectedType);
  var type = ref.type;
  var timeout = ref.timeout;
  var propCount = ref.propCount;
  if (!type) { return cb() }
  var event = type === TRANSITION ? transitionEndEvent : animationEndEvent;
  var ended = 0;
  var end = function () {
    el.removeEventListener(event, onEnd);
    cb();
  };
  var onEnd = function (e) {
    if (e.target === el) {
      if (++ended >= propCount) {
        end();
      }
    }
  };
  setTimeout(function () {
    if (ended < propCount) {
      end();
    }
  }, timeout + 1);
  el.addEventListener(event, onEnd);
}

var transformRE = /\b(transform|all)(,|$)/;

function getTransitionInfo (el, expectedType) {
  var styles = window.getComputedStyle(el);
  var transitioneDelays = styles[transitionProp + 'Delay'].split(', ');
  var transitionDurations = styles[transitionProp + 'Duration'].split(', ');
  var transitionTimeout = getTimeout(transitioneDelays, transitionDurations);
  var animationDelays = styles[animationProp + 'Delay'].split(', ');
  var animationDurations = styles[animationProp + 'Duration'].split(', ');
  var animationTimeout = getTimeout(animationDelays, animationDurations);

  var type;
  var timeout = 0;
  var propCount = 0;
  /* istanbul ignore if */
  if (expectedType === TRANSITION) {
    if (transitionTimeout > 0) {
      type = TRANSITION;
      timeout = transitionTimeout;
      propCount = transitionDurations.length;
    }
  } else if (expectedType === ANIMATION) {
    if (animationTimeout > 0) {
      type = ANIMATION;
      timeout = animationTimeout;
      propCount = animationDurations.length;
    }
  } else {
    timeout = Math.max(transitionTimeout, animationTimeout);
    type = timeout > 0
      ? transitionTimeout > animationTimeout
        ? TRANSITION
        : ANIMATION
      : null;
    propCount = type
      ? type === TRANSITION
        ? transitionDurations.length
        : animationDurations.length
      : 0;
  }
  var hasTransform =
    type === TRANSITION &&
    transformRE.test(styles[transitionProp + 'Property']);
  return {
    type: type,
    timeout: timeout,
    propCount: propCount,
    hasTransform: hasTransform
  }
}

function getTimeout (delays, durations) {
  /* istanbul ignore next */
  while (delays.length < durations.length) {
    delays = delays.concat(delays);
  }

  return Math.max.apply(null, durations.map(function (d, i) {
    return toMs(d) + toMs(delays[i])
  }))
}

function toMs (s) {
  return Number(s.slice(0, -1)) * 1000
}

/*  */

function enter (vnode) {
  var el = vnode.elm;

  // call leave callback now
  if (el._leaveCb) {
    el._leaveCb.cancelled = true;
    el._leaveCb();
  }

  var data = resolveTransition(vnode.data.transition);
  if (!data) {
    return
  }

  /* istanbul ignore if */
  if (el._enterCb || el.nodeType !== 1) {
    return
  }

  var css = data.css;
  var type = data.type;
  var enterClass = data.enterClass;
  var enterActiveClass = data.enterActiveClass;
  var appearClass = data.appearClass;
  var appearActiveClass = data.appearActiveClass;
  var beforeEnter = data.beforeEnter;
  var enter = data.enter;
  var afterEnter = data.afterEnter;
  var enterCancelled = data.enterCancelled;
  var beforeAppear = data.beforeAppear;
  var appear = data.appear;
  var afterAppear = data.afterAppear;
  var appearCancelled = data.appearCancelled;

  // activeInstance will always be the <transition> component managing this
  // transition. One edge case to check is when the <transition> is placed
  // as the root node of a child component. In that case we need to check
  // <transition>'s parent for appear check.
  var transitionNode = activeInstance.$vnode;
  var context = transitionNode && transitionNode.parent
    ? transitionNode.parent.context
    : activeInstance;

  var isAppear = !context._isMounted || !vnode.isRootInsert;

  if (isAppear && !appear && appear !== '') {
    return
  }

  var startClass = isAppear ? appearClass : enterClass;
  var activeClass = isAppear ? appearActiveClass : enterActiveClass;
  var beforeEnterHook = isAppear ? (beforeAppear || beforeEnter) : beforeEnter;
  var enterHook = isAppear ? (typeof appear === 'function' ? appear : enter) : enter;
  var afterEnterHook = isAppear ? (afterAppear || afterEnter) : afterEnter;
  var enterCancelledHook = isAppear ? (appearCancelled || enterCancelled) : enterCancelled;

  var expectsCSS = css !== false && !isIE9;
  var userWantsControl =
    enterHook &&
    // enterHook may be a bound method which exposes
    // the length of original fn as _length
    (enterHook._length || enterHook.length) > 1;

  var cb = el._enterCb = once(function () {
    if (expectsCSS) {
      removeTransitionClass(el, activeClass);
    }
    if (cb.cancelled) {
      if (expectsCSS) {
        removeTransitionClass(el, startClass);
      }
      enterCancelledHook && enterCancelledHook(el);
    } else {
      afterEnterHook && afterEnterHook(el);
    }
    el._enterCb = null;
  });

  if (!vnode.data.show) {
    // remove pending leave element on enter by injecting an insert hook
    mergeVNodeHook(vnode.data.hook || (vnode.data.hook = {}), 'insert', function () {
      var parent = el.parentNode;
      var pendingNode = parent && parent._pending && parent._pending[vnode.key];
      if (pendingNode && pendingNode.tag === vnode.tag && pendingNode.elm._leaveCb) {
        pendingNode.elm._leaveCb();
      }
      enterHook && enterHook(el, cb);
    }, 'transition-insert');
  }

  // start enter transition
  beforeEnterHook && beforeEnterHook(el);
  if (expectsCSS) {
    addTransitionClass(el, startClass);
    addTransitionClass(el, activeClass);
    nextFrame(function () {
      removeTransitionClass(el, startClass);
      if (!cb.cancelled && !userWantsControl) {
        whenTransitionEnds(el, type, cb);
      }
    });
  }

  if (vnode.data.show) {
    enterHook && enterHook(el, cb);
  }

  if (!expectsCSS && !userWantsControl) {
    cb();
  }
}

function leave (vnode, rm) {
  var el = vnode.elm;

  // call enter callback now
  if (el._enterCb) {
    el._enterCb.cancelled = true;
    el._enterCb();
  }

  var data = resolveTransition(vnode.data.transition);
  if (!data) {
    return rm()
  }

  /* istanbul ignore if */
  if (el._leaveCb || el.nodeType !== 1) {
    return
  }

  var css = data.css;
  var type = data.type;
  var leaveClass = data.leaveClass;
  var leaveActiveClass = data.leaveActiveClass;
  var beforeLeave = data.beforeLeave;
  var leave = data.leave;
  var afterLeave = data.afterLeave;
  var leaveCancelled = data.leaveCancelled;
  var delayLeave = data.delayLeave;

  var expectsCSS = css !== false && !isIE9;
  var userWantsControl =
    leave &&
    // leave hook may be a bound method which exposes
    // the length of original fn as _length
    (leave._length || leave.length) > 1;

  var cb = el._leaveCb = once(function () {
    if (el.parentNode && el.parentNode._pending) {
      el.parentNode._pending[vnode.key] = null;
    }
    if (expectsCSS) {
      removeTransitionClass(el, leaveActiveClass);
    }
    if (cb.cancelled) {
      if (expectsCSS) {
        removeTransitionClass(el, leaveClass);
      }
      leaveCancelled && leaveCancelled(el);
    } else {
      rm();
      afterLeave && afterLeave(el);
    }
    el._leaveCb = null;
  });

  if (delayLeave) {
    delayLeave(performLeave);
  } else {
    performLeave();
  }

  function performLeave () {
    // the delayed leave may have already been cancelled
    if (cb.cancelled) {
      return
    }
    // record leaving element
    if (!vnode.data.show) {
      (el.parentNode._pending || (el.parentNode._pending = {}))[vnode.key] = vnode;
    }
    beforeLeave && beforeLeave(el);
    if (expectsCSS) {
      addTransitionClass(el, leaveClass);
      addTransitionClass(el, leaveActiveClass);
      nextFrame(function () {
        removeTransitionClass(el, leaveClass);
        if (!cb.cancelled && !userWantsControl) {
          whenTransitionEnds(el, type, cb);
        }
      });
    }
    leave && leave(el, cb);
    if (!expectsCSS && !userWantsControl) {
      cb();
    }
  }
}

function resolveTransition (def$$1) {
  if (!def$$1) {
    return
  }
  /* istanbul ignore else */
  if (typeof def$$1 === 'object') {
    var res = {};
    if (def$$1.css !== false) {
      extend(res, autoCssTransition(def$$1.name || 'v'));
    }
    extend(res, def$$1);
    return res
  } else if (typeof def$$1 === 'string') {
    return autoCssTransition(def$$1)
  }
}

var autoCssTransition = cached(function (name) {
  return {
    enterClass: (name + "-enter"),
    leaveClass: (name + "-leave"),
    appearClass: (name + "-enter"),
    enterActiveClass: (name + "-enter-active"),
    leaveActiveClass: (name + "-leave-active"),
    appearActiveClass: (name + "-enter-active")
  }
});

function once (fn) {
  var called = false;
  return function () {
    if (!called) {
      called = true;
      fn();
    }
  }
}

var transition = inBrowser ? {
  create: function create (_, vnode) {
    if (!vnode.data.show) {
      enter(vnode);
    }
  },
  remove: function remove (vnode, rm) {
    /* istanbul ignore else */
    if (!vnode.data.show) {
      leave(vnode, rm);
    } else {
      rm();
    }
  }
} : {};

var platformModules = [
  attrs,
  klass,
  events,
  domProps,
  style,
  transition
];

/*  */

// the directive module should be applied last, after all
// built-in modules have been applied.
var modules = platformModules.concat(baseModules);

var patch$1 = createPatchFunction({ nodeOps: nodeOps, modules: modules });

/**
 * Not type checking this file because flow doesn't like attaching
 * properties to Elements.
 */

var modelableTagRE = /^input|select|textarea|vue-component-[0-9]+(-[0-9a-zA-Z_-]*)?$/;

/* istanbul ignore if */
if (isIE9) {
  // http://www.matts411.com/post/internet-explorer-9-oninput/
  document.addEventListener('selectionchange', function () {
    var el = document.activeElement;
    if (el && el.vmodel) {
      trigger(el, 'input');
    }
  });
}

var model = {
  inserted: function inserted (el, binding, vnode) {
    if (process.env.NODE_ENV !== 'production') {
      if (!modelableTagRE.test(vnode.tag)) {
        warn(
          "v-model is not supported on element type: <" + (vnode.tag) + ">. " +
          'If you are working with contenteditable, it\'s recommended to ' +
          'wrap a library dedicated for that purpose inside a custom component.',
          vnode.context
        );
      }
    }
    if (vnode.tag === 'select') {
      var cb = function () {
        setSelected(el, binding, vnode.context);
      };
      cb();
      /* istanbul ignore if */
      if (isIE || isEdge) {
        setTimeout(cb, 0);
      }
    } else if (
      (vnode.tag === 'textarea' || el.type === 'text') &&
      !binding.modifiers.lazy
    ) {
      if (!isAndroid) {
        el.addEventListener('compositionstart', onCompositionStart);
        el.addEventListener('compositionend', onCompositionEnd);
      }
      /* istanbul ignore if */
      if (isIE9) {
        el.vmodel = true;
      }
    }
  },
  componentUpdated: function componentUpdated (el, binding, vnode) {
    if (vnode.tag === 'select') {
      setSelected(el, binding, vnode.context);
      // in case the options rendered by v-for have changed,
      // it's possible that the value is out-of-sync with the rendered options.
      // detect such cases and filter out values that no longer has a matching
      // option in the DOM.
      var needReset = el.multiple
        ? binding.value.some(function (v) { return hasNoMatchingOption(v, el.options); })
        : binding.value !== binding.oldValue && hasNoMatchingOption(binding.value, el.options);
      if (needReset) {
        trigger(el, 'change');
      }
    }
  }
};

function setSelected (el, binding, vm) {
  var value = binding.value;
  var isMultiple = el.multiple;
  if (isMultiple && !Array.isArray(value)) {
    process.env.NODE_ENV !== 'production' && warn(
      "<select multiple v-model=\"" + (binding.expression) + "\"> " +
      "expects an Array value for its binding, but got " + (Object.prototype.toString.call(value).slice(8, -1)),
      vm
    );
    return
  }
  var selected, option;
  for (var i = 0, l = el.options.length; i < l; i++) {
    option = el.options[i];
    if (isMultiple) {
      selected = looseIndexOf(value, getValue(option)) > -1;
      if (option.selected !== selected) {
        option.selected = selected;
      }
    } else {
      if (looseEqual(getValue(option), value)) {
        if (el.selectedIndex !== i) {
          el.selectedIndex = i;
        }
        return
      }
    }
  }
  if (!isMultiple) {
    el.selectedIndex = -1;
  }
}

function hasNoMatchingOption (value, options) {
  for (var i = 0, l = options.length; i < l; i++) {
    if (looseEqual(getValue(options[i]), value)) {
      return false
    }
  }
  return true
}

function getValue (option) {
  return '_value' in option
    ? option._value
    : option.value
}

function onCompositionStart (e) {
  e.target.composing = true;
}

function onCompositionEnd (e) {
  e.target.composing = false;
  trigger(e.target, 'input');
}

function trigger (el, type) {
  var e = document.createEvent('HTMLEvents');
  e.initEvent(type, true, true);
  el.dispatchEvent(e);
}

/*  */

// recursively search for possible transition defined inside the component root
function locateNode (vnode) {
  return vnode.child && (!vnode.data || !vnode.data.transition)
    ? locateNode(vnode.child._vnode)
    : vnode
}

var show = {
  bind: function bind (el, ref, vnode) {
    var value = ref.value;

    vnode = locateNode(vnode);
    var transition = vnode.data && vnode.data.transition;
    if (value && transition && !isIE9) {
      enter(vnode);
    }
    var originalDisplay = el.style.display === 'none' ? '' : el.style.display;
    el.style.display = value ? originalDisplay : 'none';
    el.__vOriginalDisplay = originalDisplay;
  },
  update: function update (el, ref, vnode) {
    var value = ref.value;
    var oldValue = ref.oldValue;

    /* istanbul ignore if */
    if (value === oldValue) { return }
    vnode = locateNode(vnode);
    var transition = vnode.data && vnode.data.transition;
    if (transition && !isIE9) {
      if (value) {
        enter(vnode);
        el.style.display = el.__vOriginalDisplay;
      } else {
        leave(vnode, function () {
          el.style.display = 'none';
        });
      }
    } else {
      el.style.display = value ? el.__vOriginalDisplay : 'none';
    }
  }
};

var platformDirectives = {
  model: model,
  show: show
};

/*  */

// Provides transition support for a single element/component.
// supports transition mode (out-in / in-out)

var transitionProps = {
  name: String,
  appear: Boolean,
  css: Boolean,
  mode: String,
  type: String,
  enterClass: String,
  leaveClass: String,
  enterActiveClass: String,
  leaveActiveClass: String,
  appearClass: String,
  appearActiveClass: String
};

// in case the child is also an abstract component, e.g. <keep-alive>
// we want to recursively retrieve the real component to be rendered
function getRealChild (vnode) {
  var compOptions = vnode && vnode.componentOptions;
  if (compOptions && compOptions.Ctor.options.abstract) {
    return getRealChild(getFirstComponentChild(compOptions.children))
  } else {
    return vnode
  }
}

function extractTransitionData (comp) {
  var data = {};
  var options = comp.$options;
  // props
  for (var key in options.propsData) {
    data[key] = comp[key];
  }
  // events.
  // extract listeners and pass them directly to the transition methods
  var listeners = options._parentListeners;
  for (var key$1 in listeners) {
    data[camelize(key$1)] = listeners[key$1].fn;
  }
  return data
}

function placeholder (h, rawChild) {
  return /\d-keep-alive$/.test(rawChild.tag)
    ? h('keep-alive')
    : null
}

function hasParentTransition (vnode) {
  while ((vnode = vnode.parent)) {
    if (vnode.data.transition) {
      return true
    }
  }
}

var Transition = {
  name: 'transition',
  props: transitionProps,
  abstract: true,
  render: function render (h) {
    var this$1 = this;

    var children = this.$slots.default;
    if (!children) {
      return
    }

    // filter out text nodes (possible whitespaces)
    children = children.filter(function (c) { return c.tag; });
    /* istanbul ignore if */
    if (!children.length) {
      return
    }

    // warn multiple elements
    if (process.env.NODE_ENV !== 'production' && children.length > 1) {
      warn(
        '<transition> can only be used on a single element. Use ' +
        '<transition-group> for lists.',
        this.$parent
      );
    }

    var mode = this.mode;

    // warn invalid mode
    if (process.env.NODE_ENV !== 'production' &&
        mode && mode !== 'in-out' && mode !== 'out-in') {
      warn(
        'invalid <transition> mode: ' + mode,
        this.$parent
      );
    }

    var rawChild = children[0];

    // if this is a component root node and the component's
    // parent container node also has transition, skip.
    if (hasParentTransition(this.$vnode)) {
      return rawChild
    }

    // apply transition data to child
    // use getRealChild() to ignore abstract components e.g. keep-alive
    var child = getRealChild(rawChild);
    /* istanbul ignore if */
    if (!child) {
      return rawChild
    }

    if (this._leaving) {
      return placeholder(h, rawChild)
    }

    var key = child.key = child.key == null || child.isStatic
      ? ("__v" + (child.tag + this._uid) + "__")
      : child.key;
    var data = (child.data || (child.data = {})).transition = extractTransitionData(this);
    var oldRawChild = this._vnode;
    var oldChild = getRealChild(oldRawChild);

    // mark v-show
    // so that the transition module can hand over the control to the directive
    if (child.data.directives && child.data.directives.some(function (d) { return d.name === 'show'; })) {
      child.data.show = true;
    }

    if (oldChild && oldChild.data && oldChild.key !== key) {
      // replace old child transition data with fresh one
      // important for dynamic transitions!
      var oldData = oldChild.data.transition = extend({}, data);

      // handle transition mode
      if (mode === 'out-in') {
        // return placeholder node and queue update when leave finishes
        this._leaving = true;
        mergeVNodeHook(oldData, 'afterLeave', function () {
          this$1._leaving = false;
          this$1.$forceUpdate();
        }, key);
        return placeholder(h, rawChild)
      } else if (mode === 'in-out') {
        var delayedLeave;
        var performLeave = function () { delayedLeave(); };
        mergeVNodeHook(data, 'afterEnter', performLeave, key);
        mergeVNodeHook(data, 'enterCancelled', performLeave, key);
        mergeVNodeHook(oldData, 'delayLeave', function (leave) {
          delayedLeave = leave;
        }, key);
      }
    }

    return rawChild
  }
};

/*  */

// Provides transition support for list items.
// supports move transitions using the FLIP technique.

// Because the vdom's children update algorithm is "unstable" - i.e.
// it doesn't guarantee the relative positioning of removed elements,
// we force transition-group to update its children into two passes:
// in the first pass, we remove all nodes that need to be removed,
// triggering their leaving transition; in the second pass, we insert/move
// into the final disired state. This way in the second pass removed
// nodes will remain where they should be.

var props = extend({
  tag: String,
  moveClass: String
}, transitionProps);

delete props.mode;

var TransitionGroup = {
  props: props,

  render: function render (h) {
    var tag = this.tag || this.$vnode.data.tag || 'span';
    var map = Object.create(null);
    var prevChildren = this.prevChildren = this.children;
    var rawChildren = this.$slots.default || [];
    var children = this.children = [];
    var transitionData = extractTransitionData(this);

    for (var i = 0; i < rawChildren.length; i++) {
      var c = rawChildren[i];
      if (c.tag) {
        if (c.key != null && String(c.key).indexOf('__vlist') !== 0) {
          children.push(c);
          map[c.key] = c
          ;(c.data || (c.data = {})).transition = transitionData;
        } else if (process.env.NODE_ENV !== 'production') {
          var opts = c.componentOptions;
          var name = opts
            ? (opts.Ctor.options.name || opts.tag)
            : c.tag;
          warn(("<transition-group> children must be keyed: <" + name + ">"));
        }
      }
    }

    if (prevChildren) {
      var kept = [];
      var removed = [];
      for (var i$1 = 0; i$1 < prevChildren.length; i$1++) {
        var c$1 = prevChildren[i$1];
        c$1.data.transition = transitionData;
        c$1.data.pos = c$1.elm.getBoundingClientRect();
        if (map[c$1.key]) {
          kept.push(c$1);
        } else {
          removed.push(c$1);
        }
      }
      this.kept = h(tag, null, kept);
      this.removed = removed;
    }

    return h(tag, null, children)
  },

  beforeUpdate: function beforeUpdate () {
    // force removing pass
    this.__patch__(
      this._vnode,
      this.kept,
      false, // hydrating
      true // removeOnly (!important, avoids unnecessary moves)
    );
    this._vnode = this.kept;
  },

  updated: function updated () {
    var children = this.prevChildren;
    var moveClass = this.moveClass || (this.name + '-move');
    if (!children.length || !this.hasMove(children[0].elm, moveClass)) {
      return
    }

    // we divide the work into three loops to avoid mixing DOM reads and writes
    // in each iteration - which helps prevent layout thrashing.
    children.forEach(callPendingCbs);
    children.forEach(recordPosition);
    children.forEach(applyTranslation);

    // force reflow to put everything in position
    var f = document.body.offsetHeight; // eslint-disable-line

    children.forEach(function (c) {
      if (c.data.moved) {
        var el = c.elm;
        var s = el.style;
        addTransitionClass(el, moveClass);
        s.transform = s.WebkitTransform = s.transitionDuration = '';
        el.addEventListener(transitionEndEvent, el._moveCb = function cb (e) {
          if (!e || /transform$/.test(e.propertyName)) {
            el.removeEventListener(transitionEndEvent, cb);
            el._moveCb = null;
            removeTransitionClass(el, moveClass);
          }
        });
      }
    });
  },

  methods: {
    hasMove: function hasMove (el, moveClass) {
      /* istanbul ignore if */
      if (!hasTransition) {
        return false
      }
      if (this._hasMove != null) {
        return this._hasMove
      }
      addTransitionClass(el, moveClass);
      var info = getTransitionInfo(el);
      removeTransitionClass(el, moveClass);
      return (this._hasMove = info.hasTransform)
    }
  }
};

function callPendingCbs (c) {
  /* istanbul ignore if */
  if (c.elm._moveCb) {
    c.elm._moveCb();
  }
  /* istanbul ignore if */
  if (c.elm._enterCb) {
    c.elm._enterCb();
  }
}

function recordPosition (c) {
  c.data.newPos = c.elm.getBoundingClientRect();
}

function applyTranslation (c) {
  var oldPos = c.data.pos;
  var newPos = c.data.newPos;
  var dx = oldPos.left - newPos.left;
  var dy = oldPos.top - newPos.top;
  if (dx || dy) {
    c.data.moved = true;
    var s = c.elm.style;
    s.transform = s.WebkitTransform = "translate(" + dx + "px," + dy + "px)";
    s.transitionDuration = '0s';
  }
}

var platformComponents = {
  Transition: Transition,
  TransitionGroup: TransitionGroup
};

/*  */

// install platform specific utils
Vue$2.config.isUnknownElement = isUnknownElement;
Vue$2.config.isReservedTag = isReservedTag;
Vue$2.config.getTagNamespace = getTagNamespace;
Vue$2.config.mustUseProp = mustUseProp;

// install platform runtime directives & components
extend(Vue$2.options.directives, platformDirectives);
extend(Vue$2.options.components, platformComponents);

// install platform patch function
Vue$2.prototype.__patch__ = config._isServer ? noop : patch$1;

// wrap mount
Vue$2.prototype.$mount = function (
  el,
  hydrating
) {
  el = el && !config._isServer ? query(el) : undefined;
  return this._mount(el, hydrating)
};

// devtools global hook
/* istanbul ignore next */
setTimeout(function () {
  if (config.devtools) {
    if (devtools) {
      devtools.emit('init', Vue$2);
    } else if (
      process.env.NODE_ENV !== 'production' &&
      inBrowser && /Chrome\/\d+/.test(window.navigator.userAgent)
    ) {
      console.log(
        'Download the Vue Devtools for a better development experience:\n' +
        'https://github.com/vuejs/vue-devtools'
      );
    }
  }
}, 0);

module.exports = Vue$2;

}).call(this,require('_process'))
},{"_process":4}],9:[function(require,module,exports){
/*!
 * Vue.js v2.0.7
 * (c) 2014-2016 Evan You
 * Released under the MIT License.
 */
(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  (global.Vue = factory());
}(this, (function () { 'use strict';

/*  */

/**
 * Convert a value to a string that is actually rendered.
 */
function _toString (val) {
  return val == null
    ? ''
    : typeof val === 'object'
      ? JSON.stringify(val, null, 2)
      : String(val)
}

/**
 * Convert a input value to a number for persistence.
 * If the conversion fails, return original string.
 */
function toNumber (val) {
  var n = parseFloat(val, 10);
  return (n || n === 0) ? n : val
}

/**
 * Make a map and return a function for checking if a key
 * is in that map.
 */
function makeMap (
  str,
  expectsLowerCase
) {
  var map = Object.create(null);
  var list = str.split(',');
  for (var i = 0; i < list.length; i++) {
    map[list[i]] = true;
  }
  return expectsLowerCase
    ? function (val) { return map[val.toLowerCase()]; }
    : function (val) { return map[val]; }
}

/**
 * Check if a tag is a built-in tag.
 */
var isBuiltInTag = makeMap('slot,component', true);

/**
 * Remove an item from an array
 */
function remove$1 (arr, item) {
  if (arr.length) {
    var index = arr.indexOf(item);
    if (index > -1) {
      return arr.splice(index, 1)
    }
  }
}

/**
 * Check whether the object has the property.
 */
var hasOwnProperty = Object.prototype.hasOwnProperty;
function hasOwn (obj, key) {
  return hasOwnProperty.call(obj, key)
}

/**
 * Check if value is primitive
 */
function isPrimitive (value) {
  return typeof value === 'string' || typeof value === 'number'
}

/**
 * Create a cached version of a pure function.
 */
function cached (fn) {
  var cache = Object.create(null);
  return function cachedFn (str) {
    var hit = cache[str];
    return hit || (cache[str] = fn(str))
  }
}

/**
 * Camelize a hyphen-delmited string.
 */
var camelizeRE = /-(\w)/g;
var camelize = cached(function (str) {
  return str.replace(camelizeRE, function (_, c) { return c ? c.toUpperCase() : ''; })
});

/**
 * Capitalize a string.
 */
var capitalize = cached(function (str) {
  return str.charAt(0).toUpperCase() + str.slice(1)
});

/**
 * Hyphenate a camelCase string.
 */
var hyphenateRE = /([^-])([A-Z])/g;
var hyphenate = cached(function (str) {
  return str
    .replace(hyphenateRE, '$1-$2')
    .replace(hyphenateRE, '$1-$2')
    .toLowerCase()
});

/**
 * Simple bind, faster than native
 */
function bind$1 (fn, ctx) {
  function boundFn (a) {
    var l = arguments.length;
    return l
      ? l > 1
        ? fn.apply(ctx, arguments)
        : fn.call(ctx, a)
      : fn.call(ctx)
  }
  // record original fn length
  boundFn._length = fn.length;
  return boundFn
}

/**
 * Convert an Array-like object to a real Array.
 */
function toArray (list, start) {
  start = start || 0;
  var i = list.length - start;
  var ret = new Array(i);
  while (i--) {
    ret[i] = list[i + start];
  }
  return ret
}

/**
 * Mix properties into target object.
 */
function extend (to, _from) {
  for (var key in _from) {
    to[key] = _from[key];
  }
  return to
}

/**
 * Quick object check - this is primarily used to tell
 * Objects from primitive values when we know the value
 * is a JSON-compliant type.
 */
function isObject (obj) {
  return obj !== null && typeof obj === 'object'
}

/**
 * Strict object type check. Only returns true
 * for plain JavaScript objects.
 */
var toString = Object.prototype.toString;
var OBJECT_STRING = '[object Object]';
function isPlainObject (obj) {
  return toString.call(obj) === OBJECT_STRING
}

/**
 * Merge an Array of Objects into a single Object.
 */
function toObject (arr) {
  var res = {};
  for (var i = 0; i < arr.length; i++) {
    if (arr[i]) {
      extend(res, arr[i]);
    }
  }
  return res
}

/**
 * Perform no operation.
 */
function noop () {}

/**
 * Always return false.
 */
var no = function () { return false; };

/**
 * Generate a static keys string from compiler modules.
 */
function genStaticKeys (modules) {
  return modules.reduce(function (keys, m) {
    return keys.concat(m.staticKeys || [])
  }, []).join(',')
}

/**
 * Check if two values are loosely equal - that is,
 * if they are plain objects, do they have the same shape?
 */
function looseEqual (a, b) {
  /* eslint-disable eqeqeq */
  return a == b || (
    isObject(a) && isObject(b)
      ? JSON.stringify(a) === JSON.stringify(b)
      : false
  )
  /* eslint-enable eqeqeq */
}

function looseIndexOf (arr, val) {
  for (var i = 0; i < arr.length; i++) {
    if (looseEqual(arr[i], val)) { return i }
  }
  return -1
}

/*  */

var config = {
  /**
   * Option merge strategies (used in core/util/options)
   */
  optionMergeStrategies: Object.create(null),

  /**
   * Whether to suppress warnings.
   */
  silent: false,

  /**
   * Whether to enable devtools
   */
  devtools: "development" !== 'production',

  /**
   * Error handler for watcher errors
   */
  errorHandler: null,

  /**
   * Ignore certain custom elements
   */
  ignoredElements: null,

  /**
   * Custom user key aliases for v-on
   */
  keyCodes: Object.create(null),

  /**
   * Check if a tag is reserved so that it cannot be registered as a
   * component. This is platform-dependent and may be overwritten.
   */
  isReservedTag: no,

  /**
   * Check if a tag is an unknown element.
   * Platform-dependent.
   */
  isUnknownElement: no,

  /**
   * Get the namespace of an element
   */
  getTagNamespace: noop,

  /**
   * Check if an attribute must be bound using property, e.g. value
   * Platform-dependent.
   */
  mustUseProp: no,

  /**
   * List of asset types that a component can own.
   */
  _assetTypes: [
    'component',
    'directive',
    'filter'
  ],

  /**
   * List of lifecycle hooks.
   */
  _lifecycleHooks: [
    'beforeCreate',
    'created',
    'beforeMount',
    'mounted',
    'beforeUpdate',
    'updated',
    'beforeDestroy',
    'destroyed',
    'activated',
    'deactivated'
  ],

  /**
   * Max circular updates allowed in a scheduler flush cycle.
   */
  _maxUpdateCount: 100,

  /**
   * Server rendering?
   */
  _isServer: "client" === 'server'
};

/*  */

/**
 * Check if a string starts with $ or _
 */
function isReserved (str) {
  var c = (str + '').charCodeAt(0);
  return c === 0x24 || c === 0x5F
}

/**
 * Define a property.
 */
function def (obj, key, val, enumerable) {
  Object.defineProperty(obj, key, {
    value: val,
    enumerable: !!enumerable,
    writable: true,
    configurable: true
  });
}

/**
 * Parse simple path.
 */
var bailRE = /[^\w.$]/;
function parsePath (path) {
  if (bailRE.test(path)) {
    return
  } else {
    var segments = path.split('.');
    return function (obj) {
      for (var i = 0; i < segments.length; i++) {
        if (!obj) { return }
        obj = obj[segments[i]];
      }
      return obj
    }
  }
}

/*  */
/* globals MutationObserver */

// can we use __proto__?
var hasProto = '__proto__' in {};

// Browser environment sniffing
var inBrowser =
  typeof window !== 'undefined' &&
  Object.prototype.toString.call(window) !== '[object Object]';

var UA = inBrowser && window.navigator.userAgent.toLowerCase();
var isIE = UA && /msie|trident/.test(UA);
var isIE9 = UA && UA.indexOf('msie 9.0') > 0;
var isEdge = UA && UA.indexOf('edge/') > 0;
var isAndroid = UA && UA.indexOf('android') > 0;
var isIOS = UA && /iphone|ipad|ipod|ios/.test(UA);

// detect devtools
var devtools = inBrowser && window.__VUE_DEVTOOLS_GLOBAL_HOOK__;

/* istanbul ignore next */
function isNative (Ctor) {
  return /native code/.test(Ctor.toString())
}

/**
 * Defer a task to execute it asynchronously.
 */
var nextTick = (function () {
  var callbacks = [];
  var pending = false;
  var timerFunc;

  function nextTickHandler () {
    pending = false;
    var copies = callbacks.slice(0);
    callbacks.length = 0;
    for (var i = 0; i < copies.length; i++) {
      copies[i]();
    }
  }

  // the nextTick behavior leverages the microtask queue, which can be accessed
  // via either native Promise.then or MutationObserver.
  // MutationObserver has wider support, however it is seriously bugged in
  // UIWebView in iOS >= 9.3.3 when triggered in touch event handlers. It
  // completely stops working after triggering a few times... so, if native
  // Promise is available, we will use it:
  /* istanbul ignore if */
  if (typeof Promise !== 'undefined' && isNative(Promise)) {
    var p = Promise.resolve();
    timerFunc = function () {
      p.then(nextTickHandler);
      // in problematic UIWebViews, Promise.then doesn't completely break, but
      // it can get stuck in a weird state where callbacks are pushed into the
      // microtask queue but the queue isn't being flushed, until the browser
      // needs to do some other work, e.g. handle a timer. Therefore we can
      // "force" the microtask queue to be flushed by adding an empty timer.
      if (isIOS) { setTimeout(noop); }
    };
  } else if (typeof MutationObserver !== 'undefined' && (
    isNative(MutationObserver) ||
    // PhantomJS and iOS 7.x
    MutationObserver.toString() === '[object MutationObserverConstructor]'
  )) {
    // use MutationObserver where native Promise is not available,
    // e.g. PhantomJS IE11, iOS7, Android 4.4
    var counter = 1;
    var observer = new MutationObserver(nextTickHandler);
    var textNode = document.createTextNode(String(counter));
    observer.observe(textNode, {
      characterData: true
    });
    timerFunc = function () {
      counter = (counter + 1) % 2;
      textNode.data = String(counter);
    };
  } else {
    // fallback to setTimeout
    /* istanbul ignore next */
    timerFunc = function () {
      setTimeout(nextTickHandler, 0);
    };
  }

  return function queueNextTick (cb, ctx) {
    var func = ctx
      ? function () { cb.call(ctx); }
      : cb;
    callbacks.push(func);
    if (!pending) {
      pending = true;
      timerFunc();
    }
  }
})();

var _Set;
/* istanbul ignore if */
if (typeof Set !== 'undefined' && isNative(Set)) {
  // use native Set when available.
  _Set = Set;
} else {
  // a non-standard Set polyfill that only works with primitive keys.
  _Set = (function () {
    function Set () {
      this.set = Object.create(null);
    }
    Set.prototype.has = function has (key) {
      return this.set[key] !== undefined
    };
    Set.prototype.add = function add (key) {
      this.set[key] = 1;
    };
    Set.prototype.clear = function clear () {
      this.set = Object.create(null);
    };

    return Set;
  }());
}

/* not type checking this file because flow doesn't play well with Proxy */

var hasProxy;
var proxyHandlers;
var initProxy;

{
  var allowedGlobals = makeMap(
    'Infinity,undefined,NaN,isFinite,isNaN,' +
    'parseFloat,parseInt,decodeURI,decodeURIComponent,encodeURI,encodeURIComponent,' +
    'Math,Number,Date,Array,Object,Boolean,String,RegExp,Map,Set,JSON,Intl,' +
    'require' // for Webpack/Browserify
  );

  hasProxy =
    typeof Proxy !== 'undefined' &&
    Proxy.toString().match(/native code/);

  proxyHandlers = {
    has: function has (target, key) {
      var has = key in target;
      var isAllowed = allowedGlobals(key) || key.charAt(0) === '_';
      if (!has && !isAllowed) {
        warn(
          "Property or method \"" + key + "\" is not defined on the instance but " +
          "referenced during render. Make sure to declare reactive data " +
          "properties in the data option.",
          target
        );
      }
      return has || !isAllowed
    }
  };

  initProxy = function initProxy (vm) {
    if (hasProxy) {
      vm._renderProxy = new Proxy(vm, proxyHandlers);
    } else {
      vm._renderProxy = vm;
    }
  };
}

/*  */


var uid$2 = 0;

/**
 * A dep is an observable that can have multiple
 * directives subscribing to it.
 */
var Dep = function Dep () {
  this.id = uid$2++;
  this.subs = [];
};

Dep.prototype.addSub = function addSub (sub) {
  this.subs.push(sub);
};

Dep.prototype.removeSub = function removeSub (sub) {
  remove$1(this.subs, sub);
};

Dep.prototype.depend = function depend () {
  if (Dep.target) {
    Dep.target.addDep(this);
  }
};

Dep.prototype.notify = function notify () {
  // stablize the subscriber list first
  var subs = this.subs.slice();
  for (var i = 0, l = subs.length; i < l; i++) {
    subs[i].update();
  }
};

// the current target watcher being evaluated.
// this is globally unique because there could be only one
// watcher being evaluated at any time.
Dep.target = null;
var targetStack = [];

function pushTarget (_target) {
  if (Dep.target) { targetStack.push(Dep.target); }
  Dep.target = _target;
}

function popTarget () {
  Dep.target = targetStack.pop();
}

/*  */


var queue = [];
var has$1 = {};
var circular = {};
var waiting = false;
var flushing = false;
var index = 0;

/**
 * Reset the scheduler's state.
 */
function resetSchedulerState () {
  queue.length = 0;
  has$1 = {};
  {
    circular = {};
  }
  waiting = flushing = false;
}

/**
 * Flush both queues and run the watchers.
 */
function flushSchedulerQueue () {
  flushing = true;

  // Sort queue before flush.
  // This ensures that:
  // 1. Components are updated from parent to child. (because parent is always
  //    created before the child)
  // 2. A component's user watchers are run before its render watcher (because
  //    user watchers are created before the render watcher)
  // 3. If a component is destroyed during a parent component's watcher run,
  //    its watchers can be skipped.
  queue.sort(function (a, b) { return a.id - b.id; });

  // do not cache length because more watchers might be pushed
  // as we run existing watchers
  for (index = 0; index < queue.length; index++) {
    var watcher = queue[index];
    var id = watcher.id;
    has$1[id] = null;
    watcher.run();
    // in dev build, check and stop circular updates.
    if ("development" !== 'production' && has$1[id] != null) {
      circular[id] = (circular[id] || 0) + 1;
      if (circular[id] > config._maxUpdateCount) {
        warn(
          'You may have an infinite update loop ' + (
            watcher.user
              ? ("in watcher with expression \"" + (watcher.expression) + "\"")
              : "in a component render function."
          ),
          watcher.vm
        );
        break
      }
    }
  }

  // devtool hook
  /* istanbul ignore if */
  if (devtools && config.devtools) {
    devtools.emit('flush');
  }

  resetSchedulerState();
}

/**
 * Push a watcher into the watcher queue.
 * Jobs with duplicate IDs will be skipped unless it's
 * pushed when the queue is being flushed.
 */
function queueWatcher (watcher) {
  var id = watcher.id;
  if (has$1[id] == null) {
    has$1[id] = true;
    if (!flushing) {
      queue.push(watcher);
    } else {
      // if already flushing, splice the watcher based on its id
      // if already past its id, it will be run next immediately.
      var i = queue.length - 1;
      while (i >= 0 && queue[i].id > watcher.id) {
        i--;
      }
      queue.splice(Math.max(i, index) + 1, 0, watcher);
    }
    // queue the flush
    if (!waiting) {
      waiting = true;
      nextTick(flushSchedulerQueue);
    }
  }
}

/*  */

var uid$1 = 0;

/**
 * A watcher parses an expression, collects dependencies,
 * and fires callback when the expression value changes.
 * This is used for both the $watch() api and directives.
 */
var Watcher = function Watcher (
  vm,
  expOrFn,
  cb,
  options
) {
  if ( options === void 0 ) options = {};

  this.vm = vm;
  vm._watchers.push(this);
  // options
  this.deep = !!options.deep;
  this.user = !!options.user;
  this.lazy = !!options.lazy;
  this.sync = !!options.sync;
  this.expression = expOrFn.toString();
  this.cb = cb;
  this.id = ++uid$1; // uid for batching
  this.active = true;
  this.dirty = this.lazy; // for lazy watchers
  this.deps = [];
  this.newDeps = [];
  this.depIds = new _Set();
  this.newDepIds = new _Set();
  // parse expression for getter
  if (typeof expOrFn === 'function') {
    this.getter = expOrFn;
  } else {
    this.getter = parsePath(expOrFn);
    if (!this.getter) {
      this.getter = function () {};
      "development" !== 'production' && warn(
        "Failed watching path: \"" + expOrFn + "\" " +
        'Watcher only accepts simple dot-delimited paths. ' +
        'For full control, use a function instead.',
        vm
      );
    }
  }
  this.value = this.lazy
    ? undefined
    : this.get();
};

/**
 * Evaluate the getter, and re-collect dependencies.
 */
Watcher.prototype.get = function get () {
  pushTarget(this);
  var value = this.getter.call(this.vm, this.vm);
  // "touch" every property so they are all tracked as
  // dependencies for deep watching
  if (this.deep) {
    traverse(value);
  }
  popTarget();
  this.cleanupDeps();
  return value
};

/**
 * Add a dependency to this directive.
 */
Watcher.prototype.addDep = function addDep (dep) {
  var id = dep.id;
  if (!this.newDepIds.has(id)) {
    this.newDepIds.add(id);
    this.newDeps.push(dep);
    if (!this.depIds.has(id)) {
      dep.addSub(this);
    }
  }
};

/**
 * Clean up for dependency collection.
 */
Watcher.prototype.cleanupDeps = function cleanupDeps () {
    var this$1 = this;

  var i = this.deps.length;
  while (i--) {
    var dep = this$1.deps[i];
    if (!this$1.newDepIds.has(dep.id)) {
      dep.removeSub(this$1);
    }
  }
  var tmp = this.depIds;
  this.depIds = this.newDepIds;
  this.newDepIds = tmp;
  this.newDepIds.clear();
  tmp = this.deps;
  this.deps = this.newDeps;
  this.newDeps = tmp;
  this.newDeps.length = 0;
};

/**
 * Subscriber interface.
 * Will be called when a dependency changes.
 */
Watcher.prototype.update = function update () {
  /* istanbul ignore else */
  if (this.lazy) {
    this.dirty = true;
  } else if (this.sync) {
    this.run();
  } else {
    queueWatcher(this);
  }
};

/**
 * Scheduler job interface.
 * Will be called by the scheduler.
 */
Watcher.prototype.run = function run () {
  if (this.active) {
    var value = this.get();
      if (
        value !== this.value ||
      // Deep watchers and watchers on Object/Arrays should fire even
      // when the value is the same, because the value may
      // have mutated.
      isObject(value) ||
      this.deep
    ) {
      // set new value
      var oldValue = this.value;
      this.value = value;
      if (this.user) {
        try {
          this.cb.call(this.vm, value, oldValue);
        } catch (e) {
          "development" !== 'production' && warn(
            ("Error in watcher \"" + (this.expression) + "\""),
            this.vm
          );
          /* istanbul ignore else */
          if (config.errorHandler) {
            config.errorHandler.call(null, e, this.vm);
          } else {
            throw e
          }
        }
      } else {
        this.cb.call(this.vm, value, oldValue);
      }
    }
  }
};

/**
 * Evaluate the value of the watcher.
 * This only gets called for lazy watchers.
 */
Watcher.prototype.evaluate = function evaluate () {
  this.value = this.get();
  this.dirty = false;
};

/**
 * Depend on all deps collected by this watcher.
 */
Watcher.prototype.depend = function depend () {
    var this$1 = this;

  var i = this.deps.length;
  while (i--) {
    this$1.deps[i].depend();
  }
};

/**
 * Remove self from all dependencies' subscriber list.
 */
Watcher.prototype.teardown = function teardown () {
    var this$1 = this;

  if (this.active) {
    // remove self from vm's watcher list
    // this is a somewhat expensive operation so we skip it
    // if the vm is being destroyed or is performing a v-for
    // re-render (the watcher list is then filtered by v-for).
    if (!this.vm._isBeingDestroyed && !this.vm._vForRemoving) {
      remove$1(this.vm._watchers, this);
    }
    var i = this.deps.length;
    while (i--) {
      this$1.deps[i].removeSub(this$1);
    }
    this.active = false;
  }
};

/**
 * Recursively traverse an object to evoke all converted
 * getters, so that every nested property inside the object
 * is collected as a "deep" dependency.
 */
var seenObjects = new _Set();
function traverse (val) {
  seenObjects.clear();
  _traverse(val, seenObjects);
}

function _traverse (val, seen) {
  var i, keys;
  var isA = Array.isArray(val);
  if ((!isA && !isObject(val)) || !Object.isExtensible(val)) {
    return
  }
  if (val.__ob__) {
    var depId = val.__ob__.dep.id;
    if (seen.has(depId)) {
      return
    }
    seen.add(depId);
  }
  if (isA) {
    i = val.length;
    while (i--) { _traverse(val[i], seen); }
  } else {
    keys = Object.keys(val);
    i = keys.length;
    while (i--) { _traverse(val[keys[i]], seen); }
  }
}

/*
 * not type checking this file because flow doesn't play well with
 * dynamically accessing methods on Array prototype
 */

var arrayProto = Array.prototype;
var arrayMethods = Object.create(arrayProto);[
  'push',
  'pop',
  'shift',
  'unshift',
  'splice',
  'sort',
  'reverse'
]
.forEach(function (method) {
  // cache original method
  var original = arrayProto[method];
  def(arrayMethods, method, function mutator () {
    var arguments$1 = arguments;

    // avoid leaking arguments:
    // http://jsperf.com/closure-with-arguments
    var i = arguments.length;
    var args = new Array(i);
    while (i--) {
      args[i] = arguments$1[i];
    }
    var result = original.apply(this, args);
    var ob = this.__ob__;
    var inserted;
    switch (method) {
      case 'push':
        inserted = args;
        break
      case 'unshift':
        inserted = args;
        break
      case 'splice':
        inserted = args.slice(2);
        break
    }
    if (inserted) { ob.observeArray(inserted); }
    // notify change
    ob.dep.notify();
    return result
  });
});

/*  */

var arrayKeys = Object.getOwnPropertyNames(arrayMethods);

/**
 * By default, when a reactive property is set, the new value is
 * also converted to become reactive. However when passing down props,
 * we don't want to force conversion because the value may be a nested value
 * under a frozen data structure. Converting it would defeat the optimization.
 */
var observerState = {
  shouldConvert: true,
  isSettingProps: false
};

/**
 * Observer class that are attached to each observed
 * object. Once attached, the observer converts target
 * object's property keys into getter/setters that
 * collect dependencies and dispatches updates.
 */
var Observer = function Observer (value) {
  this.value = value;
  this.dep = new Dep();
  this.vmCount = 0;
  def(value, '__ob__', this);
  if (Array.isArray(value)) {
    var augment = hasProto
      ? protoAugment
      : copyAugment;
    augment(value, arrayMethods, arrayKeys);
    this.observeArray(value);
  } else {
    this.walk(value);
  }
};

/**
 * Walk through each property and convert them into
 * getter/setters. This method should only be called when
 * value type is Object.
 */
Observer.prototype.walk = function walk (obj) {
  var keys = Object.keys(obj);
  for (var i = 0; i < keys.length; i++) {
    defineReactive$$1(obj, keys[i], obj[keys[i]]);
  }
};

/**
 * Observe a list of Array items.
 */
Observer.prototype.observeArray = function observeArray (items) {
  for (var i = 0, l = items.length; i < l; i++) {
    observe(items[i]);
  }
};

// helpers

/**
 * Augment an target Object or Array by intercepting
 * the prototype chain using __proto__
 */
function protoAugment (target, src) {
  /* eslint-disable no-proto */
  target.__proto__ = src;
  /* eslint-enable no-proto */
}

/**
 * Augment an target Object or Array by defining
 * hidden properties.
 *
 * istanbul ignore next
 */
function copyAugment (target, src, keys) {
  for (var i = 0, l = keys.length; i < l; i++) {
    var key = keys[i];
    def(target, key, src[key]);
  }
}

/**
 * Attempt to create an observer instance for a value,
 * returns the new observer if successfully observed,
 * or the existing observer if the value already has one.
 */
function observe (value) {
  if (!isObject(value)) {
    return
  }
  var ob;
  if (hasOwn(value, '__ob__') && value.__ob__ instanceof Observer) {
    ob = value.__ob__;
  } else if (
    observerState.shouldConvert &&
    !config._isServer &&
    (Array.isArray(value) || isPlainObject(value)) &&
    Object.isExtensible(value) &&
    !value._isVue
  ) {
    ob = new Observer(value);
  }
  return ob
}

/**
 * Define a reactive property on an Object.
 */
function defineReactive$$1 (
  obj,
  key,
  val,
  customSetter
) {
  var dep = new Dep();

  var property = Object.getOwnPropertyDescriptor(obj, key);
  if (property && property.configurable === false) {
    return
  }

  // cater for pre-defined getter/setters
  var getter = property && property.get;
  var setter = property && property.set;

  var childOb = observe(val);
  Object.defineProperty(obj, key, {
    enumerable: true,
    configurable: true,
    get: function reactiveGetter () {
      var value = getter ? getter.call(obj) : val;
      if (Dep.target) {
        dep.depend();
        if (childOb) {
          childOb.dep.depend();
        }
        if (Array.isArray(value)) {
          dependArray(value);
        }
      }
      return value
    },
    set: function reactiveSetter (newVal) {
      var value = getter ? getter.call(obj) : val;
      if (newVal === value) {
        return
      }
      if ("development" !== 'production' && customSetter) {
        customSetter();
      }
      if (setter) {
        setter.call(obj, newVal);
      } else {
        val = newVal;
      }
      childOb = observe(newVal);
      dep.notify();
    }
  });
}

/**
 * Set a property on an object. Adds the new property and
 * triggers change notification if the property doesn't
 * already exist.
 */
function set (obj, key, val) {
  if (Array.isArray(obj)) {
    obj.length = Math.max(obj.length, key);
    obj.splice(key, 1, val);
    return val
  }
  if (hasOwn(obj, key)) {
    obj[key] = val;
    return
  }
  var ob = obj.__ob__;
  if (obj._isVue || (ob && ob.vmCount)) {
    "development" !== 'production' && warn(
      'Avoid adding reactive properties to a Vue instance or its root $data ' +
      'at runtime - declare it upfront in the data option.'
    );
    return
  }
  if (!ob) {
    obj[key] = val;
    return
  }
  defineReactive$$1(ob.value, key, val);
  ob.dep.notify();
  return val
}

/**
 * Delete a property and trigger change if necessary.
 */
function del (obj, key) {
  var ob = obj.__ob__;
  if (obj._isVue || (ob && ob.vmCount)) {
    "development" !== 'production' && warn(
      'Avoid deleting properties on a Vue instance or its root $data ' +
      '- just set it to null.'
    );
    return
  }
  if (!hasOwn(obj, key)) {
    return
  }
  delete obj[key];
  if (!ob) {
    return
  }
  ob.dep.notify();
}

/**
 * Collect dependencies on array elements when the array is touched, since
 * we cannot intercept array element access like property getters.
 */
function dependArray (value) {
  for (var e = (void 0), i = 0, l = value.length; i < l; i++) {
    e = value[i];
    e && e.__ob__ && e.__ob__.dep.depend();
    if (Array.isArray(e)) {
      dependArray(e);
    }
  }
}

/*  */

function initState (vm) {
  vm._watchers = [];
  initProps(vm);
  initData(vm);
  initComputed(vm);
  initMethods(vm);
  initWatch(vm);
}

function initProps (vm) {
  var props = vm.$options.props;
  if (props) {
    var propsData = vm.$options.propsData || {};
    var keys = vm.$options._propKeys = Object.keys(props);
    var isRoot = !vm.$parent;
    // root instance props should be converted
    observerState.shouldConvert = isRoot;
    var loop = function ( i ) {
      var key = keys[i];
      /* istanbul ignore else */
      {
        defineReactive$$1(vm, key, validateProp(key, props, propsData, vm), function () {
          if (vm.$parent && !observerState.isSettingProps) {
            warn(
              "Avoid mutating a prop directly since the value will be " +
              "overwritten whenever the parent component re-renders. " +
              "Instead, use a data or computed property based on the prop's " +
              "value. Prop being mutated: \"" + key + "\"",
              vm
            );
          }
        });
      }
    };

    for (var i = 0; i < keys.length; i++) loop( i );
    observerState.shouldConvert = true;
  }
}

function initData (vm) {
  var data = vm.$options.data;
  data = vm._data = typeof data === 'function'
    ? data.call(vm)
    : data || {};
  if (!isPlainObject(data)) {
    data = {};
    "development" !== 'production' && warn(
      'data functions should return an object.',
      vm
    );
  }
  // proxy data on instance
  var keys = Object.keys(data);
  var props = vm.$options.props;
  var i = keys.length;
  while (i--) {
    if (props && hasOwn(props, keys[i])) {
      "development" !== 'production' && warn(
        "The data property \"" + (keys[i]) + "\" is already declared as a prop. " +
        "Use prop default value instead.",
        vm
      );
    } else {
      proxy(vm, keys[i]);
    }
  }
  // observe data
  observe(data);
  data.__ob__ && data.__ob__.vmCount++;
}

var computedSharedDefinition = {
  enumerable: true,
  configurable: true,
  get: noop,
  set: noop
};

function initComputed (vm) {
  var computed = vm.$options.computed;
  if (computed) {
    for (var key in computed) {
      var userDef = computed[key];
      if (typeof userDef === 'function') {
        computedSharedDefinition.get = makeComputedGetter(userDef, vm);
        computedSharedDefinition.set = noop;
      } else {
        computedSharedDefinition.get = userDef.get
          ? userDef.cache !== false
            ? makeComputedGetter(userDef.get, vm)
            : bind$1(userDef.get, vm)
          : noop;
        computedSharedDefinition.set = userDef.set
          ? bind$1(userDef.set, vm)
          : noop;
      }
      Object.defineProperty(vm, key, computedSharedDefinition);
    }
  }
}

function makeComputedGetter (getter, owner) {
  var watcher = new Watcher(owner, getter, noop, {
    lazy: true
  });
  return function computedGetter () {
    if (watcher.dirty) {
      watcher.evaluate();
    }
    if (Dep.target) {
      watcher.depend();
    }
    return watcher.value
  }
}

function initMethods (vm) {
  var methods = vm.$options.methods;
  if (methods) {
    for (var key in methods) {
      vm[key] = methods[key] == null ? noop : bind$1(methods[key], vm);
      if ("development" !== 'production' && methods[key] == null) {
        warn(
          "method \"" + key + "\" has an undefined value in the component definition. " +
          "Did you reference the function correctly?",
          vm
        );
      }
    }
  }
}

function initWatch (vm) {
  var watch = vm.$options.watch;
  if (watch) {
    for (var key in watch) {
      var handler = watch[key];
      if (Array.isArray(handler)) {
        for (var i = 0; i < handler.length; i++) {
          createWatcher(vm, key, handler[i]);
        }
      } else {
        createWatcher(vm, key, handler);
      }
    }
  }
}

function createWatcher (vm, key, handler) {
  var options;
  if (isPlainObject(handler)) {
    options = handler;
    handler = handler.handler;
  }
  if (typeof handler === 'string') {
    handler = vm[handler];
  }
  vm.$watch(key, handler, options);
}

function stateMixin (Vue) {
  // flow somehow has problems with directly declared definition object
  // when using Object.defineProperty, so we have to procedurally build up
  // the object here.
  var dataDef = {};
  dataDef.get = function () {
    return this._data
  };
  {
    dataDef.set = function (newData) {
      warn(
        'Avoid replacing instance root $data. ' +
        'Use nested data properties instead.',
        this
      );
    };
  }
  Object.defineProperty(Vue.prototype, '$data', dataDef);

  Vue.prototype.$set = set;
  Vue.prototype.$delete = del;

  Vue.prototype.$watch = function (
    expOrFn,
    cb,
    options
  ) {
    var vm = this;
    options = options || {};
    options.user = true;
    var watcher = new Watcher(vm, expOrFn, cb, options);
    if (options.immediate) {
      cb.call(vm, watcher.value);
    }
    return function unwatchFn () {
      watcher.teardown();
    }
  };
}

function proxy (vm, key) {
  if (!isReserved(key)) {
    Object.defineProperty(vm, key, {
      configurable: true,
      enumerable: true,
      get: function proxyGetter () {
        return vm._data[key]
      },
      set: function proxySetter (val) {
        vm._data[key] = val;
      }
    });
  }
}

/*  */

var VNode = function VNode (
  tag,
  data,
  children,
  text,
  elm,
  ns,
  context,
  componentOptions
) {
  this.tag = tag;
  this.data = data;
  this.children = children;
  this.text = text;
  this.elm = elm;
  this.ns = ns;
  this.context = context;
  this.functionalContext = undefined;
  this.key = data && data.key;
  this.componentOptions = componentOptions;
  this.child = undefined;
  this.parent = undefined;
  this.raw = false;
  this.isStatic = false;
  this.isRootInsert = true;
  this.isComment = false;
  this.isCloned = false;
  this.isOnce = false;
};

var emptyVNode = function () {
  var node = new VNode();
  node.text = '';
  node.isComment = true;
  return node
};

// optimized shallow clone
// used for static nodes and slot nodes because they may be reused across
// multiple renders, cloning them avoids errors when DOM manipulations rely
// on their elm reference.
function cloneVNode (vnode) {
  var cloned = new VNode(
    vnode.tag,
    vnode.data,
    vnode.children,
    vnode.text,
    vnode.elm,
    vnode.ns,
    vnode.context,
    vnode.componentOptions
  );
  cloned.isStatic = vnode.isStatic;
  cloned.key = vnode.key;
  cloned.isCloned = true;
  return cloned
}

function cloneVNodes (vnodes) {
  var res = new Array(vnodes.length);
  for (var i = 0; i < vnodes.length; i++) {
    res[i] = cloneVNode(vnodes[i]);
  }
  return res
}

/*  */

function mergeVNodeHook (def, hookKey, hook, key) {
  key = key + hookKey;
  var injectedHash = def.__injected || (def.__injected = {});
  if (!injectedHash[key]) {
    injectedHash[key] = true;
    var oldHook = def[hookKey];
    if (oldHook) {
      def[hookKey] = function () {
        oldHook.apply(this, arguments);
        hook.apply(this, arguments);
      };
    } else {
      def[hookKey] = hook;
    }
  }
}

/*  */

function updateListeners (
  on,
  oldOn,
  add,
  remove$$1,
  vm
) {
  var name, cur, old, fn, event, capture;
  for (name in on) {
    cur = on[name];
    old = oldOn[name];
    if (!cur) {
      "development" !== 'production' && warn(
        "Invalid handler for event \"" + name + "\": got " + String(cur),
        vm
      );
    } else if (!old) {
      capture = name.charAt(0) === '!';
      event = capture ? name.slice(1) : name;
      if (Array.isArray(cur)) {
        add(event, (cur.invoker = arrInvoker(cur)), capture);
      } else {
        if (!cur.invoker) {
          fn = cur;
          cur = on[name] = {};
          cur.fn = fn;
          cur.invoker = fnInvoker(cur);
        }
        add(event, cur.invoker, capture);
      }
    } else if (cur !== old) {
      if (Array.isArray(old)) {
        old.length = cur.length;
        for (var i = 0; i < old.length; i++) { old[i] = cur[i]; }
        on[name] = old;
      } else {
        old.fn = cur;
        on[name] = old;
      }
    }
  }
  for (name in oldOn) {
    if (!on[name]) {
      event = name.charAt(0) === '!' ? name.slice(1) : name;
      remove$$1(event, oldOn[name].invoker);
    }
  }
}

function arrInvoker (arr) {
  return function (ev) {
    var arguments$1 = arguments;

    var single = arguments.length === 1;
    for (var i = 0; i < arr.length; i++) {
      single ? arr[i](ev) : arr[i].apply(null, arguments$1);
    }
  }
}

function fnInvoker (o) {
  return function (ev) {
    var single = arguments.length === 1;
    single ? o.fn(ev) : o.fn.apply(null, arguments);
  }
}

/*  */

function normalizeChildren (
  children,
  ns,
  nestedIndex
) {
  if (isPrimitive(children)) {
    return [createTextVNode(children)]
  }
  if (Array.isArray(children)) {
    var res = [];
    for (var i = 0, l = children.length; i < l; i++) {
      var c = children[i];
      var last = res[res.length - 1];
      //  nested
      if (Array.isArray(c)) {
        res.push.apply(res, normalizeChildren(c, ns, ((nestedIndex || '') + "_" + i)));
      } else if (isPrimitive(c)) {
        if (last && last.text) {
          last.text += String(c);
        } else if (c !== '') {
          // convert primitive to vnode
          res.push(createTextVNode(c));
        }
      } else if (c instanceof VNode) {
        if (c.text && last && last.text) {
          if (!last.isCloned) {
            last.text += c.text;
          }
        } else {
          // inherit parent namespace
          if (ns) {
            applyNS(c, ns);
          }
          // default key for nested array children (likely generated by v-for)
          if (c.tag && c.key == null && nestedIndex != null) {
            c.key = "__vlist" + nestedIndex + "_" + i + "__";
          }
          res.push(c);
        }
      }
    }
    return res
  }
}

function createTextVNode (val) {
  return new VNode(undefined, undefined, undefined, String(val))
}

function applyNS (vnode, ns) {
  if (vnode.tag && !vnode.ns) {
    vnode.ns = ns;
    if (vnode.children) {
      for (var i = 0, l = vnode.children.length; i < l; i++) {
        applyNS(vnode.children[i], ns);
      }
    }
  }
}

/*  */

function getFirstComponentChild (children) {
  return children && children.filter(function (c) { return c && c.componentOptions; })[0]
}

/*  */

var activeInstance = null;

function initLifecycle (vm) {
  var options = vm.$options;

  // locate first non-abstract parent
  var parent = options.parent;
  if (parent && !options.abstract) {
    while (parent.$options.abstract && parent.$parent) {
      parent = parent.$parent;
    }
    parent.$children.push(vm);
  }

  vm.$parent = parent;
  vm.$root = parent ? parent.$root : vm;

  vm.$children = [];
  vm.$refs = {};

  vm._watcher = null;
  vm._inactive = false;
  vm._isMounted = false;
  vm._isDestroyed = false;
  vm._isBeingDestroyed = false;
}

function lifecycleMixin (Vue) {
  Vue.prototype._mount = function (
    el,
    hydrating
  ) {
    var vm = this;
    vm.$el = el;
    if (!vm.$options.render) {
      vm.$options.render = emptyVNode;
      {
        /* istanbul ignore if */
        if (vm.$options.template && vm.$options.template.charAt(0) !== '#') {
          warn(
            'You are using the runtime-only build of Vue where the template ' +
            'option is not available. Either pre-compile the templates into ' +
            'render functions, or use the compiler-included build.',
            vm
          );
        } else {
          warn(
            'Failed to mount component: template or render function not defined.',
            vm
          );
        }
      }
    }
    callHook(vm, 'beforeMount');
    vm._watcher = new Watcher(vm, function () {
      vm._update(vm._render(), hydrating);
    }, noop);
    hydrating = false;
    // manually mounted instance, call mounted on self
    // mounted is called for render-created child components in its inserted hook
    if (vm.$vnode == null) {
      vm._isMounted = true;
      callHook(vm, 'mounted');
    }
    return vm
  };

  Vue.prototype._update = function (vnode, hydrating) {
    var vm = this;
    if (vm._isMounted) {
      callHook(vm, 'beforeUpdate');
    }
    var prevEl = vm.$el;
    var prevActiveInstance = activeInstance;
    activeInstance = vm;
    var prevVnode = vm._vnode;
    vm._vnode = vnode;
    if (!prevVnode) {
      // Vue.prototype.__patch__ is injected in entry points
      // based on the rendering backend used.
      vm.$el = vm.__patch__(vm.$el, vnode, hydrating);
    } else {
      vm.$el = vm.__patch__(prevVnode, vnode);
    }
    activeInstance = prevActiveInstance;
    // update __vue__ reference
    if (prevEl) {
      prevEl.__vue__ = null;
    }
    if (vm.$el) {
      vm.$el.__vue__ = vm;
    }
    // if parent is an HOC, update its $el as well
    if (vm.$vnode && vm.$parent && vm.$vnode === vm.$parent._vnode) {
      vm.$parent.$el = vm.$el;
    }
    if (vm._isMounted) {
      callHook(vm, 'updated');
    }
  };

  Vue.prototype._updateFromParent = function (
    propsData,
    listeners,
    parentVnode,
    renderChildren
  ) {
    var vm = this;
    var hasChildren = !!(vm.$options._renderChildren || renderChildren);
    vm.$options._parentVnode = parentVnode;
    vm.$options._renderChildren = renderChildren;
    // update props
    if (propsData && vm.$options.props) {
      observerState.shouldConvert = false;
      {
        observerState.isSettingProps = true;
      }
      var propKeys = vm.$options._propKeys || [];
      for (var i = 0; i < propKeys.length; i++) {
        var key = propKeys[i];
        vm[key] = validateProp(key, vm.$options.props, propsData, vm);
      }
      observerState.shouldConvert = true;
      {
        observerState.isSettingProps = false;
      }
      vm.$options.propsData = propsData;
    }
    // update listeners
    if (listeners) {
      var oldListeners = vm.$options._parentListeners;
      vm.$options._parentListeners = listeners;
      vm._updateListeners(listeners, oldListeners);
    }
    // resolve slots + force update if has children
    if (hasChildren) {
      vm.$slots = resolveSlots(renderChildren, vm._renderContext);
      vm.$forceUpdate();
    }
  };

  Vue.prototype.$forceUpdate = function () {
    var vm = this;
    if (vm._watcher) {
      vm._watcher.update();
    }
  };

  Vue.prototype.$destroy = function () {
    var vm = this;
    if (vm._isBeingDestroyed) {
      return
    }
    callHook(vm, 'beforeDestroy');
    vm._isBeingDestroyed = true;
    // remove self from parent
    var parent = vm.$parent;
    if (parent && !parent._isBeingDestroyed && !vm.$options.abstract) {
      remove$1(parent.$children, vm);
    }
    // teardown watchers
    if (vm._watcher) {
      vm._watcher.teardown();
    }
    var i = vm._watchers.length;
    while (i--) {
      vm._watchers[i].teardown();
    }
    // remove reference from data ob
    // frozen object may not have observer.
    if (vm._data.__ob__) {
      vm._data.__ob__.vmCount--;
    }
    // call the last hook...
    vm._isDestroyed = true;
    callHook(vm, 'destroyed');
    // turn off all instance listeners.
    vm.$off();
    // remove __vue__ reference
    if (vm.$el) {
      vm.$el.__vue__ = null;
    }
    // invoke destroy hooks on current rendered tree
    vm.__patch__(vm._vnode, null);
  };
}

function callHook (vm, hook) {
  var handlers = vm.$options[hook];
  if (handlers) {
    for (var i = 0, j = handlers.length; i < j; i++) {
      handlers[i].call(vm);
    }
  }
  vm.$emit('hook:' + hook);
}

/*  */

var hooks = { init: init, prepatch: prepatch, insert: insert, destroy: destroy$1 };
var hooksToMerge = Object.keys(hooks);

function createComponent (
  Ctor,
  data,
  context,
  children,
  tag
) {
  if (!Ctor) {
    return
  }

  var baseCtor = context.$options._base;
  if (isObject(Ctor)) {
    Ctor = baseCtor.extend(Ctor);
  }

  if (typeof Ctor !== 'function') {
    {
      warn(("Invalid Component definition: " + (String(Ctor))), context);
    }
    return
  }

  // async component
  if (!Ctor.cid) {
    if (Ctor.resolved) {
      Ctor = Ctor.resolved;
    } else {
      Ctor = resolveAsyncComponent(Ctor, baseCtor, function () {
        // it's ok to queue this on every render because
        // $forceUpdate is buffered by the scheduler.
        context.$forceUpdate();
      });
      if (!Ctor) {
        // return nothing if this is indeed an async component
        // wait for the callback to trigger parent update.
        return
      }
    }
  }

  // resolve constructor options in case global mixins are applied after
  // component constructor creation
  resolveConstructorOptions(Ctor);

  data = data || {};

  // extract props
  var propsData = extractProps(data, Ctor);

  // functional component
  if (Ctor.options.functional) {
    return createFunctionalComponent(Ctor, propsData, data, context, children)
  }

  // extract listeners, since these needs to be treated as
  // child component listeners instead of DOM listeners
  var listeners = data.on;
  // replace with listeners with .native modifier
  data.on = data.nativeOn;

  if (Ctor.options.abstract) {
    // abstract components do not keep anything
    // other than props & listeners
    data = {};
  }

  // merge component management hooks onto the placeholder node
  mergeHooks(data);

  // return a placeholder vnode
  var name = Ctor.options.name || tag;
  var vnode = new VNode(
    ("vue-component-" + (Ctor.cid) + (name ? ("-" + name) : '')),
    data, undefined, undefined, undefined, undefined, context,
    { Ctor: Ctor, propsData: propsData, listeners: listeners, tag: tag, children: children }
  );
  return vnode
}

function createFunctionalComponent (
  Ctor,
  propsData,
  data,
  context,
  children
) {
  var props = {};
  var propOptions = Ctor.options.props;
  if (propOptions) {
    for (var key in propOptions) {
      props[key] = validateProp(key, propOptions, propsData);
    }
  }
  var vnode = Ctor.options.render.call(
    null,
    // ensure the createElement function in functional components
    // gets a unique context - this is necessary for correct named slot check
    bind$1(createElement, { _self: Object.create(context) }),
    {
      props: props,
      data: data,
      parent: context,
      children: normalizeChildren(children),
      slots: function () { return resolveSlots(children, context); }
    }
  );
  if (vnode instanceof VNode) {
    vnode.functionalContext = context;
    if (data.slot) {
      (vnode.data || (vnode.data = {})).slot = data.slot;
    }
  }
  return vnode
}

function createComponentInstanceForVnode (
  vnode, // we know it's MountedComponentVNode but flow doesn't
  parent // activeInstance in lifecycle state
) {
  var vnodeComponentOptions = vnode.componentOptions;
  var options = {
    _isComponent: true,
    parent: parent,
    propsData: vnodeComponentOptions.propsData,
    _componentTag: vnodeComponentOptions.tag,
    _parentVnode: vnode,
    _parentListeners: vnodeComponentOptions.listeners,
    _renderChildren: vnodeComponentOptions.children
  };
  // check inline-template render functions
  var inlineTemplate = vnode.data.inlineTemplate;
  if (inlineTemplate) {
    options.render = inlineTemplate.render;
    options.staticRenderFns = inlineTemplate.staticRenderFns;
  }
  return new vnodeComponentOptions.Ctor(options)
}

function init (vnode, hydrating) {
  if (!vnode.child || vnode.child._isDestroyed) {
    var child = vnode.child = createComponentInstanceForVnode(vnode, activeInstance);
    child.$mount(hydrating ? vnode.elm : undefined, hydrating);
  }
}

function prepatch (
  oldVnode,
  vnode
) {
  var options = vnode.componentOptions;
  var child = vnode.child = oldVnode.child;
  child._updateFromParent(
    options.propsData, // updated props
    options.listeners, // updated listeners
    vnode, // new parent vnode
    options.children // new children
  );
}

function insert (vnode) {
  if (!vnode.child._isMounted) {
    vnode.child._isMounted = true;
    callHook(vnode.child, 'mounted');
  }
  if (vnode.data.keepAlive) {
    vnode.child._inactive = false;
    callHook(vnode.child, 'activated');
  }
}

function destroy$1 (vnode) {
  if (!vnode.child._isDestroyed) {
    if (!vnode.data.keepAlive) {
      vnode.child.$destroy();
    } else {
      vnode.child._inactive = true;
      callHook(vnode.child, 'deactivated');
    }
  }
}

function resolveAsyncComponent (
  factory,
  baseCtor,
  cb
) {
  if (factory.requested) {
    // pool callbacks
    factory.pendingCallbacks.push(cb);
  } else {
    factory.requested = true;
    var cbs = factory.pendingCallbacks = [cb];
    var sync = true;

    var resolve = function (res) {
      if (isObject(res)) {
        res = baseCtor.extend(res);
      }
      // cache resolved
      factory.resolved = res;
      // invoke callbacks only if this is not a synchronous resolve
      // (async resolves are shimmed as synchronous during SSR)
      if (!sync) {
        for (var i = 0, l = cbs.length; i < l; i++) {
          cbs[i](res);
        }
      }
    };

    var reject = function (reason) {
      "development" !== 'production' && warn(
        "Failed to resolve async component: " + (String(factory)) +
        (reason ? ("\nReason: " + reason) : '')
      );
    };

    var res = factory(resolve, reject);

    // handle promise
    if (res && typeof res.then === 'function' && !factory.resolved) {
      res.then(resolve, reject);
    }

    sync = false;
    // return in case resolved synchronously
    return factory.resolved
  }
}

function extractProps (data, Ctor) {
  // we are only extracting raw values here.
  // validation and default values are handled in the child
  // component itself.
  var propOptions = Ctor.options.props;
  if (!propOptions) {
    return
  }
  var res = {};
  var attrs = data.attrs;
  var props = data.props;
  var domProps = data.domProps;
  if (attrs || props || domProps) {
    for (var key in propOptions) {
      var altKey = hyphenate(key);
      checkProp(res, props, key, altKey, true) ||
      checkProp(res, attrs, key, altKey) ||
      checkProp(res, domProps, key, altKey);
    }
  }
  return res
}

function checkProp (
  res,
  hash,
  key,
  altKey,
  preserve
) {
  if (hash) {
    if (hasOwn(hash, key)) {
      res[key] = hash[key];
      if (!preserve) {
        delete hash[key];
      }
      return true
    } else if (hasOwn(hash, altKey)) {
      res[key] = hash[altKey];
      if (!preserve) {
        delete hash[altKey];
      }
      return true
    }
  }
  return false
}

function mergeHooks (data) {
  if (!data.hook) {
    data.hook = {};
  }
  for (var i = 0; i < hooksToMerge.length; i++) {
    var key = hooksToMerge[i];
    var fromParent = data.hook[key];
    var ours = hooks[key];
    data.hook[key] = fromParent ? mergeHook$1(ours, fromParent) : ours;
  }
}

function mergeHook$1 (a, b) {
  // since all hooks have at most two args, use fixed args
  // to avoid having to use fn.apply().
  return function (_, __) {
    a(_, __);
    b(_, __);
  }
}

/*  */

// wrapper function for providing a more flexible interface
// without getting yelled at by flow
function createElement (
  tag,
  data,
  children
) {
  if (data && (Array.isArray(data) || typeof data !== 'object')) {
    children = data;
    data = undefined;
  }
  // make sure to use real instance instead of proxy as context
  return _createElement(this._self, tag, data, children)
}

function _createElement (
  context,
  tag,
  data,
  children
) {
  if (data && data.__ob__) {
    "development" !== 'production' && warn(
      "Avoid using observed data object as vnode data: " + (JSON.stringify(data)) + "\n" +
      'Always create fresh vnode data objects in each render!',
      context
    );
    return
  }
  if (!tag) {
    // in case of component :is set to falsy value
    return emptyVNode()
  }
  if (typeof tag === 'string') {
    var Ctor;
    var ns = config.getTagNamespace(tag);
    if (config.isReservedTag(tag)) {
      // platform built-in elements
      return new VNode(
        tag, data, normalizeChildren(children, ns),
        undefined, undefined, ns, context
      )
    } else if ((Ctor = resolveAsset(context.$options, 'components', tag))) {
      // component
      return createComponent(Ctor, data, context, children, tag)
    } else {
      // unknown or unlisted namespaced elements
      // check at runtime because it may get assigned a namespace when its
      // parent normalizes children
      var childNs = tag === 'foreignObject' ? 'xhtml' : ns;
      return new VNode(
        tag, data, normalizeChildren(children, childNs),
        undefined, undefined, ns, context
      )
    }
  } else {
    // direct component options / constructor
    return createComponent(tag, data, context, children)
  }
}

/*  */

function initRender (vm) {
  vm.$vnode = null; // the placeholder node in parent tree
  vm._vnode = null; // the root of the child tree
  vm._staticTrees = null;
  vm._renderContext = vm.$options._parentVnode && vm.$options._parentVnode.context;
  vm.$slots = resolveSlots(vm.$options._renderChildren, vm._renderContext);
  // bind the public createElement fn to this instance
  // so that we get proper render context inside it.
  vm.$createElement = bind$1(createElement, vm);
  if (vm.$options.el) {
    vm.$mount(vm.$options.el);
  }
}

function renderMixin (Vue) {
  Vue.prototype.$nextTick = function (fn) {
    nextTick(fn, this);
  };

  Vue.prototype._render = function () {
    var vm = this;
    var ref = vm.$options;
    var render = ref.render;
    var staticRenderFns = ref.staticRenderFns;
    var _parentVnode = ref._parentVnode;

    if (vm._isMounted) {
      // clone slot nodes on re-renders
      for (var key in vm.$slots) {
        vm.$slots[key] = cloneVNodes(vm.$slots[key]);
      }
    }

    if (staticRenderFns && !vm._staticTrees) {
      vm._staticTrees = [];
    }
    // set parent vnode. this allows render functions to have access
    // to the data on the placeholder node.
    vm.$vnode = _parentVnode;
    // render self
    var vnode;
    try {
      vnode = render.call(vm._renderProxy, vm.$createElement);
    } catch (e) {
      {
        warn(("Error when rendering " + (formatComponentName(vm)) + ":"));
      }
      /* istanbul ignore else */
      if (config.errorHandler) {
        config.errorHandler.call(null, e, vm);
      } else {
        if (config._isServer) {
          throw e
        } else {
          console.error(e);
        }
      }
      // return previous vnode to prevent render error causing blank component
      vnode = vm._vnode;
    }
    // return empty vnode in case the render function errored out
    if (!(vnode instanceof VNode)) {
      if ("development" !== 'production' && Array.isArray(vnode)) {
        warn(
          'Multiple root nodes returned from render function. Render function ' +
          'should return a single root node.',
          vm
        );
      }
      vnode = emptyVNode();
    }
    // set parent
    vnode.parent = _parentVnode;
    return vnode
  };

  // shorthands used in render functions
  Vue.prototype._h = createElement;
  // toString for mustaches
  Vue.prototype._s = _toString;
  // number conversion
  Vue.prototype._n = toNumber;
  // empty vnode
  Vue.prototype._e = emptyVNode;
  // loose equal
  Vue.prototype._q = looseEqual;
  // loose indexOf
  Vue.prototype._i = looseIndexOf;

  // render static tree by index
  Vue.prototype._m = function renderStatic (
    index,
    isInFor
  ) {
    var tree = this._staticTrees[index];
    // if has already-rendered static tree and not inside v-for,
    // we can reuse the same tree by doing a shallow clone.
    if (tree && !isInFor) {
      return Array.isArray(tree)
        ? cloneVNodes(tree)
        : cloneVNode(tree)
    }
    // otherwise, render a fresh tree.
    tree = this._staticTrees[index] = this.$options.staticRenderFns[index].call(this._renderProxy);
    markStatic(tree, ("__static__" + index), false);
    return tree
  };

  // mark node as static (v-once)
  Vue.prototype._o = function markOnce (
    tree,
    index,
    key
  ) {
    markStatic(tree, ("__once__" + index + (key ? ("_" + key) : "")), true);
    return tree
  };

  function markStatic (tree, key, isOnce) {
    if (Array.isArray(tree)) {
      for (var i = 0; i < tree.length; i++) {
        if (tree[i] && typeof tree[i] !== 'string') {
          markStaticNode(tree[i], (key + "_" + i), isOnce);
        }
      }
    } else {
      markStaticNode(tree, key, isOnce);
    }
  }

  function markStaticNode (node, key, isOnce) {
    node.isStatic = true;
    node.key = key;
    node.isOnce = isOnce;
  }

  // filter resolution helper
  var identity = function (_) { return _; };
  Vue.prototype._f = function resolveFilter (id) {
    return resolveAsset(this.$options, 'filters', id, true) || identity
  };

  // render v-for
  Vue.prototype._l = function renderList (
    val,
    render
  ) {
    var ret, i, l, keys, key;
    if (Array.isArray(val)) {
      ret = new Array(val.length);
      for (i = 0, l = val.length; i < l; i++) {
        ret[i] = render(val[i], i);
      }
    } else if (typeof val === 'number') {
      ret = new Array(val);
      for (i = 0; i < val; i++) {
        ret[i] = render(i + 1, i);
      }
    } else if (isObject(val)) {
      keys = Object.keys(val);
      ret = new Array(keys.length);
      for (i = 0, l = keys.length; i < l; i++) {
        key = keys[i];
        ret[i] = render(val[key], key, i);
      }
    }
    return ret
  };

  // renderSlot
  Vue.prototype._t = function (
    name,
    fallback
  ) {
    var slotNodes = this.$slots[name];
    // warn duplicate slot usage
    if (slotNodes && "development" !== 'production') {
      slotNodes._rendered && warn(
        "Duplicate presence of slot \"" + name + "\" found in the same render tree " +
        "- this will likely cause render errors.",
        this
      );
      slotNodes._rendered = true;
    }
    return slotNodes || fallback
  };

  // apply v-bind object
  Vue.prototype._b = function bindProps (
    data,
    value,
    asProp
  ) {
    if (value) {
      if (!isObject(value)) {
        "development" !== 'production' && warn(
          'v-bind without argument expects an Object or Array value',
          this
        );
      } else {
        if (Array.isArray(value)) {
          value = toObject(value);
        }
        for (var key in value) {
          if (key === 'class' || key === 'style') {
            data[key] = value[key];
          } else {
            var hash = asProp || config.mustUseProp(key)
              ? data.domProps || (data.domProps = {})
              : data.attrs || (data.attrs = {});
            hash[key] = value[key];
          }
        }
      }
    }
    return data
  };

  // expose v-on keyCodes
  Vue.prototype._k = function getKeyCodes (key) {
    return config.keyCodes[key]
  };
}

function resolveSlots (
  renderChildren,
  context
) {
  var slots = {};
  if (!renderChildren) {
    return slots
  }
  var children = normalizeChildren(renderChildren) || [];
  var defaultSlot = [];
  var name, child;
  for (var i = 0, l = children.length; i < l; i++) {
    child = children[i];
    // named slots should only be respected if the vnode was rendered in the
    // same context.
    if ((child.context === context || child.functionalContext === context) &&
        child.data && (name = child.data.slot)) {
      var slot = (slots[name] || (slots[name] = []));
      if (child.tag === 'template') {
        slot.push.apply(slot, child.children);
      } else {
        slot.push(child);
      }
    } else {
      defaultSlot.push(child);
    }
  }
  // ignore single whitespace
  if (defaultSlot.length && !(
    defaultSlot.length === 1 &&
    (defaultSlot[0].text === ' ' || defaultSlot[0].isComment)
  )) {
    slots.default = defaultSlot;
  }
  return slots
}

/*  */

function initEvents (vm) {
  vm._events = Object.create(null);
  // init parent attached events
  var listeners = vm.$options._parentListeners;
  var on = bind$1(vm.$on, vm);
  var off = bind$1(vm.$off, vm);
  vm._updateListeners = function (listeners, oldListeners) {
    updateListeners(listeners, oldListeners || {}, on, off, vm);
  };
  if (listeners) {
    vm._updateListeners(listeners);
  }
}

function eventsMixin (Vue) {
  Vue.prototype.$on = function (event, fn) {
    var vm = this;(vm._events[event] || (vm._events[event] = [])).push(fn);
    return vm
  };

  Vue.prototype.$once = function (event, fn) {
    var vm = this;
    function on () {
      vm.$off(event, on);
      fn.apply(vm, arguments);
    }
    on.fn = fn;
    vm.$on(event, on);
    return vm
  };

  Vue.prototype.$off = function (event, fn) {
    var vm = this;
    // all
    if (!arguments.length) {
      vm._events = Object.create(null);
      return vm
    }
    // specific event
    var cbs = vm._events[event];
    if (!cbs) {
      return vm
    }
    if (arguments.length === 1) {
      vm._events[event] = null;
      return vm
    }
    // specific handler
    var cb;
    var i = cbs.length;
    while (i--) {
      cb = cbs[i];
      if (cb === fn || cb.fn === fn) {
        cbs.splice(i, 1);
        break
      }
    }
    return vm
  };

  Vue.prototype.$emit = function (event) {
    var vm = this;
    var cbs = vm._events[event];
    if (cbs) {
      cbs = cbs.length > 1 ? toArray(cbs) : cbs;
      var args = toArray(arguments, 1);
      for (var i = 0, l = cbs.length; i < l; i++) {
        cbs[i].apply(vm, args);
      }
    }
    return vm
  };
}

/*  */

var uid = 0;

function initMixin (Vue) {
  Vue.prototype._init = function (options) {
    var vm = this;
    // a uid
    vm._uid = uid++;
    // a flag to avoid this being observed
    vm._isVue = true;
    // merge options
    if (options && options._isComponent) {
      // optimize internal component instantiation
      // since dynamic options merging is pretty slow, and none of the
      // internal component options needs special treatment.
      initInternalComponent(vm, options);
    } else {
      vm.$options = mergeOptions(
        resolveConstructorOptions(vm.constructor),
        options || {},
        vm
      );
    }
    /* istanbul ignore else */
    {
      initProxy(vm);
    }
    // expose real self
    vm._self = vm;
    initLifecycle(vm);
    initEvents(vm);
    callHook(vm, 'beforeCreate');
    initState(vm);
    callHook(vm, 'created');
    initRender(vm);
  };
}

function initInternalComponent (vm, options) {
  var opts = vm.$options = Object.create(vm.constructor.options);
  // doing this because it's faster than dynamic enumeration.
  opts.parent = options.parent;
  opts.propsData = options.propsData;
  opts._parentVnode = options._parentVnode;
  opts._parentListeners = options._parentListeners;
  opts._renderChildren = options._renderChildren;
  opts._componentTag = options._componentTag;
  if (options.render) {
    opts.render = options.render;
    opts.staticRenderFns = options.staticRenderFns;
  }
}

function resolveConstructorOptions (Ctor) {
  var options = Ctor.options;
  if (Ctor.super) {
    var superOptions = Ctor.super.options;
    var cachedSuperOptions = Ctor.superOptions;
    var extendOptions = Ctor.extendOptions;
    if (superOptions !== cachedSuperOptions) {
      // super option changed
      Ctor.superOptions = superOptions;
      extendOptions.render = options.render;
      extendOptions.staticRenderFns = options.staticRenderFns;
      options = Ctor.options = mergeOptions(superOptions, extendOptions);
      if (options.name) {
        options.components[options.name] = Ctor;
      }
    }
  }
  return options
}

function Vue$3 (options) {
  if ("development" !== 'production' &&
    !(this instanceof Vue$3)) {
    warn('Vue is a constructor and should be called with the `new` keyword');
  }
  this._init(options);
}

initMixin(Vue$3);
stateMixin(Vue$3);
eventsMixin(Vue$3);
lifecycleMixin(Vue$3);
renderMixin(Vue$3);

var warn = noop;
var formatComponentName;

{
  var hasConsole = typeof console !== 'undefined';

  warn = function (msg, vm) {
    if (hasConsole && (!config.silent)) {
      console.error("[Vue warn]: " + msg + " " + (
        vm ? formatLocation(formatComponentName(vm)) : ''
      ));
    }
  };

  formatComponentName = function (vm) {
    if (vm.$root === vm) {
      return 'root instance'
    }
    var name = vm._isVue
      ? vm.$options.name || vm.$options._componentTag
      : vm.name;
    return (
      (name ? ("component <" + name + ">") : "anonymous component") +
      (vm._isVue && vm.$options.__file ? (" at " + (vm.$options.__file)) : '')
    )
  };

  var formatLocation = function (str) {
    if (str === 'anonymous component') {
      str += " - use the \"name\" option for better debugging messages.";
    }
    return ("\n(found in " + str + ")")
  };
}

/*  */

/**
 * Option overwriting strategies are functions that handle
 * how to merge a parent option value and a child option
 * value into the final value.
 */
var strats = config.optionMergeStrategies;

/**
 * Options with restrictions
 */
{
  strats.el = strats.propsData = function (parent, child, vm, key) {
    if (!vm) {
      warn(
        "option \"" + key + "\" can only be used during instance " +
        'creation with the `new` keyword.'
      );
    }
    return defaultStrat(parent, child)
  };
}

/**
 * Helper that recursively merges two data objects together.
 */
function mergeData (to, from) {
  if (!from) { return to }
  var key, toVal, fromVal;
  var keys = Object.keys(from);
  for (var i = 0; i < keys.length; i++) {
    key = keys[i];
    toVal = to[key];
    fromVal = from[key];
    if (!hasOwn(to, key)) {
      set(to, key, fromVal);
    } else if (isPlainObject(toVal) && isPlainObject(fromVal)) {
      mergeData(toVal, fromVal);
    }
  }
  return to
}

/**
 * Data
 */
strats.data = function (
  parentVal,
  childVal,
  vm
) {
  if (!vm) {
    // in a Vue.extend merge, both should be functions
    if (!childVal) {
      return parentVal
    }
    if (typeof childVal !== 'function') {
      "development" !== 'production' && warn(
        'The "data" option should be a function ' +
        'that returns a per-instance value in component ' +
        'definitions.',
        vm
      );
      return parentVal
    }
    if (!parentVal) {
      return childVal
    }
    // when parentVal & childVal are both present,
    // we need to return a function that returns the
    // merged result of both functions... no need to
    // check if parentVal is a function here because
    // it has to be a function to pass previous merges.
    return function mergedDataFn () {
      return mergeData(
        childVal.call(this),
        parentVal.call(this)
      )
    }
  } else if (parentVal || childVal) {
    return function mergedInstanceDataFn () {
      // instance merge
      var instanceData = typeof childVal === 'function'
        ? childVal.call(vm)
        : childVal;
      var defaultData = typeof parentVal === 'function'
        ? parentVal.call(vm)
        : undefined;
      if (instanceData) {
        return mergeData(instanceData, defaultData)
      } else {
        return defaultData
      }
    }
  }
};

/**
 * Hooks and param attributes are merged as arrays.
 */
function mergeHook (
  parentVal,
  childVal
) {
  return childVal
    ? parentVal
      ? parentVal.concat(childVal)
      : Array.isArray(childVal)
        ? childVal
        : [childVal]
    : parentVal
}

config._lifecycleHooks.forEach(function (hook) {
  strats[hook] = mergeHook;
});

/**
 * Assets
 *
 * When a vm is present (instance creation), we need to do
 * a three-way merge between constructor options, instance
 * options and parent options.
 */
function mergeAssets (parentVal, childVal) {
  var res = Object.create(parentVal || null);
  return childVal
    ? extend(res, childVal)
    : res
}

config._assetTypes.forEach(function (type) {
  strats[type + 's'] = mergeAssets;
});

/**
 * Watchers.
 *
 * Watchers hashes should not overwrite one
 * another, so we merge them as arrays.
 */
strats.watch = function (parentVal, childVal) {
  /* istanbul ignore if */
  if (!childVal) { return parentVal }
  if (!parentVal) { return childVal }
  var ret = {};
  extend(ret, parentVal);
  for (var key in childVal) {
    var parent = ret[key];
    var child = childVal[key];
    if (parent && !Array.isArray(parent)) {
      parent = [parent];
    }
    ret[key] = parent
      ? parent.concat(child)
      : [child];
  }
  return ret
};

/**
 * Other object hashes.
 */
strats.props =
strats.methods =
strats.computed = function (parentVal, childVal) {
  if (!childVal) { return parentVal }
  if (!parentVal) { return childVal }
  var ret = Object.create(null);
  extend(ret, parentVal);
  extend(ret, childVal);
  return ret
};

/**
 * Default strategy.
 */
var defaultStrat = function (parentVal, childVal) {
  return childVal === undefined
    ? parentVal
    : childVal
};

/**
 * Validate component names
 */
function checkComponents (options) {
  for (var key in options.components) {
    var lower = key.toLowerCase();
    if (isBuiltInTag(lower) || config.isReservedTag(lower)) {
      warn(
        'Do not use built-in or reserved HTML elements as component ' +
        'id: ' + key
      );
    }
  }
}

/**
 * Ensure all props option syntax are normalized into the
 * Object-based format.
 */
function normalizeProps (options) {
  var props = options.props;
  if (!props) { return }
  var res = {};
  var i, val, name;
  if (Array.isArray(props)) {
    i = props.length;
    while (i--) {
      val = props[i];
      if (typeof val === 'string') {
        name = camelize(val);
        res[name] = { type: null };
      } else {
        warn('props must be strings when using array syntax.');
      }
    }
  } else if (isPlainObject(props)) {
    for (var key in props) {
      val = props[key];
      name = camelize(key);
      res[name] = isPlainObject(val)
        ? val
        : { type: val };
    }
  }
  options.props = res;
}

/**
 * Normalize raw function directives into object format.
 */
function normalizeDirectives (options) {
  var dirs = options.directives;
  if (dirs) {
    for (var key in dirs) {
      var def = dirs[key];
      if (typeof def === 'function') {
        dirs[key] = { bind: def, update: def };
      }
    }
  }
}

/**
 * Merge two option objects into a new one.
 * Core utility used in both instantiation and inheritance.
 */
function mergeOptions (
  parent,
  child,
  vm
) {
  {
    checkComponents(child);
  }
  normalizeProps(child);
  normalizeDirectives(child);
  var extendsFrom = child.extends;
  if (extendsFrom) {
    parent = typeof extendsFrom === 'function'
      ? mergeOptions(parent, extendsFrom.options, vm)
      : mergeOptions(parent, extendsFrom, vm);
  }
  if (child.mixins) {
    for (var i = 0, l = child.mixins.length; i < l; i++) {
      var mixin = child.mixins[i];
      if (mixin.prototype instanceof Vue$3) {
        mixin = mixin.options;
      }
      parent = mergeOptions(parent, mixin, vm);
    }
  }
  var options = {};
  var key;
  for (key in parent) {
    mergeField(key);
  }
  for (key in child) {
    if (!hasOwn(parent, key)) {
      mergeField(key);
    }
  }
  function mergeField (key) {
    var strat = strats[key] || defaultStrat;
    options[key] = strat(parent[key], child[key], vm, key);
  }
  return options
}

/**
 * Resolve an asset.
 * This function is used because child instances need access
 * to assets defined in its ancestor chain.
 */
function resolveAsset (
  options,
  type,
  id,
  warnMissing
) {
  /* istanbul ignore if */
  if (typeof id !== 'string') {
    return
  }
  var assets = options[type];
  var res = assets[id] ||
    // camelCase ID
    assets[camelize(id)] ||
    // Pascal Case ID
    assets[capitalize(camelize(id))];
  if ("development" !== 'production' && warnMissing && !res) {
    warn(
      'Failed to resolve ' + type.slice(0, -1) + ': ' + id,
      options
    );
  }
  return res
}

/*  */

function validateProp (
  key,
  propOptions,
  propsData,
  vm
) {
  var prop = propOptions[key];
  var absent = !hasOwn(propsData, key);
  var value = propsData[key];
  // handle boolean props
  if (isBooleanType(prop.type)) {
    if (absent && !hasOwn(prop, 'default')) {
      value = false;
    } else if (value === '' || value === hyphenate(key)) {
      value = true;
    }
  }
  // check default value
  if (value === undefined) {
    value = getPropDefaultValue(vm, prop, key);
    // since the default value is a fresh copy,
    // make sure to observe it.
    var prevShouldConvert = observerState.shouldConvert;
    observerState.shouldConvert = true;
    observe(value);
    observerState.shouldConvert = prevShouldConvert;
  }
  {
    assertProp(prop, key, value, vm, absent);
  }
  return value
}

/**
 * Get the default value of a prop.
 */
function getPropDefaultValue (vm, prop, key) {
  // no default, return undefined
  if (!hasOwn(prop, 'default')) {
    return undefined
  }
  var def = prop.default;
  // warn against non-factory defaults for Object & Array
  if (isObject(def)) {
    "development" !== 'production' && warn(
      'Invalid default value for prop "' + key + '": ' +
      'Props with type Object/Array must use a factory function ' +
      'to return the default value.',
      vm
    );
  }
  // the raw prop value was also undefined from previous render,
  // return previous default value to avoid unnecessary watcher trigger
  if (vm && vm.$options.propsData &&
    vm.$options.propsData[key] === undefined &&
    vm[key] !== undefined) {
    return vm[key]
  }
  // call factory function for non-Function types
  return typeof def === 'function' && prop.type !== Function
    ? def.call(vm)
    : def
}

/**
 * Assert whether a prop is valid.
 */
function assertProp (
  prop,
  name,
  value,
  vm,
  absent
) {
  if (prop.required && absent) {
    warn(
      'Missing required prop: "' + name + '"',
      vm
    );
    return
  }
  if (value == null && !prop.required) {
    return
  }
  var type = prop.type;
  var valid = !type || type === true;
  var expectedTypes = [];
  if (type) {
    if (!Array.isArray(type)) {
      type = [type];
    }
    for (var i = 0; i < type.length && !valid; i++) {
      var assertedType = assertType(value, type[i]);
      expectedTypes.push(assertedType.expectedType);
      valid = assertedType.valid;
    }
  }
  if (!valid) {
    warn(
      'Invalid prop: type check failed for prop "' + name + '".' +
      ' Expected ' + expectedTypes.map(capitalize).join(', ') +
      ', got ' + Object.prototype.toString.call(value).slice(8, -1) + '.',
      vm
    );
    return
  }
  var validator = prop.validator;
  if (validator) {
    if (!validator(value)) {
      warn(
        'Invalid prop: custom validator check failed for prop "' + name + '".',
        vm
      );
    }
  }
}

/**
 * Assert the type of a value
 */
function assertType (value, type) {
  var valid;
  var expectedType = getType(type);
  if (expectedType === 'String') {
    valid = typeof value === (expectedType = 'string');
  } else if (expectedType === 'Number') {
    valid = typeof value === (expectedType = 'number');
  } else if (expectedType === 'Boolean') {
    valid = typeof value === (expectedType = 'boolean');
  } else if (expectedType === 'Function') {
    valid = typeof value === (expectedType = 'function');
  } else if (expectedType === 'Object') {
    valid = isPlainObject(value);
  } else if (expectedType === 'Array') {
    valid = Array.isArray(value);
  } else {
    valid = value instanceof type;
  }
  return {
    valid: valid,
    expectedType: expectedType
  }
}

/**
 * Use function string name to check built-in types,
 * because a simple equality check will fail when running
 * across different vms / iframes.
 */
function getType (fn) {
  var match = fn && fn.toString().match(/^\s*function (\w+)/);
  return match && match[1]
}

function isBooleanType (fn) {
  if (!Array.isArray(fn)) {
    return getType(fn) === 'Boolean'
  }
  for (var i = 0, len = fn.length; i < len; i++) {
    if (getType(fn[i]) === 'Boolean') {
      return true
    }
  }
  /* istanbul ignore next */
  return false
}



var util = Object.freeze({
	defineReactive: defineReactive$$1,
	_toString: _toString,
	toNumber: toNumber,
	makeMap: makeMap,
	isBuiltInTag: isBuiltInTag,
	remove: remove$1,
	hasOwn: hasOwn,
	isPrimitive: isPrimitive,
	cached: cached,
	camelize: camelize,
	capitalize: capitalize,
	hyphenate: hyphenate,
	bind: bind$1,
	toArray: toArray,
	extend: extend,
	isObject: isObject,
	isPlainObject: isPlainObject,
	toObject: toObject,
	noop: noop,
	no: no,
	genStaticKeys: genStaticKeys,
	looseEqual: looseEqual,
	looseIndexOf: looseIndexOf,
	isReserved: isReserved,
	def: def,
	parsePath: parsePath,
	hasProto: hasProto,
	inBrowser: inBrowser,
	UA: UA,
	isIE: isIE,
	isIE9: isIE9,
	isEdge: isEdge,
	isAndroid: isAndroid,
	isIOS: isIOS,
	devtools: devtools,
	nextTick: nextTick,
	get _Set () { return _Set; },
	mergeOptions: mergeOptions,
	resolveAsset: resolveAsset,
	get warn () { return warn; },
	get formatComponentName () { return formatComponentName; },
	validateProp: validateProp
});

/*  */

function initUse (Vue) {
  Vue.use = function (plugin) {
    /* istanbul ignore if */
    if (plugin.installed) {
      return
    }
    // additional parameters
    var args = toArray(arguments, 1);
    args.unshift(this);
    if (typeof plugin.install === 'function') {
      plugin.install.apply(plugin, args);
    } else {
      plugin.apply(null, args);
    }
    plugin.installed = true;
    return this
  };
}

/*  */

function initMixin$1 (Vue) {
  Vue.mixin = function (mixin) {
    this.options = mergeOptions(this.options, mixin);
  };
}

/*  */

function initExtend (Vue) {
  /**
   * Each instance constructor, including Vue, has a unique
   * cid. This enables us to create wrapped "child
   * constructors" for prototypal inheritance and cache them.
   */
  Vue.cid = 0;
  var cid = 1;

  /**
   * Class inheritance
   */
  Vue.extend = function (extendOptions) {
    extendOptions = extendOptions || {};
    var Super = this;
    var SuperId = Super.cid;
    var cachedCtors = extendOptions._Ctor || (extendOptions._Ctor = {});
    if (cachedCtors[SuperId]) {
      return cachedCtors[SuperId]
    }
    var name = extendOptions.name || Super.options.name;
    {
      if (!/^[a-zA-Z][\w-]*$/.test(name)) {
        warn(
          'Invalid component name: "' + name + '". Component names ' +
          'can only contain alphanumeric characaters and the hyphen.'
        );
      }
    }
    var Sub = function VueComponent (options) {
      this._init(options);
    };
    Sub.prototype = Object.create(Super.prototype);
    Sub.prototype.constructor = Sub;
    Sub.cid = cid++;
    Sub.options = mergeOptions(
      Super.options,
      extendOptions
    );
    Sub['super'] = Super;
    // allow further extension/mixin/plugin usage
    Sub.extend = Super.extend;
    Sub.mixin = Super.mixin;
    Sub.use = Super.use;
    // create asset registers, so extended classes
    // can have their private assets too.
    config._assetTypes.forEach(function (type) {
      Sub[type] = Super[type];
    });
    // enable recursive self-lookup
    if (name) {
      Sub.options.components[name] = Sub;
    }
    // keep a reference to the super options at extension time.
    // later at instantiation we can check if Super's options have
    // been updated.
    Sub.superOptions = Super.options;
    Sub.extendOptions = extendOptions;
    // cache constructor
    cachedCtors[SuperId] = Sub;
    return Sub
  };
}

/*  */

function initAssetRegisters (Vue) {
  /**
   * Create asset registration methods.
   */
  config._assetTypes.forEach(function (type) {
    Vue[type] = function (
      id,
      definition
    ) {
      if (!definition) {
        return this.options[type + 's'][id]
      } else {
        /* istanbul ignore if */
        {
          if (type === 'component' && config.isReservedTag(id)) {
            warn(
              'Do not use built-in or reserved HTML elements as component ' +
              'id: ' + id
            );
          }
        }
        if (type === 'component' && isPlainObject(definition)) {
          definition.name = definition.name || id;
          definition = this.options._base.extend(definition);
        }
        if (type === 'directive' && typeof definition === 'function') {
          definition = { bind: definition, update: definition };
        }
        this.options[type + 's'][id] = definition;
        return definition
      }
    };
  });
}

var KeepAlive = {
  name: 'keep-alive',
  abstract: true,
  created: function created () {
    this.cache = Object.create(null);
  },
  render: function render () {
    var vnode = getFirstComponentChild(this.$slots.default);
    if (vnode && vnode.componentOptions) {
      var opts = vnode.componentOptions;
      var key = vnode.key == null
        // same constructor may get registered as different local components
        // so cid alone is not enough (#3269)
        ? opts.Ctor.cid + '::' + opts.tag
        : vnode.key;
      if (this.cache[key]) {
        vnode.child = this.cache[key].child;
      } else {
        this.cache[key] = vnode;
      }
      vnode.data.keepAlive = true;
    }
    return vnode
  },
  destroyed: function destroyed () {
    var this$1 = this;

    for (var key in this.cache) {
      var vnode = this$1.cache[key];
      callHook(vnode.child, 'deactivated');
      vnode.child.$destroy();
    }
  }
};

var builtInComponents = {
  KeepAlive: KeepAlive
};

/*  */

function initGlobalAPI (Vue) {
  // config
  var configDef = {};
  configDef.get = function () { return config; };
  {
    configDef.set = function () {
      warn(
        'Do not replace the Vue.config object, set individual fields instead.'
      );
    };
  }
  Object.defineProperty(Vue, 'config', configDef);
  Vue.util = util;
  Vue.set = set;
  Vue.delete = del;
  Vue.nextTick = nextTick;

  Vue.options = Object.create(null);
  config._assetTypes.forEach(function (type) {
    Vue.options[type + 's'] = Object.create(null);
  });

  // this is used to identify the "base" constructor to extend all plain-object
  // components with in Weex's multi-instance scenarios.
  Vue.options._base = Vue;

  extend(Vue.options.components, builtInComponents);

  initUse(Vue);
  initMixin$1(Vue);
  initExtend(Vue);
  initAssetRegisters(Vue);
}

initGlobalAPI(Vue$3);

Object.defineProperty(Vue$3.prototype, '$isServer', {
  get: function () { return config._isServer; }
});

Vue$3.version = '2.0.7';

/*  */

// attributes that should be using props for binding
var mustUseProp = makeMap('value,selected,checked,muted');

var isEnumeratedAttr = makeMap('contenteditable,draggable,spellcheck');

var isBooleanAttr = makeMap(
  'allowfullscreen,async,autofocus,autoplay,checked,compact,controls,declare,' +
  'default,defaultchecked,defaultmuted,defaultselected,defer,disabled,' +
  'enabled,formnovalidate,hidden,indeterminate,inert,ismap,itemscope,loop,multiple,' +
  'muted,nohref,noresize,noshade,novalidate,nowrap,open,pauseonexit,readonly,' +
  'required,reversed,scoped,seamless,selected,sortable,translate,' +
  'truespeed,typemustmatch,visible'
);

var isAttr = makeMap(
  'accept,accept-charset,accesskey,action,align,alt,async,autocomplete,' +
  'autofocus,autoplay,autosave,bgcolor,border,buffered,challenge,charset,' +
  'checked,cite,class,code,codebase,color,cols,colspan,content,http-equiv,' +
  'name,contenteditable,contextmenu,controls,coords,data,datetime,default,' +
  'defer,dir,dirname,disabled,download,draggable,dropzone,enctype,method,for,' +
  'form,formaction,headers,<th>,height,hidden,high,href,hreflang,http-equiv,' +
  'icon,id,ismap,itemprop,keytype,kind,label,lang,language,list,loop,low,' +
  'manifest,max,maxlength,media,method,GET,POST,min,multiple,email,file,' +
  'muted,name,novalidate,open,optimum,pattern,ping,placeholder,poster,' +
  'preload,radiogroup,readonly,rel,required,reversed,rows,rowspan,sandbox,' +
  'scope,scoped,seamless,selected,shape,size,type,text,password,sizes,span,' +
  'spellcheck,src,srcdoc,srclang,srcset,start,step,style,summary,tabindex,' +
  'target,title,type,usemap,value,width,wrap'
);



var xlinkNS = 'http://www.w3.org/1999/xlink';

var isXlink = function (name) {
  return name.charAt(5) === ':' && name.slice(0, 5) === 'xlink'
};

var getXlinkProp = function (name) {
  return isXlink(name) ? name.slice(6, name.length) : ''
};

var isFalsyAttrValue = function (val) {
  return val == null || val === false
};

/*  */

function genClassForVnode (vnode) {
  var data = vnode.data;
  var parentNode = vnode;
  var childNode = vnode;
  while (childNode.child) {
    childNode = childNode.child._vnode;
    if (childNode.data) {
      data = mergeClassData(childNode.data, data);
    }
  }
  while ((parentNode = parentNode.parent)) {
    if (parentNode.data) {
      data = mergeClassData(data, parentNode.data);
    }
  }
  return genClassFromData(data)
}

function mergeClassData (child, parent) {
  return {
    staticClass: concat(child.staticClass, parent.staticClass),
    class: child.class
      ? [child.class, parent.class]
      : parent.class
  }
}

function genClassFromData (data) {
  var dynamicClass = data.class;
  var staticClass = data.staticClass;
  if (staticClass || dynamicClass) {
    return concat(staticClass, stringifyClass(dynamicClass))
  }
  /* istanbul ignore next */
  return ''
}

function concat (a, b) {
  return a ? b ? (a + ' ' + b) : a : (b || '')
}

function stringifyClass (value) {
  var res = '';
  if (!value) {
    return res
  }
  if (typeof value === 'string') {
    return value
  }
  if (Array.isArray(value)) {
    var stringified;
    for (var i = 0, l = value.length; i < l; i++) {
      if (value[i]) {
        if ((stringified = stringifyClass(value[i]))) {
          res += stringified + ' ';
        }
      }
    }
    return res.slice(0, -1)
  }
  if (isObject(value)) {
    for (var key in value) {
      if (value[key]) { res += key + ' '; }
    }
    return res.slice(0, -1)
  }
  /* istanbul ignore next */
  return res
}

/*  */

var namespaceMap = {
  svg: 'http://www.w3.org/2000/svg',
  math: 'http://www.w3.org/1998/Math/MathML',
  xhtml: 'http://www.w3.org/1999/xhtml'
};

var isHTMLTag = makeMap(
  'html,body,base,head,link,meta,style,title,' +
  'address,article,aside,footer,header,h1,h2,h3,h4,h5,h6,hgroup,nav,section,' +
  'div,dd,dl,dt,figcaption,figure,hr,img,li,main,ol,p,pre,ul,' +
  'a,b,abbr,bdi,bdo,br,cite,code,data,dfn,em,i,kbd,mark,q,rp,rt,rtc,ruby,' +
  's,samp,small,span,strong,sub,sup,time,u,var,wbr,area,audio,map,track,video,' +
  'embed,object,param,source,canvas,script,noscript,del,ins,' +
  'caption,col,colgroup,table,thead,tbody,td,th,tr,' +
  'button,datalist,fieldset,form,input,label,legend,meter,optgroup,option,' +
  'output,progress,select,textarea,' +
  'details,dialog,menu,menuitem,summary,' +
  'content,element,shadow,template'
);

var isUnaryTag = makeMap(
  'area,base,br,col,embed,frame,hr,img,input,isindex,keygen,' +
  'link,meta,param,source,track,wbr',
  true
);

// Elements that you can, intentionally, leave open
// (and which close themselves)
var canBeLeftOpenTag = makeMap(
  'colgroup,dd,dt,li,options,p,td,tfoot,th,thead,tr,source',
  true
);

// HTML5 tags https://html.spec.whatwg.org/multipage/indices.html#elements-3
// Phrasing Content https://html.spec.whatwg.org/multipage/dom.html#phrasing-content
var isNonPhrasingTag = makeMap(
  'address,article,aside,base,blockquote,body,caption,col,colgroup,dd,' +
  'details,dialog,div,dl,dt,fieldset,figcaption,figure,footer,form,' +
  'h1,h2,h3,h4,h5,h6,head,header,hgroup,hr,html,legend,li,menuitem,meta,' +
  'optgroup,option,param,rp,rt,source,style,summary,tbody,td,tfoot,th,thead,' +
  'title,tr,track',
  true
);

// this map is intentionally selective, only covering SVG elements that may
// contain child elements.
var isSVG = makeMap(
  'svg,animate,circle,clippath,cursor,defs,desc,ellipse,filter,font,' +
  'font-face,g,glyph,image,line,marker,mask,missing-glyph,path,pattern,' +
  'polygon,polyline,rect,switch,symbol,text,textpath,tspan,use,view',
  true
);

var isPreTag = function (tag) { return tag === 'pre'; };

var isReservedTag = function (tag) {
  return isHTMLTag(tag) || isSVG(tag)
};

function getTagNamespace (tag) {
  if (isSVG(tag)) {
    return 'svg'
  }
  // basic support for MathML
  // note it doesn't support other MathML elements being component roots
  if (tag === 'math') {
    return 'math'
  }
}

var unknownElementCache = Object.create(null);
function isUnknownElement (tag) {
  /* istanbul ignore if */
  if (!inBrowser) {
    return true
  }
  if (isReservedTag(tag)) {
    return false
  }
  tag = tag.toLowerCase();
  /* istanbul ignore if */
  if (unknownElementCache[tag] != null) {
    return unknownElementCache[tag]
  }
  var el = document.createElement(tag);
  if (tag.indexOf('-') > -1) {
    // http://stackoverflow.com/a/28210364/1070244
    return (unknownElementCache[tag] = (
      el.constructor === window.HTMLUnknownElement ||
      el.constructor === window.HTMLElement
    ))
  } else {
    return (unknownElementCache[tag] = /HTMLUnknownElement/.test(el.toString()))
  }
}

/*  */

/**
 * Query an element selector if it's not an element already.
 */
function query (el) {
  if (typeof el === 'string') {
    var selector = el;
    el = document.querySelector(el);
    if (!el) {
      "development" !== 'production' && warn(
        'Cannot find element: ' + selector
      );
      return document.createElement('div')
    }
  }
  return el
}

/*  */

function createElement$1 (tagName, vnode) {
  var elm = document.createElement(tagName);
  if (tagName !== 'select') {
    return elm
  }
  if (vnode.data && vnode.data.attrs && 'multiple' in vnode.data.attrs) {
    elm.setAttribute('multiple', 'multiple');
  }
  return elm
}

function createElementNS (namespace, tagName) {
  return document.createElementNS(namespaceMap[namespace], tagName)
}

function createTextNode (text) {
  return document.createTextNode(text)
}

function createComment (text) {
  return document.createComment(text)
}

function insertBefore (parentNode, newNode, referenceNode) {
  parentNode.insertBefore(newNode, referenceNode);
}

function removeChild (node, child) {
  node.removeChild(child);
}

function appendChild (node, child) {
  node.appendChild(child);
}

function parentNode (node) {
  return node.parentNode
}

function nextSibling (node) {
  return node.nextSibling
}

function tagName (node) {
  return node.tagName
}

function setTextContent (node, text) {
  node.textContent = text;
}

function childNodes (node) {
  return node.childNodes
}

function setAttribute (node, key, val) {
  node.setAttribute(key, val);
}


var nodeOps = Object.freeze({
	createElement: createElement$1,
	createElementNS: createElementNS,
	createTextNode: createTextNode,
	createComment: createComment,
	insertBefore: insertBefore,
	removeChild: removeChild,
	appendChild: appendChild,
	parentNode: parentNode,
	nextSibling: nextSibling,
	tagName: tagName,
	setTextContent: setTextContent,
	childNodes: childNodes,
	setAttribute: setAttribute
});

/*  */

var ref = {
  create: function create (_, vnode) {
    registerRef(vnode);
  },
  update: function update (oldVnode, vnode) {
    if (oldVnode.data.ref !== vnode.data.ref) {
      registerRef(oldVnode, true);
      registerRef(vnode);
    }
  },
  destroy: function destroy (vnode) {
    registerRef(vnode, true);
  }
};

function registerRef (vnode, isRemoval) {
  var key = vnode.data.ref;
  if (!key) { return }

  var vm = vnode.context;
  var ref = vnode.child || vnode.elm;
  var refs = vm.$refs;
  if (isRemoval) {
    if (Array.isArray(refs[key])) {
      remove$1(refs[key], ref);
    } else if (refs[key] === ref) {
      refs[key] = undefined;
    }
  } else {
    if (vnode.data.refInFor) {
      if (Array.isArray(refs[key])) {
        refs[key].push(ref);
      } else {
        refs[key] = [ref];
      }
    } else {
      refs[key] = ref;
    }
  }
}

/**
 * Virtual DOM patching algorithm based on Snabbdom by
 * Simon Friis Vindum (@paldepind)
 * Licensed under the MIT License
 * https://github.com/paldepind/snabbdom/blob/master/LICENSE
 *
 * modified by Evan You (@yyx990803)
 *

/*
 * Not type-checking this because this file is perf-critical and the cost
 * of making flow understand it is not worth it.
 */

var emptyNode = new VNode('', {}, []);

var hooks$1 = ['create', 'update', 'remove', 'destroy'];

function isUndef (s) {
  return s == null
}

function isDef (s) {
  return s != null
}

function sameVnode (vnode1, vnode2) {
  return (
    vnode1.key === vnode2.key &&
    vnode1.tag === vnode2.tag &&
    vnode1.isComment === vnode2.isComment &&
    !vnode1.data === !vnode2.data
  )
}

function createKeyToOldIdx (children, beginIdx, endIdx) {
  var i, key;
  var map = {};
  for (i = beginIdx; i <= endIdx; ++i) {
    key = children[i].key;
    if (isDef(key)) { map[key] = i; }
  }
  return map
}

function createPatchFunction (backend) {
  var i, j;
  var cbs = {};

  var modules = backend.modules;
  var nodeOps = backend.nodeOps;

  for (i = 0; i < hooks$1.length; ++i) {
    cbs[hooks$1[i]] = [];
    for (j = 0; j < modules.length; ++j) {
      if (modules[j][hooks$1[i]] !== undefined) { cbs[hooks$1[i]].push(modules[j][hooks$1[i]]); }
    }
  }

  function emptyNodeAt (elm) {
    return new VNode(nodeOps.tagName(elm).toLowerCase(), {}, [], undefined, elm)
  }

  function createRmCb (childElm, listeners) {
    function remove$$1 () {
      if (--remove$$1.listeners === 0) {
        removeElement(childElm);
      }
    }
    remove$$1.listeners = listeners;
    return remove$$1
  }

  function removeElement (el) {
    var parent = nodeOps.parentNode(el);
    // element may have already been removed due to v-html
    if (parent) {
      nodeOps.removeChild(parent, el);
    }
  }

  function createElm (vnode, insertedVnodeQueue, nested) {
    var i;
    var data = vnode.data;
    vnode.isRootInsert = !nested;
    if (isDef(data)) {
      if (isDef(i = data.hook) && isDef(i = i.init)) { i(vnode); }
      // after calling the init hook, if the vnode is a child component
      // it should've created a child instance and mounted it. the child
      // component also has set the placeholder vnode's elm.
      // in that case we can just return the element and be done.
      if (isDef(i = vnode.child)) {
        initComponent(vnode, insertedVnodeQueue);
        return vnode.elm
      }
    }
    var children = vnode.children;
    var tag = vnode.tag;
    if (isDef(tag)) {
      {
        if (
          !vnode.ns &&
          !(config.ignoredElements && config.ignoredElements.indexOf(tag) > -1) &&
          config.isUnknownElement(tag)
        ) {
          warn(
            'Unknown custom element: <' + tag + '> - did you ' +
            'register the component correctly? For recursive components, ' +
            'make sure to provide the "name" option.',
            vnode.context
          );
        }
      }
      vnode.elm = vnode.ns
        ? nodeOps.createElementNS(vnode.ns, tag)
        : nodeOps.createElement(tag, vnode);
      setScope(vnode);
      createChildren(vnode, children, insertedVnodeQueue);
      if (isDef(data)) {
        invokeCreateHooks(vnode, insertedVnodeQueue);
      }
    } else if (vnode.isComment) {
      vnode.elm = nodeOps.createComment(vnode.text);
    } else {
      vnode.elm = nodeOps.createTextNode(vnode.text);
    }
    return vnode.elm
  }

  function createChildren (vnode, children, insertedVnodeQueue) {
    if (Array.isArray(children)) {
      for (var i = 0; i < children.length; ++i) {
        nodeOps.appendChild(vnode.elm, createElm(children[i], insertedVnodeQueue, true));
      }
    } else if (isPrimitive(vnode.text)) {
      nodeOps.appendChild(vnode.elm, nodeOps.createTextNode(vnode.text));
    }
  }

  function isPatchable (vnode) {
    while (vnode.child) {
      vnode = vnode.child._vnode;
    }
    return isDef(vnode.tag)
  }

  function invokeCreateHooks (vnode, insertedVnodeQueue) {
    for (var i$1 = 0; i$1 < cbs.create.length; ++i$1) {
      cbs.create[i$1](emptyNode, vnode);
    }
    i = vnode.data.hook; // Reuse variable
    if (isDef(i)) {
      if (i.create) { i.create(emptyNode, vnode); }
      if (i.insert) { insertedVnodeQueue.push(vnode); }
    }
  }

  function initComponent (vnode, insertedVnodeQueue) {
    if (vnode.data.pendingInsert) {
      insertedVnodeQueue.push.apply(insertedVnodeQueue, vnode.data.pendingInsert);
    }
    vnode.elm = vnode.child.$el;
    if (isPatchable(vnode)) {
      invokeCreateHooks(vnode, insertedVnodeQueue);
      setScope(vnode);
    } else {
      // empty component root.
      // skip all element-related modules except for ref (#3455)
      registerRef(vnode);
      // make sure to invoke the insert hook
      insertedVnodeQueue.push(vnode);
    }
  }

  // set scope id attribute for scoped CSS.
  // this is implemented as a special case to avoid the overhead
  // of going through the normal attribute patching process.
  function setScope (vnode) {
    var i;
    if (isDef(i = vnode.context) && isDef(i = i.$options._scopeId)) {
      nodeOps.setAttribute(vnode.elm, i, '');
    }
    if (isDef(i = activeInstance) &&
        i !== vnode.context &&
        isDef(i = i.$options._scopeId)) {
      nodeOps.setAttribute(vnode.elm, i, '');
    }
  }

  function addVnodes (parentElm, before, vnodes, startIdx, endIdx, insertedVnodeQueue) {
    for (; startIdx <= endIdx; ++startIdx) {
      nodeOps.insertBefore(parentElm, createElm(vnodes[startIdx], insertedVnodeQueue), before);
    }
  }

  function invokeDestroyHook (vnode) {
    var i, j;
    var data = vnode.data;
    if (isDef(data)) {
      if (isDef(i = data.hook) && isDef(i = i.destroy)) { i(vnode); }
      for (i = 0; i < cbs.destroy.length; ++i) { cbs.destroy[i](vnode); }
    }
    if (isDef(i = vnode.children)) {
      for (j = 0; j < vnode.children.length; ++j) {
        invokeDestroyHook(vnode.children[j]);
      }
    }
  }

  function removeVnodes (parentElm, vnodes, startIdx, endIdx) {
    for (; startIdx <= endIdx; ++startIdx) {
      var ch = vnodes[startIdx];
      if (isDef(ch)) {
        if (isDef(ch.tag)) {
          removeAndInvokeRemoveHook(ch);
          invokeDestroyHook(ch);
        } else { // Text node
          nodeOps.removeChild(parentElm, ch.elm);
        }
      }
    }
  }

  function removeAndInvokeRemoveHook (vnode, rm) {
    if (rm || isDef(vnode.data)) {
      var listeners = cbs.remove.length + 1;
      if (!rm) {
        // directly removing
        rm = createRmCb(vnode.elm, listeners);
      } else {
        // we have a recursively passed down rm callback
        // increase the listeners count
        rm.listeners += listeners;
      }
      // recursively invoke hooks on child component root node
      if (isDef(i = vnode.child) && isDef(i = i._vnode) && isDef(i.data)) {
        removeAndInvokeRemoveHook(i, rm);
      }
      for (i = 0; i < cbs.remove.length; ++i) {
        cbs.remove[i](vnode, rm);
      }
      if (isDef(i = vnode.data.hook) && isDef(i = i.remove)) {
        i(vnode, rm);
      } else {
        rm();
      }
    } else {
      removeElement(vnode.elm);
    }
  }

  function updateChildren (parentElm, oldCh, newCh, insertedVnodeQueue, removeOnly) {
    var oldStartIdx = 0;
    var newStartIdx = 0;
    var oldEndIdx = oldCh.length - 1;
    var oldStartVnode = oldCh[0];
    var oldEndVnode = oldCh[oldEndIdx];
    var newEndIdx = newCh.length - 1;
    var newStartVnode = newCh[0];
    var newEndVnode = newCh[newEndIdx];
    var oldKeyToIdx, idxInOld, elmToMove, before;

    // removeOnly is a special flag used only by <transition-group>
    // to ensure removed elements stay in correct relative positions
    // during leaving transitions
    var canMove = !removeOnly;

    while (oldStartIdx <= oldEndIdx && newStartIdx <= newEndIdx) {
      if (isUndef(oldStartVnode)) {
        oldStartVnode = oldCh[++oldStartIdx]; // Vnode has been moved left
      } else if (isUndef(oldEndVnode)) {
        oldEndVnode = oldCh[--oldEndIdx];
      } else if (sameVnode(oldStartVnode, newStartVnode)) {
        patchVnode(oldStartVnode, newStartVnode, insertedVnodeQueue);
        oldStartVnode = oldCh[++oldStartIdx];
        newStartVnode = newCh[++newStartIdx];
      } else if (sameVnode(oldEndVnode, newEndVnode)) {
        patchVnode(oldEndVnode, newEndVnode, insertedVnodeQueue);
        oldEndVnode = oldCh[--oldEndIdx];
        newEndVnode = newCh[--newEndIdx];
      } else if (sameVnode(oldStartVnode, newEndVnode)) { // Vnode moved right
        patchVnode(oldStartVnode, newEndVnode, insertedVnodeQueue);
        canMove && nodeOps.insertBefore(parentElm, oldStartVnode.elm, nodeOps.nextSibling(oldEndVnode.elm));
        oldStartVnode = oldCh[++oldStartIdx];
        newEndVnode = newCh[--newEndIdx];
      } else if (sameVnode(oldEndVnode, newStartVnode)) { // Vnode moved left
        patchVnode(oldEndVnode, newStartVnode, insertedVnodeQueue);
        canMove && nodeOps.insertBefore(parentElm, oldEndVnode.elm, oldStartVnode.elm);
        oldEndVnode = oldCh[--oldEndIdx];
        newStartVnode = newCh[++newStartIdx];
      } else {
        if (isUndef(oldKeyToIdx)) { oldKeyToIdx = createKeyToOldIdx(oldCh, oldStartIdx, oldEndIdx); }
        idxInOld = isDef(newStartVnode.key) ? oldKeyToIdx[newStartVnode.key] : null;
        if (isUndef(idxInOld)) { // New element
          nodeOps.insertBefore(parentElm, createElm(newStartVnode, insertedVnodeQueue), oldStartVnode.elm);
          newStartVnode = newCh[++newStartIdx];
        } else {
          elmToMove = oldCh[idxInOld];
          /* istanbul ignore if */
          if ("development" !== 'production' && !elmToMove) {
            warn(
              'It seems there are duplicate keys that is causing an update error. ' +
              'Make sure each v-for item has a unique key.'
            );
          }
          if (elmToMove.tag !== newStartVnode.tag) {
            // same key but different element. treat as new element
            nodeOps.insertBefore(parentElm, createElm(newStartVnode, insertedVnodeQueue), oldStartVnode.elm);
            newStartVnode = newCh[++newStartIdx];
          } else {
            patchVnode(elmToMove, newStartVnode, insertedVnodeQueue);
            oldCh[idxInOld] = undefined;
            canMove && nodeOps.insertBefore(parentElm, newStartVnode.elm, oldStartVnode.elm);
            newStartVnode = newCh[++newStartIdx];
          }
        }
      }
    }
    if (oldStartIdx > oldEndIdx) {
      before = isUndef(newCh[newEndIdx + 1]) ? null : newCh[newEndIdx + 1].elm;
      addVnodes(parentElm, before, newCh, newStartIdx, newEndIdx, insertedVnodeQueue);
    } else if (newStartIdx > newEndIdx) {
      removeVnodes(parentElm, oldCh, oldStartIdx, oldEndIdx);
    }
  }

  function patchVnode (oldVnode, vnode, insertedVnodeQueue, removeOnly) {
    if (oldVnode === vnode) {
      return
    }
    // reuse element for static trees.
    // note we only do this if the vnode is cloned -
    // if the new node is not cloned it means the render functions have been
    // reset by the hot-reload-api and we need to do a proper re-render.
    if (vnode.isStatic &&
        oldVnode.isStatic &&
        vnode.key === oldVnode.key &&
        (vnode.isCloned || vnode.isOnce)) {
      vnode.elm = oldVnode.elm;
      return
    }
    var i;
    var data = vnode.data;
    var hasData = isDef(data);
    if (hasData && isDef(i = data.hook) && isDef(i = i.prepatch)) {
      i(oldVnode, vnode);
    }
    var elm = vnode.elm = oldVnode.elm;
    var oldCh = oldVnode.children;
    var ch = vnode.children;
    if (hasData && isPatchable(vnode)) {
      for (i = 0; i < cbs.update.length; ++i) { cbs.update[i](oldVnode, vnode); }
      if (isDef(i = data.hook) && isDef(i = i.update)) { i(oldVnode, vnode); }
    }
    if (isUndef(vnode.text)) {
      if (isDef(oldCh) && isDef(ch)) {
        if (oldCh !== ch) { updateChildren(elm, oldCh, ch, insertedVnodeQueue, removeOnly); }
      } else if (isDef(ch)) {
        if (isDef(oldVnode.text)) { nodeOps.setTextContent(elm, ''); }
        addVnodes(elm, null, ch, 0, ch.length - 1, insertedVnodeQueue);
      } else if (isDef(oldCh)) {
        removeVnodes(elm, oldCh, 0, oldCh.length - 1);
      } else if (isDef(oldVnode.text)) {
        nodeOps.setTextContent(elm, '');
      }
    } else if (oldVnode.text !== vnode.text) {
      nodeOps.setTextContent(elm, vnode.text);
    }
    if (hasData) {
      if (isDef(i = data.hook) && isDef(i = i.postpatch)) { i(oldVnode, vnode); }
    }
  }

  function invokeInsertHook (vnode, queue, initial) {
    // delay insert hooks for component root nodes, invoke them after the
    // element is really inserted
    if (initial && vnode.parent) {
      vnode.parent.data.pendingInsert = queue;
    } else {
      for (var i = 0; i < queue.length; ++i) {
        queue[i].data.hook.insert(queue[i]);
      }
    }
  }

  var bailed = false;
  function hydrate (elm, vnode, insertedVnodeQueue) {
    {
      if (!assertNodeMatch(elm, vnode)) {
        return false
      }
    }
    vnode.elm = elm;
    var tag = vnode.tag;
    var data = vnode.data;
    var children = vnode.children;
    if (isDef(data)) {
      if (isDef(i = data.hook) && isDef(i = i.init)) { i(vnode, true /* hydrating */); }
      if (isDef(i = vnode.child)) {
        // child component. it should have hydrated its own tree.
        initComponent(vnode, insertedVnodeQueue);
        return true
      }
    }
    if (isDef(tag)) {
      if (isDef(children)) {
        var childNodes = nodeOps.childNodes(elm);
        // empty element, allow client to pick up and populate children
        if (!childNodes.length) {
          createChildren(vnode, children, insertedVnodeQueue);
        } else {
          var childrenMatch = true;
          if (childNodes.length !== children.length) {
            childrenMatch = false;
          } else {
            for (var i$1 = 0; i$1 < children.length; i$1++) {
              if (!hydrate(childNodes[i$1], children[i$1], insertedVnodeQueue)) {
                childrenMatch = false;
                break
              }
            }
          }
          if (!childrenMatch) {
            if ("development" !== 'production' &&
                typeof console !== 'undefined' &&
                !bailed) {
              bailed = true;
              console.warn('Parent: ', elm);
              console.warn('Mismatching childNodes vs. VNodes: ', childNodes, children);
            }
            return false
          }
        }
      }
      if (isDef(data)) {
        invokeCreateHooks(vnode, insertedVnodeQueue);
      }
    }
    return true
  }

  function assertNodeMatch (node, vnode) {
    if (vnode.tag) {
      return (
        vnode.tag.indexOf('vue-component') === 0 ||
        vnode.tag.toLowerCase() === nodeOps.tagName(node).toLowerCase()
      )
    } else {
      return _toString(vnode.text) === node.data
    }
  }

  return function patch (oldVnode, vnode, hydrating, removeOnly) {
    if (!vnode) {
      if (oldVnode) { invokeDestroyHook(oldVnode); }
      return
    }

    var elm, parent;
    var isInitialPatch = false;
    var insertedVnodeQueue = [];

    if (!oldVnode) {
      // empty mount, create new root element
      isInitialPatch = true;
      createElm(vnode, insertedVnodeQueue);
    } else {
      var isRealElement = isDef(oldVnode.nodeType);
      if (!isRealElement && sameVnode(oldVnode, vnode)) {
        patchVnode(oldVnode, vnode, insertedVnodeQueue, removeOnly);
      } else {
        if (isRealElement) {
          // mounting to a real element
          // check if this is server-rendered content and if we can perform
          // a successful hydration.
          if (oldVnode.nodeType === 1 && oldVnode.hasAttribute('server-rendered')) {
            oldVnode.removeAttribute('server-rendered');
            hydrating = true;
          }
          if (hydrating) {
            if (hydrate(oldVnode, vnode, insertedVnodeQueue)) {
              invokeInsertHook(vnode, insertedVnodeQueue, true);
              return oldVnode
            } else {
              warn(
                'The client-side rendered virtual DOM tree is not matching ' +
                'server-rendered content. This is likely caused by incorrect ' +
                'HTML markup, for example nesting block-level elements inside ' +
                '<p>, or missing <tbody>. Bailing hydration and performing ' +
                'full client-side render.'
              );
            }
          }
          // either not server-rendered, or hydration failed.
          // create an empty node and replace it
          oldVnode = emptyNodeAt(oldVnode);
        }
        elm = oldVnode.elm;
        parent = nodeOps.parentNode(elm);

        createElm(vnode, insertedVnodeQueue);

        // component root element replaced.
        // update parent placeholder node element.
        if (vnode.parent) {
          vnode.parent.elm = vnode.elm;
          if (isPatchable(vnode)) {
            for (var i = 0; i < cbs.create.length; ++i) {
              cbs.create[i](emptyNode, vnode.parent);
            }
          }
        }

        if (parent !== null) {
          nodeOps.insertBefore(parent, vnode.elm, nodeOps.nextSibling(elm));
          removeVnodes(parent, [oldVnode], 0, 0);
        } else if (isDef(oldVnode.tag)) {
          invokeDestroyHook(oldVnode);
        }
      }
    }

    invokeInsertHook(vnode, insertedVnodeQueue, isInitialPatch);
    return vnode.elm
  }
}

/*  */

var directives = {
  create: updateDirectives,
  update: updateDirectives,
  destroy: function unbindDirectives (vnode) {
    updateDirectives(vnode, emptyNode);
  }
};

function updateDirectives (
  oldVnode,
  vnode
) {
  if (!oldVnode.data.directives && !vnode.data.directives) {
    return
  }
  var isCreate = oldVnode === emptyNode;
  var oldDirs = normalizeDirectives$1(oldVnode.data.directives, oldVnode.context);
  var newDirs = normalizeDirectives$1(vnode.data.directives, vnode.context);

  var dirsWithInsert = [];
  var dirsWithPostpatch = [];

  var key, oldDir, dir;
  for (key in newDirs) {
    oldDir = oldDirs[key];
    dir = newDirs[key];
    if (!oldDir) {
      // new directive, bind
      callHook$1(dir, 'bind', vnode, oldVnode);
      if (dir.def && dir.def.inserted) {
        dirsWithInsert.push(dir);
      }
    } else {
      // existing directive, update
      dir.oldValue = oldDir.value;
      callHook$1(dir, 'update', vnode, oldVnode);
      if (dir.def && dir.def.componentUpdated) {
        dirsWithPostpatch.push(dir);
      }
    }
  }

  if (dirsWithInsert.length) {
    var callInsert = function () {
      dirsWithInsert.forEach(function (dir) {
        callHook$1(dir, 'inserted', vnode, oldVnode);
      });
    };
    if (isCreate) {
      mergeVNodeHook(vnode.data.hook || (vnode.data.hook = {}), 'insert', callInsert, 'dir-insert');
    } else {
      callInsert();
    }
  }

  if (dirsWithPostpatch.length) {
    mergeVNodeHook(vnode.data.hook || (vnode.data.hook = {}), 'postpatch', function () {
      dirsWithPostpatch.forEach(function (dir) {
        callHook$1(dir, 'componentUpdated', vnode, oldVnode);
      });
    }, 'dir-postpatch');
  }

  if (!isCreate) {
    for (key in oldDirs) {
      if (!newDirs[key]) {
        // no longer present, unbind
        callHook$1(oldDirs[key], 'unbind', oldVnode);
      }
    }
  }
}

var emptyModifiers = Object.create(null);

function normalizeDirectives$1 (
  dirs,
  vm
) {
  var res = Object.create(null);
  if (!dirs) {
    return res
  }
  var i, dir;
  for (i = 0; i < dirs.length; i++) {
    dir = dirs[i];
    if (!dir.modifiers) {
      dir.modifiers = emptyModifiers;
    }
    res[getRawDirName(dir)] = dir;
    dir.def = resolveAsset(vm.$options, 'directives', dir.name, true);
  }
  return res
}

function getRawDirName (dir) {
  return dir.rawName || ((dir.name) + "." + (Object.keys(dir.modifiers || {}).join('.')))
}

function callHook$1 (dir, hook, vnode, oldVnode) {
  var fn = dir.def && dir.def[hook];
  if (fn) {
    fn(vnode.elm, dir, vnode, oldVnode);
  }
}

var baseModules = [
  ref,
  directives
];

/*  */

function updateAttrs (oldVnode, vnode) {
  if (!oldVnode.data.attrs && !vnode.data.attrs) {
    return
  }
  var key, cur, old;
  var elm = vnode.elm;
  var oldAttrs = oldVnode.data.attrs || {};
  var attrs = vnode.data.attrs || {};
  // clone observed objects, as the user probably wants to mutate it
  if (attrs.__ob__) {
    attrs = vnode.data.attrs = extend({}, attrs);
  }

  for (key in attrs) {
    cur = attrs[key];
    old = oldAttrs[key];
    if (old !== cur) {
      setAttr(elm, key, cur);
    }
  }
  for (key in oldAttrs) {
    if (attrs[key] == null) {
      if (isXlink(key)) {
        elm.removeAttributeNS(xlinkNS, getXlinkProp(key));
      } else if (!isEnumeratedAttr(key)) {
        elm.removeAttribute(key);
      }
    }
  }
}

function setAttr (el, key, value) {
  if (isBooleanAttr(key)) {
    // set attribute for blank value
    // e.g. <option disabled>Select one</option>
    if (isFalsyAttrValue(value)) {
      el.removeAttribute(key);
    } else {
      el.setAttribute(key, key);
    }
  } else if (isEnumeratedAttr(key)) {
    el.setAttribute(key, isFalsyAttrValue(value) || value === 'false' ? 'false' : 'true');
  } else if (isXlink(key)) {
    if (isFalsyAttrValue(value)) {
      el.removeAttributeNS(xlinkNS, getXlinkProp(key));
    } else {
      el.setAttributeNS(xlinkNS, key, value);
    }
  } else {
    if (isFalsyAttrValue(value)) {
      el.removeAttribute(key);
    } else {
      el.setAttribute(key, value);
    }
  }
}

var attrs = {
  create: updateAttrs,
  update: updateAttrs
};

/*  */

function updateClass (oldVnode, vnode) {
  var el = vnode.elm;
  var data = vnode.data;
  var oldData = oldVnode.data;
  if (!data.staticClass && !data.class &&
      (!oldData || (!oldData.staticClass && !oldData.class))) {
    return
  }

  var cls = genClassForVnode(vnode);

  // handle transition classes
  var transitionClass = el._transitionClasses;
  if (transitionClass) {
    cls = concat(cls, stringifyClass(transitionClass));
  }

  // set the class
  if (cls !== el._prevClass) {
    el.setAttribute('class', cls);
    el._prevClass = cls;
  }
}

var klass = {
  create: updateClass,
  update: updateClass
};

// skip type checking this file because we need to attach private properties
// to elements

function updateDOMListeners (oldVnode, vnode) {
  if (!oldVnode.data.on && !vnode.data.on) {
    return
  }
  var on = vnode.data.on || {};
  var oldOn = oldVnode.data.on || {};
  var add = vnode.elm._v_add || (vnode.elm._v_add = function (event, handler, capture) {
    vnode.elm.addEventListener(event, handler, capture);
  });
  var remove = vnode.elm._v_remove || (vnode.elm._v_remove = function (event, handler) {
    vnode.elm.removeEventListener(event, handler);
  });
  updateListeners(on, oldOn, add, remove, vnode.context);
}

var events = {
  create: updateDOMListeners,
  update: updateDOMListeners
};

/*  */

function updateDOMProps (oldVnode, vnode) {
  if (!oldVnode.data.domProps && !vnode.data.domProps) {
    return
  }
  var key, cur;
  var elm = vnode.elm;
  var oldProps = oldVnode.data.domProps || {};
  var props = vnode.data.domProps || {};
  // clone observed objects, as the user probably wants to mutate it
  if (props.__ob__) {
    props = vnode.data.domProps = extend({}, props);
  }

  for (key in oldProps) {
    if (props[key] == null) {
      elm[key] = '';
    }
  }
  for (key in props) {
    // ignore children if the node has textContent or innerHTML,
    // as these will throw away existing DOM nodes and cause removal errors
    // on subsequent patches (#3360)
    if ((key === 'textContent' || key === 'innerHTML') && vnode.children) {
      vnode.children.length = 0;
    }
    cur = props[key];
    if (key === 'value') {
      // store value as _value as well since
      // non-string values will be stringified
      elm._value = cur;
      // avoid resetting cursor position when value is the same
      var strCur = cur == null ? '' : String(cur);
      if (elm.value !== strCur && !elm.composing) {
        elm.value = strCur;
      }
    } else {
      elm[key] = cur;
    }
  }
}

var domProps = {
  create: updateDOMProps,
  update: updateDOMProps
};

/*  */

var parseStyleText = cached(function (cssText) {
  var res = {};
  var hasBackground = cssText.indexOf('background') >= 0;
  // maybe with background-image: url(http://xxx) or base64 img
  var listDelimiter = hasBackground ? /;(?![^(]*\))/g : ';';
  var propertyDelimiter = hasBackground ? /:(.+)/ : ':';
  cssText.split(listDelimiter).forEach(function (item) {
    if (item) {
      var tmp = item.split(propertyDelimiter);
      tmp.length > 1 && (res[tmp[0].trim()] = tmp[1].trim());
    }
  });
  return res
});

// merge static and dynamic style data on the same vnode
function normalizeStyleData (data) {
  var style = normalizeStyleBinding(data.style);
  // static style is pre-processed into an object during compilation
  // and is always a fresh object, so it's safe to merge into it
  return data.staticStyle
    ? extend(data.staticStyle, style)
    : style
}

// normalize possible array / string values into Object
function normalizeStyleBinding (bindingStyle) {
  if (Array.isArray(bindingStyle)) {
    return toObject(bindingStyle)
  }
  if (typeof bindingStyle === 'string') {
    return parseStyleText(bindingStyle)
  }
  return bindingStyle
}

/**
 * parent component style should be after child's
 * so that parent component's style could override it
 */
function getStyle (vnode, checkChild) {
  var res = {};
  var styleData;

  if (checkChild) {
    var childNode = vnode;
    while (childNode.child) {
      childNode = childNode.child._vnode;
      if (childNode.data && (styleData = normalizeStyleData(childNode.data))) {
        extend(res, styleData);
      }
    }
  }

  if ((styleData = normalizeStyleData(vnode.data))) {
    extend(res, styleData);
  }

  var parentNode = vnode;
  while ((parentNode = parentNode.parent)) {
    if (parentNode.data && (styleData = normalizeStyleData(parentNode.data))) {
      extend(res, styleData);
    }
  }
  return res
}

/*  */

var cssVarRE = /^--/;
var setProp = function (el, name, val) {
  /* istanbul ignore if */
  if (cssVarRE.test(name)) {
    el.style.setProperty(name, val);
  } else {
    el.style[normalize(name)] = val;
  }
};

var prefixes = ['Webkit', 'Moz', 'ms'];

var testEl;
var normalize = cached(function (prop) {
  testEl = testEl || document.createElement('div');
  prop = camelize(prop);
  if (prop !== 'filter' && (prop in testEl.style)) {
    return prop
  }
  var upper = prop.charAt(0).toUpperCase() + prop.slice(1);
  for (var i = 0; i < prefixes.length; i++) {
    var prefixed = prefixes[i] + upper;
    if (prefixed in testEl.style) {
      return prefixed
    }
  }
});

function updateStyle (oldVnode, vnode) {
  var data = vnode.data;
  var oldData = oldVnode.data;

  if (!data.staticStyle && !data.style &&
      !oldData.staticStyle && !oldData.style) {
    return
  }

  var cur, name;
  var el = vnode.elm;
  var oldStyle = oldVnode.data.style || {};
  var style = normalizeStyleBinding(vnode.data.style) || {};

  vnode.data.style = style.__ob__ ? extend({}, style) : style;

  var newStyle = getStyle(vnode, true);

  for (name in oldStyle) {
    if (newStyle[name] == null) {
      setProp(el, name, '');
    }
  }
  for (name in newStyle) {
    cur = newStyle[name];
    if (cur !== oldStyle[name]) {
      // ie9 setting to null has no effect, must use empty string
      setProp(el, name, cur == null ? '' : cur);
    }
  }
}

var style = {
  create: updateStyle,
  update: updateStyle
};

/*  */

/**
 * Add class with compatibility for SVG since classList is not supported on
 * SVG elements in IE
 */
function addClass (el, cls) {
  /* istanbul ignore if */
  if (!cls || !cls.trim()) {
    return
  }

  /* istanbul ignore else */
  if (el.classList) {
    if (cls.indexOf(' ') > -1) {
      cls.split(/\s+/).forEach(function (c) { return el.classList.add(c); });
    } else {
      el.classList.add(cls);
    }
  } else {
    var cur = ' ' + el.getAttribute('class') + ' ';
    if (cur.indexOf(' ' + cls + ' ') < 0) {
      el.setAttribute('class', (cur + cls).trim());
    }
  }
}

/**
 * Remove class with compatibility for SVG since classList is not supported on
 * SVG elements in IE
 */
function removeClass (el, cls) {
  /* istanbul ignore if */
  if (!cls || !cls.trim()) {
    return
  }

  /* istanbul ignore else */
  if (el.classList) {
    if (cls.indexOf(' ') > -1) {
      cls.split(/\s+/).forEach(function (c) { return el.classList.remove(c); });
    } else {
      el.classList.remove(cls);
    }
  } else {
    var cur = ' ' + el.getAttribute('class') + ' ';
    var tar = ' ' + cls + ' ';
    while (cur.indexOf(tar) >= 0) {
      cur = cur.replace(tar, ' ');
    }
    el.setAttribute('class', cur.trim());
  }
}

/*  */

var hasTransition = inBrowser && !isIE9;
var TRANSITION = 'transition';
var ANIMATION = 'animation';

// Transition property/event sniffing
var transitionProp = 'transition';
var transitionEndEvent = 'transitionend';
var animationProp = 'animation';
var animationEndEvent = 'animationend';
if (hasTransition) {
  /* istanbul ignore if */
  if (window.ontransitionend === undefined &&
    window.onwebkittransitionend !== undefined) {
    transitionProp = 'WebkitTransition';
    transitionEndEvent = 'webkitTransitionEnd';
  }
  if (window.onanimationend === undefined &&
    window.onwebkitanimationend !== undefined) {
    animationProp = 'WebkitAnimation';
    animationEndEvent = 'webkitAnimationEnd';
  }
}

var raf = (inBrowser && window.requestAnimationFrame) || setTimeout;
function nextFrame (fn) {
  raf(function () {
    raf(fn);
  });
}

function addTransitionClass (el, cls) {
  (el._transitionClasses || (el._transitionClasses = [])).push(cls);
  addClass(el, cls);
}

function removeTransitionClass (el, cls) {
  if (el._transitionClasses) {
    remove$1(el._transitionClasses, cls);
  }
  removeClass(el, cls);
}

function whenTransitionEnds (
  el,
  expectedType,
  cb
) {
  var ref = getTransitionInfo(el, expectedType);
  var type = ref.type;
  var timeout = ref.timeout;
  var propCount = ref.propCount;
  if (!type) { return cb() }
  var event = type === TRANSITION ? transitionEndEvent : animationEndEvent;
  var ended = 0;
  var end = function () {
    el.removeEventListener(event, onEnd);
    cb();
  };
  var onEnd = function (e) {
    if (e.target === el) {
      if (++ended >= propCount) {
        end();
      }
    }
  };
  setTimeout(function () {
    if (ended < propCount) {
      end();
    }
  }, timeout + 1);
  el.addEventListener(event, onEnd);
}

var transformRE = /\b(transform|all)(,|$)/;

function getTransitionInfo (el, expectedType) {
  var styles = window.getComputedStyle(el);
  var transitioneDelays = styles[transitionProp + 'Delay'].split(', ');
  var transitionDurations = styles[transitionProp + 'Duration'].split(', ');
  var transitionTimeout = getTimeout(transitioneDelays, transitionDurations);
  var animationDelays = styles[animationProp + 'Delay'].split(', ');
  var animationDurations = styles[animationProp + 'Duration'].split(', ');
  var animationTimeout = getTimeout(animationDelays, animationDurations);

  var type;
  var timeout = 0;
  var propCount = 0;
  /* istanbul ignore if */
  if (expectedType === TRANSITION) {
    if (transitionTimeout > 0) {
      type = TRANSITION;
      timeout = transitionTimeout;
      propCount = transitionDurations.length;
    }
  } else if (expectedType === ANIMATION) {
    if (animationTimeout > 0) {
      type = ANIMATION;
      timeout = animationTimeout;
      propCount = animationDurations.length;
    }
  } else {
    timeout = Math.max(transitionTimeout, animationTimeout);
    type = timeout > 0
      ? transitionTimeout > animationTimeout
        ? TRANSITION
        : ANIMATION
      : null;
    propCount = type
      ? type === TRANSITION
        ? transitionDurations.length
        : animationDurations.length
      : 0;
  }
  var hasTransform =
    type === TRANSITION &&
    transformRE.test(styles[transitionProp + 'Property']);
  return {
    type: type,
    timeout: timeout,
    propCount: propCount,
    hasTransform: hasTransform
  }
}

function getTimeout (delays, durations) {
  /* istanbul ignore next */
  while (delays.length < durations.length) {
    delays = delays.concat(delays);
  }

  return Math.max.apply(null, durations.map(function (d, i) {
    return toMs(d) + toMs(delays[i])
  }))
}

function toMs (s) {
  return Number(s.slice(0, -1)) * 1000
}

/*  */

function enter (vnode) {
  var el = vnode.elm;

  // call leave callback now
  if (el._leaveCb) {
    el._leaveCb.cancelled = true;
    el._leaveCb();
  }

  var data = resolveTransition(vnode.data.transition);
  if (!data) {
    return
  }

  /* istanbul ignore if */
  if (el._enterCb || el.nodeType !== 1) {
    return
  }

  var css = data.css;
  var type = data.type;
  var enterClass = data.enterClass;
  var enterActiveClass = data.enterActiveClass;
  var appearClass = data.appearClass;
  var appearActiveClass = data.appearActiveClass;
  var beforeEnter = data.beforeEnter;
  var enter = data.enter;
  var afterEnter = data.afterEnter;
  var enterCancelled = data.enterCancelled;
  var beforeAppear = data.beforeAppear;
  var appear = data.appear;
  var afterAppear = data.afterAppear;
  var appearCancelled = data.appearCancelled;

  // activeInstance will always be the <transition> component managing this
  // transition. One edge case to check is when the <transition> is placed
  // as the root node of a child component. In that case we need to check
  // <transition>'s parent for appear check.
  var transitionNode = activeInstance.$vnode;
  var context = transitionNode && transitionNode.parent
    ? transitionNode.parent.context
    : activeInstance;

  var isAppear = !context._isMounted || !vnode.isRootInsert;

  if (isAppear && !appear && appear !== '') {
    return
  }

  var startClass = isAppear ? appearClass : enterClass;
  var activeClass = isAppear ? appearActiveClass : enterActiveClass;
  var beforeEnterHook = isAppear ? (beforeAppear || beforeEnter) : beforeEnter;
  var enterHook = isAppear ? (typeof appear === 'function' ? appear : enter) : enter;
  var afterEnterHook = isAppear ? (afterAppear || afterEnter) : afterEnter;
  var enterCancelledHook = isAppear ? (appearCancelled || enterCancelled) : enterCancelled;

  var expectsCSS = css !== false && !isIE9;
  var userWantsControl =
    enterHook &&
    // enterHook may be a bound method which exposes
    // the length of original fn as _length
    (enterHook._length || enterHook.length) > 1;

  var cb = el._enterCb = once(function () {
    if (expectsCSS) {
      removeTransitionClass(el, activeClass);
    }
    if (cb.cancelled) {
      if (expectsCSS) {
        removeTransitionClass(el, startClass);
      }
      enterCancelledHook && enterCancelledHook(el);
    } else {
      afterEnterHook && afterEnterHook(el);
    }
    el._enterCb = null;
  });

  if (!vnode.data.show) {
    // remove pending leave element on enter by injecting an insert hook
    mergeVNodeHook(vnode.data.hook || (vnode.data.hook = {}), 'insert', function () {
      var parent = el.parentNode;
      var pendingNode = parent && parent._pending && parent._pending[vnode.key];
      if (pendingNode && pendingNode.tag === vnode.tag && pendingNode.elm._leaveCb) {
        pendingNode.elm._leaveCb();
      }
      enterHook && enterHook(el, cb);
    }, 'transition-insert');
  }

  // start enter transition
  beforeEnterHook && beforeEnterHook(el);
  if (expectsCSS) {
    addTransitionClass(el, startClass);
    addTransitionClass(el, activeClass);
    nextFrame(function () {
      removeTransitionClass(el, startClass);
      if (!cb.cancelled && !userWantsControl) {
        whenTransitionEnds(el, type, cb);
      }
    });
  }

  if (vnode.data.show) {
    enterHook && enterHook(el, cb);
  }

  if (!expectsCSS && !userWantsControl) {
    cb();
  }
}

function leave (vnode, rm) {
  var el = vnode.elm;

  // call enter callback now
  if (el._enterCb) {
    el._enterCb.cancelled = true;
    el._enterCb();
  }

  var data = resolveTransition(vnode.data.transition);
  if (!data) {
    return rm()
  }

  /* istanbul ignore if */
  if (el._leaveCb || el.nodeType !== 1) {
    return
  }

  var css = data.css;
  var type = data.type;
  var leaveClass = data.leaveClass;
  var leaveActiveClass = data.leaveActiveClass;
  var beforeLeave = data.beforeLeave;
  var leave = data.leave;
  var afterLeave = data.afterLeave;
  var leaveCancelled = data.leaveCancelled;
  var delayLeave = data.delayLeave;

  var expectsCSS = css !== false && !isIE9;
  var userWantsControl =
    leave &&
    // leave hook may be a bound method which exposes
    // the length of original fn as _length
    (leave._length || leave.length) > 1;

  var cb = el._leaveCb = once(function () {
    if (el.parentNode && el.parentNode._pending) {
      el.parentNode._pending[vnode.key] = null;
    }
    if (expectsCSS) {
      removeTransitionClass(el, leaveActiveClass);
    }
    if (cb.cancelled) {
      if (expectsCSS) {
        removeTransitionClass(el, leaveClass);
      }
      leaveCancelled && leaveCancelled(el);
    } else {
      rm();
      afterLeave && afterLeave(el);
    }
    el._leaveCb = null;
  });

  if (delayLeave) {
    delayLeave(performLeave);
  } else {
    performLeave();
  }

  function performLeave () {
    // the delayed leave may have already been cancelled
    if (cb.cancelled) {
      return
    }
    // record leaving element
    if (!vnode.data.show) {
      (el.parentNode._pending || (el.parentNode._pending = {}))[vnode.key] = vnode;
    }
    beforeLeave && beforeLeave(el);
    if (expectsCSS) {
      addTransitionClass(el, leaveClass);
      addTransitionClass(el, leaveActiveClass);
      nextFrame(function () {
        removeTransitionClass(el, leaveClass);
        if (!cb.cancelled && !userWantsControl) {
          whenTransitionEnds(el, type, cb);
        }
      });
    }
    leave && leave(el, cb);
    if (!expectsCSS && !userWantsControl) {
      cb();
    }
  }
}

function resolveTransition (def$$1) {
  if (!def$$1) {
    return
  }
  /* istanbul ignore else */
  if (typeof def$$1 === 'object') {
    var res = {};
    if (def$$1.css !== false) {
      extend(res, autoCssTransition(def$$1.name || 'v'));
    }
    extend(res, def$$1);
    return res
  } else if (typeof def$$1 === 'string') {
    return autoCssTransition(def$$1)
  }
}

var autoCssTransition = cached(function (name) {
  return {
    enterClass: (name + "-enter"),
    leaveClass: (name + "-leave"),
    appearClass: (name + "-enter"),
    enterActiveClass: (name + "-enter-active"),
    leaveActiveClass: (name + "-leave-active"),
    appearActiveClass: (name + "-enter-active")
  }
});

function once (fn) {
  var called = false;
  return function () {
    if (!called) {
      called = true;
      fn();
    }
  }
}

var transition = inBrowser ? {
  create: function create (_, vnode) {
    if (!vnode.data.show) {
      enter(vnode);
    }
  },
  remove: function remove (vnode, rm) {
    /* istanbul ignore else */
    if (!vnode.data.show) {
      leave(vnode, rm);
    } else {
      rm();
    }
  }
} : {};

var platformModules = [
  attrs,
  klass,
  events,
  domProps,
  style,
  transition
];

/*  */

// the directive module should be applied last, after all
// built-in modules have been applied.
var modules = platformModules.concat(baseModules);

var patch$1 = createPatchFunction({ nodeOps: nodeOps, modules: modules });

/**
 * Not type checking this file because flow doesn't like attaching
 * properties to Elements.
 */

var modelableTagRE = /^input|select|textarea|vue-component-[0-9]+(-[0-9a-zA-Z_-]*)?$/;

/* istanbul ignore if */
if (isIE9) {
  // http://www.matts411.com/post/internet-explorer-9-oninput/
  document.addEventListener('selectionchange', function () {
    var el = document.activeElement;
    if (el && el.vmodel) {
      trigger(el, 'input');
    }
  });
}

var model = {
  inserted: function inserted (el, binding, vnode) {
    {
      if (!modelableTagRE.test(vnode.tag)) {
        warn(
          "v-model is not supported on element type: <" + (vnode.tag) + ">. " +
          'If you are working with contenteditable, it\'s recommended to ' +
          'wrap a library dedicated for that purpose inside a custom component.',
          vnode.context
        );
      }
    }
    if (vnode.tag === 'select') {
      var cb = function () {
        setSelected(el, binding, vnode.context);
      };
      cb();
      /* istanbul ignore if */
      if (isIE || isEdge) {
        setTimeout(cb, 0);
      }
    } else if (
      (vnode.tag === 'textarea' || el.type === 'text') &&
      !binding.modifiers.lazy
    ) {
      if (!isAndroid) {
        el.addEventListener('compositionstart', onCompositionStart);
        el.addEventListener('compositionend', onCompositionEnd);
      }
      /* istanbul ignore if */
      if (isIE9) {
        el.vmodel = true;
      }
    }
  },
  componentUpdated: function componentUpdated (el, binding, vnode) {
    if (vnode.tag === 'select') {
      setSelected(el, binding, vnode.context);
      // in case the options rendered by v-for have changed,
      // it's possible that the value is out-of-sync with the rendered options.
      // detect such cases and filter out values that no longer has a matching
      // option in the DOM.
      var needReset = el.multiple
        ? binding.value.some(function (v) { return hasNoMatchingOption(v, el.options); })
        : binding.value !== binding.oldValue && hasNoMatchingOption(binding.value, el.options);
      if (needReset) {
        trigger(el, 'change');
      }
    }
  }
};

function setSelected (el, binding, vm) {
  var value = binding.value;
  var isMultiple = el.multiple;
  if (isMultiple && !Array.isArray(value)) {
    "development" !== 'production' && warn(
      "<select multiple v-model=\"" + (binding.expression) + "\"> " +
      "expects an Array value for its binding, but got " + (Object.prototype.toString.call(value).slice(8, -1)),
      vm
    );
    return
  }
  var selected, option;
  for (var i = 0, l = el.options.length; i < l; i++) {
    option = el.options[i];
    if (isMultiple) {
      selected = looseIndexOf(value, getValue(option)) > -1;
      if (option.selected !== selected) {
        option.selected = selected;
      }
    } else {
      if (looseEqual(getValue(option), value)) {
        if (el.selectedIndex !== i) {
          el.selectedIndex = i;
        }
        return
      }
    }
  }
  if (!isMultiple) {
    el.selectedIndex = -1;
  }
}

function hasNoMatchingOption (value, options) {
  for (var i = 0, l = options.length; i < l; i++) {
    if (looseEqual(getValue(options[i]), value)) {
      return false
    }
  }
  return true
}

function getValue (option) {
  return '_value' in option
    ? option._value
    : option.value
}

function onCompositionStart (e) {
  e.target.composing = true;
}

function onCompositionEnd (e) {
  e.target.composing = false;
  trigger(e.target, 'input');
}

function trigger (el, type) {
  var e = document.createEvent('HTMLEvents');
  e.initEvent(type, true, true);
  el.dispatchEvent(e);
}

/*  */

// recursively search for possible transition defined inside the component root
function locateNode (vnode) {
  return vnode.child && (!vnode.data || !vnode.data.transition)
    ? locateNode(vnode.child._vnode)
    : vnode
}

var show = {
  bind: function bind (el, ref, vnode) {
    var value = ref.value;

    vnode = locateNode(vnode);
    var transition = vnode.data && vnode.data.transition;
    if (value && transition && !isIE9) {
      enter(vnode);
    }
    var originalDisplay = el.style.display === 'none' ? '' : el.style.display;
    el.style.display = value ? originalDisplay : 'none';
    el.__vOriginalDisplay = originalDisplay;
  },
  update: function update (el, ref, vnode) {
    var value = ref.value;
    var oldValue = ref.oldValue;

    /* istanbul ignore if */
    if (value === oldValue) { return }
    vnode = locateNode(vnode);
    var transition = vnode.data && vnode.data.transition;
    if (transition && !isIE9) {
      if (value) {
        enter(vnode);
        el.style.display = el.__vOriginalDisplay;
      } else {
        leave(vnode, function () {
          el.style.display = 'none';
        });
      }
    } else {
      el.style.display = value ? el.__vOriginalDisplay : 'none';
    }
  }
};

var platformDirectives = {
  model: model,
  show: show
};

/*  */

// Provides transition support for a single element/component.
// supports transition mode (out-in / in-out)

var transitionProps = {
  name: String,
  appear: Boolean,
  css: Boolean,
  mode: String,
  type: String,
  enterClass: String,
  leaveClass: String,
  enterActiveClass: String,
  leaveActiveClass: String,
  appearClass: String,
  appearActiveClass: String
};

// in case the child is also an abstract component, e.g. <keep-alive>
// we want to recursively retrieve the real component to be rendered
function getRealChild (vnode) {
  var compOptions = vnode && vnode.componentOptions;
  if (compOptions && compOptions.Ctor.options.abstract) {
    return getRealChild(getFirstComponentChild(compOptions.children))
  } else {
    return vnode
  }
}

function extractTransitionData (comp) {
  var data = {};
  var options = comp.$options;
  // props
  for (var key in options.propsData) {
    data[key] = comp[key];
  }
  // events.
  // extract listeners and pass them directly to the transition methods
  var listeners = options._parentListeners;
  for (var key$1 in listeners) {
    data[camelize(key$1)] = listeners[key$1].fn;
  }
  return data
}

function placeholder (h, rawChild) {
  return /\d-keep-alive$/.test(rawChild.tag)
    ? h('keep-alive')
    : null
}

function hasParentTransition (vnode) {
  while ((vnode = vnode.parent)) {
    if (vnode.data.transition) {
      return true
    }
  }
}

var Transition = {
  name: 'transition',
  props: transitionProps,
  abstract: true,
  render: function render (h) {
    var this$1 = this;

    var children = this.$slots.default;
    if (!children) {
      return
    }

    // filter out text nodes (possible whitespaces)
    children = children.filter(function (c) { return c.tag; });
    /* istanbul ignore if */
    if (!children.length) {
      return
    }

    // warn multiple elements
    if ("development" !== 'production' && children.length > 1) {
      warn(
        '<transition> can only be used on a single element. Use ' +
        '<transition-group> for lists.',
        this.$parent
      );
    }

    var mode = this.mode;

    // warn invalid mode
    if ("development" !== 'production' &&
        mode && mode !== 'in-out' && mode !== 'out-in') {
      warn(
        'invalid <transition> mode: ' + mode,
        this.$parent
      );
    }

    var rawChild = children[0];

    // if this is a component root node and the component's
    // parent container node also has transition, skip.
    if (hasParentTransition(this.$vnode)) {
      return rawChild
    }

    // apply transition data to child
    // use getRealChild() to ignore abstract components e.g. keep-alive
    var child = getRealChild(rawChild);
    /* istanbul ignore if */
    if (!child) {
      return rawChild
    }

    if (this._leaving) {
      return placeholder(h, rawChild)
    }

    var key = child.key = child.key == null || child.isStatic
      ? ("__v" + (child.tag + this._uid) + "__")
      : child.key;
    var data = (child.data || (child.data = {})).transition = extractTransitionData(this);
    var oldRawChild = this._vnode;
    var oldChild = getRealChild(oldRawChild);

    // mark v-show
    // so that the transition module can hand over the control to the directive
    if (child.data.directives && child.data.directives.some(function (d) { return d.name === 'show'; })) {
      child.data.show = true;
    }

    if (oldChild && oldChild.data && oldChild.key !== key) {
      // replace old child transition data with fresh one
      // important for dynamic transitions!
      var oldData = oldChild.data.transition = extend({}, data);

      // handle transition mode
      if (mode === 'out-in') {
        // return placeholder node and queue update when leave finishes
        this._leaving = true;
        mergeVNodeHook(oldData, 'afterLeave', function () {
          this$1._leaving = false;
          this$1.$forceUpdate();
        }, key);
        return placeholder(h, rawChild)
      } else if (mode === 'in-out') {
        var delayedLeave;
        var performLeave = function () { delayedLeave(); };
        mergeVNodeHook(data, 'afterEnter', performLeave, key);
        mergeVNodeHook(data, 'enterCancelled', performLeave, key);
        mergeVNodeHook(oldData, 'delayLeave', function (leave) {
          delayedLeave = leave;
        }, key);
      }
    }

    return rawChild
  }
};

/*  */

// Provides transition support for list items.
// supports move transitions using the FLIP technique.

// Because the vdom's children update algorithm is "unstable" - i.e.
// it doesn't guarantee the relative positioning of removed elements,
// we force transition-group to update its children into two passes:
// in the first pass, we remove all nodes that need to be removed,
// triggering their leaving transition; in the second pass, we insert/move
// into the final disired state. This way in the second pass removed
// nodes will remain where they should be.

var props = extend({
  tag: String,
  moveClass: String
}, transitionProps);

delete props.mode;

var TransitionGroup = {
  props: props,

  render: function render (h) {
    var tag = this.tag || this.$vnode.data.tag || 'span';
    var map = Object.create(null);
    var prevChildren = this.prevChildren = this.children;
    var rawChildren = this.$slots.default || [];
    var children = this.children = [];
    var transitionData = extractTransitionData(this);

    for (var i = 0; i < rawChildren.length; i++) {
      var c = rawChildren[i];
      if (c.tag) {
        if (c.key != null && String(c.key).indexOf('__vlist') !== 0) {
          children.push(c);
          map[c.key] = c
          ;(c.data || (c.data = {})).transition = transitionData;
        } else {
          var opts = c.componentOptions;
          var name = opts
            ? (opts.Ctor.options.name || opts.tag)
            : c.tag;
          warn(("<transition-group> children must be keyed: <" + name + ">"));
        }
      }
    }

    if (prevChildren) {
      var kept = [];
      var removed = [];
      for (var i$1 = 0; i$1 < prevChildren.length; i$1++) {
        var c$1 = prevChildren[i$1];
        c$1.data.transition = transitionData;
        c$1.data.pos = c$1.elm.getBoundingClientRect();
        if (map[c$1.key]) {
          kept.push(c$1);
        } else {
          removed.push(c$1);
        }
      }
      this.kept = h(tag, null, kept);
      this.removed = removed;
    }

    return h(tag, null, children)
  },

  beforeUpdate: function beforeUpdate () {
    // force removing pass
    this.__patch__(
      this._vnode,
      this.kept,
      false, // hydrating
      true // removeOnly (!important, avoids unnecessary moves)
    );
    this._vnode = this.kept;
  },

  updated: function updated () {
    var children = this.prevChildren;
    var moveClass = this.moveClass || (this.name + '-move');
    if (!children.length || !this.hasMove(children[0].elm, moveClass)) {
      return
    }

    // we divide the work into three loops to avoid mixing DOM reads and writes
    // in each iteration - which helps prevent layout thrashing.
    children.forEach(callPendingCbs);
    children.forEach(recordPosition);
    children.forEach(applyTranslation);

    // force reflow to put everything in position
    var f = document.body.offsetHeight; // eslint-disable-line

    children.forEach(function (c) {
      if (c.data.moved) {
        var el = c.elm;
        var s = el.style;
        addTransitionClass(el, moveClass);
        s.transform = s.WebkitTransform = s.transitionDuration = '';
        el.addEventListener(transitionEndEvent, el._moveCb = function cb (e) {
          if (!e || /transform$/.test(e.propertyName)) {
            el.removeEventListener(transitionEndEvent, cb);
            el._moveCb = null;
            removeTransitionClass(el, moveClass);
          }
        });
      }
    });
  },

  methods: {
    hasMove: function hasMove (el, moveClass) {
      /* istanbul ignore if */
      if (!hasTransition) {
        return false
      }
      if (this._hasMove != null) {
        return this._hasMove
      }
      addTransitionClass(el, moveClass);
      var info = getTransitionInfo(el);
      removeTransitionClass(el, moveClass);
      return (this._hasMove = info.hasTransform)
    }
  }
};

function callPendingCbs (c) {
  /* istanbul ignore if */
  if (c.elm._moveCb) {
    c.elm._moveCb();
  }
  /* istanbul ignore if */
  if (c.elm._enterCb) {
    c.elm._enterCb();
  }
}

function recordPosition (c) {
  c.data.newPos = c.elm.getBoundingClientRect();
}

function applyTranslation (c) {
  var oldPos = c.data.pos;
  var newPos = c.data.newPos;
  var dx = oldPos.left - newPos.left;
  var dy = oldPos.top - newPos.top;
  if (dx || dy) {
    c.data.moved = true;
    var s = c.elm.style;
    s.transform = s.WebkitTransform = "translate(" + dx + "px," + dy + "px)";
    s.transitionDuration = '0s';
  }
}

var platformComponents = {
  Transition: Transition,
  TransitionGroup: TransitionGroup
};

/*  */

// install platform specific utils
Vue$3.config.isUnknownElement = isUnknownElement;
Vue$3.config.isReservedTag = isReservedTag;
Vue$3.config.getTagNamespace = getTagNamespace;
Vue$3.config.mustUseProp = mustUseProp;

// install platform runtime directives & components
extend(Vue$3.options.directives, platformDirectives);
extend(Vue$3.options.components, platformComponents);

// install platform patch function
Vue$3.prototype.__patch__ = config._isServer ? noop : patch$1;

// wrap mount
Vue$3.prototype.$mount = function (
  el,
  hydrating
) {
  el = el && !config._isServer ? query(el) : undefined;
  return this._mount(el, hydrating)
};

// devtools global hook
/* istanbul ignore next */
setTimeout(function () {
  if (config.devtools) {
    if (devtools) {
      devtools.emit('init', Vue$3);
    } else if (
      "development" !== 'production' &&
      inBrowser && /Chrome\/\d+/.test(window.navigator.userAgent)
    ) {
      console.log(
        'Download the Vue Devtools for a better development experience:\n' +
        'https://github.com/vuejs/vue-devtools'
      );
    }
  }
}, 0);

/*  */

// check whether current browser encodes a char inside attribute values
function shouldDecode (content, encoded) {
  var div = document.createElement('div');
  div.innerHTML = "<div a=\"" + content + "\">";
  return div.innerHTML.indexOf(encoded) > 0
}

// #3663
// IE encodes newlines inside attribute values while other browsers don't
var shouldDecodeNewlines = inBrowser ? shouldDecode('\n', '&#10;') : false;

/*  */

var decoder;

function decode (html) {
  decoder = decoder || document.createElement('div');
  decoder.innerHTML = html;
  return decoder.textContent
}

/**
 * Not type-checking this file because it's mostly vendor code.
 */

/*!
 * HTML Parser By John Resig (ejohn.org)
 * Modified by Juriy "kangax" Zaytsev
 * Original code by Erik Arvidsson, Mozilla Public License
 * http://erik.eae.net/simplehtmlparser/simplehtmlparser.js
 */

// Regular Expressions for parsing tags and attributes
var singleAttrIdentifier = /([^\s"'<>/=]+)/;
var singleAttrAssign = /(?:=)/;
var singleAttrValues = [
  // attr value double quotes
  /"([^"]*)"+/.source,
  // attr value, single quotes
  /'([^']*)'+/.source,
  // attr value, no quotes
  /([^\s"'=<>`]+)/.source
];
var attribute = new RegExp(
  '^\\s*' + singleAttrIdentifier.source +
  '(?:\\s*(' + singleAttrAssign.source + ')' +
  '\\s*(?:' + singleAttrValues.join('|') + '))?'
);

// could use https://www.w3.org/TR/1999/REC-xml-names-19990114/#NT-QName
// but for Vue templates we can enforce a simple charset
var ncname = '[a-zA-Z_][\\w\\-\\.]*';
var qnameCapture = '((?:' + ncname + '\\:)?' + ncname + ')';
var startTagOpen = new RegExp('^<' + qnameCapture);
var startTagClose = /^\s*(\/?)>/;
var endTag = new RegExp('^<\\/' + qnameCapture + '[^>]*>');
var doctype = /^<!DOCTYPE [^>]+>/i;
var comment = /^<!--/;
var conditionalComment = /^<!\[/;

var IS_REGEX_CAPTURING_BROKEN = false;
'x'.replace(/x(.)?/g, function (m, g) {
  IS_REGEX_CAPTURING_BROKEN = g === '';
});

// Special Elements (can contain anything)
var isScriptOrStyle = makeMap('script,style', true);
var hasLang = function (attr) { return attr.name === 'lang' && attr.value !== 'html'; };
var isSpecialTag = function (tag, isSFC, stack) {
  if (isScriptOrStyle(tag)) {
    return true
  }
  // top-level template that has a pre-processor
  if (
    isSFC &&
    tag === 'template' &&
    stack.length === 1 &&
    stack[0].attrs.some(hasLang)
  ) {
    return true
  }
  return false
};

var reCache = {};

var ltRE = /&lt;/g;
var gtRE = /&gt;/g;
var nlRE = /&#10;/g;
var ampRE = /&amp;/g;
var quoteRE = /&quot;/g;

function decodeAttr (value, shouldDecodeNewlines) {
  if (shouldDecodeNewlines) {
    value = value.replace(nlRE, '\n');
  }
  return value
    .replace(ltRE, '<')
    .replace(gtRE, '>')
    .replace(ampRE, '&')
    .replace(quoteRE, '"')
}

function parseHTML (html, options) {
  var stack = [];
  var expectHTML = options.expectHTML;
  var isUnaryTag$$1 = options.isUnaryTag || no;
  var index = 0;
  var last, lastTag;
  while (html) {
    last = html;
    // Make sure we're not in a script or style element
    if (!lastTag || !isSpecialTag(lastTag, options.sfc, stack)) {
      var textEnd = html.indexOf('<');
      if (textEnd === 0) {
        // Comment:
        if (comment.test(html)) {
          var commentEnd = html.indexOf('-->');

          if (commentEnd >= 0) {
            advance(commentEnd + 3);
            continue
          }
        }

        // http://en.wikipedia.org/wiki/Conditional_comment#Downlevel-revealed_conditional_comment
        if (conditionalComment.test(html)) {
          var conditionalEnd = html.indexOf(']>');

          if (conditionalEnd >= 0) {
            advance(conditionalEnd + 2);
            continue
          }
        }

        // Doctype:
        var doctypeMatch = html.match(doctype);
        if (doctypeMatch) {
          advance(doctypeMatch[0].length);
          continue
        }

        // End tag:
        var endTagMatch = html.match(endTag);
        if (endTagMatch) {
          var curIndex = index;
          advance(endTagMatch[0].length);
          parseEndTag(endTagMatch[0], endTagMatch[1], curIndex, index);
          continue
        }

        // Start tag:
        var startTagMatch = parseStartTag();
        if (startTagMatch) {
          handleStartTag(startTagMatch);
          continue
        }
      }

      var text = (void 0), rest$1 = (void 0), next = (void 0);
      if (textEnd > 0) {
        rest$1 = html.slice(textEnd);
        while (
          !endTag.test(rest$1) &&
          !startTagOpen.test(rest$1) &&
          !comment.test(rest$1) &&
          !conditionalComment.test(rest$1)
        ) {
          // < in plain text, be forgiving and treat it as text
          next = rest$1.indexOf('<', 1);
          if (next < 0) { break }
          textEnd += next;
          rest$1 = html.slice(textEnd);
        }
        text = html.substring(0, textEnd);
        advance(textEnd);
      }

      if (textEnd < 0) {
        text = html;
        html = '';
      }

      if (options.chars && text) {
        options.chars(text);
      }
    } else {
      var stackedTag = lastTag.toLowerCase();
      var reStackedTag = reCache[stackedTag] || (reCache[stackedTag] = new RegExp('([\\s\\S]*?)(</' + stackedTag + '[^>]*>)', 'i'));
      var endTagLength = 0;
      var rest = html.replace(reStackedTag, function (all, text, endTag) {
        endTagLength = endTag.length;
        if (stackedTag !== 'script' && stackedTag !== 'style' && stackedTag !== 'noscript') {
          text = text
            .replace(/<!--([\s\S]*?)-->/g, '$1')
            .replace(/<!\[CDATA\[([\s\S]*?)]]>/g, '$1');
        }
        if (options.chars) {
          options.chars(text);
        }
        return ''
      });
      index += html.length - rest.length;
      html = rest;
      parseEndTag('</' + stackedTag + '>', stackedTag, index - endTagLength, index);
    }

    if (html === last && options.chars) {
      options.chars(html);
      break
    }
  }

  // Clean up any remaining tags
  parseEndTag();

  function advance (n) {
    index += n;
    html = html.substring(n);
  }

  function parseStartTag () {
    var start = html.match(startTagOpen);
    if (start) {
      var match = {
        tagName: start[1],
        attrs: [],
        start: index
      };
      advance(start[0].length);
      var end, attr;
      while (!(end = html.match(startTagClose)) && (attr = html.match(attribute))) {
        advance(attr[0].length);
        match.attrs.push(attr);
      }
      if (end) {
        match.unarySlash = end[1];
        advance(end[0].length);
        match.end = index;
        return match
      }
    }
  }

  function handleStartTag (match) {
    var tagName = match.tagName;
    var unarySlash = match.unarySlash;

    if (expectHTML) {
      if (lastTag === 'p' && isNonPhrasingTag(tagName)) {
        parseEndTag('', lastTag);
      }
      if (canBeLeftOpenTag(tagName) && lastTag === tagName) {
        parseEndTag('', tagName);
      }
    }

    var unary = isUnaryTag$$1(tagName) || tagName === 'html' && lastTag === 'head' || !!unarySlash;

    var l = match.attrs.length;
    var attrs = new Array(l);
    for (var i = 0; i < l; i++) {
      var args = match.attrs[i];
      // hackish work around FF bug https://bugzilla.mozilla.org/show_bug.cgi?id=369778
      if (IS_REGEX_CAPTURING_BROKEN && args[0].indexOf('""') === -1) {
        if (args[3] === '') { delete args[3]; }
        if (args[4] === '') { delete args[4]; }
        if (args[5] === '') { delete args[5]; }
      }
      var value = args[3] || args[4] || args[5] || '';
      attrs[i] = {
        name: args[1],
        value: decodeAttr(
          value,
          options.shouldDecodeNewlines
        )
      };
    }

    if (!unary) {
      stack.push({ tag: tagName, attrs: attrs });
      lastTag = tagName;
      unarySlash = '';
    }

    if (options.start) {
      options.start(tagName, attrs, unary, match.start, match.end);
    }
  }

  function parseEndTag (tag, tagName, start, end) {
    var pos;
    if (start == null) { start = index; }
    if (end == null) { end = index; }

    // Find the closest opened tag of the same type
    if (tagName) {
      var needle = tagName.toLowerCase();
      for (pos = stack.length - 1; pos >= 0; pos--) {
        if (stack[pos].tag.toLowerCase() === needle) {
          break
        }
      }
    } else {
      // If no tag name is provided, clean shop
      pos = 0;
    }

    if (pos >= 0) {
      // Close all the open elements, up the stack
      for (var i = stack.length - 1; i >= pos; i--) {
        if (options.end) {
          options.end(stack[i].tag, start, end);
        }
      }

      // Remove the open elements from the stack
      stack.length = pos;
      lastTag = pos && stack[pos - 1].tag;
    } else if (tagName.toLowerCase() === 'br') {
      if (options.start) {
        options.start(tagName, [], true, start, end);
      }
    } else if (tagName.toLowerCase() === 'p') {
      if (options.start) {
        options.start(tagName, [], false, start, end);
      }
      if (options.end) {
        options.end(tagName, start, end);
      }
    }
  }
}

/*  */

function parseFilters (exp) {
  var inSingle = false;
  var inDouble = false;
  var curly = 0;
  var square = 0;
  var paren = 0;
  var lastFilterIndex = 0;
  var c, prev, i, expression, filters;

  for (i = 0; i < exp.length; i++) {
    prev = c;
    c = exp.charCodeAt(i);
    if (inSingle) {
      // check single quote
      if (c === 0x27 && prev !== 0x5C) { inSingle = !inSingle; }
    } else if (inDouble) {
      // check double quote
      if (c === 0x22 && prev !== 0x5C) { inDouble = !inDouble; }
    } else if (
      c === 0x7C && // pipe
      exp.charCodeAt(i + 1) !== 0x7C &&
      exp.charCodeAt(i - 1) !== 0x7C &&
      !curly && !square && !paren
    ) {
      if (expression === undefined) {
        // first filter, end of expression
        lastFilterIndex = i + 1;
        expression = exp.slice(0, i).trim();
      } else {
        pushFilter();
      }
    } else {
      switch (c) {
        case 0x22: inDouble = true; break // "
        case 0x27: inSingle = true; break // '
        case 0x28: paren++; break         // (
        case 0x29: paren--; break         // )
        case 0x5B: square++; break        // [
        case 0x5D: square--; break        // ]
        case 0x7B: curly++; break         // {
        case 0x7D: curly--; break         // }
      }
    }
  }

  if (expression === undefined) {
    expression = exp.slice(0, i).trim();
  } else if (lastFilterIndex !== 0) {
    pushFilter();
  }

  function pushFilter () {
    (filters || (filters = [])).push(exp.slice(lastFilterIndex, i).trim());
    lastFilterIndex = i + 1;
  }

  if (filters) {
    for (i = 0; i < filters.length; i++) {
      expression = wrapFilter(expression, filters[i]);
    }
  }

  return expression
}

function wrapFilter (exp, filter) {
  var i = filter.indexOf('(');
  if (i < 0) {
    // _f: resolveFilter
    return ("_f(\"" + filter + "\")(" + exp + ")")
  } else {
    var name = filter.slice(0, i);
    var args = filter.slice(i + 1);
    return ("_f(\"" + name + "\")(" + exp + "," + args)
  }
}

/*  */

var defaultTagRE = /\{\{((?:.|\n)+?)\}\}/g;
var regexEscapeRE = /[-.*+?^${}()|[\]/\\]/g;

var buildRegex = cached(function (delimiters) {
  var open = delimiters[0].replace(regexEscapeRE, '\\$&');
  var close = delimiters[1].replace(regexEscapeRE, '\\$&');
  return new RegExp(open + '((?:.|\\n)+?)' + close, 'g')
});

function parseText (
  text,
  delimiters
) {
  var tagRE = delimiters ? buildRegex(delimiters) : defaultTagRE;
  if (!tagRE.test(text)) {
    return
  }
  var tokens = [];
  var lastIndex = tagRE.lastIndex = 0;
  var match, index;
  while ((match = tagRE.exec(text))) {
    index = match.index;
    // push text token
    if (index > lastIndex) {
      tokens.push(JSON.stringify(text.slice(lastIndex, index)));
    }
    // tag token
    var exp = parseFilters(match[1].trim());
    tokens.push(("_s(" + exp + ")"));
    lastIndex = index + match[0].length;
  }
  if (lastIndex < text.length) {
    tokens.push(JSON.stringify(text.slice(lastIndex)));
  }
  return tokens.join('+')
}

/*  */

function baseWarn (msg) {
  console.error(("[Vue parser]: " + msg));
}

function pluckModuleFunction (
  modules,
  key
) {
  return modules
    ? modules.map(function (m) { return m[key]; }).filter(function (_) { return _; })
    : []
}

function addProp (el, name, value) {
  (el.props || (el.props = [])).push({ name: name, value: value });
}

function addAttr (el, name, value) {
  (el.attrs || (el.attrs = [])).push({ name: name, value: value });
}

function addDirective (
  el,
  name,
  rawName,
  value,
  arg,
  modifiers
) {
  (el.directives || (el.directives = [])).push({ name: name, rawName: rawName, value: value, arg: arg, modifiers: modifiers });
}

function addHandler (
  el,
  name,
  value,
  modifiers,
  important
) {
  // check capture modifier
  if (modifiers && modifiers.capture) {
    delete modifiers.capture;
    name = '!' + name; // mark the event as captured
  }
  var events;
  if (modifiers && modifiers.native) {
    delete modifiers.native;
    events = el.nativeEvents || (el.nativeEvents = {});
  } else {
    events = el.events || (el.events = {});
  }
  var newHandler = { value: value, modifiers: modifiers };
  var handlers = events[name];
  /* istanbul ignore if */
  if (Array.isArray(handlers)) {
    important ? handlers.unshift(newHandler) : handlers.push(newHandler);
  } else if (handlers) {
    events[name] = important ? [newHandler, handlers] : [handlers, newHandler];
  } else {
    events[name] = newHandler;
  }
}

function getBindingAttr (
  el,
  name,
  getStatic
) {
  var dynamicValue =
    getAndRemoveAttr(el, ':' + name) ||
    getAndRemoveAttr(el, 'v-bind:' + name);
  if (dynamicValue != null) {
    return dynamicValue
  } else if (getStatic !== false) {
    var staticValue = getAndRemoveAttr(el, name);
    if (staticValue != null) {
      return JSON.stringify(staticValue)
    }
  }
}

function getAndRemoveAttr (el, name) {
  var val;
  if ((val = el.attrsMap[name]) != null) {
    var list = el.attrsList;
    for (var i = 0, l = list.length; i < l; i++) {
      if (list[i].name === name) {
        list.splice(i, 1);
        break
      }
    }
  }
  return val
}

var len;
var str;
var chr;
var index$1;
var expressionPos;
var expressionEndPos;

/**
 * parse directive model to do the array update transform. a[idx] = val => $$a.splice($$idx, 1, val)
 *
 * for loop possible cases:
 *
 * - test
 * - test[idx]
 * - test[test1[idx]]
 * - test["a"][idx]
 * - xxx.test[a[a].test1[idx]]
 * - test.xxx.a["asa"][test1[idx]]
 *
 */

function parseModel (val) {
  str = val;
  len = str.length;
  index$1 = expressionPos = expressionEndPos = 0;

  if (val.indexOf('[') < 0 || val.lastIndexOf(']') < len - 1) {
    return {
      exp: val,
      idx: null
    }
  }

  while (!eof()) {
    chr = next();
    /* istanbul ignore if */
    if (isStringStart(chr)) {
      parseString(chr);
    } else if (chr === 0x5B) {
      parseBracket(chr);
    }
  }

  return {
    exp: val.substring(0, expressionPos),
    idx: val.substring(expressionPos + 1, expressionEndPos)
  }
}

function next () {
  return str.charCodeAt(++index$1)
}

function eof () {
  return index$1 >= len
}

function isStringStart (chr) {
  return chr === 0x22 || chr === 0x27
}

function parseBracket (chr) {
  var inBracket = 1;
  expressionPos = index$1;
  while (!eof()) {
    chr = next();
    if (isStringStart(chr)) {
      parseString(chr);
      continue
    }
    if (chr === 0x5B) { inBracket++; }
    if (chr === 0x5D) { inBracket--; }
    if (inBracket === 0) {
      expressionEndPos = index$1;
      break
    }
  }
}

function parseString (chr) {
  var stringQuote = chr;
  while (!eof()) {
    chr = next();
    if (chr === stringQuote) {
      break
    }
  }
}

/*  */

var dirRE = /^v-|^@|^:/;
var forAliasRE = /(.*?)\s+(?:in|of)\s+(.*)/;
var forIteratorRE = /\(([^,]*),([^,]*)(?:,([^,]*))?\)/;
var bindRE = /^:|^v-bind:/;
var onRE = /^@|^v-on:/;
var argRE = /:(.*)$/;
var modifierRE = /\.[^.]+/g;
var specialNewlineRE = /\u2028|\u2029/g;

var decodeHTMLCached = cached(decode);

// configurable state
var warn$1;
var platformGetTagNamespace;
var platformMustUseProp;
var platformIsPreTag;
var preTransforms;
var transforms;
var postTransforms;
var delimiters;

/**
 * Convert HTML string to AST.
 */
function parse (
  template,
  options
) {
  warn$1 = options.warn || baseWarn;
  platformGetTagNamespace = options.getTagNamespace || no;
  platformMustUseProp = options.mustUseProp || no;
  platformIsPreTag = options.isPreTag || no;
  preTransforms = pluckModuleFunction(options.modules, 'preTransformNode');
  transforms = pluckModuleFunction(options.modules, 'transformNode');
  postTransforms = pluckModuleFunction(options.modules, 'postTransformNode');
  delimiters = options.delimiters;
  var stack = [];
  var preserveWhitespace = options.preserveWhitespace !== false;
  var root;
  var currentParent;
  var inVPre = false;
  var inPre = false;
  var warned = false;
  parseHTML(template, {
    expectHTML: options.expectHTML,
    isUnaryTag: options.isUnaryTag,
    shouldDecodeNewlines: options.shouldDecodeNewlines,
    start: function start (tag, attrs, unary) {
      // check namespace.
      // inherit parent ns if there is one
      var ns = (currentParent && currentParent.ns) || platformGetTagNamespace(tag);

      // handle IE svg bug
      /* istanbul ignore if */
      if (options.isIE && ns === 'svg') {
        attrs = guardIESVGBug(attrs);
      }

      var element = {
        type: 1,
        tag: tag,
        attrsList: attrs,
        attrsMap: makeAttrsMap(attrs, options.isIE),
        parent: currentParent,
        children: []
      };
      if (ns) {
        element.ns = ns;
      }

      if ("client" !== 'server' && isForbiddenTag(element)) {
        element.forbidden = true;
        "development" !== 'production' && warn$1(
          'Templates should only be responsible for mapping the state to the ' +
          'UI. Avoid placing tags with side-effects in your templates, such as ' +
          "<" + tag + ">."
        );
      }

      // apply pre-transforms
      for (var i = 0; i < preTransforms.length; i++) {
        preTransforms[i](element, options);
      }

      if (!inVPre) {
        processPre(element);
        if (element.pre) {
          inVPre = true;
        }
      }
      if (platformIsPreTag(element.tag)) {
        inPre = true;
      }
      if (inVPre) {
        processRawAttrs(element);
      } else {
        processFor(element);
        processIf(element);
        processOnce(element);
        processKey(element);

        // determine whether this is a plain element after
        // removing structural attributes
        element.plain = !element.key && !attrs.length;

        processRef(element);
        processSlot(element);
        processComponent(element);
        for (var i$1 = 0; i$1 < transforms.length; i$1++) {
          transforms[i$1](element, options);
        }
        processAttrs(element);
      }

      function checkRootConstraints (el) {
        if ("development" !== 'production' && !warned) {
          if (el.tag === 'slot' || el.tag === 'template') {
            warned = true;
            warn$1(
              "Cannot use <" + (el.tag) + "> as component root element because it may " +
              'contain multiple nodes:\n' + template
            );
          }
          if (el.attrsMap.hasOwnProperty('v-for')) {
            warned = true;
            warn$1(
              'Cannot use v-for on stateful component root element because ' +
              'it renders multiple elements:\n' + template
            );
          }
        }
      }

      // tree management
      if (!root) {
        root = element;
        checkRootConstraints(root);
      } else if (!stack.length) {
        // allow 2 root elements with v-if and v-else
        if (root.if && element.else) {
          checkRootConstraints(element);
          root.elseBlock = element;
        } else if ("development" !== 'production' && !warned) {
          warned = true;
          warn$1(
            ("Component template should contain exactly one root element:\n\n" + template)
          );
        }
      }
      if (currentParent && !element.forbidden) {
        if (element.else) {
          processElse(element, currentParent);
        } else {
          currentParent.children.push(element);
          element.parent = currentParent;
        }
      }
      if (!unary) {
        currentParent = element;
        stack.push(element);
      }
      // apply post-transforms
      for (var i$2 = 0; i$2 < postTransforms.length; i$2++) {
        postTransforms[i$2](element, options);
      }
    },

    end: function end () {
      // remove trailing whitespace
      var element = stack[stack.length - 1];
      var lastNode = element.children[element.children.length - 1];
      if (lastNode && lastNode.type === 3 && lastNode.text === ' ') {
        element.children.pop();
      }
      // pop stack
      stack.length -= 1;
      currentParent = stack[stack.length - 1];
      // check pre state
      if (element.pre) {
        inVPre = false;
      }
      if (platformIsPreTag(element.tag)) {
        inPre = false;
      }
    },

    chars: function chars (text) {
      if (!currentParent) {
        if ("development" !== 'production' && !warned && text === template) {
          warned = true;
          warn$1(
            'Component template requires a root element, rather than just text:\n\n' + template
          );
        }
        return
      }
      // IE textarea placeholder bug
      /* istanbul ignore if */
      if (options.isIE &&
          currentParent.tag === 'textarea' &&
          currentParent.attrsMap.placeholder === text) {
        return
      }
      text = inPre || text.trim()
        ? decodeHTMLCached(text)
        // only preserve whitespace if its not right after a starting tag
        : preserveWhitespace && currentParent.children.length ? ' ' : '';
      if (text) {
        var expression;
        if (!inVPre && text !== ' ' && (expression = parseText(text, delimiters))) {
          currentParent.children.push({
            type: 2,
            expression: expression,
            text: text
          });
        } else {
          // #3895 special character
          text = text.replace(specialNewlineRE, '');
          currentParent.children.push({
            type: 3,
            text: text
          });
        }
      }
    }
  });
  return root
}

function processPre (el) {
  if (getAndRemoveAttr(el, 'v-pre') != null) {
    el.pre = true;
  }
}

function processRawAttrs (el) {
  var l = el.attrsList.length;
  if (l) {
    var attrs = el.attrs = new Array(l);
    for (var i = 0; i < l; i++) {
      attrs[i] = {
        name: el.attrsList[i].name,
        value: JSON.stringify(el.attrsList[i].value)
      };
    }
  } else if (!el.pre) {
    // non root node in pre blocks with no attributes
    el.plain = true;
  }
}

function processKey (el) {
  var exp = getBindingAttr(el, 'key');
  if (exp) {
    if ("development" !== 'production' && el.tag === 'template') {
      warn$1("<template> cannot be keyed. Place the key on real elements instead.");
    }
    el.key = exp;
  }
}

function processRef (el) {
  var ref = getBindingAttr(el, 'ref');
  if (ref) {
    el.ref = ref;
    el.refInFor = checkInFor(el);
  }
}

function processFor (el) {
  var exp;
  if ((exp = getAndRemoveAttr(el, 'v-for'))) {
    var inMatch = exp.match(forAliasRE);
    if (!inMatch) {
      "development" !== 'production' && warn$1(
        ("Invalid v-for expression: " + exp)
      );
      return
    }
    el.for = inMatch[2].trim();
    var alias = inMatch[1].trim();
    var iteratorMatch = alias.match(forIteratorRE);
    if (iteratorMatch) {
      el.alias = iteratorMatch[1].trim();
      el.iterator1 = iteratorMatch[2].trim();
      if (iteratorMatch[3]) {
        el.iterator2 = iteratorMatch[3].trim();
      }
    } else {
      el.alias = alias;
    }
  }
}

function processIf (el) {
  var exp = getAndRemoveAttr(el, 'v-if');
  if (exp) {
    el.if = exp;
  }
  if (getAndRemoveAttr(el, 'v-else') != null) {
    el.else = true;
  }
}

function processElse (el, parent) {
  var prev = findPrevElement(parent.children);
  if (prev && prev.if) {
    prev.elseBlock = el;
  } else {
    warn$1(
      ("v-else used on element <" + (el.tag) + "> without corresponding v-if.")
    );
  }
}

function processOnce (el) {
  var once = getAndRemoveAttr(el, 'v-once');
  if (once != null) {
    el.once = true;
  }
}

function processSlot (el) {
  if (el.tag === 'slot') {
    el.slotName = getBindingAttr(el, 'name');
  } else {
    var slotTarget = getBindingAttr(el, 'slot');
    if (slotTarget) {
      el.slotTarget = slotTarget;
    }
  }
}

function processComponent (el) {
  var binding;
  if ((binding = getBindingAttr(el, 'is'))) {
    el.component = binding;
  }
  if (getAndRemoveAttr(el, 'inline-template') != null) {
    el.inlineTemplate = true;
  }
}

function processAttrs (el) {
  var list = el.attrsList;
  var i, l, name, rawName, value, arg, modifiers, isProp;
  for (i = 0, l = list.length; i < l; i++) {
    name = rawName = list[i].name;
    value = list[i].value;
    if (dirRE.test(name)) {
      // mark element as dynamic
      el.hasBindings = true;
      // modifiers
      modifiers = parseModifiers(name);
      if (modifiers) {
        name = name.replace(modifierRE, '');
      }
      if (bindRE.test(name)) { // v-bind
        name = name.replace(bindRE, '');
        if (modifiers && modifiers.prop) {
          isProp = true;
          name = camelize(name);
          if (name === 'innerHtml') { name = 'innerHTML'; }
        }
        if (isProp || platformMustUseProp(name)) {
          addProp(el, name, value);
        } else {
          addAttr(el, name, value);
        }
      } else if (onRE.test(name)) { // v-on
        name = name.replace(onRE, '');
        addHandler(el, name, value, modifiers);
      } else { // normal directives
        name = name.replace(dirRE, '');
        // parse arg
        var argMatch = name.match(argRE);
        if (argMatch && (arg = argMatch[1])) {
          name = name.slice(0, -(arg.length + 1));
        }
        addDirective(el, name, rawName, value, arg, modifiers);
        if ("development" !== 'production' && name === 'model') {
          checkForAliasModel(el, value);
        }
      }
    } else {
      // literal attribute
      {
        var expression = parseText(value, delimiters);
        if (expression) {
          warn$1(
            name + "=\"" + value + "\": " +
            'Interpolation inside attributes has been removed. ' +
            'Use v-bind or the colon shorthand instead. For example, ' +
            'instead of <div id="{{ val }}">, use <div :id="val">.'
          );
        }
      }
      addAttr(el, name, JSON.stringify(value));
    }
  }
}

function checkInFor (el) {
  var parent = el;
  while (parent) {
    if (parent.for !== undefined) {
      return true
    }
    parent = parent.parent;
  }
  return false
}

function parseModifiers (name) {
  var match = name.match(modifierRE);
  if (match) {
    var ret = {};
    match.forEach(function (m) { ret[m.slice(1)] = true; });
    return ret
  }
}

function makeAttrsMap (attrs, isIE) {
  var map = {};
  for (var i = 0, l = attrs.length; i < l; i++) {
    if ("development" !== 'production' && map[attrs[i].name] && !isIE) {
      warn$1('duplicate attribute: ' + attrs[i].name);
    }
    map[attrs[i].name] = attrs[i].value;
  }
  return map
}

function findPrevElement (children) {
  var i = children.length;
  while (i--) {
    if (children[i].tag) { return children[i] }
  }
}

function isForbiddenTag (el) {
  return (
    el.tag === 'style' ||
    (el.tag === 'script' && (
      !el.attrsMap.type ||
      el.attrsMap.type === 'text/javascript'
    ))
  )
}

var ieNSBug = /^xmlns:NS\d+/;
var ieNSPrefix = /^NS\d+:/;

/* istanbul ignore next */
function guardIESVGBug (attrs) {
  var res = [];
  for (var i = 0; i < attrs.length; i++) {
    var attr = attrs[i];
    if (!ieNSBug.test(attr.name)) {
      attr.name = attr.name.replace(ieNSPrefix, '');
      res.push(attr);
    }
  }
  return res
}

function checkForAliasModel (el, value) {
  var _el = el;
  while (_el) {
    if (_el.for && _el.alias === value) {
      warn$1(
        "<" + (el.tag) + " v-model=\"" + value + "\">: " +
        "You are binding v-model directly to a v-for iteration alias. " +
        "This will not be able to modify the v-for source array because " +
        "writing to the alias is like modifying a function local variable. " +
        "Consider using an array of objects and use v-model on an object property instead."
      );
    }
    _el = _el.parent;
  }
}

/*  */

var isStaticKey;
var isPlatformReservedTag;

var genStaticKeysCached = cached(genStaticKeys$1);

/**
 * Goal of the optimizer: walk the generated template AST tree
 * and detect sub-trees that are purely static, i.e. parts of
 * the DOM that never needs to change.
 *
 * Once we detect these sub-trees, we can:
 *
 * 1. Hoist them into constants, so that we no longer need to
 *    create fresh nodes for them on each re-render;
 * 2. Completely skip them in the patching process.
 */
function optimize (root, options) {
  if (!root) { return }
  isStaticKey = genStaticKeysCached(options.staticKeys || '');
  isPlatformReservedTag = options.isReservedTag || (function () { return false; });
  // first pass: mark all non-static nodes.
  markStatic(root);
  // second pass: mark static roots.
  markStaticRoots(root, false);
}

function genStaticKeys$1 (keys) {
  return makeMap(
    'type,tag,attrsList,attrsMap,plain,parent,children,attrs' +
    (keys ? ',' + keys : '')
  )
}

function markStatic (node) {
  node.static = isStatic(node);
  if (node.type === 1) {
    // do not make component slot content static. this avoids
    // 1. components not able to mutate slot nodes
    // 2. static slot content fails for hot-reloading
    if (
      !isPlatformReservedTag(node.tag) &&
      node.tag !== 'slot' &&
      node.attrsMap['inline-template'] == null
    ) {
      return
    }
    for (var i = 0, l = node.children.length; i < l; i++) {
      var child = node.children[i];
      markStatic(child);
      if (!child.static) {
        node.static = false;
      }
    }
  }
}

function markStaticRoots (node, isInFor) {
  if (node.type === 1) {
    if (node.static || node.once) {
      node.staticInFor = isInFor;
    }
    if (node.static) {
      node.staticRoot = true;
      return
    }
    if (node.children) {
      for (var i = 0, l = node.children.length; i < l; i++) {
        var child = node.children[i];
        isInFor = isInFor || !!node.for;
        markStaticRoots(child, isInFor);
        if (child.type === 1 && child.elseBlock) {
          markStaticRoots(child.elseBlock, isInFor);
        }
      }
    }
  }
}

function isStatic (node) {
  if (node.type === 2) { // expression
    return false
  }
  if (node.type === 3) { // text
    return true
  }
  return !!(node.pre || (
    !node.hasBindings && // no dynamic bindings
    !node.if && !node.for && // not v-if or v-for or v-else
    !isBuiltInTag(node.tag) && // not a built-in
    isPlatformReservedTag(node.tag) && // not a component
    !isDirectChildOfTemplateFor(node) &&
    Object.keys(node).every(isStaticKey)
  ))
}

function isDirectChildOfTemplateFor (node) {
  while (node.parent) {
    node = node.parent;
    if (node.tag !== 'template') {
      return false
    }
    if (node.for) {
      return true
    }
  }
  return false
}

/*  */

var simplePathRE = /^\s*[A-Za-z_$][\w$]*(?:\.[A-Za-z_$][\w$]*|\['.*?']|\[".*?"]|\[\d+]|\[[A-Za-z_$][\w$]*])*\s*$/;

// keyCode aliases
var keyCodes = {
  esc: 27,
  tab: 9,
  enter: 13,
  space: 32,
  up: 38,
  left: 37,
  right: 39,
  down: 40,
  'delete': [8, 46]
};

var modifierCode = {
  stop: '$event.stopPropagation();',
  prevent: '$event.preventDefault();',
  self: 'if($event.target !== $event.currentTarget)return;'
};

function genHandlers (events, native) {
  var res = native ? 'nativeOn:{' : 'on:{';
  for (var name in events) {
    res += "\"" + name + "\":" + (genHandler(events[name])) + ",";
  }
  return res.slice(0, -1) + '}'
}

function genHandler (
  handler
) {
  if (!handler) {
    return 'function(){}'
  } else if (Array.isArray(handler)) {
    return ("[" + (handler.map(genHandler).join(',')) + "]")
  } else if (!handler.modifiers) {
    return simplePathRE.test(handler.value)
      ? handler.value
      : ("function($event){" + (handler.value) + "}")
  } else {
    var code = '';
    var keys = [];
    for (var key in handler.modifiers) {
      if (modifierCode[key]) {
        code += modifierCode[key];
      } else {
        keys.push(key);
      }
    }
    if (keys.length) {
      code = genKeyFilter(keys) + code;
    }
    var handlerCode = simplePathRE.test(handler.value)
      ? handler.value + '($event)'
      : handler.value;
    return 'function($event){' + code + handlerCode + '}'
  }
}

function genKeyFilter (keys) {
  var code = keys.length === 1
    ? normalizeKeyCode(keys[0])
    : Array.prototype.concat.apply([], keys.map(normalizeKeyCode));
  if (Array.isArray(code)) {
    return ("if(" + (code.map(function (c) { return ("$event.keyCode!==" + c); }).join('&&')) + ")return;")
  } else {
    return ("if($event.keyCode!==" + code + ")return;")
  }
}

function normalizeKeyCode (key) {
  return (
    parseInt(key, 10) || // number keyCode
    keyCodes[key] || // built-in alias
    ("_k(" + (JSON.stringify(key)) + ")") // custom alias
  )
}

/*  */

function bind$2 (el, dir) {
  el.wrapData = function (code) {
    return ("_b(" + code + "," + (dir.value) + (dir.modifiers && dir.modifiers.prop ? ',true' : '') + ")")
  };
}

var baseDirectives = {
  bind: bind$2,
  cloak: noop
};

/*  */

// configurable state
var warn$2;
var transforms$1;
var dataGenFns;
var platformDirectives$1;
var staticRenderFns;
var onceCount;
var currentOptions;

function generate (
  ast,
  options
) {
  // save previous staticRenderFns so generate calls can be nested
  var prevStaticRenderFns = staticRenderFns;
  var currentStaticRenderFns = staticRenderFns = [];
  var prevOnceCount = onceCount;
  onceCount = 0;
  currentOptions = options;
  warn$2 = options.warn || baseWarn;
  transforms$1 = pluckModuleFunction(options.modules, 'transformCode');
  dataGenFns = pluckModuleFunction(options.modules, 'genData');
  platformDirectives$1 = options.directives || {};
  var code = ast ? genElement(ast) : '_h("div")';
  staticRenderFns = prevStaticRenderFns;
  onceCount = prevOnceCount;
  return {
    render: ("with(this){return " + code + "}"),
    staticRenderFns: currentStaticRenderFns
  }
}

function genElement (el) {
  if (el.staticRoot && !el.staticProcessed) {
    return genStatic(el)
  } else if (el.once && !el.onceProcessed) {
    return genOnce(el)
  } else if (el.for && !el.forProcessed) {
    return genFor(el)
  } else if (el.if && !el.ifProcessed) {
    return genIf(el)
  } else if (el.tag === 'template' && !el.slotTarget) {
    return genChildren(el) || 'void 0'
  } else if (el.tag === 'slot') {
    return genSlot(el)
  } else {
    // component or element
    var code;
    if (el.component) {
      code = genComponent(el.component, el);
    } else {
      var data = el.plain ? undefined : genData(el);

      var children = el.inlineTemplate ? null : genChildren(el);
      code = "_h('" + (el.tag) + "'" + (data ? ("," + data) : '') + (children ? ("," + children) : '') + ")";
    }
    // module transforms
    for (var i = 0; i < transforms$1.length; i++) {
      code = transforms$1[i](el, code);
    }
    return code
  }
}

// hoist static sub-trees out
function genStatic (el) {
  el.staticProcessed = true;
  staticRenderFns.push(("with(this){return " + (genElement(el)) + "}"));
  return ("_m(" + (staticRenderFns.length - 1) + (el.staticInFor ? ',true' : '') + ")")
}

// v-once
function genOnce (el) {
  el.onceProcessed = true;
  if (el.if && !el.ifProcessed) {
    return genIf(el)
  } else if (el.staticInFor) {
    var key = '';
    var parent = el.parent;
    while (parent) {
      if (parent.for) {
        key = parent.key;
        break
      }
      parent = parent.parent;
    }
    if (!key) {
      "development" !== 'production' && warn$2(
        "v-once can only be used inside v-for that is keyed. "
      );
      return genElement(el)
    }
    return ("_o(" + (genElement(el)) + "," + (onceCount++) + (key ? ("," + key) : "") + ")")
  } else {
    return genStatic(el)
  }
}

// v-if with v-once shuold generate code like (a)?_m(0):_m(1)
function genIf (el) {
  var exp = el.if;
  el.ifProcessed = true; // avoid recursion
  return ("(" + exp + ")?" + (el.once ? genOnce(el) : genElement(el)) + ":" + (genElse(el)))
}

function genElse (el) {
  return el.elseBlock
    ? genElement(el.elseBlock)
    : '_e()'
}

function genFor (el) {
  var exp = el.for;
  var alias = el.alias;
  var iterator1 = el.iterator1 ? ("," + (el.iterator1)) : '';
  var iterator2 = el.iterator2 ? ("," + (el.iterator2)) : '';
  el.forProcessed = true; // avoid recursion
  return "_l((" + exp + ")," +
    "function(" + alias + iterator1 + iterator2 + "){" +
      "return " + (genElement(el)) +
    '})'
}

function genData (el) {
  var data = '{';

  // directives first.
  // directives may mutate the el's other properties before they are generated.
  var dirs = genDirectives(el);
  if (dirs) { data += dirs + ','; }

  // key
  if (el.key) {
    data += "key:" + (el.key) + ",";
  }
  // ref
  if (el.ref) {
    data += "ref:" + (el.ref) + ",";
  }
  if (el.refInFor) {
    data += "refInFor:true,";
  }
  // record original tag name for components using "is" attribute
  if (el.component) {
    data += "tag:\"" + (el.tag) + "\",";
  }
  // slot target
  if (el.slotTarget) {
    data += "slot:" + (el.slotTarget) + ",";
  }
  // module data generation functions
  for (var i = 0; i < dataGenFns.length; i++) {
    data += dataGenFns[i](el);
  }
  // attributes
  if (el.attrs) {
    data += "attrs:{" + (genProps(el.attrs)) + "},";
  }
  // DOM props
  if (el.props) {
    data += "domProps:{" + (genProps(el.props)) + "},";
  }
  // event handlers
  if (el.events) {
    data += (genHandlers(el.events)) + ",";
  }
  if (el.nativeEvents) {
    data += (genHandlers(el.nativeEvents, true)) + ",";
  }
  // inline-template
  if (el.inlineTemplate) {
    var ast = el.children[0];
    if ("development" !== 'production' && (
      el.children.length > 1 || ast.type !== 1
    )) {
      warn$2('Inline-template components must have exactly one child element.');
    }
    if (ast.type === 1) {
      var inlineRenderFns = generate(ast, currentOptions);
      data += "inlineTemplate:{render:function(){" + (inlineRenderFns.render) + "},staticRenderFns:[" + (inlineRenderFns.staticRenderFns.map(function (code) { return ("function(){" + code + "}"); }).join(',')) + "]}";
    }
  }
  data = data.replace(/,$/, '') + '}';
  // v-bind data wrap
  if (el.wrapData) {
    data = el.wrapData(data);
  }
  return data
}

function genDirectives (el) {
  var dirs = el.directives;
  if (!dirs) { return }
  var res = 'directives:[';
  var hasRuntime = false;
  var i, l, dir, needRuntime;
  for (i = 0, l = dirs.length; i < l; i++) {
    dir = dirs[i];
    needRuntime = true;
    var gen = platformDirectives$1[dir.name] || baseDirectives[dir.name];
    if (gen) {
      // compile-time directive that manipulates AST.
      // returns true if it also needs a runtime counterpart.
      needRuntime = !!gen(el, dir, warn$2);
    }
    if (needRuntime) {
      hasRuntime = true;
      res += "{name:\"" + (dir.name) + "\",rawName:\"" + (dir.rawName) + "\"" + (dir.value ? (",value:(" + (dir.value) + "),expression:" + (JSON.stringify(dir.value))) : '') + (dir.arg ? (",arg:\"" + (dir.arg) + "\"") : '') + (dir.modifiers ? (",modifiers:" + (JSON.stringify(dir.modifiers))) : '') + "},";
    }
  }
  if (hasRuntime) {
    return res.slice(0, -1) + ']'
  }
}

function genChildren (el) {
  if (el.children.length) {
    return '[' + el.children.map(genNode).join(',') + ']'
  }
}

function genNode (node) {
  if (node.type === 1) {
    return genElement(node)
  } else {
    return genText(node)
  }
}

function genText (text) {
  return text.type === 2
    ? text.expression // no need for () because already wrapped in _s()
    : JSON.stringify(text.text)
}

function genSlot (el) {
  var slotName = el.slotName || '"default"';
  var children = genChildren(el);
  return ("_t(" + slotName + (children ? ("," + children) : '') + ")")
}

// componentName is el.component, take it as argument to shun flow's pessimistic refinement
function genComponent (componentName, el) {
  var children = el.inlineTemplate ? null : genChildren(el);
  return ("_h(" + componentName + "," + (genData(el)) + (children ? ("," + children) : '') + ")")
}

function genProps (props) {
  var res = '';
  for (var i = 0; i < props.length; i++) {
    var prop = props[i];
    res += "\"" + (prop.name) + "\":" + (prop.value) + ",";
  }
  return res.slice(0, -1)
}

/*  */

/**
 * Compile a template.
 */
function compile$1 (
  template,
  options
) {
  var ast = parse(template.trim(), options);
  optimize(ast, options);
  var code = generate(ast, options);
  return {
    ast: ast,
    render: code.render,
    staticRenderFns: code.staticRenderFns
  }
}

/*  */

// operators like typeof, instanceof and in are allowed
var prohibitedKeywordRE = new RegExp('\\b' + (
  'do,if,for,let,new,try,var,case,else,with,await,break,catch,class,const,' +
  'super,throw,while,yield,delete,export,import,return,switch,default,' +
  'extends,finally,continue,debugger,function,arguments'
).split(',').join('\\b|\\b') + '\\b');
// check valid identifier for v-for
var identRE = /[A-Za-z_$][\w$]*/;
// strip strings in expressions
var stripStringRE = /'(?:[^'\\]|\\.)*'|"(?:[^"\\]|\\.)*"|`(?:[^`\\]|\\.)*\$\{|\}(?:[^`\\]|\\.)*`|`(?:[^`\\]|\\.)*`/g;

// detect problematic expressions in a template
function detectErrors (ast) {
  var errors = [];
  if (ast) {
    checkNode(ast, errors);
  }
  return errors
}

function checkNode (node, errors) {
  if (node.type === 1) {
    for (var name in node.attrsMap) {
      if (dirRE.test(name)) {
        var value = node.attrsMap[name];
        if (value) {
          if (name === 'v-for') {
            checkFor(node, ("v-for=\"" + value + "\""), errors);
          } else {
            checkExpression(value, (name + "=\"" + value + "\""), errors);
          }
        }
      }
    }
    if (node.children) {
      for (var i = 0; i < node.children.length; i++) {
        checkNode(node.children[i], errors);
      }
    }
  } else if (node.type === 2) {
    checkExpression(node.expression, node.text, errors);
  }
}

function checkFor (node, text, errors) {
  checkExpression(node.for || '', text, errors);
  checkIdentifier(node.alias, 'v-for alias', text, errors);
  checkIdentifier(node.iterator1, 'v-for iterator', text, errors);
  checkIdentifier(node.iterator2, 'v-for iterator', text, errors);
}

function checkIdentifier (ident, type, text, errors) {
  if (typeof ident === 'string' && !identRE.test(ident)) {
    errors.push(("- invalid " + type + " \"" + ident + "\" in expression: " + text));
  }
}

function checkExpression (exp, text, errors) {
  try {
    new Function(("return " + exp));
  } catch (e) {
    var keywordMatch = exp.replace(stripStringRE, '').match(prohibitedKeywordRE);
    if (keywordMatch) {
      errors.push(
        "- avoid using JavaScript keyword as property name: " +
        "\"" + (keywordMatch[0]) + "\" in expression " + text
      );
    } else {
      errors.push(("- invalid expression: " + text));
    }
  }
}

/*  */

function transformNode (el, options) {
  var warn = options.warn || baseWarn;
  var staticClass = getAndRemoveAttr(el, 'class');
  if ("development" !== 'production' && staticClass) {
    var expression = parseText(staticClass, options.delimiters);
    if (expression) {
      warn(
        "class=\"" + staticClass + "\": " +
        'Interpolation inside attributes has been removed. ' +
        'Use v-bind or the colon shorthand instead. For example, ' +
        'instead of <div class="{{ val }}">, use <div :class="val">.'
      );
    }
  }
  if (staticClass) {
    el.staticClass = JSON.stringify(staticClass);
  }
  var classBinding = getBindingAttr(el, 'class', false /* getStatic */);
  if (classBinding) {
    el.classBinding = classBinding;
  }
}

function genData$1 (el) {
  var data = '';
  if (el.staticClass) {
    data += "staticClass:" + (el.staticClass) + ",";
  }
  if (el.classBinding) {
    data += "class:" + (el.classBinding) + ",";
  }
  return data
}

var klass$1 = {
  staticKeys: ['staticClass'],
  transformNode: transformNode,
  genData: genData$1
};

/*  */

function transformNode$1 (el, options) {
  var warn = options.warn || baseWarn;
  var staticStyle = getAndRemoveAttr(el, 'style');
  if (staticStyle) {
    /* istanbul ignore if */
    {
      var expression = parseText(staticStyle, options.delimiters);
      if (expression) {
        warn(
          "style=\"" + staticStyle + "\": " +
          'Interpolation inside attributes has been removed. ' +
          'Use v-bind or the colon shorthand instead. For example, ' +
          'instead of <div style="{{ val }}">, use <div :style="val">.'
        );
      }
    }
    el.staticStyle = JSON.stringify(parseStyleText(staticStyle));
  }

  var styleBinding = getBindingAttr(el, 'style', false /* getStatic */);
  if (styleBinding) {
    el.styleBinding = styleBinding;
  }
}

function genData$2 (el) {
  var data = '';
  if (el.staticStyle) {
    data += "staticStyle:" + (el.staticStyle) + ",";
  }
  if (el.styleBinding) {
    data += "style:(" + (el.styleBinding) + "),";
  }
  return data
}

var style$1 = {
  staticKeys: ['staticStyle'],
  transformNode: transformNode$1,
  genData: genData$2
};

var modules$1 = [
  klass$1,
  style$1
];

/*  */

var warn$3;

function model$1 (
  el,
  dir,
  _warn
) {
  warn$3 = _warn;
  var value = dir.value;
  var modifiers = dir.modifiers;
  var tag = el.tag;
  var type = el.attrsMap.type;
  {
    var dynamicType = el.attrsMap['v-bind:type'] || el.attrsMap[':type'];
    if (tag === 'input' && dynamicType) {
      warn$3(
        "<input :type=\"" + dynamicType + "\" v-model=\"" + value + "\">:\n" +
        "v-model does not support dynamic input types. Use v-if branches instead."
      );
    }
  }
  if (tag === 'select') {
    genSelect(el, value, modifiers);
  } else if (tag === 'input' && type === 'checkbox') {
    genCheckboxModel(el, value, modifiers);
  } else if (tag === 'input' && type === 'radio') {
    genRadioModel(el, value, modifiers);
  } else {
    genDefaultModel(el, value, modifiers);
  }
  // ensure runtime directive metadata
  return true
}

function genCheckboxModel (
  el,
  value,
  modifiers
) {
  if ("development" !== 'production' &&
    el.attrsMap.checked != null) {
    warn$3(
      "<" + (el.tag) + " v-model=\"" + value + "\" checked>:\n" +
      "inline checked attributes will be ignored when using v-model. " +
      'Declare initial values in the component\'s data option instead.'
    );
  }
  var number = modifiers && modifiers.number;
  var valueBinding = getBindingAttr(el, 'value') || 'null';
  var trueValueBinding = getBindingAttr(el, 'true-value') || 'true';
  var falseValueBinding = getBindingAttr(el, 'false-value') || 'false';
  addProp(el, 'checked',
    "Array.isArray(" + value + ")" +
      "?_i(" + value + "," + valueBinding + ")>-1" +
      ":_q(" + value + "," + trueValueBinding + ")"
  );
  addHandler(el, 'change',
    "var $$a=" + value + "," +
        '$$el=$event.target,' +
        "$$c=$$el.checked?(" + trueValueBinding + "):(" + falseValueBinding + ");" +
    'if(Array.isArray($$a)){' +
      "var $$v=" + (number ? '_n(' + valueBinding + ')' : valueBinding) + "," +
          '$$i=_i($$a,$$v);' +
      "if($$c){$$i<0&&(" + value + "=$$a.concat($$v))}" +
      "else{$$i>-1&&(" + value + "=$$a.slice(0,$$i).concat($$a.slice($$i+1)))}" +
    "}else{" + value + "=$$c}",
    null, true
  );
}

function genRadioModel (
    el,
    value,
    modifiers
) {
  if ("development" !== 'production' &&
    el.attrsMap.checked != null) {
    warn$3(
      "<" + (el.tag) + " v-model=\"" + value + "\" checked>:\n" +
      "inline checked attributes will be ignored when using v-model. " +
      'Declare initial values in the component\'s data option instead.'
    );
  }
  var number = modifiers && modifiers.number;
  var valueBinding = getBindingAttr(el, 'value') || 'null';
  valueBinding = number ? ("_n(" + valueBinding + ")") : valueBinding;
  addProp(el, 'checked', ("_q(" + value + "," + valueBinding + ")"));
  addHandler(el, 'change', genAssignmentCode(value, valueBinding), null, true);
}

function genDefaultModel (
  el,
  value,
  modifiers
) {
  {
    if (el.tag === 'input' && el.attrsMap.value) {
      warn$3(
        "<" + (el.tag) + " v-model=\"" + value + "\" value=\"" + (el.attrsMap.value) + "\">:\n" +
        'inline value attributes will be ignored when using v-model. ' +
        'Declare initial values in the component\'s data option instead.'
      );
    }
    if (el.tag === 'textarea' && el.children.length) {
      warn$3(
        "<textarea v-model=\"" + value + "\">:\n" +
        'inline content inside <textarea> will be ignored when using v-model. ' +
        'Declare initial values in the component\'s data option instead.'
      );
    }
  }

  var type = el.attrsMap.type;
  var ref = modifiers || {};
  var lazy = ref.lazy;
  var number = ref.number;
  var trim = ref.trim;
  var event = lazy || (isIE && type === 'range') ? 'change' : 'input';
  var needCompositionGuard = !lazy && type !== 'range';
  var isNative = el.tag === 'input' || el.tag === 'textarea';

  var valueExpression = isNative
    ? ("$event.target.value" + (trim ? '.trim()' : ''))
    : trim ? "(typeof $event === 'string' ? $event.trim() : $event)" : "$event";
  valueExpression = number || type === 'number'
    ? ("_n(" + valueExpression + ")")
    : valueExpression;
  var code = genAssignmentCode(value, valueExpression);
  if (isNative && needCompositionGuard) {
    code = "if($event.target.composing)return;" + code;
  }
  // inputs with type="file" are read only and setting the input's
  // value will throw an error.
  if ("development" !== 'production' &&
      type === 'file') {
    warn$3(
      "<" + (el.tag) + " v-model=\"" + value + "\" type=\"file\">:\n" +
      "File inputs are read only. Use a v-on:change listener instead."
    );
  }
  addProp(el, 'value', isNative ? ("_s(" + value + ")") : ("(" + value + ")"));
  addHandler(el, event, code, null, true);
}

function genSelect (
    el,
    value,
    modifiers
) {
  {
    el.children.some(checkOptionWarning);
  }

  var number = modifiers && modifiers.number;
  var assignment = "Array.prototype.filter" +
    ".call($event.target.options,function(o){return o.selected})" +
    ".map(function(o){var val = \"_value\" in o ? o._value : o.value;" +
    "return " + (number ? '_n(val)' : 'val') + "})" +
    (el.attrsMap.multiple == null ? '[0]' : '');

  var code = genAssignmentCode(value, assignment);
  addHandler(el, 'change', code, null, true);
}

function checkOptionWarning (option) {
  if (option.type === 1 &&
    option.tag === 'option' &&
    option.attrsMap.selected != null) {
    warn$3(
      "<select v-model=\"" + (option.parent.attrsMap['v-model']) + "\">:\n" +
      'inline selected attributes on <option> will be ignored when using v-model. ' +
      'Declare initial values in the component\'s data option instead.'
    );
    return true
  }
  return false
}

function genAssignmentCode (value, assignment) {
  var modelRs = parseModel(value);
  if (modelRs.idx === null) {
    return (value + "=" + assignment)
  } else {
    return "var $$exp = " + (modelRs.exp) + ", $$idx = " + (modelRs.idx) + ";" +
      "if (!Array.isArray($$exp)){" +
        value + "=" + assignment + "}" +
      "else{$$exp.splice($$idx, 1, " + assignment + ")}"
  }
}

/*  */

function text (el, dir) {
  if (dir.value) {
    addProp(el, 'textContent', ("_s(" + (dir.value) + ")"));
  }
}

/*  */

function html (el, dir) {
  if (dir.value) {
    addProp(el, 'innerHTML', ("_s(" + (dir.value) + ")"));
  }
}

var directives$1 = {
  model: model$1,
  text: text,
  html: html
};

/*  */

var cache = Object.create(null);

var baseOptions = {
  isIE: isIE,
  expectHTML: true,
  modules: modules$1,
  staticKeys: genStaticKeys(modules$1),
  directives: directives$1,
  isReservedTag: isReservedTag,
  isUnaryTag: isUnaryTag,
  mustUseProp: mustUseProp,
  getTagNamespace: getTagNamespace,
  isPreTag: isPreTag
};

function compile$$1 (
  template,
  options
) {
  options = options
    ? extend(extend({}, baseOptions), options)
    : baseOptions;
  return compile$1(template, options)
}

function compileToFunctions (
  template,
  options,
  vm
) {
  var _warn = (options && options.warn) || warn;
  // detect possible CSP restriction
  /* istanbul ignore if */
  {
    try {
      new Function('return 1');
    } catch (e) {
      if (e.toString().match(/unsafe-eval|CSP/)) {
        _warn(
          'It seems you are using the standalone build of Vue.js in an ' +
          'environment with Content Security Policy that prohibits unsafe-eval. ' +
          'The template compiler cannot work in this environment. Consider ' +
          'relaxing the policy to allow unsafe-eval or pre-compiling your ' +
          'templates into render functions.'
        );
      }
    }
  }
  var key = options && options.delimiters
    ? String(options.delimiters) + template
    : template;
  if (cache[key]) {
    return cache[key]
  }
  var res = {};
  var compiled = compile$$1(template, options);
  res.render = makeFunction(compiled.render);
  var l = compiled.staticRenderFns.length;
  res.staticRenderFns = new Array(l);
  for (var i = 0; i < l; i++) {
    res.staticRenderFns[i] = makeFunction(compiled.staticRenderFns[i]);
  }
  {
    if (res.render === noop || res.staticRenderFns.some(function (fn) { return fn === noop; })) {
      _warn(
        "failed to compile template:\n\n" + template + "\n\n" +
        detectErrors(compiled.ast).join('\n') +
        '\n\n',
        vm
      );
    }
  }
  return (cache[key] = res)
}

function makeFunction (code) {
  try {
    return new Function(code)
  } catch (e) {
    return noop
  }
}

/*  */

var idToTemplate = cached(function (id) {
  var el = query(id);
  return el && el.innerHTML
});

var mount = Vue$3.prototype.$mount;
Vue$3.prototype.$mount = function (
  el,
  hydrating
) {
  el = el && query(el);

  /* istanbul ignore if */
  if (el === document.body || el === document.documentElement) {
    "development" !== 'production' && warn(
      "Do not mount Vue to <html> or <body> - mount to normal elements instead."
    );
    return this
  }

  var options = this.$options;
  // resolve template/el and convert to render function
  if (!options.render) {
    var template = options.template;
    if (template) {
      if (typeof template === 'string') {
        if (template.charAt(0) === '#') {
          template = idToTemplate(template);
          /* istanbul ignore if */
          if ("development" !== 'production' && !template) {
            warn(
              ("Template element not found or is empty: " + (options.template)),
              this
            );
          }
        }
      } else if (template.nodeType) {
        template = template.innerHTML;
      } else {
        {
          warn('invalid template option:' + template, this);
        }
        return this
      }
    } else if (el) {
      template = getOuterHTML(el);
    }
    if (template) {
      var ref = compileToFunctions(template, {
        warn: warn,
        shouldDecodeNewlines: shouldDecodeNewlines,
        delimiters: options.delimiters
      }, this);
      var render = ref.render;
      var staticRenderFns = ref.staticRenderFns;
      options.render = render;
      options.staticRenderFns = staticRenderFns;
    }
  }
  return mount.call(this, el, hydrating)
};

/**
 * Get outerHTML of elements, taking care
 * of SVG elements in IE as well.
 */
function getOuterHTML (el) {
  if (el.outerHTML) {
    return el.outerHTML
  } else {
    var container = document.createElement('div');
    container.appendChild(el.cloneNode(true));
    return container.innerHTML
  }
}

Vue$3.compile = compileToFunctions;

return Vue$3;

})));

},{}],10:[function(require,module,exports){
"use strict";

module.exports = { "de": { "buttons": { "next": "Weiter", "save": "Speichern", "delete": "L\xF6schen", "loadgraph": "Graph laden", "upload": "Hochladen", "start_setup": "Setup starten", "add": "Hinzuf\xFCgen", "add_property": "Neue Eigenschaft", "add_state": "Neuer Zustand", "create": "Hinzuf\xFCgen", "edit": "Bearbeiten", "details": "Details", "download": "Herunterladen", "emergency_stop": "Notaus", "emergency_resume": "Notus aufheben", "revoke": "Widerrufen" }, "components": { "user": "Benutzer|Benutzer", "users": "Benutzer|Benutzer", "terrarium": "Terrarium|Terraria", "terraria": "Terrarium|Terrarien", "animal": "Tier|Tiere", "animals": "Tier|Tiere", "controlunit": "Steuereinheit|Steuereinheiten", "controlunits": "Steuereinheit|Steuereinheiten", "pump": "Pumpe|Pumpen", "pumps": "Pumpe|Pumpen", "valve": "Ventil|Ventile", "valves": "Ventil|Ventile", "physical_sensor": "Physischer Sensor|Physische Sensoren", "physical_sensors": "Physischer Sensor|Physische Sensoren", "logical_sensor": "Logischer Sensor|Logische Sensoren", "logical_sensors": "Logischer Sensor|Logische Sensoren", "logical_sensor_threshold": "Logischer Sensor Grenzwert|Logische Sensor Grenzwerte", "logical_sensor_thresholds": "Logischer Sensor Grenzwert|Logische Sensor Grenzwerte", "file": "Datei|Dateien", "files": "Datei|Dateien", "log": "Systemlog|Systemlogs", "logs": "Systemlog|Systemlogs", "action": "Aktion|Aktionen", "actions": "Aktion|Aktionen", "action_sequence": "Aktionssequenz|Aktionssequenzen", "action_sequences": "Aktionssequenz|Aktionssequenzen", "action_sequence_schedules": "Aktionssequenz Zeitplan|Aktionssequenz Zeitpl\xE4ne", "action_sequence_schedule": "Aktionssequenz Zeitplan|Aktionssequenz Zeitpl\xE4ne", "action_sequence_triggers": "Aktionssequenz Ausl\xF6ser|Aktionssequenz Ausl\xF6ser", "action_sequence_trigger": "Aktionssequenz Ausl\xF6ser|Aktionssequenz Ausl\xF6ser", "action_sequence_intentions": "Aktionssequenz Intention|Aktionssequenz Intentionen", "action_sequence_intention": "Aktionssequenz Intention|Aktionssequenz Intentionen", "triggers": "Ausl\xF6ser|Ausl\xF6ser", "trigger": "Ausl\xF6ser|Ausl\xF6ser", "admin_panel": "Admin Panel", "admin_panels": "Admin Panel", "animal_feedings": "F\xFCtterung|F\xFCtterungen", "animal_feeding_schedules": "F\xFCtterungsplan|F\xFCtterungspl\xE4ne", "animal_weighings": "Wiegung|Wiegungen", "animal_weighing_schedules": "Wiegeplan|Wiegepl\xE4ne", "biography_entries": "Biografieeintrag|Biografieeintr\xE4ge", "caresheets": "Begleitblatt|Begleitbl\xE4tter", "generic_components": "Generische Komponente|Generische Komponenten", "generic_component_types": "Generischer Komponententyp|Generische Komponententypen" }, "errors": { "retrievegraphdata": "Could not retrieve graph data." }, "labels": { "connecting": "Verbinde", "title": "Titel", "text": "Text", "tags": "Tags", "status": "Status", "health": "Gesundheit", "birth": "Geburt", "gender": "Geschlecht", "gender_male": "M\xE4nnlich", "gender_female": "Weiblich", "date_birth": "Geburtstag", "date_death": "Todestag", "model": "Modell", "type": "Typ", "email": "E-Mail", "name": "Name", "name_singular": "Name (Singular)", "name_plural": "Name (Plural)", "display_name": "Anzeigename", "common_name": "Gemeiner Name", "latin_name": "Lateinischer Name", "temperature": "Temperatur", "temperature_celsius": "Temperatur", "humidity": "Feuchtigkeit", "humidity_percent": "Feuchtigkeit", "heartbeat": "Puls", "last_heartbeat": "Letzter Puls", "noanimals": "Keine Tiere", "create": "Erstelle", "settings": "Einstellung|Einstellungen", "notifications": "Benachrichtigung|Benachrichtigungen", "notification_type": "Nachrichten Kanal", "details": "Details", "rawlimitlo": "Rohwert - unteres Limit", "rawlimithi": "Rohwert - oberes Limit", "file": "Datei", "size": "Gr\xF6\xDFe", "current_value": "Aktueller Wert", "created_at": "Erstellt", "updated_at": "Letztes Update", "download": "Herunterladen", "properties": "Eigenschaften", "preview": "Vorschau", "source": "Quelle", "target": "Ziel", "associated_with": "Assoziiert mit", "log": "Log", "starts_at": "Beginnt um", "ends_at": "Endet um", "starts_after": "Startet nach", "thresholds": "Grenzwerte", "abilities": "F\xE4higkeit|F\xE4higkeiten", "bugtracker": "Bugtracker", "wiki": "Wiki", "auto_nightmode": "Auto Nachtmodus", "permanent_nightmode": "Permanenter Nachtmodus", "language": "Sprache", "belongsTo_type": "Geh\xF6rt zu (Typ)", "belongsTo_id": "Geh\xF6rt zu (ID)", "belongsTo": "Geh\xF6rt zu", "doku": "Dokumentation", "step": "Schritt", "phone_number": "Telefonnummer", "template": "Vorlage", "timezone": "Zeitzone", "critical": "Kritisch", "ok": "OK", "running": "Laufend", "runs_since": "L\xE4uft seit", "queued": "Wartend", "criticalstates": "Kritische Zust\xE4nde", "since": "Seit", "state": "Zustand", "actions": "Aktionen", "irrigate": "Bew\xE4ssern", "duration": "Dauer", "on": "An", "off": "Aus", "just_fed": "Gerade gef\xFCttert", "just_irrigated": "Gerade bew\xE4ssert", "add_weight": "Gewicht hinzuf\xFCgen", "active": "Aktiv", "copy_thresholds": "Grenzwerte kopieren", "daily": "T\xE4glich", "last_feeding": "Letzte F\xFCtterung", "crickets": "Heimchen", "mixed_fruits": "Fruchtbrei", "beetle_jelly": "BeetleJelly", "due": "F\xE4llig", "overdue": "\xDCberf\xE4llig", "meal_type": "Nahrungsart", "interval_days": "Intervall in Tagen", "interval": "Intervall", "password": "Passwort", "action": "Aktion", "weight": "Gewicht", "no_data": "Keine Daten", "overview": "\xDCbersicht", "environment": "Umgebung", "now": "Jetzt", "scheduled": "Geplant", "sequence": "Sequenz", "date": "Datum", "weighprogression": "Gewichtsverlauf", "from": "Von", "tO": "Bis", "feedings": "F\xFCtterungen", "temp_and_hum_history": "Temperatur- und Feuchtigkeitsverlauf", "biography": "Biografie", "bio_categories": "Biografiekategorien", "icon": "Symbol", "average": "Durchschnitt", "min": "Minimum", "max": "Maximum", "during_day": "Tags", "during_night": "Nachts", "total": "Total", "infrastructure": "Infrastruktur", "timeframe_start": "Zeitrahmen ab", "timeframe_end": "Zeitrahmen bis", "reference_value": "Vergleichswert", "reference_value_duration_threshold_minutes": "Vergleichswert unter/\xFCberschritten seit (Minuten)", "for": "f\xFCr", "minimum_timeout_minutes": "Timeout (Minuten)", "emergency_stop": "Notaus", "increases": "Erh\xF6ht", "decreases": "Senkt", "personal_access_tokens": "Zugriffstoken", "expires": "L\xE4uft ab", "security": "Sicherheit", "general": "Allgemein", "component": "Komponente", "to": "Bis", "daily_reminders": "T\xE4gliche Erinnerungen", "yesterday": "Gestern", "tomorrow": "Morgen", "today": "Heute", "runonce": "Nur einmal ausf\xFChren", "intention": "Intention" }, "languages": { "german": "Deutsch", "english": "Englisch" }, "menu": { "welcome": "Willkommen", "dashboard": "\xDCbersicht", "general": "Allgemein", "administration": "Administration", "create": "Erstellen", "edit": "Editieren", "delete": "L\xF6schen", "infrastructure": "Infrastruktur", "help": "Hilfe", "logout": "Abmelden", "animals": "Tiere", "terraria": "Terrarien", "users": "Benutzer", "animal_feeding_types": "Nahrungsarten", "logs": "Protokoll", "categories": "Kategorien" }, "messages": { "logical_sensor_thresholds": { "copy_warning": "Alle preexistenten Grenzwerte des Zielsensors werden entfernt." }, "users": { "setup_telegram_ok": "Telegram ist eingerichtet.", "setup_telegram_err": "Telegram ist noch nicht eingerichtet.", "setup_telegram_description": "Bitte \xF6ffnen Sie Telegram in Ihrem <a href=\"https://web.telegram.org/#/im?p=@ciliatusbot\">Browser</a> oder auf ihrem Smartphone und kontaktieren Sie <b>@ciliatusbot</b> mit untenstehendem Aktivierungscode." }, "critical_state_generic": "Kritisch: {critical}_state", "critical_state_notification_logical_sensor": { "humidity_percent": "Kritisch: Der Sensor {logical}_sensor meldet eine Feuchtigkeit von {humidity}_percent%.", "temperature_celsius": "Kritisch: Der Sensor {terrarium} meldet eine Temperatur von {temperature}_celsius\xB0C." }, "critical_state_recovery_notification_logical_sensor": { "humidity_percent": "OK: Der Sensor {logical}_sensor meldet eine Feuchtigkeit von {humidity}_percent%.", "temperature_celsius": "OK: Der Sensor {terrarium} meldet eine Temperatur von {temperature}_celsius\xB0C." }, "critical_state_notification_controlunit": "Kritisch: Die Steuereinheit {controlunit} sendet keine Daten.", "critical_state_recovery_notification_controlunit": "OK: Die Steuereinheit {controlunit} sendet wieder Daten.", "daily": { "intro": "T\xE4gliche Erinnerungen", "feedings_due": "F\xE4llige F\xFCtterungen:", "weighings_due": "F\xE4llige Wiegungen:" } }, "product": { "name": "ciliatus" }, "setup": { "welcome": "Willkommen zu Ciliatus", "create_user": "Erstelle Deinen Benutzer", "done": "Geschafft!", "what_now": "Was nun?", "login": "Einloggen", "tooltip_login": "Logge Dich mit Deinem soeben erstellten Nutzer bei Ciliatus an.", "add_terrarium": "Terrarium anlegen", "tooltip_add_terrarium": "Erstelle Dein erstes Terrarium in Ciliatus.", "add_animal": "Tier anlegen", "tooltip_add_animal": "Erstelle Dein erstes Tier und ordne es einem Terrarium zu.", "setup_telegram": "Telegram einrichten", "tooltip_setup_telegram": "Lerne, wie Du Telegram mit Ciliatus konfigurieren kannst.", "setup_controlunit": "Kontrolleinheit erstellen", "tooltip_setup_controlunit": "Lerne eine Kontrolleinheit und zugeordnete Sensoren anzulegen um damit zu beginnen Sensordaten zu Deinen Terrarien zu \xFCbermitteln.", "err_completed": "Das Setup wurde bereits durchgef\xFChrt." }, "tooltips": { "ctrltoselect": "Strg+Klick zum selektieren", "active": "Aktiv", "showondefaultdashboard": "Auf Default Dashboard anzeigen", "autoirrigation": "Automatische Bew\xE4sserung", "sendnotificationsfor": "Benachrichtigungen versenden f\xFCr", "loadandrendergraph": "Daten werden ermittelt und Graph wird gerendert", "disables_option": "Deaktiviert \"{option}\"", "phone_number": "Mobilnummer", "contact_bot": "Den Bot kontaktieren", "wait_confirmation": "Auf Best\xE4tigung warten", "set_state_to": "Zustand von <b>{target}</b> auf <b>{state}</b> \xE4ndern f\xFCr <b>{minutes} Minuten</b>", "start_after_started": "Startet wenn Schritt <b>{id}<\/b> gestartet wurde", "start_after_finished": "Startet wenn Schritt <b>{id}<\/b> beendet wurde", "sendnotifications": "Benachrichtigungen versenden", "no_schedules": "Keine Zeitpl\xE4ne", "runonce": "Einmalig", "heartbeat_critical": "Heartbeat ist kritisch!", "copy_thresholds_warning": "Alle existierenden Grenzwerte des Zielsensors werden entfernt.", "animal_feeding_schedule_matrix": "Diese Matrix enth\xE4lt alle definierten F\xFCtterungspl\xE4ne. Die Zahl in einer Spalte stellt das Intervall dar.", "animal_weighing_schedule_matrix": "Diese Matrix enth\xE4lt alle definierten Wiegepl\xE4ne. Die Zahl in einer Spalte stellt das Intervall gefolgt vom n\xE4chsten F\xE4lligkeitsdatum dar.", "done": "Erledigt", "skip": "\xDCberspringen", "material_icons_list": "Die komplette Symbolliste ist unter <a href=\"https:\/\/material.io\/icons\/\">material.io<\/a> einsehbar.", "no_data": "Keine Daten.", "connecting_to_server": "Verbindung zum Ciliatus Server wird hergestellt. Sollte dies l\xE4nger als einige Sekunden dauern, \xFCberpr\xFCfen Sie bitte Ihre Internetverbindung.", "generic_components": { "about": "Generische Komponenten sind Komponenten eines benutzerdefinierten Typs.", "type_about": "Generische Komponententypen definieren Name, Eigenschaften und m\xF6gliche Zust\xE4nde f\xFCr generische Komponenten. Sie dienen als Vorlage beim Erstellen einer neuen generischen Komponente.", "property_templates": "Definiert die Eigenschaften eines generischen Komponententyps. Beim Erstellen einer neuen Komponente diesen Typs wird man aufgefordert, diese Eigenschaften auszuf\xFCllen.", "state_templates": "Definiert m\xF6gliche Zust\xE4nde die eine Komponente diesen Typs haben kann. Beim Erstellen einer Aktionssequenz kann man aus den hier definierten Zust\xE4nden den gew\xFCnschten Zustand ausw\xE4hlen.", "type_delete_warning": "Beim L\xF6schen eines Komponententyps werden <strong>alle Komponenten dieses Typs</strong> gel\xF6scht." }, "minimum_timeout_minutes": "Definiert die Dauer der minimalen Pause, bevor die Aktionssequenz durch diesen Ausl\xF6ser nach einem Durchlauf erneut gestartet werden kann.", "reference_value": "Der Wert, mit dem der Sensorwert verglichen werden soll.", "reference_value_duration_threshold_minutes": "Dauer in Minuten, die der Sensorwert den Grenzwert unter/\xFCberschritten haben muss, bevor die Aktionssequenz ausgel\xF6st wird.", "emergency_stop": "H\xE4lt sofort alle Aktionssequenzen an und verhindert das Starten neuer Aktionssequenzen bis der Notaus aufgehoben wird.", "emergency_resume": "Hebt den Notaus auf und erlaubt den Start von Aktionssequenzen.", "leave_empty_for_auto": "Frei lassen f\xFCr automatisch", "intention_increase_decrease": "Definiert ob die Intention dieser Aktionssequenz das Erh\xF6hen oder Senken des Sensorwerts ist." }, "units": { "years": "Jahr|Jahre", "months": "Monat|Monate", "days": "Tag|Tage", "hours": "Stunde|Stunden", "minutes": "Minute|Minuten", "seconds": "Sekunde|Sekunden", "temperature_celsius": "\xB0C", "humidity_percent": "%", "days_ago": "vor {val} Tagen", "hours_ago": "vor {val} Stunden", "minutes_ago": "vor {val} Minuten", "just_now": "gerade eben", "lesser": "<", "greater": ">", "equals": "=" } }, "en": { "buttons": { "next": "Next", "save": "Save", "delete": "Delete", "loadgraph": "Load graph", "upload": "Upload", "start_setup": "Start Setup", "add": "Add", "add_property": "Add Property", "add_state": "Add State", "create": "Add", "edit": "Edit", "details": "Details", "download": "Download", "emergency_stop": "Emergency stop", "emergency_resume": "Remove emergency stop", "revoke": "Revoke" }, "components": { "user": "User|Users", "users": "User|Users", "terrarium": "Terrarium|Terraria", "terraria": "Terrarium|Terraria", "animal": "Animal|Animals", "animals": "Animal|Animals", "controlunit": "Control Unit|Control Units", "controlunits": "Control Unit|Control Units", "pump": "Pump|Pumps", "pumps": "Pump|Pumps", "valve": "Valve|Valves", "valves": "Valve|Valves", "physical_sensor": "Physical Sensor|Physical Sensors", "physical_sensors": "Physical Sensor|Physical Sensors", "logical_sensor": "Logical Sensor|Logical Sensors", "logical_sensors": "Logical Sensor|Logical Sensors", "logical_sensor_threshold": "Logical Sensor Threshold|Logical Sensor Thresholds", "logical_sensor_thresholds": "Logical Sensor Threshold|Logical Sensor Thresholds", "file": "File|Files", "files": "File|Files", "log": "System log|System logs", "logs": "System log|System logs", "action": "Action|Actions", "actions": "Action|Actions", "action_sequence": "Action sequence|Action sequences", "action_sequences": "Action sequence|Action sequences", "action_sequence_schedule": "Action sequence schedule|Action sequence schedules", "action_sequence_schedules": "Action sequence schedule|Action sequence schedules", "action_sequence_triggers": "Action sequence trigger|Action sequence triggers", "action_sequence_trigger": "Action sequence trigger|Action sequence triggers", "action_sequence_intentions": "Action sequence intention|Action sequence intentions", "action_sequence_intention": "Action sequence intention|Action sequence intentions", "triggers": "Trigger|Triggers", "trigger": "Trigger|Triggers", "admin_panel": "Admin panel", "admin_panels": "Admin panel", "animal_feedings": "Feeding|Feedings", "animal_feeding_schedules": "Feeding schedule|Feeding schedules", "animal_weighings": "Weighing|Weighings", "animal_weighing_schedules": "Weighing schedule|Weighing schedules", "biography_entries": "Biography entry|Biography entries", "caresheets": "Care Sheet|Care Sheets", "generic_components": "Generic Component|Generic Components", "generic_component_types": "Generic Component Type|Generic Component Types" }, "errors": { "retrievegraphdata": "Graphdaten konnten nicht ermittelt werden." }, "labels": { "connecting": "Connecting", "title": "Title", "text": "Text", "tags": "Tags", "status": "Status", "health": "Health", "birth": "Birth", "gender": "Gender", "gender_male": "Male", "gender_female": "Female", "date_birth": "Day of birth", "date_death": "Day of death", "model": "Model", "type": "Type", "email": "E-Mail", "name": "Name", "name_singular": "Name (Singular)", "name_plural": "Name (Plural)", "display_name": "Display Name", "common_name": "Common Name", "latin_name": "Latin Name", "temperature": "Temperature", "temperature_celsius": "Temperature", "humidity": "Humidity", "humidity_percent": "Humidity", "heartbeat": "Heartbeat", "last_heartbeat": "Last Heartbeat", "noanimals": "No Animals", "create": "Create", "settings": "Setting|Settings", "notifications": "Notification|Notifications", "notification_type": "Notification channel", "details": "Details", "rawlimitlo": "Raw value - lower limit", "rawlimithi": "Raw value - upper limit", "file": "File", "size": "Size", "current_value": "Current value", "created_at": "Creation", "updated_at": "Last update", "download": "Download", "properties": "Properties", "preview": "Preview", "source": "Source", "target": "Target", "associated_with": "Associated with", "log": "Log", "starts_at": "Starts at", "ends_at": "Ends at", "starts_after": "Starts after", "thresholds": "Thresholds", "abilities": "Ability|Abilities", "bugtracker": "Bugtracker", "wiki": "Wiki", "auto_nightmode": "Auto night mode", "permanent_nightmode": "Permanent night mode", "language": "Language", "belongsTo_type": "Belongs to (type)", "belongsTo_id": "Belongs to (ID)", "belongsTo": "Belongs to", "doku": "Documentation", "step": "Step", "phone_number": "Phone number", "template": "Template", "timezone": "Timezone", "critical": "Critical", "ok": "OK", "running": "Running", "runs_since": "Running since", "queued": "Queued", "criticalstates": "Critical States", "since": "Since", "state": "State", "actions": "Actions", "irrigate": "Irrigate", "duration": "Duration", "on": "On", "off": "Off", "just_fed": "Just fed", "just_irrigated": "Just irrigated", "add_weight": "Add weight", "active": "Aktiv", "copy_thresholds": "Copy thresholds", "daily": "Daily", "last_feeding": "Last feeding", "crickets": "Crickets", "mixed_fruits": "Fruits", "beetle_jelly": "BeetleJelly", "due": "Due", "overdue": "Overdue", "meal_type": "Meal type", "interval_days": "Interval in days", "interval": "Interval", "password": "Password", "action": "Action", "weight": "Weight", "no_data": "No Data", "overview": "Overview", "environment": "Environment", "now": "Now", "scheduled": "Scheduled", "sequence": "Sequence", "date": "Date", "weighprogression": "Weight progression", "from": "From", "tO": "To", "feedings": "Feedings", "temp_and_hum_history": "Temperature and Humidity History", "biography": "Biography", "bio_categories": "Biography categories", "icon": "Icon", "average": "Average", "min": "Minimum", "max": "Maximum", "during_day": "Daytime", "during_night": "Nighttime", "total": "Total", "infrastructure": "Infrastructure", "timeframe_start": "Timeframe from", "timeframe_end": "Timeframe to", "reference_value": "Reference value", "reference_value_duration_threshold_minutes": "Reference value undershot\/exceeded for (Minutes)", "for": "for", "minimum_timeout_minutes": "Timeout (minutes)", "emergency_stop": "Emergency stop", "increases": "Increases", "decreases": "Decreases", "personal_access_tokens": "Access Tokens", "expires": "Expires", "security": "Security", "general": "General", "component": "Component", "to": "To", "daily_reminders": "Daily Reminders", "yesterday": "Yesterday", "tomorrow": "Tomorrow", "today": "Today", "runonce": "Only run once", "intention": "Intention" }, "languages": { "german": "German", "english": "English" }, "menu": { "welcome": "Welcome", "dashboard": "Dashboard", "general": "General", "administration": "Administration", "create": "Create", "edit": "Edit", "delete": "Delete", "infrastructure": "Infrastructure", "help": "Help", "logout": "Log out", "animals": "Animals", "terraria": "Terraria", "users": "Users", "animal_feeding_types": "Food types", "logs": "Logs", "categories": "Categories" }, "messages": { "logical_sensor_thresholds": { "copy_warning": "All existing thresholds associated with the target sensor will be deleted." }, "users": { "setup_telegram_ok": "Telegram is set up.", "setup_telegram_err": "Telegram has not yet been set up.", "setup_telegram_description": "Please point your browser to <a href=\"https:\/\/web.telegram.org\/#\/im?p=@ciliatusbot\">Telegram Web<\/a> or use your smartphone to contact <b>@ciliatusbot<\/b> with your verification code below." }, "critical_state_generic": "Critical: {critical}_state", "critical_state_notification_logical_sensor": { "humidity_percent": "Critical: The sensor {logical}_sensor reports a humidity of {humidity}_percent%C.", "temperature_celsius": "Critical: The sensor {logical}_sensor reports a temperature of {temperature}_celsius\xB0C." }, "critical_state_recovery_notification_logical_sensor": { "humidity_percent": "OK: The sensor {logical}_sensor reports a humidity of {humidity}_percent%C.", "temperature_celsius": "OK: The sensor {logical}_sensor reports a temperature of {temperature}_celsius\xB0C." }, "critical_state_notification_controlunit": "Critical: The controlunit {controlunit} is not sending data.", "critical_state_recovery_notification_controlunit": "OK: The controlunit {controlunit} ist sending data again.", "daily": { "intro": "Daily reminders", "feedings_due": "Feedings due:", "weighings_due": "Weighings due:" } }, "product": { "name": "ciliatus" }, "setup": { "welcome": "Welcome to Ciliatus", "create_user": "Create your user", "done": "Done!", "what_now": "What now?", "login": "Log in", "tooltip_login": "Log in to Ciliatus with the user you just created.", "add_terrarium": "Create Terrarium", "tooltip_add_terrarium": "Create your first terrarium in Ciliatus.", "add_animal": "Create animal", "tooltip_add_animal": "Create your first animal and assign it to a terrarium.", "setup_telegram": "Setup Telegram", "tooltip_setup_telegram": "Learn how to configure Telegram with Ciliatus.", "setup_controlunit": "Create Controlunit", "tooltip_setup_controlunit": "Learn how to setup a controlunit to start feeding Ciliatus with sensor readings.", "err_completed": "Setup is already completed." }, "tooltips": { "ctrltoselect": "Ctrl-click to deselect", "active": "Active", "showondefaultdashboard": "Show on default dashboard", "autoirrigation": "Automatic irrigation (if available)", "sendnotificationsfor": "Send notifications for", "loadandrendergraph": "Collection data and rendering graph", "disables_option": "Disables \"{option}\"", "phone_number": "Mobile number", "contact_bot": "Contacting the bot", "wait_confirmation": "Waiting for confirmation", "set_state_to": "Set state of <b>{target}<\/b> to <b>{state}<\/b> for <b>{minutes} minutes<\/b>", "start_after_started": "Starts as soon as step <b>{id}<\/b> was started", "start_after_finished": "Starts as soon as step <b>{id}<\/b> finished", "sendnotifications": "Send notifications", "no_schedules": "No schedules", "runonce": "Run once", "heartbeat_critical": "Heartbeat is critical!", "copy_thresholds_warning": "All existing thresholds on the target sensor will be removed.", "animal_feeding_schedule_matrix": "This matrix contains all defined feeding schedules. A number in a column represents the schedule's interval in days.", "animal_weighing_schedule_matrix": "This matrix contains all defined weighing schedules. A number in a column represents the schedule's interval in days followed by the next due date.", "done": "Done", "skip": "Skip", "material_icons_list": "Visit <a href=\"https:\/\/material.io\/icons\/\">material.io<\/a> for a complete icon overview.", "no_data": "No data.", "connecting_to_server": "Connecting to Ciliatus Server. If this takes longer then a few seconds please check your internet connection.", "generic_components": { "about": "Generic components are components of a user defined type.", "type_about": "Generic component types define name, properties and possible states of a generic component. They are used as a template when creating a new generic component.", "property_templates": "Define properties for this generic component type. Each time you create a new component of this type you will be prompted to fill in these properties.", "state_templates": "Define possible states for a component of this type. When creating an action sequence you can chose a state from this list as a desired state.", "type_delete_warning": "When deleting a component type <strong>all components of this type<\/strong> will also be deleted." }, "minimum_timeout_minutes": "Defines the minimum timeout before the action sequence can be started by this trigger after the last time it was triggered.", "reference_value": "Reference value which will be compared to the sensor values.", "reference_value_duration_threshold_minutes": "Duration in minutes for which the sensor value has to be greater\/lower\/equal to the reference value before triggering the action sequence.", "emergency_stop": "Instantly stops all running action sequences and prohibits action sequences from starting.", "emergency_resume": "Revokes the emergency stop and allows action sequences to start.", "leave_empty_for_auto": "Leave empty for automatic", "intention_increase_decrease": "Defines whether the intention of this action sequence is to increase or decrease the sensor's readings" }, "units": { "years": "year|years", "months": "month|months", "days": "day|days", "hours": "hour|hours", "minutes": "minute|minutes", "seconds": "second|seconds", "temperature_celsius": "\xB0C", "humidity_percent": "%", "days_ago": "{val} days ago", "hours_ago": "{val} hours ago", "minutes_ago": "{val} minutes ago", "just_now": "just now", "lesser": "<", "greater": ">", "equals": "=" } } };

},{}],11:[function(require,module,exports){
'use strict';

var _vuePeity = require('vue-peity');

var _vuePeity2 = _interopRequireDefault(_vuePeity);

var _systemIndicator = require('./vue/system-indicator.vue');

var _systemIndicator2 = _interopRequireDefault(_systemIndicator);

var _dashboardWidget = require('./vue/dashboard-widget.vue');

var _dashboardWidget2 = _interopRequireDefault(_dashboardWidget);

var _googleGraph = require('./vue/google-graph.vue');

var _googleGraph2 = _interopRequireDefault(_googleGraph);

var _dygraphGraph = require('./vue/dygraph-graph.vue');

var _dygraphGraph2 = _interopRequireDefault(_dygraphGraph);

var _inlineGraph = require('./vue/inline-graph.vue');

var _inlineGraph2 = _interopRequireDefault(_inlineGraph);

var _animalsWidget = require('./vue/animals-widget.vue');

var _animalsWidget2 = _interopRequireDefault(_animalsWidget);

var _animal_feedingsWidget = require('./vue/animal_feedings-widget.vue');

var _animal_feedingsWidget2 = _interopRequireDefault(_animal_feedingsWidget);

var _animal_feeding_schedulesWidget = require('./vue/animal_feeding_schedules-widget.vue');

var _animal_feeding_schedulesWidget2 = _interopRequireDefault(_animal_feeding_schedulesWidget);

var _animal_feeding_schedulesMatrixWidget = require('./vue/animal_feeding_schedules-matrix-widget.vue');

var _animal_feeding_schedulesMatrixWidget2 = _interopRequireDefault(_animal_feeding_schedulesMatrixWidget);

var _animal_weighing_schedulesMatrixWidget = require('./vue/animal_weighing_schedules-matrix-widget.vue');

var _animal_weighing_schedulesMatrixWidget2 = _interopRequireDefault(_animal_weighing_schedulesMatrixWidget);

var _animal_weighingsWidget = require('./vue/animal_weighings-widget.vue');

var _animal_weighingsWidget2 = _interopRequireDefault(_animal_weighingsWidget);

var _animal_weighing_schedulesWidget = require('./vue/animal_weighing_schedules-widget.vue');

var _animal_weighing_schedulesWidget2 = _interopRequireDefault(_animal_weighing_schedulesWidget);

var _terrariaWidget = require('./vue/terraria-widget.vue');

var _terrariaWidget2 = _interopRequireDefault(_terrariaWidget);

var _terrariaOverviewWidget = require('./vue/terraria-overview-widget.vue');

var _terrariaOverviewWidget2 = _interopRequireDefault(_terrariaOverviewWidget);

var _controlunitWidget = require('./vue/controlunit-widget.vue');

var _controlunitWidget2 = _interopRequireDefault(_controlunitWidget);

var _controlunitsListWidget = require('./vue/controlunits-list-widget.vue');

var _controlunitsListWidget2 = _interopRequireDefault(_controlunitsListWidget);

var _filesWidget = require('./vue/files-widget.vue');

var _filesWidget2 = _interopRequireDefault(_filesWidget);

var _filesShowWidget = require('./vue/files-show-widget.vue');

var _filesShowWidget2 = _interopRequireDefault(_filesShowWidget);

var _action_sequencesWidget = require('./vue/action_sequences-widget.vue');

var _action_sequencesWidget2 = _interopRequireDefault(_action_sequencesWidget);

var _action_sequence_scheduleWidget = require('./vue/action_sequence_schedule-widget.vue');

var _action_sequence_scheduleWidget2 = _interopRequireDefault(_action_sequence_scheduleWidget);

var _pumpsWidget = require('./vue/pumps-widget.vue');

var _pumpsWidget2 = _interopRequireDefault(_pumpsWidget);

var _pumpsListWidget = require('./vue/pumps-list-widget.vue');

var _pumpsListWidget2 = _interopRequireDefault(_pumpsListWidget);

var _valvesWidget = require('./vue/valves-widget.vue');

var _valvesWidget2 = _interopRequireDefault(_valvesWidget);

var _valvesListWidget = require('./vue/valves-list-widget.vue');

var _valvesListWidget2 = _interopRequireDefault(_valvesListWidget);

var _physical_sensorsWidget = require('./vue/physical_sensors-widget.vue');

var _physical_sensorsWidget2 = _interopRequireDefault(_physical_sensorsWidget);

var _physical_sensorsListWidget = require('./vue/physical_sensors-list-widget.vue');

var _physical_sensorsListWidget2 = _interopRequireDefault(_physical_sensorsListWidget);

var _logical_sensorsWidget = require('./vue/logical_sensors-widget.vue');

var _logical_sensorsWidget2 = _interopRequireDefault(_logical_sensorsWidget);

var _logical_sensorsListWidget = require('./vue/logical_sensors-list-widget.vue');

var _logical_sensorsListWidget2 = _interopRequireDefault(_logical_sensorsListWidget);

var _generic_componentsWidget = require('./vue/generic_components-widget.vue');

var _generic_componentsWidget2 = _interopRequireDefault(_generic_componentsWidget);

var _generic_componentsListWidget = require('./vue/generic_components-list-widget.vue');

var _generic_componentsListWidget2 = _interopRequireDefault(_generic_componentsListWidget);

var _usersWidget = require('./vue/users-widget.vue');

var _usersWidget2 = _interopRequireDefault(_usersWidget);

var _biography_entriesWidget = require('./vue/biography_entries-widget.vue');

var _biography_entriesWidget2 = _interopRequireDefault(_biography_entriesWidget);

var _caresheetsWidget = require('./vue/caresheets-widget.vue');

var _caresheetsWidget2 = _interopRequireDefault(_caresheetsWidget);

var _logsWidget = require('./vue/logs-widget.vue');

var _logsWidget2 = _interopRequireDefault(_logsWidget);

var _componentsListWidget = require('./vue/components-list-widget.vue');

var _componentsListWidget2 = _interopRequireDefault(_componentsListWidget);

var _generic_component_type_createForm = require('./vue/generic_component_type_create-form.vue');

var _generic_component_type_createForm2 = _interopRequireDefault(_generic_component_type_createForm);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Vue = require('vue/dist/vue.js');


/*
import VueResource from 'vue-resource';
Vue.use(VueResource);
Vue.http.interceptors.push((request, next) => {
    request.headers['X-CSRF-TOKEN'] = Laravel.csrfToken;

    next();
});
*/

$.ajaxPrefilter(function (options) {
    if (!options.beforeSend) {
        options.beforeSend = function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', Laravel.csrfToken);
        };
    }
});

window.eventHubVue = new Vue({
    props: {
        globalLoadingBarCount: {
            type: Number,
            default: 0,
            required: false
        }
    },

    methods: {
        processStarted: function processStarted() {
            this.globalLoadingBarCount++;
            this.checkLoadingBarState();
        },

        processEnded: function processEnded() {
            this.globalLoadingBarCount--;
            this.checkLoadingBarState();
        },

        checkLoadingBarState: function checkLoadingBarState() {
            if (this.globalLoadingBarCount > 0) {
                $('#global-loading-bar').show();
            } else {
                $('#global-loading-bar').hide();
            }
        }
    }
});

var VueI18n = require('vue-i18n');
var locales = require("./lang.js");

Vue.use(VueI18n);

Vue.config.lang = $('body').data('lang');

Object.keys(locales).forEach(function (lang) {
    Vue.locale(lang, locales[lang]);
});

window.systemVue = new Vue({

    el: '#system-indicator',

    components: {
        'system-indicator': _systemIndicator2.default
    }

});

window.bodyVue = new Vue({

    el: '#content',

    data: {
        terraria: [],
        files: [],
        animals: []
    },

    props: {
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_type: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_id: {
            type: String,
            default: '',
            required: false
        }
    },

    components: {
        'dashboard-widget': _dashboardWidget2.default,
        'peity': _vuePeity2.default,
        'google-graph': _googleGraph2.default,
        'dygraph-graph': _dygraphGraph2.default,
        'inline-graph': _inlineGraph2.default,
        'animals-widget': _animalsWidget2.default,
        'animal_feedings-widget': _animal_feedingsWidget2.default,
        'animal_feeding_schedules-widget': _animal_feeding_schedulesWidget2.default,
        'animal_feeding_schedules-matrix-widget': _animal_feeding_schedulesMatrixWidget2.default,
        'animal_weighing_schedules-matrix-widget': _animal_weighing_schedulesMatrixWidget2.default,
        'animal_weighings-widget': _animal_weighingsWidget2.default,
        'animal_weighing_schedules-widget': _animal_weighing_schedulesWidget2.default,
        'terraria-widget': _terrariaWidget2.default,
        'terraria-overview-widget': _terrariaOverviewWidget2.default,
        'controlunits-widget': _controlunitWidget2.default,
        'controlunits-list-widget': _controlunitsListWidget2.default,
        'files-widget': _filesWidget2.default,
        'files-show-widget': _filesShowWidget2.default,
        'action_sequences-widget': _action_sequencesWidget2.default,
        'action_sequence_schedule-widget': _action_sequence_scheduleWidget2.default,
        'pumps-widget': _pumpsWidget2.default,
        'pumps-list-widget': _pumpsListWidget2.default,
        'valves-widget': _valvesWidget2.default,
        'valves-list-widget': _valvesListWidget2.default,
        'physical_sensors-widget': _physical_sensorsWidget2.default,
        'physical_sensors-list-widget': _physical_sensorsListWidget2.default,
        'logical_sensors-widget': _logical_sensorsWidget2.default,
        'logical_sensors-list-widget': _logical_sensorsListWidget2.default,
        'generic_components-widget': _generic_componentsWidget2.default,
        'generic_components-list-widget': _generic_componentsListWidget2.default,
        'users-widget': _usersWidget2.default,
        'biography_entries-widget': _biography_entriesWidget2.default,
        'caresheets-widget': _caresheetsWidget2.default,
        'logs-widget': _logsWidget2.default,
        'components-list-widget': _componentsListWidget2.default,

        'generic_component_type_create-form': _generic_component_type_createForm2.default
    }

});

},{"./lang.js":10,"./vue/action_sequence_schedule-widget.vue":12,"./vue/action_sequences-widget.vue":13,"./vue/animal_feeding_schedules-matrix-widget.vue":14,"./vue/animal_feeding_schedules-widget.vue":15,"./vue/animal_feedings-widget.vue":16,"./vue/animal_weighing_schedules-matrix-widget.vue":17,"./vue/animal_weighing_schedules-widget.vue":18,"./vue/animal_weighings-widget.vue":19,"./vue/animals-widget.vue":20,"./vue/biography_entries-widget.vue":21,"./vue/caresheets-widget.vue":22,"./vue/components-list-widget.vue":23,"./vue/controlunit-widget.vue":24,"./vue/controlunits-list-widget.vue":25,"./vue/dashboard-widget.vue":26,"./vue/dygraph-graph.vue":27,"./vue/files-show-widget.vue":28,"./vue/files-widget.vue":29,"./vue/generic_component_type_create-form.vue":30,"./vue/generic_components-list-widget.vue":31,"./vue/generic_components-widget.vue":32,"./vue/google-graph.vue":33,"./vue/inline-graph.vue":34,"./vue/logical_sensors-list-widget.vue":35,"./vue/logical_sensors-widget.vue":36,"./vue/logs-widget.vue":37,"./vue/physical_sensors-list-widget.vue":38,"./vue/physical_sensors-widget.vue":39,"./vue/pumps-list-widget.vue":40,"./vue/pumps-widget.vue":41,"./vue/system-indicator.vue":42,"./vue/terraria-overview-widget.vue":43,"./vue/terraria-widget.vue":44,"./vue/users-widget.vue":45,"./vue/valves-list-widget.vue":46,"./vue/valves-widget.vue":47,"vue-i18n":6,"vue-peity":7,"vue/dist/vue.js":9}],12:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            action_sequence_schedules: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        assId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: 'filter[last_finished_at]=nottoday',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(ass) {
            var item = null;
            this.action_sequence_schedules.forEach(function (data, index) {
                if (data.id === ass.action_sequence_schedule.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.action_sequence_schedules.push(ass.action_sequence_schedule);
            } else if (item !== null) {
                this.action_sequence_schedules.splice(item, 1, ass.action_sequence_schedule);
            }
        },

        delete: function _delete(ass) {
            var item = null;
            this.action_sequence_schedules.forEach(function (data, index) {
                if (data.id === ass.action_sequence_schedule_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.action_sequence_schedules.splice(item, 1);
            }
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: uri,
                method: 'GET',
                success: function success(data) {
                    if (that.assId !== '') {
                        that.action_sequence_schedules = [data.data];
                    } else {
                        that.action_sequence_schedules = data.data;
                    }

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('ActionSequenceScheduleUpdated', function (e) {
            _this.update(e);
        }).listen('ActionSequenceScheduleDeleted', function (e) {
            _this.delete(e);
        });

        var uri = '';
        if (this.assid === '') {
            uri = '/api/v1/action_sequence_schedules/?raw=true&' + this.sourceFilter;
        } else {
            uri = '/api/v1/action_sequence_schedules/' + this.assid;
        }

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h("div")}
__vue__options__.staticRenderFns = []
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-20", __vue__options__)
  } else {
    hotAPI.reload("data-v-20", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],13:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            action_sequences: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        action_sequenceId: {
            type: String,
            default: '',
            required: false
        },
        terrariumId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;
            this.action_sequences.forEach(function (data, index) {
                if (data.id === a.action_sequence.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.action_sequences.push(a.animal);
            } else if (item !== null) {
                this.action_sequences.splice(item, 1, a.animal);
            }
        },

        delete: function _delete(a) {
            var item = null;
            this.action_sequences.forEach(function (data, index) {
                if (data.id === a.action_sequences_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.action_sequences.splice(item, 1);
            }
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/action_sequences/' + that.action_sequenceId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    if (that.action_sequenceId !== '') {
                        that.action_sequences = [data.data];
                    } else {
                        that.action_sequences = data.data;
                    }

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }

    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('ActionSequenceUpdated', function (e) {
            _this.update(e);
        }).listen('ActionSequenceDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0),"\n        "+_vm._s(_vm.action_sequences.length)+" "+_vm._s(_vm.$tc("components.action_sequences", 2))+"\n    "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(_vm.$tc("components.action_sequences", 2))])," ",_vm._m(1)])," ",_vm._l((_vm.action_sequences),function(as){return _vm._h('div',[_vm._h('p',[_vm._h('a',{attrs:{"href":'/action_sequences/' + as.id + '/edit'}},[_vm._h('strong',[_vm._s(as.name)])])])," ",_vm._l((as.intentions),function(asi){return _vm._h('p',[_vm._m(2,true)," ",(asi.intention === 'increase')?_vm._h('span',[_vm._s(_vm.$t('labels.increases'))]):_vm._e()," ",(asi.intention === 'decrease')?_vm._h('span',[_vm._s(_vm.$t('labels.decreases'))]):_vm._e(),"\n\n                "+_vm._s(_vm.$t('labels.' + asi.type))+"\n\n                ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(asi.states.running),expression:"asi.states.running"}]},[_vm._h('span',{staticClass:"new badge",attrs:{"data-badge-caption":_vm.$t('labels.active')}})])])})," ",_vm._l((as.triggers),function(ast){return _vm._h('p',[_vm._m(3,true)," "+_vm._s(ast.logical_sensor.name)+" "+_vm._s(_vm.$t('units.' + ast.reference_value_comparison_type))+" "+_vm._s(ast.reference_value)+"\n                ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(ast.states.running),expression:"ast.states.running"}]},[_vm._h('span',{staticClass:"new badge",attrs:{"data-badge-caption":_vm.$t('labels.active')}})])])})," ",_vm._l((as.schedules),function(ass){return _vm._h('p',[_vm._m(4,true)," "+_vm._s(ass.timestamps.starts)+" ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(!ass.runonce),expression:"!ass.runonce"}]},[_vm._s(_vm.$t("labels.daily"))])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(ass.states.running),expression:"ass.states.running"}]},[_vm._h('span',{staticClass:"new badge",attrs:{"data-badge-caption":_vm.$t('labels.active')}})])])})])})])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/action_sequences/create?preset[terrarium]=' + _vm.terrariumId}},[_vm._s(_vm.$t("buttons.add"))])])," ",_vm._m(5)])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["playlist_play"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["explore"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["flare"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["schedule"])},function render () {var _vm=this;return _vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._h('i',{staticClass:"material-icons right"},["close"])])," ",_vm._h('p')])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-19", __vue__options__)
  } else {
    hotAPI.reload("data-v-19", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],14:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            feeding_types: [],
            animals: [],
            schedules: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        get_animal_feeding_schedules_of_type: function get_animal_feeding_schedules_of_type(animal_id, type) {
            return this.schedules.filter(function (el) {
                return el.type == type && el.animal.id == animal_id;
            });
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;

            $.ajax({
                url: '/api/v1/properties?filter[type]=AnimalFeedingType&raw=true',
                method: 'GET',
                success: function success(data) {
                    that.feeding_types = data.data;

                    $.ajax({
                        url: '/api/v1/animals?filter[death_date]=null&raw=true',
                        method: 'GET',
                        success: function success(data) {
                            that.animals = data.data;

                            $.ajax({
                                url: '/api/v1/animal_feeding_schedules?raw',
                                method: 'GET',
                                success: function success(data) {
                                    that.schedules = data.data;
                                    that.$nextTick(function () {
                                        $('.dropdown-button').dropdown();
                                    });
                                    window.eventHubVue.processEnded();
                                },
                                error: function error(_error) {
                                    console.log((0, _stringify2.default)(_error));
                                    window.eventHubVue.processEnded();
                                }
                            });
                            window.eventHubVue.processEnded();
                        },
                        error: function error(_error2) {
                            console.log((0, _stringify2.default)(_error2));
                            window.eventHubVue.processEnded();
                        }
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error3) {
                    console.log((0, _stringify2.default)(_error3));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight"},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{staticClass:"hide-on-med-and-down",attrs:{"data-field":"id"}},["\n                    "+_vm._s(_vm.$tc('components.animals', 1))+"\n                "])," ",_vm._l((_vm.feeding_types),function(type){return _vm._h('th',["\n                    "+_vm._s(type.name)+"\n                "])})])])," ",_vm._h('tbody',[_vm._l((_vm.animals),function(animal){return _vm._h('tr',[_vm._h('td',[_vm._h('a',{attrs:{"href":'/animals/' + animal.id}},[_vm._s(animal.display_name)])])," ",_vm._l((_vm.feeding_types),function(type){return _vm._h('td',[_vm._l((_vm.get_animal_feeding_schedules_of_type(animal.id, type.name)),function(schedule){return _vm._h('span',[_vm._h('a',{attrs:{"href":'/animals/' + animal.id + '/feeding_schedules/' + schedule.id + '/edit'}},[_vm._s(schedule.interval_days)])])})])})])})])])])])}
__vue__options__.staticRenderFns = []
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-9", __vue__options__)
  } else {
    hotAPI.reload("data-v-9", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],15:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            animal_feeding_schedules: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        animalId: {
            type: String,
            required: true
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;

            if (a.animal_feeding_schedule.animal.id !== this.animalId) {
                return;
            }

            this.animal_feeding_schedules.forEach(function (data, index) {
                if (data.id === a.animal_feeding_schedule.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animal_feeding_schedules.push(a.animal_feeding_schedule);
            } else if (item !== null) {
                this.animal_feeding_schedules.splice(item, 1, a.animal_feeding_schedule);
            }
        },

        delete: function _delete(a) {
            var item = null;
            this.animal_feeding_schedules.forEach(function (data, index) {
                if (data.id === a.animal_feeding_schedule_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animal_feeding_schedules.splice(item, 1);
            }
        },

        submit: function submit(e) {
            window.submit_form(e);
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/animals/' + that.animalId + '/feeding_schedules?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.animal_feeding_schedules = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }

    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('AnimalFeedingScheduleUpdated', function (e) {
            _this.update(e);
        }).listen('AnimalFeedingScheduleDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }

};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0),"\n                "+_vm._s(_vm.$tc("components.animal_feeding_schedules", 2))+"\n            "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(_vm.$tc("components.animal_feeding_schedules", 2))])," "])," ",_vm._l((_vm.animal_feeding_schedules),function(afs){return _vm._h('div',[_vm._h('p',[(afs.timestamps.next != null)?_vm._h('span',[_vm._s(afs.timestamps.next)+" - "]):_vm._e(),_vm._s(afs.type)+"\n                        ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(afs.due_days == 0),expression:"afs.due_days == 0"}]},[_vm._h('span',{staticClass:"new badge",attrs:{"data-badge-caption":_vm.$t('labels.due')}})])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(afs.due_days < 0),expression:"afs.due_days < 0"}]},[_vm._h('span',{staticClass:"new badge red",attrs:{"data-badge-caption":_vm.$t('labels.overdue')}})])])])})," ",(_vm.animal_feeding_schedules.length < 1)?_vm._h('div',[_vm._h('p',[_vm._s(_vm.$t('labels.no_data'))])]):_vm._e()])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/animals/' + _vm.animalId + '/feeding_schedules/create'}},[_vm._s(_vm.$t("buttons.add"))])," ",_vm._h('a',{attrs:{"href":'/animals/' + _vm.animalId + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",_vm._m(1)])])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["schedule"])},function render () {var _vm=this;return _vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._h('i',{staticClass:"material-icons right"},["close"])])," ",_vm._h('p')])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-8", __vue__options__)
  } else {
    hotAPI.reload("data-v-8", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],16:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            animal_feedings: [],
            animal_feeding_types: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        animalId: {
            type: String,
            required: true
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;
            if (a.animal_feeding.animal.id !== this.animalId) {
                return;
            }

            this.animal_feedings.forEach(function (data, index) {
                if (data.id === a.animal_feeding.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animal_feedings.push(a.animal_feeding);
            } else if (item !== null) {
                this.animal_feedings.splice(item, 1, a.animal_feeding);
            }
        },

        delete: function _delete(a) {
            var item = null;
            this.animal_feedings.forEach(function (data, index) {
                if (data.id === a.animal_feeding_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animal_feedings.splice(item, 1);
            }
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/animals/' + that.animalId + '/feedings?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.animal_feedings = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });

            window.eventHubVue.processStarted();
            $.ajax({
                url: '/api/v1/animals/feedings/types',
                method: 'GET',
                success: function success(data) {
                    that.animal_feeding_types = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function error(_error2) {
                    console.log((0, _stringify2.default)(_error2));
                    window.eventHubVue.processEnded();
                }
            });
        }

    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('AnimalFeedingUpdated', function (e) {
            _this.update(e);
        }).listen('AnimalFeedingDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }

};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"modal",staticStyle:{"min-height":"400px"},attrs:{"id":'modal_add_feeding_' + _vm.animalId}},[_vm._h('form',{attrs:{"action":'/api/v1/animals/' + _vm.animalId + '/feedings',"data-method":"POST","onsubmit":"window.submit_form"}},[_vm._h('div',{staticClass:"modal-content"},[_vm._h('h4',[_vm._s(_vm.$t("labels.just_fed"))])," ",_vm._h('p',[_vm._h('select',{attrs:{"name":"meal_type","id":"meal_type"}},[_vm._l((_vm.animal_feeding_types),function(ft){return _vm._h('option',{domProps:{"value":ft}},[_vm._s(ft)])})])," ",_vm._h('label',{attrs:{"for":"meal_type"}},[_vm._s(_vm.$t("labels.meal_type"))])])])," ",_vm._h('div',{staticClass:"modal-footer"},[_vm._h('button',{staticClass:"btn modal-action modal-close waves-effect waves-light",attrs:{"type":"submit"}},[_vm._s(_vm.$t("buttons.save"))+"\n                        ",_vm._m(0)])])])])," ",_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(1),"\n                "+_vm._s(_vm.$tc("components.animal_feedings", 2))+"\n            "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(_vm.$tc("components.animal_feedings", 2))])," ",_vm._m(2)])," ",_vm._l((_vm.animal_feedings),function(af){return _vm._h('div',[_vm._h('p',[(af.timestamps.created_diff.days > 1)?_vm._h('span',[_vm._s(_vm.$t('units.days_ago', {val: af.timestamps.created_diff.days}))]):_vm._e()," ",(af.timestamps.created_diff.days <= 1 && af.timestamps.created_diff.hours > 1)?_vm._h('span',[_vm._s(_vm.$t('units.hours_ago', {val: af.timestamps.created_diff.hours}))]):_vm._e()," ",(af.timestamps.created_diff.days <= 1 && af.timestamps.created_diff.hours <= 1)?_vm._h('span',[_vm._s(_vm.$t('units.just_now'))]):_vm._e()," ",_vm._h('span',[" - "+_vm._s(af.type)])])])})," ",(_vm.animal_feedings.length < 1)?_vm._h('div',[_vm._h('p',[_vm._s(_vm.$t('labels.no_data'))])]):_vm._e()])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'#modal_add_feeding_' + _vm.animalId,"onclick":'$(\'#modal_add_feeding_' + _vm.animalId + '\').modal(); $(\'#modal_add_feeding_' + _vm.animalId + ' select\').material_select(); $(\'#modal_add_feeding_' + _vm.animalId + '\').modal(\'open\');'}},[_vm._s(_vm.$t("buttons.add"))])])," ",_vm._m(3)])])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["send"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["local_dining"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._h('i',{staticClass:"material-icons right"},["close"])])," ",_vm._h('p')])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7", __vue__options__)
  } else {
    hotAPI.reload("data-v-7", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],17:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            animals: [],
            schedules: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        sourceFilter: {
            type: String,
            default: 'filter[death_date]=null',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        get_animal_weighing_schedules: function get_animal_weighing_schedules(animal_id) {
            return this.schedules.filter(function (el) {
                return el.animal.id == animal_id;
            });
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;

            $.ajax({
                url: '/api/v1/animals?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.animals = data.data;

                    $.ajax({
                        url: '/api/v1/animal_weighing_schedules?raw=true',
                        method: 'GET',
                        success: function success(data) {
                            that.schedules = data.data;
                            that.$nextTick(function () {
                                $('.dropdown-button').dropdown();
                            });
                            window.eventHubVue.processEnded();
                        },
                        error: function error(_error) {
                            console.log((0, _stringify2.default)(_error));
                            window.eventHubVue.processEnded();
                        }
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error2) {
                    console.log((0, _stringify2.default)(_error2));
                    window.eventHubVue.processEnded();
                }
            });

            window.eventHubVue.processEnded();
        }
    },

    created: function created() {
        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight"},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',["\n                    "+_vm._s(_vm.$tc('components.animals', 1))+"\n                "])," ",_vm._h('th',["\n                    "+_vm._s(_vm.$t('labels.scheduled'))+"\n                "])])])," ",_vm._h('tbody',[_vm._l((_vm.animals),function(animal){return _vm._h('tr',[_vm._h('td',[_vm._h('a',{attrs:{"href":'/animals/' + animal.id}},[_vm._s(animal.display_name)])])," ",_vm._h('td',[_vm._l((_vm.get_animal_weighing_schedules(animal.id)),function(schedule){return _vm._h('span',[_vm._h('a',{attrs:{"href":'/animals/' + animal.id + '/weighing_schedules/' + schedule.id + '/edit'}},["\n                            "+_vm._s(schedule.interval_days)+":\n                        "])," ",_vm._h('i',[_vm._s(schedule.timestamps.next)+" ("+_vm._s(schedule.due_days)+" "+_vm._s(_vm.$tc('units.days', schedule.due_days))+")"])])})])])})])])])])}
__vue__options__.staticRenderFns = []
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-10", __vue__options__)
  } else {
    hotAPI.reload("data-v-10", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],18:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            animal_weighing_schedules: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        animalId: {
            type: String,
            required: true
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;

            if (a.animal_weighing_schedule.animal.id !== this.animalId) {
                return;
            }

            this.animal_weighing_schedules.forEach(function (data, index) {
                if (data.id === a.animal_weighing_schedule.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animal_weighing_schedules.push(a.animal_weighing_schedule);
            } else if (item !== null) {
                this.animal_weighing_schedules.splice(item, 1, a.animal_weighing_schedule);
            }
        },

        delete: function _delete(a) {
            var item = null;
            this.animal_weighing_schedules.forEach(function (data, index) {
                if (data.id === a.animal_weighing_schedule_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animal_weighing_schedules.splice(item, 1);
            }
        },

        submit: function submit(e) {
            window.submit_form(e);
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/animals/' + that.animalId + '/weighing_schedules?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.animal_weighing_schedules = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('AnimalWeighingScheduleUpdated', function (e) {
            _this.update(e);
        }).listen('AnimalWeighingScheduleDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }

};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0),"\n                "+_vm._s(_vm.$tc("components.animal_weighing_schedules", 2))+"\n            "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(_vm.$tc("components.animal_weighing_schedules", 2))])," "])," ",_vm._l((_vm.animal_weighing_schedules),function(aws){return _vm._h('div',[_vm._h('p',[_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(aws.timestamps.next != null),expression:"aws.timestamps.next != null"}]},[_vm._s(aws.timestamps.next)])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(aws.timestamps.next == null),expression:"aws.timestamps.next == null"}]},[_vm._s(_vm.$t("labels.now"))])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(aws.due_days == 0),expression:"aws.due_days == 0"}]},[_vm._h('span',{staticClass:"new badge",attrs:{"data-badge-caption":_vm.$t('labels.due')}})])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(aws.due_days < 0),expression:"aws.due_days < 0"}]},[_vm._h('span',{staticClass:"new badge red",attrs:{"data-badge-caption":_vm.$t('labels.overdue')}})])])])})," ",(_vm.animal_weighing_schedules.length < 1)?_vm._h('div',[_vm._h('p',[_vm._s(_vm.$t('labels.no_data'))])]):_vm._e()])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/animals/' + _vm.animalId + '/weighing_schedules/create'}},[_vm._s(_vm.$t("buttons.add"))])," ",_vm._h('a',{attrs:{"href":'/animals/' + _vm.animalId + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",_vm._m(1)])])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["schedule"])},function render () {var _vm=this;return _vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._h('i',{staticClass:"material-icons right"},["close"])])," ",_vm._h('p')])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-12", __vue__options__)
  } else {
    hotAPI.reload("data-v-12", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],19:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            animal_weighings: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        animalId: {
            type: String,
            required: true
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;
            if (a.animal_weighing.animal.id !== this.animalId) {
                return;
            }

            this.animal_weighings.forEach(function (data, index) {
                if (data.id === a.animal_weighing.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animal_weighings.push(a.animal_weighing);
            } else if (item !== null) {
                this.animal_weighings.splice(item, 1, a.animal_weighing);
            }
        },

        delete: function _delete(a) {
            var item = null;
            this.animal_weighings.forEach(function (data, index) {
                if (data.id === a.animal_weighing_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animal_weighings.splice(item, 1);
            }
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/animals/' + that.animalId + '/weighings?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.animal_weighings = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }

    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('AnimalWeighingUpdated', function (e) {
            _this.update(e);
        }).listen('AnimalWeighingDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }

};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"modal",attrs:{"id":'modal_add_weighing_' + _vm.animalId}},[_vm._h('form',{attrs:{"action":'/api/v1/animals/' + _vm.animalId + '/weighings',"data-method":"POST","onsubmit":"window.submit_form"}},[_vm._h('div',{staticClass:"modal-content"},[_vm._h('h4',[_vm._s(_vm.$t("labels.add_weight"))])," ",_vm._h('p',[_vm._h('input',{attrs:{"type":"text","name":"weight","id":"weight","placeholder":_vm.$t('labels.weight'),"value":""}})," ",_vm._h('label',{attrs:{"for":"weight"}},[_vm._s(_vm.$t("labels.weight"))])])])," ",_vm._h('div',{staticClass:"modal-footer"},[_vm._h('button',{staticClass:"btn modal-action modal-close waves-effect waves-light",attrs:{"type":"submit"}},[_vm._s(_vm.$t("buttons.save"))+"\n                        ",_vm._m(0)])])])])," ",_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(1),"\n                "+_vm._s(_vm.$tc("components.animal_weighings", 2))+"\n            "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(_vm.$tc("components.animal_weighings", 2))])," ",_vm._m(2)])," ",_vm._l((_vm.animal_weighings),function(af){return _vm._h('div',[_vm._h('p',[(af.timestamps.created_diff.days > 1)?_vm._h('span',[_vm._s(_vm.$t('units.days_ago', {val: af.timestamps.created_diff.days}))]):_vm._e()," ",(af.timestamps.created_diff.days <= 1 && af.timestamps.created_diff.hours > 1)?_vm._h('span',[_vm._s(_vm.$t('units.hours_ago', {val: af.timestamps.created_diff.hours}))]):_vm._e()," ",(af.timestamps.created_diff.days <= 1 && af.timestamps.created_diff.hours <= 1)?_vm._h('span',[_vm._s(_vm.$t('units.just_now'))]):_vm._e()," ",_vm._h('span',[" - "+_vm._s(af.amount)+"g"])])])})," ",(_vm.animal_weighings.length < 1)?_vm._h('div',[_vm._h('p',[_vm._s(_vm.$t('labels.no_data'))])]):_vm._e()])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'#modal_add_weighing_' + _vm.animalId,"onclick":'$(\'#modal_add_weighing_' + _vm.animalId + '\').modal(); $(\'#modal_add_weighing_' + _vm.animalId + ' select\').material_select(); $(\'#modal_add_weighing_' + _vm.animalId + '\').modal(\'open\');'}},[_vm._s(_vm.$t("buttons.add"))])])," ",_vm._m(3)])])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["send"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["vertical_align_bottom"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._h('i',{staticClass:"material-icons right"},["close"])])," ",_vm._h('p')])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-11", __vue__options__)
  } else {
    hotAPI.reload("data-v-11", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],20:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            animals: [],
            feeding_types: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        animalId: {
            type: String,
            default: null,
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'animals-masonry-grid',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;
            this.animals.forEach(function (data, index) {
                if (data.id === a.animal.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animals.push(a.animal);
            } else if (item !== null) {
                this.animals.splice(item, 1, a.animal);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        delete: function _delete(a) {
            var item = null;
            this.animals.forEach(function (data, index) {
                if (data.id === a.animal_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animals.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        submit: function submit(e) {
            window.submit_form(e);
        },

        load_data: function load_data() {
            var that = this;

            var source_url = '';
            if (this.animalId !== null) {
                source_url = '/api/v1/animals/' + this.animalId;
            } else {
                source_url = '/api/v1/animals/?order[death_date]=asc&order[display_name]=asc&raw=true&' + this.sourceFilter;
            }

            window.eventHubVue.processStarted();
            $.ajax({
                url: source_url,
                method: 'GET',
                success: function success(data) {
                    if (that.animalId !== null) {
                        that.animals = [data.data];
                    } else {
                        that.animals = data.data;
                    }

                    that.$nextTick(function () {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col'
                        });
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });

            window.eventHubVue.processStarted();
            $.ajax({
                url: '/api/v1/properties?filter[type]=AnimalFeedingType&raw=true',
                method: 'GET',
                success: function success(data) {
                    that.feeding_types = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function error(_error2) {
                    console.log((0, _stringify2.default)(_error2));
                    window.eventHubVue.processEnded();
                }
            });
        }

    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('AnimalUpdated', function (e) {
            _this.update(e);
        }).listen('AnimalDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }

};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:[_vm.containerClasses, 'masonry-grid'],attrs:{"id":_vm.containerId}},[_vm._l((_vm.animals),function(animal){return _vm._h('div',[_vm._h('div',{staticClass:"modal",attrs:{"id":'modal_just_fed_' + animal.id}},[_vm._h('form',{attrs:{"action":'/api/v1/animals/' + animal.id + '/feedings',"data-method":"POST"},on:{"submit":_vm.submit}},[_vm._h('div',{staticClass:"modal-content",staticStyle:{"min-height":"300px"}},[_vm._h('h4',[_vm._s(_vm.$t("labels.just_fed"))])," ",_vm._h('p',[_vm._h('select',{attrs:{"name":"meal_type","id":"meal_type"}},[_vm._l((_vm.feeding_types),function(ft){return _vm._h('option',{domProps:{"value":ft.name}},[_vm._s(ft.name)])})])," ",_vm._h('label',{attrs:{"for":"meal_type"}},[_vm._s(_vm.$t("labels.meal_type"))])])])," ",_vm._h('div',{staticClass:"modal-footer"},[_vm._h('button',{staticClass:"btn modal-action modal-close waves-effect waves-light",attrs:{"type":"submit"}},[_vm._s(_vm.$t("buttons.save"))+"\n                        ",_vm._m(0,true)])])])])," ",_vm._h('div',{staticClass:"modal",attrs:{"id":'modal_add_weight_' + animal.id}},[_vm._h('form',{attrs:{"action":'/api/v1/animals/' + animal.id + '/weighings',"data-method":"POST"},on:{"submit":_vm.submit}},[_vm._h('div',{staticClass:"modal-content"},[_vm._h('h4',[_vm._s(_vm.$t("labels.add_weight"))])," ",_vm._h('p',[_vm._h('input',{attrs:{"name":"weight","id":"weight","placeholder":_vm.$t('labels.weight')+ '/g'}})," ",_vm._h('label',{attrs:{"for":"weight"}},[_vm._s(_vm.$t("labels.weight"))+"/g"])])])," ",_vm._h('div',{staticClass:"modal-footer"},[_vm._h('button',{staticClass:"btn modal-action modal-close waves-effect waves-light",attrs:{"type":"submit"}},[_vm._s(_vm.$t("buttons.save"))+"\n                        ",_vm._m(1,true)])])])])," ",_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-image waves-effect waves-block waves-light terrarium-card-image",class:animal.default_background_filepath ? '' : 'teal lighten-1',style:(animal.default_background_filepath ? 'background-image: url(\'' + animal.default_background_filepath + '\');' : '')})," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(animal.display_name)+" "])," ",(!animal.death_date)?_vm._h('i',{staticClass:"material-icons right"},["more_vert"]):_vm._e()])," ",_vm._h('p',[_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(animal.latin_name),expression:"animal.latin_name"}]},[_vm._s(animal.latin_name)])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(animal.common_name && !animal.latin_name),expression:"animal.common_name && !animal.latin_name"}]},[_vm._s(animal.common_name)])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(animal.birth_date || animal.death_date),expression:"animal.birth_date || animal.death_date"}]},[", "+_vm._s(animal.age_value)+" "+_vm._s(_vm.$tc("units." + animal.age_unit, animal.age_value))])," ",(animal.last_feeding && !animal.death_date)?_vm._h('span',[_vm._m(2,true)," ",_vm._m(3,true)," ",(animal.last_feeding.timestamps.created_diff.days == 0)?_vm._h('span',[(animal.last_feeding.timestamps.created_diff.is_today)?_vm._h('span',["\n                                    "+_vm._s(_vm.$t("labels.today"))+":\n                                "]):_vm._h('span',["\n                                    "+_vm._s(_vm.$t("labels.yesterday"))+":\n                                "])," "]):_vm._h('span',["\n                                "+_vm._s(_vm.$t('units.days_ago', {val: animal.last_feeding.timestamps.created_diff.days}))+":\n                            "])," ","\n                            "+_vm._s(animal.last_feeding.name)+"\n                        "]):_vm._e()," ",_vm._m(4,true)])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/animals/' + animal.id}},[_vm._s(_vm.$t("buttons.details"))])," ",_vm._h('a',{attrs:{"href":'/animals/' + animal.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",(!animal.death_date)?_vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title"},[_vm._s(_vm.$tc("components.terraria", 1)),_vm._m(5,true)])," ",_vm._h('p',[(animal.terrarium)?_vm._h('a',{attrs:{"href":'/terraria/' + animal.terrarium.id}},[_vm._s(animal.terrarium.display_name)]):_vm._e()])," ",_vm._h('span',{staticClass:"card-title"},[_vm._s(_vm.$t("labels.just_fed"))])," ",_vm._h('p',[_vm._h('a',{staticClass:"waves-effect waves-teal btn",attrs:{"href":'#modal_just_fed_' + animal.id,"onclick":'$(\'#modal_just_fed_' + animal.id + '\').modal(); $(\'#modal_just_fed_' + animal.id + ' select\').material_select(); $(\'#modal_just_fed_' + animal.id + '\').modal(\'open\');'}},[_vm._s(_vm.$t("labels.just_fed"))])])," ",_vm._h('p',[_vm._h('a',{staticClass:"waves-effect waves-teal btn",attrs:{"href":'#modal_add_weight_' + animal.id,"onclick":'$(\'#modal_add_weight_' + animal.id + '\').modal(); $(\'#modal_add_weight_' + animal.id + ' select\').material_select(); $(\'#modal_add_weight_' + animal.id + '\').modal(\'open\');'}},[_vm._s(_vm.$t("labels.add_weight"))])])]):_vm._e()])])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["send"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["send"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons tiny"},["local_dining"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["close"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-6", __vue__options__)
  } else {
    hotAPI.reload("data-v-6", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],21:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            entries: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        belongsToType: {
            type: String,
            default: null,
            required: false
        },
        belongsToId: {
            type: String,
            default: null,
            required: false
        },
        entryId: {
            type: String,
            default: null,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;
            this.entries.forEach(function (data, index) {
                if (data.id === a.entry.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.entries.push(a.animal);
            } else if (item !== null) {
                this.entries.splice(item, 1, a.entry);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        delete: function _delete(a) {
            var item = null;
            this.entries.forEach(function (data, index) {
                if (data.id === a.entry_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.entries.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('.tooltipped').tooltip({ delay: 50 });
        },

        load_data: function load_data() {
            var that = this;

            var source_url = '';
            if (this.entryId !== null) {
                source_url = '/api/v1/biography_entries/' + this.entryId;
            } else {
                source_url = '/api/v1/biography_entries/?order[created_at]=desc&filter[belongsTo_type]=' + this.belongsToType + '&filter[belongsTo_id]=' + this.belongsToId + '&raw=true';
            }

            window.eventHubVue.processStarted();
            $.ajax({
                url: source_url,
                method: 'GET',
                success: function success(data) {
                    if (that.entryId !== null) {
                        that.entries = [data.data];
                    } else {
                        that.entries = data.data;
                    }

                    that.$nextTick(function () {
                        that.refresh_grid();
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }

    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('BiographyEntryUpdated', function (e) {
            _this.update(e);
        }).listen('BiographyEntryDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }

};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:_vm.containerClasses,attrs:{"id":_vm.containerId}},[_vm._h('div',{staticClass:"timeline"},[_vm._l((_vm.entries),function(entry){return _vm._h('div',{staticClass:"timeline-event"},[_vm._h('div',{staticClass:"timeline-date"},[_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(entry.timestamps.created_diff.days > 0),expression:"entry.timestamps.created_diff.days > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":entry.timestamps.created}},["\n                            "+_vm._s(_vm.$t('units.days_ago', {val: entry.timestamps.created_diff.days}))+"\n                "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(entry.timestamps.created_diff.days < 1 &&
                              entry.timestamps.created_diff.hours > 0),expression:"entry.timestamps.created_diff.days < 1 &&\n                              entry.timestamps.created_diff.hours > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":entry.timestamps.created}},["\n                            "+_vm._s(_vm.$t('units.hours_ago', {val: entry.timestamps.created_diff.hours}))+"\n                "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(entry.timestamps.created_diff.days < 1 &&
                              entry.timestamps.created_diff.hours < 1 &&
                              entry.timestamps.created_diff.minutes > 1),expression:"entry.timestamps.created_diff.days < 1 &&\n                              entry.timestamps.created_diff.hours < 1 &&\n                              entry.timestamps.created_diff.minutes > 1"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":entry.timestamps.created}},["\n                            "+_vm._s(_vm.$t('units.minutes_ago', {val: entry.timestamps.created_diff.minutes}))+"\n                "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(entry.timestamps.created_diff.days < 1 &&
                              entry.timestamps.created_diff.hours < 1 &&
                              entry.timestamps.created_diff.minutes < 2),expression:"entry.timestamps.created_diff.days < 1 &&\n                              entry.timestamps.created_diff.hours < 1 &&\n                              entry.timestamps.created_diff.minutes < 2"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":entry.timestamps.created}},["\n                            "+_vm._s(_vm.$t('units.just_now'))+"\n                "])])," ",_vm._h('div',{staticClass:"card timeline-content"},[_vm._h('div',{staticClass:"card-content"},[_vm._h('h5',[_vm._s(entry.title)])," ",_vm._h('p',{domProps:{"innerHTML":_vm._s(entry.text)}})])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/biography_entries/' + entry.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])])," ",_vm._h('div',{staticClass:"timeline-badge teal darken-2 white-text"},[(entry.category)?_vm._h('i',{staticClass:"material-icons tooltipped",attrs:{"data-position":"top","data-delay":"50","data-tooltip":entry.category.name}},["\n                    "+_vm._s(entry.category.icon)+"\n                "]):_vm._e()])])})])])}
__vue__options__.staticRenderFns = []
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-32", __vue__options__)
  } else {
    hotAPI.reload("data-v-32", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],22:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            caresheets: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        belongsToType: {
            type: String,
            default: 'Animal',
            required: false
        },
        belongsToId: {
            type: String,
            default: null,
            required: false
        },
        caresheetId: {
            type: String,
            default: null,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;
            this.caresheets.forEach(function (data, index) {
                if (data.id === a.caresheet.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.caresheets.push(a.caresheet);
            } else if (item !== null) {
                this.caresheets.splice(item, 1, a.caresheet);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        delete: function _delete(a) {
            var item = null;
            this.caresheets.forEach(function (data, index) {
                if (data.id === a.caresheet_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.caresheets.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('.tooltipped').tooltip({ delay: 50 });
        },

        load_data: function load_data() {
            var that = this;

            var source_url = '';
            if (this.caresheetId !== null) {
                source_url = '/api/v1/animals/caresheets/' + this.caresheetId;
            } else {
                source_url = '/api/v1/animals/' + this.belongsToId + '/caresheets/?order[created_at]=desc&filter[belongsTo_type]=' + this.belongsToType + '&filter[belongsTo_id]=' + this.belongsToId + '&raw=true';
            }

            window.eventHubVue.processStarted();
            $.ajax({
                url: source_url,
                method: 'GET',
                success: function success(data) {
                    if (that.caresheetId !== null) {
                        that.caresheets = [data.data];
                    } else {
                        that.caresheets = data.data;
                    }

                    that.$nextTick(function () {
                        that.refresh_grid();
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }

    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('BiographyEntryUpdated', function (e) {
            _this.update(e);
        }).listen('BiographyEntryDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }

};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:_vm.containerClasses,attrs:{"id":_vm.containerId}},[_vm._h('div',{staticClass:"timeline"},[_vm._l((_vm.caresheets),function(caresheet){return _vm._h('div',{staticClass:"timeline-event"},[_vm._h('div',{staticClass:"timeline-date"},[_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(caresheet.timestamps.created_diff.days > 0),expression:"caresheet.timestamps.created_diff.days > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":caresheet.timestamps.created}},["\n                            "+_vm._s(_vm.$t('units.days_ago', {val: caresheet.timestamps.created_diff.days}))+"\n                "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(caresheet.timestamps.created_diff.days < 1 &&
                              caresheet.timestamps.created_diff.hours > 0),expression:"caresheet.timestamps.created_diff.days < 1 &&\n                              caresheet.timestamps.created_diff.hours > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":caresheet.timestamps.created}},["\n                            "+_vm._s(_vm.$t('units.hours_ago', {val: caresheet.timestamps.created_diff.hours}))+"\n                "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(caresheet.timestamps.created_diff.days < 1 &&
                              caresheet.timestamps.created_diff.hours < 1 &&
                              caresheet.timestamps.created_diff.minutes > 1),expression:"caresheet.timestamps.created_diff.days < 1 &&\n                              caresheet.timestamps.created_diff.hours < 1 &&\n                              caresheet.timestamps.created_diff.minutes > 1"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":caresheet.timestamps.created}},["\n                            "+_vm._s(_vm.$t('units.minutes_ago', {val: caresheet.timestamps.created_diff.minutes}))+"\n                "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(caresheet.timestamps.created_diff.days < 1 &&
                              caresheet.timestamps.created_diff.hours < 1 &&
                              caresheet.timestamps.created_diff.minutes < 2),expression:"caresheet.timestamps.created_diff.days < 1 &&\n                              caresheet.timestamps.created_diff.hours < 1 &&\n                              caresheet.timestamps.created_diff.minutes < 2"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":caresheet.timestamps.created}},["\n                            "+_vm._s(_vm.$t('units.just_now'))+"\n                "])])," ",_vm._h('div',{staticClass:"card timeline-content"},[_vm._h('div',{staticClass:"card-content"},[_vm._h('h5',[_vm._s(caresheet.title)])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/animals/' + _vm.belongsToId + '/caresheets/' + caresheet.id}},[_vm._s(_vm.$t("buttons.details"))])," ",_vm._h('a',{attrs:{"href":'/animals/' + _vm.belongsToId + '/caresheets/' + caresheet.id + '/delete'}},[_vm._s(_vm.$t("buttons.delete"))])])])," ",_vm._m(0,true)])})])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('div',{staticClass:"timeline-badge teal darken-2 white-text"},[_vm._h('i',{staticClass:"material-icons"},["content_paste"])])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-33", __vue__options__)
  } else {
    hotAPI.reload("data-v-33", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],23:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            components: [],
            meta: [],
            filter: {
                name: '',
                'controlunit.name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        };
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        sourceApiBaseUrl: {
            type: String,
            required: true
        },
        refreshTimeoutSeconds: {
            type: Number,
            default: 60,
            required: false
        }
    },

    methods: {
        update: function update(e) {
            var item = null;
            var component = this.get_component_from_event(e);

            this.components.forEach(function (data, index) {
                if (data.id === component.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.components.splice(item, 1, component);
            }
        },

        delete: function _delete(e) {
            var item = null;
            var component = this.get_component_from_event(e);

            this.components.forEach(function (data, index) {
                if (data.id === component.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.components.splice(item, 1);
            }
        },

        get_component_from_event: function get_component_from_event(e) {
            var component_types = ['physical_sensor', 'logical_sensor', 'valve', 'pump', 'generic_component'];

            var component = null;

            component_types.forEach(function (item) {
                if (e[item] !== undefined) {
                    component = e[item];
                    return;
                }
            });

            return component;
        },

        set_order: function set_order(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                } else {
                    this.order.direction = 'asc';
                }
            } else {
                this.order.field = field;
            }

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function set_filter() {
            this.filter_string = '&';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function set_page(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/' + that.sourceApiBaseUrl + '?' + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.meta = data.meta;
                    that.components = data.data;
                    that.$nextTick(function () {
                        $('table.collapsible').collapsibletable();
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('PhysicalSensorUpdated', function (e) {
            _this.update(e);
        }).listen('PhysicalSensorDeleted', function (e) {
            _this.delete(e);
        }).listen('LogicalSensorUpdated', function (e) {
            _this.update(e);
        }).listen('LogicalSensorDeleted', function (e) {
            _this.delete(e);
        }).listen('ValveUpdated', function (e) {
            _this.update(e);
        }).listen('ValveDeleted', function (e) {
            _this.delete(e);
        }).listen('PumpUpdated', function (e) {
            _this.update(e);
        }).listen('PumpDeleted', function (e) {
            _this.delete(e);
        }).listen('GenericComponentUpdated', function (e) {
            _this.update(e);
        }).listen('GenericComponentDeleted', function (e) {
            _this.delete(e);
        });

        this.set_filter();
        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight collapsible",attrs:{"data-collapsible":"expandable"}},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{attrs:{"data-field":"name"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('name')}}},[_vm._s(_vm.$t('labels.name'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'asc'),expression:"order.field == 'name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'desc'),expression:"order.field == 'name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.name),expression:"filter.name"}],attrs:{"id":"filter_name","type":"text"},domProps:{"value":_vm._s(_vm.filter.name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.name=$event.target.value}}})," ",_vm._m(0)])])," ",_vm._h('th',{staticClass:"hide-on-small-only",attrs:{"data-field":"controlunit"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('controlunit')}}},[_vm._s(_vm.$tc('components.controlunit', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'asc'),expression:"order.field == 'controlunit' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'desc'),expression:"order.field == 'controlunit' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['controlunit.name']),expression:"filter['controlunit.name']"}],attrs:{"id":"filter_controlunit","type":"text"},domProps:{"value":_vm._s(_vm.filter['controlunit.name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'controlunit.name';if (!Array.isArray($$exp)){_vm.filter['controlunit.name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(1)])])," ",_vm._m(2)])])," ",_vm._l((_vm.components),function(component){return [_vm._h('tbody',[_vm._h('tr',{staticClass:"collapsible-header"},[_vm._h('td',[_vm._h('span',[_vm._h('i',{staticClass:"material-icons"},[_vm._s(component.icon)])," ",_vm._h('a',{attrs:{"href":component.url}},[_vm._s(component.name)])])])," ",_vm._h('td',{staticClass:"hide-on-small-only"},[(component.controlunit)?_vm._h('span',[_vm._m(3,true)," ",_vm._h('a',{attrs:{"href":'/controlunits/' + component.controlunit.id}},[_vm._s(component.controlunit.name)])]):_vm._e()])," ",_vm._h('td',[_vm._h('span',[_vm._h('a',{staticClass:"waves-effect waves-light btn-small",attrs:{"href":component.url + '/edit'}},[_vm._m(4,true)])])])])," ",_vm._m(5,true)])]})])])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_name"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_controlunit"}},["Filter"])},function render () {var _vm=this;return _vm._h('th',{staticStyle:{"width":"40px"}})},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["developer_board"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["edit"])},function render () {var _vm=this;return _vm._h('tr',{staticClass:"collapsible-body"},[_vm._h('td',{attrs:{"colspan":"3"}})])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-35", __vue__options__)
  } else {
    hotAPI.reload("data-v-35", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],24:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            controlunits: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        controlunitId: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'valves-masonry-grid',
            required: false
        }
    },

    methods: {
        update: function update(cu) {
            var item = null;
            this.controlunits.forEach(function (data, index) {
                if (data.id === cu.controlunit.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.controlunits.push(cu.controlunit);
            } else if (item !== null) {
                this.controlunits.splice(item, 1, cu.controlunit);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        delete: function _delete(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.controlunits.forEach(function (data, index) {
                if (data.id === cu.controlunit_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.controlunits.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
            $('.tooltipped').tooltip({ delay: 50 });
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/controlunits/' + that.controlunitId + '?raw=true',
                method: 'GET',
                success: function success(data) {
                    if (that.controlunitId !== '') {
                        that.controlunits = [data.data];
                    } else {
                        that.controlunits = data.data;
                    }

                    that.$nextTick(function () {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col'
                        });
                        $('.tooltipped').tooltip({ delay: 50 });
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('ControlunitUpdated', function (e) {
            _this.update(e);
        }).listen('ControlunitDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:[_vm.containerClasses, 'masonry-grid'],attrs:{"id":_vm.containerId}},[_vm._l((_vm.controlunits),function(controlunit){return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0,true),"\n                    "+_vm._s(_vm.$tc("components.controlunits", 2))+"\n                "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(controlunit.name)])," ",_vm._m(1,true)])," ",_vm._h('p',["\n                        "+_vm._s(_vm.$t('labels.last_heartbeat'))+":\n                        "," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(controlunit.timestamps.last_heartbeat_diff.days > 0),expression:"controlunit.timestamps.last_heartbeat_diff.days > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":controlunit.timestamps.last_heartbeat}},["\n                            "+_vm._s(_vm.$t('units.days_ago', {val: controlunit.timestamps.last_heartbeat_diff.days}))+"\n                        "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(controlunit.timestamps.last_heartbeat_diff.days < 1 &&
                                      controlunit.timestamps.last_heartbeat_diff.hours > 0),expression:"controlunit.timestamps.last_heartbeat_diff.days < 1 &&\n                                      controlunit.timestamps.last_heartbeat_diff.hours > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":controlunit.timestamps.last_heartbeat}},["\n                            "+_vm._s(_vm.$t('units.hours_ago', {val: controlunit.timestamps.last_heartbeat_diff.hours}))+"\n                        "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(controlunit.timestamps.last_heartbeat_diff.days < 1 &&
                                      controlunit.timestamps.last_heartbeat_diff.hours < 1 &&
                                      controlunit.timestamps.last_heartbeat_diff.minutes > 1),expression:"controlunit.timestamps.last_heartbeat_diff.days < 1 &&\n                                      controlunit.timestamps.last_heartbeat_diff.hours < 1 &&\n                                      controlunit.timestamps.last_heartbeat_diff.minutes > 1"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":controlunit.timestamps.last_heartbeat}},["\n                            "+_vm._s(_vm.$t('units.minutes_ago', {val: controlunit.timestamps.last_heartbeat_diff.minutes}))+"\n                        "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(controlunit.timestamps.last_heartbeat_diff.days < 1 &&
                                      controlunit.timestamps.last_heartbeat_diff.hours < 1 &&
                                      controlunit.timestamps.last_heartbeat_diff.minutes < 2),expression:"controlunit.timestamps.last_heartbeat_diff.days < 1 &&\n                                      controlunit.timestamps.last_heartbeat_diff.hours < 1 &&\n                                      controlunit.timestamps.last_heartbeat_diff.minutes < 2"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":controlunit.timestamps.last_heartbeat}},["\n                            "+_vm._s(_vm.$t('units.just_now'))+"\n                        "])," ",_vm._m(2,true)])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/controlunits/' + controlunit.id}},[_vm._s(_vm.$t("buttons.details"))])," ",_vm._h('a',{attrs:{"href":'/controlunits/' + controlunit.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",_vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title"},[_vm._s(_vm.$tc("components.physical_sensors", 2)),_vm._m(3,true)])," ",_vm._l((controlunit.physical_sensors),function(physical_sensor){return _vm._h('p',[_vm._h('a',{attrs:{"href":'/physical_sensors/' + physical_sensor.id}},[_vm._s(physical_sensor.name)])])})])])])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["developer_board"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["close"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-15", __vue__options__)
  } else {
    hotAPI.reload("data-v-15", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],25:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            controlunits: [],
            meta: [],
            filter: {
                name: '',
                model: '',
                'controlunit.name': '',
                'terrarium.display_name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        };
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        refreshTimeoutSeconds: {
            type: Number,
            default: 60,
            required: false
        }
    },

    methods: {
        update: function update(ps) {
            var item = null;
            this.controlunits.forEach(function (data, index) {
                if (data.id === ps.controlunit.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.controlunits.splice(item, 1, ps.controlunit);
            }
        },

        delete: function _delete(ps) {
            var item = null;
            this.controlunits.forEach(function (data, index) {
                if (data.id === ps.controlunit.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.controlunits.splice(item, 1);
            }
        },

        set_order: function set_order(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                } else {
                    this.order.direction = 'asc';
                }
            } else {
                this.order.field = field;
            }

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function set_filter() {
            this.filter_string = '&';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function set_page(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/controlunits?page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.meta = data.meta;
                    that.controlunits = data.data;
                    that.$nextTick(function () {
                        $('table.collapsible').collapsibletable();
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('ControlunitUpdated', function (e) {
            _this.update(e);
        }).listen('ControlunitDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight collapsible",attrs:{"data-collapsible":"expandable"}},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{attrs:{"data-field":"name"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('name')}}},[_vm._s(_vm.$t('labels.name'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'asc'),expression:"order.field == 'name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'desc'),expression:"order.field == 'name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.name),expression:"filter.name"}],attrs:{"id":"filter_name","type":"text"},domProps:{"value":_vm._s(_vm.filter.name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.name=$event.target.value}}})," ",_vm._m(0)])])," ",_vm._h('th',{attrs:{"data-field":"timestamps.last_heartbeat"}},["\n                    "+_vm._s(_vm.$t('labels.last_heartbeat'))+"\n                "])," ",_vm._m(1)])])," ",_vm._l((_vm.controlunits),function(controlunit){return [_vm._h('tbody',[_vm._h('tr',{staticClass:"collapsible-header"},[_vm._h('td',[_vm._h('span',[_vm._m(2,true)," ",_vm._h('a',{attrs:{"href":'/controlunits/' + controlunit.id}},[_vm._s(controlunit.name)])])])," ",_vm._h('td',[_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(controlunit.timestamps.last_heartbeat_diff.days > 0),expression:"controlunit.timestamps.last_heartbeat_diff.days > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":controlunit.timestamps.last_heartbeat}},["\n                                "+_vm._s(_vm.$t('units.days_ago', {val: controlunit.timestamps.last_heartbeat_diff.days}))+"\n                            "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(controlunit.timestamps.last_heartbeat_diff.days < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.hours > 0),expression:"controlunit.timestamps.last_heartbeat_diff.days < 1 &&\n                                          controlunit.timestamps.last_heartbeat_diff.hours > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":controlunit.timestamps.last_heartbeat}},["\n\n                                "+_vm._s(_vm.$t('units.hours_ago', {val: controlunit.timestamps.last_heartbeat_diff.hours}))+"\n                            "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(controlunit.timestamps.last_heartbeat_diff.days < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.hours < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.minutes > 1),expression:"controlunit.timestamps.last_heartbeat_diff.days < 1 &&\n                                          controlunit.timestamps.last_heartbeat_diff.hours < 1 &&\n                                          controlunit.timestamps.last_heartbeat_diff.minutes > 1"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":controlunit.timestamps.last_heartbeat}},["\n\n                                "+_vm._s(_vm.$t('units.minutes_ago', {val: controlunit.timestamps.last_heartbeat_diff.minutes}))+"\n                            "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(controlunit.timestamps.last_heartbeat_diff.days < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.hours < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.minutes < 2),expression:"controlunit.timestamps.last_heartbeat_diff.days < 1 &&\n                                          controlunit.timestamps.last_heartbeat_diff.hours < 1 &&\n                                          controlunit.timestamps.last_heartbeat_diff.minutes < 2"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":controlunit.timestamps.last_heartbeat}},["\n\n                                "+_vm._s(_vm.$t('units.just_now'))+"\n                            "])])," ",_vm._h('td',[_vm._h('span',[_vm._h('a',{staticClass:"waves-effect waves-light btn-small",attrs:{"href":'/controlunits/' + controlunit.id + '/edit'}},[_vm._m(3,true)])])])])," ",_vm._h('tr',{staticClass:"collapsible-body"},[_vm._h('td',{attrs:{"colspan":"3"}},["\n                            "+_vm._s(_vm.$tc('components.physical_sensors', 2))+":\n                            ",_vm._l((controlunit.physical_sensors),function(physical_sensor,index){return _vm._h('span',[_vm._m(4,true)," ",_vm._h('a',{attrs:{"href":'/physical_sensors/' + physical_sensor.id}},[_vm._s(physical_sensor.name)])," ",(index < controlunit.physical_sensors.length-1)?[", "]:_vm._e()])})," ",_vm._m(5,true),"\n                            "+_vm._s(_vm.$tc('components.valves', 2))+":\n                            ",_vm._l((controlunit.valves),function(valve,index){return _vm._h('span',[_vm._m(6,true)," ",_vm._h('a',{attrs:{"href":'/valves/' + valve.id}},[_vm._s(valve.name)])," ",(index < controlunit.valves.length-1)?[", "]:_vm._e()])})," ",_vm._m(7,true),"\n                            "+_vm._s(_vm.$tc('components.pumps', 2))+":\n                            ",_vm._l((controlunit.pumps),function(pump,index){return _vm._h('span',[_vm._m(8,true)," ",_vm._h('a',{attrs:{"href":'/pumps/' + pump.id}},[_vm._s(pump.name)])," ",(index < controlunit.pumps.length-1)?[", "]:_vm._e()])})," ",_vm._m(9,true),"\n                            "+_vm._s(_vm.$tc('components.generic_components', 2))+":\n                            ",_vm._l((controlunit.generic_components),function(generic_component,index){return _vm._h('span',[_vm._h('i',{staticClass:"material-icons"},[_vm._s(generic_component.type.icon)])," ",_vm._h('a',{attrs:{"href":'/generic_components/' + generic_component.id}},[_vm._s(generic_component.name)])," ",(index < controlunit.generic_components.length-1)?[", "]:_vm._e()])})," ",_vm._m(10,true)])])])]})])," ",(_vm.meta.hasOwnProperty('pagination'))?_vm._h('ul',{staticClass:"pagination"},[_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(1)}}},[_vm._m(11)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._m(12)])])," ",(_vm.meta.pagination.current_page-3 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-3)}}},[_vm._s(_vm.meta.pagination.current_page-3)])]):_vm._e()," ",(_vm.meta.pagination.current_page-2 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-2)}}},[_vm._s(_vm.meta.pagination.current_page-2)])]):_vm._e()," ",(_vm.meta.pagination.current_page-1 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._s(_vm.meta.pagination.current_page-1)])]):_vm._e()," ",_vm._h('li',{staticClass:"active"},[_vm._h('a',{attrs:{"href":"#!"}},[_vm._s(_vm.meta.pagination.current_page)])])," ",(_vm.meta.pagination.current_page+1 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._s(_vm.meta.pagination.current_page+1)])]):_vm._e()," ",(_vm.meta.pagination.current_page+2 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+2)}}},[_vm._s(_vm.meta.pagination.current_page+2)])]):_vm._e()," ",(_vm.meta.pagination.current_page+3 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+3)}}},[_vm._s(_vm.meta.pagination.current_page+3)])]):_vm._e()," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._m(13)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.total_pages)}}},[_vm._m(14)])])]):_vm._e()])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_name"}},["Filter"])},function render () {var _vm=this;return _vm._h('th',{staticStyle:{"width":"40px"}})},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["developer_board"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["edit"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["memory"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["transform"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["rotate_right"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["first_page"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_left"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["last_page"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-16", __vue__options__)
  } else {
    hotAPI.reload("data-v-16", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],26:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            dashboard: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'dashboard-masonry-grid',
            required: false
        }
    },

    methods: {
        updateTerrarium: function updateTerrarium(e) {
            var item = null;
            var found = false;

            this.dashboard.terraria.ok.forEach(function (data, index) {
                if (data.id === e.terrarium.id) {
                    item = index;
                }
            });
            if (item !== null) {
                if (e.terrarium.temperature_critical !== false || e.terrarium.humidity_critical !== false || e.terrarium.heartbeat_critical !== false) {
                    this.dashboard.terraria.ok.splice(item, 1);
                } else {
                    this.dashboard.terraria.ok.splice(item, 1, e.terrarium);
                    found = true;
                }
            }

            item = null;
            this.dashboard.terraria.critical.forEach(function (data, index) {
                if (data.id === e.terrarium.id) {
                    item = index;
                }
            });
            if (item !== null) {
                if (e.terrarium.temperature_critical === false && e.terrarium.humidity_critical === false && e.terrarium.heartbeat_critical === false) {
                    this.dashboard.terraria.critical.splice(item, 1);
                } else {
                    this.dashboard.terraria.critical.splice(item, 1, e.terrarium);
                    found = true;
                }
            }

            if (found !== true) {
                if (e.terrarium.temperature_critical === false && e.terrarium.humidity_critical === false && e.terrarium.heartbeat_critical === false) {
                    this.dashboard.terraria.ok.push(e.terrarium);
                } else {
                    this.dashboard.terraria.critical.push(e.terrarium);
                }
            }

            this.refresh_grid();
        },
        deleteTerrarium: function deleteTerrarium(e) {
            var that = this;

            this.dashboard.terraria.ok.forEach(function (data, index) {
                if (data.id === e.terrarium_id) {
                    this.dashboard.terraria.ok.splice(index, 1);
                }
            });

            this.dashboard.terraria.critical.forEach(function (data, index) {
                if (data.id === e.terrarium_id) {
                    this.dashboard.terraria.critical.splice(index, 1);
                }
            });

            this.refresh_grid();
        },

        updateAnimalFeedingSchedule: function updateAnimalFeedingSchedule(e) {
            var item = null;
            var found = false;

            this.dashboard.animal_feeding_schedules.due.forEach(function (data, index) {
                if (data.id === e.animal_feeding_schedule.id) {
                    item = index;
                }
            });
            if (item !== null) {
                if (e.animal_feeding_schedule.due_days === 0) {
                    this.dashboard.animal_feeding_schedules.due.splice(item, 1, e.animal_feeding_schedule);
                    found = true;
                } else {
                    this.dashboard.animal_feeding_schedules.due.splice(item, 1);
                }
            }

            item = null;
            this.dashboard.animal_feeding_schedules.overdue.forEach(function (data, index) {
                if (data.id === e.animal_feeding_schedule.id) {
                    item = index;
                }
            });

            if (item !== null) {
                if (e.animal_feeding_schedule.due_days >= 0) {
                    this.dashboard.animal_feeding_schedules.overdue.splice(item, 1);
                } else {
                    this.dashboard.animal_feeding_schedules.overdue.splice(item, 1, e.animal_feeding_schedule);
                    found = true;
                }
            }

            if (found !== true) {
                if (e.animal_feeding_schedule.due_days == 0) {
                    this.dashboard.animal_feeding_schedules.due.push(e.animal_feeding_schedule);
                } else if (e.animal_feeding_schedule.due_days < 0) {
                    this.dashboard.animal_feeding_schedules.overdue.push(e.animal_feeding_schedule);
                }
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        deleteAnimalFeedingSchedule: function deleteAnimalFeedingSchedule(e) {
            var that = this;

            this.dashboard.animal_feeding_schedules.due.forEach(function (data, index) {
                if (data.id === e.animal_feeding_schedule_id) {
                    that.dashboard.animal_feeding_schedules.due.splice(index, 1);
                }
            });

            this.dashboard.animal_feeding_schedules.overdue.forEach(function (data, index) {
                if (data.id === e.animal_feeding_schedule_id) {
                    this.dashboard.animal_feeding_schedules.overdue.splice(index, 1);
                }
            });

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        updateAnimalWeighingSchedule: function updateAnimalWeighingSchedule(e) {
            var item = null;
            var found = false;

            this.dashboard.animal_weighing_schedules.due.forEach(function (data, index) {
                if (data.id === e.animal_weighing_schedule.id) {
                    item = index;
                }
            });
            if (item !== null) {
                if (e.animal_weighing_schedule.due_days === 0) {
                    this.dashboard.animal_weighing_schedules.due.splice(item, 1, e.animal_weighing_schedule);
                    found = true;
                } else {
                    this.dashboard.animal_weighing_schedules.due.splice(item, 1);
                }
            }

            item = null;
            this.dashboard.animal_weighing_schedules.overdue.forEach(function (data, index) {
                if (data.id === e.animal_weighing_schedule.id) {
                    item = index;
                }
            });

            if (item !== null) {
                if (e.animal_weighing_schedule.due_days >= 0) {
                    this.dashboard.animal_weighing_schedules.overdue.splice(item, 1);
                } else {
                    this.dashboard.animal_weighing_schedules.overdue.splice(item, 1, e.animal_weighing_schedule);
                    found = true;
                }
            }

            if (found !== true) {
                if (e.animal_weighing_schedule.due_days == 0) {
                    this.dashboard.animal_weighing_schedules.due.push(e.animal_weighing_schedule);
                } else if (e.animal_weighing_schedule.due_days < 0) {
                    this.dashboard.animal_weighing_schedules.overdue.push(e.animal_weighing_schedule);
                }
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        deleteAnimalWeighingSchedule: function deleteAnimalWeighingSchedule(e) {
            var that = this;

            this.dashboard.animal_weighing_schedules.due.forEach(function (data, index) {
                if (data.id === e.animal_weighing_schedule_id) {
                    that.dashboard.animal_weighing_schedules.due.splice(index, 1);
                }
            });

            this.dashboard.animal_weighing_schedules.overdue.forEach(function (data, index) {
                if (data.id === e.animal_weighing_schedule_id) {
                    this.dashboard.animal_weighing_schedules.overdue.splice(index, 1);
                }
            });

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        updateActionSequenceSchedule: function updateActionSequenceSchedule(e) {
            var item = null;
            var found = false;

            this.dashboard.action_sequence_schedules.due.forEach(function (data, index) {
                if (data.id === e.action_sequence_schedule.id) {
                    item = index;
                }
            });
            if (item !== null) {
                if (e.action_sequence_schedule.states.is_overdue === false && e.action_sequence_schedule.states.will_run_today === true) {
                    this.dashboard.action_sequence_schedules.due.splice(item, 1, e.action_sequence_schedule);
                    found = true;
                } else {
                    this.dashboard.action_sequence_schedules.due.splice(item, 1);
                }
            }
            item = null;

            item = null;
            this.dashboard.action_sequence_schedules.overdue.forEach(function (data, index) {
                if (data.id === e.action_sequence_schedule.id) {
                    item = index;
                }
            });
            if (item !== null) {
                if (e.action_sequence_schedule.states.is_overdue === false) {
                    this.dashboard.action_sequence_schedules.overdue.splice(item, 1);
                } else {
                    this.dashboard.action_sequence_schedules.overdue.splice(item, 1, e.action_sequence_schedule);
                    item = null;
                }
                found = true;
            }

            item = null;
            this.dashboard.action_sequence_schedules.running.forEach(function (data, index) {
                if (data.id === e.action_sequence_schedule.id) {
                    item = index;
                }
            });

            if (item !== null) {
                if (e.action_sequence_schedule.states.running === false) {
                    this.dashboard.action_sequence_schedules.running.splice(item, 1);
                } else {
                    this.dashboard.action_sequence_schedules.running.splice(item, 1, e.action_sequence_schedule);
                    found = true;
                }
            }

            if (found !== true) {
                if (e.action_sequence_schedule.states.is_overdue === false && e.action_sequence_schedule.states.will_run_today === true) {
                    this.dashboard.action_sequence_schedules.due.push(e.action_sequence_schedule);
                } else if (e.action_sequence_schedule.states.is_overdue === true) {
                    this.dashboard.action_sequence_schedules.overdue.push(e.action_sequence_schedule);
                } else if (e.action_sequence_schedule.states.running === true) {
                    this.dashboard.action_sequence_schedules.running.push(e.action_sequence_schedule);
                }
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        deleteActionSequenceSchedule: function deleteActionSequenceSchedule(e) {
            var that = this;

            this.dashboard.action_sequence_schedules.due.forEach(function (data, index) {
                if (data.id === e.action_sequence_schedule_id) {
                    that.dashboard.action_sequence_schedules.due.splice(index, 1);
                }
            });

            this.dashboard.action_sequence_schedules.overdue.forEach(function (data, index) {
                if (data.id === e.action_sequence_schedule_id) {
                    that.dashboard.action_sequence_schedules.overdue.splice(index, 1);
                }
            });

            this.dashboard.action_sequence_schedules.running.forEach(function (data, index) {
                if (data.id === e.action_sequence_schedule_id) {
                    that.dashboard.action_sequence_schedules.running.splice(index, 1);
                }
            });

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        updateActionSequenceTrigger: function updateActionSequenceTrigger(e) {
            var item = null;
            var found = false;

            item = null;
            this.dashboard.action_sequence_triggers.running.forEach(function (data, index) {
                if (data.id === e.action_sequence_trigger.id) {
                    item = index;
                }
            });

            if (item !== null) {
                if (e.action_sequence_trigger.states.running === false) {
                    this.dashboard.action_sequence_triggers.running.splice(item, 1);
                } else {
                    this.dashboard.action_sequence_triggers.running.splice(item, 1, e.action_sequence_trigger);
                    found = true;
                }
            }

            if (found !== true) {
                if (e.action_sequence_trigger.states.running === true) {
                    this.dashboard.action_sequence_triggers.running.push(e.action_sequence_trigger);
                }
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        deleteActionSequenceTrigger: function deleteActionSequenceTrigger(e) {
            var that = this;

            this.dashboard.action_sequence_triggers.running.forEach(function (data, index) {
                if (data.id === e.action_sequence_trigger_id) {
                    that.dashboard.action_sequence_triggers.running.splice(index, 1);
                }
            });

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        updateActionSequenceIntention: function updateActionSequenceIntention(e) {
            var item = null;
            var found = false;

            item = null;
            this.dashboard.action_sequence_intentions.running.forEach(function (data, index) {
                if (data.id === e.action_sequence_intention.id) {
                    item = index;
                }
            });

            if (item !== null) {
                if (e.action_sequence_intention.states.running === false) {
                    this.dashboard.action_sequence_intentions.running.splice(item, 1);
                } else {
                    this.dashboard.action_sequence_intentions.running.splice(item, 1, e.action_sequence_intention);
                    found = true;
                }
            }

            if (found !== true) {
                if (e.action_sequence_intention.states.running === true) {
                    this.dashboard.action_sequence_intentions.running.push(e.action_sequence_intention);
                }
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        deleteActionSequenceIntention: function deleteActionSequenceIntention(e) {
            var that = this;

            this.dashboard.action_sequence_intentions.running.forEach(function (data, index) {
                if (data.id === e.action_sequence_intention_id) {
                    that.dashboard.action_sequence_intentions.running.splice(index, 1);
                }
            });

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('.dropdown-button').dropdown({
                constrain_width: false
            });
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        submit: function submit(e) {
            window.submit_form(e);
        },

        link_post: function link_post(e) {
            e.preventDefault();
            var old = e;

            var parentElement = e.target.href ? e.target : e.target.parentElement;
            var oldContent = parentElement.innerHTML;
            $(parentElement).html('<div class="preloader-wrapper tiny active">' + '<div class="spinner-layer spinner-green-only">' + '<div class="circle-clipper left">' + '<div class="circle"></div>' + '</div><div class="gap-patch">' + '<div class="circle"></div>' + '</div><div class="circle-clipper right">' + '<div class="circle"></div>' + '</div>' + '</div>' + '</div>');

            $.post(parentElement.href, null, function (e) {
                $(parentElement).html(oldContent);
            });
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/dashboard',
                method: 'GET',
                success: function success(data) {
                    that.dashboard = data.data;

                    that.$nextTick(function () {
                        var $container = $('#' + that.containerId);
                        $container.masonry({
                            columnWidth: '.col',
                            itemSelector: '.col'
                        });

                        that.refresh_grid();
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    window.notification('An error occured :(', 'red darken-1 text-white');
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('TerrariumUpdated', function (e) {
            _this.updateTerrarium(e);
        }).listen('TerrariumDeleted', function (e) {
            _this.deleteTerrarium(e);
        }).listen('AnimalFeedingScheduleUpdated', function (e) {
            _this.updateAnimalFeedingSchedule(e);
        }).listen('AnimalFeedingScheduleDeleted', function (e) {
            _this.deleteAnimalFeedingSchedule(e);
        }).listen('AnimalWeighingScheduleUpdated', function (e) {
            _this.updateAnimalWeighingSchedule(e);
        }).listen('AnimalWeighingScheduleDeleted', function (e) {
            _this.deleteAnimalWeighingSchedule(e);
        }).listen('ActionSequenceScheduleUpdated', function (e) {
            _this.updateActionSequenceSchedule(e);
        }).listen('ActionSequenceScheduleDeleted', function (e) {
            _this.deleteActionSequenceSchedule(e);
        }).listen('ActionSequenceTriggerUpdated', function (e) {
            _this.updateActionSequenceTrigger(e);
        }).listen('ActionSequenceTriggerDeleted', function (e) {
            _this.deleteActionSequenceTrigger(e);
        }).listen('ActionSequenceIntentionUpdated', function (e) {
            _this.updateActionSequenceIntention(e);
        }).listen('ActionSequenceIntentionDeleted', function (e) {
            _this.deleteActionSequenceIntention(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:[_vm.containerClasses, 'masonry-grid'],attrs:{"id":_vm.containerId}},[(_vm.dashboard.terraria.critical.length > 0)?_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('ul',{staticClass:"collection critical with-header"},[_vm._h('li',{staticClass:"collection-header"},[_vm._m(0),"\n                "+_vm._s(_vm.dashboard.terraria.critical.length)+" "+_vm._s(_vm.$tc("components.terraria", _vm.dashboard.terraria.critical.length))+" "+_vm._s(_vm.$t("labels.critical"))+"\n            "])," ",_vm._l((_vm.dashboard.terraria.critical),function(terrarium){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',[_vm._h('a',{staticClass:"white-text",attrs:{"href":'/terraria/' + terrarium.id}},[_vm._s(terrarium.display_name)])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(terrarium.humidity_critical === true && terrarium.temperature_critical !== true),expression:"terrarium.humidity_critical === true && terrarium.temperature_critical !== true"}]},["("+_vm._s(_vm.$t("labels.humidity"))+": "+_vm._s(terrarium.cooked_humidity_percent)+"%)"])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(terrarium.humidity_critical === true && terrarium.temperature_critical === true),expression:"terrarium.humidity_critical === true && terrarium.temperature_critical === true"}]},["("+_vm._s(_vm.$t("labels.humidity"))+": "+_vm._s(terrarium.cooked_humidity_percent)+"%, "+_vm._s(_vm.$t("labels.temperature"))+": "+_vm._s(terrarium.cooked_temperature_celsius)+"°C)"])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(terrarium.humidity_critical !== true && terrarium.temperature_critical === true),expression:"terrarium.humidity_critical !== true && terrarium.temperature_critical === true"}]},["("+_vm._s(_vm.$t("labels.temperature"))+": "+_vm._s(terrarium.cooked_temperature_celsius)+"°C)"])])])})])]):_vm._e()," "," ",(_vm.dashboard.animal_feeding_schedules.overdue.length > 0)?_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('ul',{staticClass:"collection warning with-header"},[_vm._h('li',{staticClass:"collection-header"},[_vm._m(1),"\n                "+_vm._s(_vm.dashboard.animal_feeding_schedules.overdue.length)+" "+_vm._s(_vm.$tc("components.animal_feedings", 2))+" "+_vm._s(_vm.$t("labels.overdue"))+"\n            "])," ",_vm._l((_vm.dashboard.animal_feeding_schedules.overdue),function(schedule){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},[_vm._h('span',{staticStyle:{"display":"inline-block","width":"calc(100% - 60px)"}},["\n                        "+_vm._s(schedule.animal.display_name)+": "+_vm._s(schedule.type)+" ("+_vm._s(_vm.$t("labels.since"))+" "+_vm._s((schedule.due_days*-1))+" "+_vm._s(_vm.$tc("units.days", (schedule.due_days*-1)))+")\n                    "])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/skip'},on:{"click":_vm.link_post}},[_vm._m(2,true)])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/done'},on:{"click":_vm.link_post}},[_vm._m(3,true)])])])})])]):_vm._e()," "," ",(_vm.dashboard.animal_weighing_schedules.overdue.length > 0)?_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('ul',{staticClass:"collection warning with-header"},[_vm._h('li',{staticClass:"collection-header"},[_vm._m(4),"\n                "+_vm._s(_vm.dashboard.animal_weighing_schedules.overdue.length)+" "+_vm._s(_vm.$tc("components.animal_weighings", 2))+" "+_vm._s(_vm.$t("labels.overdue"))+"\n            "])," ",_vm._l((_vm.dashboard.animal_weighing_schedules.overdue),function(schedule){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},[_vm._h('span',{staticStyle:{"display":"inline-block","width":"calc(100% - 60px)"}},["\n                        "+_vm._s(schedule.animal.display_name)+" ("+_vm._s(_vm.$t("labels.since"))+" "+_vm._s((schedule.due_days*-1))+" "+_vm._s(_vm.$tc("units.days", (schedule.due_days*-1)))+")\n                    "])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/animals/' + schedule.animal.id + '/weighing_schedules/' + schedule.id + '/skip'},on:{"click":_vm.link_post}},[_vm._m(5,true)])," "," ",_vm._m(6,true)])])})])]):_vm._e()," "," ",(_vm.dashboard.action_sequence_schedules.overdue.length > 0)?_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('ul',{staticClass:"collection warning with-header"},[_vm._h('li',{staticClass:"collection-header"},[_vm._m(7),"\n                "+_vm._s(_vm.dashboard.action_sequence_schedules.overdue.length)+" "+_vm._s(_vm.$tc("components.action_sequences", 2))+" "+_vm._s(_vm.$t("labels.overdue"))+"\n            "])," ",_vm._l((_vm.dashboard.action_sequence_schedules.overdue),function(schedule){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},[_vm._h('span',{staticStyle:{"display":"inline-block","width":"calc(100% - 30px)"}},["\n                        "+_vm._s(schedule.timestamps.starts)+": "+_vm._s(schedule.sequence.name)+"\n                    "])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/action_sequence_schedules/' + schedule.id + '/skip'},on:{"click":_vm.link_post}},[_vm._m(8,true)])])])})])]):_vm._e()," "," ",(_vm.dashboard.animal_feeding_schedules.due.length > 0)?_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('ul',{staticClass:"collection ok with-header"},[_vm._h('li',{staticClass:"collection-header"},[_vm._m(9),"\n                "+_vm._s(_vm.dashboard.animal_feeding_schedules.due.length)+" "+_vm._s(_vm.$tc("components.animal_feedings", 2))+" "+_vm._s(_vm.$t("labels.due"))+"\n            "])," ",_vm._l((_vm.dashboard.animal_feeding_schedules.due),function(schedule){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},[_vm._h('span',{staticStyle:{"display":"inline-block","width":"calc(100% - 60px)"}},["\n                        "+_vm._s(schedule.animal.display_name)+": "+_vm._s(schedule.type)+"\n                    "])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/skip'},on:{"click":_vm.link_post}},[_vm._m(10,true)])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/done'},on:{"click":_vm.link_post}},[_vm._m(11,true)])])])})])]):_vm._e()," "," ",(_vm.dashboard.animal_weighing_schedules.due.length > 0)?_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('ul',{staticClass:"collection ok with-header"},[_vm._h('li',{staticClass:"collection-header"},[_vm._m(12),"\n                "+_vm._s(_vm.dashboard.animal_weighing_schedules.due.length)+" "+_vm._s(_vm.$tc("components.animal_weighings", 2))+" "+_vm._s(_vm.$t("labels.due"))+"\n            "])," ",_vm._l((_vm.dashboard.animal_weighing_schedules.due),function(schedule){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},[_vm._h('span',{staticStyle:{"display":"inline-block","width":"calc(100% - 60px)"}},["\n                        "+_vm._s(schedule.animal.display_name)+" "+_vm._s(_vm.$t('labels.today'))+"\n                    "])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/animals/' + schedule.animal.id + '/weighing_schedules/' + schedule.id + '/skip'},on:{"click":_vm.link_post}},[_vm._m(13,true)])," "," ",_vm._m(14,true)])])})])]):_vm._e()," "," ",(_vm.dashboard.action_sequence_schedules.due.length > 0)?_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('ul',{staticClass:"collection ok with-header"},[_vm._h('li',{staticClass:"collection-header"},[_vm._m(15),"\n                "+_vm._s(_vm.dashboard.action_sequence_schedules.due.length)+" "+_vm._s(_vm.$tc("components.action_sequences", 2))+" "+_vm._s(_vm.$t("labels.due"))+"\n            "])," ",_vm._l((_vm.dashboard.action_sequence_schedules.due),function(schedule){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},[_vm._h('span',{staticStyle:{"display":"inline-block","width":"calc(100% - 30px)"}},["\n                        "+_vm._s(schedule.timestamps.starts)+": "+_vm._s(schedule.sequence.name)+"\n                    "])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/action_sequence_schedules/' + schedule.id + '/skip'},on:{"click":_vm.link_post}},[_vm._m(16,true)])])])})])]):_vm._e()," "," ",(_vm.dashboard.action_sequence_schedules.running.length > 0
            || _vm.dashboard.action_sequence_triggers.running.length > 0
            || _vm.dashboard.action_sequence_intentions.running.length > 0)?_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('ul',{staticClass:"collection ok with-header"},[_vm._h('li',{staticClass:"collection-header"},[_vm._m(17),"\n                "+_vm._s((_vm.dashboard.action_sequence_schedules.running.length + _vm.dashboard.action_sequence_triggers.running.length + _vm.dashboard.action_sequence_intentions.running.length))+"\n                "+_vm._s(_vm.$tc("components.action_sequences", 2))+" "+_vm._s(_vm.$t("labels.running"))+"\n            "])," ",_vm._l((_vm.dashboard.action_sequence_schedules.running),function(schedule){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},[_vm._h('span',{staticStyle:{"display":"inline-block","width":"calc(100% - 30px)"}},[_vm._m(18,true)," ",(schedule.timestamps.last_start !== null)?_vm._h('a',{staticClass:"white-text"},[_vm._s(schedule.timestamps.last_start.split(" ")[1])]):_vm._e()," ",_vm._h('a',{staticClass:"white-text",attrs:{"href":'/action_sequences/' + schedule.sequence.id + '/edit'}},["\n                            "+_vm._s(schedule.sequence.name)+"\n                        "])])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/action_sequence_schedules/' + schedule.id + '/skip'},on:{"click":_vm.link_post}},[_vm._m(19,true)])])])})," ",_vm._l((_vm.dashboard.action_sequence_triggers.running),function(trigger){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},[_vm._h('span',{staticStyle:{"display":"inline-block","width":"calc(100% - 30px)"}},[_vm._m(20,true)," ",(trigger.timestamps.last_start !== null)?_vm._h('a',{staticClass:"white-text"},[_vm._s(trigger.timestamps.last_start.split(" ")[1])]):_vm._e()," ",_vm._h('a',{staticClass:"white-text",attrs:{"href":'/action_sequences/' + trigger.sequence.id + '/edit'}},["\n                            "+_vm._s(trigger.sequence.name)+"\n                        "])])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/action_sequence_triggers/' + trigger.id + '/skip'},on:{"click":_vm.link_post}},[_vm._m(21,true)])])])})," ",_vm._l((_vm.dashboard.action_sequence_intentions.running),function(intention){return _vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},[_vm._h('span',{staticStyle:{"display":"inline-block","width":"calc(100% - 30px)"}},[_vm._m(22,true)," ",(intention.timestamps.last_start !== null)?_vm._h('a',{staticClass:"white-text"},[_vm._s(intention.timestamps.last_start.split(" ")[1])]):_vm._e()," ",_vm._h('a',{staticClass:"white-text",attrs:{"href":'/action_sequences/' + intention.sequence.id + '/edit'}},["\n                            "+_vm._s(intention.sequence.name)+"\n                        "])])," ",_vm._h('a',{staticClass:"secondary-content white-text",attrs:{"href":'/api/v1/action_sequence_intentions/' + intention.id + '/skip'},on:{"click":_vm.link_post}},[_vm._m(23,true)])])])})])]):_vm._e()," "," ",(_vm.dashboard.terraria.critical.length < 1)?_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('ul',{staticClass:"collection ok with-header"},[_vm._h('li',{staticClass:"collection-header"},[_vm._m(24),"\n                "+_vm._s(_vm.dashboard.terraria.ok.length)+" "+_vm._s(_vm.$tc("components.terraria", 2))+"\n            "])," ",_vm._h('li',{staticClass:"collection-item"},[_vm._h('div',{staticClass:"white-text"},["\n\n                    "+_vm._s(_vm.dashboard.terraria.ok.length)+" "+_vm._s(_vm.$tc("components.terraria", _vm.dashboard.terraria.ok.length))+" "+_vm._s(_vm.$t("labels.ok"))+"\n\n                "])])])]):_vm._e()])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["video_label"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["local_dining"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["update"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["done"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["vertical_align_bottom"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["update"])},function render () {var _vm=this;return _vm._h('a',{staticClass:"secondary-content white-text"},[_vm._h('i',{staticClass:"material-icons"},["done"])])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["playlist_play"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["update"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["local_dining"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["update"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["done"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["vertical_align_bottom"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["update"])},function render () {var _vm=this;return _vm._h('a',{staticClass:"secondary-content white-text"},[_vm._h('i',{staticClass:"material-icons"},["done"])])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["playlist_play"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["update"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["playlist_play"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["schedule"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["update"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["flare"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["update"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["explore"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["update"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["video_label"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2", __vue__options__)
  } else {
    hotAPI.reload("data-v-2", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],27:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {

    props: {
        id: {
            type: Number,
            required: false,
            default: Math.floor(Math.random() * 1000000)
        },
        source: {
            type: String,
            required: true
        },
        FilterColumn: {
            type: String,
            default: 'created_at',
            required: false
        },
        ShowFilterForm: {
            type: Boolean,
            default: false,
            required: false
        },
        FilterFromDate: {
            type: String,
            default: new Date(new Date().setDate(new Date().getDate() - 7)).toYmd(),
            required: false
        },
        FilterToDate: {
            type: String,
            default: new Date().toYmd(),
            required: false
        }
    },

    data: function data() {
        return {
            chart: null,
            options: {},
            data: null
        };
    },


    methods: {
        get_filter_from_date: function get_filter_from_date() {
            if ($('#filter_from_' + this.id).val() == undefined) {
                return this.FilterFromDate;
            }

            return $('#filter_from_' + this.id).val();
        },
        get_filter_to_date: function get_filter_to_date() {
            if ($('#filter_to_' + this.id).val() == undefined) {
                return this.FilterToDate + " 23:59:59";
            }

            return $('#filter_to_' + this.id).val() + " 23:59:59";
        },

        init: function init() {
            this.build();
        },
        build: function build() {
            $('#dygraph_' + this.id + '_loading').show();
            var that = this;
            var url = this.source + '?csv=true&filter[' + this.FilterColumn + ']=ge:' + this.get_filter_from_date() + ':and:le:' + this.get_filter_to_date();

            $.ajax({
                url: url,
                method: 'GET',
                success: function success(data) {
                    that.data = data.data;
                    if (that.data.split(/\r\n|\r|\n/).length > 1) {
                        that.draw();
                    } else {
                        $('#dygraph_' + that.id + '_loading').hide();
                    }
                },
                error: function error(_error) {
                    $('#dygraph_' + that.id + '_loading').hide();
                    console.log((0, _stringify2.default)(_error));
                }
            });
        },
        draw: function draw() {
            if (this.data === null) {
                return;
            }

            var that = this;
            var g = new Dygraph(document.getElementById('dygraph_' + this.id), this.data, {
                'connectSeparatedPoints': true,
                rollPeriod: 10,
                showRangeSelector: true,
                legend: 'always',
                colors: ['#5555EE', '#CC5555'],
                axisLineColor: '#D4D4D4'
            });
            g.ready(function () {
                $('#dygraph_' + that.id + '_loading').hide();
            });
        }
    },

    created: function created() {
        window.eventHubVue.processStarted();

        window.eventHubVue.$on('ForceRerender', this.draw);

        var that = this;
        this.$nextTick(function () {
            $('.datepicker').pickadate({
                format: 'yyyy-mm-dd'
            });

            that.build();
        });

        window.eventHubVue.processEnded();
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[(_vm.ShowFilterForm === true)?_vm._h('div',[_vm._h('div',{staticClass:"row",staticStyle:{"margin-bottom":"0"}},[_vm._h('div',{staticClass:"input-field col s12 m4 l4"},[_vm._h('input',{staticClass:"datepicker",attrs:{"type":"date","placeholder":_vm.$t('labels.from'),"name":"filter_from","id":'filter_from_' + _vm.id,"data-default":_vm.FilterFromDate},domProps:{"value":_vm.FilterFromDate}})," ",_vm._h('label',{attrs:{"for":'filter_from_' + _vm.id}},[_vm._s(_vm.$t('labels.from'))])])," ",_vm._h('div',{staticClass:"input-field col s12 m4 l4"},[_vm._h('input',{staticClass:"datepicker",attrs:{"type":"date","placeholder":_vm.$t('labels.to'),"name":"filter_to","id":'filter_to_' + _vm.id,"data-default":_vm.FilterToDate},domProps:{"value":_vm.FilterToDate}})," ",_vm._h('label',{attrs:{"for":'filter_to_' + _vm.id}},[_vm._s(_vm.$t('labels.to'))])])," ",_vm._h('div',{staticClass:"input-field col s12 m4 l4"},[_vm._h('button',{staticClass:"btn waves-effect waves-light",on:{"click":_vm.build}},[_vm._s(_vm.$t('buttons.loadgraph'))])])])]):_vm._e()," ",_vm._h('div',{staticClass:"center",staticStyle:{"display":"none"},attrs:{"id":'dygraph_' + _vm.id + '_loading'}},[_vm._m(0)])," ",_vm._h('div',{staticStyle:{"width":"100%"},attrs:{"id":'dygraph_' + _vm.id}})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('div',{staticClass:"preloader-wrapper small active"},[_vm._h('div',{staticClass:"spinner-layer spinner-green-only"},[_vm._h('div',{staticClass:"circle-clipper left"},[_vm._h('div',{staticClass:"circle"})]),_vm._h('div',{staticClass:"gap-patch"},[_vm._h('div',{staticClass:"circle"})]),_vm._h('div',{staticClass:"circle-clipper right"},[_vm._h('div',{staticClass:"circle"})])])])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4", __vue__options__)
  } else {
    hotAPI.reload("data-v-4", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],28:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            files: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        fileId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_type: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_id: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;
            this.files.forEach(function (data, index) {
                if (data.id === a.file.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.files.push(a.animal);
            } else if (item !== null) {
                this.files.splice(item, 1, a.animal);
            }
        },

        delete: function _delete(a) {
            var item = null;
            this.files.forEach(function (data, index) {
                if (data.id === a.file_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.files.splice(item, 1);
            }
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/files/' + that.fileId + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    if (that.fileId !== '') {
                        that.files = [data.data];
                    } else {
                        that.files = data.data;
                    }

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('FileUpdated', function (e) {
            _this.update(e);
        }).listen('FileDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:_vm.containerClasses},[_vm._l((_vm.files),function(file){return _vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"col s12 m12 l4"},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0,true),"\n                    "+_vm._s(_vm.$tc("components.files", 1))+"\n                "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(file.display_name)])," ",_vm._m(1,true)])," ",_vm._h('p',[_vm._h('span',[_vm._s(_vm.$t("labels.size"))+": "+_vm._s((file.size / 1024 / 1024).toFixed(2))+" MB"]),_vm._m(2,true)," ",_vm._h('span',[_vm._s(_vm.$t("labels.type"))+": "+_vm._s(file.mimetype)])])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/files/' + file.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])," ",_vm._h('a',{attrs:{"href":'/files/' + file.id + '/download/' + file.display_name}},[_vm._s(_vm.$t("buttons.download"))])])," ",_vm._m(3,true)])])," ",_vm._h('div',{staticClass:"col s12 m12 l8"},[_vm._h('div',{staticClass:"card"},[(file.is_image)?_vm._h('div',{staticClass:"card-image waves-effect waves-block waves-light file-card-image"},[_vm._h('img',{attrs:{"src":file.path_external}})]):_vm._e()])])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["attach_file"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._h('i',{staticClass:"material-icons right"},["close"])])," ",_vm._h('p')])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-18", __vue__options__)
  } else {
    hotAPI.reload("data-v-18", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],29:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            files: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        fileId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_type: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_id: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(a) {
            var item = null;
            this.files.forEach(function (data, index) {
                if (data.id === a.file.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.files.push(a.animal);
            } else if (item !== null) {
                this.files.splice(item, 1, a.animal);
            }
        },

        delete: function _delete(a) {
            var item = null;
            this.files.forEach(function (data, index) {
                if (data.id === a.file_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.files.splice(item, 1);
            }
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/files/' + that.fileId + '?' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    if (that.fileId !== '') {
                        that.files = [data.data];
                    } else {
                        that.files = data.data;
                    }

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }

    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('FileUpdated', function (e) {
            _this.update(e);
        }).listen('FileDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:_vm.containerClasses},[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0),"\n                "+_vm._s(_vm.files.length)+" "+_vm._s(_vm.$tc("components.files", 2))+"\n            "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(_vm.$tc("components.files", 2))])," ",_vm._m(1)])," ",_vm._m(2),_vm._l((_vm.files),function(file){return _vm._h('div',{staticClass:"chip"},[_vm._m(3,true)," ",_vm._h('a',{attrs:{"href":'/files/' + file.id}},[_vm._s(file.display_name)])," ",_vm._h('i',[_vm._s((file.size / 1024 / 1024).toFixed(2))+" MB"])])})," ",_vm._m(4)])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/files/create?preset[belongsTo_type]=' + _vm.belongsTo_type + '&preset[belongsTo_id]=' + _vm.belongsTo_id}},[_vm._s(_vm.$t("buttons.add"))])," ",_vm._h('a',{attrs:{"href":'/files/?filter[belongsTo_type]=' + _vm.belongsTo_type + '&filter[belongsTo_id]=' + _vm.belongsTo_id}},[_vm._s(_vm.$t("buttons.details"))])])," ",_vm._m(5)])])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["attach_file"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('p')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["insert_drive_file"])},function render () {var _vm=this;return _vm._h('p')},function render () {var _vm=this;return _vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._h('i',{staticClass:"material-icons right"},["close"])])," ",_vm._h('p')])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-17", __vue__options__)
  } else {
    hotAPI.reload("data-v-17", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],30:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});
exports.default = {
    data: function data() {
        return {};
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        id: {
            type: String,
            default: null,
            required: false
        },
        nameSingular: {
            type: String,
            default: null,
            required: false
        },
        namePlural: {
            type: String,
            default: null,
            required: false
        },
        icon: {
            type: String,
            default: null,
            required: false
        },
        properties: {
            type: Array,
            default: null,
            required: false
        },
        states: {
            type: Array,
            default: null,
            required: false
        }
    },

    methods: {

        add_property: function add_property(e) {
            var preset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

            if (e !== null) {
                e.preventDefault();
            }

            $('#generic_component_type_create_prop_input_tpl input[type="text"]').attr('value', preset || '');
            $('#generic_component_type_create_props').append($('#generic_component_type_create_prop_input_tpl').html());
        },

        add_state: function add_state(e) {
            var preset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

            if (e !== null) {
                e.preventDefault();
            }

            $('#generic_component_state_create_prop_input_tpl input[type="text"]').attr('value', preset || '');
            $('#generic_component_state_create_props').append($('#generic_component_state_create_prop_input_tpl').html());
        }

    },

    created: function created() {
        var that = this;
        this.$nextTick(function () {
            if (that.id !== null) {
                $('#' + that.id).attr('action', $('#' + that.id).attr('action') + '/' + that.id);
                $('#' + that.id).attr('data-method', 'PUT');
                $('#' + that.id + ' select[name=icon]').val(that.icon);
                $('#' + that.id + ' input[name=name_singular]').attr('value', that.nameSingular || '');
                $('#' + that.id + ' input[name=name_plural]').attr('value', that.namePlural || '');

                if (that.properties !== null) {
                    that.properties.forEach(function (el) {
                        that.add_property(null, el);
                    });
                }
                if (that.states !== null) {
                    that.states.forEach(function (el) {
                        that.add_state(null, el);
                    });
                }
            }
        });
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:_vm.containerClasses},[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('form',{attrs:{"action":"/api/v1/generic_component_types","id":_vm.id,"data-method":"POST","data-redirect-success":"/categories#tab_generic_components_types"}},[_vm._h('div',{staticClass:"card-content"},[_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"col s12"},[_vm._h('strong',[_vm._s(_vm.$tc('components.generic_component_types', 1))])])," ",_vm._h('div',{staticClass:"col s12 m6 l6"},[_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"input-field col s12 m6 l6"},[_vm._h('input',{attrs:{"type":"text","placeholder":_vm.$t('labels.name_singular'),"name":"name_singular","value":""}})," ",_vm._h('label',{attrs:{"for":"name_singular"}},[_vm._s(_vm.$t('labels.name_singular'))])])," ",_vm._h('div',{staticClass:"input-field col s12 m6 l6"},[_vm._h('input',{attrs:{"type":"text","placeholder":_vm.$t('labels.name_plural'),"name":"name_plural","value":""}})," ",_vm._h('label',{attrs:{"for":"name_plural"}},[_vm._s(_vm.$t('labels.name_plural'))])])])])," ",_vm._h('div',{staticClass:"col s12 m6 l6"},[_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"input-field col s12"},[_vm._m(0)," ",_vm._h('label',{attrs:{"for":"icon"}},[_vm._s(_vm.$t('labels.icon'))])," ",_vm._h('p',{domProps:{"innerHTML":_vm._s('<i>' + _vm.$t('tooltips.material_icons_list') + '</i>')}})])])])])," ",_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"col s12 m6 l6"},[_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"col s12"},[_vm._h('strong',[_vm._s(_vm.$t('labels.properties'))])," ",_vm._h('p',[_vm._s(_vm.$t('tooltips.generic_components.property_templates'))])])])," ",_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"col s12"},[_vm._h('button',{staticClass:"btn waves-effect waves-light",on:{"click":_vm.add_property}},[_vm._s(_vm.$t('buttons.add_property'))+"\n                                        ",_vm._m(1)])])," ",_vm._m(2)])])," ",_vm._h('div',{staticClass:"col s12 m6 l6"},[_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"col s12"},[_vm._h('strong',[_vm._s(_vm.$t('labels.state'))])," ",_vm._h('p',[_vm._s(_vm.$t('tooltips.generic_components.state_templates'))])])])," ",_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"col s12"},[_vm._h('button',{staticClass:"btn waves-effect waves-light",on:{"click":_vm.add_state}},[_vm._s(_vm.$t('buttons.add_state'))+"\n                                        ",_vm._m(3)])])," ",_vm._m(4)])])])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"input-field col s12"},[_vm._h('button',{staticClass:"btn waves-effect waves-light",attrs:{"type":"submit"}},[_vm._s(_vm.$t('buttons.next'))+"\n                                ",_vm._m(5)])])])])])])])," ",_vm._m(6)," ",_vm._m(7)])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('select',{attrs:{"name":"icon"}},[_vm._h('option',{attrs:{"value":"3d_rotation"}},["3d_rotation"])," ",_vm._h('option',{attrs:{"value":"ac_unit"}},["ac_unit"])," ",_vm._h('option',{attrs:{"value":"access_alarm"}},["access_alarm"])," ",_vm._h('option',{attrs:{"value":"access_alarms"}},["access_alarms"])," ",_vm._h('option',{attrs:{"value":"access_time"}},["access_time"])," ",_vm._h('option',{attrs:{"value":"accessibility"}},["accessibility"])," ",_vm._h('option',{attrs:{"value":"accessible"}},["accessible"])," ",_vm._h('option',{attrs:{"value":"account_balance"}},["account_balance"])," ",_vm._h('option',{attrs:{"value":"account_balance_wallet"}},["account_balance_wallet"])," ",_vm._h('option',{attrs:{"value":"account_box"}},["account_box"])," ",_vm._h('option',{attrs:{"value":"account_circle"}},["account_circle"])," ",_vm._h('option',{attrs:{"value":"adb"}},["adb"])," ",_vm._h('option',{attrs:{"value":"add"}},["add"])," ",_vm._h('option',{attrs:{"value":"add_a_photo"}},["add_a_photo"])," ",_vm._h('option',{attrs:{"value":"add_alarm"}},["add_alarm"])," ",_vm._h('option',{attrs:{"value":"add_alert"}},["add_alert"])," ",_vm._h('option',{attrs:{"value":"add_box"}},["add_box"])," ",_vm._h('option',{attrs:{"value":"add_circle"}},["add_circle"])," ",_vm._h('option',{attrs:{"value":"add_circle_outline"}},["add_circle_outline"])," ",_vm._h('option',{attrs:{"value":"add_location"}},["add_location"])," ",_vm._h('option',{attrs:{"value":"add_shopping_cart"}},["add_shopping_cart"])," ",_vm._h('option',{attrs:{"value":"add_to_photos"}},["add_to_photos"])," ",_vm._h('option',{attrs:{"value":"add_to_queue"}},["add_to_queue"])," ",_vm._h('option',{attrs:{"value":"adjust"}},["adjust"])," ",_vm._h('option',{attrs:{"value":"airline_seat_flat"}},["airline_seat_flat"])," ",_vm._h('option',{attrs:{"value":"airline_seat_flat_angled"}},["airline_seat_flat_angled"])," ",_vm._h('option',{attrs:{"value":"airline_seat_individual_suite"}},["airline_seat_individual_suite"])," ",_vm._h('option',{attrs:{"value":"airline_seat_legroom_extra"}},["airline_seat_legroom_extra"])," ",_vm._h('option',{attrs:{"value":"airline_seat_legroom_normal"}},["airline_seat_legroom_normal"])," ",_vm._h('option',{attrs:{"value":"airline_seat_legroom_reduced"}},["airline_seat_legroom_reduced"])," ",_vm._h('option',{attrs:{"value":"airline_seat_recline_extra"}},["airline_seat_recline_extra"])," ",_vm._h('option',{attrs:{"value":"airline_seat_recline_normal"}},["airline_seat_recline_normal"])," ",_vm._h('option',{attrs:{"value":"airplanemode_active"}},["airplanemode_active"])," ",_vm._h('option',{attrs:{"value":"airplanemode_inactive"}},["airplanemode_inactive"])," ",_vm._h('option',{attrs:{"value":"airplay"}},["airplay"])," ",_vm._h('option',{attrs:{"value":"airport_shuttle"}},["airport_shuttle"])," ",_vm._h('option',{attrs:{"value":"alarm"}},["alarm"])," ",_vm._h('option',{attrs:{"value":"alarm_add"}},["alarm_add"])," ",_vm._h('option',{attrs:{"value":"alarm_off"}},["alarm_off"])," ",_vm._h('option',{attrs:{"value":"alarm_on"}},["alarm_on"])," ",_vm._h('option',{attrs:{"value":"album"}},["album"])," ",_vm._h('option',{attrs:{"value":"all_inclusive"}},["all_inclusive"])," ",_vm._h('option',{attrs:{"value":"all_out"}},["all_out"])," ",_vm._h('option',{attrs:{"value":"android"}},["android"])," ",_vm._h('option',{attrs:{"value":"announcement"}},["announcement"])," ",_vm._h('option',{attrs:{"value":"apps"}},["apps"])," ",_vm._h('option',{attrs:{"value":"archive"}},["archive"])," ",_vm._h('option',{attrs:{"value":"arrow_back"}},["arrow_back"])," ",_vm._h('option',{attrs:{"value":"arrow_downward"}},["arrow_downward"])," ",_vm._h('option',{attrs:{"value":"arrow_drop_down"}},["arrow_drop_down"])," ",_vm._h('option',{attrs:{"value":"arrow_drop_down_circle"}},["arrow_drop_down_circle"])," ",_vm._h('option',{attrs:{"value":"arrow_drop_up"}},["arrow_drop_up"])," ",_vm._h('option',{attrs:{"value":"arrow_forward"}},["arrow_forward"])," ",_vm._h('option',{attrs:{"value":"arrow_upward"}},["arrow_upward"])," ",_vm._h('option',{attrs:{"value":"art_track"}},["art_track"])," ",_vm._h('option',{attrs:{"value":"aspect_ratio"}},["aspect_ratio"])," ",_vm._h('option',{attrs:{"value":"assessment"}},["assessment"])," ",_vm._h('option',{attrs:{"value":"assignment"}},["assignment"])," ",_vm._h('option',{attrs:{"value":"assignment_ind"}},["assignment_ind"])," ",_vm._h('option',{attrs:{"value":"assignment_late"}},["assignment_late"])," ",_vm._h('option',{attrs:{"value":"assignment_return"}},["assignment_return"])," ",_vm._h('option',{attrs:{"value":"assignment_returned"}},["assignment_returned"])," ",_vm._h('option',{attrs:{"value":"assignment_turned_in"}},["assignment_turned_in"])," ",_vm._h('option',{attrs:{"value":"assistant"}},["assistant"])," ",_vm._h('option',{attrs:{"value":"assistant_photo"}},["assistant_photo"])," ",_vm._h('option',{attrs:{"value":"attach_file"}},["attach_file"])," ",_vm._h('option',{attrs:{"value":"attach_money"}},["attach_money"])," ",_vm._h('option',{attrs:{"value":"attachment"}},["attachment"])," ",_vm._h('option',{attrs:{"value":"audiotrack"}},["audiotrack"])," ",_vm._h('option',{attrs:{"value":"autorenew"}},["autorenew"])," ",_vm._h('option',{attrs:{"value":"av_timer"}},["av_timer"])," ",_vm._h('option',{attrs:{"value":"backspace"}},["backspace"])," ",_vm._h('option',{attrs:{"value":"backup"}},["backup"])," ",_vm._h('option',{attrs:{"value":"battery_alert"}},["battery_alert"])," ",_vm._h('option',{attrs:{"value":"battery_charging_full"}},["battery_charging_full"])," ",_vm._h('option',{attrs:{"value":"battery_full"}},["battery_full"])," ",_vm._h('option',{attrs:{"value":"battery_std"}},["battery_std"])," ",_vm._h('option',{attrs:{"value":"battery_unknown"}},["battery_unknown"])," ",_vm._h('option',{attrs:{"value":"beach_access"}},["beach_access"])," ",_vm._h('option',{attrs:{"value":"beenhere"}},["beenhere"])," ",_vm._h('option',{attrs:{"value":"block"}},["block"])," ",_vm._h('option',{attrs:{"value":"bluetooth"}},["bluetooth"])," ",_vm._h('option',{attrs:{"value":"bluetooth_audio"}},["bluetooth_audio"])," ",_vm._h('option',{attrs:{"value":"bluetooth_connected"}},["bluetooth_connected"])," ",_vm._h('option',{attrs:{"value":"bluetooth_disabled"}},["bluetooth_disabled"])," ",_vm._h('option',{attrs:{"value":"bluetooth_searching"}},["bluetooth_searching"])," ",_vm._h('option',{attrs:{"value":"blur_circular"}},["blur_circular"])," ",_vm._h('option',{attrs:{"value":"blur_linear"}},["blur_linear"])," ",_vm._h('option',{attrs:{"value":"blur_off"}},["blur_off"])," ",_vm._h('option',{attrs:{"value":"blur_on"}},["blur_on"])," ",_vm._h('option',{attrs:{"value":"book"}},["book"])," ",_vm._h('option',{attrs:{"value":"bookmark"}},["bookmark"])," ",_vm._h('option',{attrs:{"value":"bookmark_border"}},["bookmark_border"])," ",_vm._h('option',{attrs:{"value":"border_all"}},["border_all"])," ",_vm._h('option',{attrs:{"value":"border_bottom"}},["border_bottom"])," ",_vm._h('option',{attrs:{"value":"border_clear"}},["border_clear"])," ",_vm._h('option',{attrs:{"value":"border_color"}},["border_color"])," ",_vm._h('option',{attrs:{"value":"border_horizontal"}},["border_horizontal"])," ",_vm._h('option',{attrs:{"value":"border_inner"}},["border_inner"])," ",_vm._h('option',{attrs:{"value":"border_left"}},["border_left"])," ",_vm._h('option',{attrs:{"value":"border_outer"}},["border_outer"])," ",_vm._h('option',{attrs:{"value":"border_right"}},["border_right"])," ",_vm._h('option',{attrs:{"value":"border_style"}},["border_style"])," ",_vm._h('option',{attrs:{"value":"border_top"}},["border_top"])," ",_vm._h('option',{attrs:{"value":"border_vertical"}},["border_vertical"])," ",_vm._h('option',{attrs:{"value":"branding_watermark"}},["branding_watermark"])," ",_vm._h('option',{attrs:{"value":"brightness_1"}},["brightness_1"])," ",_vm._h('option',{attrs:{"value":"brightness_2"}},["brightness_2"])," ",_vm._h('option',{attrs:{"value":"brightness_3"}},["brightness_3"])," ",_vm._h('option',{attrs:{"value":"brightness_4"}},["brightness_4"])," ",_vm._h('option',{attrs:{"value":"brightness_5"}},["brightness_5"])," ",_vm._h('option',{attrs:{"value":"brightness_6"}},["brightness_6"])," ",_vm._h('option',{attrs:{"value":"brightness_7"}},["brightness_7"])," ",_vm._h('option',{attrs:{"value":"brightness_auto"}},["brightness_auto"])," ",_vm._h('option',{attrs:{"value":"brightness_high"}},["brightness_high"])," ",_vm._h('option',{attrs:{"value":"brightness_low"}},["brightness_low"])," ",_vm._h('option',{attrs:{"value":"brightness_medium"}},["brightness_medium"])," ",_vm._h('option',{attrs:{"value":"broken_image"}},["broken_image"])," ",_vm._h('option',{attrs:{"value":"brush"}},["brush"])," ",_vm._h('option',{attrs:{"value":"bubble_chart"}},["bubble_chart"])," ",_vm._h('option',{attrs:{"value":"bug_report"}},["bug_report"])," ",_vm._h('option',{attrs:{"value":"build"}},["build"])," ",_vm._h('option',{attrs:{"value":"burst_mode"}},["burst_mode"])," ",_vm._h('option',{attrs:{"value":"business"}},["business"])," ",_vm._h('option',{attrs:{"value":"business_center"}},["business_center"])," ",_vm._h('option',{attrs:{"value":"cached"}},["cached"])," ",_vm._h('option',{attrs:{"value":"cake"}},["cake"])," ",_vm._h('option',{attrs:{"value":"call"}},["call"])," ",_vm._h('option',{attrs:{"value":"call_end"}},["call_end"])," ",_vm._h('option',{attrs:{"value":"call_made"}},["call_made"])," ",_vm._h('option',{attrs:{"value":"call_merge"}},["call_merge"])," ",_vm._h('option',{attrs:{"value":"call_missed"}},["call_missed"])," ",_vm._h('option',{attrs:{"value":"call_missed_outgoing"}},["call_missed_outgoing"])," ",_vm._h('option',{attrs:{"value":"call_received"}},["call_received"])," ",_vm._h('option',{attrs:{"value":"call_split"}},["call_split"])," ",_vm._h('option',{attrs:{"value":"call_to_action"}},["call_to_action"])," ",_vm._h('option',{attrs:{"value":"camera"}},["camera"])," ",_vm._h('option',{attrs:{"value":"camera_alt"}},["camera_alt"])," ",_vm._h('option',{attrs:{"value":"camera_enhance"}},["camera_enhance"])," ",_vm._h('option',{attrs:{"value":"camera_front"}},["camera_front"])," ",_vm._h('option',{attrs:{"value":"camera_rear"}},["camera_rear"])," ",_vm._h('option',{attrs:{"value":"camera_roll"}},["camera_roll"])," ",_vm._h('option',{attrs:{"value":"cancel"}},["cancel"])," ",_vm._h('option',{attrs:{"value":"card_giftcard"}},["card_giftcard"])," ",_vm._h('option',{attrs:{"value":"card_membership"}},["card_membership"])," ",_vm._h('option',{attrs:{"value":"card_travel"}},["card_travel"])," ",_vm._h('option',{attrs:{"value":"casino"}},["casino"])," ",_vm._h('option',{attrs:{"value":"cast"}},["cast"])," ",_vm._h('option',{attrs:{"value":"cast_connected"}},["cast_connected"])," ",_vm._h('option',{attrs:{"value":"center_focus_strong"}},["center_focus_strong"])," ",_vm._h('option',{attrs:{"value":"center_focus_weak"}},["center_focus_weak"])," ",_vm._h('option',{attrs:{"value":"change_history"}},["change_history"])," ",_vm._h('option',{attrs:{"value":"chat"}},["chat"])," ",_vm._h('option',{attrs:{"value":"chat_bubble"}},["chat_bubble"])," ",_vm._h('option',{attrs:{"value":"chat_bubble_outline"}},["chat_bubble_outline"])," ",_vm._h('option',{attrs:{"value":"check"}},["check"])," ",_vm._h('option',{attrs:{"value":"check_box"}},["check_box"])," ",_vm._h('option',{attrs:{"value":"check_box_outline_blank"}},["check_box_outline_blank"])," ",_vm._h('option',{attrs:{"value":"check_circle"}},["check_circle"])," ",_vm._h('option',{attrs:{"value":"chevron_left"}},["chevron_left"])," ",_vm._h('option',{attrs:{"value":"chevron_right"}},["chevron_right"])," ",_vm._h('option',{attrs:{"value":"child_care"}},["child_care"])," ",_vm._h('option',{attrs:{"value":"child_friendly"}},["child_friendly"])," ",_vm._h('option',{attrs:{"value":"chrome_reader_mode"}},["chrome_reader_mode"])," ",_vm._h('option',{attrs:{"value":"class"}},["class"])," ",_vm._h('option',{attrs:{"value":"clear"}},["clear"])," ",_vm._h('option',{attrs:{"value":"clear_all"}},["clear_all"])," ",_vm._h('option',{attrs:{"value":"close"}},["close"])," ",_vm._h('option',{attrs:{"value":"closed_caption"}},["closed_caption"])," ",_vm._h('option',{attrs:{"value":"cloud"}},["cloud"])," ",_vm._h('option',{attrs:{"value":"cloud_circle"}},["cloud_circle"])," ",_vm._h('option',{attrs:{"value":"cloud_done"}},["cloud_done"])," ",_vm._h('option',{attrs:{"value":"cloud_download"}},["cloud_download"])," ",_vm._h('option',{attrs:{"value":"cloud_off"}},["cloud_off"])," ",_vm._h('option',{attrs:{"value":"cloud_queue"}},["cloud_queue"])," ",_vm._h('option',{attrs:{"value":"cloud_upload"}},["cloud_upload"])," ",_vm._h('option',{attrs:{"value":"code"}},["code"])," ",_vm._h('option',{attrs:{"value":"collections"}},["collections"])," ",_vm._h('option',{attrs:{"value":"collections_bookmark"}},["collections_bookmark"])," ",_vm._h('option',{attrs:{"value":"color_lens"}},["color_lens"])," ",_vm._h('option',{attrs:{"value":"colorize"}},["colorize"])," ",_vm._h('option',{attrs:{"value":"comment"}},["comment"])," ",_vm._h('option',{attrs:{"value":"compare"}},["compare"])," ",_vm._h('option',{attrs:{"value":"compare_arrows"}},["compare_arrows"])," ",_vm._h('option',{attrs:{"value":"computer"}},["computer"])," ",_vm._h('option',{attrs:{"value":"confirmation_number"}},["confirmation_number"])," ",_vm._h('option',{attrs:{"value":"contact_mail"}},["contact_mail"])," ",_vm._h('option',{attrs:{"value":"contact_phone"}},["contact_phone"])," ",_vm._h('option',{attrs:{"value":"contacts"}},["contacts"])," ",_vm._h('option',{attrs:{"value":"content_copy"}},["content_copy"])," ",_vm._h('option',{attrs:{"value":"content_cut"}},["content_cut"])," ",_vm._h('option',{attrs:{"value":"content_paste"}},["content_paste"])," ",_vm._h('option',{attrs:{"value":"control_point"}},["control_point"])," ",_vm._h('option',{attrs:{"value":"control_point_duplicate"}},["control_point_duplicate"])," ",_vm._h('option',{attrs:{"value":"copyright"}},["copyright"])," ",_vm._h('option',{attrs:{"value":"create"}},["create"])," ",_vm._h('option',{attrs:{"value":"create_new_folder"}},["create_new_folder"])," ",_vm._h('option',{attrs:{"value":"credit_card"}},["credit_card"])," ",_vm._h('option',{attrs:{"value":"crop"}},["crop"])," ",_vm._h('option',{attrs:{"value":"crop_16_9"}},["crop_16_9"])," ",_vm._h('option',{attrs:{"value":"crop_3_2"}},["crop_3_2"])," ",_vm._h('option',{attrs:{"value":"crop_5_4"}},["crop_5_4"])," ",_vm._h('option',{attrs:{"value":"crop_7_5"}},["crop_7_5"])," ",_vm._h('option',{attrs:{"value":"crop_din"}},["crop_din"])," ",_vm._h('option',{attrs:{"value":"crop_free"}},["crop_free"])," ",_vm._h('option',{attrs:{"value":"crop_landscape"}},["crop_landscape"])," ",_vm._h('option',{attrs:{"value":"crop_original"}},["crop_original"])," ",_vm._h('option',{attrs:{"value":"crop_portrait"}},["crop_portrait"])," ",_vm._h('option',{attrs:{"value":"crop_rotate"}},["crop_rotate"])," ",_vm._h('option',{attrs:{"value":"crop_square"}},["crop_square"])," ",_vm._h('option',{attrs:{"value":"dashboard"}},["dashboard"])," ",_vm._h('option',{attrs:{"value":"data_usage"}},["data_usage"])," ",_vm._h('option',{attrs:{"value":"date_range"}},["date_range"])," ",_vm._h('option',{attrs:{"value":"dehaze"}},["dehaze"])," ",_vm._h('option',{attrs:{"value":"delete"}},["delete"])," ",_vm._h('option',{attrs:{"value":"delete_forever"}},["delete_forever"])," ",_vm._h('option',{attrs:{"value":"delete_sweep"}},["delete_sweep"])," ",_vm._h('option',{attrs:{"value":"description"}},["description"])," ",_vm._h('option',{attrs:{"value":"desktop_mac"}},["desktop_mac"])," ",_vm._h('option',{attrs:{"value":"desktop_windows"}},["desktop_windows"])," ",_vm._h('option',{attrs:{"value":"details"}},["details"])," ",_vm._h('option',{attrs:{"value":"developer_board"}},["developer_board"])," ",_vm._h('option',{attrs:{"value":"developer_mode"}},["developer_mode"])," ",_vm._h('option',{attrs:{"value":"device_hub"}},["device_hub"])," ",_vm._h('option',{attrs:{"value":"devices"}},["devices"])," ",_vm._h('option',{attrs:{"value":"devices_other"}},["devices_other"])," ",_vm._h('option',{attrs:{"value":"dialer_sip"}},["dialer_sip"])," ",_vm._h('option',{attrs:{"value":"dialpad"}},["dialpad"])," ",_vm._h('option',{attrs:{"value":"directions"}},["directions"])," ",_vm._h('option',{attrs:{"value":"directions_bike"}},["directions_bike"])," ",_vm._h('option',{attrs:{"value":"directions_boat"}},["directions_boat"])," ",_vm._h('option',{attrs:{"value":"directions_bus"}},["directions_bus"])," ",_vm._h('option',{attrs:{"value":"directions_car"}},["directions_car"])," ",_vm._h('option',{attrs:{"value":"directions_railway"}},["directions_railway"])," ",_vm._h('option',{attrs:{"value":"directions_run"}},["directions_run"])," ",_vm._h('option',{attrs:{"value":"directions_subway"}},["directions_subway"])," ",_vm._h('option',{attrs:{"value":"directions_transit"}},["directions_transit"])," ",_vm._h('option',{attrs:{"value":"directions_walk"}},["directions_walk"])," ",_vm._h('option',{attrs:{"value":"disc_full"}},["disc_full"])," ",_vm._h('option',{attrs:{"value":"dns"}},["dns"])," ",_vm._h('option',{attrs:{"value":"do_not_disturb"}},["do_not_disturb"])," ",_vm._h('option',{attrs:{"value":"do_not_disturb_alt"}},["do_not_disturb_alt"])," ",_vm._h('option',{attrs:{"value":"do_not_disturb_off"}},["do_not_disturb_off"])," ",_vm._h('option',{attrs:{"value":"do_not_disturb_on"}},["do_not_disturb_on"])," ",_vm._h('option',{attrs:{"value":"dock"}},["dock"])," ",_vm._h('option',{attrs:{"value":"domain"}},["domain"])," ",_vm._h('option',{attrs:{"value":"done"}},["done"])," ",_vm._h('option',{attrs:{"value":"done_all"}},["done_all"])," ",_vm._h('option',{attrs:{"value":"donut_large"}},["donut_large"])," ",_vm._h('option',{attrs:{"value":"donut_small"}},["donut_small"])," ",_vm._h('option',{attrs:{"value":"drafts"}},["drafts"])," ",_vm._h('option',{attrs:{"value":"drag_handle"}},["drag_handle"])," ",_vm._h('option',{attrs:{"value":"drive_eta"}},["drive_eta"])," ",_vm._h('option',{attrs:{"value":"dvr"}},["dvr"])," ",_vm._h('option',{attrs:{"value":"edit"}},["edit"])," ",_vm._h('option',{attrs:{"value":"edit_location"}},["edit_location"])," ",_vm._h('option',{attrs:{"value":"eject"}},["eject"])," ",_vm._h('option',{attrs:{"value":"email"}},["email"])," ",_vm._h('option',{attrs:{"value":"enhanced_encryption"}},["enhanced_encryption"])," ",_vm._h('option',{attrs:{"value":"equalizer"}},["equalizer"])," ",_vm._h('option',{attrs:{"value":"error"}},["error"])," ",_vm._h('option',{attrs:{"value":"error_outline"}},["error_outline"])," ",_vm._h('option',{attrs:{"value":"euro_symbol"}},["euro_symbol"])," ",_vm._h('option',{attrs:{"value":"ev_station"}},["ev_station"])," ",_vm._h('option',{attrs:{"value":"event"}},["event"])," ",_vm._h('option',{attrs:{"value":"event_available"}},["event_available"])," ",_vm._h('option',{attrs:{"value":"event_busy"}},["event_busy"])," ",_vm._h('option',{attrs:{"value":"event_note"}},["event_note"])," ",_vm._h('option',{attrs:{"value":"event_seat"}},["event_seat"])," ",_vm._h('option',{attrs:{"value":"exit_to_app"}},["exit_to_app"])," ",_vm._h('option',{attrs:{"value":"expand_less"}},["expand_less"])," ",_vm._h('option',{attrs:{"value":"expand_more"}},["expand_more"])," ",_vm._h('option',{attrs:{"value":"explicit"}},["explicit"])," ",_vm._h('option',{attrs:{"value":"explore"}},["explore"])," ",_vm._h('option',{attrs:{"value":"exposure"}},["exposure"])," ",_vm._h('option',{attrs:{"value":"exposure_neg_1"}},["exposure_neg_1"])," ",_vm._h('option',{attrs:{"value":"exposure_neg_2"}},["exposure_neg_2"])," ",_vm._h('option',{attrs:{"value":"exposure_plus_1"}},["exposure_plus_1"])," ",_vm._h('option',{attrs:{"value":"exposure_plus_2"}},["exposure_plus_2"])," ",_vm._h('option',{attrs:{"value":"exposure_zero"}},["exposure_zero"])," ",_vm._h('option',{attrs:{"value":"extension"}},["extension"])," ",_vm._h('option',{attrs:{"value":"face"}},["face"])," ",_vm._h('option',{attrs:{"value":"fast_forward"}},["fast_forward"])," ",_vm._h('option',{attrs:{"value":"fast_rewind"}},["fast_rewind"])," ",_vm._h('option',{attrs:{"value":"favorite"}},["favorite"])," ",_vm._h('option',{attrs:{"value":"favorite_border"}},["favorite_border"])," ",_vm._h('option',{attrs:{"value":"featured_play_list"}},["featured_play_list"])," ",_vm._h('option',{attrs:{"value":"featured_video"}},["featured_video"])," ",_vm._h('option',{attrs:{"value":"feedback"}},["feedback"])," ",_vm._h('option',{attrs:{"value":"fiber_dvr"}},["fiber_dvr"])," ",_vm._h('option',{attrs:{"value":"fiber_manual_record"}},["fiber_manual_record"])," ",_vm._h('option',{attrs:{"value":"fiber_new"}},["fiber_new"])," ",_vm._h('option',{attrs:{"value":"fiber_pin"}},["fiber_pin"])," ",_vm._h('option',{attrs:{"value":"fiber_smart_record"}},["fiber_smart_record"])," ",_vm._h('option',{attrs:{"value":"file_download"}},["file_download"])," ",_vm._h('option',{attrs:{"value":"file_upload"}},["file_upload"])," ",_vm._h('option',{attrs:{"value":"filter"}},["filter"])," ",_vm._h('option',{attrs:{"value":"filter_1"}},["filter_1"])," ",_vm._h('option',{attrs:{"value":"filter_2"}},["filter_2"])," ",_vm._h('option',{attrs:{"value":"filter_3"}},["filter_3"])," ",_vm._h('option',{attrs:{"value":"filter_4"}},["filter_4"])," ",_vm._h('option',{attrs:{"value":"filter_5"}},["filter_5"])," ",_vm._h('option',{attrs:{"value":"filter_6"}},["filter_6"])," ",_vm._h('option',{attrs:{"value":"filter_7"}},["filter_7"])," ",_vm._h('option',{attrs:{"value":"filter_8"}},["filter_8"])," ",_vm._h('option',{attrs:{"value":"filter_9"}},["filter_9"])," ",_vm._h('option',{attrs:{"value":"filter_9_plus"}},["filter_9_plus"])," ",_vm._h('option',{attrs:{"value":"filter_b_and_w"}},["filter_b_and_w"])," ",_vm._h('option',{attrs:{"value":"filter_center_focus"}},["filter_center_focus"])," ",_vm._h('option',{attrs:{"value":"filter_drama"}},["filter_drama"])," ",_vm._h('option',{attrs:{"value":"filter_frames"}},["filter_frames"])," ",_vm._h('option',{attrs:{"value":"filter_hdr"}},["filter_hdr"])," ",_vm._h('option',{attrs:{"value":"filter_list"}},["filter_list"])," ",_vm._h('option',{attrs:{"value":"filter_none"}},["filter_none"])," ",_vm._h('option',{attrs:{"value":"filter_tilt_shift"}},["filter_tilt_shift"])," ",_vm._h('option',{attrs:{"value":"filter_vintage"}},["filter_vintage"])," ",_vm._h('option',{attrs:{"value":"find_in_page"}},["find_in_page"])," ",_vm._h('option',{attrs:{"value":"find_replace"}},["find_replace"])," ",_vm._h('option',{attrs:{"value":"fingerprint"}},["fingerprint"])," ",_vm._h('option',{attrs:{"value":"first_page"}},["first_page"])," ",_vm._h('option',{attrs:{"value":"fitness_center"}},["fitness_center"])," ",_vm._h('option',{attrs:{"value":"flag"}},["flag"])," ",_vm._h('option',{attrs:{"value":"flare"}},["flare"])," ",_vm._h('option',{attrs:{"value":"flash_auto"}},["flash_auto"])," ",_vm._h('option',{attrs:{"value":"flash_off"}},["flash_off"])," ",_vm._h('option',{attrs:{"value":"flash_on"}},["flash_on"])," ",_vm._h('option',{attrs:{"value":"flight"}},["flight"])," ",_vm._h('option',{attrs:{"value":"flight_land"}},["flight_land"])," ",_vm._h('option',{attrs:{"value":"flight_takeoff"}},["flight_takeoff"])," ",_vm._h('option',{attrs:{"value":"flip"}},["flip"])," ",_vm._h('option',{attrs:{"value":"flip_to_back"}},["flip_to_back"])," ",_vm._h('option',{attrs:{"value":"flip_to_front"}},["flip_to_front"])," ",_vm._h('option',{attrs:{"value":"folder"}},["folder"])," ",_vm._h('option',{attrs:{"value":"folder_open"}},["folder_open"])," ",_vm._h('option',{attrs:{"value":"folder_shared"}},["folder_shared"])," ",_vm._h('option',{attrs:{"value":"folder_special"}},["folder_special"])," ",_vm._h('option',{attrs:{"value":"font_download"}},["font_download"])," ",_vm._h('option',{attrs:{"value":"format_align_center"}},["format_align_center"])," ",_vm._h('option',{attrs:{"value":"format_align_justify"}},["format_align_justify"])," ",_vm._h('option',{attrs:{"value":"format_align_left"}},["format_align_left"])," ",_vm._h('option',{attrs:{"value":"format_align_right"}},["format_align_right"])," ",_vm._h('option',{attrs:{"value":"format_bold"}},["format_bold"])," ",_vm._h('option',{attrs:{"value":"format_clear"}},["format_clear"])," ",_vm._h('option',{attrs:{"value":"format_color_fill"}},["format_color_fill"])," ",_vm._h('option',{attrs:{"value":"format_color_reset"}},["format_color_reset"])," ",_vm._h('option',{attrs:{"value":"format_color_text"}},["format_color_text"])," ",_vm._h('option',{attrs:{"value":"format_indent_decrease"}},["format_indent_decrease"])," ",_vm._h('option',{attrs:{"value":"format_indent_increase"}},["format_indent_increase"])," ",_vm._h('option',{attrs:{"value":"format_italic"}},["format_italic"])," ",_vm._h('option',{attrs:{"value":"format_line_spacing"}},["format_line_spacing"])," ",_vm._h('option',{attrs:{"value":"format_list_bulleted"}},["format_list_bulleted"])," ",_vm._h('option',{attrs:{"value":"format_list_numbered"}},["format_list_numbered"])," ",_vm._h('option',{attrs:{"value":"format_paint"}},["format_paint"])," ",_vm._h('option',{attrs:{"value":"format_quote"}},["format_quote"])," ",_vm._h('option',{attrs:{"value":"format_shapes"}},["format_shapes"])," ",_vm._h('option',{attrs:{"value":"format_size"}},["format_size"])," ",_vm._h('option',{attrs:{"value":"format_strikethrough"}},["format_strikethrough"])," ",_vm._h('option',{attrs:{"value":"format_textdirection_l_to_r"}},["format_textdirection_l_to_r"])," ",_vm._h('option',{attrs:{"value":"format_textdirection_r_to_l"}},["format_textdirection_r_to_l"])," ",_vm._h('option',{attrs:{"value":"format_underlined"}},["format_underlined"])," ",_vm._h('option',{attrs:{"value":"forum"}},["forum"])," ",_vm._h('option',{attrs:{"value":"forward"}},["forward"])," ",_vm._h('option',{attrs:{"value":"forward_10"}},["forward_10"])," ",_vm._h('option',{attrs:{"value":"forward_30"}},["forward_30"])," ",_vm._h('option',{attrs:{"value":"forward_5"}},["forward_5"])," ",_vm._h('option',{attrs:{"value":"free_breakfast"}},["free_breakfast"])," ",_vm._h('option',{attrs:{"value":"fullscreen"}},["fullscreen"])," ",_vm._h('option',{attrs:{"value":"fullscreen_exit"}},["fullscreen_exit"])," ",_vm._h('option',{attrs:{"value":"functions"}},["functions"])," ",_vm._h('option',{attrs:{"value":"g_translate"}},["g_translate"])," ",_vm._h('option',{attrs:{"value":"gamepad"}},["gamepad"])," ",_vm._h('option',{attrs:{"value":"games"}},["games"])," ",_vm._h('option',{attrs:{"value":"gavel"}},["gavel"])," ",_vm._h('option',{attrs:{"value":"gesture"}},["gesture"])," ",_vm._h('option',{attrs:{"value":"get_app"}},["get_app"])," ",_vm._h('option',{attrs:{"value":"gif"}},["gif"])," ",_vm._h('option',{attrs:{"value":"golf_course"}},["golf_course"])," ",_vm._h('option',{attrs:{"value":"gps_fixed"}},["gps_fixed"])," ",_vm._h('option',{attrs:{"value":"gps_not_fixed"}},["gps_not_fixed"])," ",_vm._h('option',{attrs:{"value":"gps_off"}},["gps_off"])," ",_vm._h('option',{attrs:{"value":"grade"}},["grade"])," ",_vm._h('option',{attrs:{"value":"gradient"}},["gradient"])," ",_vm._h('option',{attrs:{"value":"grain"}},["grain"])," ",_vm._h('option',{attrs:{"value":"graphic_eq"}},["graphic_eq"])," ",_vm._h('option',{attrs:{"value":"grid_off"}},["grid_off"])," ",_vm._h('option',{attrs:{"value":"grid_on"}},["grid_on"])," ",_vm._h('option',{attrs:{"value":"group"}},["group"])," ",_vm._h('option',{attrs:{"value":"group_add"}},["group_add"])," ",_vm._h('option',{attrs:{"value":"group_work"}},["group_work"])," ",_vm._h('option',{attrs:{"value":"hd"}},["hd"])," ",_vm._h('option',{attrs:{"value":"hdr_off"}},["hdr_off"])," ",_vm._h('option',{attrs:{"value":"hdr_on"}},["hdr_on"])," ",_vm._h('option',{attrs:{"value":"hdr_strong"}},["hdr_strong"])," ",_vm._h('option',{attrs:{"value":"hdr_weak"}},["hdr_weak"])," ",_vm._h('option',{attrs:{"value":"headset"}},["headset"])," ",_vm._h('option',{attrs:{"value":"headset_mic"}},["headset_mic"])," ",_vm._h('option',{attrs:{"value":"healing"}},["healing"])," ",_vm._h('option',{attrs:{"value":"hearing"}},["hearing"])," ",_vm._h('option',{attrs:{"value":"help"}},["help"])," ",_vm._h('option',{attrs:{"value":"help_outline"}},["help_outline"])," ",_vm._h('option',{attrs:{"value":"high_quality"}},["high_quality"])," ",_vm._h('option',{attrs:{"value":"highlight"}},["highlight"])," ",_vm._h('option',{attrs:{"value":"highlight_off"}},["highlight_off"])," ",_vm._h('option',{attrs:{"value":"history"}},["history"])," ",_vm._h('option',{attrs:{"value":"home"}},["home"])," ",_vm._h('option',{attrs:{"value":"hot_tub"}},["hot_tub"])," ",_vm._h('option',{attrs:{"value":"hotel"}},["hotel"])," ",_vm._h('option',{attrs:{"value":"hourglass_empty"}},["hourglass_empty"])," ",_vm._h('option',{attrs:{"value":"hourglass_full"}},["hourglass_full"])," ",_vm._h('option',{attrs:{"value":"http"}},["http"])," ",_vm._h('option',{attrs:{"value":"https"}},["https"])," ",_vm._h('option',{attrs:{"value":"image"}},["image"])," ",_vm._h('option',{attrs:{"value":"image_aspect_ratio"}},["image_aspect_ratio"])," ",_vm._h('option',{attrs:{"value":"import_contacts"}},["import_contacts"])," ",_vm._h('option',{attrs:{"value":"import_export"}},["import_export"])," ",_vm._h('option',{attrs:{"value":"important_devices"}},["important_devices"])," ",_vm._h('option',{attrs:{"value":"inbox"}},["inbox"])," ",_vm._h('option',{attrs:{"value":"indeterminate_check_box"}},["indeterminate_check_box"])," ",_vm._h('option',{attrs:{"value":"info"}},["info"])," ",_vm._h('option',{attrs:{"value":"info_outline"}},["info_outline"])," ",_vm._h('option',{attrs:{"value":"input"}},["input"])," ",_vm._h('option',{attrs:{"value":"insert_chart"}},["insert_chart"])," ",_vm._h('option',{attrs:{"value":"insert_comment"}},["insert_comment"])," ",_vm._h('option',{attrs:{"value":"insert_drive_file"}},["insert_drive_file"])," ",_vm._h('option',{attrs:{"value":"insert_emoticon"}},["insert_emoticon"])," ",_vm._h('option',{attrs:{"value":"insert_invitation"}},["insert_invitation"])," ",_vm._h('option',{attrs:{"value":"insert_link"}},["insert_link"])," ",_vm._h('option',{attrs:{"value":"insert_photo"}},["insert_photo"])," ",_vm._h('option',{attrs:{"value":"invert_colors"}},["invert_colors"])," ",_vm._h('option',{attrs:{"value":"invert_colors_off"}},["invert_colors_off"])," ",_vm._h('option',{attrs:{"value":"iso"}},["iso"])," ",_vm._h('option',{attrs:{"value":"keyboard"}},["keyboard"])," ",_vm._h('option',{attrs:{"value":"keyboard_arrow_down"}},["keyboard_arrow_down"])," ",_vm._h('option',{attrs:{"value":"keyboard_arrow_left"}},["keyboard_arrow_left"])," ",_vm._h('option',{attrs:{"value":"keyboard_arrow_right"}},["keyboard_arrow_right"])," ",_vm._h('option',{attrs:{"value":"keyboard_arrow_up"}},["keyboard_arrow_up"])," ",_vm._h('option',{attrs:{"value":"keyboard_backspace"}},["keyboard_backspace"])," ",_vm._h('option',{attrs:{"value":"keyboard_capslock"}},["keyboard_capslock"])," ",_vm._h('option',{attrs:{"value":"keyboard_hide"}},["keyboard_hide"])," ",_vm._h('option',{attrs:{"value":"keyboard_return"}},["keyboard_return"])," ",_vm._h('option',{attrs:{"value":"keyboard_tab"}},["keyboard_tab"])," ",_vm._h('option',{attrs:{"value":"keyboard_voice"}},["keyboard_voice"])," ",_vm._h('option',{attrs:{"value":"kitchen"}},["kitchen"])," ",_vm._h('option',{attrs:{"value":"label"}},["label"])," ",_vm._h('option',{attrs:{"value":"label_outline"}},["label_outline"])," ",_vm._h('option',{attrs:{"value":"landscape"}},["landscape"])," ",_vm._h('option',{attrs:{"value":"language"}},["language"])," ",_vm._h('option',{attrs:{"value":"laptop"}},["laptop"])," ",_vm._h('option',{attrs:{"value":"laptop_chromebook"}},["laptop_chromebook"])," ",_vm._h('option',{attrs:{"value":"laptop_mac"}},["laptop_mac"])," ",_vm._h('option',{attrs:{"value":"laptop_windows"}},["laptop_windows"])," ",_vm._h('option',{attrs:{"value":"last_page"}},["last_page"])," ",_vm._h('option',{attrs:{"value":"launch"}},["launch"])," ",_vm._h('option',{attrs:{"value":"layers"}},["layers"])," ",_vm._h('option',{attrs:{"value":"layers_clear"}},["layers_clear"])," ",_vm._h('option',{attrs:{"value":"leak_add"}},["leak_add"])," ",_vm._h('option',{attrs:{"value":"leak_remove"}},["leak_remove"])," ",_vm._h('option',{attrs:{"value":"lens"}},["lens"])," ",_vm._h('option',{attrs:{"value":"library_add"}},["library_add"])," ",_vm._h('option',{attrs:{"value":"library_books"}},["library_books"])," ",_vm._h('option',{attrs:{"value":"library_music"}},["library_music"])," ",_vm._h('option',{attrs:{"value":"lightbulb_outline"}},["lightbulb_outline"])," ",_vm._h('option',{attrs:{"value":"line_style"}},["line_style"])," ",_vm._h('option',{attrs:{"value":"line_weight"}},["line_weight"])," ",_vm._h('option',{attrs:{"value":"linear_scale"}},["linear_scale"])," ",_vm._h('option',{attrs:{"value":"link"}},["link"])," ",_vm._h('option',{attrs:{"value":"linked_camera"}},["linked_camera"])," ",_vm._h('option',{attrs:{"value":"list"}},["list"])," ",_vm._h('option',{attrs:{"value":"live_help"}},["live_help"])," ",_vm._h('option',{attrs:{"value":"live_tv"}},["live_tv"])," ",_vm._h('option',{attrs:{"value":"local_activity"}},["local_activity"])," ",_vm._h('option',{attrs:{"value":"local_airport"}},["local_airport"])," ",_vm._h('option',{attrs:{"value":"local_atm"}},["local_atm"])," ",_vm._h('option',{attrs:{"value":"local_bar"}},["local_bar"])," ",_vm._h('option',{attrs:{"value":"local_cafe"}},["local_cafe"])," ",_vm._h('option',{attrs:{"value":"local_car_wash"}},["local_car_wash"])," ",_vm._h('option',{attrs:{"value":"local_convenience_store"}},["local_convenience_store"])," ",_vm._h('option',{attrs:{"value":"local_dining"}},["local_dining"])," ",_vm._h('option',{attrs:{"value":"local_drink"}},["local_drink"])," ",_vm._h('option',{attrs:{"value":"local_florist"}},["local_florist"])," ",_vm._h('option',{attrs:{"value":"local_gas_station"}},["local_gas_station"])," ",_vm._h('option',{attrs:{"value":"local_grocery_store"}},["local_grocery_store"])," ",_vm._h('option',{attrs:{"value":"local_hospital"}},["local_hospital"])," ",_vm._h('option',{attrs:{"value":"local_hotel"}},["local_hotel"])," ",_vm._h('option',{attrs:{"value":"local_laundry_service"}},["local_laundry_service"])," ",_vm._h('option',{attrs:{"value":"local_library"}},["local_library"])," ",_vm._h('option',{attrs:{"value":"local_mall"}},["local_mall"])," ",_vm._h('option',{attrs:{"value":"local_movies"}},["local_movies"])," ",_vm._h('option',{attrs:{"value":"local_offer"}},["local_offer"])," ",_vm._h('option',{attrs:{"value":"local_parking"}},["local_parking"])," ",_vm._h('option',{attrs:{"value":"local_pharmacy"}},["local_pharmacy"])," ",_vm._h('option',{attrs:{"value":"local_phone"}},["local_phone"])," ",_vm._h('option',{attrs:{"value":"local_pizza"}},["local_pizza"])," ",_vm._h('option',{attrs:{"value":"local_play"}},["local_play"])," ",_vm._h('option',{attrs:{"value":"local_post_office"}},["local_post_office"])," ",_vm._h('option',{attrs:{"value":"local_printshop"}},["local_printshop"])," ",_vm._h('option',{attrs:{"value":"local_see"}},["local_see"])," ",_vm._h('option',{attrs:{"value":"local_shipping"}},["local_shipping"])," ",_vm._h('option',{attrs:{"value":"local_taxi"}},["local_taxi"])," ",_vm._h('option',{attrs:{"value":"location_city"}},["location_city"])," ",_vm._h('option',{attrs:{"value":"location_disabled"}},["location_disabled"])," ",_vm._h('option',{attrs:{"value":"location_off"}},["location_off"])," ",_vm._h('option',{attrs:{"value":"location_on"}},["location_on"])," ",_vm._h('option',{attrs:{"value":"location_searching"}},["location_searching"])," ",_vm._h('option',{attrs:{"value":"lock"}},["lock"])," ",_vm._h('option',{attrs:{"value":"lock_open"}},["lock_open"])," ",_vm._h('option',{attrs:{"value":"lock_outline"}},["lock_outline"])," ",_vm._h('option',{attrs:{"value":"looks"}},["looks"])," ",_vm._h('option',{attrs:{"value":"looks_3"}},["looks_3"])," ",_vm._h('option',{attrs:{"value":"looks_4"}},["looks_4"])," ",_vm._h('option',{attrs:{"value":"looks_5"}},["looks_5"])," ",_vm._h('option',{attrs:{"value":"looks_6"}},["looks_6"])," ",_vm._h('option',{attrs:{"value":"looks_one"}},["looks_one"])," ",_vm._h('option',{attrs:{"value":"looks_two"}},["looks_two"])," ",_vm._h('option',{attrs:{"value":"loop"}},["loop"])," ",_vm._h('option',{attrs:{"value":"loupe"}},["loupe"])," ",_vm._h('option',{attrs:{"value":"low_priority"}},["low_priority"])," ",_vm._h('option',{attrs:{"value":"loyalty"}},["loyalty"])," ",_vm._h('option',{attrs:{"value":"mail"}},["mail"])," ",_vm._h('option',{attrs:{"value":"mail_outline"}},["mail_outline"])," ",_vm._h('option',{attrs:{"value":"map"}},["map"])," ",_vm._h('option',{attrs:{"value":"markunread"}},["markunread"])," ",_vm._h('option',{attrs:{"value":"markunread_mailbox"}},["markunread_mailbox"])," ",_vm._h('option',{attrs:{"value":"memory"}},["memory"])," ",_vm._h('option',{attrs:{"value":"menu"}},["menu"])," ",_vm._h('option',{attrs:{"value":"merge_type"}},["merge_type"])," ",_vm._h('option',{attrs:{"value":"message"}},["message"])," ",_vm._h('option',{attrs:{"value":"mic"}},["mic"])," ",_vm._h('option',{attrs:{"value":"mic_none"}},["mic_none"])," ",_vm._h('option',{attrs:{"value":"mic_off"}},["mic_off"])," ",_vm._h('option',{attrs:{"value":"mms"}},["mms"])," ",_vm._h('option',{attrs:{"value":"mode_comment"}},["mode_comment"])," ",_vm._h('option',{attrs:{"value":"mode_edit"}},["mode_edit"])," ",_vm._h('option',{attrs:{"value":"monetization_on"}},["monetization_on"])," ",_vm._h('option',{attrs:{"value":"money_off"}},["money_off"])," ",_vm._h('option',{attrs:{"value":"monochrome_photos"}},["monochrome_photos"])," ",_vm._h('option',{attrs:{"value":"mood"}},["mood"])," ",_vm._h('option',{attrs:{"value":"mood_bad"}},["mood_bad"])," ",_vm._h('option',{attrs:{"value":"more"}},["more"])," ",_vm._h('option',{attrs:{"value":"more_horiz"}},["more_horiz"])," ",_vm._h('option',{attrs:{"value":"more_vert"}},["more_vert"])," ",_vm._h('option',{attrs:{"value":"motorcycle"}},["motorcycle"])," ",_vm._h('option',{attrs:{"value":"mouse"}},["mouse"])," ",_vm._h('option',{attrs:{"value":"move_to_inbox"}},["move_to_inbox"])," ",_vm._h('option',{attrs:{"value":"movie"}},["movie"])," ",_vm._h('option',{attrs:{"value":"movie_creation"}},["movie_creation"])," ",_vm._h('option',{attrs:{"value":"movie_filter"}},["movie_filter"])," ",_vm._h('option',{attrs:{"value":"multiline_chart"}},["multiline_chart"])," ",_vm._h('option',{attrs:{"value":"music_note"}},["music_note"])," ",_vm._h('option',{attrs:{"value":"music_video"}},["music_video"])," ",_vm._h('option',{attrs:{"value":"my_location"}},["my_location"])," ",_vm._h('option',{attrs:{"value":"nature"}},["nature"])," ",_vm._h('option',{attrs:{"value":"nature_people"}},["nature_people"])," ",_vm._h('option',{attrs:{"value":"navigate_before"}},["navigate_before"])," ",_vm._h('option',{attrs:{"value":"navigate_next"}},["navigate_next"])," ",_vm._h('option',{attrs:{"value":"navigation"}},["navigation"])," ",_vm._h('option',{attrs:{"value":"near_me"}},["near_me"])," ",_vm._h('option',{attrs:{"value":"network_cell"}},["network_cell"])," ",_vm._h('option',{attrs:{"value":"network_check"}},["network_check"])," ",_vm._h('option',{attrs:{"value":"network_locked"}},["network_locked"])," ",_vm._h('option',{attrs:{"value":"network_wifi"}},["network_wifi"])," ",_vm._h('option',{attrs:{"value":"new_releases"}},["new_releases"])," ",_vm._h('option',{attrs:{"value":"next_week"}},["next_week"])," ",_vm._h('option',{attrs:{"value":"nfc"}},["nfc"])," ",_vm._h('option',{attrs:{"value":"no_encryption"}},["no_encryption"])," ",_vm._h('option',{attrs:{"value":"no_sim"}},["no_sim"])," ",_vm._h('option',{attrs:{"value":"not_interested"}},["not_interested"])," ",_vm._h('option',{attrs:{"value":"note"}},["note"])," ",_vm._h('option',{attrs:{"value":"note_add"}},["note_add"])," ",_vm._h('option',{attrs:{"value":"notifications"}},["notifications"])," ",_vm._h('option',{attrs:{"value":"notifications_active"}},["notifications_active"])," ",_vm._h('option',{attrs:{"value":"notifications_none"}},["notifications_none"])," ",_vm._h('option',{attrs:{"value":"notifications_off"}},["notifications_off"])," ",_vm._h('option',{attrs:{"value":"notifications_paused"}},["notifications_paused"])," ",_vm._h('option',{attrs:{"value":"offline_pin"}},["offline_pin"])," ",_vm._h('option',{attrs:{"value":"ondemand_video"}},["ondemand_video"])," ",_vm._h('option',{attrs:{"value":"opacity"}},["opacity"])," ",_vm._h('option',{attrs:{"value":"open_in_browser"}},["open_in_browser"])," ",_vm._h('option',{attrs:{"value":"open_in_new"}},["open_in_new"])," ",_vm._h('option',{attrs:{"value":"open_with"}},["open_with"])," ",_vm._h('option',{attrs:{"value":"pages"}},["pages"])," ",_vm._h('option',{attrs:{"value":"pageview"}},["pageview"])," ",_vm._h('option',{attrs:{"value":"palette"}},["palette"])," ",_vm._h('option',{attrs:{"value":"pan_tool"}},["pan_tool"])," ",_vm._h('option',{attrs:{"value":"panorama"}},["panorama"])," ",_vm._h('option',{attrs:{"value":"panorama_fish_eye"}},["panorama_fish_eye"])," ",_vm._h('option',{attrs:{"value":"panorama_horizontal"}},["panorama_horizontal"])," ",_vm._h('option',{attrs:{"value":"panorama_vertical"}},["panorama_vertical"])," ",_vm._h('option',{attrs:{"value":"panorama_wide_angle"}},["panorama_wide_angle"])," ",_vm._h('option',{attrs:{"value":"party_mode"}},["party_mode"])," ",_vm._h('option',{attrs:{"value":"pause"}},["pause"])," ",_vm._h('option',{attrs:{"value":"pause_circle_filled"}},["pause_circle_filled"])," ",_vm._h('option',{attrs:{"value":"pause_circle_outline"}},["pause_circle_outline"])," ",_vm._h('option',{attrs:{"value":"payment"}},["payment"])," ",_vm._h('option',{attrs:{"value":"people"}},["people"])," ",_vm._h('option',{attrs:{"value":"people_outline"}},["people_outline"])," ",_vm._h('option',{attrs:{"value":"perm_camera_mic"}},["perm_camera_mic"])," ",_vm._h('option',{attrs:{"value":"perm_contact_calendar"}},["perm_contact_calendar"])," ",_vm._h('option',{attrs:{"value":"perm_data_setting"}},["perm_data_setting"])," ",_vm._h('option',{attrs:{"value":"perm_device_information"}},["perm_device_information"])," ",_vm._h('option',{attrs:{"value":"perm_identity"}},["perm_identity"])," ",_vm._h('option',{attrs:{"value":"perm_media"}},["perm_media"])," ",_vm._h('option',{attrs:{"value":"perm_phone_msg"}},["perm_phone_msg"])," ",_vm._h('option',{attrs:{"value":"perm_scan_wifi"}},["perm_scan_wifi"])," ",_vm._h('option',{attrs:{"value":"person"}},["person"])," ",_vm._h('option',{attrs:{"value":"person_add"}},["person_add"])," ",_vm._h('option',{attrs:{"value":"person_outline"}},["person_outline"])," ",_vm._h('option',{attrs:{"value":"person_pin"}},["person_pin"])," ",_vm._h('option',{attrs:{"value":"person_pin_circle"}},["person_pin_circle"])," ",_vm._h('option',{attrs:{"value":"personal_video"}},["personal_video"])," ",_vm._h('option',{attrs:{"value":"pets"}},["pets"])," ",_vm._h('option',{attrs:{"value":"phone"}},["phone"])," ",_vm._h('option',{attrs:{"value":"phone_android"}},["phone_android"])," ",_vm._h('option',{attrs:{"value":"phone_bluetooth_speaker"}},["phone_bluetooth_speaker"])," ",_vm._h('option',{attrs:{"value":"phone_forwarded"}},["phone_forwarded"])," ",_vm._h('option',{attrs:{"value":"phone_in_talk"}},["phone_in_talk"])," ",_vm._h('option',{attrs:{"value":"phone_iphone"}},["phone_iphone"])," ",_vm._h('option',{attrs:{"value":"phone_locked"}},["phone_locked"])," ",_vm._h('option',{attrs:{"value":"phone_missed"}},["phone_missed"])," ",_vm._h('option',{attrs:{"value":"phone_paused"}},["phone_paused"])," ",_vm._h('option',{attrs:{"value":"phonelink"}},["phonelink"])," ",_vm._h('option',{attrs:{"value":"phonelink_erase"}},["phonelink_erase"])," ",_vm._h('option',{attrs:{"value":"phonelink_lock"}},["phonelink_lock"])," ",_vm._h('option',{attrs:{"value":"phonelink_off"}},["phonelink_off"])," ",_vm._h('option',{attrs:{"value":"phonelink_ring"}},["phonelink_ring"])," ",_vm._h('option',{attrs:{"value":"phonelink_setup"}},["phonelink_setup"])," ",_vm._h('option',{attrs:{"value":"photo"}},["photo"])," ",_vm._h('option',{attrs:{"value":"photo_album"}},["photo_album"])," ",_vm._h('option',{attrs:{"value":"photo_camera"}},["photo_camera"])," ",_vm._h('option',{attrs:{"value":"photo_filter"}},["photo_filter"])," ",_vm._h('option',{attrs:{"value":"photo_library"}},["photo_library"])," ",_vm._h('option',{attrs:{"value":"photo_size_select_actual"}},["photo_size_select_actual"])," ",_vm._h('option',{attrs:{"value":"photo_size_select_large"}},["photo_size_select_large"])," ",_vm._h('option',{attrs:{"value":"photo_size_select_small"}},["photo_size_select_small"])," ",_vm._h('option',{attrs:{"value":"picture_as_pdf"}},["picture_as_pdf"])," ",_vm._h('option',{attrs:{"value":"picture_in_picture"}},["picture_in_picture"])," ",_vm._h('option',{attrs:{"value":"picture_in_picture_alt"}},["picture_in_picture_alt"])," ",_vm._h('option',{attrs:{"value":"pie_chart"}},["pie_chart"])," ",_vm._h('option',{attrs:{"value":"pie_chart_outlined"}},["pie_chart_outlined"])," ",_vm._h('option',{attrs:{"value":"pin_drop"}},["pin_drop"])," ",_vm._h('option',{attrs:{"value":"place"}},["place"])," ",_vm._h('option',{attrs:{"value":"play_arrow"}},["play_arrow"])," ",_vm._h('option',{attrs:{"value":"play_circle_filled"}},["play_circle_filled"])," ",_vm._h('option',{attrs:{"value":"play_circle_outline"}},["play_circle_outline"])," ",_vm._h('option',{attrs:{"value":"play_for_work"}},["play_for_work"])," ",_vm._h('option',{attrs:{"value":"playlist_add"}},["playlist_add"])," ",_vm._h('option',{attrs:{"value":"playlist_add_check"}},["playlist_add_check"])," ",_vm._h('option',{attrs:{"value":"playlist_play"}},["playlist_play"])," ",_vm._h('option',{attrs:{"value":"plus_one"}},["plus_one"])," ",_vm._h('option',{attrs:{"value":"poll"}},["poll"])," ",_vm._h('option',{attrs:{"value":"polymer"}},["polymer"])," ",_vm._h('option',{attrs:{"value":"pool"}},["pool"])," ",_vm._h('option',{attrs:{"value":"portable_wifi_off"}},["portable_wifi_off"])," ",_vm._h('option',{attrs:{"value":"portrait"}},["portrait"])," ",_vm._h('option',{attrs:{"value":"power"}},["power"])," ",_vm._h('option',{attrs:{"value":"power_input"}},["power_input"])," ",_vm._h('option',{attrs:{"value":"power_settings_new"}},["power_settings_new"])," ",_vm._h('option',{attrs:{"value":"pregnant_woman"}},["pregnant_woman"])," ",_vm._h('option',{attrs:{"value":"present_to_all"}},["present_to_all"])," ",_vm._h('option',{attrs:{"value":"print"}},["print"])," ",_vm._h('option',{attrs:{"value":"priority_high"}},["priority_high"])," ",_vm._h('option',{attrs:{"value":"public"}},["public"])," ",_vm._h('option',{attrs:{"value":"publish"}},["publish"])," ",_vm._h('option',{attrs:{"value":"query_builder"}},["query_builder"])," ",_vm._h('option',{attrs:{"value":"question_answer"}},["question_answer"])," ",_vm._h('option',{attrs:{"value":"queue"}},["queue"])," ",_vm._h('option',{attrs:{"value":"queue_music"}},["queue_music"])," ",_vm._h('option',{attrs:{"value":"queue_play_next"}},["queue_play_next"])," ",_vm._h('option',{attrs:{"value":"radio"}},["radio"])," ",_vm._h('option',{attrs:{"value":"radio_button_checked"}},["radio_button_checked"])," ",_vm._h('option',{attrs:{"value":"radio_button_unchecked"}},["radio_button_unchecked"])," ",_vm._h('option',{attrs:{"value":"rate_review"}},["rate_review"])," ",_vm._h('option',{attrs:{"value":"receipt"}},["receipt"])," ",_vm._h('option',{attrs:{"value":"recent_actors"}},["recent_actors"])," ",_vm._h('option',{attrs:{"value":"record_voice_over"}},["record_voice_over"])," ",_vm._h('option',{attrs:{"value":"redeem"}},["redeem"])," ",_vm._h('option',{attrs:{"value":"redo"}},["redo"])," ",_vm._h('option',{attrs:{"value":"refresh"}},["refresh"])," ",_vm._h('option',{attrs:{"value":"remove"}},["remove"])," ",_vm._h('option',{attrs:{"value":"remove_circle"}},["remove_circle"])," ",_vm._h('option',{attrs:{"value":"remove_circle_outline"}},["remove_circle_outline"])," ",_vm._h('option',{attrs:{"value":"remove_from_queue"}},["remove_from_queue"])," ",_vm._h('option',{attrs:{"value":"remove_red_eye"}},["remove_red_eye"])," ",_vm._h('option',{attrs:{"value":"remove_shopping_cart"}},["remove_shopping_cart"])," ",_vm._h('option',{attrs:{"value":"reorder"}},["reorder"])," ",_vm._h('option',{attrs:{"value":"repeat"}},["repeat"])," ",_vm._h('option',{attrs:{"value":"repeat_one"}},["repeat_one"])," ",_vm._h('option',{attrs:{"value":"replay"}},["replay"])," ",_vm._h('option',{attrs:{"value":"replay_10"}},["replay_10"])," ",_vm._h('option',{attrs:{"value":"replay_30"}},["replay_30"])," ",_vm._h('option',{attrs:{"value":"replay_5"}},["replay_5"])," ",_vm._h('option',{attrs:{"value":"reply"}},["reply"])," ",_vm._h('option',{attrs:{"value":"reply_all"}},["reply_all"])," ",_vm._h('option',{attrs:{"value":"report"}},["report"])," ",_vm._h('option',{attrs:{"value":"report_problem"}},["report_problem"])," ",_vm._h('option',{attrs:{"value":"restaurant"}},["restaurant"])," ",_vm._h('option',{attrs:{"value":"restaurant_menu"}},["restaurant_menu"])," ",_vm._h('option',{attrs:{"value":"restore"}},["restore"])," ",_vm._h('option',{attrs:{"value":"restore_page"}},["restore_page"])," ",_vm._h('option',{attrs:{"value":"ring_volume"}},["ring_volume"])," ",_vm._h('option',{attrs:{"value":"room"}},["room"])," ",_vm._h('option',{attrs:{"value":"room_service"}},["room_service"])," ",_vm._h('option',{attrs:{"value":"rotate_90_degrees_ccw"}},["rotate_90_degrees_ccw"])," ",_vm._h('option',{attrs:{"value":"rotate_left"}},["rotate_left"])," ",_vm._h('option',{attrs:{"value":"rotate_right"}},["rotate_right"])," ",_vm._h('option',{attrs:{"value":"rounded_corner"}},["rounded_corner"])," ",_vm._h('option',{attrs:{"value":"router"}},["router"])," ",_vm._h('option',{attrs:{"value":"rowing"}},["rowing"])," ",_vm._h('option',{attrs:{"value":"rss_feed"}},["rss_feed"])," ",_vm._h('option',{attrs:{"value":"rv_hookup"}},["rv_hookup"])," ",_vm._h('option',{attrs:{"value":"satellite"}},["satellite"])," ",_vm._h('option',{attrs:{"value":"save"}},["save"])," ",_vm._h('option',{attrs:{"value":"scanner"}},["scanner"])," ",_vm._h('option',{attrs:{"value":"schedule"}},["schedule"])," ",_vm._h('option',{attrs:{"value":"school"}},["school"])," ",_vm._h('option',{attrs:{"value":"screen_lock_landscape"}},["screen_lock_landscape"])," ",_vm._h('option',{attrs:{"value":"screen_lock_portrait"}},["screen_lock_portrait"])," ",_vm._h('option',{attrs:{"value":"screen_lock_rotation"}},["screen_lock_rotation"])," ",_vm._h('option',{attrs:{"value":"screen_rotation"}},["screen_rotation"])," ",_vm._h('option',{attrs:{"value":"screen_share"}},["screen_share"])," ",_vm._h('option',{attrs:{"value":"sd_card"}},["sd_card"])," ",_vm._h('option',{attrs:{"value":"sd_storage"}},["sd_storage"])," ",_vm._h('option',{attrs:{"value":"search"}},["search"])," ",_vm._h('option',{attrs:{"value":"security"}},["security"])," ",_vm._h('option',{attrs:{"value":"select_all"}},["select_all"])," ",_vm._h('option',{attrs:{"value":"send"}},["send"])," ",_vm._h('option',{attrs:{"value":"sentiment_dissatisfied"}},["sentiment_dissatisfied"])," ",_vm._h('option',{attrs:{"value":"sentiment_neutral"}},["sentiment_neutral"])," ",_vm._h('option',{attrs:{"value":"sentiment_satisfied"}},["sentiment_satisfied"])," ",_vm._h('option',{attrs:{"value":"sentiment_very_dissatisfied"}},["sentiment_very_dissatisfied"])," ",_vm._h('option',{attrs:{"value":"sentiment_very_satisfied"}},["sentiment_very_satisfied"])," ",_vm._h('option',{attrs:{"value":"settings"}},["settings"])," ",_vm._h('option',{attrs:{"value":"settings_applications"}},["settings_applications"])," ",_vm._h('option',{attrs:{"value":"settings_backup_restore"}},["settings_backup_restore"])," ",_vm._h('option',{attrs:{"value":"settings_bluetooth"}},["settings_bluetooth"])," ",_vm._h('option',{attrs:{"value":"settings_brightness"}},["settings_brightness"])," ",_vm._h('option',{attrs:{"value":"settings_cell"}},["settings_cell"])," ",_vm._h('option',{attrs:{"value":"settings_ethernet"}},["settings_ethernet"])," ",_vm._h('option',{attrs:{"value":"settings_input_antenna"}},["settings_input_antenna"])," ",_vm._h('option',{attrs:{"value":"settings_input_component"}},["settings_input_component"])," ",_vm._h('option',{attrs:{"value":"settings_input_composite"}},["settings_input_composite"])," ",_vm._h('option',{attrs:{"value":"settings_input_hdmi"}},["settings_input_hdmi"])," ",_vm._h('option',{attrs:{"value":"settings_input_svideo"}},["settings_input_svideo"])," ",_vm._h('option',{attrs:{"value":"settings_overscan"}},["settings_overscan"])," ",_vm._h('option',{attrs:{"value":"settings_phone"}},["settings_phone"])," ",_vm._h('option',{attrs:{"value":"settings_power"}},["settings_power"])," ",_vm._h('option',{attrs:{"value":"settings_remote"}},["settings_remote"])," ",_vm._h('option',{attrs:{"value":"settings_system_daydream"}},["settings_system_daydream"])," ",_vm._h('option',{attrs:{"value":"settings_voice"}},["settings_voice"])," ",_vm._h('option',{attrs:{"value":"share"}},["share"])," ",_vm._h('option',{attrs:{"value":"shop"}},["shop"])," ",_vm._h('option',{attrs:{"value":"shop_two"}},["shop_two"])," ",_vm._h('option',{attrs:{"value":"shopping_basket"}},["shopping_basket"])," ",_vm._h('option',{attrs:{"value":"shopping_cart"}},["shopping_cart"])," ",_vm._h('option',{attrs:{"value":"short_text"}},["short_text"])," ",_vm._h('option',{attrs:{"value":"show_chart"}},["show_chart"])," ",_vm._h('option',{attrs:{"value":"shuffle"}},["shuffle"])," ",_vm._h('option',{attrs:{"value":"signal_cellular_4_bar"}},["signal_cellular_4_bar"])," ",_vm._h('option',{attrs:{"value":"signal_cellular_connected_no_internet_4_bar"}},["signal_cellular_connected_no_internet_4_bar"])," ",_vm._h('option',{attrs:{"value":"signal_cellular_no_sim"}},["signal_cellular_no_sim"])," ",_vm._h('option',{attrs:{"value":"signal_cellular_null"}},["signal_cellular_null"])," ",_vm._h('option',{attrs:{"value":"signal_cellular_off"}},["signal_cellular_off"])," ",_vm._h('option',{attrs:{"value":"signal_wifi_4_bar"}},["signal_wifi_4_bar"])," ",_vm._h('option',{attrs:{"value":"signal_wifi_4_bar_lock"}},["signal_wifi_4_bar_lock"])," ",_vm._h('option',{attrs:{"value":"signal_wifi_off"}},["signal_wifi_off"])," ",_vm._h('option',{attrs:{"value":"sim_card"}},["sim_card"])," ",_vm._h('option',{attrs:{"value":"sim_card_alert"}},["sim_card_alert"])," ",_vm._h('option',{attrs:{"value":"skip_next"}},["skip_next"])," ",_vm._h('option',{attrs:{"value":"skip_previous"}},["skip_previous"])," ",_vm._h('option',{attrs:{"value":"slideshow"}},["slideshow"])," ",_vm._h('option',{attrs:{"value":"slow_motion_video"}},["slow_motion_video"])," ",_vm._h('option',{attrs:{"value":"smartphone"}},["smartphone"])," ",_vm._h('option',{attrs:{"value":"smoke_free"}},["smoke_free"])," ",_vm._h('option',{attrs:{"value":"smoking_rooms"}},["smoking_rooms"])," ",_vm._h('option',{attrs:{"value":"sms"}},["sms"])," ",_vm._h('option',{attrs:{"value":"sms_failed"}},["sms_failed"])," ",_vm._h('option',{attrs:{"value":"snooze"}},["snooze"])," ",_vm._h('option',{attrs:{"value":"sort"}},["sort"])," ",_vm._h('option',{attrs:{"value":"sort_by_alpha"}},["sort_by_alpha"])," ",_vm._h('option',{attrs:{"value":"spa"}},["spa"])," ",_vm._h('option',{attrs:{"value":"space_bar"}},["space_bar"])," ",_vm._h('option',{attrs:{"value":"speaker"}},["speaker"])," ",_vm._h('option',{attrs:{"value":"speaker_group"}},["speaker_group"])," ",_vm._h('option',{attrs:{"value":"speaker_notes"}},["speaker_notes"])," ",_vm._h('option',{attrs:{"value":"speaker_notes_off"}},["speaker_notes_off"])," ",_vm._h('option',{attrs:{"value":"speaker_phone"}},["speaker_phone"])," ",_vm._h('option',{attrs:{"value":"spellcheck"}},["spellcheck"])," ",_vm._h('option',{attrs:{"value":"star"}},["star"])," ",_vm._h('option',{attrs:{"value":"star_border"}},["star_border"])," ",_vm._h('option',{attrs:{"value":"star_half"}},["star_half"])," ",_vm._h('option',{attrs:{"value":"stars"}},["stars"])," ",_vm._h('option',{attrs:{"value":"stay_current_landscape"}},["stay_current_landscape"])," ",_vm._h('option',{attrs:{"value":"stay_current_portrait"}},["stay_current_portrait"])," ",_vm._h('option',{attrs:{"value":"stay_primary_landscape"}},["stay_primary_landscape"])," ",_vm._h('option',{attrs:{"value":"stay_primary_portrait"}},["stay_primary_portrait"])," ",_vm._h('option',{attrs:{"value":"stop"}},["stop"])," ",_vm._h('option',{attrs:{"value":"stop_screen_share"}},["stop_screen_share"])," ",_vm._h('option',{attrs:{"value":"storage"}},["storage"])," ",_vm._h('option',{attrs:{"value":"store"}},["store"])," ",_vm._h('option',{attrs:{"value":"store_mall_directory"}},["store_mall_directory"])," ",_vm._h('option',{attrs:{"value":"straighten"}},["straighten"])," ",_vm._h('option',{attrs:{"value":"streetview"}},["streetview"])," ",_vm._h('option',{attrs:{"value":"strikethrough_s"}},["strikethrough_s"])," ",_vm._h('option',{attrs:{"value":"style"}},["style"])," ",_vm._h('option',{attrs:{"value":"subdirectory_arrow_left"}},["subdirectory_arrow_left"])," ",_vm._h('option',{attrs:{"value":"subdirectory_arrow_right"}},["subdirectory_arrow_right"])," ",_vm._h('option',{attrs:{"value":"subject"}},["subject"])," ",_vm._h('option',{attrs:{"value":"subscriptions"}},["subscriptions"])," ",_vm._h('option',{attrs:{"value":"subtitles"}},["subtitles"])," ",_vm._h('option',{attrs:{"value":"subway"}},["subway"])," ",_vm._h('option',{attrs:{"value":"supervisor_account"}},["supervisor_account"])," ",_vm._h('option',{attrs:{"value":"surround_sound"}},["surround_sound"])," ",_vm._h('option',{attrs:{"value":"swap_calls"}},["swap_calls"])," ",_vm._h('option',{attrs:{"value":"swap_horiz"}},["swap_horiz"])," ",_vm._h('option',{attrs:{"value":"swap_vert"}},["swap_vert"])," ",_vm._h('option',{attrs:{"value":"swap_vertical_circle"}},["swap_vertical_circle"])," ",_vm._h('option',{attrs:{"value":"switch_camera"}},["switch_camera"])," ",_vm._h('option',{attrs:{"value":"switch_video"}},["switch_video"])," ",_vm._h('option',{attrs:{"value":"sync"}},["sync"])," ",_vm._h('option',{attrs:{"value":"sync_disabled"}},["sync_disabled"])," ",_vm._h('option',{attrs:{"value":"sync_problem"}},["sync_problem"])," ",_vm._h('option',{attrs:{"value":"system_update"}},["system_update"])," ",_vm._h('option',{attrs:{"value":"system_update_alt"}},["system_update_alt"])," ",_vm._h('option',{attrs:{"value":"tab"}},["tab"])," ",_vm._h('option',{attrs:{"value":"tab_unselected"}},["tab_unselected"])," ",_vm._h('option',{attrs:{"value":"tablet"}},["tablet"])," ",_vm._h('option',{attrs:{"value":"tablet_android"}},["tablet_android"])," ",_vm._h('option',{attrs:{"value":"tablet_mac"}},["tablet_mac"])," ",_vm._h('option',{attrs:{"value":"tag_faces"}},["tag_faces"])," ",_vm._h('option',{attrs:{"value":"tap_and_play"}},["tap_and_play"])," ",_vm._h('option',{attrs:{"value":"terrain"}},["terrain"])," ",_vm._h('option',{attrs:{"value":"text_fields"}},["text_fields"])," ",_vm._h('option',{attrs:{"value":"text_format"}},["text_format"])," ",_vm._h('option',{attrs:{"value":"textsms"}},["textsms"])," ",_vm._h('option',{attrs:{"value":"texture"}},["texture"])," ",_vm._h('option',{attrs:{"value":"theaters"}},["theaters"])," ",_vm._h('option',{attrs:{"value":"thumb_down"}},["thumb_down"])," ",_vm._h('option',{attrs:{"value":"thumb_up"}},["thumb_up"])," ",_vm._h('option',{attrs:{"value":"thumbs_up_down"}},["thumbs_up_down"])," ",_vm._h('option',{attrs:{"value":"time_to_leave"}},["time_to_leave"])," ",_vm._h('option',{attrs:{"value":"timelapse"}},["timelapse"])," ",_vm._h('option',{attrs:{"value":"timeline"}},["timeline"])," ",_vm._h('option',{attrs:{"value":"timer"}},["timer"])," ",_vm._h('option',{attrs:{"value":"timer_10"}},["timer_10"])," ",_vm._h('option',{attrs:{"value":"timer_3"}},["timer_3"])," ",_vm._h('option',{attrs:{"value":"timer_off"}},["timer_off"])," ",_vm._h('option',{attrs:{"value":"title"}},["title"])," ",_vm._h('option',{attrs:{"value":"toc"}},["toc"])," ",_vm._h('option',{attrs:{"value":"today"}},["today"])," ",_vm._h('option',{attrs:{"value":"toll"}},["toll"])," ",_vm._h('option',{attrs:{"value":"tonality"}},["tonality"])," ",_vm._h('option',{attrs:{"value":"touch_app"}},["touch_app"])," ",_vm._h('option',{attrs:{"value":"toys"}},["toys"])," ",_vm._h('option',{attrs:{"value":"track_changes"}},["track_changes"])," ",_vm._h('option',{attrs:{"value":"traffic"}},["traffic"])," ",_vm._h('option',{attrs:{"value":"train"}},["train"])," ",_vm._h('option',{attrs:{"value":"tram"}},["tram"])," ",_vm._h('option',{attrs:{"value":"transfer_within_a_station"}},["transfer_within_a_station"])," ",_vm._h('option',{attrs:{"value":"transform"}},["transform"])," ",_vm._h('option',{attrs:{"value":"translate"}},["translate"])," ",_vm._h('option',{attrs:{"value":"trending_down"}},["trending_down"])," ",_vm._h('option',{attrs:{"value":"trending_flat"}},["trending_flat"])," ",_vm._h('option',{attrs:{"value":"trending_up"}},["trending_up"])," ",_vm._h('option',{attrs:{"value":"tune"}},["tune"])," ",_vm._h('option',{attrs:{"value":"turned_in"}},["turned_in"])," ",_vm._h('option',{attrs:{"value":"turned_in_not"}},["turned_in_not"])," ",_vm._h('option',{attrs:{"value":"tv"}},["tv"])," ",_vm._h('option',{attrs:{"value":"unarchive"}},["unarchive"])," ",_vm._h('option',{attrs:{"value":"undo"}},["undo"])," ",_vm._h('option',{attrs:{"value":"unfold_less"}},["unfold_less"])," ",_vm._h('option',{attrs:{"value":"unfold_more"}},["unfold_more"])," ",_vm._h('option',{attrs:{"value":"update"}},["update"])," ",_vm._h('option',{attrs:{"value":"usb"}},["usb"])," ",_vm._h('option',{attrs:{"value":"verified_user"}},["verified_user"])," ",_vm._h('option',{attrs:{"value":"vertical_align_bottom"}},["vertical_align_bottom"])," ",_vm._h('option',{attrs:{"value":"vertical_align_center"}},["vertical_align_center"])," ",_vm._h('option',{attrs:{"value":"vertical_align_top"}},["vertical_align_top"])," ",_vm._h('option',{attrs:{"value":"vibration"}},["vibration"])," ",_vm._h('option',{attrs:{"value":"video_call"}},["video_call"])," ",_vm._h('option',{attrs:{"value":"video_label"}},["video_label"])," ",_vm._h('option',{attrs:{"value":"video_library"}},["video_library"])," ",_vm._h('option',{attrs:{"value":"videocam"}},["videocam"])," ",_vm._h('option',{attrs:{"value":"videocam_off"}},["videocam_off"])," ",_vm._h('option',{attrs:{"value":"videogame_asset"}},["videogame_asset"])," ",_vm._h('option',{attrs:{"value":"view_agenda"}},["view_agenda"])," ",_vm._h('option',{attrs:{"value":"view_array"}},["view_array"])," ",_vm._h('option',{attrs:{"value":"view_carousel"}},["view_carousel"])," ",_vm._h('option',{attrs:{"value":"view_column"}},["view_column"])," ",_vm._h('option',{attrs:{"value":"view_comfy"}},["view_comfy"])," ",_vm._h('option',{attrs:{"value":"view_compact"}},["view_compact"])," ",_vm._h('option',{attrs:{"value":"view_day"}},["view_day"])," ",_vm._h('option',{attrs:{"value":"view_headline"}},["view_headline"])," ",_vm._h('option',{attrs:{"value":"view_list"}},["view_list"])," ",_vm._h('option',{attrs:{"value":"view_module"}},["view_module"])," ",_vm._h('option',{attrs:{"value":"view_quilt"}},["view_quilt"])," ",_vm._h('option',{attrs:{"value":"view_stream"}},["view_stream"])," ",_vm._h('option',{attrs:{"value":"view_week"}},["view_week"])," ",_vm._h('option',{attrs:{"value":"vignette"}},["vignette"])," ",_vm._h('option',{attrs:{"value":"visibility"}},["visibility"])," ",_vm._h('option',{attrs:{"value":"visibility_off"}},["visibility_off"])," ",_vm._h('option',{attrs:{"value":"voice_chat"}},["voice_chat"])," ",_vm._h('option',{attrs:{"value":"voicemail"}},["voicemail"])," ",_vm._h('option',{attrs:{"value":"volume_down"}},["volume_down"])," ",_vm._h('option',{attrs:{"value":"volume_mute"}},["volume_mute"])," ",_vm._h('option',{attrs:{"value":"volume_off"}},["volume_off"])," ",_vm._h('option',{attrs:{"value":"volume_up"}},["volume_up"])," ",_vm._h('option',{attrs:{"value":"vpn_key"}},["vpn_key"])," ",_vm._h('option',{attrs:{"value":"vpn_lock"}},["vpn_lock"])," ",_vm._h('option',{attrs:{"value":"wallpaper"}},["wallpaper"])," ",_vm._h('option',{attrs:{"value":"warning"}},["warning"])," ",_vm._h('option',{attrs:{"value":"watch"}},["watch"])," ",_vm._h('option',{attrs:{"value":"watch_later"}},["watch_later"])," ",_vm._h('option',{attrs:{"value":"wb_auto"}},["wb_auto"])," ",_vm._h('option',{attrs:{"value":"wb_cloudy"}},["wb_cloudy"])," ",_vm._h('option',{attrs:{"value":"wb_incandescent"}},["wb_incandescent"])," ",_vm._h('option',{attrs:{"value":"wb_iridescent"}},["wb_iridescent"])," ",_vm._h('option',{attrs:{"value":"wb_sunny"}},["wb_sunny"])," ",_vm._h('option',{attrs:{"value":"wc"}},["wc"])," ",_vm._h('option',{attrs:{"value":"web"}},["web"])," ",_vm._h('option',{attrs:{"value":"web_asset"}},["web_asset"])," ",_vm._h('option',{attrs:{"value":"weekend"}},["weekend"])," ",_vm._h('option',{attrs:{"value":"whatshot"}},["whatshot"])," ",_vm._h('option',{attrs:{"value":"widgets"}},["widgets"])," ",_vm._h('option',{attrs:{"value":"wifi"}},["wifi"])," ",_vm._h('option',{attrs:{"value":"wifi_lock"}},["wifi_lock"])," ",_vm._h('option',{attrs:{"value":"wifi_tethering"}},["wifi_tethering"])," ",_vm._h('option',{attrs:{"value":"work"}},["work"])," ",_vm._h('option',{attrs:{"value":"wrap_text"}},["wrap_text"])," ",_vm._h('option',{attrs:{"value":"youtube_searched_for"}},["youtube_searched_for"])," ",_vm._h('option',{attrs:{"value":"zoom_in"}},["zoom_in"])," ",_vm._h('option',{attrs:{"value":"zoom_out"}},["zoom_out"])," ",_vm._h('option',{attrs:{"value":"zoom_out_map"}},["zoom_out_map"])])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["add"])},function render () {var _vm=this;return _vm._h('div',{staticClass:"col s12",attrs:{"id":"generic_component_type_create_props"}},[_vm._h('br')])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["add"])},function render () {var _vm=this;return _vm._h('div',{staticClass:"col s12",attrs:{"id":"generic_component_state_create_props"}},[_vm._h('br')])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["send"])},function render () {var _vm=this;return _vm._h('div',{attrs:{"id":"generic_component_type_create_prop_input_tpl","hidden":""}},[_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"input-field col s10 m10 l10"},[_vm._h('input',{attrs:{"type":"text","name":"property_name[]","value":""}})])," ",_vm._h('div',{staticClass:"input-field col s2 m2 l2"},[_vm._h('a',{staticClass:"red-text",attrs:{"href":"#","onclick":"$(this)[0].parentElement.parentElement.remove(); return false;"}},[_vm._h('i',{staticClass:"material-icons"},["delete"])])])])])},function render () {var _vm=this;return _vm._h('div',{attrs:{"id":"generic_component_state_create_prop_input_tpl","hidden":""}},[_vm._h('div',{staticClass:"row"},[_vm._h('div',{staticClass:"input-field col s10 m10 l10"},[_vm._h('input',{attrs:{"type":"text","name":"state[]","value":""}})])," ",_vm._h('div',{staticClass:"input-field col s2 m2 l2"},[_vm._h('a',{staticClass:"red-text",attrs:{"href":"#","onclick":"$(this)[0].parentElement.parentElement.remove(); return false;"}},[_vm._h('i',{staticClass:"material-icons"},["delete"])])])])])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-36", __vue__options__)
  } else {
    hotAPI.reload("data-v-36", __vue__options__)
  }
})()}
},{"vue":8,"vue-hot-reload-api":5}],31:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            generic_components: [],
            meta: [],
            filter: {
                name: '',
                'type.name_singular': this.defaultTypeFilter,
                'controlunit.name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        };
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        defaultTypeFilter: {
            type: String,
            default: '',
            required: false
        },
        refreshTimeoutSeconds: {
            type: Number,
            default: 60,
            required: false
        }
    },

    methods: {
        update: function update(ps) {
            var item = null;
            this.generic_components.forEach(function (data, index) {
                if (data.id === ps.generic_component.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.generic_components.splice(item, 1, ps.generic_component);
            }
        },

        delete: function _delete(ps) {
            var item = null;
            this.generic_components.forEach(function (data, index) {
                if (data.id === ps.generic_component.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.generic_components.splice(item, 1);
            }
        },

        set_order: function set_order(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                } else {
                    this.order.direction = 'asc';
                }
            } else {
                this.order.field = field;
            }

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function set_filter() {
            this.filter_string = '&';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function set_page(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/generic_components?page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.meta = data.meta;
                    that.generic_components = data.data;
                    that.$nextTick(function () {
                        $('table.collapsible').collapsibletable();
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('GenericComponentUpdated', function (e) {
            _this.update(e);
        }).listen('GenericComponentDeleted', function (e) {
            _this.delete(e);
        });

        this.set_filter();
        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight collapsible",attrs:{"data-collapsible":"expandable"}},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{attrs:{"data-field":"name"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('name')}}},[_vm._s(_vm.$t('labels.name'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'asc'),expression:"order.field == 'name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'desc'),expression:"order.field == 'name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.name),expression:"filter.name"}],attrs:{"id":"filter_name","type":"text"},domProps:{"value":_vm._s(_vm.filter.name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.name=$event.target.value}}})," ",_vm._m(0)])])," ",_vm._h('th',{attrs:{"data-field":"generic_component.type.name"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('type.name_singular')}}},[_vm._s(_vm.$t('labels.type'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'type.name_singular' && _vm.order.direction == 'asc'),expression:"order.field == 'type.name_singular' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'type.name_singular' && _vm.order.direction == 'desc'),expression:"order.field == 'type.name_singular' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['type.name_singular']),expression:"filter['type.name_singular']"}],attrs:{"id":"filter_generic_component_type_name_singular","type":"text"},domProps:{"value":_vm._s(_vm.filter['type.name_singular'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'type.name_singular';if (!Array.isArray($$exp)){_vm.filter['type.name_singular']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(1)])])," ",_vm._h('th',{staticClass:"hide-on-small-only",attrs:{"data-field":"controlunit"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('controlunit')}}},[_vm._s(_vm.$tc('components.controlunit', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'asc'),expression:"order.field == 'controlunit' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'desc'),expression:"order.field == 'controlunit' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['controlunit.name']),expression:"filter['controlunit.name']"}],attrs:{"id":"filter_controlunit","type":"text"},domProps:{"value":_vm._s(_vm.filter['controlunit.name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'controlunit.name';if (!Array.isArray($$exp)){_vm.filter['controlunit.name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(2)])])," ",_vm._m(3)])])," ",_vm._l((_vm.generic_components),function(generic_component){return [_vm._h('tbody',[_vm._h('tr',{staticClass:"collapsible-header"},[_vm._h('td',[_vm._h('span',[_vm._h('i',{staticClass:"material-icons"},[_vm._s(generic_component.type.icon)])," ",_vm._h('a',{attrs:{"href":'/generic_components/' + generic_component.id}},[_vm._s(generic_component.name)])])])," ",_vm._h('td',[_vm._h('span',[_vm._h('a',{attrs:{"href":'/generic_component_types/' + generic_component.type.id}},[_vm._s(generic_component.type.name_singular)])])])," ",_vm._h('td',{staticClass:"hide-on-small-only"},[(generic_component.controlunit)?_vm._h('span',[_vm._m(4,true)," ",_vm._h('a',{attrs:{"href":'/controlunits/' + generic_component.controlunit.id}},[_vm._s(generic_component.controlunit.name)])]):_vm._e()])," ",_vm._h('td',[_vm._h('span',[_vm._h('a',{staticClass:"waves-effect waves-light btn-small",attrs:{"href":'/generic_components/' + generic_component.id + '/edit'}},[_vm._m(5,true)])])])])," ",_vm._h('tr',{staticClass:"collapsible-body"},[_vm._h('td',{attrs:{"colspan":"4"}},[_vm._l((generic_component.properties),function(value,name){return _vm._h('span',["\n                                "+_vm._s(name)+": "+_vm._s(value)+"\n                                ",_vm._m(6,true)])})])])])]})])," ",(_vm.meta.hasOwnProperty('pagination'))?_vm._h('ul',{staticClass:"pagination"},[_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(1)}}},[_vm._m(7)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._m(8)])])," ",(_vm.meta.pagination.current_page-3 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-3)}}},[_vm._s(_vm.meta.pagination.current_page-3)])]):_vm._e()," ",(_vm.meta.pagination.current_page-2 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-2)}}},[_vm._s(_vm.meta.pagination.current_page-2)])]):_vm._e()," ",(_vm.meta.pagination.current_page-1 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._s(_vm.meta.pagination.current_page-1)])]):_vm._e()," ",_vm._h('li',{staticClass:"active"},[_vm._h('a',{attrs:{"href":"#!"}},[_vm._s(_vm.meta.pagination.current_page)])])," ",(_vm.meta.pagination.current_page+1 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._s(_vm.meta.pagination.current_page+1)])]):_vm._e()," ",(_vm.meta.pagination.current_page+2 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+2)}}},[_vm._s(_vm.meta.pagination.current_page+2)])]):_vm._e()," ",(_vm.meta.pagination.current_page+3 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+3)}}},[_vm._s(_vm.meta.pagination.current_page+3)])]):_vm._e()," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._m(9)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.total_pages)}}},[_vm._m(10)])])]):_vm._e()])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_name"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_generic_component_type_name_singular"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_controlunit"}},["Filter"])},function render () {var _vm=this;return _vm._h('th',{staticStyle:{"width":"40px"}})},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["developer_board"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["edit"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["first_page"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_left"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["last_page"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-30", __vue__options__)
  } else {
    hotAPI.reload("data-v-30", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],32:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            generic_components: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        genericComponentId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'generic_components-masonry-grid',
            required: false
        }
    },

    methods: {
        update: function update(gc) {
            var item = null;
            this.generic_components.forEach(function (data, index) {
                if (data.id === gc.generic_component.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.generic_components.push(gc.generic_component);
            } else if (item !== null) {
                this.generic_components.splice(item, 1, gc.generic_component);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        delete: function _delete(gc) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.generic_components.forEach(function (data, index) {
                if (data.id === gc.generic_component_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.generic_components.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/generic_components/' + that.genericComponentId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    if (that.genericComponentId !== '') {
                        that.generic_components = [data.data];
                    } else {
                        that.generic_components = data.data;
                    }

                    that.$nextTick(function () {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col'
                        });
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('GenericComponentUpdated', function (e) {
            _this.update(e);
        }).listen('GenericComponentDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:[_vm.containerClasses, 'masonry-grid'],attrs:{"id":_vm.containerId}},[_vm._l((_vm.generic_components),function(generic_component){return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._h('i',{staticClass:"material-icons"},[_vm._s(generic_component.type.icon)]),"\n                    "+_vm._s(generic_component.type.name_singular)+"\n                "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(generic_component.name)])," ",_vm._m(0,true)])," ",_vm._h('p',[_vm._l((generic_component.properties),function(value,name){return _vm._h('span',[_vm._s(name)+": "+_vm._s(value),_vm._m(1,true)])})])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/generic_components/' + generic_component.id}},[_vm._s(_vm.$t("buttons.details"))])," ",_vm._h('a',{attrs:{"href":'/generic_components/' + generic_component.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",_vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(_vm.$tc('components.controlunits', 1)),_vm._m(2,true)])," ",_vm._h('p',[_vm._h('a',{attrs:{"href":'/controlunits/' + generic_component.controlunit.id}},[_vm._s(generic_component.controlunit.name)])])," ",_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(generic_component.belongsTo_type)])," ",_vm._h('p',[(generic_component.belongsTo)?_vm._h('a',{attrs:{"href":generic_component.belongsTo.url}},[_vm._s(generic_component.belongsTo.name)]):_vm._e()])])])])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["close"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-29", __vue__options__)
  } else {
    hotAPI.reload("data-v-29", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],33:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {

    props: {
        id: {
            type: Number,
            required: false,
            default: Math.floor(Math.random() * 1000000)
        },
        type: {
            type: String,
            default: 'line',
            required: false
        },
        eventType: {
            type: String,
            default: null,
            required: false
        },
        source: {
            type: String,
            required: true
        },
        HorizontalAxisTitle: {
            type: String,
            default: '',
            required: false
        },
        VerticalAxisTitle: {
            type: String,
            default: '',
            required: false
        },
        VerticalAxisGridlinesCount: {
            type: Number,
            default: 5,
            required: false
        },
        Height: {
            type: Number,
            default: 300,
            required: false
        },
        BackgroundColor: {
            type: String,
            default: '',
            required: false
        },
        FilterColumn: {
            type: String,
            default: null,
            required: true
        },
        ShowFilterForm: {
            type: Boolean,
            default: false,
            required: false
        },
        FilterFromDate: {
            type: String,
            default: new Date(new Date().setMonth(new Date().getMonth() - 3)).toYmd(),
            required: false
        },
        FilterToDate: {
            type: String,
            default: new Date().toYmd(),
            required: false
        }
    },
    data: function data() {
        return {
            chart: null,
            options: {},
            data: []
        };
    },


    methods: {
        get_filter_from_date: function get_filter_from_date() {
            if ($('#filter_from_' + this.id).val() == undefined) {
                return this.FilterFromDate;
            }

            return $('#filter_from_' + this.id).val();
        },
        get_filter_to_date: function get_filter_to_date() {
            if ($('#filter_to_' + this.id).val() == undefined) {
                return this.FilterToDate + " 23:59:59";
            }

            return $('#filter_to_' + this.id).val() + " 23:59:59";
        },
        init: function init() {
            this.data = new google.visualization.DataTable();
            this.build();
        },
        build: function build() {
            $('#dygraph_' + this.id + '_loading').show();
            var that = this;
            var url = this.source + '&filter[' + this.FilterColumn + ']=ge:' + this.get_filter_from_date() + ':and:le:' + this.get_filter_to_date();

            $.ajax({
                url: url,
                method: 'GET',
                success: function success(data) {

                    that.data.removeRows(0, that.data.getNumberOfRows());
                    that.data.removeColumns(0, that.data.getNumberOfColumns());

                    $.each(data.data.columns, function (item, col) {
                        that.data.addColumn(col.type, col.name);
                        if (col.type == 'date') {
                            $.each(data.data.rows, function (ritem, r) {
                                data.data.rows[ritem][item] = new Date(r[item]);
                            });
                        }
                    });

                    that.data.addRows(data.data.rows);

                    that.chart = new google.visualization.LineChart(document.getElementById('google_chart_' + that.id));
                    that.draw();
                },
                error: function error(_error) {
                    $('#dygraph_' + this.id + '_loading').hide();
                    console.log((0, _stringify2.default)(_error));
                }
            });
        },
        draw: function draw() {
            this.options = {
                hAxis: {
                    title: this.HorizontalAxisTitle,
                    titleTextStyle: 'chartTextColor',
                    gridlines: {
                        count: this.VerticalAxisGridlinesCount,
                        color: '#666'
                    }
                },
                vAxis: {
                    title: this.VerticalAxisTitle,
                    titleTextStyle: 'chartTextColor',
                    gridlines: {
                        color: '#666'
                    }
                },
                annotations: {
                    textStyle: 'chartTextColor'
                },
                height: this.Height,
                width: '100%',
                backgroundColor: 'transparent',
                curveType: 'function',
                pointSize: 4
            };

            this.chart.draw(this.data, this.options);

            $('#dygraph_' + this.id + '_loading').hide();
        }
    },

    created: function created() {
        var _this = this;

        window.eventHubVue.processStarted();

        google.charts.load('current', { packages: ['corechart', this.type] });
        google.charts.setOnLoadCallback(this.init);

        window.eventHubVue.$on('ForceRerender', this.draw);
        if (this.eventType !== null) {
            window.echo.private('dashboard-updates').listen(this.eventType, function (e) {
                _this.build();
            });
        }

        this.$nextTick(function () {
            $('.datepicker').pickadate({
                format: 'yyyy-mm-dd'
            });
        });

        window.eventHubVue.processEnded();
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[(_vm.ShowFilterForm === true)?_vm._h('div',[_vm._h('div',{staticClass:"row",staticStyle:{"margin-bottom":"0"}},[_vm._h('div',{staticClass:"input-field col s12 m4 l4"},[_vm._h('input',{staticClass:"datepicker",attrs:{"type":"date","placeholder":_vm.$t('labels.from'),"name":"filter_from","id":'filter_from_' + _vm.id,"data-default":_vm.FilterFromDate},domProps:{"value":_vm.FilterFromDate}})," ",_vm._h('label',{attrs:{"for":'filter_from_' + _vm.id}},[_vm._s(_vm.$t('labels.from'))])])," ",_vm._h('div',{staticClass:"input-field col s12 m4 l4"},[_vm._h('input',{staticClass:"datepicker",attrs:{"type":"date","placeholder":_vm.$t('labels.to'),"name":"filter_to","id":'filter_to_' + _vm.id,"data-default":_vm.FilterToDate},domProps:{"value":_vm.FilterToDate}})," ",_vm._h('label',{attrs:{"for":'filter_to_' + _vm.id}},[_vm._s(_vm.$t('labels.to'))])])," ",_vm._h('div',{staticClass:"input-field col s12 m4 l4"},[_vm._h('button',{staticClass:"btn waves-effect waves-light",on:{"click":_vm.build}},[_vm._s(_vm.$t('buttons.loadgraph'))])])])]):_vm._e()," ",_vm._h('div',{staticClass:"center",staticStyle:{"display":"none"},attrs:{"id":'google_chart_' + _vm.id + '_loading'}},[_vm._m(0)])," ",_vm._h('div',{attrs:{"id":'google_chart_' + _vm.id}})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('div',{staticClass:"preloader-wrapper small active"},[_vm._h('div',{staticClass:"spinner-layer spinner-green-only"},[_vm._h('div',{staticClass:"circle-clipper left"},[_vm._h('div',{staticClass:"circle"})]),_vm._h('div',{staticClass:"gap-patch"},[_vm._h('div',{staticClass:"circle"})]),_vm._h('div',{staticClass:"circle-clipper right"},[_vm._h('div',{staticClass:"circle"})])])])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3", __vue__options__)
  } else {
    hotAPI.reload("data-v-3", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],34:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

var _vuePeity = require('vue-peity');

var _vuePeity2 = _interopRequireDefault(_vuePeity);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {

    components: {
        Peity: _vuePeity2.default
    },

    props: ['source', 'type', 'options', 'parentid', 'graphtype'],

    computed: {
        graphData: function graphData() {
            if (this.data === undefined) return '';

            return this.data.toString();
        }
    },

    data: function data() {
        return {
            data: []
        };
    },


    methods: {
        createSensorrreading: function createSensorrreading(value) {
            this.data.push(value);
        },
        updateTerrariumGraph: function updateTerrariumGraph(t) {
            if (t.terrarium.id == this.parentid) {
                if (this.graphtype == 'humidity_percent') this.data = t.terrarium.humidity_history;else if (this.graphtype == 'temperature_celsius') this.data = t.terrarium.temperature_history;
            }
        },
        rerender: function rerender() {
            var tmp = this.data;
            this.data = [0];

            this.$nextTick(function () {
                this.data = tmp;
            });
        }
    },

    created: function created() {
        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: that.source,
            method: 'GET',
            success: function success(data) {
                that.data = data.data;
                window.eventHubVue.processEnded();
            },
            error: function error(_error) {
                console.log((0, _stringify2.default)(_error));
                window.eventHubVue.processEnded();
            }
        });

        window.eventHubVue.$on('ForceRerender', this.rerender);
        window.eventHubVue.$on('SensorreadingCreated', this.createSensorrreading);
        window.eventHubVue.$on('TerrariumGraphUpdated', this.updateTerrariumGraph);
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('span',[_vm._h('peity',{attrs:{"type":_vm.type,"options":_vm.options,"data":_vm.graphData}})])}
__vue__options__.staticRenderFns = []
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5", __vue__options__)
  } else {
    hotAPI.reload("data-v-5", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5,"vue-peity":7}],35:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            logical_sensors: [],
            meta: [],
            filter: {
                name: '',
                type: '',
                'physical_sensor.terrarium.display_name': '',
                'physical_sensor.name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        };
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(ls) {
            var item = null;
            this.logical_sensors.forEach(function (data, index) {
                if (data.id === ls.logical_sensor.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.logical_sensors.splice(item, 1, ls.logical_sensor);
            }
        },

        delete: function _delete(ls) {
            var item = null;
            this.logical_sensors.forEach(function (data, index) {
                if (data.id === ls.logical_sensor.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.logical_sensors.splice(item, 1);
            }
        },
        set_order: function set_order(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                } else {
                    this.order.direction = 'asc';
                }
            } else {
                this.order.field = field;
            }

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function set_filter() {
            this.filter_string = '&';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function set_page(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/logical_sensors?page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.meta = data.meta;
                    that.logical_sensors = data.data;
                    that.$nextTick(function () {
                        $('table.collapsible').collapsibletable();
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('LogicalSensorUpdated', function (e) {
            _this.update(e);
        }).listen('LogicalSensorDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight collapsible",attrs:{"data-collapsible":"expandable"}},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{attrs:{"data-field":"name"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('name')}}},[_vm._s(_vm.$t('labels.name'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'asc'),expression:"order.field == 'name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'desc'),expression:"order.field == 'name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.name),expression:"filter.name"}],attrs:{"id":"filter_name","type":"text"},domProps:{"value":_vm._s(_vm.filter.name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.name=$event.target.value}}})," ",_vm._m(0)])])," ",_vm._h('th',{staticClass:"hide-on-small-only",attrs:{"data-field":"physical_sensor"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('physical_sensors.name')}}},[_vm._s(_vm.$tc('components.physical_sensors', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'physical_sensor.name' && _vm.order.direction == 'asc'),expression:"order.field == 'physical_sensor.name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'physical_sensor.name' && _vm.order.direction == 'desc'),expression:"order.field == 'physical_sensor.name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['physical_sensor.name']),expression:"filter['physical_sensor.name']"}],attrs:{"id":"filter_physical_sensor","type":"text"},domProps:{"value":_vm._s(_vm.filter['physical_sensor.name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'physical_sensor.name';if (!Array.isArray($$exp)){_vm.filter['physical_sensor.name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(1)])])," ",_vm._h('th',{staticClass:"hide-on-med-and-down",attrs:{"data-field":"terrarium"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('terraria.display_name')}}},[_vm._s(_vm.$tc('components.terraria', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'physical_sensor.terrarium.display_name' && _vm.order.direction == 'asc'),expression:"order.field == 'physical_sensor.terrarium.display_name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'physical_sensor.terrarium.display_name' && _vm.order.direction == 'desc'),expression:"order.field == 'physical_sensor.terrarium.display_name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['physical_sensor.terrarium.display_name']),expression:"filter['physical_sensor.terrarium.display_name']"}],attrs:{"id":"filter_terrarium","type":"text"},domProps:{"value":_vm._s(_vm.filter['physical_sensor.terrarium.display_name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'physical_sensor.terrarium.display_name';if (!Array.isArray($$exp)){_vm.filter['physical_sensor.terrarium.display_name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(2)])])," ",_vm._h('th',{attrs:{"data-field":"type"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('type')}}},[_vm._s(_vm.$t('labels.type'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'type' && _vm.order.direction == 'asc'),expression:"order.field == 'type' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'type' && _vm.order.direction == 'desc'),expression:"order.field == 'type' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.type),expression:"filter.type"}],attrs:{"id":"filter_type","type":"text"},domProps:{"value":_vm._s(_vm.filter.type)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.type=$event.target.value}}})," ",_vm._m(3)])])," ",_vm._h('th',{staticClass:"hide-on-small-only",attrs:{"data-field":"rawvalue"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('rawvalue')}}},[_vm._s(_vm.$t('labels.rawvalue', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'rawvalue' && _vm.order.direction == 'asc'),expression:"order.field == 'rawvalue' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'rawvalue' && _vm.order.direction == 'desc'),expression:"order.field == 'rawvalue' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])])," ",_vm._m(4)])])," ",_vm._l((_vm.logical_sensors),function(logical_sensor){return [_vm._h('tbody',[_vm._h('tr',{staticClass:"collapsible-header"},[_vm._h('td',[_vm._h('span',[_vm._m(5,true)," ",_vm._h('a',{attrs:{"href":'/logical_sensors/' + logical_sensor.id}},[_vm._s(logical_sensor.name)])])])," ",_vm._h('td',{staticClass:"hide-on-small-only"},[(logical_sensor.physical_sensor)?_vm._h('span',[_vm._m(6,true)," ",_vm._h('a',{attrs:{"href":'/physical_sensors/' + logical_sensor.physical_sensor.id}},[_vm._s(logical_sensor.physical_sensor.name)])]):_vm._e()])," ",_vm._h('td',{staticClass:"hide-on-med-and-down"},[(logical_sensor.physical_sensor && logical_sensor.physical_sensor.terrarium)?_vm._h('span',[_vm._m(7,true)," ",_vm._h('a',{attrs:{"href":'/terraria/' + logical_sensor.physical_sensor.terrarium.id}},[_vm._s(logical_sensor.physical_sensor.terrarium.display_name)])]):_vm._e()])," ",_vm._h('td',["\n                            "+_vm._s(logical_sensor.type)+"\n                        "])," ",_vm._h('td',{staticClass:"hide-on-small-only"},["\n                             "+_vm._s(Math.round(logical_sensor.rawvalue, 2))+"\n                        "])," ",_vm._h('td',[_vm._h('span',[_vm._h('a',{staticClass:"waves-effect waves-light btn-small",attrs:{"href":'/logical_sensors/' + logical_sensor.id + '/edit'}},[_vm._m(8,true)])])])])," ",_vm._h('tr',{staticClass:"collapsible-body"},[_vm._h('td',["\n                            "+_vm._s(_vm.$t('labels.rawlimitlo'))+": "+_vm._s(logical_sensor.rawvalue_lowerlimit),_vm._m(9,true),"\n                            "+_vm._s(_vm.$t('labels.rawlimithi'))+": "+_vm._s(logical_sensor.rawvalue_upperlimit)+"\n                        "])," ",_vm._h('td',{staticClass:"hide-on-small-only"},[(logical_sensor.physical_sensor.controlunit)?_vm._h('span',["\n                                "+_vm._s(_vm.$tc('components.controlunits', 1))+":\n                                ",_vm._m(10,true)," ",_vm._h('a',{attrs:{"href":'/controlunits/' + logical_sensor.physical_sensor.controlunit.id}},[_vm._s(logical_sensor.physical_sensor.controlunit.name)])]):_vm._e()," ",_vm._m(11,true)," ",_vm._h('span',[_vm._s(_vm.$t('labels.model'))+": "+_vm._s(logical_sensor.physical_sensor.model)])])," ",_vm._h('td',{staticClass:"hide-on-med-and-down"},[(logical_sensor.physical_sensor.terrarium)?_vm._h('span',["\n                                "+_vm._s(_vm.$t('labels.temperature_celsius'))+": "+_vm._s(logical_sensor.physical_sensor.terrarium.cooked_temperature_celsius)+"°C",_vm._m(12,true),"\n                                "+_vm._s(_vm.$t('labels.humidity_percent'))+": "+_vm._s(logical_sensor.physical_sensor.terrarium.cooked_humidity_percent)+"%\n                            "]):_vm._e()])," ",_vm._m(13,true)," ",_vm._m(14,true)," ",_vm._m(15,true)])])]})])," ",(_vm.meta.hasOwnProperty('pagination'))?_vm._h('ul',{staticClass:"pagination"},[_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(1)}}},[_vm._m(16)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._m(17)])])," ",(_vm.meta.pagination.current_page-3 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-3)}}},[_vm._s(_vm.meta.pagination.current_page-3)])]):_vm._e()," ",(_vm.meta.pagination.current_page-2 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-2)}}},[_vm._s(_vm.meta.pagination.current_page-2)])]):_vm._e()," ",(_vm.meta.pagination.current_page-1 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._s(_vm.meta.pagination.current_page-1)])]):_vm._e()," ",_vm._h('li',{staticClass:"active"},[_vm._h('a',{attrs:{"href":"#!"}},[_vm._s(_vm.meta.pagination.current_page)])])," ",(_vm.meta.pagination.current_page+1 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._s(_vm.meta.pagination.current_page+1)])]):_vm._e()," ",(_vm.meta.pagination.current_page+2 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+2)}}},[_vm._s(_vm.meta.pagination.current_page+2)])]):_vm._e()," ",(_vm.meta.pagination.current_page+3 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+3)}}},[_vm._s(_vm.meta.pagination.current_page+3)])]):_vm._e()," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._m(18)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.total_pages)}}},[_vm._m(19)])])]):_vm._e()])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_name"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_physical_sensor"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_terrarium"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_type"}},["Filter"])},function render () {var _vm=this;return _vm._h('th',{staticStyle:{"width":"40px"}})},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["memory"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["memory"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["video_label"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["edit"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["developer_board"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('td')},function render () {var _vm=this;return _vm._h('td',{staticClass:"hide-on-small-only"})},function render () {var _vm=this;return _vm._h('td')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["first_page"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_left"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["last_page"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-28", __vue__options__)
  } else {
    hotAPI.reload("data-v-28", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],36:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            logical_sensors: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        logical_sensorId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'logical_sensors-masonry-grid',
            required: false
        }
    },

    methods: {
        update: function update(cu) {
            var item = null;
            this.logical_sensors.forEach(function (data, index) {
                if (data.id === cu.logical_sensor.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.logical_sensors.push(cu.logical_sensor);
            } else if (item !== null) {
                this.logical_sensors.splice(item, 1, cu.logical_sensor);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        delete: function _delete(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.logical_sensors.forEach(function (data, index) {
                if (data.id === cu.logical_sensor_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.logical_sensors.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/logical_sensors/' + that.logical_sensorId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    if (that.logical_sensorId !== '') {
                        that.logical_sensors = [data.data];
                    } else {
                        that.logical_sensors = data.data;
                    }

                    that.$nextTick(function () {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col'
                        });
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('LogicalSensorUpdated', function (e) {
            _this.update(e);
        }).listen('LogicalSensorDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:[_vm.containerClasses, 'masonry-grid'],attrs:{"id":_vm.containerId}},[_vm._l((_vm.logical_sensors),function(logical_sensor){return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0,true),"\n                    "+_vm._s(_vm.$tc("components.logical_sensors", 2))+"\n                "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(logical_sensor.name)])," ",_vm._m(1,true)])," ",_vm._h('p',[_vm._h('span',[_vm._s(_vm.$t("labels.type"))+": "+_vm._s(_vm.$t("labels." + logical_sensor.type))])])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/logical_sensors/' + logical_sensor.id}},[_vm._s(_vm.$t("buttons.details"))])," ",_vm._h('a',{attrs:{"href":'/logical_sensors/' + logical_sensor.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",_vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(_vm.$tc("components.physical_sensors", 1)),_vm._m(2,true)])," ",_vm._h('p',[(logical_sensor.physical_sensor)?_vm._h('span',["\n                            "+_vm._s(_vm.$tc("components.physical_sensor", 1))+":\n                            ",_vm._h('a',{attrs:{"href":'/physical_sensors/' + logical_sensor.physical_sensor.id}},[_vm._s(logical_sensor.physical_sensor.name)])]):_vm._e()])," ",_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(_vm.$tc("components.logical_sensor_thresholds", 2))])," ",_vm._l((logical_sensor.thresholds),function(lst){return _vm._h('p',["\n                        "+_vm._s(_vm.$t("labels.starts_at"))+" "+_vm._s(lst.timestamps.starts)+":\n                        ",_vm._h('strong',[_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(lst.rawvalue_lowerlimit && !lst.rawvalue_upperlimit),expression:"lst.rawvalue_lowerlimit && !lst.rawvalue_upperlimit"}]},["min "+_vm._s(lst.rawvalue_lowerlimit)+_vm._s(_vm.$t("units." + logical_sensor.type))])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(!lst.rawvalue_lowerlimit && lst.rawvalue_upperlimit),expression:"!lst.rawvalue_lowerlimit && lst.rawvalue_upperlimit"}]},["max "+_vm._s(lst.rawvalue_upperlimit)+_vm._s(_vm.$t("units." + logical_sensor.type))])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(lst.rawvalue_lowerlimit && lst.rawvalue_upperlimit),expression:"lst.rawvalue_lowerlimit && lst.rawvalue_upperlimit"}]},[_vm._s(lst.rawvalue_lowerlimit)+" - "+_vm._s(lst.rawvalue_upperlimit)+_vm._s(_vm.$t("units." + logical_sensor.type))])])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(lst.id == logical_sensor.current_threshold_id),expression:"lst.id == logical_sensor.current_threshold_id"}]},[_vm._h('span',{staticClass:"new badge",attrs:{"data-badge-caption":_vm.$t('labels.active')}})])])})])])])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["memory"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["close"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-27", __vue__options__)
  } else {
    hotAPI.reload("data-v-27", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],37:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            logs: [],
            meta: [],
            filter: {},
            filter_string: '',
            order: {
                field: 'created_at',
                direction: 'desc'
            },
            order_string: '',
            page: 1
        };
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        set_order: function set_order(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                } else {
                    this.order.direction = 'asc';
                }
            } else {
                this.order.field = field;
            }

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function set_filter() {
            this.filter_string = '&';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function set_page(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/logs?page=' + this.page + this.filter_string + this.order_string,
                method: 'GET',
                success: function success(data) {
                    that.meta = data.meta;
                    that.logs = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        this.load_data();
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight"},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{attrs:{"data-field":"source"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('source_name')}}},[_vm._s(_vm.$t('labels.source'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'source_name' && _vm.order.direction == 'asc'),expression:"order.field == 'source_name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'source_name' && _vm.order.direction == 'desc'),expression:"order.field == 'source_name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.source_name),expression:"filter.source_name"}],attrs:{"id":"filter_source","type":"text"},domProps:{"value":_vm._s(_vm.filter.source_name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.source_name=$event.target.value}}})," ",_vm._m(0)])])," ",_vm._h('th',{attrs:{"data-field":"action"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('action')}}},[_vm._s(_vm.$t('labels.action'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'action' && _vm.order.direction == 'asc'),expression:"order.field == 'action' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'action' && _vm.order.direction == 'desc'),expression:"order.field == 'action' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.action),expression:"filter.action"}],attrs:{"id":"filter_action","type":"text"},domProps:{"value":_vm._s(_vm.filter.action)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.action=$event.target.value}}})," ",_vm._m(1)])])," ",_vm._h('th',{attrs:{"data-field":"target"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('target_name')}}},[_vm._s(_vm.$t('labels.target'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'target_name' && _vm.order.direction == 'asc'),expression:"order.field == 'target_name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'target_name' && _vm.order.direction == 'desc'),expression:"order.field == 'target_name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.target_name),expression:"filter.target_name"}],attrs:{"id":"filter_target","type":"text"},domProps:{"value":_vm._s(_vm.filter.target_name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.target_name=$event.target.value}}})," ",_vm._m(2)])])," ",_vm._h('th',{attrs:{"data-field":"associated"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('associatedWith_name')}}},[_vm._s(_vm.$t('labels.associated_with'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'associatedWith_name' && _vm.order.direction == 'asc'),expression:"order.field == 'associatedWith_name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'associatedWith_name' && _vm.order.direction == 'desc'),expression:"order.field == 'associatedWith_name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.associatedWith_name),expression:"filter.associatedWith_name"}],attrs:{"id":"filter_associated_with","type":"text"},domProps:{"value":_vm._s(_vm.filter.associatedWith_name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.associatedWith_name=$event.target.value}}})," ",_vm._m(3)])])," ",_vm._h('th',[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('created_at')}}},[_vm._s(_vm.$t('labels.created_at'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'created_at' && _vm.order.direction == 'asc'),expression:"order.field == 'created_at' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'created_at' && _vm.order.direction == 'desc'),expression:"order.field == 'created_at' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])])])])," ",_vm._h('tbody',[_vm._l((_vm.logs),function(log){return _vm._h('tr',[_vm._h('td',[(log.source != null)?_vm._h('span',[_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(log.source),expression:"log.source"}],staticClass:"material-icons"},[_vm._s(log.source.icon)])," ",_vm._h('a',{attrs:{"href":log.source.url}},[_vm._s(log.source.name)])]):_vm._h('span',["\n                        "+_vm._s(log.source_name)+"\n                    "])," "])," ",_vm._h('td',[(log.action == 'start')?_vm._h('span',{staticClass:"material-icons"},["play_arrow"]):_vm._e()," ",(log.action == 'finish')?_vm._h('span',{staticClass:"material-icons"},["done"]):_vm._e()," ",(log.action == 'create')?_vm._h('span',{staticClass:"material-icons"},["add"]):_vm._e()," ",(log.action == 'delete')?_vm._h('span',{staticClass:"material-icons"},["delete"]):_vm._e()," ",(log.action == 'update')?_vm._h('span',{staticClass:"material-icons"},["update"]):_vm._e()," ",(log.action == 'recover')?_vm._h('span',{staticClass:"material-icons"},["settings_backup_restore"]):_vm._e()," ",(log.action == 'notify_recovered')?_vm._h('span',{staticClass:"material-icons"},["notifications_none"]):_vm._e()," ",(log.action == 'notify')?_vm._h('span',{staticClass:"material-icons"},["notifications_active"]):_vm._e(),"\n                    "+_vm._s(log.action)+"\n                "])," ",_vm._h('td',[(log.target != null)?_vm._h('span',[_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(log.target),expression:"log.target"}],staticClass:"material-icons"},[_vm._s(log.target.icon)])," ",_vm._h('a',{attrs:{"href":log.target.url}},[_vm._s(log.target.name)])]):_vm._h('span',["\n                        "+_vm._s(log.target_name)+"\n                    "])," "])," ",_vm._h('td',[(log.associated != null)?_vm._h('span',[_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(log.associated),expression:"log.associated"}],staticClass:"material-icons"},[_vm._s(log.associated.icon)])," ",_vm._h('a',{attrs:{"href":log.associated.url}},[_vm._s(log.associated.name)])]):_vm._h('span',["\n                        "+_vm._s(log.associated_name)+"\n                    "])," "])," ",_vm._h('td',["\n                    "+_vm._s(log.timestamps.created)+"\n                "])])})])])," ",(_vm.meta.hasOwnProperty('pagination'))?_vm._h('ul',{staticClass:"pagination"},[_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(1)}}},[_vm._m(4)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._m(5)])])," ",(_vm.meta.pagination.current_page-3 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-3)}}},[_vm._s(_vm.meta.pagination.current_page-3)])]):_vm._e()," ",(_vm.meta.pagination.current_page-2 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-2)}}},[_vm._s(_vm.meta.pagination.current_page-2)])]):_vm._e()," ",(_vm.meta.pagination.current_page-1 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._s(_vm.meta.pagination.current_page-1)])]):_vm._e()," ",_vm._h('li',{staticClass:"active"},[_vm._h('a',{attrs:{"href":"#!"}},[_vm._s(_vm.meta.pagination.current_page)])])," ",(_vm.meta.pagination.current_page+1 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._s(_vm.meta.pagination.current_page+1)])]):_vm._e()," ",(_vm.meta.pagination.current_page+2 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+2)}}},[_vm._s(_vm.meta.pagination.current_page+2)])]):_vm._e()," ",(_vm.meta.pagination.current_page+3 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+3)}}},[_vm._s(_vm.meta.pagination.current_page+3)])]):_vm._e()," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._m(6)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.total_pages)}}},[_vm._m(7)])])]):_vm._e()])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_source"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_action"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_target"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_associated_with"}},["Filter"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["first_page"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_left"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["last_page"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-34", __vue__options__)
  } else {
    hotAPI.reload("data-v-34", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],38:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            physical_sensors: [],
            meta: [],
            filter: {
                name: '',
                model: '',
                'controlunit.name': '',
                'terrarium.display_name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        };
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        refreshTimeoutSeconds: {
            type: Number,
            default: 60,
            required: false
        }
    },

    methods: {
        update: function update(ps) {
            var item = null;
            this.physical_sensors.forEach(function (data, index) {
                if (data.id === ps.physical_sensor.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.physical_sensors.splice(item, 1, ps.physical_sensor);
            }
        },

        delete: function _delete(ps) {
            var item = null;
            this.physical_sensors.forEach(function (data, index) {
                if (data.id === ps.physical_sensor.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.physical_sensors.splice(item, 1);
            }
        },

        set_order: function set_order(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                } else {
                    this.order.direction = 'asc';
                }
            } else {
                this.order.field = field;
            }

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function set_filter() {
            this.filter_string = '&';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function set_page(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/physical_sensors?page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.meta = data.meta;
                    that.physical_sensors = data.data;
                    that.$nextTick(function () {
                        $('table.collapsible').collapsibletable();
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('PhysicalSensorUpdated', function (e) {
            _this.update(e);
        }).listen('PhysicalensorDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight collapsible",attrs:{"data-collapsible":"expandable"}},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{attrs:{"data-field":"name"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('name')}}},[_vm._s(_vm.$t('labels.name'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'asc'),expression:"order.field == 'name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'desc'),expression:"order.field == 'name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.name),expression:"filter.name"}],attrs:{"id":"filter_name","type":"text"},domProps:{"value":_vm._s(_vm.filter.name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.name=$event.target.value}}})," ",_vm._m(0)])])," ",_vm._h('th',{attrs:{"data-field":"model"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('model')}}},[_vm._s(_vm.$t('labels.model'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'model' && _vm.order.direction == 'asc'),expression:"order.field == 'model' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'model' && _vm.order.direction == 'desc'),expression:"order.field == 'model' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.model),expression:"filter.model"}],attrs:{"id":"filter_model","type":"text"},domProps:{"value":_vm._s(_vm.filter.model)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.model=$event.target.value}}})," ",_vm._m(1)])])," ",_vm._h('th',{staticClass:"hide-on-small-only",attrs:{"data-field":"controlunit"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('controlunit')}}},[_vm._s(_vm.$tc('components.controlunit', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'asc'),expression:"order.field == 'controlunit' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'desc'),expression:"order.field == 'controlunit' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['controlunit.name']),expression:"filter['controlunit.name']"}],attrs:{"id":"filter_controlunit","type":"text"},domProps:{"value":_vm._s(_vm.filter['controlunit.name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'controlunit.name';if (!Array.isArray($$exp)){_vm.filter['controlunit.name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(2)])])," ",_vm._h('th',{staticClass:"hide-on-med-and-down",attrs:{"data-field":"terrarium"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('terraria.display_name')}}},[_vm._s(_vm.$tc('components.terraria', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'physical_sensor.terrarium.display_name' && _vm.order.direction == 'asc'),expression:"order.field == 'physical_sensor.terrarium.display_name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'physical_sensor.terrarium.display_name' && _vm.order.direction == 'desc'),expression:"order.field == 'physical_sensor.terrarium.display_name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['terrarium.display_name']),expression:"filter['terrarium.display_name']"}],attrs:{"id":"filter_terrarium","type":"text"},domProps:{"value":_vm._s(_vm.filter['terrarium.display_name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'terrarium.display_name';if (!Array.isArray($$exp)){_vm.filter['terrarium.display_name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(3)])])," ",_vm._m(4)])])," ",_vm._l((_vm.physical_sensors),function(physical_sensor){return [_vm._h('tbody',[_vm._h('tr',{staticClass:"collapsible-header"},[_vm._h('td',[_vm._h('span',[_vm._m(5,true)," ",_vm._h('a',{attrs:{"href":'/physical_sensors/' + physical_sensor.id}},[_vm._s(physical_sensor.name)])])])," ",_vm._h('td',[_vm._h('span',["\n                                "+_vm._s(physical_sensor.model)+"\n                            "])])," ",_vm._h('td',{staticClass:"hide-on-small-only"},[(physical_sensor.controlunit)?_vm._h('span',[_vm._m(6,true)," ",_vm._h('a',{attrs:{"href":'/controlunits/' + physical_sensor.controlunit.id}},[_vm._s(physical_sensor.controlunit.name)])]):_vm._e()])," ",_vm._h('td',{staticClass:"hide-on-med-and-down"},[(physical_sensor.terrarium)?_vm._h('span',[_vm._m(7,true)," ",_vm._h('a',{attrs:{"href":'/terraria/' + physical_sensor.terrarium.id}},[_vm._s(physical_sensor.terrarium.display_name)])]):_vm._e()])," ",_vm._h('td',[_vm._h('span',[_vm._h('a',{staticClass:"waves-effect waves-light btn-small",attrs:{"href":'/physical_sensors/' + physical_sensor.id + '/edit'}},[_vm._m(8,true)])])])])," ",_vm._h('tr',{staticClass:"collapsible-body"},[_vm._h('td',{attrs:{"colspan":"4"}},["\n                            "+_vm._s(_vm.$t('labels.last_heartbeat'))+":\n                            "," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(physical_sensor.timestamps.last_heartbeat_diff.days > 0),expression:"physical_sensor.timestamps.last_heartbeat_diff.days > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":physical_sensor.timestamps.last_heartbeat}},["\n                                    "+_vm._s(_vm.$t('units.days_ago', {val: physical_sensor.timestamps.last_heartbeat_diff.days}))+"\n                                "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(physical_sensor.timestamps.last_heartbeat_diff.days < 1 &&
                                          physical_sensor.timestamps.last_heartbeat_diff.hours > 0),expression:"physical_sensor.timestamps.last_heartbeat_diff.days < 1 &&\n                                          physical_sensor.timestamps.last_heartbeat_diff.hours > 0"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":physical_sensor.timestamps.last_heartbeat}},["\n                                    "+_vm._s(_vm.$t('units.hours_ago', {val: physical_sensor.timestamps.last_heartbeat_diff.hours}))+"\n                                "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(physical_sensor.timestamps.last_heartbeat_diff.days < 1 &&
                                          physical_sensor.timestamps.last_heartbeat_diff.hours < 1 &&
                                          physical_sensor.timestamps.last_heartbeat_diff.minutes > 1),expression:"physical_sensor.timestamps.last_heartbeat_diff.days < 1 &&\n                                          physical_sensor.timestamps.last_heartbeat_diff.hours < 1 &&\n                                          physical_sensor.timestamps.last_heartbeat_diff.minutes > 1"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":physical_sensor.timestamps.last_heartbeat}},["\n                                    "+_vm._s(_vm.$t('units.minutes_ago', {val: physical_sensor.timestamps.last_heartbeat_diff.minutes}))+"\n                                "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(physical_sensor.timestamps.last_heartbeat_diff.days < 1 &&
                                          physical_sensor.timestamps.last_heartbeat_diff.hours < 1 &&
                                          physical_sensor.timestamps.last_heartbeat_diff.minutes < 2),expression:"physical_sensor.timestamps.last_heartbeat_diff.days < 1 &&\n                                          physical_sensor.timestamps.last_heartbeat_diff.hours < 1 &&\n                                          physical_sensor.timestamps.last_heartbeat_diff.minutes < 2"}],staticClass:"tooltipped",attrs:{"data-position":"bottom","data-delay":"50","data-tooltip":physical_sensor.timestamps.last_heartbeat}},["\n                                    "+_vm._s(_vm.$t('units.just_now'))+"\n                                "])," ",_vm._m(9,true),"\n                            "+_vm._s(_vm.$tc('components.logical_sensors', 2))+":\n                            ",_vm._l((physical_sensor.logical_sensors),function(logical_sensor,index){return _vm._h('span',[_vm._m(10,true)," ",_vm._h('a',{attrs:{"href":'/logical_sensors/' + logical_sensor.id}},[_vm._s(logical_sensor.name)])," ",(index < physical_sensor.logical_sensors.length-1)?[", "]:_vm._e()])})])," ",_vm._m(11,true)])])]})])," ",(_vm.meta.hasOwnProperty('pagination'))?_vm._h('ul',{staticClass:"pagination"},[_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(1)}}},[_vm._m(12)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._m(13)])])," ",(_vm.meta.pagination.current_page-3 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-3)}}},[_vm._s(_vm.meta.pagination.current_page-3)])]):_vm._e()," ",(_vm.meta.pagination.current_page-2 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-2)}}},[_vm._s(_vm.meta.pagination.current_page-2)])]):_vm._e()," ",(_vm.meta.pagination.current_page-1 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._s(_vm.meta.pagination.current_page-1)])]):_vm._e()," ",_vm._h('li',{staticClass:"active"},[_vm._h('a',{attrs:{"href":"#!"}},[_vm._s(_vm.meta.pagination.current_page)])])," ",(_vm.meta.pagination.current_page+1 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._s(_vm.meta.pagination.current_page+1)])]):_vm._e()," ",(_vm.meta.pagination.current_page+2 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+2)}}},[_vm._s(_vm.meta.pagination.current_page+2)])]):_vm._e()," ",(_vm.meta.pagination.current_page+3 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+3)}}},[_vm._s(_vm.meta.pagination.current_page+3)])]):_vm._e()," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._m(14)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.total_pages)}}},[_vm._m(15)])])]):_vm._e()])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_name"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_model"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_controlunit"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_terrarium"}},["Filter"])},function render () {var _vm=this;return _vm._h('th',{staticStyle:{"width":"40px"}})},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["memory"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["developer_board"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["video_label"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["edit"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["memory"])},function render () {var _vm=this;return _vm._h('td',{staticClass:"hide-on-med-and-down"})},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["first_page"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_left"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["last_page"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-26", __vue__options__)
  } else {
    hotAPI.reload("data-v-26", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],39:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            physical_sensors: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        physical_sensorId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'physical_sensors-masonry-grid',
            required: false
        }
    },

    methods: {
        update: function update(cu) {
            var item = null;
            this.physical_sensors.forEach(function (data, index) {
                if (data.id === cu.physical_sensor.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.physical_sensors.push(cu.physical_sensor);
            } else if (item !== null) {
                this.physical_sensors.splice(item, 1, cu.physical_sensor);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        delete: function _delete(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.physical_sensors.forEach(function (data, index) {
                if (data.id === cu.physical_sensor_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.physical_sensors.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/physical_sensors/' + that.physical_sensorId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    if (that.physical_sensorId !== '') {
                        that.physical_sensors = [data.data];
                    } else {
                        that.physical_sensors = data.data;
                    }

                    that.$nextTick(function () {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col'
                        });
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('PhysicalSensorUpdated', function (e) {
            _this.update(e);
        }).listen('PhysicalSensorDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:[_vm.containerClasses, 'masonry-grid'],attrs:{"id":_vm.containerId}},[_vm._l((_vm.physical_sensors),function(physical_sensor){return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0,true),"\n                    "+_vm._s(_vm.$tc("components.physical_sensors", 2))+"\n                "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(physical_sensor.name)])," ",_vm._m(1,true)])," ",_vm._m(2,true)])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/physical_sensors/' + physical_sensor.id}},[_vm._s(_vm.$t("buttons.details"))])," ",_vm._h('a',{attrs:{"href":'/physical_sensors/' + physical_sensor.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",_vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(_vm.$tc("components.logical_sensors", 2))+" ",_vm._m(3,true)])," ",_vm._l((physical_sensor.logical_sensors),function(logical_sensor){return _vm._h('p',[_vm._h('a',{attrs:{"href":'/logical_sensors/' + logical_sensor.id}},[_vm._s(logical_sensor.name)])," ",_vm._h('i',[_vm._s(_vm.$t("labels." + logical_sensor.type))])])})])])])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["memory"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('p')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["close"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-25", __vue__options__)
  } else {
    hotAPI.reload("data-v-25", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],40:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            pumps: [],
            meta: [],
            filter: {
                name: '',
                'controlunit.name': '',
                'terrarium.display_name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        };
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(p) {
            var item = null;
            this.pumps.forEach(function (data, index) {
                if (data.id === p.pump.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.pumps.splice(item, 1, p.pump);
            }
        },

        delete: function _delete(p) {
            var item = null;
            this.pumps.forEach(function (data, index) {
                if (data.id === p.pump.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.pumps.splice(item, 1);
            }
        },
        set_order: function set_order(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                } else {
                    this.order.direction = 'asc';
                }
            } else {
                this.order.field = field;
            }

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function set_filter() {
            this.filter_string = '&';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function set_page(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/pumps?page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.meta = data.meta;
                    that.pumps = data.data;
                    that.$nextTick(function () {
                        $('table.collapsible').collapsibletable();
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('PumpUpdated', function (e) {
            _this.update(e);
        }).listen('PumpDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight collapsible",attrs:{"data-collapsible":"expandable"}},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{attrs:{"data-field":"name"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('name')}}},[_vm._s(_vm.$t('labels.name'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'asc'),expression:"order.field == 'name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'desc'),expression:"order.field == 'name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.name),expression:"filter.name"}],attrs:{"id":"filter_name","type":"text"},domProps:{"value":_vm._s(_vm.filter.name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.name=$event.target.value}}})," ",_vm._m(0)])])," ",_vm._h('th',{attrs:{"data-field":"controlunit"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('controlunit')}}},[_vm._s(_vm.$tc('components.controlunit', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'asc'),expression:"order.field == 'controlunit' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'desc'),expression:"order.field == 'controlunit' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['controlunit.name']),expression:"filter['controlunit.name']"}],attrs:{"id":"filter_controlunit","type":"text"},domProps:{"value":_vm._s(_vm.filter['controlunit.name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'controlunit.name';if (!Array.isArray($$exp)){_vm.filter['controlunit.name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(1)])])," ",_vm._m(2)])])," ",_vm._l((_vm.pumps),function(pump){return [_vm._h('tbody',[_vm._h('tr',{staticClass:"collapsible-header"},[_vm._h('td',[_vm._h('span',[_vm._m(3,true)," ",_vm._h('a',{attrs:{"href":'/pumps/' + pump.id}},[_vm._s(pump.name)])])])," ",_vm._h('td',[(pump.controlunit)?_vm._h('span',[_vm._m(4,true)," ",_vm._h('a',{attrs:{"href":'/controlunits/' + pump.controlunit.id}},[_vm._s(pump.controlunit.name)])]):_vm._e()])," ",_vm._h('td',[_vm._h('span',[_vm._h('a',{staticClass:"waves-effect waves-light btn-small",attrs:{"href":'/pumps/' + pump.id + '/edit'}},[_vm._m(5,true)])])])])," ",_vm._h('tr',{staticClass:"collapsible-body"},[_vm._h('td',{attrs:{"colspan":"3"}},["\n                            "+_vm._s(_vm.$tc('components.valves', 2))+":\n                            ",_vm._l((pump.valves),function(valve,index){return _vm._h('span',[_vm._m(6,true)," ",_vm._h('a',{attrs:{"href":'/valves/' + valve.id}},[_vm._s(valve.name)])," ",(index < pump.valves.length-1)?[", "]:_vm._e()])})])])])]})])," ",(_vm.meta.hasOwnProperty('pagination'))?_vm._h('ul',{staticClass:"pagination"},[_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(1)}}},[_vm._m(7)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._m(8)])])," ",(_vm.meta.pagination.current_page-3 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-3)}}},[_vm._s(_vm.meta.pagination.current_page-3)])]):_vm._e()," ",(_vm.meta.pagination.current_page-2 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-2)}}},[_vm._s(_vm.meta.pagination.current_page-2)])]):_vm._e()," ",(_vm.meta.pagination.current_page-1 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._s(_vm.meta.pagination.current_page-1)])]):_vm._e()," ",_vm._h('li',{staticClass:"active"},[_vm._h('a',{attrs:{"href":"#!"}},[_vm._s(_vm.meta.pagination.current_page)])])," ",(_vm.meta.pagination.current_page+1 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._s(_vm.meta.pagination.current_page+1)])]):_vm._e()," ",(_vm.meta.pagination.current_page+2 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+2)}}},[_vm._s(_vm.meta.pagination.current_page+2)])]):_vm._e()," ",(_vm.meta.pagination.current_page+3 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+3)}}},[_vm._s(_vm.meta.pagination.current_page+3)])]):_vm._e()," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._m(9)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.total_pages)}}},[_vm._m(10)])])]):_vm._e()])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_name"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_controlunit"}},["Filter"])},function render () {var _vm=this;return _vm._h('th',{staticStyle:{"width":"40px"}})},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["rotate_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["developer_board"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["edit"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["transform"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["first_page"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_left"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["last_page"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-22", __vue__options__)
  } else {
    hotAPI.reload("data-v-22", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],41:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            pumps: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        pumpId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'valves-masonry-grid',
            required: false
        }
    },

    methods: {
        update: function update(cu) {
            var item = null;
            this.pumps.forEach(function (data, index) {
                if (data.id === cu.pump.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.pumps.push(cu.pump);
            } else if (item !== null) {
                this.pumps.splice(item, 1, cu.pump);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        delete: function _delete(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.pumps.forEach(function (data, index) {
                if (data.id === cu.pump_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.pumps.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/pumps/' + that.pumpId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    if (that.pumpId !== '') {
                        that.pumps = [data.data];
                    } else {
                        that.pumps = data.data;
                    }

                    that.$nextTick(function () {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col'
                        });
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('PumpUpdated', function (e) {
            _this.update(e);
        }).listen('PumpDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:[_vm.containerClasses, 'masonry-grid'],attrs:{"id":_vm.containerId}},[_vm._l((_vm.pumps),function(pump){return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0,true),"\n                    "+_vm._s(_vm.$tc("components.pumps", 2))+"\n                "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(pump.name)])," ",_vm._m(1,true)])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/pumps/' + pump.id}},[_vm._s(_vm.$t("buttons.details"))])," ",_vm._h('a',{attrs:{"href":'/pumps/' + pump.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",_vm._m(2,true)])])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["rotate_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._h('i',{staticClass:"material-icons right"},["close"])])," ",_vm._h('p')])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-21", __vue__options__)
  } else {
    hotAPI.reload("data-v-21", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],42:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            echo: window.echo,
            ready: false,
            system: {
                emergency_stop: false
            }
        };
    },
    created: function created() {
        var _this = this;

        var that = this;

        $.ajax({
            url: '/api/v1/dashboard/system_status?raw=true',
            method: 'GET',
            success: function success(data) {
                that.system = data.data;
            },
            error: function error(_error) {
                console.log((0, _stringify2.default)(_error));
            }
        });

        window.echo.private('dashboard-updates').listen('SystemStatusUpdated', function (e) {
            _this.system = e.system_status;
        });

        setTimeout(function () {
            that.ready = true;
        }, 2000);
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('transition',{attrs:{"name":"fade"}},[_vm._h('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.echo.connector.pusher.connection.state !== 'connected'
                  && _vm.echo.connector.pusher.connection.state !== 'initialized'
                  && _vm.ready),expression:"echo.connector.pusher.connection.state !== 'connected'\n                  && echo.connector.pusher.connection.state !== 'initialized'\n                  && ready"}],staticClass:"side-menu-info"},[_vm._h('div',{staticClass:"side-menu-info-title"},[_vm._h('i',{staticClass:"material-icons"},["signal_wifi_off"])," "+_vm._s(_vm.$t('labels.connecting'))+"\n            "])," ",_vm._h('div',{staticClass:"side-menu-info-content"},[_vm._h('span',[_vm._s(_vm.$t('tooltips.connecting_to_server'))])])])])," ",_vm._h('transition',{attrs:{"name":"fade"}},[_vm._h('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.system.emergency_stop === true),expression:"system.emergency_stop === true"}],staticClass:"side-menu-info"},[_vm._h('div',{staticClass:"side-menu-info-title"},[_vm._h('i',{staticClass:"material-icons"},["power_settings_new"])," "+_vm._s(_vm.$t('labels.emergency_stop'))+"\n            "])," ",_vm._h('div',{staticClass:"side-menu-info-content"},[_vm._h('span',[_vm._s(_vm.$t('tooltips.emergency_stop'))])])])])])}
__vue__options__.staticRenderFns = []
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1", __vue__options__)
  } else {
    hotAPI.reload("data-v-1", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],43:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            terraria: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(t) {
            var item = null;
            this.terraria.forEach(function (data, index) {
                if (data.id === t.terrarium.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.terraria.push(t.terrarium);
            } else if (item !== null) {
                this.terraria.splice(item, 1, t.terrarium);
            }
            window.eventHubVue.$emit('TerrariumGraphUpdated', t);
        },

        delete: function _delete(t) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.terraria.forEach(function (data, index) {
                if (data.id === t.terrarium_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.terraria.splice(item, 1);
            }
        },

        submit: function submit(e) {
            window.submit_form(e);
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/terraria/?raw=true&history_minutes=0',
                method: 'GET',
                success: function success(data) {
                    that.terraria = data.data;

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('TerrariumUpdated', function (e) {
            _this.update(e);
        }).listen('TerrariumDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }

};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:_vm.containerClasses,attrs:{"id":_vm.containerId}},[_vm._l((_vm.terraria),function(terrarium){return _vm._h('div',{class:_vm.wrapperClasses},[_vm._m(0,true)," ",_vm._h('a',{attrs:{"href":'/terraria/' + terrarium.id}},[_vm._h('strong',[_vm._s(terrarium.display_name)])])," ",_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('strong',[_vm._s(_vm.$tc('components.animals', 2))])," ",_vm._l((terrarium.animals),function(animal){return _vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('a',{attrs:{"href":'/animals/' + animal.id}},[_vm._h('strong',[_vm._s(animal.display_name)])])," ",_vm._h('i',[_vm._s(animal.common_name)+" "+_vm._s(animal.latin_name)])])})])," ",_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('strong',[_vm._s(_vm.$tc('components.action_sequences', 2))])," ",_vm._l((terrarium.action_sequences),function(action_sequence){return _vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('a',{attrs:{"href":'/action_sequences/' + action_sequence.id}},[_vm._h('strong',[_vm._s(action_sequence.name)])])," ",_vm._l((action_sequence.schedules),function(schedule){return _vm._h('i',[_vm._s(schedule.timestamps.starts)])})," ",_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('strong',[_vm._s(_vm.$tc('components.actions', 2))])," ",_vm._l((action_sequence.actions),function(action){return _vm._h('div',{staticStyle:{"margin-left":"20px"}},[(action.target_object != undefined)?_vm._h('div',[_vm._h('a',{attrs:{"href":'/actions/' + action.id}},[_vm._h('strong',[_vm._s(action.target_object.name)])])," ",_vm._m(1,true),"\n                            "+_vm._s(action.desired_state)+"\n                            ",_vm._h('i',[_vm._s(action.duration_minutes)+" "+_vm._s(_vm.$tc('units.minutes', action.duration_minutes))])," ",(action.target_object == undefined)?_vm._h('div',[_vm._m(2,true)]):_vm._e()]):_vm._e()])})])])})])," ",_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('strong',[_vm._s(_vm.$tc('components.physical_sensors', 2))])," ",_vm._l((terrarium.physical_sensors),function(physical_sensor){return _vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('a',{attrs:{"href":'/physical_sensors/' + physical_sensor.id}},[_vm._h('strong',[_vm._s(physical_sensor.name)])])," ",_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('strong',[_vm._s(_vm.$tc('components.controlunit', 1))])," ",(physical_sensor.controlunit != undefined)?_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('a',{attrs:{"href":'/controlunits/' + physical_sensor.controlunit.id}},[_vm._h('strong',[_vm._s(physical_sensor.controlunit.name)])])]):_vm._e()," ",_vm._h('strong',[_vm._s(_vm.$tc('components.logical_sensors', 2))])," ",_vm._l((physical_sensor.logical_sensors),function(logical_sensor){return _vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('a',{attrs:{"href":'/logical_sensors/' + logical_sensor.id}},[_vm._h('strong',[_vm._s(logical_sensor.name)])])," ",_vm._h('i',[_vm._s(logical_sensor.type)])," ",_vm._m(3,true),"\n                        "+_vm._s(logical_sensor.rawvalue)+"\n                    "])})])])})])," ",_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('strong',[_vm._s(_vm.$tc('components.valves', 2))])," ",_vm._l((terrarium.valves),function(valve){return _vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('a',{attrs:{"href":'/valves/' + valve.id}},[_vm._h('strong',[_vm._s(valve.name)])," ",_vm._h('i',[_vm._s(valve.state)])])," ",_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('strong',[_vm._s(_vm.$tc('components.controlunit', 1))])," ",(valve.controlunit != undefined)?_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('a',{attrs:{"href":'/controlunits/' + valve.controlunit.id}},[_vm._h('strong',[_vm._s(valve.controlunit.name)])])]):_vm._e()," ",_vm._h('strong',[_vm._s(_vm.$tc('components.pumps', 2))])," ",(valve.pump != undefined)?_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('a',{attrs:{"href":'/pumps/' + valve.pump.id}},[_vm._h('strong',[_vm._s(valve.pump.name)])])," ",_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('strong',[_vm._s(_vm.$tc('components.controlunit', 1))])," ",(valve.pump.controlunit != undefined)?_vm._h('div',{staticStyle:{"margin-left":"20px"}},[_vm._h('a',{attrs:{"href":'/controlunits/' + valve.pump.controlunit.id}},[_vm._h('strong',[_vm._s(valve.pump.controlunit.name)])])]):_vm._e()])]):_vm._e()])])})])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["keyboard_arrow_right"])},function render () {var _vm=this;return _vm._h('strong',{staticClass:"red-text"},["unknown"])},function render () {var _vm=this;return _vm._h('br')}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-14", __vue__options__)
  } else {
    hotAPI.reload("data-v-14", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],44:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

var _inlineGraph = require('./inline-graph.vue');

var _inlineGraph2 = _interopRequireDefault(_inlineGraph);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            terraria: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        terrariumId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'terraria-masonry-grid',
            required: false
        }
    },

    components: {
        'inline-graph': _inlineGraph2.default
    },

    methods: {
        update: function update(t) {
            var item = null;
            this.terraria.forEach(function (data, index) {
                if (data.id === t.terrarium.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.terraria.push(t.terrarium);
            } else if (item !== null) {
                this.terraria.splice(item, 1, t.terrarium);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
            window.eventHubVue.$emit('TerrariumGraphUpdated', t);
        },

        delete: function _delete(t) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.terraria.forEach(function (data, index) {
                if (data.id === t.terrarium_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.terraria.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        submit: function submit(e) {
            window.submit_form(e);
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/terraria/' + that.terrariumId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    if (that.terrariumId !== '') {
                        that.terraria = [data.data];
                    } else {
                        that.terraria = data.data;
                    }

                    that.$nextTick(function () {
                        var $container = $('#' + that.containerId);
                        $container.masonry({
                            columnWidth: '.col',
                            itemSelector: '.col'
                        });
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('TerrariumUpdated', function (e) {
            _this.update(e);
        }).listen('TerrariumDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }

};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:[_vm.containerClasses, 'masonry-grid'],attrs:{"id":_vm.containerId}},[_vm._l((_vm.terraria),function(terrarium){return _vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-image waves-effect waves-block waves-light terrarium-card-image",class:terrarium.default_background_filepath ? '' : 'teal lighten-1',style:(terrarium.default_background_filepath ? 'background-image: url(\'' + terrarium.default_background_filepath + '\');' : '')},[_vm._h('div',[_vm._h('inline-graph',{attrs:{"parentid":terrarium.id,"graphtype":"humidity_percent","type":"line","options":{'fill': null, 'strokeWidth': '3', 'stroke': '#2196f3', width: '100%', height:'140px', min: 1, max: 99},"source":'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/humidity_percent'}})])," ",_vm._h('div',{staticStyle:{"position":"relative","top":"-145px"}},[_vm._h('inline-graph',{attrs:{"parentid":terrarium.id,"graphtype":"temperature_celsius","type":"line","options":{'fill': null, 'strokeWidth': '3', 'stroke': '#b71c1c', width: '100%', height:'140px', min: 1, max: 99},"source":'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/temperature_celsius'}})])])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(terrarium.display_name)])," ",_vm._m(0,true)])," ",_vm._h('p',[_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(terrarium.cooked_temperature_celsius !== null),expression:"terrarium.cooked_temperature_celsius !== null"}],class:{ 'red-text': terrarium.temperature_critical, 'darken-3': terrarium.temperature_critical }},["\n                        "+_vm._s(_vm.$t("labels.temperature"))+": "+_vm._s(terrarium.cooked_temperature_celsius)+"°C\n                        ",_vm._m(1,true)])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(terrarium.cooked_humidity_percent !== null),expression:"terrarium.cooked_humidity_percent !== null"}],class:{ 'red-text': terrarium.humidity_critical, 'darken-3': terrarium.humidity_critical }},["\n                        "+_vm._s(_vm.$t("labels.humidity"))+": "+_vm._s(terrarium.cooked_humidity_percent)+"%\n                    "])," ",_vm._h('span',{directives:[{name:"show",rawName:"v-show",value:(terrarium.heartbeat_critical),expression:"terrarium.heartbeat_critical"}],staticClass:"red-text darken-3"},[_vm._m(2,true),"\n                        "+_vm._s(_vm.$t("tooltips.heartbeat_critical"))+"\n                    "])])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/terraria/' + terrarium.id}},[_vm._s(_vm.$t("buttons.details"))])," ",_vm._h('a',{attrs:{"href":'/terraria/' + terrarium.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",_vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(_vm.$tc("components.animals", 2)),_vm._m(3,true)])," ",_vm._l((terrarium.animals),function(animal){return _vm._h('p',[_vm._h('a',{attrs:{"href":'/animals/' + animal.id}},[_vm._s(animal.display_name)])," ",_vm._h('i',[_vm._s(animal.common_name)])])})," ",_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(_vm.$tc("components.action_sequence_schedules", 2))])," ",_vm._l((terrarium.action_sequences),function(as){return _vm._h('p',[_vm._h('a',{attrs:{"href":'/action_sequences/' + as.id}},[_vm._s(as.name)])])})])])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('br')},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["close"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-13", __vue__options__)
  } else {
    hotAPI.reload("data-v-13", __vue__options__)
  }
})()}
},{"./inline-graph.vue":34,"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],45:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            users: [],
            meta: [],
            filter: {
                id: null,
                name: null,
                email: null
            },
            filter_string: '',
            page: 1
        };
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        set_filter: function set_filter() {
            this.filter_string = '';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function set_page(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/users?page=' + this.page + '&' + this.filter_string,
                method: 'GET',
                success: function success(data) {
                    that.meta = data.meta;
                    that.users = data.data;
                    that.$nextTick(function () {
                        $('.dropdown-button').dropdown();
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        this.load_data();
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight"},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{staticClass:"hide-on-med-and-down",attrs:{"data-field":"id"}},["\n                    ID\n                    ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.id),expression:"filter.id"}],attrs:{"id":"filter_id","type":"text"},domProps:{"value":_vm._s(_vm.filter.id)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.id=$event.target.value}}})," ",_vm._m(0)])])," ",_vm._h('th',{attrs:{"data-field":"name"}},["\n                    "+_vm._s(_vm.$t('labels.name'))+"\n                    ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.name),expression:"filter.name"}],attrs:{"id":"filter_name","type":"text"},domProps:{"value":_vm._s(_vm.filter.name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.name=$event.target.value}}})," ",_vm._m(1)])])," ",_vm._h('th',{attrs:{"data-field":"email"}},["\n                    "+_vm._s(_vm.$t('labels.email'))+"\n                    ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.email),expression:"filter.email"}],attrs:{"id":"filter_email","type":"text"},domProps:{"value":_vm._s(_vm.filter.email)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.email=$event.target.value}}})," ",_vm._m(2)])])," ",_vm._h('th',{attrs:{"data-field":"actions"}},["\n                    "+_vm._s(_vm.$t('labels.actions'))+"\n                "])])])," ",_vm._h('tbody',[_vm._l((_vm.users),function(user){return _vm._h('tr',[_vm._h('td',{staticClass:"hide-on-med-and-down"},[_vm._s(user.id)])," ",_vm._h('td',[_vm._s(user.name)])," ",_vm._h('td',[_vm._s(user.email)])," ",_vm._h('td',[_vm._h('a',{staticClass:"dropdown-button btn btn-small",attrs:{"href":"#!","data-activates":'dropdown-edit-user_' + user.id}},["\n                        "+_vm._s(_vm.$t('labels.actions')),_vm._m(3,true)])," ",_vm._h('ul',{staticClass:"dropdown-content",attrs:{"id":'dropdown-edit-user_' + user.id}},[_vm._h('li',[_vm._h('a',{attrs:{"href":'/users/' + user.id + '/edit'}},["\n                                "+_vm._s(_vm.$t('buttons.edit'))+"\n                            "])])," ",_vm._h('li',[_vm._h('a',{attrs:{"href":'/users/' + user.id + '/delete'}},["\n                                "+_vm._s(_vm.$t('buttons.delete'))+"\n                            "])])])])])})])])," ",_vm._h('ul',{staticClass:"pagination"},[_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(1)}}},[_vm._m(4)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._m(5)])])," ",(_vm.meta.pagination.current_page-3 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-3)}}},[_vm._s(_vm.meta.pagination.current_page-3)])]):_vm._e()," ",(_vm.meta.pagination.current_page-2 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-2)}}},[_vm._s(_vm.meta.pagination.current_page-2)])]):_vm._e()," ",(_vm.meta.pagination.current_page-1 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._s(_vm.meta.pagination.current_page-1)])]):_vm._e()," ",_vm._h('li',{staticClass:"active"},[_vm._h('a',{attrs:{"href":"#!"}},[_vm._s(_vm.meta.pagination.current_page)])])," ",(_vm.meta.pagination.current_page+1 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._s(_vm.meta.pagination.current_page+1)])]):_vm._e()," ",(_vm.meta.pagination.current_page+2 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+2)}}},[_vm._s(_vm.meta.pagination.current_page+2)])]):_vm._e()," ",(_vm.meta.pagination.current_page+3 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+3)}}},[_vm._s(_vm.meta.pagination.current_page+3)])]):_vm._e()," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._m(6)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.total_pages)}}},[_vm._m(7)])])])])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_id"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_name"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_email"}},["Filter"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["keyboard_arrow_down"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["first_page"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_left"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["last_page"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-31", __vue__options__)
  } else {
    hotAPI.reload("data-v-31", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],46:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            valves: [],
            meta: [],
            filter: {
                name: '',
                'pump.name': '',
                'controlunit.name': '',
                'terrarium.display_name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        };
    },


    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function update(v) {
            var item = null;
            this.valves.forEach(function (data, index) {
                if (data.id === v.valve.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.valves.splice(item, 1, v.valve);
            }
        },

        delete: function _delete(v) {
            var item = null;
            this.valves.forEach(function (data, index) {
                if (data.id === v.valve.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.valves.splice(item, 1);
            }
        },
        set_order: function set_order(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                } else {
                    this.order.direction = 'asc';
                }
            } else {
                this.order.field = field;
            }

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function set_filter() {
            this.filter_string = '&';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function set_page(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function load_data() {
            window.eventHubVue.processStarted();
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/valves?page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    that.meta = data.meta;
                    that.valves = data.data;
                    that.$nextTick(function () {
                        $('table.collapsible').collapsibletable();
                    });
                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('ValveUpdated', function (e) {
            _this.update(e);
        }).listen('ValveDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('table',{staticClass:"responsive highlight collapsible",attrs:{"data-collapsible":"expandable"}},[_vm._h('thead',[_vm._h('tr',[_vm._h('th',{attrs:{"data-field":"name"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('name')}}},[_vm._s(_vm.$t('labels.name'))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'asc'),expression:"order.field == 'name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'name' && _vm.order.direction == 'desc'),expression:"order.field == 'name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter.name),expression:"filter.name"}],attrs:{"id":"filter_name","type":"text"},domProps:{"value":_vm._s(_vm.filter.name)},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }_vm.filter.name=$event.target.value}}})," ",_vm._m(0)])])," ",_vm._h('th',{attrs:{"data-field":"pump"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('pump')}}},[_vm._s(_vm.$tc('components.pump', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'pump' && _vm.order.direction == 'asc'),expression:"order.field == 'pump' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'pump' && _vm.order.direction == 'desc'),expression:"order.field == 'pump' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['pump.name']),expression:"filter['pump.name']"}],attrs:{"id":"filter_pump","type":"text"},domProps:{"value":_vm._s(_vm.filter['pump.name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'pump.name';if (!Array.isArray($$exp)){_vm.filter['pump.name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(1)])])," ",_vm._h('th',{staticClass:"hide-on-small-only",attrs:{"data-field":"controlunit"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('controlunit')}}},[_vm._s(_vm.$tc('components.controlunit', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'asc'),expression:"order.field == 'controlunit' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'controlunit' && _vm.order.direction == 'desc'),expression:"order.field == 'controlunit' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['controlunit.name']),expression:"filter['controlunit.name']"}],attrs:{"id":"filter_controlunit","type":"text"},domProps:{"value":_vm._s(_vm.filter['controlunit.name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'controlunit.name';if (!Array.isArray($$exp)){_vm.filter['controlunit.name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(2)])])," ",_vm._h('th',{staticClass:"hide-on-med-and-down",attrs:{"data-field":"terrarium"}},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_order('terraria.display_name')}}},[_vm._s(_vm.$tc('components.terraria', 1))])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'valve.terrarium.display_name' && _vm.order.direction == 'asc'),expression:"order.field == 'valve.terrarium.display_name' && order.direction == 'asc'"}],staticClass:"material-icons"},["arrow_drop_up"])," ",_vm._h('i',{directives:[{name:"show",rawName:"v-show",value:(_vm.order.field == 'valve.terrarium.display_name' && _vm.order.direction == 'desc'),expression:"order.field == 'valve.terrarium.display_name' && order.direction == 'desc'"}],staticClass:"material-icons"},["arrow_drop_down"])," ",_vm._h('div',{staticClass:"input-field inline"},[_vm._h('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter['terrarium.display_name']),expression:"filter['terrarium.display_name']"}],attrs:{"id":"filter_terrarium","type":"text"},domProps:{"value":_vm._s(_vm.filter['terrarium.display_name'])},on:{"keyup":function($event){if($event.keyCode!==13){ return; }_vm.set_filter($event)},"input":function($event){if($event.target.composing){ return; }var $$exp = _vm.filter, $$idx = 'terrarium.display_name';if (!Array.isArray($$exp)){_vm.filter['terrarium.display_name']=$event.target.value}else{$$exp.splice($$idx, 1, $event.target.value)}}}})," ",_vm._m(3)])])," ",_vm._m(4)])])," ",_vm._l((_vm.valves),function(valve){return [_vm._h('tbody',[_vm._h('tr',{staticClass:"collapsible-header"},[_vm._h('td',[_vm._h('span',[_vm._m(5,true)," ",_vm._h('a',{attrs:{"href":'/valves/' + valve.id}},[_vm._s(valve.name)])])])," ",_vm._h('td',[(valve.pump)?_vm._h('span',[_vm._m(6,true)," ",_vm._h('a',{attrs:{"href":'/pumps/' + valve.pump.id}},[_vm._s(valve.pump.name)])]):_vm._e()])," ",_vm._h('td',{staticClass:"hide-on-small-only"},[(valve.controlunit)?_vm._h('span',[_vm._m(7,true)," ",_vm._h('a',{attrs:{"href":'/controlunits/' + valve.controlunit.id}},[_vm._s(valve.controlunit.name)])]):_vm._e()])," ",_vm._h('td',{staticClass:"hide-on-med-and-down"},[(valve.terrarium)?_vm._h('span',[_vm._m(8,true)," ",_vm._h('a',{attrs:{"href":'/terraria/' + valve.terrarium.id}},[_vm._s(valve.terrarium.display_name)])]):_vm._e()])," ",_vm._h('td',[_vm._h('span',[_vm._h('a',{staticClass:"waves-effect waves-light btn-small",attrs:{"href":'/valves/' + valve.id + '/edit'}},[_vm._m(9,true)])])])])," ",_vm._m(10,true)])]})])," ",(_vm.meta.hasOwnProperty('pagination'))?_vm._h('ul',{staticClass:"pagination"},[_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(1)}}},[_vm._m(11)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == 1, 'waves-effect': _vm.meta.pagination.current_page != 1 }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._m(12)])])," ",(_vm.meta.pagination.current_page-3 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-3)}}},[_vm._s(_vm.meta.pagination.current_page-3)])]):_vm._e()," ",(_vm.meta.pagination.current_page-2 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-2)}}},[_vm._s(_vm.meta.pagination.current_page-2)])]):_vm._e()," ",(_vm.meta.pagination.current_page-1 > 0)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page-1)}}},[_vm._s(_vm.meta.pagination.current_page-1)])]):_vm._e()," ",_vm._h('li',{staticClass:"active"},[_vm._h('a',{attrs:{"href":"#!"}},[_vm._s(_vm.meta.pagination.current_page)])])," ",(_vm.meta.pagination.current_page+1 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._s(_vm.meta.pagination.current_page+1)])]):_vm._e()," ",(_vm.meta.pagination.current_page+2 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+2)}}},[_vm._s(_vm.meta.pagination.current_page+2)])]):_vm._e()," ",(_vm.meta.pagination.current_page+3 <= _vm.meta.pagination.total_pages)?_vm._h('li',{staticClass:"waves-effect"},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+3)}}},[_vm._s(_vm.meta.pagination.current_page+3)])]):_vm._e()," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.current_page+1)}}},[_vm._m(13)])])," ",_vm._h('li',{class:{ 'disabled': _vm.meta.pagination.current_page == _vm.meta.pagination.total_pages, 'waves-effect': _vm.meta.pagination.current_page != _vm.meta.pagination.total_pages }},[_vm._h('a',{attrs:{"href":"#!"},on:{"click":function($event){_vm.set_page(_vm.meta.pagination.total_pages)}}},[_vm._m(14)])])]):_vm._e()])])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_name"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_pump"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_controlunit"}},["Filter"])},function render () {var _vm=this;return _vm._h('label',{attrs:{"for":"filter_terrarium"}},["Filter"])},function render () {var _vm=this;return _vm._h('th',{staticStyle:{"width":"40px"}})},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["transform"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["rotate_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["developer_board"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["video_label"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["edit"])},function render () {var _vm=this;return _vm._h('tr',{staticClass:"collapsible-body"},[_vm._h('td',{attrs:{"colspan":"3"}})," ",_vm._h('td',{staticClass:"hide-on-small-only"})," ",_vm._h('td',{staticClass:"hide-on-med-and-down"})])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["first_page"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_left"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["chevron_right"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["last_page"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-24", __vue__options__)
  } else {
    hotAPI.reload("data-v-24", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}],47:[function(require,module,exports){
;(function(){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _stringify = require('babel-runtime/core-js/json/stringify');

var _stringify2 = _interopRequireDefault(_stringify);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
    data: function data() {
        return {
            valves: []
        };
    },


    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        valveId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'valves-masonry-grid',
            required: false
        }
    },

    methods: {
        update: function update(cu) {
            var item = null;
            this.valves.forEach(function (data, index) {
                if (data.id === cu.valve.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.valves.push(cu.valve);
            } else if (item !== null) {
                this.valves.splice(item, 1, cu.valve);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        delete: function _delete(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.valves.forEach(function (data, index) {
                if (data.id === cu.valve_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.valves.splice(item, 1);
            }

            this.$nextTick(function () {
                this.refresh_grid();
            });
        },

        refresh_grid: function refresh_grid() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        load_data: function load_data() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/valves/' + that.valveId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function success(data) {
                    if (that.valveId !== '') {
                        that.valves = [data.data];
                    } else {
                        that.valves = data.data;
                    }

                    that.$nextTick(function () {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col'
                        });
                    });

                    window.eventHubVue.processEnded();
                },
                error: function error(_error) {
                    console.log((0, _stringify2.default)(_error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function created() {
        var _this = this;

        window.echo.private('dashboard-updates').listen('ValveUpdated', function (e) {
            _this.update(e);
        }).listen('ValveDeleted', function (e) {
            _this.delete(e);
        });

        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function () {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000);
        }
    }
};
})()
if (module.exports.__esModule) module.exports = module.exports.default
var __vue__options__ = (typeof module.exports === "function"? module.exports.options: module.exports)
if (__vue__options__.functional) {console.error("[vueify] functional components are not supported and should be defined in plain js files using render functions.")}
__vue__options__.render = function render () {var _vm=this;return _vm._h('div',{class:[_vm.containerClasses, 'masonry-grid'],attrs:{"id":_vm.containerId}},[_vm._l((_vm.valves),function(valve){return _vm._h('div',[_vm._h('div',{class:_vm.wrapperClasses},[_vm._h('div',{staticClass:"card"},[_vm._h('div',{staticClass:"card-content teal lighten-1 white-text"},[_vm._m(0,true),"\n                    "+_vm._s(_vm.$tc("components.valves", 2))+"\n                "])," ",_vm._h('div',{staticClass:"card-content"},[_vm._h('span',{staticClass:"card-title activator truncate"},[_vm._h('span',[_vm._s(valve.name)])," ",_vm._m(1,true)])])," ",_vm._h('div',{staticClass:"card-action"},[_vm._h('a',{attrs:{"href":'/valves/' + valve.id}},[_vm._s(_vm.$t("buttons.details"))])," ",_vm._h('a',{attrs:{"href":'/valves/' + valve.id + '/edit'}},[_vm._s(_vm.$t("buttons.edit"))])])," ",_vm._h('div',{staticClass:"card-reveal"},[_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(_vm.$tc("components.pumps", 1)),_vm._m(2,true)])," ",(valve.pump)?_vm._h('p',[_vm._h('a',{attrs:{"href":'/pumps/' + valve.pump.id}},[_vm._s(valve.pump.name)])]):_vm._e()," ",_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(_vm.$tc("components.terraria", 1))])," ",(valve.terrarium)?_vm._h('p',[_vm._h('a',{attrs:{"href":'/terraria/' + valve.terrarium.id}},[_vm._s(valve.terrarium.name)])]):_vm._e()," ",_vm._h('span',{staticClass:"card-title grey-text text-darken-4"},[_vm._s(_vm.$tc("components.controlunits", 1))])," ",(valve.controlunit)?_vm._h('p',[_vm._h('a',{attrs:{"href":'/controlunits/' + valve.controlunit.id}},[_vm._s(valve.controlunit.name)])]):_vm._e()])])])])})])}
__vue__options__.staticRenderFns = [function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons"},["transform"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["more_vert"])},function render () {var _vm=this;return _vm._h('i',{staticClass:"material-icons right"},["close"])}]
if (module.hot) {(function () {  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), true)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-23", __vue__options__)
  } else {
    hotAPI.reload("data-v-23", __vue__options__)
  }
})()}
},{"babel-runtime/core-js/json/stringify":1,"vue":8,"vue-hot-reload-api":5}]},{},[11]);

//# sourceMappingURL=vue.js.map
