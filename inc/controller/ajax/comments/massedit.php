<?php

namespace fpcm\controller\ajax\comments;

/**
 * Massenbearbeitung von Kommentaren
 * 
 * @package fpcm\controller\ajax\articles\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.6
 */
class massedit extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\comments\lists;

    /**
     * Artikel-Liste
     * @var \fpcm\model\comments\commentList
     */
    protected $commentList;

    /**
     * Kommentar-IDs
     * @var array
     */
    protected $commentIds = [];

    /**
     * Artikel-Informationen
     * @var array
     */
    protected $data = [];

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->editCommentsMass();
    }
    
    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        if (!$this->checkPageToken()) {
            return false;
        }

        $this->commentList = new \fpcm\model\comments\commentList();
        $this->commentIds = array_map('intval', $this->getRequestVar('ids', [\fpcm\classes\http::FILTER_JSON_DECODE, 'object' => false]));
        $this->data = $this->getRequestVar('fields', [\fpcm\classes\http::FILTER_JSON_DECODE, 'object' => false]);

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $fields = [];

        if (isset($this->data['isSpam'])) {
            $fields['spammer'] = (int) $this->data['isSpam'];
        }

        if (isset($this->data['isApproved'])) {
            $fields['approved'] = (int) $this->data['isApproved'];
        }

        if (isset($this->data['isPrivate'])) {
            $fields['private'] = (int) $this->data['isPrivate'];
        }

        if (isset($this->data['moveToArticle']) && is_numeric($this->data['moveToArticle'])) {

            if (!$this->permissions->comment->move) {
                $this->returnCode = 0;
                $this->getResponse();
            }

            $fields['articleId'] = (int) $this->data['moveToArticle'];
        }

        $result = $this->commentList->editCommentsByMass($this->commentIds, $fields);

        $this->returnCode = $result ? 1 : 0;
        $this->getResponse();
    }

}

?>