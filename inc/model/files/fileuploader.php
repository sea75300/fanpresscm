<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * PHP fileupload handler
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class fileuploader extends \fpcm\model\abstracts\staticModel {

    /**
     * Array mit $_FILES Struktur
     * @var array
     */
    protected $uploader;

    /**
     *
     * @var \finfo
     */
    private $finfo = null;

    /**
     * Konstruktor
     * @param array $uploader includes $_FILES array
     */
    public function __construct(array $uploader)
    {
        parent::__construct();
        $this->uploader = $uploader;
    }

    /**
     * Führt Upload via HTML-Form + PHP in Dateimanager durch
     * @param int $userId
     * @return array
     */
    public function processUpload($userId)
    {
        $this->uploader = $this->events->trigger('fileupload\phpBefore', $this->uploader);

        $tempNames = $this->uploader['tmp_name'];
        $fileNames = $this->uploader['name'];
        $fileTypes = $this->uploader['type'];

        $res = ['error' => [], 'success' => []];

        foreach ($tempNames as $key => $value) {

            if (!is_uploaded_file($value) || !isset($fileNames[$key]) || !isset($fileTypes[$key])) {
                continue;
            }

            $mime = $this->getFinfoData($value);
            if ($mime === null) {
                $mime = $fileTypes[$key];
            }
            if ($fileTypes[$key] !== $mime || !image::isValidType(\fpcm\model\abstracts\file::retrieveFileExtension($fileNames[$key]), $mime )) {
                trigger_error('Unsupported filetype '.$mime.' in ' . $fileNames[$key]);
                $res['error'][$key] = $fileNames[$key];
                continue;
            }

            $fileName = $this->getUploadFileName($fileNames[$key]);
            $uploadParent = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, dirname($fileName));
            if ($this->config->file_subfolders && !is_dir($uploadParent) && !mkdir($uploadParent)) {
                trigger_error('Failed to create month-based upload parent folder');
                return false;                
            }

            $image = new image($fileName);
            if (!$image->moveUploadedFile($value)) {
                trigger_error('Unable to move uploaded to to uploader folder! ' . $fileNames[$key]);
                $res['error'][$key] = $fileNames[$key];
                continue;
            }

            $image->createThumbnail();
            $image->setFiletime(time());
            $image->setUserid($userId);
            if (!$image->save()) {
                trigger_error('Unable to add uploaded file to database list! ' . $fileNames[$key]);
                $res['error'][$key] = $fileNames[$key];
                continue;
            }

            $fmThumbs = new \fpcm\model\crons\fmThumbs();
            $fmThumbs->run();

            $res['success'][$key] = $fileNames[$key];
        }

        $this->events->trigger('fileupload\phpAfter', [
            'uploader' => $this->uploader,
            'results' => $res
        ]);

        return $res;
    }

    /**
     * Führt Upload von Module-Package via HTML-Form + PHP sowie Installation aus Modulmanager durch
     * @return bool
     */
    public function processModuleUpload()
    {
        $tempNames = $this->uploader['tmp_name'];
        $fileNames = $this->uploader['name'];
        $fileTypes = $this->uploader['type'];

        foreach ($tempNames as $key => $value) {

            if (!is_uploaded_file($value) || !isset($fileNames[$key]) || !isset($fileTypes[$key])) {
                continue;
            }

            $mime = $this->getFinfoData($value);
            if ($mime === null) {
                $mime = $fileTypes[$key];
            }
            if ($fileTypes[$key] !== $mime ||
                $mime !== 'application/zip' ||
                \fpcm\model\abstracts\file::retrieveFileExtension($fileNames[$key])  !== \fpcm\model\packages\package::DEFAULT_EXTENSION ) {
                trigger_error('Unsupported filetype '.$mime.' in ' . $fileNames[$key]);
                $res['error'][$key] = $fileNames[$key];
                continue;
            }            
            
            $fileName = $this->getUploadFileName($fileNames[$key]);

            $path = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, $fileName);
            if (!move_uploaded_file($value, $path)) {
                return false;
            }

            $package = new \fpcm\model\packages\module($fileName);
            if (!$package->extract()) {
                return false;
            }

            if (!$package->copy()) {
                return false;
            }
            
            break;

        }

        $module = new \fpcm\module\module(\fpcm\module\module::getKeyFromFilename($fileName), false );
        if (!$module->addModule()) {
            return false;
        }

        $package->cleanup();
        return true;
    }

    /**
     * Führt Upload von HTML-Template für Artikle-Editor via HTML-Form + PHP durch
     * @since FPCM 3.3
     * @return bool
     */
    public function processArticleTemplateUpload()
    {
        $tempNames = $this->uploader['tmp_name'];
        $fileNames = $this->uploader['name'];
        $fileTypes = $this->uploader['type'];

        $combinations = array_combine(templatefile::$allowedExts, templatefile::$allowedTypes);
        
        foreach ($tempNames as $key => $value) {

            if (!is_uploaded_file($value) || !isset($fileNames[$key]) || !isset($fileTypes[$key])) {
                continue;
            }

            $ext = \fpcm\model\abstracts\file::retrieveFileExtension($fileNames[$key]);

            $mime = $this->getFinfoData($value);
            if ($mime === null) {
                $mime = $fileTypes[$key];
            }
            
            $assigned = $combinations[$ext] ?? null;
            if ($assigned === templatefile::$allowedTypes[0]) {
                $assigned = templatefile::$allowedTypes[1];
            }

            if ($assigned === null ){
                trigger_error('Unsupported filetype '.$mime.' in ' . $fileNames[$key]);
                return false;
            }

            $isValidType = in_array($mime, templatefile::$allowedTypes) && in_array($ext, templatefile::$allowedExts) && $assigned === $mime;
            if ($fileTypes[$key] !== $mime || !$isValidType) {
                trigger_error('Unsupported filetype '.$mime.' in ' . $fileNames[$key]);
                return false;
            }                        

            $file = new templatefile($fileNames[$key]);
            if (!$file->moveUploadedFile($value)) {
                trigger_error('Unable to move uploaded to to uploader folder! ' . $fileNames[$key]);
                return false;
            }
        }

        return true;
    }

    /**
     * Führt Upload eines Artikel-Bildes aus
     * @param string $filename
     * @since FPCM 3.6
     * @return bool
     */
    public function processAuthorImageUpload($filename)
    {
        if (!isset($this->uploader)) {
            return false;
        }

        if (!is_uploaded_file($this->uploader['tmp_name']) || !trim($this->uploader['name'])) {
            return false;
        }

        $mime = $this->getFinfoData($this->uploader['tmp_name']);
        if ($mime === null) {
            $mime = $this->uploader['type'];
        }        

        $ext = \fpcm\model\abstracts\file::retrieveFileExtension($this->uploader['name']);
        if ($this->uploader['type'] !== $mime || !authorImage::isValidType($ext, $mime )) {
            trigger_error('Unsupported filetype '.$mime.' in ' . $this->uploader['name']);
            return false;
        }

        if ($this->uploader['size'] > FPCM_AUTHOR_IMAGE_MAX_SIZE) {
            trigger_error('Uploaded file ' . $this->uploader['name'] . ' is to large, maximum size is ' . \fpcm\classes\tools::calcSize(FPCM_AUTHOR_IMAGE_MAX_SIZE));
            return false;
        }

        $file = new authorImage($filename . '.' . $ext);
        if (!$file->moveUploadedFile($this->uploader['tmp_name'])) {
            trigger_error('Unable to move uploaded to to uploader folder! ' . $this->uploader['name']);
            return false;
        }

        return true;
    }

    /**
     * Returns complete file name which includes sub folder name for uploaded images
     * @param string $fileName
     * @return string
     */
    public function getUploadFileName(string $fileName) : string
    {
        $fileName = ops::getUploadPath($fileName, $this->config->file_subfolders);

        if ($this->config->file_subfolders) {
            return basename(dirname($fileName)).'/'.basename($fileName);
        }

        return basename($fileName);
    }

    /**
     * Get file information via finfo
     * @param string $filename
     * @param int $option
     * @return string|null
     * @since FPCM FPCM 4.4.5
     */
    protected function getFinfoData(string $filename, int $option = FILEINFO_MIME_TYPE)
    {
        if (!class_exists('\finfo')) {
            return null;
}

        if (!$this->finfo instanceof \finfo) {
            $this->finfo = new \finfo();
        }

        return $this->finfo->file($filename, $option);
    }

}

?>