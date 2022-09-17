<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\gsearch;

/**
 * Global search indexer, performs search
 * 
 * @package fpcm\model\gsearch
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class indexer extends \fpcm\model\abstracts\tablelist
{
    /**
     * List of model classes to search on
     * @var array
     */
    private $models = [];

    /**
     * Search result set
     * @var resultSet
     */
    private $result;

    /**
     * Search contition object
     * @var conditions
     */
    private conditions $params;

    /**
     * 
     * @var array
     */
    private $instances = [];

    /**
     * Constructor
     * @param conditions $params
     */
    public function __construct(conditions $params)
    {
        parent::__construct();
        $this->params = $params;
        $this->initDefaultModels();
    }

    /**
     * Processes search and returns data
     * @return \fpcm\model\gsearch\resultSet
     */
    public function getData()
    {
        $result = $this->events->trigger('search\models', $this->models);
        if (!$result->getSuccessed() || !$result->getContinue()) {
            return new resultSet([], 0);
        }
        
        $this->models = $result->getData();

        $cResults = [];
        $sResults = [];
        
        if (!$this->query($cResults, $sResults)) {
            return new resultSet([], 0);
        }         
        
        $setItems = [];

        /* @var $instance \fpcm\model\interfaces\gsearchIndex */
        foreach ($sResults as $result) {
            
            if (empty($result->model)) {
                trigger_error('Global search return value "model" is missing or empty!', E_USER_ERROR);
                continue;
            }

            $instance = $this->instances[$result->model] ?? null;
            $link = $instance?->getElementLink($result->oid);
            $icon = $instance?->getElementIcon();

            $setItems[] = new resultItem($result->text, $link, $icon);
            
        }

        return new resultSet($setItems, array_sum($cResults));        
        
    }

    /**
     * Queries data
     * @param array $cResults
     * @param array $sResults
     * @return bool
     */
    private function query(array &$cResults, array &$sResults): bool
    {
        if (!is_array($this->models) || !count($this->models)) {
            return false;
        }
        
        $sParams = [':term' => '%'.$this->params->getTerm().'%'];
        
        $counter = [];
        $searcher = [];
        
        foreach ($this->models as $key => $class) {

            /* @var $cquery \fpcm\model\dbal\selectParams */
            /* @var $squery \fpcm\model\dbal\selectParams */
            $this->instances[$key] = (new $class());
            
            if (!$this->instances[$key] instanceof \fpcm\model\interfaces\gsearchIndex) {
                //trigger_error(sprintf('Object of type %s must be implement the interface \fpcm\model\interfaces\gsearchIndex', $class), E_USER_ERROR);
                unset($this->instances[$key]);
                continue;
            }

            $cquery = $this->instances[$key]->getCountQuery();
            $cquery->setParams($sParams);
            $counter[] = $cquery;
            
            
            $squery = $this->instances[$key]->getSearchQuery();
            $squery->setParams($sParams);
            $searcher[] = $squery;

        }
        
        if (!count($counter) || !count($searcher)) {
            return false;
        }         
        
        $cResults = $this->dbcon->unionSelectFetch($counter, \PDO::FETCH_KEY_PAIR);
        $sResults = $this->dbcon->unionSelectFetch($searcher, \PDO::FETCH_OBJ, false, FPCM_ARTICLES_SOURCES_AUTOCOMPLETE, 0);

        return count($cResults) && count($sResults);
    }
    
    private function initDefaultModels()
    {
        /* @var $perm \fpcm\model\permissions\permissions */
        $perm = \fpcm\classes\loader::getObject('\fpcm\model\permissions\permissions');

        if ($perm->editArticles()) {
            $this->models['articles'] = '\fpcm\model\articles\articlelist';
        }

        if ($perm->editComments()) {
            $this->models['comments'] = '\fpcm\model\comments\commentList';
        }

        if ($perm->uploads->visible) {
            $this->models['images'] = '\fpcm\model\files\imagelist';
        }

    }

    
}
