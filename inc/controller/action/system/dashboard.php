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

class dashboard extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\common\isAccessibleTrue;
    
    /**
     * Get view path for controller
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'dashboard/index';
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'hl_dashboard';
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->view->addJsLangVars(['DASHBOARD_LOADING']);
        $this->view->addJsFiles(['dashboard.js', 'ui/dnd.js']);
        $this->view->addFromLibrary('sortable_js', [
            'Sortable.min.js'
        ]);
        
        $dropdown = new \fpcm\view\helper\dropdown('dashboardAction');
        $dropdown->setText('HL_OPTIONS')->setUiSize('btn-sm')->setIcon('bars');
        $dropdown->setOptions([
            
            (new \fpcm\view\helper\dropdownItem('resetDashboardSettings'))
                ->setText('USERS_META_RESET_DASHBOARD')
                ->setIcon('undo')
                ->setValue('1'),
            
            (new \fpcm\view\helper\dropdownItem('resetDashboardSettings2'))
                ->setText('DASHBOARD_MANAGE_CONTAINER')
                ->setIcon('box')
                ->setValue('2'),
        ]);
        
        $this->view->addButton($dropdown);
        
    }

}

?>