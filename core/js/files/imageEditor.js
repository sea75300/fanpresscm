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
                fpcm.filemanager.cropper = null;
            },
            dlButtons: [
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
                {
                    text: 'FILE_LIST_EDIT_MOVE',
                    icon: "arrow-left",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.move(-2, 0);
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_MOVE',
                    icon: "arrow-up",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.move(0, -2);
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_MOVE',
                    icon: "arrow-down",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.move(0, 2);
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_MOVE',
                    icon: "arrow-right",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.move(2, 0);
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_FLIP',
                    icon: "right-left",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.flipX();
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_FLIP',
                    icon: "right-left fa-rotate-90",
                    showLabel: false,
                    click: function() {
                        fpcm.filemanager.cropper.flipY();
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_RESIZE',
                    icon: "expand-arrows-alt",
                    showLabel: false,
                    click: function() {

                        let _sizes = fpcm.filemanager.cropper.getSelectionSize();

                        if (!_sizes.width || !_sizes.height) {
                            return false;
                        }

                        var _inputWidth = new fpcm.ui.forms.input();
                        _inputWidth.name = 'files-editor-width';
                        _inputWidth.label = fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH');
                        _inputWidth.value = _sizes.width;
                        _inputWidth.type = 'number';

                        var _inputHeight = new fpcm.ui.forms.input();
                        _inputHeight.name = 'files-editor-height';
                        _inputHeight.label = fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT');
                        _inputHeight.value = _sizes.height;
                        _inputHeight.type = 'number';
                        
                        
                        var _sizeWrapper = document.createElement('div');
                        _sizeWrapper.classList.add('row', 'row-cols-1', 'row-cols-md-2');

                        var _sizeWrapperCol1 = document.createElement('div');
                        _sizeWrapperCol1.classList.add('col');
                        _inputWidth.assignToDom(_sizeWrapperCol1);

                        var _sizeWrapperCol2 = document.createElement('div');
                        _sizeWrapperCol2.classList.add('col');
                        _inputHeight.assignToDom(_sizeWrapperCol2);
                        
                        _sizeWrapper.appendChild(_sizeWrapperCol1);
                        _sizeWrapper.appendChild(_sizeWrapperCol2);

                        fpcm.ui_dialogs.create({
                            id: 'files-editor-save',
                            title: 'GLOBAL_SAVE',
                            content: _sizeWrapper,
                            closeButton: true,
                            scrollable: false,
                            directAssignToDom: true,
                            dlButtons: [{
                                text: 'GLOBAL_SAVE',
                                icon: "save",
                                clickClose: true,
                                primary: true,
                                click: function() {

                                    let _newWidth = parseInt(document.getElementById(fpcm.ui.prepareId('files-editor-width', true)).value);
                                    let _newHeight = parseInt(document.getElementById(fpcm.ui.prepareId('files-editor-height', true)).value);
                                    fpcm.filemanager.cropper.resize(_newWidth, _newHeight);
                                }
                            }]
                        });
                    }
                },
                {
                    text: 'FILE_LIST_EDIT_DYNAMIC',
                    icon: "lock",
                    showLabel: false,
                    class: "btn-outline-secondary",
                    click: function(_e) {
                        let _res = fpcm.filemanager.cropper.toggleDynamicSelection();

                        let _c1 = 'fa-lock-open';
                        let _c2 = 'fa-lock';
                        
                        if (!_res) {
                            _c1 = 'fa-lock';
                            _c2 = 'fa-lock-open';
                        }

                        this.childNodes.item(0).classList.replace(_c1, _c2);
                    }
                },
                {
                    text: 'GLOBAL_RESET',
                    icon: "rotate" ,
                    showLabel: false,
                    class: "btn-outline-secondary",
                    click: function() {
                        fpcm.filemanager.cropper.reset();
                    }
                },
                {
                    text: 'GLOBAL_SAVE',
                    icon: "save",
                    clickClose: false,
                    primary: true,
                    click: function() {

                        let _sizes = fpcm.filemanager.cropper.getSelectionSize();
                        if (!_sizes.width || !_sizes.height) {
                            return false;
                        }

                        let _fnWoExt = _param.data.filename.replace(/^([0-9]{4}\-{1}[0-9]{2}\/{1})([a-z0-9_\.\-\(\)]+)(\.[a-z0-9]{3,4})$/i, `$2`);

                        var _inputFilename = new fpcm.ui.forms.input();
                        _inputFilename.name = 'files-editor-filename';
                        _inputFilename.label = fpcm.ui.translate('FILE_LIST_FILENAME');
                        _inputFilename.value = _fnWoExt;
                        _inputFilename.placeholder = _fnWoExt + '.png';

                        var _inputWidth = new fpcm.ui.forms.input();
                        _inputWidth.name = 'files-editor-width';
                        _inputWidth.label = fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH');
                        _inputWidth.value = _sizes.width;
                        _inputWidth.type = 'number';

                        var _inputHeight = new fpcm.ui.forms.input();
                        _inputHeight.name = 'files-editor-height';
                        _inputHeight.label = fpcm.ui.translate('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT');
                        _inputHeight.value = _sizes.height;
                        _inputHeight.type = 'number';

                        fpcm.ui_dialogs.create({
                            id: 'files-editor-save',
                            title: 'GLOBAL_SAVE',
                            content: [
                                _inputFilename,
                                _inputWidth,
                                _inputHeight,
                            ],
                            closeButton: true,
                            scrollable: false,
                            dlButtons: [{
                                text: 'GLOBAL_SAVE',
                                icon: "save",
                                clickClose: true,
                                primary: true,
                                click: function() {

                                    let _newWidth = parseInt(document.getElementById(fpcm.ui.prepareId('files-editor-width', true)).value);
                                    let _newHeight = parseInt(document.getElementById(fpcm.ui.prepareId('files-editor-height', true)).value);

                                    if (_newWidth !== _inputWidth.value || _newHeight !== _inputHeight.value) {
                                        fpcm.filemanager.cropper.resize(_newWidth, _newHeight);
                                    }

                                    let _newFilename = document.getElementById(fpcm.ui.prepareId('files-editor-filename', true));
                                    if (!_newFilename.value || !_newFilename.value.match(/^[a-z0-9_\.\-\(\)]+$/i)) {

                                        fpcm.ui.addMessage({
                                            txt: _newFilename.validationMessage,
                                            type: 'error'
                                        });

                                        return;
                                    }

                                    fpcm.filemanager.cropper.save(_newFilename.value, _param.afterUpload);
                                    fpcm.ui_dialogs.close('files-editor-save');
                                }
                            }]
                        });

                    }
                },
            ]
        });

    }

};