/**
 * FanPress CM File Uploader Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2017-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.fileuploader = {

    _uploadsDone: 0,

    init: function() {

        'use strict';

        var uploaderEl = fpcm.dom.fromId('fileupload');

        uploaderEl.fileupload({
            url: fpcm.vars.ajaxActionPath + 'jqupload&dest=' + fpcm.vars.jsvars.uploadDest,
            dropZone: fpcm.dom.fromId('fpcm-filemanager-upload-drop'),
            uploadTemplateId: null,
            downloadTemplateId: null,
            acceptFileTypes: fpcm.filemanager.getAcceptTypes ? fpcm.filemanager.getAcceptTypes() : /(\.|\/)(gif|jpe?g|png)$/i,
            downloadTemplate: function (_params) {

                let file = _params.files[0];
                if (!file.error) {
                    return '';
                }

                return fpcm.fileuploader.createFileListItem(_params, file, false, true);
            },
            uploadTemplate: function (_params) {

                if (!_params.files) {
                    return '';
                }

                let rows = '';
                jQuery.each(_params.files, function (index, file) {
                    rows += fpcm.fileuploader.createFileListItem(_params, file, !index && !_params.options.autoUpload, !index);
                });
                
                return rows;
            }
        });

        fpcm.fileuploader._uploadsDone = 0;

        uploaderEl.on('fileuploaddone', function (e, data) {
            fpcm.fileuploader._uploadsDone++;
            if (fpcm.fileuploader._uploadsDone < data.getNumberOfFiles()) {
                return true;
            }

            if (!fpcm.filemanager || fpcm.filemanager.runFileIndexUpdate === undefined) {
                return true;
            }

            fpcm.filemanager.runFileIndexUpdate(data);
            return true;
        });

        jQuery(document).bind('dragover', function (e) {
            var dropZone = fpcm.dom.fromId('fpcm-filemanager-upload-drop'), timeout = window.dropZoneTimeout;

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

    },
    
    createFileListItem: function (_params, _file, _addUploadButton, _addCancelButton) {
        
        let html = '<div class="row template-upload fade py-2 my-2 fpcm ui-background-white-50p fpcm-ui-border-radius-all">';
        html += '   <div class="col-12 col-sm-auto fpcm-ui-center jqupload-row-buttons align-self-center">';

        if (_addUploadButton) {
            html += fpcm.vars.jsvars.uploadListButtons.start.replace('{{id}}', (new Date).getTime());
        }

        if (_addCancelButton) {
            html += fpcm.vars.jsvars.uploadListButtons.cancel.replace('{{id}}', (new Date).getTime());
        }

        html += '   </div>';
        html += '   <div class="col-12 col-md align-self-center fpcm-ui-ellipsis pt-3 pt-sm-0">';
        html += '       <span class="name">' + _file.name + '</span> (<span class="size">' + _params.formatFileSize(_file.size) + '</span>)';
        html += '   </div>';
        html += '   <div class="col-12 col-md align-self-center fpcm-ui-ellipsis pt-3 pt-sm-0">';
        html += '       <span class="error fpcm fpcm-ui-important-text"> ' + (_file.error ? fpcm.ui.translate('SAVE_FAILED_UPLOAD_GEN').replace('{{uploadMsg}}', _file.error) : '') + '</span>';
        html += '   </div>';
        html += '</div>';

        return html;
    }
    
};