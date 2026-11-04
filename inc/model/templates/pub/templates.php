<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub;


/**
 * Templates list loader
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
class templates extends \fpcm\model\abstracts\staticModel {

    private string $basePath = '';

    public function __construct()
    {
        parent::__construct();
        $this->basePath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_STYLES);
    }

    private function getPattern() : string
    {
        return '/*/*.html' . ($this->config->system_twig ? '.twig' : '');
    }

    /**
     * Fetch template files from 
     * @return array
     */
    public function get() : array
    {
        $pattern = $this->getPattern();

        fpcmLogSystem($this->basePath . $pattern);
        
        $files = glob($this->basePath . $pattern);        
        if (!$files || !count($files)) {
            return [];
        }

        $return = [];
        foreach ($files as $file) {
            $file = new file($file);
            $return[] = $file;
        }

        return $return;
    }

}
