<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\templates;

/**
 * Template controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class fetchEditor extends \fpcm\controller\abstracts\ajaxController {

    /**
     *
     * @var string
     */
    private $templateId;

    /**
     *
     * @var string
     */
    private $templateFunction;

    /**
     *
     * @var \fpcm\model\pubtemplates
     */
    private $template;

    /**
     *
     * @var string
     */
    private $prefix = 'TEMPLATE_ARTICLE_';

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'templates'];
    }
    
    protected function getViewPath()
    {
        return 'templates/templateEditor';
    }

        /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        $this->templateId = $this->getRequestVar('tpl');
        $this->templateFunction = 'get'. ucfirst($this->templateId).'Template';

        if (!$this->templateId || !$this->templateFunction || !method_exists($this, $this->templateFunction)) {
            return false;
        }

        return true;
    }

    public function process()
    {
        call_user_func([$this, $this->templateFunction]);
        if (!$this->prefix || !is_object($this->template) || !$this->template instanceof \fpcm\model\pubtemplates\template) {
            return false;
        }

        $this->view->assign('replacements', $this->template->getReplacementTranslations($this->prefix));
        $this->view->assign('allowedTags', htmlentities($this->template->getAllowedTags(', ')));
        $this->view->assign('content', $this->template->getContent());
        $this->view->assign('tplId', $this->templateId);
        $this->view->render();

        return true;
    }
    
    private function getArticleTemplate()
    {
        $this->template = new \fpcm\model\pubtemplates\article($this->config->articles_template_active);
    }
    
    private function getArticleSingleTemplate()
    {
        $this->template = new \fpcm\model\pubtemplates\article($this->config->article_template_active);
    }
    
    private function getCommentTemplate()
    {
        $this->template = new \fpcm\model\pubtemplates\comment($this->config->comments_template_active);
        $this->prefix = 'TEMPLATE_COMMMENT_';
    }
    
    private function getCommentFormTemplate()
    {
        $this->template = new \fpcm\model\pubtemplates\commentform();
        $this->prefix = 'TEMPLATE_COMMMENTFORM_';
    }
    
    private function getLatestNewsTemplate()
    {
        $this->template = new \fpcm\model\pubtemplates\latestnews();
    }
    
    private function getTweetTemplate()
    {
        $this->template = new \fpcm\model\pubtemplates\tweet();
    }

}