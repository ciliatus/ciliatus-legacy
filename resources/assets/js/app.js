$.ajaxPrefilter(function(options) {
    if (!options.beforeSend) {
        options.beforeSend = function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', window.Laravel.csrfToken);
        }
    }
});

window.submit_form = function (e, _callback = undefined)
{

    console.log(e);
    e.preventDefault();

    if ($(e.target).data('prevent-submit-on-enter') === true
        && e.keyCode === 13) {
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
    }
    else {
        data = $(e.target).serialize();
    }

    var content_type = 'application/x-www-form-urlencoded';
    if ($(e.target).data('ignore-enctype')) {
        content_type = false;
    }

    var form_id =  $(e.target).prop('id');
    var form = this;

    return $.ajax({
        url: $(e.target).prop('action'),
        type: $(e.target).data('method'),
        data: data,
        cache: false,
        contentType: content_type,
        processData: false,
        xhr: function() {  // Custom XMLHttpRequest
            var xhr = $.ajaxSettings.xhr();
            if(xhr.upload){ // Check if upload property exists
                xhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
            }
            return xhr;
        },
        success: function(data) {
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
                }
                else {
                    callback(data);
                }
            }

            if (redirect_success !== undefined) {
                if (redirect_success == 'auto') {
                    if (data.meta.redirect.uri !== undefined) {
                        window.setTimeout(function() {
                            window.location.replace(data.meta.redirect.uri);
                        }, 1000);
                    }
                }
                else {
                    window.setTimeout(function() {
                        window.location.replace(redirect_success);
                    }, 1000);
                }
            }
        },
        error: function(data) {
            btns.removeAttr('disabled');
            var msg = 'Unknown';
            if (data.responseJSON !== undefined)
                msg = data.responseJSON.error.message;
            window.notification('Error ' + data.status + '<br />' + data.statusText + ':<br />' + msg, 'orange darken-2 text-white');
        }
    });
};

var progressHandlingFunction = function(e){
    if (e.lengthComputable){
        $('.form-progress-bar').data('valuenow', e.loaded/e.total*100);
        $('.form-progress-bar').css('width', e.loaded/e.total*100 + '%');
    }
};

window.notification = function(text, cssClass, length) {
    length = length || 5000;
    cssClass = cssClass || null;
    Materialize.toast(text, length, cssClass)
};

window.runPage = function() {
    $('.masonry-grid').masonry();

    $('select').material_select();

    $('.dropdown-button').dropdown({
        constrain_width: false, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        belowOrigin: true, // Displays dropdown below the button
        alignment: 'left' // Displays dropdown with edge aligned to the left of button
    });

    $('.button-collapse').sideNav();

    $('.tooltipped').tooltip({delay: 50});

    $('.collapsible').collapsible();

    $.ajaxSetup({
        statusCode: {
            401: function(err){
                if (err.responseJSON.error.indexOf('Unauthenticated') !== -1) {
                    location.reload();
                }
            }
        }
    });

    // SideNav collapse active
    var active_headers = $('.collapsible-body ul li.active').parent().parent().parent();
    active_headers.addClass('active');
    active_headers.children('.collapsible-body').css('display', 'block');

    $('form').submit(window.submit_form);

    $('[data-livedata="true"]').each(function() {
        new LiveData($(this).data('livedatasource'), $(this).data('livedatainterval'), domCallbacks[$(this).data('livedatacallback')], this).run();
    });

    /* Enable tabs to update url with tab hash and
     * force rerender of masonry grids */
    $('ul.tabs').tabs({
        onShow: function(event, ui) {
            location.hash = $(this).attr('href');
            var grid = $('.masonry-grid');
            if (grid && grid.masonry) {
                grid.masonry('layout');
                grid.masonry('reloadItems');
            }
            window.eventHubVue.$emit('ForceRerender');

            /*
             * Avoid scrolling when anchor is in url
             * @TODO: find better solution, it's quite sloppy
             */
            setTimeout(function() {
                window.scrollTo(0, 0);
            }, 1);
        }
    });
};

var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

String.prototype.toUnderscoreCase = function() {
    return this.replace(/\.?([A-Z])/g, function (x,y){return "_" + y.toLowerCase()}).replace(/^_/, "")
};

String.prototype.base64encode = function() {
    return Base64.encode(this);
};

String.prototype.base64decode = function() {
    return Base64.decode(this);
};

Date.prototype.toYmd = function() {
    var month = this.getMonth()+1;
    var date = this.getDate();
    return this.getFullYear() + '-' + (month > 9 ? month : '0' + month) + '-' + (date > 9 ? date : '0' + date);
};