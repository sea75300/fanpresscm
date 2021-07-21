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
            
            onRenderHtmlAfter: function () {
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
            
            fpcm.ui.selectmenu('#listView', {
                change: function (_ev, _ui) {
                    fpcm.ajax.post('setconfig', {
                        data: {
                            var: 'file_view',
                            value: _ui.value
                        },
                        execDone: function() {
                            fpcm.filemanager.reloadFiles();
                            fpcm.dom.fromId('opensearch').removeClass('btn-primary');
                        }
                    });
                }
            });            
        }

        fpcm.dom.fromId('btnFmgrUploadBack').click(function () {
            fpcm.ui_tabs.show('#files', 0);
        });
        
    },

    initListActions: function (_hideLoader) {
        fpcm.ui.assignCheckboxes();        
        fpcm.filemanager.initPagination();
        fpcm.filemanager.initInsertButtons();
        fpcm.filemanager.initRenameButtons();
        fpcm.filemanager.initEditButtons();
        fpcm.filemanager.initDeleteButtons();
        fpcm.filemanager.initAltTextButtons();
        fpcm.filemanager.initPropertiesButton();
        fpcm.filemanager.initPagination();
        fpcm.dom.fromClass('fpcm.link-fancybox').fancybox();
        if (_hideLoader === true) {
            fpcm.ui_loader.hide();
        }
    },
    
    closeRenameDialog: function() {
        fpcm.dom.fromId('newfilename').val('');
        fpcm.dom.fromId('newFilenameDialog').val('');
    },
    
    initInsertButtons: function () {

        fpcm.dom.fromClass('fpcm-filelist-tinymce-thumb').unbind('click');
        fpcm.dom.fromClass('fpcm-filelist-tinymce-full').unbind('click');
        fpcm.dom.fromClass('fpcm-filelist-articleimage').unbind('click');
        fpcm.dom.fromId('insertGallery').unbind('click');

        if (fpcm.vars.jsvars.fmgrMode === 2) {
            fpcm.dom.fromClass('fpcm-filelist-tinymce-thumb').click(function () {
                parent.fpcm.editor.insertThumbByEditor(fpcm.dom.fromTag(this).attr('href'), fpcm.dom.fromTag(this).data('imgtext'));
                return false;
            });

            fpcm.dom.fromClass('fpcm-filelist-tinymce-full').click(function () {
                parent.fpcm.editor.insertFullByEditor(fpcm.dom.fromTag(this).attr('href'), fpcm.dom.fromTag(this).data('imgtext'));
                return false;
            });

            fpcm.dom.fromId('insertGallery').click(function () {

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
            fpcm.dom.fromClass('fpcm-filelist-articleimage').click(function () {

                var url   = fpcm.dom.fromTag(this).attr('href');
                parent.document.getElementById('articleimagepath').value  = url;
                var dialogEl = window.parent.fpcm.dom.fromId("fpcm-dialog-editor-html-filemanager");

                dialogEl.dialog('close');
                dialogEl.empty();
                return false;
            });

            return false;
        }
        
        if (fpcm.vars.jsvars.fmgrMode === 4) {
            
            fpcm.dom.fromClass('fpcm-filelist-tinymce-thumb').click(function () {
                var url   = fpcm.dom.fromTag(this).attr('href');
                parent.document.getElementById('mediaposter').value  = url;
                var dialogEl = window.parent.fpcm.dom.fromId("fpcm-dialog-editor-html-filemanager");

                dialogEl.dialog('close');
                dialogEl.empty();
                return false;
            });

            fpcm.dom.fromClass('fpcm-filelist-tinymce-full').click(function () {
                var url   = fpcm.dom.fromTag(this).attr('href');
                parent.document.getElementById('mediaposter').value  = url;
                var dialogEl = window.parent.fpcm.dom.fromId("fpcm-dialog-editor-html-filemanager");

                dialogEl.dialog('close');
                dialogEl.empty();
                return false;
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

            var selectedFile = fpcm.dom.fromTag(this).data('file');
            fpcm.dom.fromId('newFilenameDialog').val(fpcm.dom.fromTag(this).data('oldname'));

            fpcm.ui.dialog({
                id: 'files-rename',
                title: fpcm.ui.translate('FILE_LIST_RENAME_NEWNAME'),
                closeButton: true,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_SAVE'),
                        icon: "save",
                        clickClose: true,
                        primary: true,
                        click: function() {
                            fpcm.ajax.post('files/rename', {
                                data: {
                                    newName: fpcm.dom.fromId('newFilenameDialog').val(),
                                    oldName: selectedFile
                                },
                                execDone: function (result) {
                                    fpcm.ui.addMessage(result);
                                    fpcm.filemanager.closeRenameDialog();
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

        fpcm.dom.fromClass('fpcm-filelist-link-edit').unbind('click');
        fpcm.dom.fromClass('fpcm-filelist-link-edit').click(function () {
            fpcm.imageEditor.initEditorDialog({
                afterUpload: fpcm.filemanager.reloadFiles,
                data: fpcm.dom.fromTag(this).data()
            });
            return false;
        });
    },

    initAltTextButtons: function() {

        fpcm.dom.fromClass('fpcm-filelist-link-alttext').unbind('click');
        fpcm.dom.fromClass('fpcm-filelist-link-alttext').click(function () {

        var selectedFile = fpcm.dom.fromTag(this).data('file');
        fpcm.dom.fromId('altTextDialog').val(fpcm.dom.fromTag(this).data('alttext'));

        fpcm.ui.dialog({
            id: 'files-alttext',
            title: 'FILE_LIST_ALTTEXT',
            closeButton: true,
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_SAVE'),
                    icon: "save",
                    clickClose: true,
                    clickClose: true,
                    click: function() {
                        fpcm.ajax.post('files/alttext', {
                            data: {
                                file: selectedFile,
                                alttext: fpcm.dom.fromId('altTextDialog').val()
                            },
                            execDone: function (result) {
                                fpcm.ui.addMessage(result);
                                fpcm.dom.fromId('altTextDialog').val('');
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

        fpcm.dom.fromClass('fpcm-filelist-delete').unbind('click');
        fpcm.dom.fromClass('fpcm-filelist-delete').click(function () {

            var el = fpcm.dom.fromTag(this);
            var filename = el.data('filename');
            var path = el.data('file');

            fpcm.ui.confirmDialog({
                clickNoDefault: true,
                clickYes: function () {

                    fpcm.ajax.post('files/delete', {
                        dataType: 'json',
                        data: {
                            filename: path
                        },
                        execDone: function (result) {

                            result.txt.replace('{{filenames}}', filename);
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

        var el = fpcm.dom.fromId('createThumbs');
        if (!el.length) {
            return false;
        }

        el.click(function (event, ui) {
            
            var items = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
            if (!items || !items.length) {
                return false;
            }

            fpcm.ajax.post('files/createthumbs', {
                data: {
                    items: items
                },
                execDone: function (result) {

                    jQuery.each(result, function (i, value) {
                        fpcm.ui.addMessage(value, i == 1 ? true : false);
                    });

                    fpcm.filemanager.reloadFiles();
                }
            });

            return false;
        })

    },

    initDeleteMultipleButton: function() {

        var el = fpcm.dom.fromId('deleteFiles');
        if (!el.length) {
            return false;
        }

        el.unbind('click');
        el.click(function (event, ui) {
            
            var items = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
            if (!items || !items.length) {
                return false;
            }

            fpcm.ui.confirmDialog({
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

        fpcm.filemanager.propertiesDialog = [
            'filetime',
            'fileuser',
            'filesize',
            'resulution',
            'filehash',
            'filemime',
            'credits'
        ];

        fpcm.dom.fromClass('fpcm-filelist-properties').click(function () {
            
            var el = fpcm.dom.fromTag(this);

            fpcm.ui.dialog({
                id: 'files-properties',
                title: fpcm.ui.translate('GLOBAL_PROPERTIES'),
                closeButton: true,
                dlOnOpen: function () {

                    var titleTxt = '';
                    var titleHtml = '';

                    jQuery.each(fpcm.filemanager.propertiesDialog, function (idx, prop) {
                        
                        switch (prop) {
                            case 'resulution' :
                                titleTxt = el.data('fileresx') + ' X ' + el.data('fileresy') + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
                                titleHtml = el.data('fileresx') + fpcm.ui.getIcon('times') + el.data('fileresy') + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
                                fpcm.dom.fromId('fpcm-dialog-files-properties-' + prop).attr('title', titleTxt).html(titleHtml);
                                break;
                            default:
                                titleTxt = el.data('' + prop);
                                titleHtml = el.data('' + prop);
                                break;
                        }


                        if (!titleHtml) {
                            titleHtml = '&nbsp';
                        }

                        fpcm.dom.fromId('fpcm-dialog-files-properties-' + prop).html(titleHtml).attr('title', el.data('' + prop));
                    });
                },
                dlOnClose: function() {
                    jQuery.each(fpcm.filemanager.propertiesDialog, function (idx, prop) {
                        fpcm.dom.fromId('fpcm-dialog-files-properties-' + prop).empty().attr('title', '');
                    });
                }
            })
            

            return false;
        });
    },
    
    initPagination: function() {

        fpcm.vars.jsvars.pager = {
            maxPages: 0,
            showBackButton: true,
            showNextButton: true
        };

        fpcm.ui.initPager({
            keepSelect: true,
            backAction: function() {
                var link = fpcm.dom.fromTag(this).attr('href');
                if (link === '#') {
                    return false;
                }

                var page = link.split('&page=');
                fpcm.filemanager.reloadFiles((page[1] === undefined) ? 1 : page[1]);
                return false;
            },
            
            nextAction: function() {
                
                var link = fpcm.dom.fromTag(this).attr('href');
                if (link === '#') {
                    return false;
                }

                var page = link.split('&page=');
                fpcm.filemanager.reloadFiles((page[1] === undefined) ? 1 : page[1]);
                return false;
            },
            selectAction: function( event, ui ) {
                debugger;
                fpcm.filemanager.reloadFiles(ui.value);
            }
        });

    },

    reloadFiles: function (_page, _filter) {

        if (!_page) {
            _page = 1;
        }
        
         if (_filter) {
            fpcm.vars.jsvars.filesLastSearch = (new Date()).getTime();
        }

        fpcm.ajax.getItemList({
            module: 'files',
            destination: "#fpcm-tab-files-list-pane",
            mode: fpcm.vars.jsvars.fmgrMode,
            page: _page,
            filter: _filter ? _filter : null,
            loader: fpcm.dom.fromTag('div.fpcm-ui-inline-loader').length ? false : true,
            dataType: 'html',
            onAssignHtmlAfter: function () {
                fpcm.filemanager.initListActions(true);
            }
        });
        
        return false;
    },
    
    initFilesSearch: function() {

        fpcm.dom.fromId('opensearch').click(function () {

            var sDlg = fpcm.ui.dialog({
                id: 'files-search',
                title: 'ARTICLES_SEARCH',
                dlButtons: [
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: "check",
                        primary: true,
                        clickClose: true,
                        click: function(_ui, _bsObj) {               
                            
                            var sParams = fpcm.ui.getValuesByClass('fpcm-files-search-input');                            
                            sParams.combinations = fpcm.ui.getValuesByClass('fpcm-ui-input-select-filessearch-combination');

                            fpcm.filemanager.startFilesSearch(sParams);
                        }
                    },                    
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_RESET'),
                        icon: "undo" ,                        
                        click: function() {
                            fpcm.ui.relocate('self');
                        }
                    },                    
                    {
                        text: 'GLOBAL_CLOSE',
                        icon: 'times',
                        clickClose: true
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
        fpcm.dom.fromId('opensearch').addClass('btn-primary');
    },
    
    runFileIndexUpdate: function () {

        fpcm.ajax.get('cronasync', {
            data    : {
                cjId: 'fileindex'
            },
            loaderMsg: fpcm.ui.translate('FILE_LIST_ADDTOINDEX')
        });

    }
};
