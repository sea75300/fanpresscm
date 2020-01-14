<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\categories;

/**
 * Category edit controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class base extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    protected $category;

    protected $saveMessage = 'added';

    protected $tabHeadline = 'CATEGORIES_ADD';

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->categories;
    }

    protected function getViewPath() : string
    {
        return 'categories/editor';
    }

    public function request()
    {
        $this->save();
        return true;
    }

    protected function getHelpLink()
    {
        return 'HL_CATEGORIES_MNG';
    }

    protected function getActiveNavigationElement()
    {
        return 'submenu-itemnav-item-categories';
    }

    public function process()
    {
        $this->view->assign('userRolls', (new \fpcm\model\users\userRollList())->getUserRollsTranslated());
        $this->view->assign('category', $this->category);
        $this->view->assign('selectedGroups', explode(';', $this->category->getGroups()));
        $this->view->addButton(new \fpcm\view\helper\saveButton('categorySave'));
        $this->view->addJsFiles(['categories.js']);
        $this->view->addTabs('fpcm-category-tabs', [
            (new \fpcm\view\helper\tabItem('tabs-category'))->setText($this->tabHeadline)->setFile('categories/editor.php')
        ]);

        $this->view->render();
    }
    
    protected function save()
    {
        if (!$this->buttonClicked('categorySave')) {
            return true;
        }

        $data = $this->getRequestVar('category', [
            \fpcm\classes\http::FILTER_STRIPSLASHES,
            \fpcm\classes\http::FILTER_TRIM
        ]);

        if (!trim($data['name']) || empty($data['groups'])) {
            $this->view->addErrorMessage('SAVE_FAILED_CATEGORY');
            return true;
        }

        $groups = implode(';', array_map('intval', $data['groups']));
        $this->category->setGroups($groups);
        $this->category->setIconPath($data['iconpath']);
        $this->category->setName($data['name']);

        $res = $this->category->getId()
             ? $this->category->update()
             : $this->category->save();

        if ($res === false) {
            $this->view->addErrorMessage('SAVE_FAILED_CATEGORY');
            return false;
        }

        $this->redirect('categories/list', [$this->saveMessage => 1]);
        return true;
    }
}

?>