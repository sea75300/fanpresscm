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

        _elemClassId = fpcm.ui.prepareId(_elemClassId);

        if (params === undefined) {
            params = {};
        }

        let _nodes = fpcm.ui_tabs.getNodes(_elemClassId);
        if (!_nodes) {
            return false;
        }

        let _tb = [].slice.call(_nodes);
        _tb.forEach(function (_el) {

            var _obj = new bootstrap.Tab(_el);

            _el.addEventListener('click', function (_ev) {

                if (!params.reload) {
                    fpcm.ui_tabs.setActiveTab(_ev.target.dataset.tabIndex);
                    return true;
                }

                _ev.preventDefault();

                let _currentTab = fpcm.ui_tabs.getActiveTab();
                if (_ev.target.classList.contains('active') && _ev.target.dataset.tabIndex == _currentTab) {
                    _ev.target.classList.remove('active');
                    (new bootstrap.Tab(_ev.target)).show();

                }

                fpcm.ui_tabs.setActiveTab(_ev.target.dataset.tabIndex);
            });

            _el.addEventListener('show.bs.tab', function (_ev) {

                fpcm.ui_tabs._updateMainToolbar(_ev.target, _ev.relatedTarget);
                if (params.emptyPanel && _ev.relatedTarget) {

                    if (typeof params.onEmptyPanelBefore === 'function') {
                        params.onEmptyPanelBefore(_ev);
                    }

                    fpcm.dom.fromId(_ev.relatedTarget.dataset.bsTarget).empty();
                }

                if (typeof params.onShow === 'function') {
                    params.onShow(_ev);
                }

                if (_ev.target.attributes.href.value.substr(0,1) === '#') {
                    return true;
                }

                var _tabList = _ev.target.dataset.dataviewList
                             ? _ev.target.dataset.dataviewList
                             : false;

                if (!_ev.target.dataset.ajaxQuiet) {

                    var _ldr = document.createElement('span');
                    _ldr.classList.add('spinner-border');
                    _ldr.classList.add('spinner-border-sm');
                    _ldr.classList.add('ms-2');
                    _ev.target.appendChild(_ldr);

                }

                fpcm.ajax.get(_ev.target.href, {
                    quiet: true, //_ev.target.dataset.ajaxQuiet ? true : false,
                    execDone: function (_result) {

                        if (!_ev.target.dataset.ajaxQuiet && _ldr) {
                            _ev.target.removeChild(_ldr);
                        }

                        if (!_tabList) {

                            if (typeof params.onRenderHtmlBefore === 'function') {
                                params.onRenderHtmlBefore(_ev, _result);
                            }

                            let _assignment = _result instanceof Object && _result.html ? _result.html : _result;
                            fpcm.dom.assignHtml(_ev.target.dataset.bsTarget, _assignment);

                            if (typeof params.onRenderHtmlAfter === 'function') {
                                params.onRenderHtmlAfter(_ev, _result);
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

            _el.addEventListener('shown.bs.tab', function (_ev) {

                if (typeof params.onTabShowAfter === 'function') {
                    params.onTabShowAfter(_ev);
                }

            });
        });

        if (fpcm.vars.jsvars.activeTab === undefined || fpcm.vars.jsvars.activeTab < 0 || !_tb[fpcm.vars.jsvars.activeTab]) {
            fpcm.vars.jsvars.activeTab = 0;
        }

        if (fpcm.vars.jsvars.activeTab > -1 && fpcm.dom.fromTag(_tb[fpcm.vars.jsvars.activeTab]).hasClass('active') ) {
            fpcm.dom.fromTag(_tb[fpcm.vars.jsvars.activeTab]).removeClass('active');
        }

        (new bootstrap.Tab(_tb[fpcm.vars.jsvars.activeTab])).show();
    },

    _updateMainToolbar: function (_active, _prev) {

        if (!_active || !_prev) {
            return true;
        }

        if (!_active.dataset || !_prev.dataset) {
            return true;
        }

        if (!_active.dataset.toolbarButtons || !_prev.dataset.toolbarButtons ||
            _active.dataset.toolbarButtons === _prev.dataset.toolbarButtons) {
            return true;
        }

        fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ _active.dataset.toolbarButtons).removeClass('fpcm-ui-hidden');
        fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ _prev.dataset.toolbarButtons).addClass('fpcm-ui-hidden');

    },

    getNodes: function(_elemClassId) {

        _elemClassId = fpcm.ui.prepareId(_elemClassId);

        let _nodes = document.querySelectorAll(_elemClassId + ' a.nav-link');

        if (!_nodes.length) {
            console.error('No tab nodes fround for "' + _elemClassId + ' a.nav-link"!');
            return false;
        }

        return _nodes;
    },

    show: function (_elemClassId, _tabId) {

        if (!_elemClassId || !_tabId === undefined) {
            console.error('Invalid params data given data given "_elemClassId" or "_tabId" cennot be empty!');
            return false;
        }

        _elemClassId = fpcm.ui.prepareId(_elemClassId);

        let _nodes = fpcm.ui_tabs.getNodes(_elemClassId);
        if (!_nodes) {
            return false;
        }

        _nodes = [].slice.call(_nodes);
        if (!_nodes[_tabId]) {
            console.error('Undefined tab node ' + _tabId + '!');
            return false;
        }

        if (fpcm.dom.fromTag(_nodes[_tabId]).hasClass('active') ) {
            fpcm.dom.fromTag(_nodes[_tabId]).removeClass('active');
        }

        (new bootstrap.Tab(_nodes[_tabId])).show();
    },

    getActiveTab: function () {

        let _el = document.getElementById('activeTab');
        return _el ? _el.value : 0;

    },

    setActiveTab: function (_val) {

        let _el = document.getElementById('activeTab');
        if (!_el) {
            return false;
        }

        _el.value = _val;
        return true;
    }

}