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

    tabsObj: {},

    init: function() {

        fpcm.ui_tabs.render('#files', {
            reload: true,
            onRenderHtmlAfter: function (_event, _result) {

                if (_result.data && _result.data.pager) {
                    fpcm.vars.jsvars.pager = _result.data.pager;
                }

                fpcm.filemanager.initListActions();
            }
        });

        if (fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            fpcm.filemanager.initFilesSearch();
        }

        fpcm.filemanager.initNewThumbButton();
        fpcm.filemanager.initDeleteMultipleButton();

    },

    initAfter: function() {

        if (fpcm.vars.jsvars.fmgrMode !== 1) {
            return;
        }

        fpcm.dom.bindClick('#btnSettings', function () {

            let _settings = fpcm.ui_dialogs.fromDOM('filesSettings');
            if (!_settings) {
                return;
            }

            fpcm.ui_dialogs.create({
                id: 'files-settings',
                title: 'HL_OPTIONS',
                size: '',
                closeButton: true,
                directAssignToDom: true,
                content: _settings,
                icon: {
                    icon: 'cogs',
                },
                dlOnOpenAfter: function () {
                    fpcm.ui.selectmenu('select[data-user_setting]', {
                        change: function (_ev, _ui) {

                            document.getElementById('pageSelect').selectedIndex = 0;

                            fpcm.ajax.post('setconfig', {
                                data: {
                                    var: _ui.dataset.user_setting,
                                    value: _ui.value
                                },
                                execDone: function () {
                                    fpcm.filemanager.reloadFiles();
                                    fpcm.vars.jsvars.dialogs.filesSettings.fields[_ui.dataset.user_setting].preSelected = _ui.value;
                                }
                            });
                        }
                    });
                }
            });

        });

    },

    initListActions: function () {
        fpcm.ui.assignCheckboxes();
        fpcm.ui.initLightbox();
        fpcm.filemanager.initPagination();
        fpcm.filemanager.initInsertButtons();
        fpcm.filemanager.initRenameButtons();
        fpcm.filemanager.initEditButtons();
        fpcm.filemanager.initDeleteButtons();
        fpcm.filemanager.initAltTextButtons();
        fpcm.filemanager.initPropertiesButton();
        fpcm.filemanager.initCopyButton();
    },

    initInsertButtons: function () {

        if (fpcm.vars.jsvars.fmgrMode === 2) {

            fpcm.dom.bindClick('.fpcm-filelist-tinymce-thumb', function (_e, _ui) {
                parent.fpcm.editor.insertThumbByEditor(_ui.href, _ui.dataset.imgtext);
                fpcm.ui_dialogs.close('editor-html-filemanager', true);
            });

            fpcm.dom.bindClick('.fpcm-filelist-tinymce-full', function (_e, _ui) {
                parent.fpcm.editor.insertFullByEditor(_ui.href, _ui.dataset.imgtext);
                fpcm.ui_dialogs.close('editor-html-filemanager', true);
            });

            fpcm.dom.bindClick('#btnInsertGallery', function () {

                var values = [];
                fpcm.dom.fromClass('fpcm-ui-list-checkbox:checked').map(function (idx, item) {
                    values.push(jQuery(item).data('gallery'));
                });

                if (!values.length) {
                    return false;
                }

                parent.fpcm.editor.insertGalleryByEditor(values);
                return false;
            });

            return false;
        }

        if (fpcm.vars.jsvars.fmgrMode === 3) {

            fpcm.dom.bindClick('.fpcm-filelist-articleimage', function (_e, _ui) {
                parent.document.getElementById('articleimagepath').value  = _ui.href;
                fpcm.ui_dialogs.close('editor-html-filemanager', true);
            });

            return false;
        }

        if (fpcm.vars.jsvars.fmgrMode === 4) {

            let _fid = fpcm.ui.prepareId('mediaposter', true);

            fpcm.dom.bindClick('.fpcm-filelist-tinymce-thumb', function (_e, _ui) {
                parent.document.getElementById(_fid).value  = _ui.href;
                fpcm.ui_dialogs.close('editor-html-filemanager', true);
            });

            fpcm.dom.bindClick('.fpcm-filelist-tinymce-full', function (_e, _ui) {
                parent.document.getElementById(_fid).value  = _ui.href;
                fpcm.ui_dialogs.close('editor-html-filemanager', true);
            });
        }

    },

    initRenameButtons: function() {

        fpcm.dom.bindClick('.fpcm-filelist-rename', function (_e, _ui) {

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
                        click: function() {

                            let _inputEl = document.getElementById('fpcm-id-new-filename-dialog');
                            if (!_inputEl.value || !_inputEl.value.match(/^[a-z0-9_\.\-\(\)]+$/i)) {

                                fpcm.ui.addMessage({
                                    txt: _inputEl.validationMessage,
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
        fpcm.dom.bindClick('.fpcm-filelist-link-edit', function (_e, _ui) {
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

        fpcm.dom.bindClick('.fpcm-filelist-link-alttext', function (_e, _ui) {

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

        fpcm.dom.bindClick('.fpcm-filelist-delete',function (_e, _ui) {
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

            var items = fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
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

                            fpcm.dom.fromId('fpcm-select-all').prop('checked', false);
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

    initPropertiesButton: function() {

        let _form = [
            {
                prop: 'filetime',
                label: 'GLOBAL_LASTCHANGE',
                icon: new fpcm.ui.forms.icon('calendar', 'lg'),
                class: 'mb-2'
            },
            {
                prop: 'fileuser',
                label: 'FILE_LIST_UPLOAD_BY',
                icon: new fpcm.ui.forms.icon('user', 'lg'),
                class: 'mb-2'
            },
            {
                prop: 'filesize',
                label: 'FILE_LIST_FILESIZE',
                icon: new fpcm.ui.forms.icon('weight', 'lg'),
                class: 'mb-2'
            },
            {
                prop: 'resulution',
                label: 'FILE_LIST_RESOLUTION',
                icon: new fpcm.ui.forms.icon('maximize', 'lg'),
                class: 'mb-2'
            },
            {
                prop: 'filemime',
                label: 'FILE_LIST_FILETYPE',
                icon: new fpcm.ui.forms.icon('file-circle-question', 'lg'),
                class: 'mb-2'
            },
            {
                prop: 'filehash',
                label: 'FILE_LIST_FILEHASH',
                icon: new fpcm.ui.forms.icon('hashtag', 'lg'),
                class: 'mb-2'
            },
            {
                prop: 'credits',
                label: 'FILE_LIST_FILECREDITS',
                icon: new fpcm.ui.forms.icon('copyright', 'lg'),
                class: 'mb-2'
            }

        ];

        fpcm.dom.bindClick('.fpcm-filelist-properties', function (_e, _ui) {

            let _titleTxt = '';
            let _titleHtml = '';

            let _dlgContent = document.createElement('div');

            for (var _idx in _form) {

                let _propCfg = _form[_idx];

                switch (_propCfg.prop) {
                    case 'resulution' :
                        _titleTxt = _ui.dataset.fileresx + ' X ' + _ui.dataset.fileresy + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
                        _titleHtml = _ui.dataset.fileresx + fpcm.ui.getIcon('times') + _ui.dataset.fileresy + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
                        break;
                    default:
                        _titleTxt = _ui.dataset[_propCfg.prop];
                        _titleHtml = _ui.dataset[_propCfg.prop];
                        break;
                }

                let _row = document.createElement('div');
                _row.className = 'row g-0 ' + _propCfg.class;

                let _icon = _propCfg.icon;
                _icon.iconClass = 'me-2';

                let _colDescr = document.createElement('div');
                _colDescr.className = 'col-form-label align-self-center col-12 col-md-3 me-3';
                _colDescr.innerHTML = _icon.getString() + fpcm.ui.translate(_propCfg.label);
                _row.appendChild(_colDescr);

                let _colValue = document.createElement('div');
                _colValue.className = 'col align-self-center';
                _colValue.innerHTML = _titleHtml;
                _colValue.title = _titleTxt;
                _row.appendChild(_colValue);

                _dlgContent.appendChild(_row);
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
            }
        });

        return false;
    },

    initFilesSearch: function() {

        fpcm.dom.bindClick('#btnOpenSearch', function () {

            let _formData = fpcm.vars.jsvars.searchForm;

            let _form = document.createElement('div');

            for (var _fieldName in _formData.fields) {

                let _field = _formData.fields[_fieldName];

                let _tmp = new fpcm.ui.forms[_field.call];
                _tmp.name = _fieldName;
                _field.class += ' ' + _tmp.class;
                _tmp.wrapper = 'form-floating';

                if (!_tmp.assignFormObject) {
                    continue;
                }

                _tmp.assignFormObject(_field);

                let _row = document.createElement('div');
                _row.className = 'row mb-3';

                let _colDescr = document.createElement('div');
                _colDescr.className = 'col-12' + (!_field.noCombination ? ' col-md-9' : '');
                _tmp.assignToDom(_colDescr);

                _row.appendChild(_colDescr);

                let _colCombination = document.createElement('div');
                _colCombination.className = 'col-12 col-md-3 align-self-center';

                if (!_field.noCombination) {
                    let _comb = new fpcm.ui.forms.select();
                    _comb.name = 'combination' + _fieldName.charAt(0).toUpperCase() + _fieldName.slice(1);
                    _comb.options = _formData.combinations.default;
                    _comb.class = 'fpcm-ui-input-select-filessearch-combination ' + _comb.class;
                    _comb.wrapper = 'form-floating';
                    _comb.label = 'ARTICLE_SEARCH_LOGIC';

                    _comb.assignToDom(_colCombination);
                }

                _row.appendChild(_colCombination);

                _form.appendChild(_row);
            }

            fpcm.ui_dialogs.create({
                id: 'files-search',
                title: 'ARTICLES_SEARCH',
                closeButton: true,
                directAssignToDom: true,
                content: _form,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: "check",
                        primary: true,
                        clickClose: true,
                        click: function(_ui, _bsObj) {

                            var sParams = fpcm.dom.getValuesByClass('fpcm-files-search-input');
                            sParams.combinations = fpcm.dom.getValuesByClass('fpcm-ui-input-select-filessearch-combination');

                            fpcm.filemanager.startFilesSearch(sParams);
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_RESET'),
                        icon: "filter-circle-xmark" ,
                        clickClose: true,
                        click: function() {
                            fpcm.ui.relocate('self');
                        }
                    }
                ],
                dlOnOpenAfter: function () {
                    document.getElementById('fpcm-id-filename').focus();
                }
            });

            return false;
        });

    },

    startFilesSearch: function (sParams) {

        if (((new Date()).getTime() - fpcm.vars.jsvars.filesLastSearch) < 10000) {
            fpcm.ui.addMessage({
                type: 'error',
                txt : fpcm.ui.translate('SEARCH_WAITMSG')
            });
            return false;
        }

        fpcm.filemanager.reloadFiles(1, sParams);
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
    }
};
