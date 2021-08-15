<?php

/**
 * User add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

class useradd extends userbase {

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
            (new \fpcm\view\helper\checkbox('data[passInfoUser]'))->setText('USERS_SENDUSERINFO')
        ]);
        $this->view->setFormAction('users/add');
        $this->view->assign('showDisableButton', false);
        $this->view->assign('showExtended', false);
        $this->view->assign('showImage', false);
        $this->view->assign('twoFaAuth', false);
        $this->view->render();
    }
    
    protected function initTabs()
    {
        $tabs = [];
        $tabs[] = (new \fpcm\view\helper\tabItem('edit'))->setText('USERS_ADD')->setFile( $this->getViewPath() . '.php');       
        $this->view->addTabs('users', $tabs, 'fpcm ui-tabs-autoinit');
        
    }

}

?>
