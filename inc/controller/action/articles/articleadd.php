<?php

/**
 * Article add controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

class articleadd extends articlebase {

    protected function getPermissions()
    {
        return ['article' => 'add'];
    }

    public function request()
    {
        if (parent::request()) {
            $id = $this->saveArticle();

            if ($id === false && !$this->emptyTitleContent) {
                $this->view->addErrorMessage('SAVE_FAILED_ARTICLE');
            } elseif ($id > 0) {
                $this->redirect('articles/edit', [
                    'articleid' => $id,
                    'added' => $this->permissions->check(['article' => 'approve']) ? 2 : 1
                ]);
            }
        }

        $this->article->enableTweetCreation($this->config->twitter_events['create']);
        return true;
    }

    public function process()
    {
        parent::process();

        $this->view->setFormAction('articles/add');
        $this->view->assign('editorMode', 0);
        $this->view->assign('showComments', false);
        $this->view->assign('showRevisions', false);
        $this->view->assign('showShares', false);
        $this->view->assign('postponedTimer', time());
        $this->view->render();
    }

}

?>