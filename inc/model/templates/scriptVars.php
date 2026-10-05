<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates;


/**
 * Twig based script vars wrapper
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
class scriptVars implements \JsonSerializable {

    const UI_VAR_MESSAGES = 'messages';

    const UI_VAR_LANG = 'lang';

    /**
     * UI vars
     * @var array
     */
    private array $ui = [];

    /**
     * Script vars
     * @var array
     */
    private array $jsvars = [];

    /**
     * Action path
     * @var string
     */
    private string $ajaxActionPath = '';

    /**
     * Spinner url
     * @var string
     */
    private string $spinnerUrl = '';

    /**
     * Action path
     * @var bool
     */
    private bool $ajaxRefreshDisable = false;

    /**
     * Constructor
     * @param array $ui
     * @param array $jsvars
     */
    public function __construct(
        array $ui = [],
        array $jsvars = []
    )
    {

        $this->ui = array_merge_recursive([
            self::UI_VAR_MESSAGES => [],
            self::UI_VAR_LANG => []
        ], $ui);


        $l = \fpcm\classes\language::getInstance();

        $langVars = $this->ui[self::UI_VAR_LANG] ?? [];
        if (count($langVars)) {
            $this->ui[self::UI_VAR_LANG] = array_combine($langVars, array_map([$l, 'translate'], $langVars));
        }

        $this->jsvars = $jsvars;
        
        $this->spinnerUrl = \fpcm\classes\dirs::getPublicAssetUrl('spinner.gif');
        $this->ajaxActionPath = \fpcm\classes\tools::getFullControllerLink('ajax/');
        $this->ajaxRefreshDisable = defined('FPCM_DISABLE_AJAX_CRONJOBS_PUB') && FPCM_DISABLE_AJAX_CRONJOBS_PUB;
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return ['vars' => get_object_vars($this)];
    }

}
