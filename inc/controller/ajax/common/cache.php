<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX controller zum Cache leeren
 *
 * @package fpcm\controller\ajax\common\cache
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class cache extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\common\isAccessibleTrue;

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
     * @return bool
     */
    public function hasAccess()
    {
        if (!$this->checkReferer()) {
            return false;
        }

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
        $this->module = $this->request->fromPOST('cache', [\fpcm\model\http\request::FILTER_URLDECODE, \fpcm\model\http\request::FILTER_DECRYPT, \fpcm\model\http\request::FILTER_FIRSTUPPER]);
        $this->objid = $this->request->fromPOST('objid', [\fpcm\model\http\request::FILTER_CASTINT]);
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

        $ev = $this->events->trigger('clearCache', [
            'module' => $this->module,
            'objid' => $this->objid
        ]);

        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event clearCache failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            $this->response->setReturnData(new \fpcm\view\message(
                'CACHE_CLEARED_FAILED',
                \fpcm\view\message::TYPE_ERROR
            ))->fetch();
        }

        $this->response->setReturnData(new \fpcm\view\message(
            'CACHE_CLEARED_OK',
            \fpcm\view\message::TYPE_NOTICE
        ))->fetch();

    }

    /**
     *
     * @return bool
     */
    private function cleanupArticle()
    {
        $this->cache->cleanup(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE.'/'.\fpcm\model\articles\article::CACHE_ARTICLE_SINGLE . $this->objid);
        return true;
    }

    /**
     *
     * @return bool
     */
    private function cleanupArticles()
    {
        $this->cache->cleanup(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE.'/*');
        return true;
    }

}
