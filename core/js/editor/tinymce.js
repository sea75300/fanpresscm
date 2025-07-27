/**
 * FanPress CM TinyMCE Wrapepr Editor Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor_tinymce = {

    create: function(config) {

        params = {
            selector            : 'textarea',
            default_link_target : '_blank',
            insertdatetime_formats: [
                "%H:%M:%S",
                "%H:%M",
                "%r",
                "%I:%M %p",
                "%d.%m.%Y",
                "%d. %b %Y",
                "%Y-%m-%d",
                "%D",
            ],
            theme               : config.theme,
            menubar             : false,
            relative_urls       : false,
            image_advtab        : true,
            resize              : true,
            convert_urls        : true,
            browser_spellcheck  : true,
            branding            : false,
            element_format      : 'html',
            content_style       : 'body { font-size: ' + fpcm.vars.jsvars.editorDefaultFontsize + '; } figure > img { margin: 0.25rem; }'
        };

        if (config.skin !== undefined) {
            params.skin = config.skin;
        }

        if (config.width !== undefined) {
            params.width = config.width;
        }

        if (config.language !== undefined) {
            params.language = config.language;
        }

        if (config.plugins !== undefined) {
            params.plugins = config.plugins;
        }

        if (config.toolbar !== undefined) {
            params.toolbar1 = config.toolbar;
        }

        if (config.autosave_prefix !== undefined) {
            params.autosave_prefix              = config.autosave_prefix;
            params.autosave_retention           = config.autosave_retention ? config.autosave_retention : '15m';
            params.autosave_restore_when_empty  = false;
        }

        if (config.images_upload_url !== undefined && config.automatic_uploads !== undefined) {
            params.images_upload_url     = config.images_upload_url;
            params.automatic_uploads     = config.automatic_uploads;
            params.images_reuse_filename = true;
        }

        if (config.templates !== undefined) {
            params.templates = config.templates;
        }

        if (config.textpattern_patterns !== undefined) {
            params.textpattern_patterns = config.textpattern_patterns;
        }

        if (config.image_list !== undefined) {
            params.image_list = config.image_list;
        }

        if (config.link_list !== undefined) {
            params.link_list = config.link_list;
        }

        if (config.image_class_list !== undefined) {
            params.image_class_list = config.image_class_list;
        }

        if (config.link_class_list !== undefined) {
            params.link_class_list = config.link_class_list;
        }

        if (config.image_caption !== undefined) {
            params.image_caption = config.image_caption;
        }

        params.link_assume_external_targets = (config.link_assume_external_targets !== undefined
                                            ? config.link_assume_external_targets
                                            : true);

        if (config.image_caption !== undefined) {
            params.image_caption = config.image_caption;
        }

        if (config.file_picker !== undefined && typeof config.file_picker == 'function') {
            params.file_picker_callback = config.file_picker;
        }

        if (config.onInit !== undefined && typeof config.onInit == 'function') {
            params.setup = config.onInit;
        }
        else {
            params.setup = function(editor) {
                if (config.onInitAfterStd) {
                    config.onInitAfterStd(editor);
                }
            }
        }

        if (config.autoresize_min_height !== undefined) {
            params.autoresize_min_height = config.autoresize_min_height ? config.autoresize_min_height : '250';
        }

        if (config.min_height !== undefined) {
            params.min_height = config.min_height ? config.min_height : '250';
        }

        if (config.onPaste !== undefined) {
            params.paste_preprocess = config.onPaste;
        }

        if (config.custom_elements) {
            params.custom_elements = config.custom_elements;
        }

        if (config.file_picker_types) {
            params.file_picker_types = config.file_picker_types;
        }

        if (config.emoticons_append) {
            params.emoticons_append = config.emoticons_append;
        }

        if (config.skin) {
            params.skin = config.skin;
        }

        params.deprecation_warnings = false;

        tinymce.init(params);
    }

};

if (fpcm.editor) {

    fpcm.editor.initEditor = function() {

        fpcm.vars.jsvars.editorConfig.file_picker = function(callback, value, meta) {

            fpcm.editor.filePickerCallback = callback;
            fpcm.editor.filePickerActions = {
                fmUpload: 'btnFileUpload',
                fmSearch: 'btnOpenSearch',
                fmNewThumbs: 'btnCreateThumbs',
                fmDelete: 'btnDeleteFiles',
                fmGallery: 'btnInsertGallery',
            };

            _btns = [{
                type:  'custom',
                name: 'fmSearch',
                text: fpcm.ui.translate('ARTICLES_SEARCH'),
                disabled: false,
                primary: false,
                align: 'start'
            }];

            if (fpcm.vars.jsvars.filemanagerPermissions.add) {
                _btns.push({
                    type:  'custom',
                    name: 'fmUpload',
                    text: fpcm.ui.translate('FILE_LIST_UPLOADFORM'),
                    disabled: false,
                    primary: true,
                    align: 'start'
                });
            }

            if (!fpcm.editor.insertGalleryDisabled()) {
                _btns.push({
                    type:  'custom',
                    name: 'fmGallery',
                    text: fpcm.ui.translate('FILE_LIST_INSERTGALLERY'),
                    disabled: false,
                    primary: false,
                });
            }

            if (fpcm.vars.jsvars.filemanagerPermissions.thumbs) {
                _btns.push({
                    type:  'custom',
                    name: 'fmNewThumbs',
                    text: fpcm.ui.translate('FILE_LIST_NEWTHUMBS'),
                    disabled: false,
                    primary: false,
                });
            }


            if (fpcm.vars.jsvars.filemanagerPermissions.delete) {
                _btns.push({
                    type:  'custom',
                    name: 'fmDelete',
                    text: fpcm.ui.translate('GLOBAL_DELETE'),
                    disabled: false,
                    primary: false,
                });
            }

            _btns.push({
                type:  'cancel',
                name: 'fmClose',
                text: fpcm.ui.translate('GLOBAL_CLOSE'),
                disabled: false,
                primary: true
            });

            tinymce.activeEditor.windowManager.openUrl({
                title: fpcm.ui.translate('HL_FILES_MNG'),
                size: 'large',
                url: fpcm.vars.jsvars.filemanagerUrl + fpcm.vars.jsvars.filemanagerMode,
                id: 'fpcm-dialog-editor-tinymce-filemanager',
                buttons: _btns,
                onAction: function(api, action) {

                    if (!fpcm.editor.filePickerActions[action.name]) {
                        return false;
                    }

                    api.sendMessage({
                        mceAction: 'clickFmgrBtn',
                        cmd: fpcm.editor.filePickerActions[action.name],
                        validSource: window.location.href
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
                                });
                            }

                        }
                    });
                }
            });

            var _galleryPlaceholderClass = 'fpcm-content-gallery-placeholder';

            editor.on('ResolveName', function (e) {
                if (e.target.nodeName === 'FIGURE' && editor.dom.hasClass(e.target, _galleryPlaceholderClass)) {
                    e.name = 'gallery';
                }
            });

            editor.on('BeforeSetContent', function (e) {

                let _data = e.content.match(/\[gallery\](.*)\[\/gallery\]/i);
                let _hasGallery = e.content.search(_galleryPlaceholderClass) > 0;
                if (!_data || _hasGallery) {
                    return true;
                }

                let _list = _data[1].split('|');
                if (!_list.length) {
                    return true;
                }

                let _repl = [];

                for (var i = 0; i < _list.length; i++) {

                    if (!_list[i]) {
                        continue;
                    }

                    let _item = _list[i].split(/(thumb:)?([a-z0-9_.\-\/]*)(:link)?/i);
                    if (!_item || !_item.length) {
                        continue;
                    }

                    let _imgurl = fpcm.vars.jsvars.uploadFileRoot + (_item[1] === fpcm.vars.jsvars.galleryThumbStr ? _item[2].replace('/', '/thumbs/') : _item[2]);
                    _repl.push('<img src="' + _imgurl + '" class="fpcm-content-gallery-item" data-mce-resize="false" data-mce-placeholder="1"  data-item="' + _list[i] + '" />');
                }

                if (!_repl.length) {
                    return true;
                }

                _repl.unshift('<figure role="group" class="' + _galleryPlaceholderClass + '" data-mce-placeholder="1" data-placeholder="' + _data[0] + '">');
                _repl.push('</figure>');

                e.content = e.content.replace(_data[0], _repl.join(''));

                return true;
            });

            editor.on('PreInit', function () {

                editor.serializer.addNodeFilter('figure', function (_nodes) {

                    if (!_nodes.length) {
                        return true;
                    }

                    for (var i = 0; i < _nodes.length; i++) {

                        if (!_nodes[i]) {
                            continue;
                        }

                        let _node = _nodes[i];
                        if (_node.attr('class') !== _galleryPlaceholderClass) {
                            continue;
                        }

                        _node.value = _node.attr('data-placeholder');
                        _node.raw = true;
                        _node.type = 3;
                    }
                });
            });

            editor.on('NodeChange', function (_params) {

                if (!_params.selectionChange || _params.element.nodeName != 'FIGURE' || !_params.element.classList.contains(_galleryPlaceholderClass)) {
                    return true;
                }

                if (!_params.element.children.length) {
                    return true;
                }

                let _elList = [];
                for (var i = 0; i < _params.element.children.length; i++) {
                    _elList.push(_params.element.children[i].dataset.item);
                }

                if (!_elList.length) {
                    return true;
                }

                _params.element.dataset.placeholder = '[gallery]' + _elList.join('|') + '[/gallery]';
                return true;

            });

        };

        fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);

    };

    fpcm.editor.initToolbar = function () {
        return true;
    };

    fpcm.editor.insertThumbByEditor = function (url, title) {

        fpcm.editor.filePickerCallback(url, {
            alt: title,
            text: title
        });

        top.tinymce.activeEditor.windowManager.close();
    };

    fpcm.editor.insertFullByEditor = function (url, title) {

        fpcm.editor.filePickerCallback(url, {
            alt: title,
            text: title
        });

        top.tinymce.activeEditor.windowManager.close();
    };

    fpcm.editor.insertGalleryByEditor = function (_values) {

        if (!_values.length) {
            return false;
        }

        top.tinymce.activeEditor.insertContent(fpcm.editor.getGalleryReplacement(_values) + fpcm.vars.jsvars.editorGalleryTagEnd);
        top.tinymce.activeEditor.windowManager.close();
        top.tinymce.activeEditor.windowManager.close();

    };

    fpcm.editor.insertGalleryDisabled = function (_mode) {

        if (_mode === undefined) {
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
        if (_cont && _cont.search('/gallery') != -1 ) {
            return true;
        }

        return false;
    };

}
