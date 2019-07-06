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
    
    fileOpenMode = fmgrMode;
        
    var size = fpcm.ui.getDialogSizes(top, 0.75);

    fpcm.ui.appendHtml('#fpcm-dialog-editor-html-filemanager', '<iframe id="fpcm-dialog-editor-html-filemanager-frame" class="fpcm-ui-full-width" src="' + fpcm.vars.jsvars.filemanagerUrl + fmgrMode + '"></iframe>');
    fpcm.ui.dialog({
        id       : 'editor-html-filemanager',
        dlMinWidth : size.width,
        dlMinHeight: size.height,
        modal    : true,
        resizable: true,
        title    : fpcm.ui.translate('HL_FILES_MNG'),
        dlButtons  : [
            {
                text: fpcm.ui.translate('ARTICLES_SEARCH'),
                icon: "ui-icon-search",
                click: function() {
                    jQuery(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#opensearch').click();
                }
            },
            {
                text: fpcm.ui.translate('FILE_LIST_NEWTHUMBS'),
                icon: "ui-icon-image",
                click: function() {
                    jQuery(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#createThumbs').click();
                }
            },
            {
                text: fpcm.ui.translate('GLOBAL_DELETE'),
                icon: "ui-icon-trash",
                click: function() {
                    jQuery(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#deleteFiles').click();
                }
            },
            {
                text: fpcm.ui.translate('GLOBAL_CLOSE'),
                icon: "ui-icon-closethick",                    
                click: function() {
                    jQuery(this).dialog('close');
                }
            }                            
        ],
        dlOnClose: function( event, ui ) {
            jQuery(this).empty();
        }
    });   
};
    
fpcm.editor.setSelectToDialog = function(obj) {
    jQuery(obj).find('.fpcm-ui-input-select').selectmenu({
        appendTo: "#" + jQuery(obj).attr('id')
    });
};