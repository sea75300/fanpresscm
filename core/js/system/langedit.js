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

            fpcm.ui_dialogs.create({
                id: 'langform-new',
                title: 'New language variable ',
                content: '<input typer="text" id="fpcm-langedit-newvar" class="form-control mb-1" placeholder="Variable name"><br>\n\
                          <input typer="text" id="fpcm-langedit-newval" class="form-control" placeholder="Variable value">',
                closeButton: true,
                dlButtons: [
                    {
                        text: 'GLOBAL_SAVE',
                        icon: "save",
                        primary: true,
                        clickClose: true,
                        click: function () {
                            
                            var newVarName = fpcm.dom.fromId('fpcm-langedit-newvar').val();
                            var newVarValue = fpcm.dom.fromId('fpcm-langedit-newval').val();
                            newVarName = newVarName.trim().replace(/[^a-z0-9\_]/ig, '');
                            newVarValue = newVarValue.trim();
                            if (!newVarName) {
                                console.error('Empty language variable found!');
                                return false;
                            }

                            fpcm.dom.appendHtml('form', '<input type="hidden" name="lang[' + newVarName.toUpperCase() + ']" value="' + newVarValue + '">');
                            fpcm.dom.fromId('btnSave').trigger('click');
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
                '<textarea class="w-100 h-100 border-0 fpcm ui-textarea-noresize" id="' + newTextid + '">' + fpcm.dom.fromId(oldTextId).val() + '</textarea>'
            ];

            fpcm.ui_dialogs.create({
                id: 'langform-' + data.dest,
                title: 'Edit language var: ' + data.var,
                content: content.join('\n'),
                closeButton: true,
                dlButtons: [
                    {
                        text: 'GLOBAL_SAVE',
                        icon: "check",
                        primary: true,
                        clickClose: true,
                        click: function () {
                            var newVal = fpcm.dom.fromId(newTextid).val();
                            fpcm.dom.fromId(oldTextId).val(newVal);
                            fpcm.dom.fromId(descrId).html(newVal);
                            fpcm.dom.fromId('btnSave').trigger('click');
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