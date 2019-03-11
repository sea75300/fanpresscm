/**
 * FanPress CM Editor TinyMCE4 Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor.initTinyMce = function() {

    fpcm.vars.jsvars.editorConfig.file_picker = function(callback, value, meta) {
        var fmSize = fpcm.ui.getDialogSizes(top, 0.75);
        
        fpcm.editor.filePickerCallback = callback;

        tinymce.activeEditor.windowManager.open({
            file            : fpcm.vars.jsvars.filemanagerUrl + fpcm.vars.jsvars.filemanagerMode,
            title           : fpcm.ui.translate('HL_FILES_MNG'),
            width           : fmSize.width,
            height          : fmSize.height,
            close_previous  : false,
            buttons  : [
                {
                    text: fpcm.ui.translate('ARTICLES_SEARCH'),
                    onclick: function() {
                        var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
                        jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('#opensearch').click();
                    }
                },
                {
                    text: fpcm.ui.translate('FILE_LIST_NEWTHUMBS'),
                    onclick: function() {
                        var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
                        jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('#createThumbs').click();
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_DELETE'),
                    onclick: function() {
                        var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
                        jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('#deleteFiles').click();
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    onclick: function() {
                        top.tinymce.activeEditor.windowManager.close();
                    }
                }                            
            ]
        });
    };

    fpcm.vars.jsvars.editorConfig.onPaste = function(plugin, args) {
        var content = fpcm.editor_videolinks.replace(args.content);
        if (content === args.content) {
            return true;
        }

        fpcm.ui.showLoader(true);
        args.content = fpcm.editor_videolinks.createFrame(content, true);
        fpcm.ui.showLoader(false);
    };

    fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);

};