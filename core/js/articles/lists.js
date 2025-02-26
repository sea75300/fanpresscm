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

    categoryMs: null,

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

                    fpcm.dom.resetValuesByIdsSelect(['meUserid', 'mePinned', 'meDraft','meApproval', 'meComments', 'meArchived']);
                }
            });

        });

    },
    
    initWidgets: function() {
        
        if (!fpcm.articles.categoryMs) {
            fpcm.articles.categoryMs = fpcm.ui.multiselect('categories');
        }
        
    },
    
    onMassEditorDialogClose: function () {
        
        if (!fpcm.articles.categoryMs) {
            return false;
        }
        
        fpcm.articles.categoryMs.clear();
        fpcm.articles.categoryMs = null;
        return true;
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
                fpcm.articles.tweetSingleActions();
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

        fpcm.articles._showTweetDialog(ids);
    },
    
    _showTweetDialog: function(_ids) {

        if (!_ids || _ids.length == 0) {
            return false;
        }

        fpcm.ui_dialogs.create({
            title: 'EDITOR_TWEET_TEXT',
            closeButton: true,
            content: `<div class="row mb-5"><div class="col flex-grow-1">${fpcm.vars.jsvars.newTweetFields[0]}</div><div class="col-auto mb-3 align-self-center">${fpcm.vars.jsvars.newTweetFields[1]}</div></div>`,
            dlOnClose: function() {
                fpcm.dom.resetCheckboxesByClass('fpcm-ui-list-checkbox');
                fpcm.dom.resetValuesByIdsString(['twitterText'], '');
            },
            dlOnOpenAfter: function() {
                
                let _textEL = fpcm.dom.fromId('twitterText');
                
                fpcm.dom.bindClick('#twitterReplacements li > a.dropdown-item', function (_e, _ui) {

                    if (!_ui.dataset.var) {
                        return false;
                    }

                    let currentText = _textEL.val();
                    let currentpos = fpcm.dom.fromTag(_textEL).prop('selectionStart');

                    _textEL.val(currentText.substring(0, currentpos) + _ui.dataset.var +  currentText.substring(currentpos));
                });
            },
            dlButtons: [
                {
                    text: 'ARTICLE_LIST_NEWTWEET',
                    icon: 'brands fa-twitter',
                    primary: true,
                    clickClose: true,
                    click: function(_dlg, _btn) {

                        _btn.childNodes[0].className = '';
                        _btn.childNodes[0].innerHTML = '<div class="spinner-border spinner-border-sm text-light" role="status"></div';
                        
                        let _text = fpcm.dom.fromId('twitterText').val();
                        fpcm.articles.execNewTweet(_ids, _text);
                    }
                }
            ]
            
        });        
        
    },
    
    execNewTweet: function(_ids, _text) {

        fpcm.ajax.post('articles/tweet', {
            data    : {
                ids: fpcm.ajax.toJSON(_ids),
                text: _text
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
    
    tweetSingleActions: function() {
        
        fpcm.dom.bindClick('.fpcm-ui-article-twitter-single', function (_e, _ui) {
            fpcm.articles._showTweetDialog([_ui.dataset.articleid]);
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