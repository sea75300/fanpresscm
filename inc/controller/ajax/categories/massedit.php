<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\categories;

/**
 * Mass edit for categories
 * 
 * @package fpcm\controller\ajax\articles\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.3
 */
class massedit extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

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
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->categories;
    }
    
    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        if (!$this->checkPageToken()) {
            $this->response->setReturnData( new \fpcm\model\http\responseData(0) )->fetch();
        }

        $this->ids = $this->request->fromPOST('ids', [
            \fpcm\model\http\request::FILTER_JSON_DECODE,
            'object' => false
        ]);

        $this->data = $this->request->fromPOST('fields');
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

        $code = (new \fpcm\model\categories\categoryList)->editCategoriesByMass(array_map('intval', $this->ids), $fields) ? 1 : 0;
        $this->response->setReturnData( new \fpcm\model\http\responseData($code) )->fetch();
    }

}

?>