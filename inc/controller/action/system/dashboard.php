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
        $this->view->addJsFiles(['common/dashboard.js', 'ui/dnd.js']);
        $this->view->addFromLibrary('sortable_js', [
            'Sortable.min.js'
        ]);

        $this->view->addOffCanvas('DASHBOARD_MANAGE_CONTAINER', 'dashboard/manage');

        $btn = new \fpcm\view\helper\button('dashboardAction');
        $btn->setText('DASHBOARD_MANAGE_CONTAINER')->setIcon('bars')
            ->setAria([
                'controls' => 'offcanvasInfo'
            ])
            ->setData([
                'bs-toggle' => "offcanvas",
                'bs-target' => "#offcanvasInfo",
            ]);

        $this->view->addButton($btn);

    }

}
