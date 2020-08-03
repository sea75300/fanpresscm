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
        $this->response = new \fpcm\model\http\response;

        $this->newFileName = $this->request->fromPOST('newName');
        $this->fileName = $this->request->fromPOST('oldName', [
            \fpcm\model\http\request::FILTER_BASE64DECODE
        ]);

        if (!$this->newFileName || !$this->fileName) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('RENAME_FAILED_FILE', [
                    '{{filename1}}' => $this->fileName,
                    '{{filename2}}' => $this->newFileName
                ]),
                \fpcm\view\message::TYPE_ERROR
            ))->fetch();
        }
        
        if (strpos($this->newFileName, '..') !== false) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('RENAME_FAILED_FILE', [
                    '{{filename1}}' => $this->fileName,
                    '{{filename2}}' => $this->newFileName
                ]),
                \fpcm\view\message::TYPE_ERROR
            ))->fetch();
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

            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('DELETE_SUCCESS_RENAME', $replace),
                \fpcm\view\message::TYPE_NOTICE
            ))->fetch();            

        }

        $this->response->setReturnData(new \fpcm\view\message(
            $this->language->translate('RENAME_FAILED_FILE', $replace),
            \fpcm\view\message::TYPE_ERROR
        ))->fetch();            

    }

}

?>