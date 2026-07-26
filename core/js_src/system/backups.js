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

fpcm.backups = {

    init: function() {

        fpcm.dataview.render('backups', {
            onRenderAfter: fpcm.ui.assignCheckboxes
        });

    },

};