<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\categories;

/**
 * Category list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class categorylist extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\common\dataView,
        \fpcm\controller\traits\theme\nav\categories;

    /**
     *
     * @var \fpcm\model\categories\categoryList 
     */
    protected $list;

    /**
     *
     * @var \fpcm\model\users\userRollList
     */
    protected $rollList;

    /**
     *
     * @var bool
     */
    protected $countReadOnly = false;

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'categorylist';
    }

    /**
     * 
     * @return bool
     */
    public function request()
    {

        if ($this->request->hasMessage('added')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ADDCATEGORY');
        }

        if ($this->request->hasMessage('edited')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_EDITCATEGORY');
        }

        $this->list = new \fpcm\model\categories\categoryList();
        $this->rollList = new \fpcm\model\users\userRollList();

        $this->delete();
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function process()
    {
        $this->items = $this->list->getCategoriesAll();
        $this->itemsCount = count($this->items);

        $this->countReadOnly = $this->itemsCount < 2 ? true : false;
        $this->initDataView();

        $this->view->addJsFiles(['system/categories.js']);

        $this->view->addFromLibrary(
            'selectize_js',
            [ 'dist/js/selectize.min.js' ],
            [ 'dist/css/selectize.default.css' ]
        ); 

        $this->view->addJsLangVars(['CATEGORIES_ROLLS', 'SAVE_FAILED_CATEGORY']);

        $this->view->addAjaxPageToken('categories/massedit');
        $this->view->setFormAction('categories/list');

        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('addnew'))->setUrl(\fpcm\classes\tools::getFullControllerLink('categories/add'))->setText('GLOBAL_NEW')->setIcon('tag'),
            (new \fpcm\view\helper\button('massEdit', 'massEdit'))->setText('GLOBAL_EDIT')->setIcon('edit')->setIconOnly(true),
            (new \fpcm\view\helper\deleteButton('delete'))->setClass('fpcm ui-button-confirm')
        ]);

        $rolls = (new \fpcm\model\users\userRollList())->getUserRollsTranslated();
        
        $this->view->addJsVars([
            'masseditFields' => [
                'fieldIconPath' => (string) new \fpcm\components\masseditField(
                    (new \fpcm\view\helper\textInput('iconpath'))
                        ->setText('')
                        ->setType('url')
                        ->setText('CATEGORIES_ICON_PATH')
                        ->setIcon('link'),
                    ''
                ),
                'fieldRolls' => (string) new \fpcm\components\masseditField(
                    (new \fpcm\view\helper\select('rolls'))
                        ->setOptions($rolls)
                        ->setIsMultiple(true)
                        ->setSelected([])
                        ->setClass('col-12 col-sm-6 col-md-4')
                        ->setText('CATEGORIES_ROLLS')
                        ->setIcon('users'),
                ),
            ],
            'massEditSaveFailed' => 'SAVE_FAILED_CATEGORY'
        ]);

        $this->view->render();
        return true;
    }

    protected function getDataViewTabs() : array
    {
        return [
            (new \fpcm\view\helper\tabItem('tabs-'.$this->getDataViewName().'-list'))
                ->setText('HL_CATEGORIES_MNG')
                ->setFile('components/dataview__inline.php')
        ];
    }

    /**
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        return [
            (new \fpcm\components\dataView\column('select', ''))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('name', 'CATEGORIES_NAME'))->setSize(3),
            (new \fpcm\components\dataView\column('groups', 'CATEGORIES_ROLLS'))->setSize(3),
            (new \fpcm\components\dataView\column('icon', 'CATEGORIES_ICON_PATH'))->setSize(4)
        ];
    }

    /**
     * 
     * @param \fpcm\model\categories\category $category
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($category)
    {
        $rolls = $this->rollList->getRollsbyIdString($category->getGroups());

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('ids[]', 'ids' . $category->getId()))->setValue($category->getId())->setReadonly($this->countReadOnly)->setClass('fpcm-ui-list-checkbox'), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\editButton('editCat'))->setUrlbyObject($category), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('name', new \fpcm\view\helper\escape($category->getName())),
            new \fpcm\components\dataView\rowCol('groups', implode(', ', array_keys($rolls))),
            new \fpcm\components\dataView\rowCol('icon', $category->getCategoryImage()),
        ]);
    }
    
    private function delete()
    {
        if (!$this->buttonClicked('delete')) {
            return false;
        }
        
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }

        $id = $this->request->getIDs()[0] ?? false;
        if (!$id) {
            return true;
        }

        $category = new \fpcm\model\categories\category($id);
        if ($category->delete()) {
            $this->view->addNoticeMessage('DELETE_SUCCESS_CATEGORIES');
            return true;
        }

        $this->view->addErrorMessage('DELETE_FAILED_CATEGORIES');
        return false;
    }

}

?>
