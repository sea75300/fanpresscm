/**
 * FanPress CM Comments Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.comments = {

    init: function () {

        if (fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            fpcm.comments.initCommentSearch();
        }
    
        fpcm.ui.checkboxradio('.fpcm-ui-comments-status');
        fpcm.comments.assignActionsList();

        if (parent.fpcm.editor && parent.fpcm.editor.editorTabs && fpcm.vars.jsvars.reloadList) {
            parent.fpcm.editor.editorTabs.tabs('load', 2);
        }
    },
    
    initAfter: function() {
        
        if (fpcm.dataview && fpcm.dataview.exists('commenttrash')) {
            fpcm.dataview.render('commenttrash', {
                onRenderAfter: function() {
                    fpcm.ui.assignCheckboxes();
                    fpcm.ui.assignControlgroups();
                }
            });
            
            return true;
        }
        
        if (fpcm.dataview && fpcm.dataview.exists('commentlist')) {
            fpcm.dataview.render('commentlist', {
                onRenderAfter: function() {
                    fpcm.ui.assignCheckboxes();
                    fpcm.ui.assignControlgroups();
                }
            });
        }
        
    },

    assignActionsList: function() {
            
        if (fpcm.vars.jsvars.activeTab) {
            fpcm.vars.jsvars.massEdit = {
                relocateParams: '&rg=' + fpcm.vars.jsvars.activeTab
            }
        }

        fpcm.dom.fromId('massEdit').unbind('click');
        fpcm.dom.fromId('massEdit').click(function () {
            fpcm.system.initMassEditDialog('comments/massedit', 'comments-massedit', fpcm.comments);
            return false;
        });

        return true;
    },

    assignActions: function() {
        
        var action = fpcm.dom.fromId('action').val();
        if (!fpcm.comments[action]) {
            return -1;
        }
        
        fpcm.comments[action]();
        return -1;
    },

    initWidgets: function(dialogId) {

        fpcm.ui.autocomplete('.fpcm-ui-input-articleid', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
            appendTo: dialogId,
            minLength: 3
        });

        return true;
    },
    
    initCommentSearch: function() {

        fpcm.dom.fromId('opensearch').unbind('click');
        fpcm.dom.fromId('opensearch').click(function () {

            var sDlg = fpcm.ui.dialog({
                id      : 'comments-search',
                resizable: true,
                title    : fpcm.ui.translate('ARTICLES_SEARCH'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: 'ui-icon-check',
                        class: 'fpcm-ui-button-primary',
                        click: function() {
                            
                            var sParams = {
                                mode: fpcm.vars.jsvars.articleSearchMode,
                                filter: fpcm.ui.getValuesByClass('fpcm-comments-search-input')
                            };
                            
                            sParams.filter.combinations = fpcm.ui.getValuesByClass('fpcm-ui-input-select-commentsearch-combination');

                            fpcm.comments.startCommentSearch(sParams);
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
                dlOnOpen: function() {
                    fpcm.dom.fromId('text').focus();
                }
            });

            fpcm.ui.selectmenu('.fpcm-ui-input-select-commentsearch', {
                width: '100%',
                appendTo: '#' + sDlg.attr('id')
            });
            
            fpcm.ui.selectmenu('.fpcm-ui-input-select-commentsearch-combination', {
                width: '100%',
                appendTo: '#' + sDlg.attr('id'),
            });
            
            fpcm.ui.autocomplete('#articleId', {
                source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
                appendTo: '#' + sDlg.attr('id'),
                minLength: 3
            });

            return false;
        });

    },

    startCommentSearch: function (sParams) {

        if (((new Date()).getTime() - fpcm.vars.jsvars.commentsLastSearch) < 10000) {
            fpcm.ui.addMessage({
                type: 'error',
                txt : fpcm.ui.translate('SEARCH_WAITMSG')
            });
            return false;
        }

        fpcm.ajax.post('comments/search', {
            data: sParams,
            execDone: function (result) {
                fpcm.ui.mainToolbar.find('.fpcm-ui-pager-element').addClass('fpcm-ui-hidden');
                fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');
                fpcm.vars.jsvars.dataviews[result.dataViewName] = result.dataViewVars;
                fpcm.dataview.updateAndRender(result.dataViewName, {
                    onRenderAfter: function () {
                        fpcm.ui.assignCheckboxes();
                        fpcm.ui.assignControlgroups();
                    }
                });
            }
        });

        fpcm.vars.jsvars.commentsLastSearch = (new Date()).getTime();
    },

    emptyTrash: function() {

        fpcm.system.emptyTrash({
            fn: 'clearComments'
        });

        return true;

    },

    restoreFromTrash: function() {

        var ids = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
        if (ids.length == 0) {
            fpcm.ui_loader.hide();
            return false;
        }

        fpcm.system.emptyTrash({
            fn: 'restoreComments',
            ids: ids
        });

        return true;

    }

};