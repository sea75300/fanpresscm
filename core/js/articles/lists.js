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

        fpcm.dom.bindClick('#btnSettings', function (ev, _ui, _result) {
            fpcm.ui_dialogs.settings('comments', 'settings', function () {
                fpcm.ui.relocate('self');
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