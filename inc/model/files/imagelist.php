<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Image list object
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class imagelist extends \fpcm\model\abstracts\filelist {

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();

        $this->table = \fpcm\classes\database::tableFiles;
        $this->basepath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, '/');
        $this->exts = image::$allowedExts;
    }

    /**
     * Return list of files in file system
     * @return array
     */
    public function getFolderList()
    {
        $res = parent::getFolderList();
        $this->pathprefix = '????-??'.DIRECTORY_SEPARATOR;
        return array_merge($res, parent::getFolderList());
    }

    /**
     * Gibt Dateiindex in Datenbank zurück
     * @param int $limit
     * @param int $offset
     * @return array:\fpcm\model\files\image
     */
    public function getDatabaseList($limit = false, $offset = false)
    {
        $where = '1=1' . $this->dbcon->orderBy(['filetime DESC']);
        if ($limit !== false && $offset !== false) {
            $where .= ' ' . $this->dbcon->limitQuery($limit, $offset);
        }

        $images = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams($this->table))
            ->setWhere($where)->setFetchAll(true)
        );

        $res = [];
        foreach ($images as $image) {
            $imageObj = new image('', false);
            $imageObj->createFromDbObject($image);
            $res[$image->filename] = $imageObj;
        }

        return $res;
    }

    /**
     * Fetch file index by condition
     * @param \fpcm\model\files\search $conditions
     * @return array:\fpcm\model\files\image
     */
    public function getDatabaseListByCondition(search $conditions)
    {
        $where = [];
        $valueParams = [];
        $combination = '';
        
        if ($conditions->isMultiple()) {
            $this->assignMultipleSearchParams($conditions, $where, $valueParams, $combination);
        }
        else {
            $this->assignSearchParams($conditions, $where, $valueParams, $combination);
        }

        if (!count($where)) {
            $where = ['1=1'];
        }

        $where = implode(" {$combination} ", $where);

        $where2 = [];
        $where2[] = $this->dbcon->orderBy($conditions->orderby ? $conditions->orderby : ['filetime DESC']);

        if (is_array($conditions->limit)) {
            $where2[] = $this->dbcon->limitQuery($conditions->limit[0], $conditions->limit[1]);
        }

        $where .= ' ' . implode(' ', $where2);

        $images = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams($this->table))
            ->setWhere($where)->setParams($valueParams)->setFetchAll(true)
        );

        $res = [];
        foreach ($images as $image) {
            $imageObj = new image('', false);
            $imageObj->createFromDbObject($image);
            $res[$image->filename] = $imageObj;
        }

        return $res;
    }

    /**
     * Fetch file index by condition
     * @param \fpcm\model\files\search $conditions
     * @return array:\fpcm\model\files\image
     */
    public function getDatabaseCountByCondition(search $conditions)
    {
        $where = [];
        $valueParams = [];
        $combination = '';

        $this->assignSearchParams($conditions, $where, $valueParams, $combination);

        if (!count($where)) {
            $where = ['1=1'];
        }

        return $this->dbcon->count($this->table, 'id', implode(" {$combination} ", $where), $valueParams);
    }

    /**
     * Aktualisiert Dateiindex in Datenbank
     * @param int $userId
     */
    public function updateFileIndex($userId)
    {
        $folderFiles = $this->getFolderList();

        $dbFiles = $this->getDatabaseList();
        if (!$folderFiles || !count($folderFiles)) {
            return;
        }

        foreach ($folderFiles as $folderFile) {

            $this->removeBasePath($folderFile);
            if (isset($dbFiles[$folderFile])) {
                $dbFiles[$folderFile]->update();
                continue;
            }

            $image = new \fpcm\model\files\image($folderFile, false, true);
            $image->setFiletime($image->getModificationTime());
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
    public function getDatabaseFileCount()
    {
        return $this->dbcon->count($this->table);
    }

    /**
     * Erzeugt Thumbanils für Dateimanager
     * @param arraye $folderFiles
     */
    public function createFilemanagerThumbs($folderFiles = null)
    {
        include_once \fpcm\classes\loader::libGetFilePath('PHPImageWorkshop');

        $folderFiles = is_null($folderFiles) ? $this->getFolderList() : $folderFiles;
        $memoryLimit = \fpcm\classes\baseconfig::memoryLimit(true);

        $filesizeLimit = $memoryLimit * 0.025;
        foreach ($folderFiles as $folderFile) {

            if (filesize($folderFile) >= $filesizeLimit) {
                $msgPath = ops::removeBaseDir($folderFile);
                fpcmLogSystem("Skip filemanager thumbnail generation for {$msgPath} because of image dimension. You may reduce file size?");
                continue;
            }

            if (pathinfo($folderFile, PATHINFO_EXTENSION) == 'bmp' || substr($folderFile, -4) === '.bmp') {
                $msgPath = ops::removeBaseDir($folderFile);
                fpcmLogSystem("Skip filemanager thumbnail generation for {$msgPath}, \"".pathinfo($folderFile, PATHINFO_EXTENSION)."\" is no supported. You may use another image type?");
                continue;
            }

            $imgPath = $folderFile;
            $this->removeBasePath($imgPath);
            $image = new \fpcm\model\files\image($imgPath);

            if ($image->hasFileManageThumbnail()) {
                $image = null;
                $phpImgWsp = null;
                continue;
            }
            
            try {
                $phpImgWsp = \PHPImageWorkshop\ImageWorkshop::initFromPath($folderFile);
                $phpImgWsp->cropToAspectRatio(
                    \PHPImageWorkshop\Core\ImageWorkshopLayer::UNIT_PIXEL,
                    100, 100, 0, 0, 'MM'
                );

                $phpImgWsp->resizeInPixel(100, 100);
                $phpImgWsp->save(dirname($image->getFileManagerThumbnail()), basename($image->getFileManagerThumbnail()));
            } catch (\ErrorException $exc) {
                trigger_error('Error while creating filemanager thumbnail '.$image->getFileManagerThumbnail().PHP_EOL.$exc->getMessage());
                continue;
            }

            if (!$image->hasFileManageThumbnail()) {
                trigger_error('Unable to create filemanager thumbnail: ' . $image->getFileManagerThumbnail());
            }

            $image = null;
            $phpImgWsp = null;
        }
    }

    /**
     * Gibt aktuelle Größe des upload-Ordners in byte zurück
     * @return int
     */
    public function getUploadFolderSize()
    {
        $result = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))->setItem('SUM(filesize) as sizesum'));
        if (!is_object($result) || !isset($result->sizesum)) {
            return 0;
        }

        return $result->sizesum;
    }

    /**
     * Assigns search params from search object to where condition
     * @param \fpcm\model\files\search $conditions
     * @param array $where
     * @param array $valueParams
     * @param string $combination
     * @return bool
     * @since FPCm 4.3
     */
    private function assignSearchParams(search $conditions, array &$where, array &$valueParams, string $combination)
    {
        if ($conditions->filename) {
            $where[] = "filename ".$this->dbcon->dbLike()." ?";
            $valueParams[] = '%' . $conditions->filename . '%';
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

        return true;
    }

    /**
     * Assigns search params object to value arrays
     * @param \fpcm\model\files\search $conditions
     * @param array $where
     * @param array $valueParams
     * @param string $combination
     * @return bool
     */
    private function assignMultipleSearchParams(search $conditions, array &$where, array &$valueParams, string $combination) : bool
    {
        if ($conditions->filename) {
            $where[] = "filename ".$this->dbcon->dbLike()." ?";
            $valueParams[] = '%' . $conditions->filename . '%';
        }

        if ($conditions->datefrom !== null) {
            $where[] = $conditions->getCondition('datefrom', 'filetime >= ?');
            $valueParams[] = $conditions->datefrom;
        }

        if ($conditions->dateto !== null) {
            $where[] = $conditions->getCondition('dateto', 'filetime <= ?');
            $valueParams[] = $conditions->dateto;
        }

        if ($conditions->userid > -1) {
            $where[] = $conditions->getCondition('userid', $this->dbcon->inQuery('userid', [0, $conditions->userid]));
            $valueParams[] = 0;
            $valueParams[] = $conditions->userid;
        }

        return true;
    }

}

?>