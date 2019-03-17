/**
 * FanPress CM Comments Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor = {

    init: function () {
        fpcm.editor[fpcm.vars.jsvars.editorInitFunction].call();
        fpcm.editor.initToolbar();
        fpcm.ui.setFocus('commentname');
    },

    initCodeMirror: function() {

        var colorsEl = jQuery('#fpcm-dialog-editor-html-insertcolor').find('div.fpcm-dialog-editor-colors');
        for (var i = 0;i < fpcm.vars.jsvars.editorConfig.colors.length; i++) {
            colorsEl.append('<span class="fpcm-ui-padding-md-tb fas fa-square fa-fw fa-lg" style="color:' + fpcm.vars.jsvars.editorConfig.colors[i] + '" data-color="' + fpcm.vars.jsvars.editorConfig.colors[i] + '"></span>');
            if ((i+1) % 20 == 0) {
                colorsEl.append('<br>');
            }
        }

        jQuery('div.fpcm-dialog-editor-colors span').click(function() {
            jQuery('#colorhexcode').val(jQuery(this).data('color'));
        });

        fpcm.editor.cmInstance = fpcm.editor_codemirror.create({
           editorId  : 'htmleditor',
           elementId : 'commenttext',
           extraKeys : {
                "Enter"    : function() {
                    fpcm.editor.insertBr();
                },
                "Ctrl-B"    : function() {
                    jQuery('#fpcm-editor-html-bold-btn').click();
                },
                "Ctrl-I"    : function() {
                    jQuery('#fpcm-editor-html-italic-btn').click();
                },
                "Ctrl-U"    : function() {
                    jQuery('#fpcm-editor-html-underline-btn').click();
                },
                "Ctrl-O"    : function() {
                    jQuery('#fpcm-editor-html-strike-btn').click();
                },
                "Shift-Ctrl-F"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertcolor-btn').click();
                },
                "Ctrl-Y"    : function() {
                    jQuery('#fpcm-editor-html-sup-btn').click();
                },
                "Shift-Ctrl-Y"    : function() {
                    jQuery('#fpcm-editor-html-sub-btn').click();
                },
                "Shift-Ctrl-L"    : function() {
                    jQuery('#fpcm-editor-html-aleft-btn').click();
                },
                "Shift-Ctrl-C"    : function() {
                    jQuery('#fpcm-editor-html-acenter-btn').click();
                },
                "Shift-Ctrl-R"    : function() {
                    jQuery('#fpcm-editor-html-aright-btn').click();
                },
                "Shift-Ctrl-J"    : function() {
                    jQuery('#fpcm-editor-html-ajustify-btn').click();
                },
                "Ctrl-Alt-N"    : function() {
                    jQuery('#fpcm-editor-html-insertlist-btn').click();
                },
                "Shift-Ctrl-N"    : function() {
                    jQuery('#fpcm-editor-html-insertlistnum-btn').click();
                },
                "Ctrl-Q"    : function() {
                    jQuery('#fpcm-editor-html-quote-btn').click();
                },
                "Ctrl-L"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertlink-btn').click();
                },
                "Ctrl-P"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertimage-btn').click();
                },
                "Shift-Ctrl-Z"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertmedia-btn').click();
                },
                "Shift-Ctrl-T"    : function() {
                    jQuery('#fpcm-dialog-editor-html-inserttable-btn').click();
                },
                "Shift-Ctrl-E"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertsmiley-btn').click();
                },
                "Shift-Ctrl-D"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertdraft-btn').click();
                },
                "Shift-Ctrl-I"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertsymbol-btn').click();
                },
                "Shift-Ctrl-S"    : function() {
                    jQuery('#fpcm-editor-html-removetags-btn').click();
                    return false;
                }
            }
        });

    },
    
    initTinyMce: function() {
        fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);
    }

};