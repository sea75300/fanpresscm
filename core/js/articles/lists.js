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
        
        fpcm.dom.bindClick('#massEdit', function (_e, _ui) {

            fpcm.system.initMassEditDialog('articles/massedit', 'articles-massedit', fpcm.articles, {
                onSuccess: function () {
                    fpcm.articles.loadArticles({
                        loader: true
                    });
                }
            });

        });

    },
    
    initWidgets: function() {
        fpcm.dom.fromId('categories').selectize({
            placeholder: fpcm.ui.translate('EDITOR_CATEGORIES_SEARCH'),
            searchField: ['text', 'value'],
            plugins: ['remove_button']
        });
    },
    
    initArticleSearch: function() {
        
        fpcm.dom.bindClick('#opensearch', function (_e, _ui) {

            fpcm.ui_dialogs.create({
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
                            _filter = fpcm.dom.getValuesByClass('fpcm-articles-search-input');
                            _filter.combinations = fpcm.dom.getValuesByClass('fpcm-ui-input-select-articlesearch-combination');

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
                        text: fpcm.ui.translate('GLOBAL_RESET'),
                        icon: "filter-circle-xmark" ,
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

        let ids = fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
        if (ids.length == 0) {
            return false;
        }

        fpcm.ui_dialogs.confirm({
            clickNoDefault: true,
            clickYes: function() {

                fpcm.articles.execNewTweet(ids);
                fpcm.dom.resetCheckboxesByClass('fpcm-ui-list-checkbox');
                
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
        
        fpcm.dom.bindClick('.fpcm-article-cache-clear', function (_e, _ui) {

            fpcm.system.clearCache({
                cache: _ui.dataset.cache ? _ui.dataset.cache : '',
                objid: _ui.dataset.objid ? _ui.dataset.objid : 0
            });

        });

    },

    deleteSingleArticle: function() {
        
        fpcm.dom.bindClick('.fpcm-ui-button-delete-article-single', function (_e, _ui) {

            fpcm.ui_dialogs.confirm({
                
                clickYes: function () {
                    fpcm.ajax.exec('articles/delete', {
                        dataType: 'json',
                        pageToken: 'articles/delete',
                        data: {
                            id: _ui.dataset.articleid
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
                }                
            });

        });
    },

    deleteMultipleArticle: function() {

        var articleIds = fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
        if (articleIds.length == 0) {
            fpcm.ui_loader.hide();
            return false;
        }

        fpcm.ui_dialogs.confirm({

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
                        
                        fpcm.dom.resetCheckboxesByClass('fpcm-ui-list-checkbox');
                    }
                });
            }

        });

        return true;

    },

    clearMultipleArticleCache: function() {

        fpcm.system.clearCache({
            cache: fpcm.vars.jsvars.artCacheMod,
            objid: 0
        });
        
        fpcm.dom.resetCheckboxesByClass('fpcm-ui-list-checkbox');
    },
    
    resetActionsMenu: function () {
        fpcm.dom.resetValuesByIdsSelect('action');
        return true;
    }
};