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

        if (fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            this.initCommentSearch();
        }

        if (window.tinymce && fpcm.vars.jsvars.commentsEdit) {
            fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);
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
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
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
                source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
                appendTo: '#fpcm-dialog-comments-search',
                minLength: 3
            });

            var size = fpcm.ui.getDialogSizes();

            fpcm.ui.dialog({
                id      : 'comments-search',
                dlWidth: size.width,
                resizable: true,
                title    : fpcm.ui.translate('ARTICLES_SEARCH'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
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

    startCommentSearch: function (sParams) {

        if (((new Date()).getTime() - fpcmCommentsLastSearch) < 10000) {
            fpcm.ui.addMessage({
                type: 'error',
                txt : fpcm.ui.translate('SEARCH_WAITMSG')
            });
            return false;
        }

        fpcm.ui.showLoader(true);
        
        fpcm.ajax.post('comments/search', {
            data: sParams,
            execDone: function () {
                fpcm.ui.showLoader(false);
                fpcm.ui.assignHtml('#tabs-comments-active', fpcm.ajax.getResult('comments/search'));
                fpcm.ui.assignButtons();
                fpcm.comments.initCommentSearch();
                fpcm.comments.assignActions();
                fpcm.ui.assignSelectmenu();
                fpcm.ui.resize();
            }
        });

        fpcmCommentsLastSearch = (new Date()).getTime();
    }

};