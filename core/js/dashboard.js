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

        fpcm.dom.bindClick('#resetDashboardSettings', fpcm.dashboard.resetPositions);

        fpcm.ajax.exec('dashboard', {
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

                     if (!_containers[_i]) {
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

        fpcm.dom.bindClick('.fpcm.ui-dashboard-conatiner-disable', function (_e, _ui) {
            fpcm.dashboard.disableContainer(_ui.dataset.cname);
        });
    },
    
    resetPositions: function ()
    {
        fpcm.ui_dialogs.confirm({
            clickYes: function () {
                fpcm.ajax.post('setconfig', {
                    data: {
                        op: 'reset',
                        var: 'dashboardpos'
                    },
                    execDone: fpcm.dashboard.init,
                    quiet: true
                });
            }
        });

        return false;
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
    }

};