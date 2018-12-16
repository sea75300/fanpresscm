/**
 * FanPress CM Dashboard Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.dashboard = {

    init: function () {
        fpcm.dashboard.load();
    },
    
    load: function () {
        fpcm.ui.showLoader(true, '<strong>' + fpcm.ui.translate('DASHBOARD_LOADING') + '</strong>');
        fpcm.ajax.exec('dashboard', {
            execDone: function(result) {
                fpcm.ui.assignHtml('#fpcm-dashboard-containers', result);
                fpcm.ui.initJqUiWidgets();
                fpcm.ui.showLoader(false);
                fpcm.dashboard.forceUpdate();
                fpcm.dashboard.openUpdateCheckUrl();
                jQuery('#fpcm-dashboard-containers').sortable({
                    items: 'div.fpcm-dashboard-container',
                    handle: 'span.fpcm-dashboard-container-move',
                    opacity: 0.5,
                    update: function ( event, ui ) {

                        var saveItems = {};
                        jQuery.each(ui.item.parent().children(), function (pos, item) {
                            saveItems[jQuery(item).attr('data-container')] = parseInt(pos) + 1;
                        });

                        fpcm.ajax.post('setconfig', {
                            data: {
                                var: 'dashboardpos',
                                value: saveItems
                            },
                            execDone: fpcm.dashboard.load
                        });

                    }
                });
                return false;
            }
        });

    },
    
    forceUpdate: function () {
        
        if (!fpcm.vars.jsvars.forceUpdate) {
            return false;
        }
        
        fpcm.ui.relocate(jQuery('#startUpdate').attr('href'));
        return true;
    },
    
    openUpdateCheckUrl: function () {
        
        if (!fpcm.vars.jsvars.openUpdateCheckUrl) {
            return false;
        }

        window.open(jQuery('#chckmanual').attr('href'), '_blank', 'width=700,height=500,scrollbars=yes,resizable=yes,');
        return true;
    }

};