<?php
    /**
     * PHP file uploader model
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * PHP fileupload handler
     * 
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class fileuploader extends \fpcm\model\abstracts\staticModel {

        /**
         * Array mit $_FILES Struktur
         * @var array
         */
        protected $uploader;

        /**
         * Konstruktor
         * @param array $uploader $_FILES array
         */
        public function __construct(array $uploader) {
            parent::__construct();
            $this->uploader = $uploader;
        }
        
        /**
         * Führt Upload via HTML-Form + PHP in Dateimanager durch
         * @param int $userId
         * @return array
         */
        public function processUpload($userId) {

            $this->uploader = $this->events->runEvent('fileUploadPhpBefore', $this->uploader);
            
            $tempNames = $this->uploader['tmp_name'];
            $fileNames = $this->uploader['name'];
            $fileTypes = $this->uploader['type'];
            
            $res = array('error' => array(), 'success' => array());

            foreach ($tempNames as $key => $value) {               

                if (!is_uploaded_file($value) || !isset($fileNames[$key]) || !isset($fileTypes[$key])) continue;
                
                $ext = pathinfo($fileNames[$key], PATHINFO_EXTENSION);
                $ext = ($ext) ? strtolower($ext) : '';
                
                if ((isset($fileTypes[$key]) && !in_array($fileTypes[$key], image::$allowedTypes)) || !in_array($ext, image::$allowedExts)) {
                    trigger_error('Unsupported filetype in '.$fileNames[$key]);
                    $res['error'][$key] = $fileNames[$key];
                    continue;
                }
                
                $fileName = \fpcm\classes\baseconfig::$uploadDir.$fileNames[$key];
                $image = new image($fileNames[$key]);
                if (!$image->moveUploadedFile($value)) {
                    trigger_error('Unable to move uploaded to to uploader folder! '.$fileNames[$key]);
                    $res['error'][$key] = $fileNames[$key];
                    continue;
                }

                $image->createThumbnail();
                $image->setFiletime(time());
                $image->setUserid($userId);
                if (!$image->save()) {
                    trigger_error('Unable to add uploaded file to database list! '.$fileNames[$key]);
                    $res['error'][$key] = $fileNames[$key];
                    continue;
                }

                $fmThumbs = new \fpcm\model\crons\fmThumbs('fmThumbs');
                $fmThumbs->run();
                
                $res['success'][$key] = $fileNames[$key];
            }
            
            $this->events->runEvent('fileUploadPhpAfter', array('uploader' => $this->uploader, 'results' => $res));
            
            return $res;
        }
        
        /**
         * Führt Upload von Module-Package via HTML-Form + PHP sowie Installation aus Modulmanager durch
         * @return boolean
         */
        public function processModuleUpload() {

            $tempNames = $this->uploader['tmp_name'];
            $fileNames = $this->uploader['name'];
            $fileTypes = $this->uploader['type'];

            foreach ($tempNames as $key => $value) {               

                if (!is_uploaded_file($value) || !isset($fileNames[$key]) || !isset($fileTypes[$key])) continue;
                
                $ext = pathinfo($fileNames[$key], PATHINFO_EXTENSION);
                $ext = ($ext) ? strtolower($ext) : '';
                
                if ($ext != 'zip') return false;
                
                $fileName = \fpcm\classes\baseconfig::$tempDir.$fileNames[$key];
                if (!move_uploaded_file($value, $fileName)) return false;
                
                $data = \fpcm\model\packages\package::explodeModuleFileName(basename($fileNames[$key], '.zip'));

                $package = new \fpcm\model\packages\module('module', $data[0], $data[1]);                
                $res     = $package->extract();
                
                $extractPath = $package->getExtractPath();
                        
                $modulelisteConfigFile = realpath($extractPath.$data[0].'/config/modulelist.yml');
                
                if (!file_exists($modulelisteConfigFile)) {
                    return $res;
                }

                include_once \fpcm\classes\loader::libGetFilePath('spyc', 'Spyc.php');
                $modulelisteConfig = \Spyc::YAMLLoad($modulelisteConfigFile);                  
                
                if ($res !== true) return $res;
                
                $package->setCopyDestination($modulelisteConfig['vendor'].'/');
                $res     = $package->copy();

                if ($res !== true) return $res;
                
                $package->cleanup();
                
                $moduleClass = \fpcm\model\abstracts\module::getModuleClassName($modulelisteConfig['vendor'].'/'.$modulelisteConfig['key']);
                if (class_exists($moduleClass)) {
                    $modObj = new $moduleClass($modulelisteConfig['vendor'].$modulelisteConfig['key'], '', $data[1]);
                    $res    = ($modObj->isInstalled() ? $modObj->runUpdate() : $modObj->runInstall());
                    
                    if ($res !== true) return $res;
                }
            }
            
            return true;
        }
        
        /**
         * Führt Upload von HTML-Template für Artikle-Editor via HTML-Form + PHP durch
         * @since FPCM 3.3
         * @return boolean
         */
        public function processArticleTemplateUpload() {

            $tempNames = $this->uploader['tmp_name'];
            $fileNames = $this->uploader['name'];
            $fileTypes = $this->uploader['type'];

            foreach ($tempNames as $key => $value) {               

                if (!is_uploaded_file($value) || !isset($fileNames[$key]) || !isset($fileTypes[$key]) || $this->uploader['name'] == 'index.html' || $this->uploader['name'] == 'index.htm') {
                    return false;
                }
                
                $ext = pathinfo($fileNames[$key], PATHINFO_EXTENSION);
                $ext = ($ext) ? strtolower($ext) : '';
                
                if ((isset($fileTypes[$key]) && !in_array($fileTypes[$key], templatefile::$allowedTypes)) || !in_array($ext, templatefile::$allowedExts)) {
                    trigger_error('Unsupported filetype in '.$fileNames[$key]);
                    return false;
                }

                $file = new templatefile($fileNames[$key]);
                if (!$file->moveUploadedFile($value)) {
                    trigger_error('Unable to move uploaded to to uploader folder! '.$fileNames[$key]);
                    return false;
                }

            }
            
            return true;
        }
        
        /**
         * Führt Upload eines Artikel-Bildes aus
         * @param string $filename
         * @since FPCM 3.6
         * @return boolean
         */
        public function processAuthorImageUpload($filename) {

            if (!isset($this->uploader)) {
                return false;
            }

            if (!is_uploaded_file($this->uploader['tmp_name']) || !trim($this->uploader['name'])) {
                return false;
            }

            if ($this->uploader['size'] > FPCM_AUTHOR_IMAGE_MAX_SIZE) {
                trigger_error('Uploaded file '.$this->uploader['name'].' is to large, maximum size is '.\fpcm\classes\tools::calcSize(FPCM_AUTHOR_IMAGE_MAX_SIZE));
                return false;
            }

            $ext = pathinfo($this->uploader['name'], PATHINFO_EXTENSION);
            $ext = ($ext) ? strtolower($ext) : '';

            if (!in_array($this->uploader['type'], authorImage::$allowedTypes) || !in_array($ext, authorImage::$allowedExts)) {
                trigger_error('Unsupported filetype in '.$this->uploader['name']);
                return false;
            }

            $file = new authorImage($filename.'.'.$ext);
            if (!$file->moveUploadedFile($this->uploader['tmp_name'])) {
                trigger_error('Unable to move uploaded to to uploader folder! '.$this->uploader['name']);
                return false;
            }

            return true;
        }
    }
?>