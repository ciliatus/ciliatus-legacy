window.submit_form = function (e)
{
    e.preventDefault();
    var btns = $('button[type=submit]:enabled');
    btns.attr('disabled', 'disabled');
    var callback = $(e.target).data('callback');
    var callback_param = $(e.target).data('callback-param');
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
            notification('Saved', 'teal darken-1 text-white');
            if ($(e.target).data('redirect-target') != undefined) {
                window.setTimeout(function() {
                    window.location.replace($(e.target).data('redirect-target'))
                }, $(e.target).data('redirect-delay') || 2000);
            }

            if (callback !== undefined) {
                domCallbacks[callback](true, callback_param);
            }
        },
        error: function(data) {
            btns.removeAttr('disabled');
            var msg = 'Unknown';
            if (data.responseJSON !== undefined)
                msg = data.responseJSON.error.message;
            notification('Error ' + data.status + '<br />' + data.statusText + ':<br />' + msg, 'orange darken-2 text-white');

            if (callback !== undefined) {
                domCallbacks[callback](false, callback_param);
            }
        }
    });
};

var progressHandlingFunction = function(e){
    if (e.lengthComputable){
        $('.form-progress-bar').data('valuenow', e.loaded/e.total*100);
        $('.form-progress-bar').css('width', e.loaded/e.total*100 + '%');
    }
};

function notification(text, cssClass, length) {
    length = length || 5000;
    cssClass = cssClass || null;
    Materialize.toast(text, length, cssClass)
}