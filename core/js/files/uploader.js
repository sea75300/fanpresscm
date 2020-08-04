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

    _uploadsDone: 0,

    init: function() {
        this.initJqUpload();
        this.initUploadButtons();
    },
    
    initUploadButtons: function() {
        
        if (fpcm.vars.jsvars.jqUploadInit) {
            return false;
        }

        fpcm.dom.fromId('btnAddFile').click(function () {
            fpcm.dom.fromId('fpcm-ui-phpupload-filelist').empty();
            fpcm.dom.fromTag(this).parent().find('.fpcm-ui-fileinput-select').trigger('click');
            fpcm.dom.fromClass('fpcm-ui-fileinput-select').change(function () {

                fpcm.dom.fromId('fpcm-ui-phpupload-filelist').empty();
                if (!fpcm.dom.fromTag(this)[0] || !fpcm.dom.fromTag(this)[0].files) {
                    return false;
                }

                var fileList = fpcm.dom.fromTag(this)[0].files;
                for (var i=0;i<fileList.length;i++) {
                    fpcm.ui.appendHtml('#fpcm-ui-phpupload-filelist', '<div class="row no-gutters fpcm-ui-padding-md-tb"><div class="col-12 fpcm-ui-margin-md-top fpcm-ui-margin-md-bottom"><span class="far fa-fw fa-lg fa-file-image"></span>' + fileList[i].name +'</div></div>')
                }
                
                fpcm.dom.fromId('fpcm-ui-fileupload-list').fadeIn();
                return false;
            });

            return false;

        });

        fpcm.dom.fromId('btnUploadFile').click(function () {
            fpcm.ui_loader.show();
        });

        fpcm.dom.fromId('btnCancelUpload').click(function () {
            fpcm.dom.fromId('fpcm-ui-fileupload-list').fadeOut();
            fpcm.dom.fromId('fpcm-ui-phpupload-filelist').empty();
            fpcm.dom.fromClass('fpcm-ui-fileinput-select').empty();
        });

    },
    
    initJqUpload: function() {
        
        if (!fpcm.vars.jsvars.jqUploadInit) {
            return false;
        }

        'use strict';

        var uploaderEl = fpcm.dom.fromId('fileupload');

        uploaderEl.fileupload({
            url: fpcm.vars.ajaxActionPath + 'jqupload',
            dropZone: fpcm.dom.fromId('fpcm-filemanager-upload-drop'),
            uploadTemplateId: null,
            downloadTemplateId: null,
            downloadTemplate: function (_params) {
                return '';
            },
            uploadTemplate: function (_params) {

                if (!_params.files) {
                    return '';
                }

                let rows = '';

                jQuery.each(_params.files, function (index, file) {
                    
                    
                    let html = '<div class="row template-upload fade py-2 my-2 fpcm ui-background-white-50p fpcm-ui-border-radius-all">';
                    html += '   <div class="col-12 col-sm-auto fpcm-ui-center jqupload-row-buttons align-self-center">';
                    
                    if (!index && !_params.options.autoUpload) {
                        html += fpcm.vars.jsvars.uploadLIstButtons.start;
                    }
                    
                    if (!index) {
                        html += fpcm.vars.jsvars.uploadLIstButtons.cancel;
                    }
                    
                    html += '   </div>';
                    html += '   <div class="col-12 col-sm-auto align-self-center fpcm-ui-ellipsis pt-3 pt-sm-0">';
                    html += '       <span class="name">' + file.name + '</span> (<span class="size">' + _params.formatFileSize(file.size) + '</span>)';
                    html += '       <strong class="error"></strong>';
                    html += '   </div>';
                    html += '</div>';
                    
                    rows += html;
                });
                
                return rows;
            }
        });

        fpcm.filemanager._uploadsDone = 0;

        uploaderEl.on('fileuploaddone', function (e, data) {
            fpcm.filemanager._uploadsDone++;
            if (fpcm.filemanager._uploadsDone < data.getNumberOfFiles()) {
                return true;
            }

            fpcm.ajax.get('cronasync', {
                data    : {
                    cjId: 'fileindex'
                },
                loaderMsg: fpcm.ui.translate('FILE_LIST_ADDTOINDEX')
            });
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

    }
};