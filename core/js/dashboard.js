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

        fpcm.ui.showLoader(true, '<strong>' + fpcm.ui.translate('DASHBOARD_LOADING') + '</strong>');
        fpcm.ajax.exec('dashboard', {
            execDone: function() {
                fpcm.ui.assignHtml('#fpcm-dashboard-containers', fpcm.ajax.getResult('dashboard'));
                fpcm.ui.initJqUiWidgets();
                fpcm.ui.showLoader(false);

                var fpcmRFDinterval = setInterval(function(){
                    if (jQuery('#fpcm-dashboard-finished').length == 1) {
                        clearInterval(fpcmRFDinterval);
                        fpcm.dashboard.forceUpdate();
                        fpcm.dashboard.openUpdateCheckUrl();
                        return false;
                    }
                }, 250);
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