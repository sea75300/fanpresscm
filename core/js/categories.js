/**
 * FanPress CM categories namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.categories = {

    init: function () {        

        fpcm.dom.fromId('massEdit').click(function () {

            fpcm.system.initMassEditDialog('categories/massedit', 'categories-massedit', fpcm.categories, {
                multipleSelect: 'rolls',
                multipleSelectField: 'groups',
                fields: fpcm.vars.jsvars.masseditFields
            });

            fpcm.dom.fromId('rolls').selectize({
                placeholder: fpcm.ui.translate('CATEGORIES_ROLLS'),
                searchField: ['text', 'value'],
                plugins: ['remove_button']
            });

            return false;
        });

        var dvName = 'categorylist';
        if (!fpcm.dataview || !fpcm.dataview.exists(dvName)) {
            return true;
        }

        fpcm.dataview.render(dvName, {
            onRenderAfter: fpcm.ui.assignCheckboxes
        });

    },

};