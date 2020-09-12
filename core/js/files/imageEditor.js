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

        let sizes = fpcm.ui.getDialogSizes(top, 0.80);

        fpcm.ui.dialog({
            id: 'files-editor',
            title: fpcm.ui.translate('FILE_LIST_EDIT'),
            content: '<div class="m-2"><img id="fpcm-dialog-files-imgeditor" class="d-block fpcm ui-full-view-max-width-100p" src="' + _param.data.url + '"></div>',
            dlPosition: {
                my: "center center-25%",
                at: "center center-25%"
            },
            dlOnOpen: function() {

                let imgEL = fpcm.dom.fromId('fpcm-dialog-files-imgeditor');
                imgEL.attr('src', fpcm.dom.fromTag(this).attr('href'));
                fpcm.filemanager.cropper = new Cropper(imgEL[0], {
                });

                return true;
            },
            dlOnClose: function() {
                fpcm.dom.fromTag(this).remove();
                fpcm.filemanager.cropper.destroy();
            },
            dlButtons: [
                {
                    text: fpcm.ui.translate('FILE_LIST_EDIT_ZOOMOUT'),
                    icon: "ui-icon-zoomout",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.zoom(-0.2);
                    }
                },
                {
                    text: fpcm.ui.translate('FILE_LIST_EDIT_ZOOMIN'),
                    icon: "ui-icon-zoomin",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.zoom(0.2);
                    }
                },
                {
                    text: fpcm.ui.translate('FILE_LIST_EDIT_ROTATE_ANTICLOCKWISE'),
                    icon: "ui-icon-arrowthick-1-sw",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.rotate(-10);
                    }
                },
                {
                    text: fpcm.ui.translate('FILE_LIST_EDIT_ROTATE_CLOCKWISE'),
                    icon: "ui-icon-arrowthick-1-se",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.rotate(10);
                    }
                },
                {
                    text: fpcm.ui.translate('FILE_LIST_EDIT_MOVE'),
                    icon: "ui-icon-arrow-4-diag",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.setDragMode("move");
                    }
                },
                {
                    text: fpcm.ui.translate('FILE_LIST_EDIT_CROP'),
                    icon: "ui-icon-scissors",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.setDragMode("crop");
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_SAVE'),
                    icon: "ui-icon-check",                        
                    click: function() {
                        fpcm.dom.fromTag(this).dialog( "close" );
                        fpcm.filemanager.cropper.getCroppedCanvas().toBlob((blob) => {

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
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                
                    click: function () {
                        fpcm.dom.fromTag(this).dialog('close');
                    }
                }
            ]
        });            

    },

};