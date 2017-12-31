/**
 * FanPress CM Comments Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.comments = {

    init: function () {

        if (fpcmLang.searchHeadline) {
            this.initCommentSearch();
        }

        if (window.tinymce && window.fpcmCommentsEdit) {
            fpcm.editor_tinymce.create({
                language : fpcmTinyMceLang,
                plugins  : fpcmTinyMcePlugins,
                toolbar  : fpcmTinyMceToolbar,
                onInit : function(ed) { 
                    ed.on('init', function() {
                        this.getBody().style.fontSize = fpcmTinyMceDefaultFontsize;
                        jQuery(this.iframeElement).removeAttr('title');
                    });
                }    
            });
            fpcm.ui.setFocus('commentname');
        }

        fpcm.comments.assignActions();
    },

    assignActions: function() {

        jQuery('#fpcmcommentslistmassedit').click(function () {
            fpcm.system.initMassEditDialog('comments/massedit', 'comments-massedit', fpcm.comments);
            return false;
        });

        return true;
    },

    initWidgets: function(dialogId) {

        fpcm.ui.autocomplete('#moveToArticle', {
            source: fpcmAjaxActionPath + 'autocomplete&src=articles',
            appendTo: dialogId,
            minLength: 3
        });

        return true;
    },
    
    initCommentSearch: function() {

        jQuery('#fpcmcommentsopensearch').click(function () {

            fpcm.ui.selectmenu('.fpcm-ui-input-select-commentsearch', {
                width: '100%',
                appendTo: '#fpcm-dialog-comments-search'
            });

            fpcm.ui.datepicker('.fpcm-full-width-date');
            
            fpcm.ui.autocomplete('#articleId', {
                source: fpcmAjaxActionPath + 'autocomplete&src=articles',
                appendTo: '#fpcm-dialog-comments-search',
                minLength: 3
            });

            var size = fpcm.ui.getDialogSizes();

            fpcm.ui.dialog({
                id      : 'comments-search',
                dlWidth: size.width,
                resizable: true,
                title    : fpcm.ui.translate('searchHeadline'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('searchStart'),
                        icon: "ui-icon-check",                        
                        click: function() {                            
                            var sfields = jQuery('.fpcm-comments-search-input');
                            var sParams = {
                                filter: {}
                            };
                            
                            jQuery.each(sfields, function( key, obj ) {
                                var objVal  = jQuery(obj).val();
                                var objName = jQuery(obj).attr('name');                                
                                sParams.filter[objName] = objVal;
                            });

                            fpcm.comments.startCommentSearch(sParams);
                            jQuery(this).dialog('close');
                        }
                    },                    
                    {
                        text: fpcm.ui.translate('close'),
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

    startCommentSearch: function (sParams) {

        if (((new Date()).getTime() - fpcmCommentsLastSearch) < 10000) {
            fpcmJs.addAjaxMassage('error', fpcm.ui.translate('searchWaitMsg'));            
            return false;
        }

        fpcm.ui.showLoader(true);
        
        fpcm.ajax.post('comments/search', {
            data: sParams,
            execDone: function () {
                fpcm.ui.showLoader(false);
                fpcm.ui.assignHtml('#tabs-comments-active', fpcm.ajax.getResult('comments/search'));
                fpcmJs.assignButtons();
                fpcm.comments.initCommentSearch();
                fpcm.comments.assignActions();
                fpcm.ui.assignSelectmenu();
                fpcm.ui.resize();
            }
        });

        fpcmCommentsLastSearch = (new Date()).getTime();
    }

};