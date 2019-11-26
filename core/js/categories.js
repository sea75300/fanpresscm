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
            
            var fieldIconPath = fpcm.vars.jsvars.masseditFields.fieldIconPath;
            var fieldRolls = fpcm.vars.jsvars.masseditFields.fieldRolls;


            fpcm.dom.appendHtml(
                '#fpcm-body',
                '<div id="fpcm-dialog-categories-massedit"  class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-massedit-dialog">' + fieldIconPath + fieldRolls + '</div>'
            );

            fpcm.system.initMassEditDialog('categories/massedit', 'categories-massedit', fpcm.categories, {
                multipleSelect: 'rolls',
                multipleSelectField: 'groups',
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
    
    initWidgets: function () {

        fpcm.dom.fromId('rolls').selectize({
            placeholder: fpcm.ui.translate('CATEGORIES_ROLLS'),
            searchField: ['text', 'value'],
            plugins: ['remove_button']
        });

    },

};