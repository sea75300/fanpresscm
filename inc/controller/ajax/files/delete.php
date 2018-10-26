<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller to delete single file
 * 
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class delete extends \fpcm\controller\abstracts\ajaxController {

    /**
     *
     * @var string
     */
    private $fileName = '';

    /**
     *
     * @var string
     */
    private $newFileName = '';

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['uploads' => 'delete'];
    }
    
    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->fileName = $this->getRequestVar('filename', [
            \fpcm\classes\http::FILTER_BASE64DECODE
        ]);

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $replace = ['{{filename1}}' => basename($this->fileName), '{{filename2}}' => basename($this->newFileName)];
        if ((new \fpcm\model\files\image($this->fileName, false))->delete()) {
            $this->returnData['code'] = 1;
            $this->returnData['message'] = $this->language->translate('DELETE_SUCCESS_FILES', $replace);
            $this->getSimpleResponse();
        }

        $this->returnData['code'] = 0;
        $this->returnData['message'] = $this->language->translate('DELETE_FAILED_FILES', $replace);
        $this->getSimpleResponse();
    }

}

?>