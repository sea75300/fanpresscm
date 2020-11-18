/**
 * FanPress CM Article list Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.revisions = {

    init: function() {
        fpcm.ui.selectmenu('#revisionList', {
            change: function (event, ui) {
                fpcm.ui.relocate(fpcm.vars.actionPath + 'articles/revision&aid= ' + fpcm.vars.jsvars.articleId + '&rid=' + ui.item.value);
            }
        });
    }
};