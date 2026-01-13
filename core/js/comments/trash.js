/**
 * FanPress CM Article list Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.comments_trash = {

    init: function() {
        fpcm.dataview.render('commenttrash');
    },

    emptyTrash: function() {

        fpcm.system.emptyTrash({
            fn: 'clearComments'
        });

        return true;

    },

    restoreFromTrash: function() {

        var ids = fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
        if (ids.length == 0) {
            fpcm.ui_loader.hide();
            return false;
        }

        fpcm.system.emptyTrash({
            fn: 'restoreComments',
            ids: ids
        });

        return true;

    }

};