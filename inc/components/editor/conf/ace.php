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
class ace implements \JsonSerializable {

    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Editor font size
     * @var string
     */
    protected string $fontSize;

    /**
     * Editor themen
     * @var string
     */
    protected string $theme;

    /**
     * Editor Mode
     * @var string
     */
    protected string $mode = 'ace/mode/html';

    /**
     * Autocompletion enabled
     * @var bool
     */
    protected bool $enableBasicAutocompletion = true;

    /**
     * Live autocompletion enabled
     * @var bool
     */
    protected bool $enableLiveAutocompletion = true;

    /**
     * Snippets for autocompletion enabled
     * @var bool
     */
    protected bool $enableSnippets = true;

    /**
     * Wrap lines
     * @var bool
     */
    protected bool $wrap = true;

    /**
     * Minimum number of editor lines
     * @var int
     */
    protected int $minLines = 15;

    /**
     * Meximum number of editor lines
     * @var int
     */
    protected int $maxLines = 50;
    
    /**
     * Constructor
     * @param \fpcm\model\system\config $config
     */
    public function __construct(\fpcm\model\system\config $config) {
        $this->fontSize = $config->system_editor_fontsize;
        $this->theme = sprintf('ace/theme/tomorrow%s', $config->system_darkmode ? '_night' : '');
    }

}
