(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

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

},{}]},{},[1]);

//# sourceMappingURL=app.js.map
