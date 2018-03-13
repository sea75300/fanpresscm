/**
 * FanPress CM texts namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.texts = {

    init: function() {
        fpcm.dataview.render('textslist', {
            onRenderAfter: fpcm.ui.assignCheckboxes
        });
    },

};