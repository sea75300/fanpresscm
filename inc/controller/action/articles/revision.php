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
 * @copyright (c) 2011-2018, Stefan Seehafer
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
        $aid = $this->request->fromGET('aid', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        $rid = $this->request->fromGET('rid', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        if (!$aid || !$rid) {
            $this->view = new \fpcm\view\error('GLOBAL_NOTFOUND');
            return false;
        }

        $this->article = new \fpcm\model\articles\article($aid);

        if (!$this->article->exists()) {
            $this->view = new \fpcm\view\error('LOAD_FAILED_ARTICLE', 'articles/listall');
            return false;
        }

        $this->revision = clone $this->article;
        $this->revision->getRevision($rid);

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
                'aid' => $this->article->getId(), 
                'rid' => $this->revision->getId()
            ],
            true
        );

        $this->view->addButton((new \fpcm\view\helper\linkButton('backToArticel'))
                ->setUrl($this->article->getEditLink().'&rg=3')
                ->setText('EDITOR_BACKTOCURRENT')
                ->setIcon('chevron-circle-left'), 2);

        include_once \fpcm\classes\loader::libGetFilePath('Jfcherng');

        $users = $this->userList->getUsersByIds([
            $this->article->getCreateuser(),
            $this->article->getChangeuser(),
            $this->revision->getCreateuser(),
            $this->revision->getChangeuser(),
        ]);
        
        $this->view->assign('articleCreate', $this->language->translate('EDITOR_AUTHOREDIT', [
            '{{username}}' => isset($users[$this->article->getCreateuser()]) ? $users[$this->article->getCreateuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getCreatetime())           
        ]));

        $this->view->assign('articleChange', $this->language->translate('EDITOR_LASTEDIT', [
            '{{username}}' => isset($users[$this->article->getChangeuser()]) ? $users[$this->article->getChangeuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->article->getChangetime())
        ]));
        
        $this->view->assign('revisionCreate', $this->language->translate('EDITOR_AUTHOREDIT', [
            '{{username}}' => isset($users[$this->revision->getCreateuser()]) ? $users[$this->revision->getCreateuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->revision->getCreatetime())           
        ]));

        $this->view->assign('revisionChange', $this->language->translate('EDITOR_LASTEDIT', [
            '{{username}}' => isset($users[$this->revision->getChangeuser()]) ? $users[$this->revision->getChangeuser()]->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->revision->getChangetime())
        ]));
        
        try {
            $differ = new Differ([$this->revision->getContent()], [$this->article->getContent()]);
            $renderer = RendererFactory::make('Combined', [
                'detailLevel' => 'word',
                'language' => 'eng',
                'lineNumbers' => false
            ]);
            
        } catch (\Exception $exc) {
            $this->view = new \fpcm\view\error($exc->getMessage());
            exit;
        }

        $this->view->assign('diffResult', html_entity_decode($renderer->render($differ)) );
        $this->view->render();
    }

}

?>
