/**
 * FanPress CM Article list Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.articles = {

    init: function() {

        fpcm.worker.postMessage({
            namespace: 'articles',
            function: 'loadArticles',
            id: 'articles.loadArticles',
            param: {
                page: fpcm.vars.jsvars.listPage
            }
        });

        fpcm.articles.initArticleSearch();

        fpcm.dom.fromId('massEdit').click(function () {

            fpcm.system.initMassEditDialog('articles/massedit', 'articles-massedit', fpcm.articles, {
                onSuccess: function () {
                    fpcm.articles.loadArticles({
                        loader: true
                    });
                }
            });

            fpcm.dom.fromId('categories').selectize({
                placeholder: fpcm.ui.translate('EDITOR_CATEGORIES_SEARCH'),
                searchField: ['text', 'value'],
                plugins: ['remove_button']
            });

            return false;
        });
    },
    
    assignActions: function() {
        
        var action = fpcm.dom.fromId('action').val();

        if (action == 'newtweet') {
            fpcm.articles.articleActionsTweet();
            return -1;
        }

        if (action == 'articlecache') {
            fpcm.system.clearCache({
                cache: fpcm.vars.jsvars.artCacheMod,
                objid: 0
            });
            fpcm.articles.resetActionsMenu();
            return -1;
        }

        if (action == 'delete') {
            fpcm.articles.deleteMultipleArticle();
            return -1;
        }

        return true;
    },
    
    initArticleSearch: function() {

        fpcm.dom.fromId('opensearch').click(function () {

            var sDlg = fpcm.ui.dialog({
                id: 'articles-search',
                title: 'ARTICLES_SEARCH',
                closeButton: true,
                dlButtons: [
                    {
                        text: 'ARTICLE_SEARCH_START',
                        icon: 'search',
                        primary: true,
                        clickClose: true,
                        click: function() {                            
                            let _filter = {};
                            _filter = fpcm.ui.getValuesByClass('fpcm-articles-search-input');
                            _filter.combinations = fpcm.ui.getValuesByClass('fpcm-ui-input-select-articlesearch-combination');

                            if (((new Date()).getTime() - fpcm.vars.jsvars.articlesLastSearch) < 10000) {
                                fpcm.ui.addMessage({
                                    type: 'error',
                                    txt : fpcm.ui.translate('SEARCH_WAITMSG')
                                });
                                return false;
                            }

                            fpcm.articles.loadArticles({
                                filter: _filter
                            });

                            fpcm.vars.jsvars.articlesLastSearch = (new Date()).getTime();                            
                        }
                    },                    
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_RESET'),
                        icon: "undo" ,
                        clickClose: true,
                        click: function() {
                            fpcm.ui.relocate('self');
                        }
                    }                          
                ],
                dlOnOpen: function( event, ui ) {
                    fpcm.dom.setFocus('text');
                }
            });

            return false;
        });

    },
    
    loadArticles: function(_params) {

        if (!_params) {
            _params = {};
        }

        let _fnParams = {
            mode: fpcm.vars.jsvars.listMode,
            page: _params.page !== undefined ? parseInt(_params.page) : 1,
            module: 'articles',
            onRenderDataViewAfter: function () {
                fpcm.ui.assignCheckboxes();
                fpcm.ui.assignControlgroups();
                fpcm.articles.clearArticleCache();
                fpcm.articles.deleteSingleArticle();
            },
            onPagerNext: function () {
                fpcm.articles.loadArticles({
                    page: fpcm.vars.jsvars.pager.showNextButton,
                    loader: true
                });
                
                return true;
            },
            onPagerBack: function () {
                fpcm.articles.loadArticles({
                    page: fpcm.vars.jsvars.pager.showBackButton,
                    loader: true
                });

                return true;
            },
            onPagerSelect: function (event, ui) {

                fpcm.articles.loadArticles({
                    page: ui.value,
                    loader: true
                });

                return true;
            }
        };

        if (_params.filter instanceof Object) {
            _fnParams.filter = _params.filter;
        }

        fpcm.ajax.getItemList(_fnParams);        
    },
    
    articleActionsTweet: function() {

        fpcm.ui.confirmDialog({
            clickNoDefault: true,
            clickYes: function() {
                let ids = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
                if (ids.length == 0) {
                    fpcm.ui_loader.hide();
                    return false;
                }

                fpcm.articles.execNewTweet(ids);
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
                fpcm.articles.resetActionsMenu();
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
                                fpcm.articles.loadArticles({
                                    loader: true
                                });
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

                        fpcm.articles.resetActionsMenu();

                        if (result.code == 1) {
                            fpcm.articles.loadArticles({
                                loader: true
                            });
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
        fpcm.ui.resetSelectMenuSelection('action');
        return true;
    }
};