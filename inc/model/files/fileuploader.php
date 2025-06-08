<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * PHP fileupload handler
 *
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @deprecated 5.3-dev
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
        $ev = $this->events->trigger('fileupload\phpBefore', $this->uploader);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event fileupload\phpBefore failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $this->uploader = $ev->getData();

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

            if ($image->exists()) {

                if (!$image->update()) {
                    trigger_error('Unable to update uploaded file to database list! ' . $fileNames[$key]);
                    $res['error'][$key] = $fileNames[$key];
                    continue;
                }

            }
            elseif (!$image->save()) {
                trigger_error('Unable to add uploaded file to database list! ' . $fileNames[$key]);
                $res['error'][$key] = $fileNames[$key];
                continue;
            }

            $fmThumbs = new \fpcm\model\crons\fmThumbs();
            $fmThumbs->run();

            $res['success'][$key] = $fileNames[$key];
        }

        $ev = $this->events->trigger('fileupload\phpAfter', [
            'uploader' => $this->uploader,
            'results' => $res
        ]);

        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event fileupload\phpAfter failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        return $res;
    }

    /**
     * Führt Upload eines Artikel-Bildes aus
     * @param string $filename
     * @since 3.6
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
            return basename(dirname($fileName)).'/'.\fpcm\classes\tools::escapeFileName(basename($fileName));
        }

        return \fpcm\classes\tools::escapeFileName(basename($fileName));
    }

    /**
     * Get file information via finfo
     * @param string $filename
     * @param int $option
     * @return string|null
     * @since 4.5
     */
    protected function getFinfoData(string $filename, int $option = FILEINFO_MIME_TYPE) : ?string
    {
        if (!$this->finfo instanceof \finfo) {
            $this->finfo = new \finfo();
        }

        return $this->finfo->file($filename, $option);
    }

    /**
     * Check and match upload error codes from $_FILES
     * @param int $code
     * @return string
     * @since 5.2.0-a1
     */
    public static function matchUploadError(int $code) : string
    {
        return match ($code) {
            1 => 'The file %s exceeds the maximum file size of PHP.',
            2 => 'The file %s exceeds the maximum file size  in the HTML form.',
            3 => 'The file %s was only partially uploaded.',
            4 => 'No file was uploaded.',
            6 => 'Temporary folder not found.',
            7 => 'Failed to write file %s to disk.',
            8 => 'A PHP extension stopped the upload of file %s.',
            default => ''
        };

    }
}
