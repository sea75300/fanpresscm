<?php

/**
 * Category list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\categories;

class categorylist extends \fpcm\controller\abstracts\controller {

    protected $list;
    protected $rollList;

    protected function getViewPath()
    {
        return 'components/dataview';
    }

    protected function getPermissions()
    {
        return ['system' => 'categories'];
    }

    protected function getHelpLink()
    {
        return 'hl_options';
    }

    public function request()
    {

        $this->list = new \fpcm\model\categories\categoryList();
        $this->rollList = new \fpcm\model\users\userRollList();

        if ($this->getRequestVar('added')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ADDCATEGORY');
        }

        if ($this->getRequestVar('edited')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_EDITCATEGORY');
        }

        if ($this->buttonClicked('delete') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        if ($this->buttonClicked('delete') && !is_null($this->getRequestVar('ids'))) {
            $category = new \fpcm\model\categories\category($this->getRequestVar('ids'));

            if ($category->delete()) {
                $this->view->addNoticeMessage('DELETE_SUCCESS_CATEGORIES');
            } else {
                $this->view->addErrorMessage('DELETE_FAILED_CATEGORIES');
            }
        }

        return true;
    }

    public function process()
    {
        $categoryList = $this->list->getCategoriesAll();
        $countReadOnly = count($categoryList) === 1 ? true : false;

        $dataView = new \fpcm\components\dataView\dataView('categorylist');
        $dataView->addColumns([
            (new \fpcm\components\dataView\column('select', ''))->setSize('05')->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('icon', 'CATEGORIES_ICON_PATH'))->setSize(3),
            (new \fpcm\components\dataView\column('name', 'CATEGORIES_NAME'))->setSize(3),
            (new \fpcm\components\dataView\column('groups', 'CATEGORIES_ROLLS'))->setAlign('center'),
        ]);

        /* @var $category \fpcm\model\categories\category */
        foreach ($categoryList as $category) {

            $rolls = $this->rollList->getRollsbyIdsTranslated(explode(';', $category->getGroups()));

            $dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\radiobutton('ids', 'ids'.$category->getId()))->setValue($category->getId())->setReadonly($countReadOnly), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\editButton('editCat'))->setUrlbyObject($category) , '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    new \fpcm\components\dataView\rowCol('icon', $category->getCategoryImage() ),
                    new \fpcm\components\dataView\rowCol('name', new \fpcm\view\helper\escape($category->getName())),
                    new \fpcm\components\dataView\rowCol('groups', implode(', ', array_keys($rolls)))
                ]
            ));

        }

        $this->view->addDataView($dataView);
        $this->view->addJsFiles(['categories.js']);
        $this->view->assign('headline', 'HL_CATEGORIES_MNG');

        $this->view->setFormAction('categories/list');
        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('addnew'))->setUrl(\fpcm\classes\tools::getFullControllerLink('categories/add'))->setText('CATEGORIES_ADD')->setIcon('file-o')->setClass('fpcm-loader'),
            (new \fpcm\view\helper\deleteButton('delete'))->setClass('fpcm-ui-button-confirm')
        ]);

        $this->view->render();
    }

}

?>
