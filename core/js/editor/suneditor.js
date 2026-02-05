/**
 * FanPress CM TinyMCE Wrapepr Editor Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor_suneditor = {

    create: function (_config) {

        _config.lang = SUNEDITOR_LANG[_config.lang];

        const editor = SUNEDITOR.create(
            document.getElementById('suneditor'),
            _config
        );
    }

};

if (fpcm.editor) {

    fpcm.editor.initEditor = function () {

        fpcm.editor_suneditor.create(fpcm.vars.jsvars.editorConfig);

    };

    fpcm.editor.initToolbar = function () {
        return true;
    };

    fpcm.editor.insertThumbByEditor = function (url, title) {

        fpcm.editor.filePickerCallback(url, {
            alt: title,
            text: title
        });

        //top.tinymce.activeEditor.windowManager.close();
    };

    fpcm.editor.insertFullByEditor = function (url, title) {

        fpcm.editor.filePickerCallback(url, {
            alt: title,
            text: title
        });

        //top.tinymce.activeEditor.windowManager.close();
    };

    fpcm.editor.insertGalleryByEditor = function (_values) {

        if (!_values.length) {
            return false;
        }

        /*top.tinymce.activeEditor.insertContent(fpcm.editor.getGalleryReplacement(_values) + fpcm.vars.jsvars.editorGalleryTagEnd);
        top.tinymce.activeEditor.windowManager.close();
        top.tinymce.activeEditor.windowManager.close();*/

    };

    fpcm.editor.insertGalleryDisabled = function (_mode) {

        /*if (_mode === undefined) {
            _mode = fpcm.vars.jsvars.filemanagerMode;
        }

        if (_mode !== 2) {
            return true;
        }

        if (top.tinymce === undefined) {
            return true;
        }

        if (top.tinymce.activeEditor === undefined) {
            return true;
        }

        let _cont = top.tinymce.activeEditor.getContent();
        if (_cont && _cont.search('/gallery') != -1) {
            return true;
        }*/

        return false;
    };

}
