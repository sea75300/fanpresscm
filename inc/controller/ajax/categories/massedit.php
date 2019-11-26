<?php

namespace fpcm\controller\ajax\categories;

/**
 * Mass edit for categories
 * 
 * @package fpcm\controller\ajax\articles\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.3
 */
class massedit extends \fpcm\controller\abstracts\ajaxController {

    /**
     * Kommentar-IDs
     * @var array
     */
    protected $ids = [];

    /**
     * Data array
     * @var array
     */
    protected $data = [];

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'categories'];
    }

    
    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->setReturnJson();
        
        if (!$this->checkPageToken()) {
            $this->returnCode = 0;
            return false;
        }

        $this->ids = $this->getRequestVar('ids', [
            \fpcm\classes\http::FILTER_JSON_DECODE,
            'object' => false
        ]);

        $this->data = $this->getRequestVar('fields');
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $fields = [
            'iconpath'  => $this->data['iconpath'] ?? -1,
            'groups'    => isset($this->data['groups'])
                        ? implode(';', array_map('intval', $this->data['groups']))
                        : -1
        ];

        $this->returnCode = (new \fpcm\model\categories\categoryList)->editCategoriesByMass(array_map('intval', $this->ids), $fields) ? 1 : 0;
        $this->getResponse();
    }

}

?>