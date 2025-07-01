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
    
        fpcm.comments.assignActionsList();
    },
    
    initAfter: function() {
        
        if (!fpcm.dataview) {
            return false;
        }
        
        if (fpcm.dataview.exists('commenttrash')) {
            fpcm.dataview.render('commenttrash');            
            return true;
        }
        
        if (fpcm.dataview.exists('commentlist')) {
            fpcm.dataview.render('commentlist');
            fpcm.comments.deleteSingleArticle();
        }
        
        
    },

    assignActionsList: function() {
            
        if (fpcm.vars.jsvars.activeTab) {
            fpcm.vars.jsvars.massEdit = {
                relocateParams: '&rg=' + fpcm.vars.jsvars.activeTab
            }
        }

        fpcm.dom.bindClick('#massEdit', function () {
            fpcm.system.initMassEditDialog('comments/massedit', 'comments-massedit', fpcm.comments);
            return false;
        });
        
        fpcm.comments.deleteSingleArticle();
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

    initWidgets: function() {

        fpcm.ui.autocomplete('#moveToArticle', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
            minLength: 3
        });

        return true;
    },
    
    initCommentSearch: function() {

        fpcm.dom.fromId('opensearch').unbind('click');
        fpcm.dom.fromId('opensearch').click(function () {

            fpcm.ui_dialogs.create({
                id: 'comments-search',
                title: 'ARTICLES_SEARCH',
                closeButton: true,
                dlButtons  : [
                    {
                        text: 'ARTICLE_SEARCH_START',
                        icon: 'search',
                        primary: true,
                        clickClose: true,
                        click: function() {
                            
                            var sParams = {
                                mode: fpcm.vars.jsvars.articleSearchMode,
                                filter: fpcm.dom.getValuesByClass('fpcm-comments-search-input')
                            };
                            
                            sParams.filter.combinations = fpcm.dom.getValuesByClass('fpcm-ui-input-select-commentsearch-combination');

                            fpcm.comments.startCommentSearch(sParams);
                        }
                    },                    
                    {
                        text: 'GLOBAL_RESET',
                        icon: "filter-circle-xmark" ,
                        clickClose: true,
                        click: function() {
                            fpcm.ui.relocate('self');
                        }
                    }              
                ],
                dlOnOpenAfter: function() {            
                    fpcm.ui.autocomplete('#articleId', {
                        source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
                        minLength: 3
                    });

                    fpcm.dom.fromId('text').focus();
                }
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

                if (result.message) {
                    fpcm.ui.addMessage(result.message);
                }

                fpcm.ui.mainToolbar.find('.fpcm-ui-pager-element').addClass('fpcm-ui-hidden');
                fpcm.vars.jsvars.dataviews[result.dataViewName] = result.dataViewVars;
                fpcm.dataview.updateAndRender(result.dataViewName);
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
    
    deleteSingleArticle: function() {
        
        fpcm.dom.bindClick('.fpcm-ui-button-delete-comment-single', function (_e, _ui) {

            fpcm.ui_dialogs.confirm({
                
                clickYes: function () {
                    fpcm.ajax.exec('comments/delete', {
                        dataType: 'json',
                        pageToken: 'comments/delete',
                        data: {
                            id: _ui.dataset.comid
                        },
                        execDone: function (result) {
                            return fpcm.commentCallbacks.deleteCallback(result);
                        }
                    });
                }                
            });

        });
    },
    
    deleteMultipleArticle: function() {

        var _comIds = fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
        if (_comIds.length === 0) {
            fpcm.ui_loader.hide();
            return false;
        }

        fpcm.ui_dialogs.confirm({

            clickYes: function () {
                fpcm.ajax.exec('comments/delete', {
                    dataType: 'json',
                    pageToken: 'comments/delete',
                    data: {
                        id: _comIds,
                        multiple: 1
                    },
                    execDone: function (result) {
                        return fpcm.commentCallbacks.deleteCallback(result);
                    }
                });
            }

        });

        return true;    
    },

    restoreFromTrash: function() {

        var ids = fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
        if (ids.length == 0) {
            fpcm.ui_loader.hide();
            return false;
        }

        fpcm.system.emptyTrash({
            fn: 'restoreComments',
            ids: ids
        });

        return true;

    },
    
    resetActionsMenu: function () {
        fpcm.dom.resetValuesByIdsSelect('ids');
        return true;
    }

};