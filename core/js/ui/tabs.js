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
                            
                if (typeof params.onShow === 'function') {
                    params.onShow(_ev);
                }

                if (_ev.target.attributes.href.value.substr(0,1) === '#') {
                    return true;
                }

                var _tabList = _ev.target.dataset.dataviewList
                             ? _ev.target.dataset.dataviewList
                             : false; 
                
                fpcm.ajax.get(_ev.target.href, {
                    execDone: function (_result) {

                        if (!_result instanceof Object || !_tabList) {
                            
                            if (typeof params.onRenderHtmlBefore === 'function') {
                                params.onRenderHtmlBefore(_ev);
                            }                            
                            
                            fpcm.dom.assignHtml(_ev.target.dataset.bsTarget, _result);
                            
                            if (typeof params.onRenderHtmlAfter === 'function') {
                                params.onRenderHtmlAfter(_ev);
                            }                            

                            return false;
                        }
                        
                        if (!_result.dataViewVars) {
                            return false;
                        }
                            
                        if (typeof params.onRenderJsonBefore === 'function') {
                            params.onRenderJsonBefore(_ev, _result);
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
                            
                        if (typeof params.onRenderJsonAfter === 'function') {
                            params.onRenderJsonAfter(_ev, _result);
                        }
                    }
                });


            });
        });

        (new bootstrap.Tab(_tb[0])).show();
 
    },
    
}