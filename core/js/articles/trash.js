/**
 * FanPress CM Article list Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.articles_trash = {

    initAfter: function() {

        fpcm.dataview.render('articlelist', {
            onRenderAfter: function() {
                fpcm.ui.assignCheckboxes();
                fpcm.ui.assignControlgroups();
            }
        });

    },
    
    assignActions: function() {
        
        var action = fpcm.dom.fromId('action').val();

        if (action == 'trash') {
            fpcm.articles_trash.emptyTrash();
            return -1;
        }

        if (action == 'restore') {
            fpcm.articles_trash.restoreFromTrash();
            return -1;
        }

        return true;
    },

    emptyTrash: function() {

        fpcm.system.emptyTrash({
            fn: 'clearArticles'
        });

        return true;

    },

    restoreFromTrash: function() {

        var ids = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
        if (ids.length == 0) {
            fpcm.ui_loader.hide();
            return false;
        }

        fpcm.system.emptyTrash({
            fn: 'restoreArticles',
            ids: ids
        });

        return true;

    },
    
    resetActionsMenu: function () {
        fpcm.ui.resetSelectMenuSelection('#action');
        return true;
    }
};