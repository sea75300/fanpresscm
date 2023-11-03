<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\editor\conf;

/**
 * TinyMCE 5 based editor plugin config class
 * 
 * @package fpcm\components\editor
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class tinymceEditor5 implements \JsonSerializable {
    
    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Editor theme
     * @var string
     */
    public $theme = 'silver';

    /**
     * Editor language
     * @var string
     */
    public $language;

    /**
     * plugins setting
     * @var string
     */
    public $plugins;

    /**
     * custom_elements setting
     * @var string
     */
    public $custom_elements = 'readmore';

    /**
     * toolbar setting
     * @var string
     */
    public $toolbar;

    /**
     * link_class_list setting
     * @var string
     */
    public $link_class_list;

    /**
     * image_class_list setting
     * @var string
     */
    public $image_class_list;

    /**
     * link_list setting
     * @var string
     */
    public $link_list;

    /**
     * image_list setting
     * @var string
     */
    public $image_list;

    /**
     * textpattern_patterns setting
     * @var string
     */
    public $textpattern_patterns;

    /**
     * templates setting
     * @var string
     */
    public $templates;

    /**
     * autosave_prefix setting
     * @var string
     */
    public $autosave_prefix;

    /**
     * images_upload_url setting
     * @var string
     */
    public $images_upload_url;

    /**
     * automatic_uploads setting
     * @var string
     */
    public $automatic_uploads;

    /**
     * width setting
     * @var string
     */
    public $width = '100%';

    /**
     * min_height setting
     * @var string
     */
    public $min_height = 500;

    /**
     * file_picker_types setting
     * @var string
     */
    public $file_picker_types = ['image', 'file'];

    /**
     * pagebreak_separator setting
     * @var string
     */
    public $pagebreak_separator;

    /**
     * image_caption setting
     * @var string
     */
    public $image_caption = true;

    /**
     * image_caption setting
     * @var string
     */
    public $skin = 'oxide';

    /**
     * Constructor
     * @param \fpcm\model\system\config $config
     * @param array $pluginFolders
     * @param array $cssClasses
     * @param array $patterns
     * @param array $drafts
     * @param int $userId
     */
    public function __construct(
        \fpcm\model\system\config $config,
        array $pluginFolders,
        array $cssClasses,
        array $patterns,
        array $drafts,
        int $userId
    ) {
        
        $this->plugins = $pluginFolders;
        $this->link_class_list = $cssClasses;
        $this->image_class_list = $cssClasses;

        $this->toolbar = implode(' ', [
            'formatselect','fontsizeselect', '|',
            'bold', 'italic', 'underline', 'strikethrough', '|', 
            'forecolor', 'backcolor', '|',
            'alignleft', 'aligncenter', 'alignright', 'alignjustify', 'outdent', 'indent', '|',
            'subscript', 'superscript', 'table', 'toc', '|', 'bullist', 'numlist', '|',
            'pagebreak', 'hr', 'blockquote', '|',
            'link', 'unlink', 'anchor', 'image', 'media', '|',
            'fpcm_emoticons', 'charmap', 'insertdatetime', 'template', '|',
            'undo', 'redo', 'removeformat', 'searchreplace', 'fullscreen', 'code', 'restoredraft', '|',
            'emoticons', '|', 'help'
        ]);

        $this->textpattern_patterns = $patterns;
        $this->templates = $drafts;
        $this->autosave_prefix = 'fpcm-editor-as-' . $userId;
        $this->pagebreak_separator = \fpcm\model\pubtemplates\article::PAGEBREAK_TAG;
        $this->link_list = \fpcm\classes\tools::getFullControllerLink('ajax/autocomplete', ['src' => 'editorlinks']);
        $this->image_list = \fpcm\classes\tools::getFullControllerLink('ajax/autocomplete', ['src' => 'editorfiles']);

        $this->images_upload_url = \fpcm\classes\tools::getFullControllerLink('ajax/editor/imgupload');
        $this->automatic_uploads = true;

        if (\fpcm\model\system\session::getInstance()?->getCurrentUser()?->getUserMeta()?->system_darkmode) {
            $this->skin .= '-dark';
        }
        
        $this->language = $config->system_lang;
        
    }

    /**
     * INit editor plugins for comment editor
     * @return bool
     */
    public function prepareComments() : bool
    {
        
        $this->plugins = str_replace([
            'autosave',
            'template',
        ], '', $this->plugins);

        $this->toolbar = str_replace([
            'restoredraft',
            'template',
        ], '', $this->toolbar);


        $this->custom_elements = '';
        return true;
    }
}
