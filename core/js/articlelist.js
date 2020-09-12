/**
 * FanPress CM Article list Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.articlelist = {

    init: function() {
        fpcm.dom.fromId('massEdit').click(function () {
            fpcm.system.initMassEditDialog('articles/massedit', 'articles-massedit', fpcm.articlelist);
            return false;
        });

        fpcm.articlelist.initArticleSearch();
    },
    
    initAfter: function() {
        fpcm.dataview.render('articlelist', {
            onRenderAfter: function() {
                fpcm.ui.assignCheckboxes();
                fpcm.ui.assignControlgroups();
            }
        });

        fpcm.articlelist.clearArticleCache();
        fpcm.articlelist.deleteSingleArticle();
        
        fpcm.dom.fromId('categories').selectize({
            placeholder: fpcm.ui.translate('EDITOR_CATEGORIES_SEARCH'),
            searchField: ['text', 'value'],
            plugins: ['remove_button']
        });
    },
    
    assignActions: function() {
        
        var action = fpcm.dom.fromId('action').val();

        if (action == 'newtweet') {
            fpcm.articlelist.articleActionsTweet();
            return -1;
        }

        if (action == 'articlecache') {
            fpcm.system.clearCache({
                cache: fpcm.vars.jsvars.artCacheMod,
                objid: 0
            });
            fpcm.articlelist.resetActionsMenu();
            return -1;
        }

        if (action == 'delete') {
            fpcm.articlelist.deleteMultipleArticle();
            return -1;
        }

        if (action == 'trash') {
            fpcm.articlelist.emptyTrash();
            return -1;
        }

        if (action == 'restore') {
            fpcm.articlelist.restoreFromTrash();
            return -1;
        }

        return true;
    },
    
    initArticleSearch: function() {

        fpcm.dom.fromId('opensearch').click(function () {

            var sDlg = fpcm.ui.dialog({
                id      : 'articles-search',
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
                                filter: fpcm.ui.getValuesByClass('fpcm-articles-search-input')
                            };
                            
                            sParams.filter.combinations = fpcm.ui.getValuesByClass('fpcm-ui-input-select-articlesearch-combination');
                            fpcm.articlelist.startSearch(sParams);
                            fpcm.dom.fromTag(this).dialog('close');
                        }
                    },                    
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_RESET'),
                        icon: "ui-icon-refresh" ,                        
                        click: function() {
                            fpcm.ui.relocate('self');
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick" ,                        
                        click: function() {
                            fpcm.dom.fromTag(this).dialog('close');
                        }
                    }                            
                ],
                dlOnOpen: function( event, ui ) {
                    fpcm.dom.setFocus('text');
                }
            });

            fpcm.ui.selectmenu('.fpcm-ui-input-select-articlesearch', {
                width: '100%',
                appendTo: '#' + sDlg.attr('id')
            });

            fpcm.ui.selectmenu('.fpcm-ui-input-select-articlesearch-combination', {
                width: '100%',
                appendTo: '#' + sDlg.attr('id')
            });

            return false;
        });

    },
    
    startSearch: function (sParams) {

        if (((new Date()).getTime() - fpcm.vars.jsvars.articlesLastSearch) < 10000) {
            fpcm.ui.addMessage({
                type: 'error',
                txt : fpcm.ui.translate('SEARCH_WAITMSG')
            });
            return false;
        }

        fpcm.ajax.post('articles/search', {
            data: sParams,
            dataType: 'json',
            execDone: function (result) {

                fpcm.ui.mainToolbar.find('.fpcm-ui-pager-element').addClass('fpcm-ui-hidden');
                fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');

                if (result.message) {
                    fpcm.ui.addMessage(result.message);
                }

                fpcm.vars.jsvars.dataviews[result.dataViewName] = result.dataViewVars;
                fpcm.dataview.updateAndRender(result.dataViewName, {
                    onRenderAfter: function () {
                        fpcm.ui.assignCheckboxes();
                        fpcm.ui.assignControlgroups();
                    }
                });

                fpcm.articlelist.clearArticleCache();
                fpcm.articlelist.deleteSingleArticle();
                fpcm.dom.fromId('opensearch').addClass('fpcm-ui-button-primary');
            }
        });

        fpcm.vars.jsvars.articlesLastSearch = (new Date()).getTime();
    },
    
    articleActionsTweet: function() {
        fpcm.ui.confirmDialog({
            clickNoDefault: true,
            clickYes: function() {
                var articleIds = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
                if (articleIds.length == 0) {
                    fpcm.ui_loader.hide();
                    return false;
                }

                fpcm.dom.fromTag(this).dialog('close');
                fpcm.articlelist.execNewTweet(articleIds);
            }
        });

    },
    
    execNewTweet: function(articleIds) {

        fpcm.ajax.post('articles/tweet', {
            data    : {
                ids : fpcm.ajax.toJSON(articleIds)
            },
            async   : false,
            dataType: 'json',
            execDone: function(result) {
                fpcm.articlelist.resetActionsMenu();
                if (result.notice != 0) {
                    fpcm.ui.addMessage({
                        type: 'notice',
                        txt : fpcm.ui.translate(result.notice)
                    });
                }

                if (result.error != 0) {
                    fpcm.ui.addMessage({
                        type: 'error',
                        txt : fpcm.ui.translate(result.error)
                    });
                }

            }
        });

    },

    clearArticleCache: function() {
        
        fpcm.dom.fromClass('fpcm-article-cache-clear').unbind('click');
        fpcm.dom.fromClass('fpcm-article-cache-clear').click(function() {
            
            var obj = fpcm.dom.fromTag(this);
            
            var cache = obj.data('cache') ? obj.data('cache') : '';
            var objid = obj.data('objid') ? obj.data('objid') : 0;

            fpcm.system.clearCache({
                cache: cache,
                objid: objid
            });
            
            return false;
        });

    },

    deleteSingleArticle: function() {
        
        fpcm.dom.fromClass('fpcm-ui-button-delete-article-single').unbind('click');
        fpcm.dom.fromClass('fpcm-ui-button-delete-article-single').click(function() {

            var articleId = fpcm.dom.fromTag(this).data('articleid');
            
            fpcm.ui.confirmDialog({
                
                clickYes: function () {
                    fpcm.ajax.exec('articles/delete', {
                        dataType: 'json',
                        pageToken: 'articles/delete',
                        data: {
                            id: articleId
                        },
                        execDone: function (result) {

                            if (result.code == 1) {
                                window.location.reload();
                                return true;
                            }

                            fpcm.ui.addMessage({
                                txt: 'DELETE_FAILED_ARTICLE',
                                type: 'error',
                            }, true);
                        }
                    });
                    fpcm.dom.fromTag(this).dialog("close");
                },
                clickNoDefault: true
                
            });

            return false;
        });

    },

    deleteMultipleArticle: function() {

        var articleIds = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
        if (articleIds.length == 0) {
            fpcm.ui_loader.hide();
            return false;
        }

        fpcm.ui.confirmDialog({

            clickYes: function () {
                fpcm.ajax.exec('articles/delete', {
                    dataType: 'json',
                    pageToken: 'articles/delete',
                    data: {
                        id: articleIds,
                        multiple: 1
                    },
                    execDone: function (result) {

                        fpcm.articlelist.resetActionsMenu();

                        if (result.code == 1) {
                            window.location.reload();
                            return true;
                        }

                        fpcm.ui.addMessage({
                            txt: 'DELETE_FAILED_ARTICLE',
                            type: 'error',
                        }, true);
                    }
                });

                fpcm.dom.fromTag(this).dialog("close");
            },
            clickNoDefault: true

        });

        return true;

    },

    emptyTrash: function() {

        fpcm.system.emptyTrash({
            fn: 'clearArticles'
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
            fn: 'restoreArticles',
            ids: ids
        });

        return true;

    },
    
    resetActionsMenu: function () {
        fpcm.ui.resetSelectMenuSelection('#action');
        return true;
    }
};