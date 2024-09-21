<?php

/**
 * User roll add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users\roll;

class edit extends base {

    use \fpcm\controller\traits\users\savePermissions;

    protected $update = true;

    /**
     *
     * @var string
     */
    protected $headlineVar = 'USERS_ROLL_EDIT';

    public function request()
    {
        $this->rollId = $this->request->getID();

        if (!$this->rollId) {
            $this->redirect('users/list');
            return false;
        }

        $this->getRollObject($this->rollId);

        if (!$this->userRoll->exists()) {
            $this->view = new \fpcm\view\error('LOAD_FAILED_ROLL', 'users/list');
            return true;
        }

        $this->view->setFormAction($this->userRoll->getEditLink(), [], true);

        if ($this->permissions->system->permissions) {
            $this->fetchRollPermssions();
        }

        return parent::request();
    }

    /**
     *
     * @return bool
     */
    public function process() {

        if ($this->permissions->system->permissions) {
            $this->assignToView();
        }

        return parent::process();
    }

}
