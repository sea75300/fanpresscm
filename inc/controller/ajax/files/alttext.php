<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller to for alternate file text
 * 
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class alttext extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->uploads->visible && $this->permissions->uploads->add;
    }
    
    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $fileName = $this->request->fromPOST('file', [
            \fpcm\model\http\request::FILTER_BASE64DECODE
        ]);

        $altText = $this->request->fromPOST('alttext', [
            \fpcm\model\http\request::FILTER_URLDECODE,
            \fpcm\model\http\request::FILTER_TRIM,
            \fpcm\model\http\request::FILTER_HTMLENTITY_DECODE,
            \fpcm\model\http\request::FILTER_HTMLSPECIALCHARS
        ]);
        
        if (!trim($fileName)) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('SAVE_FAILED_FILE_ALTTEXT'),
                \fpcm\view\message::TYPE_ERROR
            ))->fetch();
        }

        $image = new \fpcm\model\files\image($fileName);
        $image->setAltText(substr($altText, 0, 250));
        
        if ($image->exists() && $image->update()) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('SAVE_SUCCESS_FILE_ALTTEXT'),
                \fpcm\view\message::TYPE_NOTICE
            ))->fetch();
        }

        $this->response->setReturnData(new \fpcm\view\message(
            $this->language->translate('SAVE_FAILED_FILE_ALTTEXT'),
            \fpcm\view\message::TYPE_ERROR
        ))->fetch();

        return true;
    }

}

?>