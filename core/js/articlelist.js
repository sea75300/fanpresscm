/**
 * FanPress CM Article list Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.articlelist = {

    init: function() {
        
        fpcm.ui.checkboxradio('.fpcm-ui-massedit-categories .fpcm-ui-input-checkbox', {
            icon: false
        });

        jQuery('#fpcmarticleslistmassedit').click(function () {
            fpcm.system.initMassEditDialog('articles/massedit', 'articles-massedit', fpcm.articlelist);
            return false;
        });
        
        fpcm.articlelist.initArticleSearch();
        fpcm.articlelist.clearArticleCache();
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
            fpcmJs.clearCache({
                cache: artCacheMod,
                objid: 0
            });
            return -1;
        }
        
        return true;
    },
    
    initArticleSearch: function() {
        jQuery('#fpcmarticlesopensearch').click(function () {

            fpcm.ui.selectmenu('.fpcm-ui-input-select-articlesearch', {
                width: '100%',
                appendTo: '#fpcm-dialog-articles-search'
            });

            fpcm.ui.datepicker('.fpcm-full-width-date', {
                minDate: fpcmArticlSearchMinDate
            });

            var size = fpcm.ui.getDialogSizes();

            fpcm.ui.dialog({
                id      : 'articles-search',
                dlWidth: size.width,
                resizable: true,
                title    : fpcm.ui.translate('searchHeadline'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('searchStart'),
                        icon: "ui-icon-check",
                        click: function() {                            
                            var sfields = jQuery('.fpcm-articles-search-input');
                            var sParams = {
                                mode: fpcmArticleSearchMode,
                                filter: {}
                            };
                            
                            jQuery.each(sfields, function( key, obj ) {
                                var objVal  = jQuery(obj).val();
                                var objName = jQuery(obj).attr('name');                                
                                sParams.filter[objName] = objVal;
                            });

                            fpcmJs.startSearch(sParams);
                            jQuery(this).dialog('close');
                        }
                    },                    
                    {
                        text: fpcm.ui.translate('close'),
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
    
    articleActionsTweet: function() {
        fpcm.ui.confirmDialog({
            clickYes: function() {
                var articleIds = fpcm.ui.getCheckboxCheckedValues('.fpcm-list-selectbox');
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
                    fpcmJs.addAjaxMassage('notice', result.notice);
                }

                if (result.error != 0) {
                    fpcmJs.addAjaxMassage('error', result.error);
                }

            }
        });

    },

    clearArticleCache: function() {
        
        jQuery('.fpcm-article-cache-clear').click(function() {
            
            var obj = jQuery(this);
            
            var cache = obj.attr('data-cache') ? obj.attr('data-cache') : '';
            var objid = obj.attr('data-objid') ? obj.attr('data-objid') : 0;

            fpcmJs.clearCache({
                cache: cache,
                objid: objid
            });
            
            return false;
        });

    }
};