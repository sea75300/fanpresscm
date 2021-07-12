/**
 * FanPress CM UI Tabs Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui_tabs = {

    init: function() {
        
        var _el = fpcm.dom.fromClass('fpcm.ui-tabs-function-autoinit');
        if (_el === false || !_el.length) {
            return true;
        }

        fpcm.ui_tabs.render('#' + _el.attr('id'));
        return true;
    },

    render: function(_elemClassId, params) {

        if (!_elemClassId) {
            console.error('Invalid "_elemClassId" data given for, value cannot be empty!');
            return false;
        }

        if (params === undefined) {
            params = {};
        }

        let _nodes = document.querySelectorAll(_elemClassId + ' a.nav-link');
        if (!_nodes.length) {
            console.error('No tab nodes fround for "' + _elemClassId + ' a.nav-link"!');
            return false;            
        }

        let _tb = [].slice.call(_nodes);
        _tb.forEach(function (_el) {

            var _obj = new bootstrap.Tab(_el);

            _el.addEventListener('click', function (_ev) {
                _ev.preventDefault();
                (new bootstrap.Tab(_ev.target)).show();
            });

            _el.addEventListener('show.bs.tab', function (_ev) {

                fpcm.ui_tabs._updateMainToolbar(_ev.target, _ev.relatedTarget);

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
    
    _updateMainToolbar: function (_active, _prev) {

        if (_active === undefined || _prev === undefined || 
            _active.dataset === undefined || _prev.dataset === undefined) {
            return true;
        }

        if (_active.dataset.toolbarButtons === undefined || _prev.dataset.toolbarButtons === undefined ||
            _active.dataset.toolbarButtons === _prev.dataset.toolbarButtons) {
            return true;
        }

        fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ _active.dataset.toolbarButtons).removeClass('fpcm-ui-hidden').parent('div').addClass('mx-1');        
        fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ _prev.dataset.toolbarButtons).addClass('fpcm-ui-hidden').parent('div').removeClass('mx-1');
        
    }
    
}