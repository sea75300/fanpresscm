/**
 * FanPress CM system javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
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
        fpcm.ajax.get('syscheck', {
            execDone: function (result) {
                fpcm.ui.assignHtml("#tabs-options-check", result);
                fpcm.ui.initJqUiWidgets();
            }
        });

    }

};