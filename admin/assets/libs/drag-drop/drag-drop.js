var app = app || {};
(function (o) {
    "use strict";
    var ajax, getFormData, setProgress;
    ajax = function (data) {
        var xmlhttp = new XMLHttpRequest();
        var uploaded;
        xmlhttp.addEventListener('readystatechange', function () {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    o.options.finished(this.response);
                } else {
                    o.options.error();
                }
            }
        });
        xmlhttp.upload.addEventListener('progress', function (e) {
            var percent;
            if (e.lengthComputable === true) {
                percent = Math.round((event.loaded / event.total) * 100);
                setProgress(percent);
            }
        });
        xmlhttp.open('post', o.options.processor);
        xmlhttp.send(data);
    };
    getFormData = function (source) {
        var data = new FormData();
        var i;
        for (i = 0; i < source.length; i = i + 1) {
            data.append('files[]', source[i]);
        }
        return data
    };
    setProgress = function (value) {
        if (o.options.progressBar !== undefined) {
            o.options.progressBar.style.width = value ? value + '%' : '0';
        }
        if (o.options.progressText !== undefined) {
            o.options.progressText.textContent = value ? value + '%' : '0';
        }
    };
    o.uploader = function (options) {
        o.options = options;
        if (o.options.files !== undefined) {
            ajax(getFormData(o.options.files));
        }
    };
}(app));
$(function () {
    "use strict";
    var dropZone = document.getElementById('drop-zone');
    var bar = document.getElementById('bar');
    var barFill = document.getElementById('bar-fill');
    var barFillText = document.getElementById('bar-fill-text');
    var startUpload = function (files) {
        $('#bar').removeClass('hidden');
        $('#bar-fill').css('width', '0');
        var retype = $('#type-upload-files').val();
        app.uploader({
            files: files,
            progress: $('#bar'),
            progressBar: barFill,
            progressText: barFillText,
            processor: admin_ajax_url + "?action=async_upload&retype=" + retype,
            finished: function (data) {
                if (retype == 'thickbox') {
                    $('#media-items .media-attachments').prepend(data);
                    $('.creative-media-upload-sidemenu a').removeClass('current');
                    $('.creative-media-upload-sidemenu a.library').addClass('current');
                    $('.content-tab').addClass('hidden');
                    $('.content-library').removeClass('hidden');
                } else {
                    $('#media-items .media-attachments').append(data);
                }
                $('#bar').addClass('hidden');
            },
            error: function () {}
        });
    };
    dropZone.ondrop = function (e) {
        e.preventDefault();
        this.className = 'upload-console-drop';
        startUpload(e.dataTransfer.files);
    };
    dropZone.ondragover = function () {
        this.className = 'upload-console-drop drop';
        return false;
    };
    dropZone.ondragleave = function () {
        this.className = 'upload-console-drop';
        return false;
    };
    $("#plupload-browse-button").on('click', function (e) {
        $("#standard-upload-files").click();
    });
    $("#html-upload").on('click', function (e) {
        startUpload('');
        return false;
    });
    $("#standard-upload-files").on('change', function (e) {
        var standardfiles = document.getElementById('standard-upload-files').files;
        e.preventDefault();
        startUpload(standardfiles);
    });
});