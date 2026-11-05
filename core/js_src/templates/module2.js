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

//        if (fpcm.dataview.exists('draftfiles')) {
//            fpcm.dataview.render('draftfiles');
//        }

        fpcm.ui_tabs.render('#fpcm-tabs-templates', {

            onRenderHtmlBefore: (_ui, _result) => {

                let _cards = document.createElement('div');
                _cards.classList.add('mx-2', 'py-1');

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

                        let _row = document.createElement('a');
                        _row.classList.add('list-group-item', 'list-group-item-action', 'py-3', 'd-flex', 'flex-column', 'flex-lg-row', 'align-items-center');
                        _row.href = fpcm.vars.actionPath + 'templates/editor&hash=' + _files[_file].hash + '&parent=' + _files[_file].parentFolder;
                        _row.addEventListener('click', fpcm.templates._openEditor);
                        _row.appendChild((new fpcm.ui.forms.icon('file', 'lg')).getAsDom());
                        _row.dataset.name = _files[_file].filename;
                        _row.dataset.hash = _files[_file].hash;
                        _row.dataset.parent = _files[_file].parentFolder;                        

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
                            _msg.classList.add('badge', 'text-bg-danger', 'm-2', 'me-md-5', 'text-truncate');
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
                        _lastChange.classList.add('badge', 'text-bg-secondary', 'm-2', 'm-lg-0');
                        _lastChange.innerHTML = _files[_file].changedTime;
                        _lastChange.title = fpcm.ui.translate('GLOBAL_LASTCHANGE');
                        _row.appendChild(_lastChange);

                        _list.appendChild(_row);

                    }

                    _card.appendChild(_list);
                    _cards.appendChild(_card);
                }

                _result.dom = _cards;
            }
        });


        /*fpcm.dom.bindClick('.fpcm-articletemplates-edit', function(_ev, _ui) {
            fpcm.ui_loader.hide();
            fpcm.ui_dialogs.create({
                id: 'draft-editor',
                title: 'TEMPLATE_HL_DRAFTS_EDIT',
                url: _ui.attributes.href.value,
                icon: {
                    icon: 'file-code'
                },
                closeButton: true,
                dlButtons : [
                    {
                        text: 'GLOBAL_SAVE',
                        icon: "save",
                        primary: true,
                        click: function(_ui) {
                            fpcm.dom.appendAndClickButtonInDialogFrame(_ui, 'btnSaveTemplate');
                            fpcm.ui_loader.hide();
                        }
                    }
                ]
            });

            return false;

        });

        fpcm.dom.bindClick('#btnSaveTemplates', function () {
            fpcm.ui_dialogs.confirm({
                clickYes: function () {
                    fpcm.ajax.post('templates/save', {
                        data: {
                            content: fpcm.editor_ace.getValue(),
                            tplid  : fpcm.templates._tplId
                        },
                        execDone: function (_result) {

                            if (_result instanceof Object && _result.txt) {
                                fpcm.ui.addMessage(_result);
                                return true;
                            }

                        }
                    });
                }
            });
        });*/

    },

    _openEditor: function(_ev) {

        _ev.preventDefault();

        fpcm.ui_dialogs.create({
            id: 'template-editor',
            title: fpcm.ui.translate('TEMPLATE_HL_DRAFTS_EDIT') + ' - ' + _ev.currentTarget.dataset.name,
            url: _ev.currentTarget.href,
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
                fpcm.ui_dialogs.close('template-editor');
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
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {
        fpcm.ui.relocate('?module=templates/templates&rg=6');
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