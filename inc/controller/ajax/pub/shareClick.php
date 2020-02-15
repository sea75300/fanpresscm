<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\pub;

define('FPCM_NOTOKEN', true);

/**
 * AJAX controller zum Cache leeren 
 * 
 * @package fpcm\controller\ajax\pub
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class shareClick extends \fpcm\controller\abstracts\ajaxController {

    /**
     *
     * @var string
     */
    private $item;

    /**
     *
     * @var int
     */
    private $oid;

    /**
     * 
     * @return bool
     */
    public function hasAccess() : bool
    {
        return true;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request() : bool
    {
        if (!$this->config->system_share_count) {
            return false;
        }

        $this->oid = $this->getRequestVar('oid', [\fpcm\classes\http::FILTER_CASTINT]);
        $this->item = $this->getRequestVar('item');
        
        if (!$this->oid || !$this->item) {
            return false;
        }

        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process() : bool
    {
        /* @var $share \fpcm\model\shares\share */
        $share = (new \fpcm\model\shares\shares())->getByArticleId($this->oid, $this->item);
        $share = isset($share[$this->item]) ? $share[$this->item] : new \fpcm\model\shares\share();

        $share->increase();
        $share->setShareitem($this->item);
        $share->setArticleId($this->oid);
        $share->setLastshare(time());

        return $share->exists() ? $share->update() : $share->save();
    }

    /**
     * 
     * @return bool
     */
    protected function initPermissionObject(): bool
    {
        return true;
    }

}

?>