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

class fetchEditor extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\templates\edit;
    
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
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'templates/templateEditor';
    }

        /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->templateId = $this->request->fromGET('tpl');
        $this->templateFunction = 'get'. ucfirst($this->templateId).'Template';

        if (!$this->templateId || !$this->templateFunction || !method_exists($this, $this->templateFunction)) {
            return false;
        }

        return true;
    }

    /**
     * 
     * @return bool
     */
    public function process()
    {
        if (!call_user_func([$this, $this->templateFunction]) ||
            !$this->prefix || !is_object($this->template) ||
            !$this->template instanceof \fpcm\model\pubtemplates\template) {
            return false;
        }

        $this->view->assign('replacements', $this->template->getReplacementTranslations($this->prefix));
        $this->view->assign('attributes', $this->template->getReplacementAttributesMap());
        $this->view->assign('allowedTags', $this->template->getAllowedTagsArray());
        $this->view->assign('content', $this->template->getContent());
        $this->view->assign('isWritable', $this->template->isWritable());
        $this->view->assign('tplId', $this->templateId);
        $this->view->render();

        return true;
    }

}