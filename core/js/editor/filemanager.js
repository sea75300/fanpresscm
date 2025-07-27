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

        let _btns = [{
            text: 'ARTICLES_SEARCH',
            icon: "search",
            click: function(_ui) {
                fpcm.dom.findElementInDialogFrame(_ui, '#btnOpenSearch').click();
            }
        }];
        
        
        if (fpcm.vars.jsvars.filemanagerPermissions.add) {
            _btns.push({
                text: 'FILE_LIST_UPLOADFORM',
                icon: "upload",
                primary: true,
                click: function(_ui) {
                    fpcm.dom.findElementInDialogFrame(_ui, '#btnFileUpload').click();
                }
            });
        }
        
        if (!fpcm.editor.insertGalleryDisabled(_mode)) {
            _btns.push({
                text: 'FILE_LIST_INSERTGALLERY',
                icon: "images",
                click: function(_ui) {

                    let _var = fpcm.dom.findElementInDialogFrame(_ui, '.fpcm-ui-list-checkbox:checked');
                    if (!_var.length) {
                        return false;
                    } 
                    
                    var values = [];
                    _var.map(function (idx, item) {
                        values.push(item.dataset.gallery);
                    });

                    if (!values.length) {
                        return false;
                    }

                    fpcm.editor.insertGalleryByEditor(values);
                    return false;                    
                }
            });
        }

        if (fpcm.vars.jsvars.filemanagerPermissions.thumbs) {
            _btns.push({
                text: 'FILE_LIST_NEWTHUMBS',
                icon: "image",
                click: function(_ui) {
                    fpcm.dom.findElementInDialogFrame(_ui, '#btnCreateThumbs').click();
                }
            });
        }

        if (fpcm.vars.jsvars.filemanagerPermissions.delete) {
            _btns.push({
                text: 'GLOBAL_DELETE',
                icon: "trash",
                click: function(_ui) {
                    fpcm.dom.findElementInDialogFrame(_ui, '#btnDeleteFiles').click();
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
            icon: {
                icon: 'folder-open'
            },
            dlButtons: _btns
        });   
    };

}
