/**
 * FanPress CM UI Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui_tabs = {

    render: function(_elemClassId, params) {

        if (params === undefined) params = {};

        var _tb = [].slice.call(document.querySelectorAll(_elemClassId + ' a.nav-link'));
        _tb.forEach(function (_el) {

            var _obj = new bootstrap.Tab(_el);

            _el.addEventListener('click', function (_ev) {
                _ev.preventDefault();
                (new bootstrap.Tab(_ev.target)).show();
            });
            
            
            _el.addEventListener('show.bs.tab', function (_ev) {

                if (_ev	.target.attributes.href.value.substr(0,1) === '#') {
                    return true;
                }

                var _tabList = _ev.target.dataset.dataviewList
                             ? _ev.target.dataset.dataviewList
                             : false; 
                
                fpcm.ajax.get(_ev.target.href, {
                    execDone: function (_result) {

                        if (!_result instanceof Object || !_tabList) {
                            
                            if (typeof params.onRenderHtmlBefore === 'function') {
                                params.onRenderHtmlBefore.call();
                            }                            
                            
                            fpcm.dom.assignHtml(_ev.target.dataset.bsTarget, _result);
                            
                            if (typeof params.onRenderHtmlAfter === 'function') {
                                params.onRenderHtmlAfter.call();
                            }                            

                            return false;
                        }
                        
                        if (!_result.dataViewVars) {
                            return false;
                        }

                        fpcm.vars.jsvars.dataviews[_tabList] = _result.dataViewVars;

                        fpcm.dom.assignHtml(
                            _ev.target.dataset.bsTarget,
                            fpcm.dataview.getDataViewWrapper(_tabList, params.dataViewWrapperClass ? params.dataViewWrapperClass : ''
                        ));

                        fpcm.dataview.updateAndRender(
                            _tabList,
                            {
                                onRenderAfter: params.initDataViewOnRenderAfter
                        }); 
                    }
                });


            });
        });

        (new bootstrap.Tab(_tb[0])).show();
        
//        
//        var el = fpcm.dom.fromTag(elemClassId);
//        if (!el.length) {
//            return;
//        }
//        
//        if (params.addMainToobarToggle) {
//            params.beforeActivate = function( event, ui ) {
//                fpcm.ui.updateMainToolbar(ui);
//                if (params.addMainToobarToggleAfter) {
//                    params.addMainToobarToggleAfter(event, ui);
//                }
//
//            }
//        }
//        
//        if (params.saveActiveTab) {
//            params.activate = function(event, ui) {
//                fpcm.vars.jsvars.activeTab = fpcm.dom.fromTag(this).tabs('option', 'active');
//                fpcm.dom.fromId('activeTab').val(fpcm.vars.jsvars.activeTab);
//                fpcm.ui.updateMainToolbar(ui);
//                if (params.saveActiveTabAfter) {
//                    params.saveActiveTabAfter(event, ui);
//                }
//            };
//
//            params.create = function(event, ui) {
//                fpcm.ui.updateMainToolbar(ui);
//                if (params.saveActiveTabAfterInit) {
//                    params.saveActiveTabAfterInit(event, ui);
//                }
//            }
//        }
//
//        if (params.initDataViewJson) {
//
//            params.beforeLoad = function(event, ui) {
//
//                fpcm.ui_loader.show();   
//                
//                tabList = ui.tab.data('dataview-list');                
//                if (!tabList) {
//                    return true;
//                }
//
//                if (params.initDataViewJsonBeforeLoad) {
//                    params.initDataViewJsonBeforeLoad(event, ui);
//                }
//
//                if (!params.dataFilterParams) {
//                    params.dataFilterParams = function( response ) {
//                        this.dataTypes = ['html', 'text'];
//                        return response;
//                    };
//                    
//                }
//
//                ui.ajaxSettings.dataTypes = params.dataTypes ? params.dataTypes : ['json'];
//                ui.ajaxSettings.accepts = params.accepts ? params.accepts : 'application/json';
//                ui.ajaxSettings.dataFilter = params.dataFilterParams;
//
//                ui.jqXHR.done(function(jqXHR) {
//
//                    if (typeof jqXHR !== 'object' || !jqXHR.dataViewVars) {
//                        return true;
//                    }
//
//                    fpcm.vars.jsvars.dataviews[tabList] = jqXHR.dataViewVars;
//                    if (params.initbeforeLoadDone) {
//                        params.initbeforeLoadDone(jqXHR);
//                    }
//                    
//                    return true;
//                });
//
//                ui.jqXHR.fail(function(jqXHR, textStatus, errorThrown) {
//                    console.error(fpcm.ui.translate('AJAX_RESPONSE_ERROR'));
//                    console.error('STATUS MESSAGE: ' + textStatus);
//                    console.error('ERROR MESSAGE: ' + errorThrown);
//                    fpcm.ajax.showAjaxErrorMessage();
//                    fpcm.ui_loader.hide();
//                });
//            };
//
//            params.load = function(event, ui) {
//
//                var tabList = ui.tab.data('dataview-list');                
//                if (!tabList) {
//
//                    if (params.initbeforeLoadDoneNoTabList) {
//                        params.initbeforeLoadDoneNoTabList(event, ui);
//                    }
//
//                    fpcm.ui_loader.hide();
//                    return true;
//                }
//
//                if (!fpcm.vars.jsvars.dataviews[tabList]) {
//                    fpcm.ui_loader.hide();
//                    return false;
//                }
//
//                if (params.initDataViewJsonBefore) {
//                    params.initDataViewJsonBefore(event, ui);
//                }
//
//                ui.panel.append(fpcm.dataview.getDataViewWrapper(tabList, params.dataViewWrapperClass ? params.dataViewWrapperClass : ''));
//
//                fpcm.dataview.updateAndRender(
//                    tabList,
//                    {
//                        onRenderAfter: params.initDataViewOnRenderAfter
//                });
//
//                if (params.initDataViewJsonAfter) {
//                    params.initDataViewJsonAfter(event, ui);
//                }
//
//                fpcm.ui_loader.hide();
//                return false;
//            };
//        }
//        
//        var tabEl = el.tabs(params);
//        
//        if (params.addTabScroll) {
//
//            el.find('ul.ui-tabs-nav').wrap('<div class="fpcm-tabs-scroll"></div>');
//            fpcm.ui.initTabsScroll(elemClassId);
//            
//            fpcm.dom.fromWindow().resize(function() {
//                fpcm.ui.initTabsScroll(elemClassId, true);
//            });
//
//        }
//
//        return tabEl;
 
    },
    
}