/**
 * FanPress CM Filemanager Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.filemanager = {

    init: function() {

        if (fpcm.vars.jsvars.fmLoadAjax) {
            this.reloadFiles();
            this.initActionButtons();
        }
        
        if (fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            this.initFilesSearch();
        }
    
        jQuery('#tabs-files-list-reload').click(function () {
            fpcm.filemanager.reloadFiles();
            return false;
        });
    },

    initJqUiWidgets: function () {
        fpcm.ui.assignCheckboxes();
        fpcm.ui.assignControlgroups();
        this.initInsertButtons();
        this.initSelectionCheckboxes();
        this.initPagination();
        jQuery('.fpcm-link-fancybox').fancybox();
    },
    
    initActionButtons : function() {

        jQuery('#btnRenameFiles').click(function () {
            if (fpcm.ui.langvarExists('FILE_LIST_RENAME_NEWNAME')) {
                fpcm.ui.showLoader(false);
                return true;
            }

            var newName = prompt(fpcm.ui.translate('FILE_LIST_RENAME_NEWNAME'), '');
            if (!newName || newName == '') {
                jQuery(this).addClass('fpcm-noloader');
                fpcm.ui.showLoader(false);
                return false;
            }

            jQuery('#newfilename').val(newName);
        });

        
        this.initSelectionCheckboxes();
    },
    
    initInsertButtons: function () {
        jQuery('.fpcm-filelist-tinymce-thumb').click(function () {
            var url   = jQuery(this).attr('href');
            var title = jQuery(this).attr('imgtxt');  

            if (fpcm.vars.jsvars.editorType == 1) {
                if (parent.fileOpenMode == 1) {
                    parent.document.getElementById('linksurl').value  = url;
                    parent.document.getElementById('linkstext').value = title;
                }            
                if (parent.fileOpenMode == 2) {
                    parent.document.getElementById('imagespath').value = url;
                    parent.document.getElementById('imagesalt').value  = title;                
                }

                window.parent.jQuery("#fpcm-dialog-editor-html-filemanager").dialog('close');
                window.parent.jQuery('#fpcm-dialog-editor-html-filemanager').empty();
            } else {
                top.tinymce.activeEditor.windowManager.getParams().oninsert(url, { alt: title, text: title });
                top.tinymce.activeEditor.windowManager.close();
            }

            return false;
        });

        jQuery('.fpcm-filelist-tinymce-full').click(function () {
            var url   = jQuery(this).attr('href');
            var title = jQuery(this).attr('imgtxt');

            if (fpcm.vars.jsvars.editorType == 1) {
                if (parent.fileOpenMode == 1) {
                    parent.document.getElementById('linksurl').value  = url;
                    parent.document.getElementById('linkstext').value = title;
                }            
                if (parent.fileOpenMode == 2) {
                    parent.document.getElementById('imagespath').value = url;
                    parent.document.getElementById('imagesalt').value  = title;
                }

                window.parent.jQuery("#fpcm-dialog-editor-html-filemanager").dialog('close');
                window.parent.jQuery('#fpcm-dialog-editor-html-filemanager').empty();
            } else {
                top.tinymce.activeEditor.windowManager.getParams().oninsert(url, { alt: title, text: title });
                top.tinymce.activeEditor.windowManager.close();
            }

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
    
    initSelectionCheckboxes: function() {

        fpcm.ui.checkboxradio(
            '.fpcm-ui-filemanager-buttons #fpcmselectall', {}, function() {
                fpcm.filemanager.refreshSingleCheckboxes();
        });
        
        this.refreshSingleCheckboxes();
    },
    
    refreshSingleCheckboxes: function() {
        jQuery('.fpcm-filelist-actions-checkbox').find('input[type="checkbox"]').checkboxradio({
            showLabel: false
        }).checkboxradio('refresh');  
    },
    
    initPagination: function() {

        fpcm.ui.selectmenu('#pageSelect', {
            select: function( event, ui ) {
                fpcm.filemanager.reloadFiles(ui.item.value);
            }
        });

        jQuery('#fpcmpagernext').click(function() {
            var page = jQuery(this).attr('href').split('&page=');
            fpcm.filemanager.reloadFiles((page[1] === undefined) ? 1 : page[1]);
            return false;
        });

        jQuery('#fpcmpagerback').click(function() {
            var page = jQuery(this).attr('href').split('&page=');
            fpcm.filemanager.reloadFiles((page[1] === undefined) ? 1 : page[1]);
            return false;
        });

    },

    reloadFiles: function (page) {

        fpcm.ui.showLoader(true);

        if (!page) {
            page = 1;
        }

        fpcm.ajax.get('filelist', {
            data: {
                mode: fpcm.vars.jsvars.fmgrMode,
                page: page
            },
            execDone: function () {

                fpcm.ui.assignHtml("#tabs-files-list-content", fpcm.ajax.getResult('filelist'));
                fpcm.filemanager.initJqUiWidgets();
                var fpcmRFDinterval = setInterval(function(){
                    if (jQuery('#fpcm-filelist-images-finished').length == 1) {
                        fpcm.ui.showLoader(false);
                        fpcm.ui.resize();
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
                            var sParams = {
                                mode    : fpcm.vars.jsvars.fmgrMode,
                                filter  : {}
                            };

                            jQuery.each(sfields, function( key, obj ) {
                                var objVal  = jQuery(obj).val();
                                var objName = jQuery(obj).attr('name');                                
                                sParams.filter[objName] = objVal;
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
        
        fpcm.ajax.post('files/search', {
            data: sParams,
            execDone: function () {
                fpcm.ui.assignHtml("#tabs-files-list-content", fpcm.ajax.getResult('files/search'));
                fpcm.ui.initJqUiWidgets();
                fpcm.filemanager.initJqUiWidgets();
                var fpcmRFDinterval = setInterval(function(){
                    if (jQuery('#fpcm-filelist-images-finished').length == 1) {
                        fpcm.ui.showLoader(false);
                        fpcm.ui.resize();
                        clearInterval(fpcmRFDinterval);
                        if (page) {
                            jQuery(window).scrollTop(0);
                        }
                        return false;
                    }
                }, 250);
            }
        });

        fpcm.vars.jsvars.filesLastSearch = (new Date()).getTime();
    }
};