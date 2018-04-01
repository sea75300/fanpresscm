<?php

/**
 * AJAX cache controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX controller zum Cache leeren 
 * 
 * @package fpcm\controller\ajax\common\cache
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class cache extends \fpcm\controller\abstracts\ajaxController {

    /**
     *
     * @var string
     */
    private $module;

    /**
     *
     * @var string
     */
    private $objid;

    /**
     * 
     * @return boolean
     */
    public function hasAccess()
    {
        if (!is_object($this->session) || !$this->session->exists()) {
            return false;
        }
        
        return true;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->module = $this->getRequestVar('cache', [\fpcm\classes\http::FILTER_URLDECODE, \fpcm\classes\http::FILTER_DECRYPT]);
        $this->objid = $this->getRequestVar('objid', [\fpcm\classes\http::FILTER_CASTINT]);

        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        if ($this->module) {

            $fn = 'cleanup' . ucfirst($this->module);
            if (method_exists($this, $fn)) {
                call_user_func([$this, $fn]);
            }
        } else {
            $this->cache->cleanup();
        }

        $this->events->runEvent('clearCache', [
            'module' => $this->module,
            'objid' => $this->objid
        ]);

        $this->returnData = [
            'txt' => 'CACHE_CLEARED_OK',
            'type' => 'notice',
            'icon' => 'hdd'
        ];

        $this->getSimpleResponse();
    }

    /**
     * 
     * @return boolean
     */
    private function cleanupArticle()
    {
        $this->cache->cleanup(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE.'/'.\fpcm\model\articles\article::CACHE_ARTICLE_SINGLE . $this->objid);
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function cleanupArticles()
    {
        $this->cache->cleanup(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE.'/*');
        return true;
    }

}

?>