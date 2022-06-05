<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

/**
 * Article add controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class articleadd extends articlebase {

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
        $this->view->assign('showComments', false);
        $this->view->assign('showRevisions', false);
        $this->view->assign('showShares', false);
        $this->view->assign('postponedTimer', time());
        $this->view->render();
    }

}
