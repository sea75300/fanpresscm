<?php

/**
 * AJAX jQuery uploader controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller for uppy uploader
 * 
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class uppy extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     *
     * @var string
     */
    protected $dest = '';

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->uploads->visible && $this->permissions->uploads->add && defined('FPCM_UPLOADER_UPPY') && FPCM_UPLOADER_UPPY;
    }
    
    /**
     * Controller-Processing
     */
    public function process()
    {
        $file = $this->request->fromFiles('file');
        if ($file === null) {
            $this->response->setCode(400)->fetch();
        }
        
        $realFile = $file['name'];
        $tmpFile = $file['tmp_name'];
        if (!is_uploaded_file($tmpFile)) {
            $this->response->setCode(400)->fetch();
        }
        
        $mime = (new \finfo)->file($tmpFile, FILEINFO_MIME_TYPE);
        if (!\fpcm\model\files\image::isValidType(\fpcm\model\files\image::retrieveFileExtension($realFile), $mime)) {
            trigger_error('Unsupported filetype '.$mime.' in ' . $realFile);
            $this->response->setCode(415)->fetch();
        }
        
        $image = new \fpcm\model\files\image($realFile);
        if (!$image->moveUploadedFile($tmpFile)) {
            trigger_error('Unable to move uploaded to to uploader folder! ' . $realFile);
            $this->response->setCode(500)->fetch();
        }
        
        $image->createThumbnail();
        $image->setFiletime(time());
        $image->setUserid($this->session->getUserId());

        if ($image->exists()) {

            if (!$image->update()) {
                trigger_error('Unable to update uploaded file to database list! ' . $realFile);
                $this->response->setCode(500)->fetch();
            }

        }
        elseif (!$image->save()) {
            trigger_error('Unable to add uploaded file to database list! ' . $realFile);
            $this->response->setCode(500)->fetch();
        }

        $this->response->setReturnData([
            'url' => $image->getImageUrl()
        ])->fetch();
        
//        require_once \fpcm\classes\loader::libGetFilePath('jqupload/server/fpcmUploadHandler.php');
//
//        $config = $this->processByParam('getConfig', 'dest');
//        if ($config === self::ERROR_PROCESS_BYPARAMS) {
//            $this->response->setCode('501')->addHeaders('HTTP/1.1 501 Not Implemented')->fetch();
//        }
//
//        $config['script_url'] = \fpcm\classes\tools::getFullControllerLink('ajax/jqupload', [ 'dest' => $this->dest ]);
//        
//        new \fpcmUploadHandler($config);

    }

    /**
     * 
     * @return array
     */
    protected function getConfigDefault() : array
    {
        return [
            'upload_dir' => \fpcm\model\files\ops::getUploadPath(DIRECTORY_SEPARATOR, $this->config->file_subfolders),
            'upload_url' => \fpcm\model\files\ops::getUploadUrl('/', $this->config->file_subfolders),
            'accept_file_types' => \fpcm\components\fileupload\jqupload::FILETYPES_IMG,
            'image_versions' => array(
                'thumbnail' => array(
                    'upload_dir' => \fpcm\model\files\ops::getUploadPath(DIRECTORY_SEPARATOR.'thumbs'.DIRECTORY_SEPARATOR),
                    'upload_url' => \fpcm\model\files\ops::getUploadUrl('/thumbs/'),
                    'crop' => false,
                    'max_width' => $this->config->file_thumb_size,
                    'max_height' => $this->config->file_thumb_size
                )
            ),
            'min_width' => false,
            'max_width' => false,
            'min_height' => false,
            'max_height' => false
        ];
    }

    /**
     * 
     * @return array
     */
    protected function getConfigDrafts() : array
    {
        return [
            'upload_dir' => \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DRAFTS, DIRECTORY_SEPARATOR),
            'upload_url' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_DRAFTS, '/'),
            'accept_file_types' => \fpcm\components\fileupload\jqupload::FILETYPES_DRAFTS,
            'image_versions' => array(),
            'min_width' => false,
            'max_width' => false,
            'min_height' => false,
            'max_height' => false
        ];
    }

    /**
     * 
     * @return array
     */
    protected function getConfigCsv() : array
    {
        $unique = \fpcm\classes\tools::getHash($this->session->getSessionId().$this->session->getUserId());
        
        return [
            'upload_dir' => \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, DIRECTORY_SEPARATOR . $unique . DIRECTORY_SEPARATOR),
            'upload_url' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_TEMP, '/'),
            'accept_file_types' => \fpcm\components\fileupload\jqupload::FILETYPES_CSV,
            'image_versions' => array(),
            'min_width' => false,
            'max_width' => false,
            'min_height' => false,
            'max_height' => false
        ];
    }

    /**
     * 
     * @return array
     */
    protected function getConfigModules() : array
    {
        $unique = \fpcm\classes\tools::getHash($this->session->getSessionId().$this->session->getUserId());

        return [
            'upload_dir' => \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, DIRECTORY_SEPARATOR. $unique. DIRECTORY_SEPARATOR),
            'upload_url' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_TEMP, '/'. $unique . '/'),
            'accept_file_types' => \fpcm\components\fileupload\jqupload::FILETYPES_MODULES,
            'max_number_of_files' => 1,
            'image_versions' => array(),
            'replace_dots_in_filenames' => null,
            'min_width' => false,
            'max_width' => false,
            'min_height' => false,
            'max_height' => false
        ];
    }

}

?>