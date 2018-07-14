<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Dashboard controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class info extends \fpcm\controller\abstracts\controller {

    /**
     * Get view path for controller
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'system/info';
    }

    public function process() : bool
    {
        $this->view->setViewVars([
            'content' => simplexml_load_string($this->language->getHelp())->xpath("/chapters/chapter[@ref=\"HL_HELP_SUPPORT\"]")[0],
            'licence' => file_get_contents(\fpcm\classes\dirs::getFullDirPath('', 'licence.txt'))
        ]);
        return true;
    }

}

?>