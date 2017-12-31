/**
 * FanPress CM TinyMCE Wrapepr Editor Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor_tinymce = {

    create: function(config) {

        params = {
            selector            : 'textarea',
            skin                : 'fpcm',
            theme               : 'modern',
            menubar             : false,
            relative_urls       : false,
            image_advtab        : true,
            resize              : true,
            convert_urls        : true,
            browser_spellcheck  : true,
            default_link_target : '_blank',
            branding            : false,    
        };
        
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
        
        if (config.autoresize_min_height !== undefined) {
            params.autoresize_min_height = config.autoresize_min_height ? config.autoresize_min_height : '250';
        }
        
        if (config.autoresize_min_height !== undefined) {
            params.autoresize_min_height = config.autoresize_min_height ? config.autoresize_min_height : '250';
        }
        
        if (config.onPaste !== undefined) {
            params.paste_preprocess = config.onPaste;
        }
        
        if (config.custom_elements) {
            params.custom_elements = config.custom_elements;
        }

        tinymce.init(params);

    }

};