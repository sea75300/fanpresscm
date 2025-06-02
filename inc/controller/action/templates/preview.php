<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\templates;

/**
 * Template preview controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class preview extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\templates\preview;

    /**
     *
     * @var \fpcm\model\pubtemplates\template
     */
    protected $template;

    /**
     *
     * @var int
     */
    protected $tid;

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->tid = $this->request->fromGET('tid');

        if (!$this->tid) {
            return false;
        }

        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->template = $this->getTemplateById($this->tid);

        switch ($this->tid) {
            case \fpcm\model\pubtemplates\article::TEMPLATE_ID :
                $this->getArticlesPreview();
                break;
            case \fpcm\model\pubtemplates\article::TEMPLATE_ID_SINGLE :
                $this->getArticlePreview();
                break;
            case \fpcm\model\pubtemplates\comment::TEMPLATE_ID :
                $this->getCommentPreview();
                break;
            case \fpcm\model\pubtemplates\commentform::TEMPLATE_ID :
                $this->getCommentFormPreview();
                break;
            case \fpcm\model\pubtemplates\latestnews::TEMPLATE_ID :
                $this->getLatestNewsPreview();
                break;
            case \fpcm\model\pubtemplates\sharebuttons::TEMPLATE_ID :
                $this->getShareButtonPreview();
                break;
            default :
                $this->view = new \fpcm\view\error('Invalid template data');
                return;
        }

        $this->view->assign('isArchive', false);
        $this->view->assign('showToolbars', false);
        $this->view->assign('hideDebug', true);
        $this->view->assign('systemMode', 1);
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);

        $cssfiles = [];
        if (trim($this->config->system_css_path)) {
            $cssfiles[] = trim($this->config->system_css_path);
        }
        
        $jsfiles = [
            \fpcm\components\components::getjQuery(),
            \fpcm\classes\dirs::getRootUrl('js/fpcm.js')
        ];

        $evJs = $this->events->trigger('pub\addJsFiles', $jsfiles);
        if (!$evJs->getSuccessed() || !$evJs->getContinue()) {
            trigger_error(sprintf("Event pub\addJsFiles failed. Returned success = %s, continue = %s", $evJs->getSuccessed(), $evJs->getContinue()));
            return false;
        }

        $evCss = $this->events->trigger('pub\addCssFiles', $cssfiles);
        if (!$evCss->getSuccessed() || !$evCss->getContinue()) {
            trigger_error(sprintf("Event pub\addCssFiles failed. Returned success = %s, continue = %s", $evCss->getSuccessed(), $evCss->getContinue()));
            return false;
        }

        $this->view->overrideJsFiles($evJs->getData());
        $this->view->overrideCssFiles($evCss->getData());

        $this->view->render();
    }

    private function getArticlesPreview()
    {
        $this->view = new \fpcm\view\view('public/showall');

        $parsed = [];

        $article1 = new \fpcm\model\articles\article();
        $article1->setTitle('Lorem ipsum dolor sit amet, consetetur sadipscing elitr!');
        $article1->setContent('Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.<!--- Page Break --->Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.');
        $article1->setPinned(0);
        $article1->setSources($this->config->system_url);
        $article1->setCategories([1,2,3]);
        $article1->setCreatetime(time() - 3600);
        $article1->setChangetime(time());
        $article1->setId(1);

        $this->template->assignByObject(
            $article1,
            ['author' => $this->session->getCurrentUser(), 'changeUser' => $this->session->getCurrentUser()],
            ['Category 1' => (new \fpcm\model\categories\category(1))->getCategoryImage()], 0
        );

        $parsed[] = $this->template->parse();

        $article2 = new \fpcm\model\articles\article();
        $article2->setTitle('Ut wisi enim ad minim veniam?');
        $article2->setContent('Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.<!--- Page Break --->Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. ');
        $article2->setPinned(0);
        $article2->setSources('');
        $article2->setCategories([1,2,3]);
        $article2->setCreatetime(time() - 7200);
        $article2->setChangetime(time() - 7200);
        $article2->setId(2);

        $this->template->assignByObject(
            $article2,
            ['author' => $this->session->getCurrentUser(), 'changeUser' => $this->session->getCurrentUser()],
            ['Category 1' => (new \fpcm\model\categories\category(1))->getCategoryImage()], 0
        );

        $parsed[] = $this->template->parse();
        $this->view->assign('content', implode(PHP_EOL, $parsed));
        $this->view->assign('commentform', '');
    }

    private function getArticlePreview()
    {
        $this->view = new \fpcm\view\view('public/showsingle');

        $article = new \fpcm\model\articles\article();
        $article->setTitle('Lorem ipsum dolor sit amet, consetetur sadipscing elitr!');
        $article->setContent('Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.');
        $article->setPinned(0);
        $article->setSources($this->config->system_url);
        $article->setCategories([1,2,3]);
        $article->setCreatetime(time() - 3600);
        $article->setChangetime(time());
        $article->setId(1);

        $this->template->assignByObject(
            $article,
            ['author' => $this->session->getCurrentUser(), 'changeUser' => $this->session->getCurrentUser()],
            ['Category 1' => (new \fpcm\model\categories\category(1))->getCategoryImage()], 0
        );

        $this->view->assign('article', $this->template->parse());
        $this->view->assign('comments', '');
        $this->view->assign('commentform', '');
    }

    private function getCommentPreview()
    {
        $this->view = new \fpcm\view\view('public/showsingle');
        $this->view->assign('article', '');

        $comment = new \fpcm\model\comments\comment();
        $comment->setName($this->session->getCurrentUser()->getDisplayname());
        $comment->setEmail($this->session->getCurrentUser()->getEmail());
        $comment->setWebsite($this->request->getHost());
        $comment->setText('Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. ');
        $comment->setCreatetime(time() - 1800);

        $this->template->assignByObject($comment, 1);
        $this->view->assign('comments', $this->template->parse());
        $this->view->assign('commentform', '');
    }

    private function getCommentFormPreview()
    {
        $this->view = new \fpcm\view\view('public/showsingle');
        $this->view->assign('article', '');
        $this->view->assign('comments', '');

        $comment = new \fpcm\model\comments\comment();
        $comment->setName($this->session->getCurrentUser()->getDisplayname());
        $comment->setEmail($this->session->getCurrentUser()->getEmail());
        $comment->setWebsite($this->request->getHost());
        $this->template->assignByObject(new \fpcm\model\articles\article(), $comment, \fpcm\components\components::getChatptchaProvider());

        $this->view->assign('commentform', $this->template->parse());
    }

    private function getLatestNewsPreview()
    {
        $this->view = new \fpcm\view\view('public/showlatest');

        $article1 = new \fpcm\model\articles\article();
        $article1->setTitle('Lorem ipsum dolor sit amet, consetetur sadipscing elitr!');
        $article1->setContent('Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.');
        $article1->setPinned(0);
        $article1->setSources($this->config->system_url);
        $article1->setCategories([1,2,3]);
        $article1->setCreatetime(time() - 3600);
        $article1->setChangetime(time());
        $article1->setId(1);
        $this->template->assignByObject($article1, $this->session->getCurrentUser());
        $parsed[] = $this->template->parse();

        $article2 = new \fpcm\model\articles\article();
        $article2->setTitle('Ut wisi enim ad minim veniam?');
        $article2->setContent('Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. ');
        $article2->setPinned(0);
        $article2->setSources($this->config->system_url);
        $article2->setCategories([1,2,3]);
        $article2->setCreatetime(time() - 7200);
        $article2->setChangetime(time() - 7200);
        $article2->setId(2);
        $this->template->assignByObject($article1, $this->session->getCurrentUser());
        $parsed[] = $this->template->parse();

        $this->view->assign('content', implode(PHP_EOL, $parsed));
    }

    private function getShareButtonPreview()
    {
        $this->view = new \fpcm\view\view('public/showlatest');
        $this->template->assignData($this->config->system_url, 'Lorem ipsum dolor sit amet', 1);
        $this->view->assign('content', $this->template->parse());
    }

}

?>