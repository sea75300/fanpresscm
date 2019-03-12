
/**
 * FanPress CM Editor Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor.initTinyMce = function() {

    fpcm.vars.jsvars.editorConfig.file_picker = function(callback, value, meta) {

        fpcm.editor.filePickerCallback = callback;

        tinymce.activeEditor.windowManager.open({
            title: fpcm.ui.translate('HL_FILES_MNG'),
            size: 'large',
            body: {
                type: 'panel',
                items: [{
                    type: 'htmlpanel',
                    html: fpcm.ui.createIFrame({
                        src: fpcm.vars.jsvars.filemanagerUrl + fpcm.vars.jsvars.filemanagerMode,
                        style: 'width:100%;height:100%;',
                        id: 'fpcm-dialog-editor-tinymce-filemanager'
                    })
                }]
            },
            buttons: [
                {
                    type:  'custom',
                    name: 'fmSearch',
                    text: fpcm.ui.translate('ARTICLES_SEARCH'),
                    disabled: false,
                    primary: false,
                    align: 'start'
                },
                {
                    type:  'custom',
                    name: 'fmNewThumbs',
                    text: fpcm.ui.translate('FILE_LIST_NEWTHUMBS'),
                    disabled: false,
                    primary: false,
                    align: 'start'
                },
                {
                    type:  'custom',
                    name: 'fmDelete',
                    text: fpcm.ui.translate('GLOBAL_DELETE'),
                    disabled: false,
                    primary: false,
                    align: 'start'
                },
                {
                    type:  'cancel',
                    name: 'fmClose',
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    disabled: false,
                    primary: true
                },                          
            ],
            initialData: {
                fmSearch: 'opensearch',
                fmNewThumbs: 'createThumbs',
                fmDelete: 'deleteFiles'
            },
            onAction: function(api, action) {

                if (!this.initialData[action.name]) {
                    return false;
                }

                jQuery('#fpcm-dialog-editor-tinymce-filemanager').contents().find('#' + this.initialData[action.name]).click();
                
            }
        });

        return true;
    }

    fpcm.vars.jsvars.editorConfig.onPaste = function(plugin, args) {
        var content = fpcm.editor_videolinks.replace(args.content);
        if (content === args.content) {
            return true;
        }

        fpcm.ui.showLoader(true);
        args.content = fpcm.editor_videolinks.createFrame(content, true);
        fpcm.ui.showLoader(false);
    };

    fpcm.vars.jsvars.editorConfig.onInitAfterStd = function(editor) {

        editor.ui.registry.addButton('fpcm_emoticons', {
            icon: 'emoticons',
            tooltip: 'Insert Current Date',
            disabled: false,
            onAction: function () {
                debugger;
            },
            onSetup: function (buttonApi) {
                
            }
        });

    };

    fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);

};