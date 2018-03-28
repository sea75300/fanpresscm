/**
 * FanPress CM Users Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ipadresses = {

    init: function() {
        
        fpcm.ui.checkboxradio('.fpcm-ui-ipadresses-rolls', {
            icon: false
        });

        var dvName = 'iplist';
        if (!fpcm.dataview.exists(dvName)) {
            return true;
        }

        fpcm.dataview.render(dvName, {
            onRenderAfter: fpcm.ui.assignCheckboxes
        });

    },

};