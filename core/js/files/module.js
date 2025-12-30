/**
 * FanPress CM Filemanager Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.filemanager = {

    viewType: 'images',

    init: function() {

        fpcm.ui_tabs.render('#files', {
            reload: true,
            onRenderHtmlAfter: function (_event, _result) {

                if (_result.data && _result.data.pager) {
                    fpcm.vars.jsvars.pager = _result.data.pager;
                }

                fpcm.filemanager.viewType = _event.target.dataset.viewtype;
                fpcm.filemanager.initListActions();

                document.getElementById('fpcm-select-all').checked = false;
            }
        });

        fpcm.filemanager.initNewThumbButton();
        fpcm.filemanager.initDeleteMultipleButton();

    },

    initAfter: function() {

        if (fpcm.vars.jsvars.fmgrMode !== 1) {
            return;
        }

        fpcm.dom.bindClick('#btnSettings', function () {

            fpcm.ui_dialogs.settings('files', 'filesSettings', function (ev, _ui, _result) {
                fpcm.filemanager.reloadFiles();
            });

        });

    },

    initListActions: function () {
        fpcm.ui.assignCheckboxes();
        fpcm.filemanager.initPagination();
        fpcm.filemanager.initInsertButtons();
        fpcm.filemanager.initRenameButtons();
        fpcm.filemanager.initEditButtons();
        fpcm.filemanager.initDeleteButtons();
        fpcm.filemanager.initAltTextButtons();
        fpcm.filemanager.initPropertiesButton();
        fpcm.filemanager.initCopyButton();
        fpcm.filemanager.initReminderButton();

        if (fpcm.filemanager.listActions) {
            fpcm.filemanager.listActions.init();
        }
    },

    initInsertButtons: function () {

        fpcm.dom.bindClick('a.btn[data-insert-type]', function (_e, _ui) {

            debugger;

            let _search = new URLSearchParams(window.location.search);
            let _isMedia = _search.get('m') === 'media';

            if (!_isMedia && _ui.dataset.insertType === 'video' ||
                _isMedia && _ui.dataset.insertType === 'image') {
                return;
            }

            if (_isMedia && _ui.dataset.insertType === 'video') {
                parent.fpcm.editor.insertFullByEditor(_ui.href, _ui.href);
                fpcm.ui_dialogs.close('editor-html-filemanager', true);
                return;
            }

            switch (_ui.dataset.insertFn) {
                case 'thumb' :
                    parent.fpcm.editor.insertThumbByEditor(_ui.href, _ui.dataset.imgtext);
                    break;
                case 'full' :
                    parent.fpcm.editor.insertFullByEditor(_ui.href, _ui.dataset.imgtext);
                    break;
                case 'articleimg' :
                    parent.document.getElementById('articleimagepath').value  = _ui.href;
                    break;
                case 'poster-thumb' :
                    parent.document.getElementById(fpcm.ui.prepareId('mediaposter', true)).value  = _ui.href;
                    break;
                case 'poster-full' :
                    parent.document.getElementById(fpcm.ui.prepareId('mediaposter', true)).value  = _ui.href;
                    break;
            }

            fpcm.ui_dialogs.close('editor-html-filemanager', true);
        });

        fpcm.dom.bindClick('#btnInsertGallery', function () {

            let _items = document.querySelectorAll('.fpcm-ui-list-checkbox[data-type=image]:checked');
            if (!_items || !_items.length) {
                return false;
            }

            parent.fpcm.editor.insertGalleryByEditor(_items);
            return false;
        });

    },

    initRenameButtons: function() {

        fpcm.dom.bindClick('a.dropdown-item[data-action="rename"]', function (_e, _ui) {

            if (!fpcm.ui.langvarExists('FILE_LIST_RENAME_NEWNAME')) {
                console.log('FILE_LIST_RENAME_NEWNAME');
                return false;
            }

            var _docname = _e.delegateTarget.dataset.file;

            var _input = new fpcm.ui.forms.input();
            _input.name = 'new-filename-dialog';
            _input.label = fpcm.ui.translate('FILE_LIST_FILENAME');
            _input.value = _e.delegateTarget.dataset.oldname;
            _input.placeholder = _e.delegateTarget.dataset.oldname;

            fpcm.ui_dialogs.create({
                id: 'files-rename',
                title: 'FILE_LIST_RENAME_NEWNAME',
                closeButton: true,
                content: _input,
                dlButtons: [
                    {
                        text: 'GLOBAL_SAVE',
                        icon: "save",
                        clickClose: true,
                        primary: true,
                        click: function(_ev, _ui) {

                            let _inputEl = document.getElementById('fpcm-id-new-filename-dialog');
                            if (!_inputEl.value || !_inputEl.value.match(/^[a-z0-9_\.\-\(\)]+$/i)) {

                                let _msg = fpcm.ui.translate('RENAME_FAILED_FILE')
                                        .replace('{{filename1}}', _e.delegateTarget.dataset.oldname)
                                        .replace('{{filename2}}', _inputEl.value);

                                if (_inputEl.validationMessage) {
                                    _msg = _inputEl.validationMessage;
                                }

                                fpcm.ui.addMessage({
                                    txt: _msg,
                                    type: 'error'
                                });

                                return;
                            }

                            fpcm.ajax.post('files/rename', {
                                data: {
                                    newName: _inputEl.value,
                                    oldName: _docname
                                },
                                execDone: function (result) {
                                    fpcm.ui.addMessage(result);
                                    fpcm.filemanager.reloadFiles();
                                }
                            });
                        }
                    }
                ],
                dlOnOpenAfter: function () {
                    document.getElementById('fpcm-id-new-filename-dialog').focus();
                }
            });

            return false;
        });
    },

    initEditButtons: function() {
        fpcm.dom.bindClick('a.dropdown-item[data-action="edit"]', function (_e, _ui) {
            fpcm.imageEditor.initEditorDialog({
                afterUpload: function () {
                    fpcm.filemanager.reloadFiles();
                    fpcm.ui_dialogs.close('files-editor');
                },
                data: _ui.dataset
            });
            return false;
        });
    },

    initAltTextButtons: function() {

        fpcm.dom.bindClick('a.dropdown-item[data-action="alttext"]', function (_e, _ui) {

            var _input = new fpcm.ui.forms.input();
            _input.name = 'alt-text-dialog';
            _input.label = fpcm.ui.translate('FILE_LIST_ALTTEXT');
            _input.value = _ui.dataset.alttext;
            _input.placeholder = _ui.dataset.alttext;

            fpcm.ui_dialogs.create({
                id: 'files-alttext',
                title: 'FILE_LIST_ALTTEXT',
                closeButton: true,
                content: _input,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_SAVE'),
                        icon: "save",
                        clickClose: true,
                        primary: true,
                        click: function() {
                            fpcm.ajax.post('files/alttext', {
                                data: {
                                    file: _ui.dataset.file,
                                    alttext: fpcm.dom.fromId('fpcm-id-alt-text-dialog').val()
                                },
                                execDone: function (result) {
                                    fpcm.ui.addMessage(result);
                                    fpcm.filemanager.reloadFiles();
                                }
                            });
                        }
                    }
                ],
                dlOnOpenAfter: function () {
                    document.getElementById('fpcm-id-alt-text-dialog').focus();
                }
            });

            return false;
        });
    },

    initDeleteButtons: function() {

        fpcm.dom.bindClick('a.dropdown-item[data-action="delete"]',function (_e, _ui) {
            fpcm.ui_dialogs.confirm({
                clickNoDefault: true,
                focusNo: true,
                clickYes: function () {

                    fpcm.ajax.post('files/delete', {
                        dataType: 'json',
                        data: {
                            filename: _ui.dataset.file
                        },
                        execDone: function (result) {

                            result.txt.replace('{{filenames}}', _ui.dataset.filename);
                            fpcm.ui.addMessage(result);

                            fpcm.filemanager.reloadFiles();
                            return false;
                        }
                    });

                    return false;
                }
            });

            return false;
        });

    },

    initNewThumbButton: function() {

        fpcm.dom.bindClick('#btnCreateThumbs', function (event, ui) {

            var items = fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox[data-type=image]');
            if (!items || !items.length) {
                return false;
            }

            ui.disabled = true;

            fpcm.ajax.post('files/createthumbs', {
                async: true,
                loaderMsg: fpcm.ui.translate('MSG_FILES_CREATETHUMBS'),
                data: {
                    items: items
                },
                execDone: function (result) {

                    for (var _i in result) {
                        let _value = result[_i];
                        fpcm.ui.addMessage(_value, _i == 1);
                    }

                    fpcm.filemanager.reloadFiles();
                    ui.disabled = false;
                }
            });

            return false;
        });

    },

    initDeleteMultipleButton: function() {

        fpcm.dom.bindClick('#btnDeleteFiles', function () {

            var items = fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
            if (!items || !items.length) {
                return false;
            }

            fpcm.ui_dialogs.confirm({
                focusNo: true,
                clickYes: function () {
                    fpcm.ajax.post('files/delete', {
                        dataType: 'json',
                        data: {
                            filename: items,
                            multiple: 1
                        },
                        execDone: function (result) {

                            jQuery.each(result, function (i, value) {
                                fpcm.ui.addMessage(value, i == 1 ? true : false);
                            });

                            document.getElementById('fpcm-select-all').checked = false;
                            fpcm.filemanager.reloadFiles();
                        }
                    });

                    return false;
                }
            });

            return false;
        });

    },

    initCopyButton: function() {

        fpcm.dom.bindClick('a.dropdown-item[data-fn="system.createCopy"]', function (_e) {
            fpcm.system.createCopy(_e);
        });

    },

    initReminderButton: function() {

        fpcm.dom.bindClick('button[data-remindertype="files"]', function (_e) {

            let _remDlg = fpcm.ui_dialogs.fromDOM('reminders');
            let _rid = parseInt(_e.currentTarget.dataset.reminderid);
            let _oid = parseInt(_e.currentTarget.dataset.id);

            fpcm.ui_dialogs.create({
                id: 'files-remidners',
                title: 'HL_REMINDER',
                content: _remDlg,
                closeButton: true,
                scrollable: false,
                directAssignToDom: true,
                dlButtons: [
                    {
                        text: 'GLOBAL_SAVE',
                        icon: "save",
                        clickClose: true,
                        primary: true,
                        click: function() {

                            let _uid = parseInt(document.getElementById(fpcm.ui.prepareId('user-id', true)).value);
                            if (!_uid) {
                                fpcm.ui.addMessage({
                                    txt: 'REMINDER_SAVE_FAILED',
                                    type: 'error'
                                });

                                return false;
                            }

                            let _dt = {
                                date: document.getElementById(fpcm.ui.prepareId('resub-date', true)).value,
                                time: document.getElementById(fpcm.ui.prepareId('resub-time', true)).value
                            }

                            if (!_dt.date || !_dt.time) {
                                fpcm.ui.addMessage({
                                    txt: 'REMINDER_SAVE_FAILED',
                                    type: 'error'
                                });

                                return false;
                            }

                            fpcm.reminders.set(
                                _e.currentTarget.dataset.remindertype,
                                _rid,
                                _oid,
                                _uid,
                                _dt,
                                document.getElementById(fpcm.ui.prepareId('resub-comment', true)).value,
                                function (_res) {
                                    if (!_res.reload) {
                                        return false;
                                    }

                                    fpcm.filemanager.reloadFiles();
                                }
                            );
                        }
                    },
                    {
                        text: 'GLOBAL_DELETE',
                        icon: "trash",
                        clickClose: true,
                        showLabel: false,
                        disabled: !_rid,
                        click: function() {

                            fpcm.reminders.delete(
                                _e.currentTarget.dataset.remindertype,
                                _rid,
                                function (_res) {
                                    if (!_res.reload) {
                                        return false;
                                    }

                                    fpcm.filemanager.reloadFiles();
                                }
                            );
                        }
                    },
                ],
                dlOnOpen: function () {

                    if (!_rid) {
                        return false;
                    }

                    fpcm.reminders.get(
                        _e.currentTarget.dataset.remindertype,
                        _rid,
                        function (_res) {

                            let _uselect = document.getElementById(fpcm.ui.prepareId('user-id', true));
                            let _secIndx = 0;

                            for (var _o in _uselect.options) {
                                _secIndx = _o;
                                if (_uselect.options[_o].value == _res.uid) {
                                    break;
                                }
                            }

                            _uselect.value = _res.uid;
                            _uselect.selectedIndex = _secIndx;

                            document.getElementById(fpcm.ui.prepareId('resub-date', true)).value = _res.dateTime.date;
                            document.getElementById(fpcm.ui.prepareId('resub-time', true)).value = _res.dateTime.time;
                            document.getElementById(fpcm.ui.prepareId('resub-comment', true)).value = _res.comment;
                        }
                    );
                }
            });


        });

    },

    initPropertiesButton: function() {

        let _form = [
            {
                prop: 'filetime',
                label: 'GLOBAL_LASTCHANGE',
                icon: new fpcm.ui.forms.icon('calendar', 'lg'),
                class: 'mb-2',
                cols: 2
            },
            {
                prop: 'fileuser',
                label: 'FILE_LIST_UPLOAD_BY',
                icon: new fpcm.ui.forms.icon('user', 'lg'),
                cols: 2
            },
            {
                prop: 'filesize',
                label: 'FILE_LIST_FILESIZE',
                icon: new fpcm.ui.forms.icon('weight', 'lg'),
                cols: 2
            },
            {
                prop: 'resolution',
                label: 'FILE_LIST_RESOLUTION',
                icon: new fpcm.ui.forms.icon('maximize', 'lg'),
                cols: 2
            },
            {
                prop: 'filemime',
                label: 'FILE_LIST_FILETYPE',
                icon: new fpcm.ui.forms.icon('file-circle-question', 'lg'),
                cols: 2
            },
            {
                prop: 'credits',
                label: 'FILE_LIST_FILECREDITS',
                icon: new fpcm.ui.forms.icon('copyright', 'lg'),
                cols: 1
            },
            {
                prop: 'filehash',
                label: 'FILE_LIST_FILEHASH',
                icon: new fpcm.ui.forms.icon('hashtag', 'lg'),
                class: 'mb-2',
                cols: 1
            }

        ];

        fpcm.dom.bindClick('a.dropdown-item[data-action="properties"]', function (_e, _ui) {

            let _titleTxt = '';
            let _titleHtml = '';

            let _dlgContent = document.createElement('div');
            _dlgContent.classList.add('list-group');

            let _mfSckip = ['resolution', 'credits'];

            for (var _idx in _form) {

                let _propCfg = _form[_idx];

                if (_ui.dataset.mft === '1' && _mfSckip.includes(_propCfg.prop)) {
                    continue;
                }

                switch (_propCfg.prop) {
                    case 'resolution' :
                        _titleTxt = _ui.dataset.fileresx + ' X ' + _ui.dataset.fileresy + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
                        _titleHtml = _ui.dataset.fileresx + fpcm.ui.getIcon('times') + _ui.dataset.fileresy + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
                        break;
                    default:
                        _titleTxt = _ui.dataset[_propCfg.prop];
                        _titleHtml = _ui.dataset[_propCfg.prop];
                        break;
                }

                let _row = document.createElement('div');
                _row.classList.add('row', 'row-cols-1', 'row-cols-lg-' + _propCfg.cols, 'g-0');

                let _icon = _propCfg.icon;
                _icon.iconClass = 'me-2';

                let _colDescr = document.createElement('div');
                _colDescr.classList.add('col', 'align-self-center');
                _colDescr.innerHTML = _icon.getString() + fpcm.ui.translate(_propCfg.label);
                _row.appendChild(_colDescr);

                if (!_titleHtml) {
                    _titleHtml = '&nbsp;';
                }

                let _colValue = document.createElement('div');
                _colValue.classList.add('col', 'text-truncate');
                _colValue.innerHTML = _titleHtml;
                _colValue.title = _titleTxt;
                _row.appendChild(_colValue);

                let _listitem = document.createElement('div');
                _listitem.classList.add('list-group-item');
                _listitem.appendChild(_row);

                _dlgContent.appendChild(_listitem);
            }

            fpcm.ui_dialogs.create({
                id: 'files-properties',
                title: fpcm.ui.translate('GLOBAL_PROPERTIES'),
                closeButton: true,
                directAssignToDom: true,
                content: _dlgContent
            });

        });

    },

    initPagination: function() {

        fpcm.ui.initPager({
            backAction: function() {
                fpcm.filemanager.reloadFiles(fpcm.vars.jsvars.pager.showBackButton);
            },

            nextAction: function() {
                fpcm.filemanager.reloadFiles(fpcm.vars.jsvars.pager.showNextButton);
            },
            selectAction: function( event, ui ) {
                fpcm.filemanager.reloadFiles(ui.value);
            }
        });

    },

    reloadFiles: function (_page, _filter) {

        if (!_page) {
            _page = fpcm.filemanager.getCurrentPage();
        }

        fpcm.dom.assignHtml('#fpcm-tab-files-list-pane', fpcm.vars.jsvars.loaderTpl.replace(/\{\$thumbsize\}/g, fpcm.vars.jsvars.thumbsize));

         if (_filter) {
            fpcm.vars.jsvars.filesLastSearch = (new Date()).getTime();
        }

        fpcm.ajax.getItemList({
            module: 'files',
            destination: "#fpcm-tab-files-list-pane",
            mode: fpcm.vars.jsvars.fmgrMode,
            type: fpcm.filemanager.viewType,
            page: _page,
            filter: _filter ? _filter : null,
            loader: false,
            dataType: 'json',
            onAssignHtmlAfter: function (_result) {

                if (_result.data && _result.data.pager) {
                    fpcm.vars.jsvars.pager = _result.data.pager;
                }

                fpcm.filemanager.initListActions();
                fpcm.dom.fromClass('fpcm-select-all').prop('checked', false);
                document.getElementById('fpcm-select-all').checked = false;
            }
        });

        return false;
    },

    runFileIndexUpdate: function () {

        fpcm.ajax.get('crons/exec', {
            data    : {
                cjId: 'fileindex'
            },
            loaderMsg: fpcm.ui.translate('FILE_LIST_ADDTOINDEX'),
            execDone: function () {
                fpcm.ui_tabs.show('#files', 0);
            }
        });
    },

    getCurrentPage: function () {

        let _el = document.getElementById('pageSelect');
        if (!_el || _el.options.length < 2 || _el.value === undefined) {
            return 1;
        }

        return _el.value;
    },

    getAcceptTypesArr: function () {

        return [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
            '.jpeg', '.jpg', '.png', '.gif', '.webp',
            'video/mp4', 'video/ogg', 'video/webm',
            'audio/mpeg', 'audio/wav', 'audio/ogg',
            '.ogg', '.webm', '.mp4', '.mp3', '.wav',
        ];

    }
};
