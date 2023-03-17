<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX autocomplete cleanup controller
 * 
 * @package fpcm\controller\ajax\commom
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class autocompleteCleanup extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\common\isAccessibleTrue;

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
        $this->processByParam('cleanup', 'src');
        $this->response->setReturnData([])->fetch();
    }

    /**
     * Autocomplete for article sources
     * @return bool
     */
    protected function cleanupArticlesources()
    {
        if ($this->hasNoArticlesAccess()) {
            return false;
        }

        $data = array_filter( \fpcm\model\articles\article::fetchSourcesAutocomplete() , function ($value) {
            return !str_contains($value, $this->term);
        });
        
        if (!is_array($data) || !count($data)) {
            $data = [];
        }

        $fopt = new \fpcm\model\files\fileOption(\fpcm\model\articles\article::SOURCES_AUTOCOMPLETE);
        return $fopt->write($data);
    }
    
    private function hasNoArticlesAccess() : bool
    {
        return !$this->permissions->article->edit && !$this->permissions->article->editall ? true : false;
    }

}

?>