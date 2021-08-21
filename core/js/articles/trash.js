/**
 * FanPress CM Article list Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.articles_trash = {

    init: function() {
        fpcm.dataview.render('articlelist');
    },

    emptyTrash: function() {

        fpcm.system.emptyTrash({
            fn: 'clearArticles'
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
            fn: 'restoreArticles',
            ids: ids
        });

        return true;

    }

};