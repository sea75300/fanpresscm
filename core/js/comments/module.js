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

        fpcm.dom.bindClick('#btnSettings', function (ev, _ui, _result) {
            fpcm.ui_dialogs.settings('comments', 'settings', function () {
                fpcm.ui.relocate('self');
            });
        });
  
        fpcm.worker.postMessage({
            namespace: 'comments',
            function: 'loadItems',
            id: 'comments.loadItems',
            param: {
                page: fpcm.vars.jsvars.listPage
            }
        });    
    
        fpcm.comments.assignActionsList();
    },

    assignActionsList: function() {
            
        if (fpcm.vars.jsvars.activeTab) {
            fpcm.vars.jsvars.massEdit = {
                relocateParams: '&rg=' + fpcm.vars.jsvars.activeTab
            }
        }

        fpcm.dom.bindClick('#btnMassEdit', function () {
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

    loadItems: function(_params) {

        if (!fpcm.vars.jsvars.listMode) {
            return;
        }

        if (!_params) {
            _params = {};
        }

        let _fnParams = {
            mode: fpcm.vars.jsvars.listMode,
            page: _params.page !== undefined ? parseInt(_params.page) : 1,
            module: 'comments',
            onRenderDataViewAfter: function () {
                fpcm.comments.assignActionsList();
                fpcm.comments.deleteSingleArticle();
            },
            onPagerNext: function () {
                fpcm.comments.loadItems({
                    page: fpcm.vars.jsvars.pager.showNextButton,
                    loader: true
                });
                
                return true;
            },
            onPagerBack: function () {
                fpcm.comments.loadItems({
                    page: fpcm.vars.jsvars.pager.showBackButton,
                    loader: true
                });

                return true;
            },
            onPagerSelect: function (event, ui) {

                fpcm.comments.loadItems({
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

    startCommentSearch: function (_params) {

        if (((new Date()).getTime() - fpcm.vars.jsvars.commentsLastSearch) < 10000) {
            fpcm.ui.addMessage({
                type: 'error',
                txt : fpcm.ui.translate('SEARCH_WAITMSG')
            });
            return false;
        }

        fpcm.ajax.post('comments/lists', {
            data: _params,
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
    
    deleteSingleArticle: function() {
        
        fpcm.dom.bindClick('a[data-comid]', function (_e, _ui) {

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
    
    resetActionsMenu: function () {
        fpcm.dom.resetValuesByIdsSelect('ids');
        return true;
    }

};