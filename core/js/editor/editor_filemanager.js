/**
 * FanPress CM Filemanager Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

if (fpcm.editor !== undefined) {

    fpcm.editor.showFileManager = function(fmgrMode) {

        if (fmgrMode === undefined) {
            fmgrMode = fpcm.vars.jsvars.filemanagerMode;
        }

        var size = fpcm.ui.getDialogSizes(top, 0.75);

        fpcm.dom.appendHtml('#fpcm-dialog-editor-html-filemanager', '<iframe id="fpcm-dialog-editor-html-filemanager-frame" class="fpcm-ui-full-width" src="' + fpcm.vars.jsvars.filemanagerUrl + fmgrMode + '"></iframe>');
        fpcm.ui.dialog({
            id: 'editor-html-filemanager',
            title: 'HL_FILES_MNG',
            closeButton: true,
            dlButtons: [
                {
                    text: 'FILE_LIST_INSERTGALLERY',
                    icon: "images",
                    disabled: fpcm.editor.insertGalleryDisabled(fmgrMode),
                    click: function() {
                        fpcm.dom.fromTag(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#insertGallery').click();
                    }
                },
                {
                    text: 'ARTICLES_SEARCH',
                    icon: "search",
                    click: function() {
                        fpcm.dom.fromTag(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#opensearch').click();
                    }
                },
                {
                    text: 'FILE_LIST_NEWTHUMBS',
                    icon: "image",
                    click: function() {
                        fpcm.dom.fromTag(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#createThumbs').click();
                    }
                },
                {
                    text: 'GLOBAL_DELETE',
                    icon: "trash",
                    click: function() {
                        fpcm.dom.fromTag(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#deleteFiles').click();
                    }
                }                            
            ]
        });   
    };

}
