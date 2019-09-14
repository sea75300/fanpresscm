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

        jQuery('#tabs-files-list-reload').click(function () {
            fpcm.filemanager.reloadFiles();
            return false;
        });
        
        window.addEventListener('message', function (event) {

            if (!event.data || !event.data.cmd) {
                return false;
            }

            if (event.data.mceAction === 'clickFmgrBtn') {
                jQuery('#' + event.data.cmd).click()
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
                        value: jQuery(this).val()
                    },
                    execDone: fpcm.filemanager.reloadFiles
                });
            });

            fpcm.filemanager.tabsObj = fpcm.ui.tabs('#fpcm-files-tabs', {
                beforeActivate: function( event, ui ) {

                    var hideButtons = jQuery(ui.oldTab).data('toolbar-buttons');
                    var showButtons = jQuery(ui.newTab).data('toolbar-buttons');

                    fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ hideButtons).addClass('fpcm-ui-hidden');
                    fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ showButtons).removeClass('fpcm-ui-hidden');

                    jQuery('#fpcm-select-all').checkboxradio('instance').option('classes', {
                        "ui-checkboxradio-label": (showButtons == 2 ? "fpcm-ui-hidden" : "")
                    });

                    jQuery('#listViewCards').checkboxradio('instance').option('classes', {
                        "ui-checkboxradio-label": (showButtons == 2 ? "fpcm-ui-hidden" : "")
                    });

                    jQuery('#listViewList').checkboxradio('instance').option('classes', {
                        "ui-checkboxradio-label": (showButtons == 2 ? "fpcm-ui-hidden" : "")
                    });

                    fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');
                }
            });
        }

        jQuery('#btnFmgrUploadBack').click(function () {
            fpcm.filemanager.tabsObj.tabs('option', 'active', 0);
        });
        
    },

    initJqUiWidgets: function () {
        fpcm.ui.assignCheckboxes();
        fpcm.ui.assignControlgroups();
        fpcm.filemanager.initInsertButtons();
        fpcm.filemanager.initRenameButtons();
        fpcm.filemanager.initDeleteButtons();
        fpcm.filemanager.initPropertiesButton();
        fpcm.filemanager.initPagination();
        jQuery('.fpcm-link-fancybox').fancybox();
    },
    
    closeRenameDialog: function() {
        jQuery('#newfilename').val('');
        jQuery('#newFilenameDialog').val('');
    },
    
    initInsertButtons: function () {

        jQuery('.fpcm-filelist-tinymce-thumb').click(function () {
            parent.fpcm.editor.insertThumbByEditor(jQuery(this).attr('href'), jQuery(this).data('imgtext'));
            return false;
        });

        jQuery('.fpcm-filelist-tinymce-full').click(function () {
            parent.fpcm.editor.insertFullByEditor(jQuery(this).attr('href'), jQuery(this).data('imgtext'));
            return false;
        });

        jQuery('.fpcm-filelist-articleimage').click(function () {

            var url   = jQuery(this).attr('href');
            parent.document.getElementById('articleimagepath').value  = url;
            window.parent.jQuery("#fpcm-dialog-editor-html-filemanager").dialog('close');
            window.parent.jQuery('#fpcm-dialog-editor-html-filemanager').empty();

            return false;
        });
    },

    initRenameButtons: function() {
        jQuery('.fpcm-filelist-rename').click(function () {

            if (!fpcm.ui.langvarExists('FILE_LIST_RENAME_NEWNAME')) {
                console.log('FILE_LIST_RENAME_NEWNAME');
                return false;
            }

            var selectedFile = jQuery(this).data('file');
            jQuery('#newFilenameDialog').val(jQuery(this).data('oldname'));

            fpcm.ui.dialog({
                id: 'files-rename',
                dlWidth: fpcm.ui.getDialogSizes().width,
                title: fpcm.ui.translate('FILE_LIST_RENAME_NEWNAME'),
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_SAVE'),
                        icon: "ui-icon-check",                        
                        click: function() {
                            jQuery( this ).dialog( "close" );
                            fpcm.ui.showLoader(true);
                            fpcm.ajax.exec('files/rename', {
                                data: {
                                    newName: jQuery('#newFilenameDialog').val(),
                                    oldName: selectedFile
                                },
                                execDone: function () {
                                    
                                    var result = fpcm.ajax.getResult('files/rename', true);
                                    fpcm.ui.addMessage({
                                        txtComplete: result.message,
                                        type: result.code < 1 ? 'error' : 'notice'
                                    });
                                    
                                    fpcm.filemanager.closeRenameDialog();
                                    fpcm.filemanager.reloadFiles();
                                    fpcm.ui.showLoader();
                                }
                            })
                            
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                
                        click: function () {
                            fpcm.filemanager.closeRenameDialog();
                            jQuery(this).dialog('close');
                        }
                    }
                ]
            });
            
            return false;
        });
    },

    initDeleteButtons: function() {

        jQuery('.fpcm-filelist-delete').click(function () {

            var filename = jQuery(this).data('filename');
            var path = jQuery(this).data('file');

            fpcm.ajax.exec('files/delete', {
                data: {
                    filename: path
                },
                execDone: function (result) {

                    result = fpcm.ajax.fromJSON(result);
                    fpcm.ui.addMessage({
                        txt: result.message.replace('{{filenames}}', filename),
                        type: result.code == 0 ? 'error' : 'notice'
                    });

                    fpcm.filemanager.reloadFiles();
                    fpcm.ui.showLoader();
                    return false;
                }
            });

            return false;
        });
    },

    initNewThumbButton: function() {

        var el = jQuery('#createThumbs');
        if (!el.length) {
            return false;
        }

        el.click(function (event, ui) {
            
            var items = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
            if (!items || !items.length) {
                return false;
            }

            fpcm.ui.showLoader(true);
            fpcm.ajax.exec('files/createthumbs', {
                data: {
                    items: items
                },
                execDone: function (result) {
                    
                    result = fpcm.ajax.fromJSON(result);
                    
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

        var el = jQuery('#deleteFiles');
        if (!el.length) {
            return false;
        }

        el.click(function (event, ui) {
            
            var items = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
            if (!items || !items.length) {
                return false;
            }

            fpcm.ui.showLoader(true);
            fpcm.ajax.exec('files/delete', {
                data: {
                    filename: items,
                    multiple: 1
                },
                execDone: function (result) {
                    
                    result = fpcm.ajax.fromJSON(result);

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

        jQuery('.fpcm-filelist-properties').click(function () {
            
            var el = jQuery(this);

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
                                jQuery('#fpcm-dialog-files-properties-' + prop).attr('title', titleTxt).html(titleHtml);
                                break;
                            default:
                                titleTxt = el.data('' + prop);
                                titleHtml = el.data('' + prop);
                                break;
                        }


                        if (!titleHtml) {
                            titleHtml = '&nbsp';
                        }

                        jQuery('#fpcm-dialog-files-properties-' + prop).html(titleHtml).attr('title', el.data('' + prop));
                    });
                },
                dlOnClose: function() {
                    jQuery.each(fpcm.filemanager.propertiesDialog, function (idx, prop) {
                        jQuery('#fpcm-dialog-files-properties-' + prop).empty().attr('title', '');
                    });
                },
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                
                        click: function() {
                            jQuery( this ).dialog( "close" );
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
                var link = jQuery(this).attr('href');
                if (link === '#') {
                    return false;
                }

                var page = link.split('&page=');
                fpcm.filemanager.reloadFiles((page[1] === undefined) ? 1 : page[1]);
                return false;
            },
            
            nextAction: function() {
                
                var link = jQuery(this).attr('href');
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

        if (!jQuery('div.fpcm-ui-inline-loader').length) {
            fpcm.ui.showLoader(true);
        }

        if (!page) {
            page = 1;
        }
        
        if (!filter) {
            filter = {};
        }
        else if (filter) {
            fpcm.vars.jsvars.filesLastSearch = (new Date()).getTime();
        }

        fpcm.ajax.get('filelist', {
            data: {
                mode: fpcm.vars.jsvars.fmgrMode,
                page: page,
                filter: filter
            },
            execDone: function (result) {

                fpcm.ui.assignHtml("#tabs-files-list-content", result);
                fpcm.filemanager.initJqUiWidgets();
                var fpcmRFDinterval = setInterval(function(){
                    if (jQuery('#fpcm-filelist-images-finished').length == 1) {
                        fpcm.ui.showLoader(false);
                        clearInterval(fpcmRFDinterval);
                        if (page) {
                            jQuery(window).scrollTop(0);
                        }
                        return false;
                    }
                }, 250);

            }
        });
        
        return false;
    },
    
    initFilesSearch: function() {

        jQuery('#opensearch').click(function () {

            fpcm.ui.selectmenu('.fpcm-ui-input-select-filesearch', {
                width: '100%',
                appendTo: '#fpcm-dialog-files-search'
            });

            fpcm.ui.autocomplete('#articleId', {
                source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
                appendTo: '#fpcm-dialog-files-search',
                minLength: 3
            });

            fpcm.ui.dialog({
                id      : 'files-search',
                dlWidth: fpcm.ui.getDialogSizes(top, 0.75).width,
                resizable: true,
                title    : fpcm.ui.translate('ARTICLES_SEARCH'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: "ui-icon-check",
                        class: 'fpcm-ui-button-primary',
                        click: function() {                            
                            var sfields = jQuery('.fpcm-files-search-input');
                            var sParams = {};
                            var el = {};

                            jQuery.each(sfields, function( key, obj ) {
                                el = jQuery(obj);
                                sParams[el.attr('name')] = el.val();
                            });

                            fpcm.filemanager.startFilesSearch(sParams);
                            jQuery(this).dialog('close');
                        }
                    },                    
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                        
                        click: function() {
                            jQuery(this).dialog('close');
                        }
                    }                            
                ],
                dlOnOpen: function( event, ui ) {
                    jQuery('#text').focus();
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

        fpcm.ui.showLoader(true);
        fpcm.filemanager.reloadFiles(1, sParams);        
    }
};