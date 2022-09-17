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
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class searchall extends \fpcm\controller\abstracts\ajaxController
{
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
        $this->term = $this->request->fetchAll('term', [
            \fpcm\model\http\request::FILTER_STRIPTAGS,
            \fpcm\model\http\request::FILTER_STRIPSLASHES,
            \fpcm\model\http\request::FILTER_TRIM,
            \fpcm\model\http\request::FILTER_URLDECODE
        ]);

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!trim($this->term) || strlen($this->term) < 3) {
            $this->response->setReturnData(new \fpcm\model\gsearch\resultSet([], 0))->fetch();
        }

        $cond = new \fpcm\model\gsearch\conditions($this->term);
        
        $indexer = new \fpcm\model\gsearch\indexer($cond);
        $result = $indexer->getData();

        $this->response->setReturnData($result)->fetch();
    }

}
