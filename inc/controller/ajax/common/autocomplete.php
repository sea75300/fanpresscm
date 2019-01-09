<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX autocomplete controller
 * 
 * @package fpcm\controller\ajax\commom.addmsg
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.6
 */
class autocomplete extends \fpcm\controller\abstracts\ajaxController {

    /**
     * Modul-String
     * @var string
     */
    protected $module = null;

    /**
     * Suchbegriff
     * @var string
     */
    protected $term = '';

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->module = ucfirst($this->getRequestVar('src'));
        $this->term = $this->getRequestVar('term', [\fpcm\classes\http::FILTER_STRIPTAGS, \fpcm\classes\http::FILTER_STRIPSLASHES, \fpcm\classes\http::FILTER_TRIM, \fpcm\classes\http::FILTER_URLDECODE]);

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $fn = 'autocomplete' . $this->module;
        if (!method_exists($this, $fn)) {
            $this->getSimpleResponse();
        }

        call_user_func([$this, $fn]);
        $this->returnData = $this->events->trigger('autocompleteGetData', [
            'module'     => $this->module,
            'returnData' => $this->returnData
        ]);

        $this->getSimpleResponse();
    }

    /**
     * Autocomplete von Artikeln
     * @return bool
     */
    private function autocompleteArticles()
    {
        if (!$this->permissions->check(['article' => ['edit', 'editall']])) {
            $this->returnData = [];
            return false;
        }

        $list = new \fpcm\model\articles\articlelist();

        $conditions = new \fpcm\model\articles\search();
        $conditions->title = $this->term;
        $conditions->approval = -1;
        $conditions->limit = [200, 0];
        $conditions->orderby = ['createtime DESC'];
        $conditions->metaOnly = true;

        $result = $list->getArticlesByCondition($conditions);
        if (!$result || !count($result)) {
            $this->returnData = [];
            return false;
        }

        /* @var \fpcm\model\articles\article $article */
        foreach ($result as $article) {
            $this->returnData[] = [
                'value' => $article->getId(),
                'label' => $article->getTitle() . ' (' . date($this->config->system_dtmask, $article->getCreatetime()) . ')'
            ];
        }

        return true;
    }

    /**
     * Autocomplete for article sources
     * @return bool
     */
    private function autocompleteArticlesources()
    {
        if (!$this->permissions->check(['article' => ['edit', 'editall']])) {
            $this->returnData = [];
            return false;
        }

        $data = \fpcm\model\articles\article::fetchSourcesAutocomplete();
        if (!$this->term) {
            $this->returnData = $data;
            return true;
        }

        foreach ($data as $value) {
            if (stripos($value, $this->term) === false) {
                continue;
            }

            $this->returnData[] = $value;
        }
        
        return true;
    }

    /**
     * Autocomplete der Bild-Liste im Editor
     * @return bool
     */
    private function autocompleteEditorfiles()
    {
        $this->returnData = \fpcm\components\components::getArticleEditor()->getFileList();
        return true;
    }

    /**
     * Autocomplete der Link-Liste im Editor
     * @return bool
     */
    private function autocompleteEditorlinks()
    {
        $this->returnData = \fpcm\components\components::getArticleEditor()->getEditorLinks();
        return true;
    }

}

?>