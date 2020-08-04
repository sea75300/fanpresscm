/**
 * FanPress CM Filemanager Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor.showFileManager = function(fmgrMode) {
        
    if (fmgrMode === undefined) {
        fmgrMode = fpcm.vars.jsvars.filemanagerMode;
    }
        
    var size = fpcm.ui.getDialogSizes(top, 0.75);

    fpcm.dom.appendHtml('#fpcm-dialog-editor-html-filemanager', '<iframe id="fpcm-dialog-editor-html-filemanager-frame" class="fpcm-ui-full-width" src="' + fpcm.vars.jsvars.filemanagerUrl + fmgrMode + '"></iframe>');
    fpcm.ui.dialog({
        id       : 'editor-html-filemanager',
        dlMinWidth : size.width,
        dlMinHeight: size.height,
        modal    : true,
        resizable: true,
        title    : fpcm.ui.translate('HL_FILES_MNG'),
        dlButtons  : [
            {
                text: fpcm.ui.translate('FILE_LIST_INSERTGALLERY'),
                icon: "ui-icon-suitcase",
                disabled: fpcm.editor.insertGalleryDisabled(fmgrMode),
                click: function() {
                    fpcm.dom.fromTag(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#insertGallery').click();
                }
            },
            {
                text: fpcm.ui.translate('ARTICLES_SEARCH'),
                icon: "ui-icon-search",
                click: function() {
                    fpcm.dom.fromTag(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#opensearch').click();
                }
            },
            {
                text: fpcm.ui.translate('FILE_LIST_NEWTHUMBS'),
                icon: "ui-icon-image",
                click: function() {
                    fpcm.dom.fromTag(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#createThumbs').click();
                }
            },
            {
                text: fpcm.ui.translate('GLOBAL_DELETE'),
                icon: "ui-icon-trash",
                click: function() {
                    fpcm.dom.fromTag(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#deleteFiles').click();
                }
            },
            {
                text: fpcm.ui.translate('GLOBAL_CLOSE'),
                icon: "ui-icon-closethick",                    
                click: function() {
                    fpcm.dom.fromTag(this).dialog('close');
                }
            }                            
        ],
        dlOnClose: function( event, ui ) {
            fpcm.dom.fromTag(this).empty();
        }
    });   
};
    
fpcm.editor.setSelectToDialog = function(obj) {
    fpcm.dom.fromTag(obj).find('.fpcm-ui-input-select').selectmenu({
        appendTo: "#" + fpcm.dom.fromTag(obj).attr('id')
    });
};