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

        if (fpcm.vars.jsvars.fmgrMode === 1) {
            
            fpcm.ui.selectmenu('select[data-user_setting]', {
                change: function (_ev, _ui) {

                    document.getElementById('pageSelect').selectedIndex = 0;
                    
                    fpcm.ajax.post('setconfig', {
                        data: {
                            var: _ui.dataset.user_setting,
                            value: _ui.value
                        },
                        execDone: fpcm.filemanager.reloadFiles
                    });
                }
            });            
         
        }

        fpcm.dom.fromId('btnFmgrUploadBack').click(function () {
            fpcm.ui_tabs.show('#files', 0);
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
            
            fpcm.dom.bindClick('.fpcm-filelist-tinymce-thumb', function (_e, _ui) {
                parent.document.getElementById('mediaposter').value  = _ui.href;
                fpcm.ui_dialogs.close('editor-html-filemanager', true);
            });
            
            fpcm.dom.bindClick('.fpcm-filelist-tinymce-full', function (_e, _ui) {
                parent.document.getElementById('mediaposter').value  = _ui.href;
                fpcm.ui_dialogs.close('editor-html-filemanager', true);
            });
        }

    },

    initRenameButtons: function() {
        fpcm.dom.fromClass('fpcm-filelist-rename').unbind('click');
        fpcm.dom.fromClass('fpcm-filelist-rename').click(function () {

            if (!fpcm.ui.langvarExists('FILE_LIST_RENAME_NEWNAME')) {
                console.log('FILE_LIST_RENAME_NEWNAME');
                return false;
            }
            
            var _docname = this.dataset.file;

            var _input = new fpcm.ui.forms.input();
            _input.name = 'new-filename-dialog';
            _input.label = fpcm.ui.translate('FILE_LIST_FILENAME');
            _input.value = this.dataset.oldname;
            _input.placeholder = this.dataset.oldname;

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
                            fpcm.ajax.post('files/rename', {
                                data: {
                                    newName: fpcm.dom.fromId('fpcm-id-new-filename-dialog').val(),
                                    oldName: _docname
                                },
                                execDone: function (result) {
                                    fpcm.ui.addMessage(result);
                                    fpcm.filemanager.reloadFiles();
                                }
                            });
                        }
                    }
                ]
            });
            
            return false;
        });
    },
    
    initEditButtons: function() {
        fpcm.dom.bindClick('.fpcm-filelist-link-edit', function (_e, _ui) {
            fpcm.imageEditor.initEditorDialog({
                afterUpload: fpcm.filemanager.reloadFiles,
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
                        clickClose: true,
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
                ]
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

                    jQuery.each(result, function (i, value) {
                        fpcm.ui.addMessage(value, i == 1 ? true : false);
                    });

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

    initPropertiesButton: function() {

        fpcm.filemanager.propertiesDialog = {
            filetime: '',
            fileuser: '',
            filesize: '',
            resulution: '',
            filehash: '',
            filemime: '',
            credits: ''
        };

        fpcm.dom.bindClick('.fpcm-filelist-properties', function (_e, _ui) {
            fpcm.ui_dialogs.create({
                id: 'files-properties',
                title: fpcm.ui.translate('GLOBAL_PROPERTIES'),
                closeButton: true,
                dlOnOpen: function () {

                    var titleTxt = '';
                    var titleHtml = '';
                    
                    for (var _prop in fpcm.filemanager.propertiesDialog) {

                        switch (_prop) {
                            case 'resulution' :
                                titleTxt = _ui.dataset.fileresx + ' X ' + _ui.dataset.fileresy + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
                                titleHtml = _ui.dataset.fileresx + fpcm.ui.getIcon('times') + _ui.dataset.fileresy + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
                                fpcm.dom.fromId('fpcm-dialog-files-properties-' + _prop).attr('title', titleTxt).html(titleHtml);
                                break;
                            default:
                                titleTxt = _ui.dataset[_prop];
                                titleHtml = _ui.dataset[_prop];
                                break;
                        }


                        if (!titleHtml) {
                            titleHtml = '&nbsp';
                        }

                        fpcm.dom.fromId('fpcm-dialog-files-properties-' + _prop).html(titleHtml).attr('title', _ui.dataset[_prop]);
                        
                    }
                },
                dlOnClose: function() {
                    for (var _prop in fpcm.filemanager.propertiesDialog) {
                        fpcm.dom.fromId('fpcm-dialog-files-properties-' + _prop).empty().attr('title', fpcm.filemanager.propertiesDialog[_prop]);
                    };
                }
            })
            

            return false;
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

            fpcm.ui_dialogs.create({
                id: 'files-search',
                title: 'ARTICLES_SEARCH',
                closeButton: true,
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
                dlOnOpen: function( event, ui ) {
                    fpcm.dom.fromId('text').focus();
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
