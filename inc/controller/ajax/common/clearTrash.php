<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX controller zum Cache leeren 
 * 
 * @package fpcm\controller\ajax\common\cache
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.4
 */
class clearTrash extends \fpcm\controller\abstracts\ajaxControllerJSON implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->article->delete || $this->permissions->comment->delete;
    }

    
    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->processByParam();
        $this->getSimpleResponse();
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processClearArticles()
    {
        if (!$this->permissions->article->delete || !$this->checkPageToken('ajax/clearTrashArticles')) {
            return false;
        }

        $res = (new \fpcm\model\articles\articlelist)->emptyTrash();
        
        $this->returnData = [
            'code' => (int)$res,
            'msg' => new \fpcm\view\message(
                $this->language->translate( $res ? 'DELETE_SUCCESS_TRASH' : 'DELETE_FAILED_TRASH' ),
                $res ? \fpcm\view\message::TYPE_NOTICE : \fpcm\view\message::TYPE_ERROR,
                $res ? \fpcm\view\message::ICON_NOTICE : \fpcm\view\message::ICON_ERROR
            )
        ];
        
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processRestoreArticles()
    {
        if (!$this->permissions->article->delete || !$this->checkPageToken('ajax/clearTrashArticles')) {
            return false;
        }

        $ids = $this->request->getIDs();
        if (!count($ids)) {
            $this->returnData = [
                'code' => 0,
                'msg' => new \fpcm\view\message(
                    $this->language->translate('SELECT_ITEMS_MSG'),
                    \fpcm\view\message::TYPE_ERROR,
                    \fpcm\view\message::ICON_ERROR
                )
            ];

            return false;
        }
        
        $res = (new \fpcm\model\articles\articlelist)->restoreArticles($ids);

        $this->returnData = [
            'code' => (int)$res,
            'msg' => new \fpcm\view\message(
                $this->language->translate( $res ? 'SAVE_SUCCESS_ARTICLERESTORE' : 'SAVE_FAILED_ARTICLERESTORE' ),
                $res ? \fpcm\view\message::TYPE_NOTICE : \fpcm\view\message::TYPE_ERROR,
                $res ? \fpcm\view\message::ICON_NOTICE : \fpcm\view\message::ICON_ERROR
            )
        ];
        
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processClearComments()
    {
        if (!$this->permissions->comment->delete || !$this->checkPageToken('ajax/clearTrashComments')) {
            return false;
        }

        $res = (new \fpcm\model\comments\commentList)->emptyTrash();
        
        $this->returnData = [
            'code' => (int)$res,
            'msg' => new \fpcm\view\message(
                $this->language->translate( $res ? 'DELETE_SUCCESS_TRASH' : 'DELETE_FAILED_TRASH' ),
                $res ? \fpcm\view\message::TYPE_NOTICE : \fpcm\view\message::TYPE_ERROR,
                $res ? \fpcm\view\message::ICON_NOTICE : \fpcm\view\message::ICON_ERROR
            )
        ];
        
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processrestoreComments()
    {
        if (!$this->permissions->comment->delete || !$this->checkPageToken('ajax/clearTrashComments')) {
            return false;
        }

        $ids = $this->request->getIDs();
        if (!count($ids)) {
            $this->returnData = [
                'code' => 0,
                'msg' => new \fpcm\view\message(
                    $this->language->translate('SELECT_ITEMS_MSG'),
                    \fpcm\view\message::TYPE_ERROR,
                    \fpcm\view\message::ICON_ERROR
                )
            ];

            return false;
        }
        
        $res = (new \fpcm\model\comments\commentList)->retoreComments($ids);

        $this->returnData = [
            'code' => (int)$res,
            'msg' => new \fpcm\view\message(
                $this->language->translate( $res ? 'SAVE_SUCCESS_ARTICLERESTORE' : 'SAVE_FAILED_ARTICLERESTORE' ),
                $res ? \fpcm\view\message::TYPE_NOTICE : \fpcm\view\message::TYPE_ERROR,
                $res ? \fpcm\view\message::ICON_NOTICE : \fpcm\view\message::ICON_ERROR
            )
        ];
        
        return true;
    }

}

?>