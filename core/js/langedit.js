/**
 * FanPress CM language editor namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.langedit = {

    init: function() {

        var buttons = fpcm.dom.fromClass('fpcm-language-edit');

        buttons.unbind('click');
        buttons.click(function() {
            
            var data = jQuery(this).data();
            
            var oldTextId = 'lang_' + data.dest;
            var newTextid = 'langform_value_' + data.dest;
            var descrId = 'lang_descr_' + data.dest;

            var content = [
                '<textarea class="fpcm-ui-full-width fpcm-ui-full-height" id="' + newTextid + '">' + fpcm.dom.fromId(oldTextId).val() + '</textarea>'
            ];

            fpcm.ui.dialog({
                id: 'langform-' + data.dest,
                resizable: true,
                title: 'Edit language var: ' + data.var,
                content: content.join('\n'),
                dlOnClose: function() {
                    fpcm.dom.fromTag(this).remove();
                },
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_SAVE'),
                        icon: "ui-icon-check",
                        class: 'fpcm-ui-button-primary',
                        click: function () {
                            var newVal = fpcm.dom.fromId(newTextid).val();
                            fpcm.dom.fromId(oldTextId).val(newVal);
                            fpcm.dom.fromId(descrId).text(newVal);                            
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",
                        click: function () {
                            fpcm.dom.fromTag(this).dialog('close');
                        }
                    }
                ]
            });
            
            return false;
            
        });
    },

};