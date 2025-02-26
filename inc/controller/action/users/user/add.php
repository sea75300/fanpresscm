<?php

/**
 * User add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users\user;

class add extends base {

    /**
     *
     * @var \fpcm\model\users\author
     */
    protected $author;

    public function process()
    {
        parent::process();
        
        $this->view->addButtons([
            (new \fpcm\view\helper\saveButton('userSave'))->setPrimary(),
            (new \fpcm\view\helper\checkbox('data[passInfoUser]'))->setText('USERS_SENDUSERINFO')->setSwitch(true)->setWrapperClass('ms-2 mb-0 mt-1')
        ]);
        $this->view->setFormAction('users/add');
        $this->view->assign('showDisableButton', false);
        $this->view->assign('showExtended', false);
        $this->view->assign('showImage', false);
        $this->view->assign('twoFaAuth', false);
        $this->view->assign('createInfo', '');
        $this->view->assign('changeInfo', '');
        $this->view->render();
    }
    
    protected function initTabs()
    {
        $tabs = [];
        $tabs[] = (new \fpcm\view\helper\tabItem('edit'))->setText('USERS_ADD')->setFile( $this->getViewPath() . '.php');       
        $this->view->addTabs('users', $tabs, 'fpcm ui-tabs-autoinit');
        
    }

}
