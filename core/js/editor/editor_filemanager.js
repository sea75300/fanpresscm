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

    fpcm.editor.showFileManager = function(_mode) {

        if (_mode === undefined) {
            _mode = fpcm.vars.jsvars.filemanagerMode;
        }

        let _btns = [,
            {
                text: 'ARTICLES_SEARCH',
                icon: "search",
                click: function(_ui) {
                    fpcm.dom.findElementInDialogFrame(_ui, '#btnOpenSearch').click();
                }
            },
            {
                text: 'FILE_LIST_NEWTHUMBS',
                icon: "image",
                click: function(_ui) {
                    fpcm.dom.findElementInDialogFrame(_ui, '#btnCreateThumbs').click();
                }
            },
            {
                text: 'GLOBAL_DELETE',
                icon: "trash",
                click: function(_ui) {
                    fpcm.dom.findElementInDialogFrame(_ui, '#btnDeleteFiles').click();
                }
            }                            
        ];
        
        if (!fpcm.editor.insertGalleryDisabled(_mode)) {
            _btns.unshift(
            {
                text: 'FILE_LIST_INSERTGALLERY',
                icon: "images",
                click: function(_ui) {
                    fpcm.dom.findElementInDialogFrame(_ui, '#insertGallery').click();
                }
            });
        }

        fpcm.ui_dialogs.create({
            id: 'editor-html-filemanager',
            title: 'HL_FILES_MNG',
            closeButton: true,
            url: fpcm.vars.jsvars.filemanagerUrl + _mode,
            useSize: true,
            size: 'xl modal-fullscreen-lg-down',
            modalBodyClass: 'vh-75',
            dlButtons: _btns
        });   
    };

}
