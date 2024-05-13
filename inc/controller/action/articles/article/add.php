<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles\article;

/**
 * Article add controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class add extends base {

    /**
     *
     * @var bool
     */
    protected $showComments = false;

    public function isAccessible(): bool
    {
        return $this->permissions->article->add;
    }

    public function process()
    {
        $this->article->enableTweetCreation($this->config->twitter_events['create']);

        parent::process();

        $this->view->setFormAction('articles/add');
        $this->view->assign('editorMode', 0);
        $this->view->assign('showArchiveStatus', false);
        $this->view->assign('showComments', false);
        $this->view->assign('showRevisions', false);
        $this->view->assign('showShares', false);
        $this->view->assign('postponedTimer', time());
        $this->view->assign('pinnedTimer', time()+3600*24);
        $this->view->render();
    }

    protected function onArticleSaveAfterSuccess(int $id): bool
    {
        $this->redirect('articles/edit', [
            'id' => $id,
            'added' => $this->permissions->article->approve ? 2 : 1
        ]);

        return true;
    }


}
