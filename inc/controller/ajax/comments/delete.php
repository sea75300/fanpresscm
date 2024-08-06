<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\comments;

/**
 * Delete articles single/multiple
 * 
 * @package fpcm\controller\ajax\articles\inedit
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since 5.1.0-a1
 */
class delete extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->config->system_comments_enabled && $this->permissions->comment->delete;
    }

    /**
     * 
     * @return bool
     */
    public function request() : bool
    {
        if (!$this->checkPageToken('comments/delete')) {
            return false;
        }
        
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $isMultiple = $this->request->fromPOST('multiple', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        $id = $this->request->fromPOST('id', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if ($isMultiple) {
            $this->response->setReturnData(new \fpcm\model\http\responseData( (new \fpcm\model\comments\commentList )->deleteComments($id) ? 1 : 0 ) )->fetch();
        }
        
        $comment = new \fpcm\model\comments\comment($id);
        $this->response->setReturnData( new \fpcm\model\http\responseData( $comment->exists() && $comment->delete() ? 1 : 0 ) )->fetch();
    }

}
