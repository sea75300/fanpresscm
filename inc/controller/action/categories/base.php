<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\categories;

/**
 * Category edit controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class base extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\requestFunctions
{

    use \fpcm\controller\traits\common\simpleEditForm,
        \fpcm\controller\traits\theme\nav\categories;
    
    /**
     *
     * @var \fpcm\model\categories\category
     */
    protected $category;

    protected $saveMessage = 'added';

    protected $tabHeadline = 'CATEGORIES_ADD';

    public function process()
    {
        $this->view->addButton(new \fpcm\view\helper\saveButton('categorySave'));
        $this->view->addJsFiles(['system/categories.js']);
        $this->view->addTabs('fpcm-category-tabs', [
            (new \fpcm\view\helper\tabItem('tabs-category'))
                ->setText($this->tabHeadline)
                ->setFile($this->getViewPath() . '.php')
        ]);
        
        $selectedGroups = explode(';', $this->category->getGroups());
        
        $checkFields = [];
        foreach ((new \fpcm\model\users\userRollList())->getUserRollsTranslated() as $rollname => $rollid) {
            $checkFields[] = (new \fpcm\view\helper\checkbox('category[groups][]', 'cat'.$rollid))
                ->setText($rollname)
                ->setValue($rollid)
                ->setSwitch(true)
                ->setSelected(in_array($rollid, $selectedGroups));
        }

        $this->assignFields([
            (new \fpcm\view\helper\textInput('category[name]'))
                    ->setValue($this->category->getName())
                    ->setAutoFocused(true)
                    ->setRequired(true)
                    ->setText('CATEGORIES_NAME')
                    ->setIcon('tag'),
            (new \fpcm\view\helper\textInput('category[iconpath]'))
                    ->setValue($this->category->getIconPath())
                    ->setType('url')
                    ->setText('CATEGORIES_ICON_PATH')
                    ->setIcon('link'),
            new \fpcm\components\fieldGroup($checkFields, 'CATEGORIES_ROLLS', new \fpcm\view\helper\icon('user-tag'))
        ]);

        $this->view->render();
    }
    
    public function oncategorySave()
    {
        $data = $this->request->fromPOST('category', [
            \fpcm\model\http\request::FILTER_STRIPSLASHES,
            \fpcm\model\http\request::FILTER_TRIM
        ]);

        $groups = implode(';', array_map('intval', ( $data['groups'] ?? [] ) ));
        $this->category->setGroups($groups);
        $this->category->setIconPath($data['iconpath']);
        $this->category->setName($data['name']);

        if (!trim($data['name']) || empty($data['groups'])) {
            $this->view->addErrorMessage('SAVE_FAILED_CATEGORY');
            return true;
        }

        $res = $this->category->getId()
             ? $this->category->update()
             : $this->category->save();

        if ($res === \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY) {
            $this->view->addErrorMessage('SAVE_FAILED_CATEGORY_EXISTS');
            return false;
        }

        if ($res === false) {            
            $this->view->addErrorMessage('SAVE_FAILED_CATEGORY');
            return false;
        }

        $this->redirect('categories/list', [$this->saveMessage => 1]);
        return true;
    }
    
}

?>