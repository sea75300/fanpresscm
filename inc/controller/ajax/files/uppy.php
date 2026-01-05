<?php

/**
 * AJAX uppy upload controller
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021-2025, Stefan Seehafer
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
        return $this->permissions->uploads->visible && $this->permissions->uploads->add;
    }

    /**
     * Controller processing
     * @return void
     */
    public function process()
    {
        $config = $this->processByParam('process', 'dest');
        if ($config === self::ERROR_PROCESS_BYPARAMS) {
            $this->response->setCode('501')->addHeaders('HTTP/1.1 501 Not Implemented')->fetch();
        }

    }

    /**
     * Default upload
     * @return void
     */
    protected function processDefault()
    {
        if (!$this->permissions->uploads->visible || !$this->permissions->uploads->add) {
            $this->response->setCode(403)->fetch();
        }

        $file = $this->request->fromFiles('file');
        if ($file === null) {
            $this->response->setCode(400)->fetch();
        }

        $realFile = $file['name'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            trigger_error(sprintf( \fpcm\model\files\fileuploader::matchUploadError($file['error']) , $realFile) );
            $this->response->setCode(400)->fetch();
        }

        $tmpFile = $file['tmp_name'];
        if (!is_uploaded_file($tmpFile)) {
            $this->response->setCode(400)->fetch();
        }

        $mime = \fpcm\model\abstracts\file::retrieveRealType($tmpFile);
        $ext = \fpcm\model\abstracts\file::retrieveFileExtension($realFile);

        $type = \fpcm\model\files\mediaFile::getMediaFileType($ext, $mime);

        $mtypes = [\fpcm\model\files\mediaFile::TYPE_IMAGE, \fpcm\model\files\mediaFile::TYPE_AUDIOVIDEO];
        if (!in_array($type, $mtypes)) {
            trigger_error('Unsupported filetype '.$mime.' in ' . $realFile);
            $this->response->setCode(415)->fetch();
        }
        
        $obj = new \fpcm\model\files\mediaFile($realFile);
        $obj->addUploadFolder();
        $obj->setMediaType($type);

        if (!$obj->moveUploadedFile($tmpFile)) {
            trigger_error('Unable to move uploaded to to uploader folder! ' . $realFile);
            $this->response->setCode(500)->fetch();
        }

        if ($obj->isImage()) {
            $obj->createThumbnail();
        }

        $obj->setFiletime(time());
        $obj->setUserid($this->session->getUserId());

        $this->response->setCode(200)->fetch();
    }

    /**
     * Drafts upload
     * @return void
     */
    protected function processDrafts()
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
     * User image upload
     * @return void
     */
    protected function processUserimage()
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

        $mime = \fpcm\model\abstracts\file::retrieveRealType($tmpFile);
        $ext = \fpcm\model\abstracts\file::retrieveFileExtension($realFile);

        if (!\fpcm\model\files\authorImage::isValidType($ext, $mime)) {
            trigger_error('Unsupported filetype '.$mime.' in ' . $realFile);
            $this->response->setCode(415)->fetch();
        }

        if ($file['size'] > FPCM_AUTHOR_IMAGE_MAX_SIZE) {
            trigger_error('Uploaded file ' . $realFile . ' is to large, maximum size is ' . \fpcm\classes\tools::calcSize(FPCM_AUTHOR_IMAGE_MAX_SIZE));
            $this->response->setCode(431)->fetch();
        }

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
     * CSV upload
     * @return void
     */
    protected function processCsv()
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
     * Module upload
     * @return void
     */
    protected function processModules()
    {
        $this->response->setCode(415)->fetch();

        $unique = \fpcm\classes\tools::getHash($this->session->getSessionId().$this->session->getUserId());
    }

}
