(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
(function (global){
'use strict';

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
    e.preventDefault();
    var btns = $('button[type=submit]:enabled');
    btns.attr('disabled', 'disabled');
    var callback = $(e.target).data('callback');
    var callback_param = $(e.target).data('callback-param');
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
            notification('Saved', 'teal darken-1 text-white');

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
            notification('Error ' + data.status + '<br />' + data.statusText + ':<br />' + msg, 'orange darken-2 text-white');
        }
    });
};

var progressHandlingFunction = function progressHandlingFunction(e) {
    if (e.lengthComputable) {
        $('.form-progress-bar').data('valuenow', e.loaded / e.total * 100);
        $('.form-progress-bar').css('width', e.loaded / e.total * 100 + '%');
    }
};

function notification(text, cssClass, length) {
    length = length || 5000;
    cssClass = cssClass || null;
    Materialize.toast(text, length, cssClass);
}

global.runPage = function () {
    $('select').material_select();

    $(".button-collapse").sideNav();

    var active_headers = $('.collapsible-body ul li.active').parent().parent().parent();
    active_headers.addClass('active');
    active_headers.children('.collapsible-body').css('display', 'block');

    $('form').submit(window.submit_form);

    $('[data-livedata="true"]').each(function () {
        new LiveData($(this).data('livedatasource'), $(this).data('livedatainterval'), domCallbacks[$(this).data('livedatacallback')], this).run();
    });
};

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}]},{},[1]);

//# sourceMappingURL=app.js.map
