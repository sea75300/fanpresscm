<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\packagemgr;

/**
 * AJAX controlelr after module file upload
 * 
 * @package fpcm\controller\ajax\packagemgr
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class moduleExtractCopy extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->modules->install && !defined('FPCM_DISABLE_MODULE_ZIPUPLOAD') || !FPCM_DISABLE_MODULE_ZIPUPLOAD;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $unique = \fpcm\classes\tools::getHash($this->session->getSessionId().$this->session->getUserId());

        $filename = $this->request->fromPOST('file');

        $source = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, $unique . DIRECTORY_SEPARATOR . $filename);
        $dest = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, $filename);
        
        $failedMsg = $this->language->translate('SAVE_FAILED_UPLOADMODULE');
        if (!\fpcm\model\files\ops::isValidDataFolder($source, \fpcm\classes\dirs::DATA_TEMP) || !\fpcm\model\files\ops::isValidDataFolder($dest, \fpcm\classes\dirs::DATA_TEMP)) {            
            $this->response->setReturnData( new \fpcm\view\message($failedMsg, \fpcm\view\message::TYPE_ERROR) )->fetch();
        }
        
        if (!copy($source, $dest)) {
            $this->response->setReturnData( new \fpcm\view\message($failedMsg, \fpcm\view\message::TYPE_ERROR) )->fetch();
        }

        \fpcm\model\files\ops::deleteRecursive(dirname($source));

        $package = new \fpcm\model\packages\module($filename);
        if (!$package->isPreValidated()) {
            $this->response->setReturnData( new \fpcm\view\message($failedMsg, \fpcm\view\message::TYPE_ERROR) )->fetch();
        }

        if ($package->extract() !== true) {
            $this->response->setReturnData( new \fpcm\view\message($failedMsg, \fpcm\view\message::TYPE_ERROR) )->fetch();
        }

        if ($package->copy() !== true) {
            $this->response->setReturnData( new \fpcm\view\message($failedMsg, \fpcm\view\message::TYPE_ERROR) )->fetch();
        }

        if ($package->cleanup() !== true) {
            $this->response->setReturnData( new \fpcm\view\message($failedMsg, \fpcm\view\message::TYPE_ERROR) )->fetch();
        }

        $this->response->setReturnData( new \fpcm\view\message($this->language->translate('SAVE_SUCCESS_UPLOADMODULE'), \fpcm\view\message::TYPE_NOTICE) )->fetch();
    }

}
