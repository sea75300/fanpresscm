<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller to rename file list
 * 
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class rename extends \fpcm\controller\abstracts\ajaxController
{

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
        $this->newFileName = $this->request->fromPOST('newName');
        $this->fileName = $this->request->fromPOST('oldName', [
            \fpcm\model\http\request::FILTER_BASE64DECODE,
            \fpcm\model\http\request::FILTER_DECRYPT
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
        
        if (str_contains($this->newFileName, '..')) {
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
        $mfObj = new \fpcm\model\files\mediaFile($this->fileName, false);

        $replace = [
            '{{filename1}}' => basename($this->fileName),
            '{{filename2}}' => basename($this->newFileName)
        ];

        if ($mfObj->rename($this->newFileName, $this->session->getUserId())) {

            if ($mfObj->isImage()) {
                (new \fpcm\model\files\mediaFilesList())->createFilemanagerThumbs();
            }            

            $msg = new \fpcm\view\message(
                $this->language->translate('DELETE_SUCCESS_RENAME', $replace),
                \fpcm\view\message::TYPE_NOTICE
            );
            
            $this->response->setReturnData()->fetch();            

        }
        else {
            $msg = new \fpcm\view\message(
                $this->language->translate('RENAME_FAILED_FILE', $replace),
                \fpcm\view\message::TYPE_ERROR
            );
        }

        $this->response->setReturnData($msg)->fetch();            

    }

}
