<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller to create new thumbnails
 * 
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class createThumbs extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     *
     * @var array
     */
    private $files = [];

    /**
     *
     * @var array
     */
    private $success = [];

    /**
     *
     * @var array
     */
    private $failed = [];

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->uploads->visible && $this->permissions->uploads->thumbs;
    }
    
    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->files = $this->getRequestVar('items', [
            \fpcm\classes\http::FILTER_BASE64DECODE
        ]);

        if (!$this->files) {
            $this->returnData['code'][1] = 0;
            $this->returnData['message'][1] = $this->language->translate('GLOBAL_NOTFOUND2');

            $this->getSimpleResponse();
        }

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        array_walk($this->files, [$this, 'createThumb']);

        $hasSuccess = count($this->success);
        $hasFailed = count($this->failed);
        if ($hasSuccess) {
            $this->returnData['code'][1] = 1;
            $this->returnData['message'][1] = $this->language->translate('SUCCESS_FILES_NEWTHUMBS', [
                '{{filenames}}' => implode(', ', $this->success)
            ]);
        }

        if ($hasFailed) {
            $this->returnData['code'][2] = 0;
            $this->returnData['message'][2] = $this->language->translate('FAILED_FILES_NEWTHUMBS', [
                '{{filenames}}' => implode(', ', $this->failed)
            ]);
        }

        if ($hasSuccess || $hasFailed) {
            $this->getSimpleResponse();
        }

        $this->returnData['code'][1] = 0;
        $this->returnData['message'][1] = $this->language->translate('GLOBAL_NOTFOUND2');
        $this->getSimpleResponse();
    }

    private function createThumb($fileName) : bool
    {
        if (!$fileName) {
            return false;
        }
        
        if ((new \fpcm\model\files\image($fileName, false))->createThumbnail()) {
            $this->success[] = $fileName;
            return true;
        }

        $this->failed[] = $fileName;
        return false;
    }

}

?>