<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\editor\conf;

/**
 * TinyMCE 5 based editor plugin config class
 *
 * @package fpcm\components\editor\conf
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
class sun implements \JsonSerializable {

    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * language
     * @var string
     */
    protected string $lang;

    /**
     * Editor mode
     * @var string
     */
    //protected string $mode = 'inline';

    /**
     * Link to new window
     * @var bool
     */
    protected bool $linkTargetNewWindow = true;

    /**
     * Editor button list
     * @var string
     */
    protected array $buttonList;

    /**
     * Editor width
     * @var string
     */
    protected string $width = '100%';

    /**
     * Editor-height
     * @var string
     */
    protected string $minHeight = '500px';

    /**
     * Editor font size unit
     * @var string
     */
    protected string $fontSizeUnit = 'pt';

    /**
     * Default style
     * @var string
     */
    protected string $defaultStyle;

    /**
     * Constructor
     * @param \fpcm\model\system\config $config
     */
    public function __construct(\fpcm\model\system\config $config) {

        $this->lang = $config->system_lang;
        
        $this->defaultStyle = sprintf('font-size: %s', $config->system_editor_fontsize);

        $this->buttonList = [
            [
                "formatBlock",
                "fontSize",
            ],
            [
                "bold",
                "italic",
                "underline",
                "strike"
            ],
            [
                "fontColor",
                "hiliteColor"
            ],
            [
                "align",
                "outdent"
            ],
            [
                "indent",
                "subscript",
                "superscript",
                "table",
                "list"                
            ],
            [
                "horizontalRule",
                "blockquote",
            ],
            [
                "link",
                "image",
                "imageGallery",
                "video",
                "audio"                
            ],
            [                
                "template"                   
            ],
            [
                "undo",
                "redo",

                "removeFormat",
                "fullScreen",
                "showBlocks",
                "codeView",
                
                
            ]
        ];

    }

}
