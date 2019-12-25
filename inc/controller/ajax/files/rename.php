<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller to rename file list
 * 
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class rename extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

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
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->uploads->visible && $this->permissions->uploads->rename;
    }
    
    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->setReturnJson();

        $this->newFileName = $this->getRequestVar('newName');
        $this->fileName = $this->getRequestVar('oldName', [
            \fpcm\classes\http::FILTER_BASE64DECODE
        ]);

        if (!$this->newFileName || !$this->fileName) {
            $this->returnData['code'] = -1;
            $this->returnData['message'] = $this->language->translate('DELETE_FAILED_RENAME', [
                '{{filename1}}' => $this->fileName,
                '{{filename2}}' => $this->newFileName
            ]);

            $this->getSimpleResponse();
        }

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $image = new \fpcm\model\files\image($this->fileName, false);
        
        $replace = ['{{filename1}}' => basename($this->fileName), '{{filename2}}' => basename($this->newFileName)];
        if ($image->rename($this->newFileName, $this->session->getUserId())) {

            (new \fpcm\model\files\imagelist())->createFilemanagerThumbs();
            
            $this->returnData['code'] = 1;
            $this->returnData['message'] = $this->language->translate('DELETE_SUCCESS_RENAME', $replace);
            $this->getSimpleResponse();
        }

        $this->returnData['code'] = 0;
        $this->returnData['message'] = $this->language->translate('DELETE_FAILED_RENAME', $replace);
        $this->getSimpleResponse();
    }

}

?>