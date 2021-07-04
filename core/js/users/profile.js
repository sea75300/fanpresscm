/**
 * FanPress CM Profile Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.profile = {

    init: function () {

        if (fpcm.vars.jsvars.reloadPage) {
            setTimeout(function () {
                fpcm.ui_loader.show();
                fpcm.ui.relocate(fpcm.vars.actionPath + 'system/profile');
            }, 1500);
        }
        
        fpcm.dom.fromId('dataemail').focusout(function () {
            fpcm.ui.showCurrentPasswordConfirmation();
            
        });

    }

};