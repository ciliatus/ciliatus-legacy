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

global.LiveData = function(source_uri, interval, callback, target)
{
    liveDataObjects += this;
    this.source_uri = source_uri;
    this.interval = interval * 1000;
    this.callback = callback;
    this.target = target;
    this.runner = null;
    this.refs = new Array();
    return this;
}

LiveData.prototype.run = function()
{
    var ld = this;
    ld.fetchData(ld);
    this.runner = setInterval(function() {
        ld.fetchData(ld);
    }, this.interval);
};

LiveData.prototype.fetchData = function(ld)
{
    $.ajax({
        url: ld.source_uri,
        type: 'GET',
        error: function() {
            ld.callback(false, 'error', ld);
        },
        success: function(data) {
            ld.callback(true, data, ld);
        }
    });
};

LiveData.prototype.cleanupRefs = function ()
{
    $.each(this.refs, function() { this.remove() });

    this.refs = new Array();
};

LiveData.prototype.stop = function()
{
    clearInterval(this.runner);
};




window.submit_form = function (e)
{
    e.preventDefault();
    var btns = $('button[type=submit]:enabled');
    btns.attr('disabled', 'disabled');
    var callback = $(e.target).data('callback');
    var callback_param = $(e.target).data('callback-param');
    var redirect_success = $(e.target).data('redirect-success')
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
            window.notification('<i class="material-icons">check</i>', 'teal darken-1 text-white');

            if (callback !== undefined) {
                window[callback](data);
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
    $('select').material_select();

    $('.dropdown-button').dropdown();

    $('.button-collapse').sideNav();

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
            if (grid !== undefined) {
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

String.prototype.toUnderscoreCase = function() {
    return this.replace(/\.?([A-Z])/g, function (x,y){return "_" + y.toLowerCase()}).replace(/^_/, "")
};

Date.prototype.toYmd = function() {
    var month = this.getMonth()+1;
    var date = this.getDate();
    return this.getFullYear() + '-' + (month > 9 ? month : '0' + month) + '-' + (date > 9 ? date : '0' + date);
};