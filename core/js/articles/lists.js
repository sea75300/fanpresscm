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
        
        fpcm.articles.loadArticles({
            page: fpcm.vars.jsvars.listPage
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

            return false;
        });

        fpcm.dom.fromId('categories').selectize({
            placeholder: fpcm.ui.translate('EDITOR_CATEGORIES_SEARCH'),
            searchField: ['text', 'value'],
            plugins: ['remove_button']
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
                id      : 'articles-search',
                resizable: true,
                title    : fpcm.ui.translate('ARTICLES_SEARCH'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: 'ui-icon-check',
                        class: 'fpcm-ui-button-primary',
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
                            fpcm.dom.fromTag(this).dialog('close');
                        }
                    },                    
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_RESET'),
                        icon: "ui-icon-refresh" ,                        
                        click: function() {
                            fpcm.articles.loadArticles({
                                loader: true
                            });
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
    
    loadArticles: function(_params) {

        if (!_params) {
            _params = {};
        }

        let _data = {
            mode: fpcm.vars.jsvars.listMode,
            page: _params.page !== undefined ? parseInt(_params.page) : 1,    
        };
        
        if (_params.filter instanceof Object) {
            _data.filter = _params.filter;
        }

        fpcm.ajax.post('articles/lists', {
            data: _data,
            quiet: _data.filter !== undefined || _params.loader ? false : true,
            execDone: function (result)
            {
                if (!result) {
                    return false;
                }
                
                if (!result.dataViewName && result.txt && result.type) {
                    fpcm.ui.addMessage(result);
                    return false;
                }
                
                fpcm.vars.jsvars.dataviews[result.dataViewName] = result.dataViewVars;
                fpcm.dataview.updateAndRender(result.dataViewName, {
                    onRenderAfter: function() {
                        fpcm.ui.assignCheckboxes();
                        fpcm.ui.assignControlgroups();

                        fpcm.articles.clearArticleCache();
                        fpcm.articles.deleteSingleArticle();
                    }
                });
                
                if (_params.filter) {
                    fpcm.ui.mainToolbar.find('.fpcm-ui-pager-element').addClass('fpcm-ui-hidden');
                    fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');
                    fpcm.dom.fromId('opensearch').addClass('fpcm-ui-button-primary');
                }
                else if (result.pager && !_params.filter) {
                    fpcm.ui.mainToolbar.find('.fpcm-ui-pager-element').removeClass('fpcm-ui-hidden');
                    fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');
                    fpcm.dom.fromId('opensearch').removeClass('fpcm-ui-button-primary');
                    
                    fpcm.vars.jsvars.pager.currentPage = result.pager.currentPage;
                    fpcm.vars.jsvars.pager.maxPages = result.pager.maxPages;
                    fpcm.vars.jsvars.pager.showBackButton = result.pager.showBackButton;
                    fpcm.vars.jsvars.pager.showNextButton = result.pager.showNextButton;

                    fpcm.ui.initPager({
                        nextAction: function () {
                                
                            if (!fpcm.vars.jsvars.pager.showBackButton || fpcm.vars.jsvars.pager.currentPage >= fpcm.vars.jsvars.pager.maxPages) {
                                return false;
                            }

                            fpcm.articles.loadArticles({
                                page: fpcm.vars.jsvars.pager.showNextButton,
                                loader: true
                            });
                            
                            return false;
                        },
                        backAction: function () {
                                
                            if (!fpcm.vars.jsvars.pager.showBackButton) {
                                return false;
                            }

                            fpcm.articles.loadArticles({
                                page: fpcm.vars.jsvars.pager.showBackButton,
                                loader: true
                            });

                        },
                        selectAction: function( event, ui ) {
                            
                            if (ui.item.value == fpcm.vars.jsvars.pager.currentPage) {
                                return false;
                            }

                            fpcm.articles.loadArticles({
                                page: ui.item.value,
                                loader: true
                            });

                        }
                    });
                }

                return true;
            }
        });
        
        
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

                fpcm.dom.fromTag(this).dialog('close');
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
        fpcm.ui.resetSelectMenuSelection('#action');
        return true;
    }
};