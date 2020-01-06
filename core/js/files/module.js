/**
 * FanPress CM Filemanager Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.filemanager = {

    tabsObj: {},

    init: function() {

        if (fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            fpcm.filemanager.initFilesSearch();
        }

        fpcm.filemanager.initNewThumbButton();
        fpcm.filemanager.initDeleteMultipleButton();

        fpcm.dom.fromId('tabs-files-list-reload').click(function () {
            fpcm.filemanager.reloadFiles();
            return false;
        });
        
        window.addEventListener('message', function (event) {

            if (!event.data || !event.data.cmd) {
                return false;
            }

            if (event.data.mceAction === 'clickFmgrBtn') {
                fpcm.dom.fromId('' + event.data.cmd).click()
            }

        });
    },
    
    initAfter: function() {

        if (fpcm.vars.jsvars.loadAjax) {
            fpcm.filemanager.reloadFiles();
        }

        if (fpcm.vars.jsvars.fmgrMode === 1) {
            fpcm.ui.checkboxradio('.fpcm-ui-listeview-setting', {}, function () {
                fpcm.ajax.post('setconfig', {
                    data: {
                        var: 'file_view',
                        value: fpcm.dom.fromTag(this).val()
                    },
                    execDone: fpcm.filemanager.reloadFiles
                });
            });

            fpcm.filemanager.tabsObj = fpcm.ui.tabs('#fpcm-files-tabs', {
                beforeActivate: function( event, ui ) {

                    var hideButtons = fpcm.dom.fromTag(ui.oldTab).data('toolbar-buttons');
                    var showButtons = fpcm.dom.fromTag(ui.newTab).data('toolbar-buttons');

                    fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ hideButtons).addClass('fpcm-ui-hidden');
                    fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ showButtons).removeClass('fpcm-ui-hidden');

                    fpcm.dom.fromId('fpcm-select-all').checkboxradio('instance').option('classes', {
                        "ui-checkboxradio-label": (showButtons == 2 ? "fpcm-ui-hidden" : "")
                    });

                    fpcm.dom.fromId('listViewCards').checkboxradio('instance').option('classes', {
                        "ui-checkboxradio-label": (showButtons == 2 ? "fpcm-ui-hidden" : "")
                    });

                    fpcm.dom.fromId('listViewList').checkboxradio('instance').option('classes', {
                        "ui-checkboxradio-label": (showButtons == 2 ? "fpcm-ui-hidden" : "")
                    });

                    fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');
                }
            });
        }

        fpcm.dom.fromId('btnFmgrUploadBack').click(function () {
            fpcm.filemanager.tabsObj.tabs('option', 'active', 0);
        });
        
    },

    initJqUiWidgets: function () {
        fpcm.dom.fromId('fpcm-select-all').prop('checked', false).checkboxradio('refresh');
        fpcm.ui.assignCheckboxes();
        fpcm.ui.assignControlgroups();
        fpcm.filemanager.initInsertButtons();
        fpcm.filemanager.initRenameButtons();
        fpcm.filemanager.initDeleteButtons();
        fpcm.filemanager.initPropertiesButton();
        fpcm.filemanager.initPagination();
        fpcm.dom.fromClass('fpcm-link-fancybox').fancybox();
    },
    
    closeRenameDialog: function() {
        fpcm.dom.fromId('newfilename').val('');
        fpcm.dom.fromId('newFilenameDialog').val('');
    },
    
    initInsertButtons: function () {

        fpcm.dom.fromClass('fpcm-filelist-tinymce-thumb').click(function () {
            parent.fpcm.editor.insertThumbByEditor(fpcm.dom.fromTag(this).attr('href'), fpcm.dom.fromTag(this).data('imgtext'));
            return false;
        });

        fpcm.dom.fromClass('fpcm-filelist-tinymce-full').click(function () {
            parent.fpcm.editor.insertFullByEditor(fpcm.dom.fromTag(this).attr('href'), fpcm.dom.fromTag(this).data('imgtext'));
            return false;
        });

        fpcm.dom.fromClass('fpcm-filelist-articleimage').click(function () {

            var url   = fpcm.dom.fromTag(this).attr('href');
            parent.document.getElementById('articleimagepath').value  = url;
            var dialogEl = window.parent.fpcm.dom.fromId("fpcm-dialog-editor-html-filemanager");
            
            dialogEl.dialog('close');
            dialogEl.empty();
            return false;
        });
    },

    initRenameButtons: function() {
        fpcm.dom.fromClass('fpcm-filelist-rename').click(function () {

            if (!fpcm.ui.langvarExists('FILE_LIST_RENAME_NEWNAME')) {
                console.log('FILE_LIST_RENAME_NEWNAME');
                return false;
            }

            var selectedFile = fpcm.dom.fromTag(this).data('file');
            fpcm.dom.fromId('newFilenameDialog').val(fpcm.dom.fromTag(this).data('oldname'));

            fpcm.ui.dialog({
                id: 'files-rename',
                dlWidth: fpcm.ui.getDialogSizes().width,
                title: fpcm.ui.translate('FILE_LIST_RENAME_NEWNAME'),
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_SAVE'),
                        icon: "ui-icon-check",                        
                        click: function() {
                            fpcm.dom.fromTag(this).dialog( "close" );
                            fpcm.ajax.exec('files/rename', {
                                data: {
                                    newName: fpcm.dom.fromId('newFilenameDialog').val(),
                                    oldName: selectedFile
                                },
                                execDone: function (result) {
                                    
                                    if (!result.message) {
                                        fpcm.filemanager.closeRenameDialog();
                                        fpcm.filemanager.reloadFiles();
                                        return false;
                                    }
                                    
                                    fpcm.ui.addMessage({
                                        txtComplete: result.message,
                                        type: result.code < 1 ? 'error' : 'notice'
                                    });
                                    
                                    fpcm.filemanager.closeRenameDialog();
                                    fpcm.filemanager.reloadFiles();
                                }
                            })
                            
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                
                        click: function () {
                            fpcm.filemanager.closeRenameDialog();
                            fpcm.dom.fromTag(this).dialog('close');
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

                    fpcm.dom.fromTag(this).dialog( "close" );
                    fpcm.ajax.exec('files/delete', {
                        dataType: 'json',
                        data: {
                            filename: path
                        },
                        execDone: function (result) {

                            fpcm.ui.addMessage({
                                txt: result.message.replace('{{filenames}}', filename),
                                type: result.code == 0 ? 'error' : 'notice'
                            });

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

            fpcm.ajax.exec('files/createthumbs', {
                dataType: 'json',
                data: {
                    items: items
                },
                execDone: function (result) {

                    jQuery.each(result.message, function (i, value) {
                        fpcm.ui.addMessage({
                            txt: value,
                            type: result.code[i] ? 'notice' : 'error'
                        }, i == 1 ? true : false);
                    })

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
                clickNoDefault: true,
                clickYes: function () {

                    fpcm.dom.fromTag(this).dialog( "close" );
                    fpcm.ajax.exec('files/delete', {
                        dataType: 'json',
                        data: {
                            filename: items,
                            multiple: 1
                        },
                        execDone: function (result) {

                            jQuery.each(result.message, function (i, value) {
                                fpcm.ui.addMessage({
                                    txt: value,
                                    type: result.code[i] ? 'notice' : 'error'
                                }, i == 1 ? true : false);
                            })

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
                dlOnOpen: function () {

                    var titleTxt = '';
                    var titleHtml = '';

                    jQuery.each(fpcm.filemanager.propertiesDialog, function (idx, prop) {
                        
                        switch (prop) {
                            case 'resulution' :
                                titleTxt = el.data('fileresx') + ' X ' + el.data('fileresy') + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
                                titleHtml = el.data('fileresx') + '<span class="fa fa-times fa-fw"></span>' + el.data('fileresy') + ' ' + fpcm.ui.translate('FILE_LIST_RESOLUTION_PIXEL');
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
                },
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                
                        click: function() {
                            fpcm.dom.fromTag(this).dialog( "close" );
                        }
                    }
                ]
            })
            

            return false;
        });
    },
    
    initPagination: function() {

        fpcm.vars.jsvars.pager = {
            maxPages: 0,
            showBackButton: true,
            showNextButton: true,            
        };

        fpcm.ui.initPager({
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
                fpcm.filemanager.reloadFiles(ui.item.value);
            }
        });

    },

    reloadFiles: function (page, filter) {

        if (!page) {
            page = 1;
        }
        
        if (!filter) {
            filter = {};
        }
        else if (filter) {
            fpcm.vars.jsvars.filesLastSearch = (new Date()).getTime();
        }

        fpcm.ajax.post('filelist', {
            quiet: fpcm.dom.fromTag('div.fpcm-ui-inline-loader').length ? true : false,
            data: {
                mode: fpcm.vars.jsvars.fmgrMode,
                page: page,
                filter: filter
            },
            execDone: function (result) {

                fpcm.ui.assignHtml("#tabs-files-list-content", result);
                fpcm.filemanager.initJqUiWidgets();
                var fpcmRFDinterval = setInterval(function(){
                    if (fpcm.dom.fromId('fpcm-filelist-images-finished').length == 1) {
                        fpcm.ui_loader.hide();
                        clearInterval(fpcmRFDinterval);
                        if (page) {
                            fpcm.dom.fromWindow().scrollTop(0);
                        }
                        return false;
                    }
                }, 250);

            }
        });
        
        return false;
    },
    
    initFilesSearch: function() {

        fpcm.dom.fromId('opensearch').click(function () {

            var sDlg = fpcm.ui.dialog({
                id      : 'files-search',
                resizable: true,
                title    : fpcm.ui.translate('ARTICLES_SEARCH'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: "ui-icon-check",
                        class: 'fpcm-ui-button-primary',
                        click: function() {               
                            
                            var sParams = fpcm.ui.getValuesByClass('fpcm-files-search-input');                            
                            sParams.combinations = fpcm.ui.getValuesByClass('fpcm-ui-input-select-filessearch-combination');

                            fpcm.filemanager.startFilesSearch(sParams);
                            fpcm.dom.fromTag(this).dialog('close');
                        }
                    },                    
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                        
                        click: function() {
                            fpcm.dom.fromTag(this).dialog('close');
                        }
                    }                            
                ],
                dlOnOpen: function( event, ui ) {
                    fpcm.dom.fromId('text').focus();
                }
            });

            fpcm.ui.selectmenu('.fpcm-ui-input-select-filessearch-combination', {
                width: '100%',
                appendTo: '#' + sDlg.attr('id')
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
    }
};
