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

    init: function() {

        if (fpcm.vars.jsvars.loadAjax) {
            fpcm.filemanager.reloadFiles();
        }

        if (fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            fpcm.filemanager.initFilesSearch();
        }
    
        jQuery('#tabs-files-list-reload').click(function () {
            fpcm.filemanager.reloadFiles();
            return false;
        });

        if (fpcm.vars.jsvars.fmgrMode === 1) {
            fpcm.ui.checkboxradio('.fpcm-ui-listeview-setting', {}, function () {

                fpcm.filemanager.activeView = jQuery(this).val();
                fpcm.ajax.post('setconfig', {
                    data: {
                        var: 'file_view',
                        value: fpcm.filemanager.activeView
                    }
                });

                fpcm.filemanager.reloadFiles();
            });

            fpcm.ui.tabs('#fpcm-files-tabs', {
                beforeActivate: function( event, ui ) {

                    var hideButtons = jQuery(ui.oldTab).attr('data-toolbar-buttons');
                    var showButtons = jQuery(ui.newTab).attr('data-toolbar-buttons');

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
        
    },

    initJqUiWidgets: function () {
        fpcm.ui.assignCheckboxes();
        fpcm.ui.assignControlgroups();
        fpcm.filemanager.initInsertButtons();
        fpcm.filemanager.initRenameButtons();
        fpcm.filemanager.initDeleteButtons();
        fpcm.filemanager.refreshSingleCheckboxes();
        fpcm.filemanager.initPagination();
        jQuery('.fpcm-link-fancybox').fancybox();
    },
    
    closeRenameDialog: function() {
        jQuery('#newfilename').val('');
        jQuery('#newFilenameDialog').val('');
    },
    
    initInsertButtons: function () {

        jQuery('.fpcm-filelist-tinymce-thumb').click(function () {
            parent.fpcm.editor.insertThumbByEditor(jQuery(this).attr('href'), jQuery(this).attr('data-imgtext'));
            return false;
        });

        jQuery('.fpcm-filelist-tinymce-full').click(function () {
            parent.fpcm.editor.insertFullByEditor(jQuery(this).attr('href'), jQuery(this).attr('data-imgtext'));
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

    refreshSingleCheckboxes: function() {
        jQuery('.fpcm-filelist-actions-checkbox').find('input[type="checkbox"]').checkboxradio({
            showLabel: false
        }).checkboxradio('refresh');  
    },

    initRenameButtons: function() {
        jQuery('.fpcm-filelist-rename').click(function () {

            if (!fpcm.ui.langvarExists('FILE_LIST_RENAME_NEWNAME')) {
                console.log('FILE_LIST_RENAME_NEWNAME');
                return false;
            }

            var selectedFile = jQuery(this).attr('data-file');
            jQuery('#newFilenameDialog').val(jQuery(this).attr('data-oldname'));

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
            fpcm.ajax.exec('files/delete', {
                data: {
                    filename: jQuery(this).attr('data-file')
                },
                execDone: function () {
                    fpcm.filemanager.reloadFiles();
                    fpcm.ui.showLoader();
                }
            });

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

        fpcm.ui.showLoader(true);

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
                filter: filter,
                view: fpcm.filemanager.activeView ? fpcm.filemanager.activeView : null
            },
            execDone: function () {

                fpcm.ui.assignHtml("#tabs-files-list-content", fpcm.ajax.getResult('filelist'));
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

            fpcm.ui.datepicker('.fpcm-full-width-date');
            
            fpcm.ui.autocomplete('#articleId', {
                source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
                appendTo: '#fpcm-dialog-files-search',
                minLength: 3
            });

            var size = fpcm.ui.getDialogSizes();

            fpcm.ui.dialog({
                id      : 'files-search',
                dlWidth: size.width,
                resizable: true,
                title    : fpcm.ui.translate('ARTICLES_SEARCH'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: "ui-icon-check",                        
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