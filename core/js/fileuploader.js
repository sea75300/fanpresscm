/**
 * FanPress CM File Uploader Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.fileuploader = {

    init: function() {
        this.initJqUpload();
        this.initUploadButtons();
    },
    
    initUploadButtons: function() {

        jQuery('#btnAddFile').click(function () {
            jQuery('#fpcm-ui-phpupload-filelist').empty();
            jQuery(this).parent().find('.fpcm-ui-fileinput-select').trigger('click');
            jQuery('.fpcm-ui-fileinput-select').change(function () {

                jQuery('#fpcm-ui-phpupload-filelist').empty();

                var fileList = jQuery(this)[0].files;
                if (fileList === undefined) {
                    return false;
                }

                for (var i=0;i<fileList.length;i++) {
                    fpcm.ui.appendHtml('#fpcm-ui-phpupload-filelist', '<div class="row fpcm-ui-padding-md-tb"><div class="col-12">' + fileList[i].name +'</div></div>')
                }
                return false;
            });

            return false;

        });

        jQuery('#btnUploadFile').click(function () {
            fpcm.ui.showLoader(true);
        });

        jQuery('#btnCancelUpload').click(function () {
            jQuery('#fpcm-ui-phpupload-filelist').empty();
            jQuery('.fpcm-ui-fileinput-select').empty();
        });

    },
    
    initJqUpload: function() {
        
        if (!fpcm.vars.jsvars.jqUploadInit) {
            return false;
        }

        'use strict';

        var uploaderEl = jQuery('#fileupload');

        uploaderEl.fileupload({
            url: fpcm.vars.ajaxActionPath + 'jqupload',
            dropZone: jQuery('#fpcm-filemanager-upload-drop'),
        });

        this._uploadsDone = 0;
        uploaderEl.on('fileuploaddone', function (e, data) {
            fpcm.filemanager._uploadsDone++;
            if (fpcm.filemanager._uploadsDone < data.getNumberOfFiles()) {
                return true;
            }

            fpcm.ui.showLoader(true, fpcm.ui.translate('FILE_LIST_ADDTOINDEX'));
            fpcm.ajax.get('cronasync', {
                data    : {
                    cjId: 'fileindex'
                },
                execDone: 'fpcm.ui.showLoader(false);'
            });
        });

        uploaderEl.addClass('fileupload-processing');

        jQuery.ajax({
            url: uploaderEl.fileupload('option', 'url'),
            dataType: 'json',
            context: uploaderEl[0]
        }).always(function () {
            jQuery(this).removeClass('fileupload-processing');
        }).done(function (result) {
            jQuery(this).fileupload('option', 'done').call(this, jQuery.Event('done'), {result: result});
        });
        
        jQuery(document).bind('dragover', function (e) {
            var dropZone = jQuery('#fpcm-filemanager-upload-drop'), timeout = window.dropZoneTimeout;

            if (!timeout) {
                dropZone.addClass('in');
            } else {
                clearTimeout(timeout);
            }

            var found = false, node = e.target;

            do {
                if (node === dropZone[0]) {
                    found = true;
                    break;
                }
                node = node.parentNode;
            } while (node != null);
            if (found) {
                dropZone.addClass('hover');
            } else {
                dropZone.removeClass('hover');
            }
            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null;
                dropZone.removeClass('in hover');
            }, 100);
        });

    }
};