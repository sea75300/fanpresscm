<?php
    /**
     * Image list object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Image list object
     * 
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */    
    final class imagelist extends \fpcm\model\abstracts\filelist {

        /**
         * Konstruktor
         */
        public function __construct() {
            $this->table    = \fpcm\classes\database::tableFiles;
            $this->basepath = \fpcm\classes\baseconfig::$uploadDir;
            $this->exts     = image::$allowedExts;
            
            parent::__construct();
        }
        
        /**
         * Gibt Dateiindex in Datenbank zurück
         * @param int $limit
         * @param int $offset
         * @return array:\fpcm\model\files\image
         */
        public function getDatabaseList($limit = false, $offset = false) {
            
            $where = '1=1'.$this->dbcon->orderBy(array('filetime DESC'));
            if ($limit !== false && $offset !== false) {
                $where .= ' '.$this->dbcon->limitQuery($limit, $offset);
            }

            $images = $this->dbcon->fetch(
                $this->dbcon->select($this->table, '*', $where),
                true
            );

            $res = [];
            foreach ($images as $image) {
                $imageObj = new image('', '', '', false);
                $imageObj->createFromDbObject($image);
                $res[$image->filename] = $imageObj;
            }
            
            return $res;            
        }
        
        /**
         * Gibt Dateiindex in Datenbank zurück
         * @param int $limit
         * @param int $offset
         * @return array:\fpcm\model\files\image
         */
        
        /**
         * Gibt Dateiindex anahdn von bestimmten Bedigungen zurück
         * @param \fpcm\model\files\search $conditions
         * @return array:\fpcm\model\files\image
         */
        public function getDatabaseListByCondition(search $conditions) {

            $where = array('1=1');
            $valueParams = [];

            if ($conditions->filename) {
                $where[] = "filename LIKE ?";
                $valueParams[] = '%'.$conditions->filename.'%';
            }

            if ($conditions->datefrom) {
                $where[] = "filetime >= ?";
                $valueParams[] = $conditions->datefrom;
            }
            
            if ($conditions->dateto) {
                $where[] = "filetime <= ?";
                $valueParams[] = $conditions->dateto;
            }

            $combination = $conditions->combination ? $conditions->combination : 'AND';

            $where       = implode(" {$combination} ", $where);
            
            $where2   = [];
            $where2[] = $this->dbcon->orderBy($conditions->limit ? $conditions->limit : ['filetime DESC']);
            
            if (is_array($conditions->limit)) {
                $where2[] = $this->dbcon->limitQuery($conditions->limit[0], $conditions->limit[1]);
            }

            $where .= ' '.implode(' ', $where2);

            $images = $this->dbcon->fetch(
                $this->dbcon->select(
                    $this->table, '*', $where,
                    $valueParams
                ),
                true
            );

            $res = [];
            foreach ($images as $image) {
                $imageObj = new image('', '', '', false);
                $imageObj->createFromDbObject($image);
                $res[$image->filename] = $imageObj;
            }
            
            return $res;            
        }

        /**
         * Aktualisiert Dateiindex in Datenbank
         * @param int $userId
         */
        public function updateFileIndex($userId) {
            $folderFiles    = $this->getFolderList();
            $dbFiles        = $this->getDatabaseList();
            
            if (!$folderFiles || !count($folderFiles) || count($folderFiles) == count($dbFiles)) {
                return;
            }

            foreach ($folderFiles as $folderFile) {
                if (isset($dbFiles[$folderFile])) continue;                
                $image = new \fpcm\model\files\image(basename($folderFile), '', '', false, true);
                $image->setFiletime(time());
                $image->setUserid($userId);
                
                if (!in_array($image->getMimetype(), image::$allowedTypes) || !in_array(strtolower($image->getExtension()), image::$allowedExts)) {
                    trigger_error("Filetype not allowed in \"$folderFile\".");
                    continue;
                }
                
                if (!$image->exists(true) && !$image->save()) {
                    trigger_error("Unable to save image \"$folderFile\" to database.");
                }
            }
            
            foreach ($dbFiles as $dbFile) {
                if (!$dbFile->existsFolder() && !$dbFile->delete()) {
                    trigger_error("Unable to remove image \"$folderFile\" from database.");
                }
            }
            
            $this->createFilemanagerThumbs($folderFiles);
        }
        
        /**
         * Liefert Anzahl von Dateieinträgen in Datenbank zurück
         * @return int
         * @since FPCM 3.1
         */
        public function getDatabaseFileCount() {
            return $this->dbcon->count($this->table);
        }

        /**
         * Erzeugt Thumbanils für Dateimanager
         * @param arraye $folderFiles
         */
        public function createFilemanagerThumbs($folderFiles = null) {            
            $folderFiles = is_null($folderFiles) ? $this->getFolderList() : $folderFiles;
            include_once \fpcm\classes\loader::libGetFilePath('PHPImageWorkshop', 'ImageWorkshop.php');

            $memoryLimit      = \fpcm\classes\baseconfig::memoryLimit(true);
            
            $filesizeLimit   = $memoryLimit * 0.025;
            $memoryWorkLimit = $memoryLimit * (\fpcm\classes\baseconfig::memoryLimit() < 128) ? 0.33 : 0.5;
            foreach ($folderFiles as $folderFile) {

                if (filesize($folderFile) >= $filesizeLimit) {
                    $msgPath = ops::removeBaseDir($folderFile);
                    fpcmLogSystem("Skip filemanager thumbnail generation for {$msgPath} because of image dimension. You may reduce file size?");
                    continue;
                }
                
                $phpImgWsp = \PHPImageWorkshop\ImageWorkshop::initFromPath($folderFile);
                $image     = new \fpcm\model\files\image(basename($folderFile), '', '');
                if (file_exists($image->getFileManagerThumbnail())) {
                    $image     = null;
                    $phpImgWsp = null;
                    continue;
                }

                if (memory_get_usage(true) < $memoryWorkLimit) {
                    $phpImgWsp->cropMaximumInPixel(0, 0, "MM");
                }

                $phpImgWsp->resizeInPixel(100, 100);
                $phpImgWsp->save(dirname($image->getFileManagerThumbnail()), basename($image->getFileManagerThumbnail()));

                if (!file_exists($image->getFileManagerThumbnail())) {
                    trigger_error('Unable to create filemanager thumbnail: '.$image->getFileManagerThumbnail());
                }

                $image     = null;
                $phpImgWsp = null;
            }
        }
        
        /**
         * Gibt aktuelle Größe des upload-Ordners in byte zurück
         * @return int
         */
        public function getUploadFolderSize() {     
            return array_sum(array_map('filesize', $this->getFolderList()));
        }
        
    }
?>