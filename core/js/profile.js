/**
 * FanPress CM Profile Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.profile = {

    init: function () {

        if (fpcmReloadPage) {
            setTimeout(function () {
                fpcm.ui.showLoader(true);
                fpcmJs.relocate(fpcmActionPath + 'system/profile');
            }, 1500);
        }

    }

};