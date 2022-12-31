<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Dashboard controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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

    /**
     * 
     * @return bool
     */
    public function process() : bool
    {
        $this->view->setViewVars([
            'content' => simplexml_load_string($this->language->getHelp())->xpath("/chapters/chapter[@ref=\"HL_HELP_SUPPORT\"]")[0],
            'licence' => file_get_contents(\fpcm\classes\dirs::getFullDirPath('', 'licence.txt')),
            'backdrop' => (new \fpcm\model\files\backdropImage(true))->getCredits()
        ]);
        
        $this->view->assign('tabContentClass', 'fpcm ui-background-white-50p');
        
        $this->view->addTabs('supports', [
            (new \fpcm\view\helper\tabItem('support'))
                ->setText('HL_HELP_SUPPORT')
                ->setFile( $this->getViewPath() . '.php' )
                ->setState(\fpcm\view\helper\tabItem::STATE_ACTIVE )
        ]);
        
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function initPermissionObject(): bool
    {
        return true;
    }

}

?>