<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\categories;

/**
 * Category edit controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class categoryedit extends base {

    public function request()
    {
        $this->saveMessage = 'edited';
        $this->tabHeadline = 'CATEGORIES_EDIT';
        
        $id = $this->request->getID();
        
        if ($id === null) {
            $this->redirect('categories/list');
        }

        $this->category = new \fpcm\model\categories\category($id);
        if (!$this->category->exists()) {
            $this->view = new \fpcm\view\error('LOAD_FAILED_CATEGORY', 'categories/list');
            return false;
        }

        return true;
    }

    public function process()
    {
        $this->view->setFormAction($this->category->getEditLink(), [], true);
        parent::process();
    }

}

?>