<?php

namespace fpcm\controller\ajax\articles;

/**
 * Massenbearbeitung von Artikeln
 * 
 * @package fpcm\controller\ajax\articles\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.6
 */
class massedit extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\articles\lists;

    /**
     * Artikel-IDs
     * @var array
     */
    protected $articleIds = [];

    /**
     * Artikel-Informationen
     * @var array
     */
    protected $data = [];

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['article' => ['edit', 'editall', 'approve', 'archive']];
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        if (!$this->checkPageToken('articles/massedit')) {
            return false;
        }

        $this->articleList = new \fpcm\model\articles\articlelist();
        $this->articleIds = array_map('intval', $this->getRequestVar('ids', [\fpcm\classes\http::FILTER_JSON_DECODE, 'object' => false]));
        $this->data = $this->getRequestVar('fields', [\fpcm\classes\http::FILTER_JSON_DECODE, 'object' => false]);

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $fields = [];

        if (isset($this->data['categories'])) {
            $fields['categories'] = json_encode(array_map('intval', $this->data['categories']));
        }

        if (isset($this->data['userid'])) {
            $fields['createuser'] = (int) $this->data['userid'];
        }

        if (isset($this->data['comments'])) {
            $fields['comments'] = (int) $this->data['comments'];
        }

        if (isset($this->data['pinned'])) {
            $fields['pinned'] = ($this->data['archived'] > 0 ? 0 : (int) $this->data['pinned']);
        }

        if (isset($this->data['approval'])) {
            $fields['approval'] = (int) $this->data['approval'];
        }

        if (isset($this->data['draft'])) {
            $fields['draft'] = (int) $this->data['draft'];
        }

        if (isset($this->data['archived'])) {
            $fields['archived'] = (int) $this->data['archived'];
        }

        $result = $this->articleList->editArticlesByMass($this->articleIds, $fields);


        $this->returnCode = $result ? 1 : 0;
        $this->getResponse();
    }

}

?>