
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
        fpcm.editor.filePickerActions = {
            fmSearch: 'opensearch',
            fmNewThumbs: 'createThumbs',
            fmDelete: 'deleteFiles',
            fmGallery: 'insertGallery',
        };

        tinymce.activeEditor.windowManager.openUrl({
            title: fpcm.ui.translate('HL_FILES_MNG'),
            size: 'large',
            url: fpcm.vars.jsvars.filemanagerUrl + fpcm.vars.jsvars.filemanagerMode,
            id: 'fpcm-dialog-editor-tinymce-filemanager',
            buttons: [                
                {
                    type:  'custom',
                    name: 'fmGallery',
                    text: fpcm.ui.translate('FILE_LIST_INSERTGALLERY'),
                    disabled: fpcm.editor.insertGalleryDisabled(),
                    primary: false,
                    align: 'start'
                },
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
            onAction: function(api, action) {

                if (!fpcm.editor.filePickerActions[action.name]) {
                    return false;
                }

                api.sendMessage({
                    mceAction: 'clickFmgrBtn',
                    cmd: fpcm.editor.filePickerActions[action.name]
                });
            }
        });

        return true;
    }

    fpcm.vars.jsvars.editorConfig.onPaste = function(plugin, args) {
        var content = fpcm.editor_videolinks.replace(args.content);
        if (content === args.content) {
            return true;
        }

        fpcm.ui_loader.show();
        args.content = fpcm.editor_videolinks.createFrame(content, true);
        fpcm.ui_loader.hide();
    };

    fpcm.vars.jsvars.editorConfig.onInitAfterStd = function(editor) {

        editor.ui.registry.addButton('fpcm_emoticons', {
            icon: 'emoji',
            tooltip: fpcm.ui.translate('EDITOR_INSERTSMILEY'),
            disabled: false,
            onAction: function () {
                
                tinymce.activeEditor.windowManager.open({
                    title: fpcm.ui.translate('EDITOR_INSERTSMILEY'),
                    size: 'normal',
                    body: {
                        type: 'panel',
                        items: [{
                            type: 'collection',
                            name: 'smileyList'
                        }]
                    },
                    buttons: [
                        {
                            type:  'cancel',
                            text: fpcm.ui.translate('GLOBAL_CLOSE'),
                            disabled: false,
                            primary: true
                        },                          
                    ],
                    initialData: {
                        smileyList: fpcm.vars.jsvars.editorConfig.fpcmEmoticons
                    },
                    onAction: function (api, data) {

                        if (data.value) {
                            editor.insertContent(data.value);
                        }

                        api.close();
                    }
                });

            },
            onSetup: function (buttonApi) {
                fpcm.ajax.get('editor/smileys', {
                    quiet: true,
                    dataType: 'json',
                    data: {
                        json: true
                    },
                    execDone: function (items) {

                        fpcm.vars.jsvars.editorConfig.fpcmEmoticons = [];

                        for(var x = 0;x < items.length; x++) {
                            fpcm.vars.jsvars.editorConfig.fpcmEmoticons.push({
                                text: items[x].code,
                                value: ' ' + items[x].code + ' ',
                                icon: items[x].img
                            }   
                            );
                        }

                    }
                });
            }
        });

        editor.ui.registry.addButton('fpcm_readmore', {
            icon: 'page-break',
            tooltip: fpcm.ui.translate('EDITOR_HTML_BUTTONS_READMORE'),
            disabled: false,
            onAction: function () {
                
                tinymce.activeEditor.windowManager.open({
                    title: fpcm.ui.translate('EDITOR_HTML_BUTTONS_READMORE'),
                    size: 'large',
                    body: {
                        type: 'panel',
                        items: [{
                            type: 'textarea',
                            name: 'readMoreText',
                            placeholder: fpcm.ui.translate('EDITOR_HTML_BUTTONS_READMORE')
                        }]
                    },
                    buttons: [
                        {
                            type:  'cancel',
                            text: 'Cancel',
                            disabled: false,
                            primary: false
                        },                          
                        {
                            type:  'submit',
                            text: 'Insert',
                            disabled: false,
                            primary: true
                        },                          
                    ],
                    onSubmit: function (api) {

                        var data = api.getData();
                        if (data.readMoreText) {

                            if (data.readMoreText.search(/^(<\/?[\w\s="/.':;#-\/\?]+>)/i) === -1) {
                                data.readMoreText = '<p>' + data.readMoreText + '</p>';
                            }

                            editor.insertContent('<readmore>' + data.readMoreText + '</readmore>');
                        }

                        api.close();
                    }
                });

            }
        });

    };

    fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);

};