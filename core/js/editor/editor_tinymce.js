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
            content_style       : 'body { font-size: ' + fpcm.vars.jsvars.editorDefaultFontsize + '; }'
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

        if (config.mobileConfig) {
            params.mobile = {
                theme: 'mobile',
                resize: 'both',
                plugins: config.mobileConfig.plugins,
                toolbar: config.mobileConfig.toolbar
            }
        }

        tinymce.init(params);
    }

};

if (fpcm.editor) {
    
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
        
        let cont = top.tinymce.activeEditor.getContent({format: 'text'});
        if (cont && cont.search('/gallery') != -1 ) {
            return true;
        }

        return false;
    };

}
