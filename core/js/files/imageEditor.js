/**
 * FanPress CM Image editor Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.imageEditor = {

    initEditorDialog: function(_param) {

        fpcm.ui.dialog({
            id: 'files-editor',
            title: 'FILE_LIST_EDIT',
            class: 'modal-fullscreen',
            content: '<div class="m-2"><img id="fpcm-dialog-files-imgeditor" class="d-block fpcm ui-full-view-max-width-100p" src="' + _param.data.url + '"></div>',
            closeButton: true,
            dlOnOpen: function() {

                let imgEL = fpcm.dom.fromId('fpcm-dialog-files-imgeditor');
                imgEL.attr('src', fpcm.dom.fromTag(this).attr('href'));
                fpcm.filemanager.cropper = new Cropper(imgEL[0], {
                    autoCrop: false
                });

                return true;
            },
            dlOnClose: function() {
                fpcm.dom.fromTag(this).remove();
                fpcm.filemanager.cropper.destroy();
            },
            dlButtons: [
                {
                    text: 'FILE_LIST_EDIT_RESIZE',
                    icon: "expand-arrows-alt",
                    showLabel: false,
                    click: function() {
                        var _cropBox = fpcm.filemanager.cropper.getCropBoxData();
                        if (!_cropBox.width || !_cropBox.height) {
                            return false;
                        }

                        let inWidth = fpcm.ui.getTextInput({
                            value: _cropBox.width,
                            name: 'fpcm-ui-files-editor-width',
                            text: fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH'),
                            placeholder: fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH'),
                        });

                        let inHeight = fpcm.ui.getTextInput({
                            value: _cropBox.height,
                            name: 'fpcm-ui-files-editor-height',
                            text: fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT'),
                            placeholder: fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT'),
                        });

                        fpcm.ui.dialog({
                            id: 'files-editor_prop',
                            title: fpcm.ui.translate('FILE_LIST_EDIT_RESIZE'),
                            closeButton: true,
                            content: '<div class="row g-0 mb-2">' + fpcm.ui.translate('FILE_LIST_EDIT_RESIZE_NOTICE') + '</div>' +
                                     '<div class="row mb-2">' + inWidth + '</div>' +
                                     '<div class="row">' + inHeight + '</div>',
                            dlButtons: [
                                {
                                    text: 'GLOBAL_SAVE',
                                    icon: "save",                        
                                    clickClose: true,
                                    click: function() {
                                        fpcm.vars.jsvars.cropperSizes = {
                                            maxWidth: parseInt(fpcm.dom.fromId('fpcm-ui-files-editor-width').val()),
                                            maxHeight: parseInt(fpcm.dom.fromId('fpcm-ui-files-editor-height').val())
                                        }
                                    }
                                }                  
                            ]
                        });
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_ZOOMOUT',
                    icon: "search-minus",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.zoom(-0.2);
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_ZOOMIN',
                    icon: "search-plus",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.zoom(0.2);
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_ROTATE_ANTICLOCKWISE',
                    icon: "undo",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.rotate(-10);
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_ROTATE_CLOCKWISE',
                    icon: "redo",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.rotate(10);
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_MOVE',
                    icon: "arrows-alt",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.setDragMode("move");
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_CROP',
                    icon: "crop-alt",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.setDragMode("crop");
                    }
                },
                {                   
                    text: 'GLOBAL_RESET',
                    icon: "undo" ,                        
                    click: function() {
                        fpcm.filemanager.cropper.reset();
                    }
                },
                {
                    text: 'GLOBAL_SAVE',
                    icon: "save",                        
                    clickClose: true,
                    primary: true,
                    click: function() {
                        fpcm.filemanager.cropper.getCroppedCanvas(fpcm.vars.jsvars.cropperSizes !== undefined ? fpcm.vars.jsvars.cropperSizes : {}).toBlob((blob) => {

                            const formData = new FormData();
                            formData.append('file', blob, _param.data.filename);

                            fpcm.ajax.post('editor/imgupload', {
                                data: formData,
                                processData: false,
                                contentType: false,
                                execDone: function (result) {
                                    _param.afterUpload(result);
                                }
                            });

                        }, _param.data.mime);
                    }
                }
            ]
        });            

    },

};