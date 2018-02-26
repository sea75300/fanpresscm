/**
 * FanPress CM system javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.systemcheck = {
    
    init: function() {
        
        if (!fpcm.vars.jsvars.runSysCheck) {
            return true;
        }

        fpcm.systemcheck.execute();
        return true;
    },
    
    execute: function() {

        fpcm.ui.showLoader(true);
        fpcm.ajax.get('syscheck', {
            execDone: function () {
                fpcm.ui.showLoader(false);
                fpcm.ui.assignHtml("#tabs-options-check", fpcm.ajax.getResult('syscheck'));
                fpcm.ui.initJqUiWidgets();
                fpcm.ui.resize();
            }
        });

    }

};