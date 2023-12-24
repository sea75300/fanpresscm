<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX autocomplete controller
 * 
 * @package fpcm\controller\ajax\commom
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.6
 */
class autocomplete extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\common\isAccessibleTrue;

    /**
     * Suchbegriff
     * @var string
     */
    protected $term = '';

    /**
     * Suchbegriff
     * @var bool
     */
    protected $tinyMce = true;

    /**
     * 
     * @var array
     */
    protected $returnData = [];

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->term = $this->request->fetchAll('term', [
            \fpcm\model\http\request::FILTER_STRIPTAGS,
            \fpcm\model\http\request::FILTER_STRIPSLASHES,
            \fpcm\model\http\request::FILTER_TRIM,
            \fpcm\model\http\request::FILTER_URLDECODE
        ]);
        
        if ($this->term === null) {
            $this->term = '';
        }

        $this->tinyMce = (bool) $this->request->fetchAll('tinyMce');
        
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
        ])->getData()['returnData'])->fetch();

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
        foreach ($data as $value) {
            $this->returnData[] = [
                'value' => $value,
                'label' => $value
            ];
        }
        
        $this->returnData = array_filter($this->returnData, function ($value) {
            return str_contains($value['value'], $this->term);
        });
        
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

        $labelString = $this->tinyMce ? 'title' : 'label';
        
        $data = \fpcm\components\components::getArticleEditor()->getFileList($labelString);

        $this->returnData = array_filter($data, function ($value) use($labelString) {
            return str_contains($value[$labelString], $this->term) || str_contains($value['value'], $this->term);
        });

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
        
        $labelString = $this->tinyMce ? 'title' : 'label';

        $data = \fpcm\components\components::getArticleEditor()->getEditorLinks($labelString);
        array_shift($data);

        $this->returnData = array_filter($data, function ($value) use($labelString) {
            return str_contains($value[$labelString], $this->term) || str_contains($value['value'], $this->term);
        });

        return true;
    }
    
    private function hasNoArticlesAccess() : bool
    {
        return !$this->permissions->article->edit && !$this->permissions->article->editall;
    }

}
