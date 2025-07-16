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
    
    cropper: null,

    initEditorDialog: function(_param) {

        if (fpcm.vars.jsvars.cropperSizes === undefined) {
            fpcm.vars.jsvars.cropperSizes = {};
        }

        let _img = document.createElement('img');
        _img.setAttribute('id', 'fpcm-id-dialog-files-imgeditor');
        _img.setAttribute('src', _param.data.url);

        fpcm.ui_dialogs.create({
            id: 'files-editor',
            title: 'FILE_LIST_EDIT',
            class: 'modal-fullscreen',
            modalBodyClass: 'overflow-hidden',
            content: _img,
            closeButton: true,
            scrollable: false,
            directAssignToDom: true,
            dlOnOpenAfter: function() {
                fpcm.filemanager.cropper = fpcm.cropper.getInstance('dialog-files-imgeditor');
                return true;
            },
            dlOnClose: function() {
                fpcm.dom.fromTag(this).remove();
                /*fpcm.filemanager.cropper.destroy();
                fpcm.vars.jsvars.cropperSizes = {};*/
            },
            dlButtons: [
                {
                    text: 'FILE_LIST_EDIT_RESIZE',
                    icon: "expand-arrows-alt",
                    showLabel: false,
                    click: function() {
                        /*var _cropBox = fpcm.filemanager.cropper.getCropBoxData();
                        if (!_cropBox.width || !_cropBox.height) {
                            return false;
                        }

                        let inWidth = fpcm.ui.getTextInput({
                            value: Math.round(_cropBox.width),
                            name: 'fpcm-ui-files-editor-width',
                            text: fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH'),
                            placeholder: fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH'),
                        });

                        let inHeight = fpcm.ui.getTextInput({
                            value: Math.round(_cropBox.height),
                            name: 'fpcm-ui-files-editor-height',
                            text: fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT'),
                            placeholder: fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT'),
                        });

                        fpcm.ui_dialogs.create({
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
                                    primary: true,
                                    click: function() {
                                        fpcm.vars.jsvars.cropperSizes = {
                                            width: Math.round(parseInt(fpcm.dom.fromId('fpcm-ui-files-editor-width').val())),
                                            height: Math.round(parseInt(fpcm.dom.fromId('fpcm-ui-files-editor-height').val()))
                                        }
                                    }
                                }
                            ],
                            dlOnOpenAfter: function() {

                                fpcm.dom.bindEvent('#fpcm-ui-files-editor-width', 'change', function (_e, _ui) {
                                    fpcm.imageEditor.calcResized(_ui, 'fpcm-ui-files-editor-height', 0);
                                });

                                fpcm.dom.bindEvent('#fpcm-ui-files-editor-height', 'change', function (_e, _ui) {
                                    fpcm.imageEditor.calcResized(_ui, 'fpcm-ui-files-editor-width', 1);
                                });

                            }
                        });*/
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
                        fpcm.filemanager.cropper.rotate('-2deg');
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_ROTATE_CLOCKWISE',
                    icon: "redo",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.rotate('2deg');
                    }
                },
                /*{
                    text: 'FILE_LIST_EDIT_MOVE',
                    icon: "arrows-alt",
                    showLabel: false,
                    click: function() {
                        ///fpcm.filemanager.cropper.setDragMode("move");
                    }
                },*/
                {
                    text: 'FILE_LIST_EDIT_CROP',
                    icon: "crop-alt",
                    showLabel: false,
                    click: function() {
                       // fpcm.filemanager.cropper.setDragMode("crop");
                    }
                },
                {
                    text: 'GLOBAL_RESET',
                    icon: "undo" ,
                    showLabel: false,
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
                        
                        fpcm.filemanager.cropper.save(_param);
                        
                       /* fpcm.filemanager.cropper.getCroppedCanvas(fpcm.vars.jsvars.cropperSizes).toBlob((_blob) => {

                            const formData = new FormData();
                            formData.append('file', _blob, _param.data.filename);

                            fpcm.ajax.post('editor/imgupload', {
                                data: formData,
                                processData: false,
                                contentType: false,
                                execDone: function (result) {
                                    _param.afterUpload(result);
                                    fpcm.vars.jsvars.cropperSizes = {};
                                }
                            });

                        }, _param.data.mime);*/
                    }
                },
            ]
        });

    },

    calcResized: function(_ui, _assignTo, _mode) {

        /*var _cropBox = fpcm.filemanager.cropper.getCropBoxData();
        if (!_cropBox.width || !_cropBox.height) {
            return false;
        }

        let _factor = _mode ? _cropBox.width / _cropBox.height : _cropBox.height / _cropBox.width;
        fpcm.dom.fromId(_assignTo).val(Math.round(_ui.value * _factor));*/
    }

};