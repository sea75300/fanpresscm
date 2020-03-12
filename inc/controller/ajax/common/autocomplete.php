<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX autocomplete controller
 * 
 * @package fpcm\controller\ajax\commom
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.6
 */
class autocomplete extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\common\isAccessibleTrue;

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
        $this->returnData = [];
        $this->response = new \fpcm\model\http\response;
        $this->term = $this->request->fetchAll('term', [
            \fpcm\model\http\request::FILTER_STRIPTAGS,
            \fpcm\model\http\request::FILTER_STRIPSLASHES,
            \fpcm\model\http\request::FILTER_TRIM, \fpcm\model\http\request::FILTER_URLDECODE
        ]);

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if ($this->processByParam('autocomplete', 'src') !== true) {
            $this->response->setReturnData([])->fetch();
        }

        $this->response->setReturnData($this->events->trigger('autocompleteGetData', [
            'module'     => $this->request->fetchAll('src'),
            'returnData' => $this->returnData
        ]))->fetch();

    }

    /**
     * Autocomplete von Artikeln
     * @return bool
     */
    protected function autocompleteArticles()
    {
        if ($this->hasNoArticlesAccess()) {
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
    protected function autocompleteArticlesources()
    {
        if ($this->hasNoArticlesAccess()) {
            return false;
        }

        $data = \fpcm\model\articles\article::fetchSourcesAutocomplete();
        if (!$this->term) {
            $this->returnData = $data;
            return true;
        }

        foreach ($data as $value) {
            if ($this->term && stripos($value, $this->term) === false) {
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
    protected function autocompleteEditorfiles()
    {
        if ($this->hasNoArticlesAccess()) {
            return false;
        }

        $data = \fpcm\components\components::getArticleEditor()->getFileList();
        foreach ($data as $value) {
            if ($this->term && stripos($value['label'], $this->term) === false && stripos($value['value'], $this->term) === false) {
                continue;
            }

            $this->returnData[] = $value;
        }

        return true;
    }

    /**
     * Autocomplete der Link-Liste im Editor
     * @return bool
     */
    protected function autocompleteEditorlinks()
    {
        if ($this->hasNoArticlesAccess()) {
            return false;
        }

        $data = \fpcm\components\components::getArticleEditor()->getEditorLinks();
        foreach ($data as $value) {
            if ($this->term && stripos($value['label'], $this->term) === false && stripos($value['value'], $this->term) === false) {
                continue;
            }

            $this->returnData[] = $value;
        }
        
        return true;
    }
    
    private function hasNoArticlesAccess() : bool
    {
        return !$this->permissions->article->edit && !$this->permissions->article->editall ? true : false;
    }

}

?>