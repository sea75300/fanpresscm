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
        jQuery('#massEdit').click(function () {
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

        var articleActions = {
            newtweet    : 'newtweet',
            clearcache  : 'articlecache'
        }
        
        var action = jQuery('#actionsaction').val();
        if (action == articleActions.newtweet) {
            fpcm.articlelist.articleActionsTweet();
            fpcm.ui.removeLoaderClass(this);
            return -1;
        }
        if (action == articleActions.clearcache) {
            fpcm.system.clearCache({
                cache: fpcm.vars.jsvars.artCacheMod,
                objid: 0
            });
            return -1;
        }
        
        return true;
    },
    
    initArticleSearch: function() {
        jQuery('#opensearch').click(function () {

            fpcm.ui.selectmenu('.fpcm-ui-input-select-articlesearch', {
                width: '100%',
                appendTo: '#fpcm-dialog-articles-search'
            });

            fpcm.ui.dialog({
                id      : 'articles-search',
                dlWidth: fpcm.ui.getDialogSizes(top, 0.75).width,
                resizable: true,
                title    : fpcm.ui.translate('ARTICLES_SEARCH'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: 'ui-icon-check',
                        class: 'fpcm-ui-button-primary',
                        click: function() {                            
                            var sfields = jQuery('.fpcm-articles-search-input');
                            var sParams = {
                                mode: fpcm.vars.jsvars.articleSearchMode,
                                filter: {}
                            };
                            
                            jQuery.each(sfields, function( key, obj ) {
                                var objVal  = jQuery(obj).val();
                                var objName = jQuery(obj).attr('name');                                
                                sParams.filter[objName] = objVal;
                            });

                            fpcm.articlelist.startSearch(sParams);
                            jQuery(this).dialog('close');
                        }
                    },                    
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick" ,                        
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
            execDone: function (result) {

                result = fpcm.ajax.fromJSON(result);

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
            defaultNo: true,
            clickYes: function() {
                var articleIds = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
                if (articleIds.length == 0) {
                    fpcm.ui.showLoader(false);
                    return false;
                }

                fpcm.articlelist.execNewTweet(articleIds);
                jQuery(this).dialog('close');
            },
            clickNo: function() {
                jQuery(this).dialog('close');
            }
        });

    },
    
    execNewTweet: function(articleIds) {

        fpcm.ajax.post('articles/tweet', {
            data    : {
                ids : fpcm.ajax.toJSON(articleIds)
            },
            async   : false,
            execDone: function(result) {

                jQuery('#actionsaction').prop('selectedIndex',0);
                jQuery('#actionsaction').selectmenu('refresh');

                fpcm.ui.showLoader(false);
                result = fpcm.ajax.fromJSON(fpcm.ajax.getResult('articles/tweet'));
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
        
        jQuery('.fpcm-article-cache-clear').click(function() {
            
            var obj = jQuery(this);
            
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
        
        jQuery('.fpcm-ui-button-delete-article-single').click(function() {

            var articleId = jQuery(this).data('articleid');
            
            fpcm.ui.confirmDialog({
                
                clickYes: function () {
                    fpcm.ui.showLoader(true);
                    fpcm.ajax.exec('articles/delete', {
                        data: {
                            id: articleId
                        },
                        execDone: function (result) {
                            window.location.reload();
                        }
                    });
                    jQuery(this).dialog("close");
                },
                clickNoDefault: true
                
            });

            return false;
        });

    }
};