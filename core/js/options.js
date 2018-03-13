/**
 * FanPress CM Options Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.options = {

    init: function () {

        if (fpcm.vars.jsvars.runSysCheck) {
            fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab1').addClass('fpcm-ui-hidden');
            fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab2').removeClass('fpcm-ui-hidden');
            fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');
        }

        fpcm.ui.tabs('.fpcm-tabs-general', {
            active   : (fpcm.vars.jsvars.runSysCheck ? 7 : 0),
            addTabScroll: true,
            addMainToobarToggle: true
        });

        fpcm.ui.datepicker('#articles_archive_datelimit', {
            maxDate: "-3m"
        });

        jQuery('#tabs-options-syscheck').click(function () {
            fpcm.systemcheck.execute();
        });

        jQuery('#syschecksubmitstats').click(function () {
            fpcm.ui.showLoader(true);
            fpcm.ajax.get('syscheck', {
                data: {
                    sendstats: 1
                },
                execDone: function () {
                    fpcm.ui.showLoader(false);
                }
            });
        });
        
        fpcm.ui.selectmenu('#smtp_enabled', {
            change: function( event, data ) {
                var status = (data.item.value == 1 ? false : true);
                fpcm.ui.isReadonly('input.fpcm-ui-options-smtp-input', status);
                fpcm.ui.selectmenu('#smtp_settingsencr', {
                    disabled: status
                });
                return true;
            }
        });
    }
};