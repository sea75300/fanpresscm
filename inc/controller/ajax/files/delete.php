<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller to delete single file
 * 
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class delete extends \fpcm\controller\abstracts\ajaxController
{

    /**
     *
     * @var string
     */
    private $fileName = '';

    /**
     * 
     * @var array
     */
    protected $returnData;

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
            \fpcm\model\http\request::FILTER_BASE64DECODE,
            \fpcm\model\http\request::FILTER_DECRYPT
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
        $this->response->fetch();
    }
    
    private function deleteSingle()
    {
        if (!is_string($this->fileName) || !trim($this->fileName)) {
            
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('DELETE_FAILED_FILES', ''),
                \fpcm\view\message::TYPE_ERROR
            ));

            return false;
        }
        
        $replace = ['{{filenames}}' => basename($this->fileName)];

        $img = new \fpcm\model\files\image($this->fileName);
        if ($img->isValidDataFolder('', \fpcm\classes\dirs::DATA_UPLOADS) && $img->delete()) {
            
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('DELETE_SUCCESS_FILES', $replace),
                \fpcm\view\message::TYPE_NOTICE
            ));            

            return true;
        }

        $this->response->setReturnData(new \fpcm\view\message(
            $this->language->translate('DELETE_FAILED_FILES', $replace),
            \fpcm\view\message::TYPE_ERROR
        ));

        return false;
    }
    
    private function deleteMultiple()
    {
        if (!is_array($this->fileName) || !count($this->fileName)) {
            
            $this->response->setReturnData([new \fpcm\view\message(
                $this->language->translate('DELETE_FAILED_FILES', ''),
                \fpcm\view\message::TYPE_ERROR
            )]);

            return false;
        }

        array_walk($this->fileName, function ($fileName)
        {
            if ((new \fpcm\model\files\image($fileName))->delete()) {
                $this->deleted['ok'][] = $fileName;
                return true;
            }

            $this->deleted['failed'][] = $fileName;
            return false;
        });

        if (count($this->deleted['ok'])) {

            $this->returnData[] = new \fpcm\view\message(
                $this->language->translate('DELETE_SUCCESS_FILES', [
                    '{{filenames}}' => implode(', ', $this->deleted['ok'])
                ]),
                \fpcm\view\message::TYPE_NOTICE
            );
        }

        if (count($this->deleted['failed'])) {
            $this->returnData[] = new \fpcm\view\message(
                    $this->language->translate('DELETE_FAILED_FILES', [
                    '{{filenames}}' => implode(', ', $this->deleted['failed'])
                ]),
                \fpcm\view\message::TYPE_ERROR
            );
        }
        
        $this->response->setReturnData($this->returnData);

    }

}

?>