<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\templates;

/**
 * Template controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class fetchEditor extends \fpcm\controller\abstracts\ajaxController
{

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

        $tags = $this->template->getAllowedTagsArray();
        $list = array_map(function ($tag) {
            
            $t = substr($tag, 1, -1);
            
            return (new \fpcm\view\helper\dropdownItem('tag-'.\fpcm\classes\tools::getHash($tag)))
                    ->setText(htmlspecialchars($tag))
                    ->setValue($t)
                    ->setData([
                        'htmltag' => $t
                    ]);
            
        }, $tags);
        
        $editor = new \fpcm\components\editor\aceEditor();
        $vars = $editor->getViewVars()->toArray();

        $this->postPrepareVars($vars);

        $this->view->setViewVars($vars);

        $this->view->assign('replacements', $this->template->getReplacementTranslations($this->prefix));
        $this->view->assign('attributes', $this->template->getReplacementAttributesMap());
        $this->view->assign('allowedTagsList', $list);
        $this->view->assign('content', $this->template->getContent());
        $this->view->assign('isWritable', $this->template->isWritable());
        $this->view->assign('tplId', $this->templateId);
        $this->view->render();

        return true;
    }
    
    private function postPrepareVars(array &$vars)
    {
        $vars['editorButtons']['sup']->setReturned(true);
        $vars['editorButtons']['sub']->setReturned(true);
        $vars['editorButtons']['quote']->setReturned(true);
        $vars['editorButtons']['media']->setReturned(true);
        $vars['editorButtons']['pagebreak']->setReturned(true);
        $vars['editorButtons']['drafts']->setReturned(true);
        $vars['editorButtons']['delim5']->setReturned(true);
        $vars['editorButtons']['removestyles']->setReturned(true);
        $vars['editorButtons']['restore']->setReturned(true);

        unset(
            $vars['editorButtons']['sup'],
            $vars['editorButtons']['sub'],
            $vars['editorButtons']['quote'],
            $vars['editorButtons']['media'],
            $vars['editorButtons']['pagebreak'],
            $vars['editorButtons']['drafts'],
            $vars['editorButtons']['delim5'],
            $vars['editorButtons']['removestyles'],
            $vars['editorButtons']['restore']
        );    
    }

}