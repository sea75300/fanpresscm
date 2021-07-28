<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

use Jfcherng\Diff\Differ;
use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;
use Jfcherng\Diff\Renderer\RendererConstant;
/**
 * Article edit controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class revision extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\isAccessible,
           \fpcm\controller\interfaces\requestFunctions {

    /**
     *
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     *
     * @var \fpcm\model\categories\categoryList
     */
    protected $categoryList;

    /**
     *
     * @var \fpcm\model\articles\article
     */
    protected $article = null;

    /**
     *
     * @var \fpcm\model\articles\article
     */
    protected $revision = null;

    /**
     *
     * @var int
     */
    protected $rid = 0;

    /**
     *
     * @var int
     */
    protected $aid = 0;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return ($this->permissions->article->edit || $this->permissions->article->editall) && $this->permissions->article->revisions;
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'articles_editor';
    }

    /**
     * see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'articles/revisiondiff';
    }

    /**
     * 
     * @return string
     */
    protected function getActiveNavigationElement()
    {
        return 'itemnav-id-editnews';
    }

    /**
     * @see \fpcm\controller\abstracts\controller::request()
     * @return bool
     */
    public function request()
    {        
        $this->aid = $this->request->fromGET('aid', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        $this->rid = $this->request->fromGET('rid', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        if (!$this->aid || !$this->rid) {
            $this->view = new \fpcm\view\error('GLOBAL_NOTFOUND');
            return false;
        }

        $this->article = new \fpcm\model\articles\article($this->aid);

        if (!$this->article->exists()) {
            $this->view = new \fpcm\view\error('LOAD_FAILED_ARTICLE', 'articles/listall');
            return false;
        }

        $this->revision = clone $this->article;
        $this->revision->getRevision($this->rid);

        $this->userList     = new \fpcm\model\users\userList();
        $this->categoryList = new \fpcm\model\categories\categoryList();

        return true;
    }

    /**
     * @see \fpcm\controller\abstracts\controller::process()
     * @return mixed
     */
    public function process()
    {
        $this->view->assign('article', $this->article);
        $this->view->assign('revision', $this->revision);
        
        $categories = $this->categoryList->getCategoriesNameListAll();
        
        $this->view->assign('categoriesArticle', array_keys(array_intersect($categories, $this->article->getCategories())));
        $this->view->assign('categoriesRevision', array_keys(array_intersect($categories, $this->revision->getCategories())));
        $this->view->assign('commentEnabledGlobal', false);
        $this->view->assign('showArchiveStatus', true);
        $this->view->assign('showDraftStatus', true);

        $this->view->setFormAction(
            'articles/revision', [
                'aid' => $this->aid, 
                'rid' => $this->rid
            ]
        );
        
        $this->view->addJsVars([
            'articleId' => $this->aid
        ]);
        
        $revision = [];

        array_map(function ($value) use (&$revision) {
            
            $ddI = new \fpcm\view\helper\dropdownItem();
            $ddI->setUrl($this->getControllerLink('articles/revision', [
                    'aid' => $this->aid,
                    'rid' => $value
            ]));
            $ddI->setValue($value);
            $ddI->setText((string) new \fpcm\view\helper\dateText($value));
            $revision[] = $ddI;

        }, array_keys($this->article->getRevisions()));
        
        $this->view->addButtons([
            (new \fpcm\view\helper\dropdown('revisionList'))
                ->setOptions($revision)
                ->setSelected($this->rid),
            (new \fpcm\view\helper\linkButton('backToArticel'))
                ->setUrl($this->article->getEditLink().'&rg=3')
                ->setText('EDITOR_BACKTOCURRENT')
                ->setIcon('chevron-circle-left'),    
            (new \fpcm\view\helper\submitButton('revisionRestore'))
                ->setText('EDITOR_REVISION_RESTORE')
                ->setIcon('undo')
                ->setReadonly($this->article->isInEdit()),
        ]);
        
        include_once \fpcm\classes\loader::libGetFilePath('Jfcherng');

        $users = $this->userList->getUsersByIds([
            $this->article->getCreateuser(),
            $this->article->getChangeuser(),
            $this->revision->getCreateuser(),
            $this->revision->getChangeuser(),
        ]);
        
        $this->view->assign('articleCreate', $this->language->translate('GLOBAL_USER_ON_TIME', [
            '{{username}}' => isset($users[$this->article->getCreateuser()]) ? $users[$this->article->getCreateuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getCreatetime())           
        ]));

        $this->view->assign('articleChange', $this->language->translate('GLOBAL_USER_ON_TIME', [
            '{{username}}' => isset($users[$this->article->getChangeuser()]) ? $users[$this->article->getChangeuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getChangetime())
        ]));
        
        $this->view->assign('revisionCreate', $this->language->translate('GLOBAL_USER_ON_TIME', [
            '{{username}}' => isset($users[$this->revision->getCreateuser()]) ? $users[$this->revision->getCreateuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->revision->getCreatetime())           
        ]));

        $this->view->assign('revisionChange', $this->language->translate('GLOBAL_USER_ON_TIME', [
            '{{username}}' => isset($users[$this->revision->getChangeuser()]) ? $users[$this->revision->getChangeuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->revision->getChangetime())
        ]));
        
        try {
            $differContent = new Differ([$this->revision->getContent()], [$this->article->getContent()]);
            $rendererContent = RendererFactory::make('Combined', [
                'detailLevel' => 'word',
                'language' => 'eng',
                'lineNumbers' => false,
                'showHeader' => false
            ]);

            $differTitel = new Differ([$this->revision->getTitle()], [$this->article->getTitle()]);
            $rendererTitle = RendererFactory::make('Combined', [
                'detailLevel' => 'char',
                'language' => 'eng',
                'lineNumbers' => false,
                'showHeader' => false
            ]);
            
        } catch (\Exception $exc) {
            $this->view = new \fpcm\view\error($exc->getMessage());
            exit;
        }
        
        $this->view->assign('diffResultTitle', html_entity_decode($rendererTitle->render($differTitel)) );
        $this->view->assign('diffResultText', html_entity_decode($rendererContent->render($differContent)) );
        
        $this->view->addTabs('article', [
            (new \fpcm\view\helper\tabItem('article'))->setText('EDITOR_STATUS_REVISION')->setFile('articles/revisiondiff.php')
        ]);
        
        $this->view->render();
    }
    
    protected function onRevisionRestore()
    {
        if (!$this->rid || !$this->article->restoreRevision($this->rid)) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLEREVRESTORE');
            return false;
        }

        $this->redirect('articles/edit', [
            'id' => $this->aid,
            'revrestore' => 1
        ]);

        return true;
    }

}
