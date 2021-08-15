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

        fpcm.ui_dialogs.create({
            id: 'editor-html-filemanager',
            title: 'HL_FILES_MNG',
            closeButton: true,
            url: fpcm.vars.jsvars.filemanagerUrl + fmgrMode,
            dlButtons: [
                {
                    text: 'FILE_LIST_INSERTGALLERY',
                    icon: "images",
                    disabled: fpcm.editor.insertGalleryDisabled(fmgrMode),
                    click: function(_ui) {
                        fpcm.dom.findElementInDialogFrame(_ui, '#insertGallery').click();
                    }
                },
                {
                    text: 'ARTICLES_SEARCH',
                    icon: "search",
                    click: function(_ui) {
                        fpcm.dom.findElementInDialogFrame(_ui, '#opensearch').click();
                    }
                },
                {
                    text: 'FILE_LIST_NEWTHUMBS',
                    icon: "image",
                    click: function(_ui) {
                        fpcm.dom.findElementInDialogFrame(_ui, '#createThumbs').click();
                    }
                },
                {
                    text: 'GLOBAL_DELETE',
                    icon: "trash",
                    click: function(_ui) {
                        fpcm.dom.findElementInDialogFrame(_ui, '#deleteFiles').click();
                    }
                }                            
            ]
        });   
    };

}
