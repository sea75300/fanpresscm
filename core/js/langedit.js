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
        
        fpcm.dom.fromId('btnNew').unbind('click');
        fpcm.dom.fromId('btnNew').click(function() {

            fpcm.ui.dialog({
                id: 'langform-new',
                resizable: true,
                dlHeight: 300,
                title: 'New language variable ',
                content: '<input typer="text" id="fpcm-langedit-newvar" class="fpcm-ui-full-width fpcm-ui-input fpcm-ui-input-text mb-2" placeholder="Variable name"><br>\n\
                          <input typer="text" id="fpcm-langedit-newval" class="fpcm-ui-full-width fpcm-ui-input fpcm-ui-input-text" placeholder="Variable value">',
                dlOnClose: function() {
                    fpcm.dom.fromTag(this).remove();
                },
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_SAVE'),
                        icon: "ui-icon-check",
                        class: 'fpcm-ui-button-primary',
                        click: function () {
                            
                            var newVarName = fpcm.dom.fromId('fpcm-langedit-newvar').val();
                            var newVarValue = fpcm.dom.fromId('fpcm-langedit-newval').val();
                            newVarName = newVarName.trim().replace(/[^a-z0-9\_]/ig, '');
                            newVarValue = newVarValue.trim();
                            if (!newVarName) {
                                return false;
                            }

                            fpcm.dom.appendHtml('form', '<input type="hidden" name="lang[' + newVarName.toUpperCase() + ']" value="' + newVarValue + '">');
                            fpcm.dom.fromId('btnSave').trigger('click');
                            fpcm.dom.fromTag(this).dialog('close');
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
                        
            
            
        });
        

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
                dlHeight: 300,
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
                            fpcm.dom.fromId(descrId).html(newVal);                            
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

        var buttons2 = fpcm.dom.fromClass('fpcm-language-delete');

        buttons2.unbind('click');
        buttons2.click(function() {
            jQuery(this).parent().parent().remove();
            fpcm.dom.fromId('btnSave').trigger('click');
        });
    },

};