/**
 * FanPress CM user editing Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.useredit = {

    init: function () {
        fpcm.ui.checkboxradio('#disable2Fa');
        fpcm.ui.selectmenu('.fpcm-ui-input-select', {
            removeCornerLeft: true
        });
    }

};