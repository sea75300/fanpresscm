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
class delete extends \fpcm\controller\abstracts\ajaxControllerJSON implements \fpcm\controller\interfaces\isAccessible {

    /**
     *
     * @var string
     */
    private $fileName = '';

    /**
     *
     * @var bool
     */
    private $multiple = false;

    /**
     *
     * @var array
     */
    private $deleted = [
        'ok' => [],
        'failed' => [],
    ];

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->uploads->visible && $this->permissions->uploads->delete;
    }
    
    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->fileName = $this->request->fromPOST('filename', [
            \fpcm\model\http\request::FILTER_BASE64DECODE
        ]);

        $this->multiple = (bool) $this->request->fromPOST('multiple');
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        call_user_func([$this, 'delete'.($this->multiple ? 'Multiple' : 'Single')]);
        $this->getSimpleResponse();
    }
    
    private function deleteSingle()
    {
        if (!is_string($this->fileName) || !trim($this->fileName)) {
            $this->returnData['code'] = 0;
            $this->returnData['message'] = $this->language->translate('DELETE_FAILED_FILES', '');
            return false;
        }
        
        $replace = ['{{filenames}}' => basename($this->fileName)];

        $img = new \fpcm\model\files\image($this->fileName, false);
        if ($img->isValidDataFolder('', \fpcm\classes\dirs::DATA_UPLOADS) && $img->delete()) {
            $this->returnData['code'] = 1;
            $this->returnData['message'] = $this->language->translate('DELETE_SUCCESS_FILES', $replace);
            return true;
        }

        $this->returnData['code'] = 0;
        $this->returnData['message'] = $this->language->translate('DELETE_FAILED_FILES', $replace);
        return false;
    }
    
    private function deleteMultiple()
    {
        if (!is_array($this->fileName) || !count($this->fileName)) {
            $this->returnData['code'][1] = 0;
            $this->returnData['message'][1] = $this->language->translate('DELETE_FAILED_FILES', '');
            return false;
        }

        array_walk($this->fileName, function ($fileName)
        {
            if ((new \fpcm\model\files\image($fileName, false))->delete()) {
                $this->deleted['ok'][] = $fileName;
                return true;
            }

            $this->deleted['failed'][] = $fileName;
            return false;
        });

        if (count($this->deleted['ok'])) {
            $this->returnData['code'][1] = 1;
            $this->returnData['message'][1] = $this->language->translate('DELETE_SUCCESS_FILES', [
                '{{filenames}}' => implode(', ', $this->deleted['ok'])
            ]);
        }

        if (count($this->deleted['failed'])) {
            $this->returnData['code'][2] = 0;
            $this->returnData['message'][2] = $this->language->translate('DELETE_FAILED_FILES', [
                '{{filenames}}' => implode(', ', $this->deleted['failed'])
            ]);            
        }

    }

}

?>