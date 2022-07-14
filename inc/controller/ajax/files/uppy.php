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
class uppy extends \fpcm\controller\abstracts\ajaxController
{

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
        return defined('FPCM_UPLOADER_UPPY') && FPCM_UPLOADER_UPPY;
    }
    
    /**
     * Controller-Processing
     */
    public function process()
    {
        $config = $this->processByParam('process', 'dest');
        if ($config === self::ERROR_PROCESS_BYPARAMS) {
            $this->response->setCode('501')->addHeaders('HTTP/1.1 501 Not Implemented')->fetch();
        }        

    }

    /**
     * 
     * @return array
     */
    protected function processDefault() : array
    {
        if (!$this->permissions->uploads->visible || !$this->permissions->uploads->add) {
            $this->response->setCode(403)->fetch();
        }
        
        $file = $this->request->fromFiles('file');
        if ($file === null) {
            $this->response->setCode(400)->fetch();
        }
        
        $realFile = $file['name'];
        $tmpFile = $file['tmp_name'];
        if (!is_uploaded_file($tmpFile)) {
            $this->response->setCode(400)->fetch();
        }

        $mime = \fpcm\model\files\image::retrieveRealType($tmpFile);
        if (!\fpcm\model\files\image::isValidType(\fpcm\model\files\image::retrieveFileExtension($realFile), $mime)) {
            trigger_error('Unsupported filetype '.$mime.' in ' . $realFile);
            $this->response->setCode(415)->fetch();
        }
        
        $obj = new \fpcm\model\files\image($realFile);
        $obj->addUploadFolder();
        if (!$obj->moveUploadedFile($tmpFile)) {
            trigger_error('Unable to move uploaded to to uploader folder! ' . $realFile);
            $this->response->setCode(500)->fetch();
        }
        
        $obj->createThumbnail();
        $obj->setFiletime(time());
        $obj->setUserid($this->session->getUserId());

        if ($obj->exists()) {

            if (!$obj->update()) {
                trigger_error('Unable to update uploaded file to database list! ' . $realFile);
                $this->response->setCode(500)->fetch();
            }

        }
        elseif (!$obj->save()) {
            trigger_error('Unable to add uploaded file to database list! ' . $realFile);
            $this->response->setCode(500)->fetch();
        }

        $this->response->setReturnData([
            'url' => $obj->getImageUrl()
        ])->fetch();
    }

    /**
     * 
     * @return array
     */
    protected function processDrafts() : array
    {
        if (!$this->permissions->system->drafts) {
            $this->response->setCode(403)->fetch();
        }

        $file = $this->request->fromFiles('file');
        if ($file === null) {
            $this->response->setCode(400)->fetch();
        }
        
        $realFile = $file['name'];
        $tmpFile = $file['tmp_name'];
        if (!is_uploaded_file($tmpFile)) {
            $this->response->setCode(400)->fetch();
        }

        $mime = \fpcm\model\files\templatefile::retrieveRealType($tmpFile);
        if (!\fpcm\model\files\templatefile::isValidType(\fpcm\model\files\templatefile::retrieveFileExtension($realFile), $mime)) {
            trigger_error('Unsupported filetype '.$mime.' in ' . $realFile);
            $this->response->setCode(415)->fetch();
        }
        
        $obj = new \fpcm\model\files\templatefile($realFile);
        if (!$obj->moveUploadedFile($tmpFile)) {
            trigger_error('Unable to move uploaded to to uploader folder! ' . $realFile);
            $this->response->setCode(500)->fetch();
        }

        $this->response->setReturnData([
            'url' => $obj->getFileUrl()
        ])->fetch();
    }

    /**
     * 
     * @return array
     */
    protected function processUserimage() : array
    {
        $userId = $this->request->fromGET('uid', [\fpcm\model\http\request::FILTER_CASTINT]);
        if (!$userId)  {
            $this->response->setCode(400)->fetch();
        }
        
        $author = new \fpcm\model\users\author($userId);
        $this->cache->cleanup();
        
        $file = $this->request->fromFiles('file');
        if ($file === null) {
            $this->response->setCode(400)->fetch();
        }
        
        $realFile = $file['name'];
        $tmpFile = $file['tmp_name'];
        if (!is_uploaded_file($tmpFile)) {
            $this->response->setCode(400)->fetch();
        }

        $mime = \fpcm\model\files\image::retrieveRealType($tmpFile);
        if (!\fpcm\model\files\authorImage::isValidType(\fpcm\model\files\image::retrieveFileExtension($realFile), $mime)) {
            trigger_error('Unsupported filetype '.$mime.' in ' . $realFile);
            $this->response->setCode(415)->fetch();
        }
        
        if ($file['size'] > FPCM_AUTHOR_IMAGE_MAX_SIZE) {
            trigger_error('Uploaded file ' . $realFile . ' is to large, maximum size is ' . \fpcm\classes\tools::calcSize(FPCM_AUTHOR_IMAGE_MAX_SIZE));
            $this->response->setCode(431)->fetch();
        }
        
        $ext = \fpcm\model\abstracts\file::retrieveFileExtension($realFile);
        $obj = new \fpcm\model\files\authorImage($author->getImage() . '.' . $ext);
        if (!$obj->moveUploadedFile($tmpFile)) {
            trigger_error('Unable to move uploaded to to uploader folder! ' . $realFile);
            $this->response->setCode(500)->fetch();
        }

        $this->response->setReturnData([
            'url' => $obj->getImageUrl()
        ])->fetch();
    }

    /**
     * 
     * @return array
     */
    protected function processCsv() : array
    {
        if (!$this->permissions->system->options) {
            $this->response->setCode(403)->fetch();
        }

        $file = $this->request->fromFiles('file');
        if ($file === null) {
            $this->response->setCode(400)->fetch();
        }
        
        $realFile = $file['name'];
        $tmpFile = $file['tmp_name'];
        if (!is_uploaded_file($tmpFile)) {
            $this->response->setCode(400)->fetch();
        }

        $mime = \fpcm\model\files\templatefile::retrieveRealType($tmpFile);
        if (!in_array($mime, ['text/plain', 'text/csv'])) {
            trigger_error('Unsupported filetype '.$mime.' in ' . $realFile);
            $this->response->setCode(415)->fetch();
        }

        $unique = \fpcm\classes\tools::getHash($this->session->getSessionId().$this->session->getUserId());
        
        $uniquePath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP) . DIRECTORY_SEPARATOR . $unique . DIRECTORY_SEPARATOR;

        if ( !file_exists($uniquePath) && !mkdir($uniquePath) ) {
            $this->response->setCode(400)->fetch();
        }
        
        $baseName = basename($realFile, '.csv');
        
        $obj = new \fpcm\model\files\csvFile( $unique . DIRECTORY_SEPARATOR . $baseName , null, null);
        if (!$obj->moveUploadedFile($tmpFile)) {
            trigger_error('Unable to move uploaded to to uploader folder! ' . $realFile);
            $this->response->setCode(500)->fetch();
        }

        $this->response->setReturnData([
            'url' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_TEMP, $unique . '/' . $baseName),
        ])->fetch();
    }

    /**
     * 
     * @return array
     */
    protected function processModules() : array
    {
        $this->response->setCode(415)->fetch();

        $unique = \fpcm\classes\tools::getHash($this->session->getSessionId().$this->session->getUserId());
//
//        return [
//            'upload_dir' => \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, DIRECTORY_SEPARATOR. $unique. DIRECTORY_SEPARATOR),
//            'upload_url' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_TEMP, '/'. $unique . '/'),
//            'accept_file_types' => \fpcm\components\fileupload\jqupload::FILETYPES_MODULES,
//            'max_number_of_files' => 1,
//            'image_versions' => array(),
//            'replace_dots_in_filenames' => null,
//            'min_width' => false,
//            'max_width' => false,
//            'min_height' => false,
//            'max_height' => false
//        ];
    }

}

?>