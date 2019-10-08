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
            fpcm.ui.controlgroup('.fpcm-ui-massedit-categories', {
                removeLeftBorderRadius: true
            });
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
    },
    
    assignActions: function() {
        
        var action = fpcm.dom.fromId('actionsaction').val();
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

        return true;
    },
    
    initArticleSearch: function() {
        fpcm.dom.fromId('opensearch').click(function () {

            fpcm.ui.selectmenu('.fpcm-ui-input-select-articlesearch', {
                width: '100%',
                appendTo: '#fpcm-dialog-articles-search'
            });

            fpcm.ui.selectmenu('.fpcm-ui-input-select-articlesearch-combination', {
                width: '100%',
                appendTo: '#fpcm-dialog-articles-search'
            });

            fpcm.ui.dialog({
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
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick" ,                        
                        click: function() {
                            fpcm.dom.fromTag(this).dialog('close');
                        }
                    }                            
                ],
                dlOnOpen: function( event, ui ) {
                    fpcm.dom.setFocus('#text');
                }
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

        fpcm.ui.showLoader(true);

        fpcm.ajax.post('articles/search', {
            data: sParams,
            dataType: 'json',
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

                fpcm.articlelist.clearArticleCache();
                fpcm.articlelist.deleteSingleArticle();
                fpcm.ui.showLoader(false);
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
                    fpcm.ui.showLoader(false);
                    return false;
                }

                fpcm.dom.fromTag(this).dialog('close');
                fpcm.ui.showLoader(true);
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

                fpcm.ui.showLoader(false);
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
        
        fpcm.dom.fromClass('fpcm-ui-button-delete-article-single').click(function() {

            var articleId = fpcm.dom.fromTag(this).data('articleid');
            
            fpcm.ui.confirmDialog({
                
                clickYes: function () {
                    fpcm.ui.showLoader(true);
                    fpcm.ajax.exec('articles/delete', {
                        dataType: 'json',
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
            fpcm.ui.showLoader(false);
            return false;
        }

        fpcm.ui.confirmDialog({

            clickYes: function () {
                fpcm.ui.showLoader(true);
                fpcm.ajax.exec('articles/delete', {
                    dataType: 'json',
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
    
    resetActionsMenu: function () {
        var el = fpcm.dom.fromId('actionsaction');
        el.prop('selectedIndex',0);
        el.selectmenu('refresh');
        return true;
    }
};