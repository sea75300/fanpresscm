/**
 * FanPress CM Dashboard Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.dashboard = {

    onDone: {},

    init: function () {

        fpcm.dom.bindClick('#btnResetDashboardSettings', fpcm.dashboard.resetPositions);
        fpcm.dom.bindEvent('#offcanvasInfo', 'shown.bs.offcanvas', fpcm.dashboard.fetchDisabledContainer);
        fpcm.dom.bindEvent('#offcanvasInfo', 'hidden.bs.offcanvas', function () {
            fpcm.dom.fromTag('#fpcm-ui-container-disabled-list > a[data-container]').empty();
        });

        fpcm.ajax.exec('dashboard/load', {
            quiet: true,
            async: true,
            execDone: function(result) {
                fpcm.dom.assignHtml('#fpcm-dashboard-containers', result);
                fpcm.ui.initJqUiWidgets();
                fpcm.dashboard.forceUpdate();
                fpcm.dashboard.openUpdateCheckUrl();
                fpcm.dashboard.initDraggable();

                jQuery.each(fpcm.dashboard.onDone, function (idx, object) {

                    if (!object.execAfter || typeof object.execAfter !== 'function') {
                        return true;
                    }

                    object.execAfter();
                });
                
                
                return false;
            }
        });        
    },
    
    forceUpdate: function () {
        
        if (!fpcm.vars.jsvars.forceUpdate) {
            return false;
        }
        
        fpcm.ui.relocate(fpcm.dom.fromId('startUpdate').attr('href'));
        return true;
    },
    
    openUpdateCheckUrl: function () {
        
        if (!fpcm.vars.jsvars.openUpdateCheckUrl) {
            return false;
        }

        window.open(fpcm.dom.fromId('chckmanual').attr('href'), '_blank', 'width=700,height=500,scrollbars=yes,resizable=yes,');
        return true;
    },
    
    initDraggable: function () {7

        fpcm.ui_dnd.initDnd({
            destination: 'fpcm-dashboard-containers',
            handle: '.fpcm.dashboard-container-move',
            dropCallback: function (_event) {

                 var _containers = _event.to.children;
                 var _saveItems = {};

                 for (var _i = 0; _i < _containers.length; _i++) {

                     if (!_containers[_i] || !_containers[_i].dataset || !_containers[_i].dataset.cname) {
                         continue;
                     }

                     _saveItems[ _containers[_i].dataset.cname ] = _i + 1;
                 }

                 fpcm.ajax.post('setconfig', {
                     data: {
                         var: 'dashboardpos',
                         value: _saveItems
                     },
                     execDone: fpcm.dashboard.init,
                     quiet: true
                 });
            }
        });

        fpcm.dom.bindClick('.fpcm.ui-dashboard-container-disable', function (_e, _ui) {
            fpcm.dashboard.disableContainer(_ui.dataset.cname);
        });
    },
    
    resetPositions: function (_e)
    {
        fpcm.ui_dialogs.confirm({
            clickYes: function () {
                
                let _spin = document.createElement('span');
                _spin.classList.add('spinner-border');
                _spin.classList.add('spinner-border-sm');
                _spin.classList.add('ms-1');
                _e.delegateTarget.appendChild(_spin);
                
                fpcm.ajax.post('setconfig', {
                    data: {
                        op: 'reset',
                        var: 'dashboardpos'
                    },
                    execDone: function () {
                        fpcm.dashboard.init();
                        _e.delegateTarget.removeChild(_spin);
                    },
                    quiet: true
                });
            }
        });

        return false;
    },
    
    fetchDisabledContainer: function() {

        fpcm.ajax.exec('dashboard/manager', {
            quiet: true,
            async: true,
            execDone: function(_result) {

                fpcm.dom.fromTag('#fpcm-ui-container-disabled-list > a[data-container]').remove();
                let _el = fpcm.dom.fromId('fpcm-ui-container-disabled-list');

                let _btn = '';
                if (_result[0].code !== -1) {
                    _btn = '<button type="button" class="btn-close"></button>';
                }

                for (var _i in _result) {
                    _el.append(`<a class="list-group-item d-flex justify-content-between align-items-start" data-container="${_result[_i].code}"><div class="align-self-center">${_result[_i].hl}</div> ${_btn}</a>`);
                }

                fpcm.dom.bindClick('#fpcm-ui-container-disabled-list > a[data-container]', function (_e, _ui) {
                    fpcm.dashboard.enableContainer(_ui);
                });

            }
        });    
    },
    
    disableContainer: function (_value)
    {
        fpcm.ui_dialogs.confirm({
            clickYes: function () {
                fpcm.ajax.post('setconfig', {
                    data: {
                        op: 'change',
                        var: 'dashboard_containers_disabled',
                        value: _value
                    },
                    execDone: fpcm.dashboard.init,
                    quiet: true
                });
            }
        });

        return false;
    },
    
    enableContainer: function (_ui)
    {
        fpcm.ui_dialogs.confirm({
            clickYes: function () {
                fpcm.ajax.post('setconfig', {
                    data: {
                        op: 'reset',
                        var: 'dashboard_containers_disabled',
                        value: _ui.dataset.container
                    },
                    execDone: function () {
                        fpcm.dashboard.fetchDisabledContainer();
                        fpcm.dashboard.init();
                    },
                    quiet: true
                });
            }
        });

        return false;
    }

};