/**
 * FanPress CM Templates Namespace
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.templates = {

    _tplId: '',
    _cssFilePath: '',

    init: function() {

        fpcm.ui_tabs.render('#fpcm-tabs-templates', {

            onRenderHtmlBefore: (_ui, _result) => {

                let _cards = document.createElement('div');
                _cards.classList.add('mx-2', 'py-1');

                if (_result instanceof Array && !_result.length) {
                    let _card = document.createElement('div');
                    _card.classList.add('card', 'my-2');

                    let _alert = document.createElement('span');
                    _alert.classList.add('alert', 'alert-info', 'm-3');
                    _alert.innerHTML = fpcm.ui.translate('GLOBAL_NOTFOUND2');
                    _card.appendChild(_alert);
                    _cards.appendChild(_card);
                    _result.dom = _cards;

                    return true;
                }

                for (var _folder in _result) {

                    let _card = document.createElement('div');
                    _card.classList.add('card', 'my-2');

                    let _headline = document.createElement('div');
                    _headline.classList.add('card-header');
                    _headline.innerHTML = fpcm.ui.translate('TEMPLATES_FOLDER_' + _folder.toUpperCase());
                    _card.appendChild(_headline);

                    let _list = document.createElement('div');
                    _list.classList.add('list-group', 'list-group-flush');

                    let _files = _result[_folder];
                    for (var _file in _files) {

                        let _row = document.createElement('div');
                        _row.classList.add('list-group-item', 'py-3', 'd-flex', 'flex-column', 'flex-lg-row', 'align-items-center');

                        fpcm.templates._addButtons(_row, _files[_file], _folder === 'drafts');

                        let _descr = document.createElement('span');
                        _descr.classList.add('fpcm', 'ui-label', 'ps-2', 'flex-grow-1', 'mt-3', 'mt-lg-0');
                        _descr.innerHTML = _files[_file].filename;
                        _row.appendChild(_descr);

                        let _msgText = '';
                        if (!_files[_file].readable) {
                            _msgText = 'TEMPLATE_NOT_READABLE';
                        }
                        else if (!_files[_file].writable) {
                            _msgText = 'TEMPLATE_NOT_WRITABLE';
                        }

                        if (!_files[_file].readable || !_files[_file].writable) {

                            _row.classList.add('list-group-item-danger');
                            _row.dataset.editable = 0;

                            let _msg = document.createElement('span');
                            _msg.classList.add('badge', 'text-bg-danger', 'm-2', 'me-lg-5', 'text-truncate');
                            _msg.appendChild((new fpcm.ui.forms.icon('triangle-exclamation', 'lg')).getAsDom());

                            let _msgLabel = document.createElement('span');
                            _msgLabel.classList.add('fpcm', 'ui-label', 'ps-2');
                            _msgLabel.innerHTML = fpcm.ui.translate(_msgText);
                            _msg.appendChild(_msgLabel);
                            _row.appendChild(_msg);
                        }
                        else {
                            _row.dataset.editable = 1;
                        }

                        let _lastChange = document.createElement('span');

                        let _bg = 'text-bg-secondary';;
                        if (_files[_file].editRecent) {
                            _bg = 'text-bg-warning';
                        }

                        if (_files[_file].readable) {
                            _lastChange.classList.add('badge', _bg, 'm-2');
                            _lastChange.innerHTML = _files[_file].changedTime;
                            _lastChange.title = fpcm.ui.translate('GLOBAL_LASTCHANGE');
                            _row.appendChild(_lastChange);

                            let _filesize = document.createElement('span');
                            _filesize.classList.add('badge', 'text-bg-secondary', 'm-2');
                            _filesize.innerHTML = _files[_file].fileSize;
                            _filesize.title = fpcm.ui.translate('FILE_LIST_FILESIZE');
                            _row.appendChild(_filesize);
                        }

                        _list.appendChild(_row);

                    }

                    _card.appendChild(_list);
                    _cards.appendChild(_card);
                }

                _result.dom = _cards;
            }
        });
    },

    _openEditor: function(_ev) {

        _ev.preventDefault();

        fpcm.ui_dialogs.create({
            id: 'template-editor',
            title: fpcm.ui.translate('TEMPLATE_HL_DRAFTS_EDIT') + ' - ' + _ev.currentTarget.dataset.name,
            url: _ev.currentTarget.dataset.url,
            icon: {
                icon: 'file-code'
            },
            closeButton: true,
            dlButtons : [
                {
                    text: 'GLOBAL_SAVE',
                    icon: "save",
                    primary: true,
                    disabled: _ev.currentTarget.dataset.editable == '0',
                    click: function(_ui, _btn) {
                        fpcm.templates._saveTemplate(
                            _btn.dataset.hash,
                            _btn.dataset.parent,
                            _btn.parentElement.parentElement.querySelector('iframe').contentWindow.fpcm.editor_ace.getValue()
                        );
                    },
                    data: {
                        hash: _ev.currentTarget.dataset.hash,
                        parent: _ev.currentTarget.dataset.parent
                    }
                },
                {
                    text: 'GLOBAL_PREVIEW',
                    icon: "eye",
                    click: fpcm.templates._showPreview
                }
            ]
        });

    },

    _saveTemplate: function(_hash, _parent, _value) {

        fpcm.ajax.post('templates/save2', {
            data: {
                hash: _hash,
                parent: _parent,
                content: _value
            },
            execDone: function (_result) {
                fpcm.ui_tabs.show('#fpcm-tabs-templates', fpcm.ui_tabs.getActiveTab());
                fpcm.ui_dialogs.close('template-editor');
            }
        });
    },

    _deleteDraft: function(_ev) {

        let _hash = _ev.currentTarget.dataset.hash;

        fpcm.ui_dialogs.confirm({
            clickYes: function () {
                fpcm.ajax.post('templates/delete', {
                    data: {
                        hash: _hash,
                    },
                    execDone: function () {
                        fpcm.ui_tabs.show('#fpcm-tabs-templates', 1);
                    }
                });
            }
        });
    },


    _showPreview: function(_ev) {



        /*fpcm.ajax.post('templates/savetemp', {
            quiet: true,
            data: {
                content: fpcm.editor_ace.getValue(),
                tplid  : fpcm.templates._tplId
            },
            execDone: function() {
                fpcm.ui_dialogs.create({
                    id: 'templatepreview-layer',
                    closeButton: true,
                    url: fpcm.vars.actionPath + 'templates/preview&tid=' + fpcm.templates._tplId,
                    dlOnOpenAfter: (_ui) => {

                        let _head = _ui.children.item(0).querySelector('iframe').contentDocument.head;

                        let _link = document.createElement('link');
                        _link.type = 'text/css';
                        _link.rel = 'stylesheet';
                        _link.href = document.getElementById('css-file-' + fpcm.templates._tplId).value;

                        fpcm.templates._saveCss();

                        _head.appendChild(_link);
                    }
                });
            }
        });*/

    },

    _saveCss: function() {

        let _cff = document.getElementById('css-file-');
        if (!_cff) {
            return false;
        }

        fpcm.templates._cssFilePath = _cff.value;

    },

    _restoreCss: function () {

        if (!fpcm.templates._cssFilePath) {
            return;
        }

        document.getElementById('css-file').value = fpcm.templates._cssFilePath;
    },

    _addButtons: function (_row, _file, _showDelete) {

        if (!_file.writable) {
            return false;
        }

        let _open = document.createElement('a');
        _open.classList.add('btn', 'btn-secondary', 'mx-0', 'me-lg-2', 'shadow-sm');
        _open.dataset.url = fpcm.vars.actionPath + 'templates/editor&hash=' + _file.hash + '&parent=' + _file.parentFolder;
        _open.dataset.name = _file.filename;
        _open.dataset.hash = _file.hash;
        _open.dataset.parent = _file.parentFolder;
        _open.addEventListener('click', fpcm.templates._openEditor);
        _open.title = fpcm.ui.translate('GLOBAL_EDIT');

        let _iconPen = new fpcm.ui.forms.icon('pen');
        _open.appendChild(_iconPen.getAsDom());
        _row.appendChild(_open);

        if (!_showDelete) {
            return;
        }

        let _delete = document.createElement('a');
        _delete.classList.add('btn', 'btn-outline-secondary', 'my-2', 'mx-0', 'my-lg-0', 'mx-lg-2', 'shadow-sm');
        _delete.dataset.hash = _file.hash;
        _delete.addEventListener('click', fpcm.templates._deleteDraft);
        _delete.title = fpcm.ui.translate('GLOBAL_EDIT');

        let _iconnTrash = new fpcm.ui.forms.icon('trash');
        _delete.appendChild(_iconnTrash.getAsDom());
        _row.appendChild(_delete);
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {
        fpcm.ui_tabs.show('#fpcm-tabs-templates', 1);
    },

    getAcceptTypes: function ()
    {
        return /(\.|\/)(htm|html|txt)$/i;
    },

    getAcceptTypesArr: function ()
    {
        return ['html', 'htm', 'txt', 'application/xhtml+xml', 'text/html', 'text/plain'];
    }

};

fpcm.editor = { }