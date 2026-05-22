<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Media files list object
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class mediaFilesList
extends \fpcm\model\abstracts\filelist
implements \fpcm\model\interfaces\gsearchIndex {

    /**
     * User id to use for file indexing
     * @var int
     * @since 4.5
     */
    private $indexUserId = 0;

    /**
     * finfo instance
     * @var \finfo
     * @since 4.5
     */
    private $finfo = null;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();

        $this->table = \fpcm\classes\database::tableFiles;
        $this->basepath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, '/');
        $this->exts = mediaFile::$allowedExts;
    }

    /**
     * Return list of files in file system
     * @return array
     */
    public function getFolderList(array $exsts = [])
    {
        if (count($exsts)) {
            $old = $this->exts;
            $this->exts = $exsts;
        }

        $res1 = parent::getFolderList();
        $this->pathprefix = '????-??'.DIRECTORY_SEPARATOR;
        $res2 = parent::getFolderList();

        if (!empty($old)) {
            $this->exts = $old;
        }

        return array_values(array_unique(
            array_merge( array_combine($res1, $res1), array_combine($res2, $res2) )
        ));
    }

    /**
     * Gibt Dateiindex in Datenbank zurück
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getDatabaseList($limit = false, $offset = false)
    {
        $where = '1=1' . $this->dbcon->orderBy(['filetime DESC']);
        if ($limit !== false && $offset !== false) {
            $where .= ' ' . $this->dbcon->limitQuery($limit, $offset);
        }

        $files = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams($this->table))
            ->setWhere($where)->setFetchAll(true)
        );

        $res = [];
        foreach ($files as $file) {
            $mfObj = new mediaFile('', false);
            $mfObj->createFromDbObject($file);
            $res[$file->filename] = $mfObj;
        }

        return $res;
    }

    /**
     * Fetch file index by condition
     * @param \fpcm\model\files\search $conditions
     * @return array
     */
    public function getDatabaseListByCondition(search $conditions)
    {
        $where = [];
        $valueParams = [];
        $combination = '';

        if ($conditions->isMultiple()) {
            $qas = $conditions->prepareFilterParams();

            $where = $qas->getQueries();
            $valueParams = $qas->getValues();
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

        $files = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams($this->table))
            ->setWhere($where)->setParams($valueParams)->setFetchAll(true)
        );

        if ($this->dbcon->getLastQueryErrorCode() === \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX) {
            return \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX;
        }

        $res = [];
        foreach ($files as $file) {
            $mfObj = new mediaFile('', false);
            $mfObj->createFromDbObject($file);
            $res[$file->filename] = $mfObj;
        }

        return $res;
    }

    /**
     * Fetch file index by condition
     * @param \fpcm\model\files\search $conditions
     * @return array
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
     * Updates file index
     * @param int $userId
     * @return bool
     */
    public function updateFileIndex($userId)
    {
        $fsIdx = $this->getFolderList();

        $dbIdx = $this->getDatabaseList();
        if (!$fsIdx || !count($fsIdx)) {
            return true;
        }

        if ($userId === '' || $userId === null) {
            $userId = 0;
        }

        array_walk($fsIdx, [$this, 'removeBasePath']);

        $fsIdx = array_flip($fsIdx);

        $notInDb  = array_diff_key($fsIdx, $dbIdx);
        $notInFs  = array_diff_key($dbIdx, $fsIdx);

        if (count($notInFs)) {

            /* @var $file mediaFile */
            $ids = array_map(function ($file) {
                return $file->getId();
            }, $notInFs);

            if (!$this->dbcon->delete($this->table, $this->dbcon->inQuery('id', $ids), array_values($ids)) ) {
                trigger_error('Unable to remove file index data for files with names: ' . implode(', ', array_keys($notInFs)));
            }

            \fpcm\model\reminders\reminders::getInstance()->removeByObject(mediaFile::class, $ids);
        }

        if (count($notInDb)) {

            $this->indexUserId = (int) $userId;
            $this->finfo = new \finfo();

            /* @var $file mediaFile */
            $res = array_map([$this, 'addToIndex'], array_keys($notInDb));

            $doThumbs = array_keys(array_intersect($notInDb, array_keys($res, true)));
            if (!is_array($doThumbs) || !count($doThumbs)) {
                return true;
            }

            array_walk($doThumbs, function (&$file) {
                $file = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, $file);
            });

            $this->createFilemanagerThumbs($doThumbs);
        }

        return true;

    }

    /**
     * Add single file to index
     * @param string $file
     * @return bool
     * @since 4.5
     */
    private function addToIndex(string $file) : bool
    {
        $mfObj = new \fpcm\model\files\mediaFile($file, false);
        $mfObj->setFiletime($mfObj->getModificationTime());
        $mfObj->setUserid($this->indexUserId);

        $mime = $this->finfo->file($mfObj->getFullpath(), FILEINFO_MIME_TYPE);

        if (!mediaFile::isValidType(\fpcm\model\abstracts\file::retrieveFileExtension($mfObj->getFullpath()), $mime )) {
            trigger_error(sprintf('Unsupported und not allowed filetype "%s" in "%s".', $mfObj->getMimetype(), $mfObj->getFilename()));
            return false;
        }

        $res = $mfObj->save();
        if (!$res && $this->dbcon->getLastQueryErrorCode() === \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY) {
            trigger_error("Unable to save image \"{$mfObj->getFilename()}\" to database, file is already indexed.");
            return false;
        }

        if (!$res) {
            trigger_error("Unable to save image \"{$mfObj->getFilename()}\" to database, due to unknows reason.");
            return false;
        }

        return true;
    }

    /**
     * Liefert Anzahl von Dateieinträgen in Datenbank zurück
     * @return int
     * @since 3.1
     */
    public function getDatabaseFileCount()
    {
        return $this->dbcon->count($this->table);
    }

    /**
     * Creates file manager thumbnails
     * @param array|null $folderFiles
     * @param bool|null $forceAll
     * @return bool
     */
    public function createFilemanagerThumbs(?array $folderFiles = null, ?bool $forceAll = null) : bool
    {
        $fn = thumbnailCreator::getFunctionName();

        if ($folderFiles === null) {
            $folderFiles = $this->getFolderList();
        }

        if (!count($folderFiles)) {
            return false;
        }

        $filesizeLimit = \fpcm\classes\baseconfig::memoryLimit(true) * 0.025;
        $folderFiles = array_filter($folderFiles, function ($folderFile) use ($filesizeLimit) {

            $ext = \fpcm\model\abstracts\file::retrieveFileExtension($folderFile);
            $mime = \fpcm\model\abstracts\file::retrieveRealType($folderFile);

            if (mediaFile::getMediaFileType($ext, $mime) === mediaFile::TYPE_AUDIOVIDEO) {
                return false;
            }

            if (filesize($folderFile) >= $filesizeLimit) {
                $msgPath = ops::removeBaseDir($folderFile);
                fpcmLogSystem("Skip filemanager thumbnail generation for {$msgPath} because of image dimension. You may reduce file size?");
                return false;
            }

            if ($ext == 'bmp' || substr($folderFile, -4) === '.bmp') {
                $msgPath = ops::removeBaseDir($folderFile);
                fpcmLogSystem("Skip filemanager thumbnail generation for {$msgPath}, \"".$ext."\" is no supported. You may use another image type?");
                return false;
            }

            return true;
        });

        if (!count($folderFiles)) {
            return false;
        }

        foreach ($folderFiles as $folderFile) {

            $imgPath = $folderFile;
            $this->removeBasePath($imgPath);

            $file = new mediaFile($imgPath);
            if ($file->isAudioVideo()) {
                continue;
            }

            if ($file->hasFileManageThumbnail() && !$forceAll) {
                $file = null;
                continue;
            }

            $proc = new thumbnailCreator($folderFile, $file->getFileManagerThumbnail());
            if (!$proc->{$fn}(\fpcm\classes\dirs::DATA_FMTMP)) {
                trigger_error('Error while creating filemanager thumbnail '.$file->getFileManagerThumbnail());
                continue;
            }

            if (!$file->hasFileManageThumbnail()) {
                trigger_error('Unable to create filemanager thumbnail: ' . $file->getFileManagerThumbnail());
            }

            $file = null;
            $proc = null;
        }

        return true;
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
     * @since 4.3
     */
    private function assignSearchParams(search $conditions, array &$where, array &$valueParams, string $combination)
    {
        if ($conditions->filename) {
            $where[] = "filename ".$this->dbcon->dbLike()." :filename OR alttext ".$this->dbcon->dbLike()." :alttext";
            $valueParams[':filename'] = '%' . $conditions->filename . '%';
            $valueParams[':alttext'] = $valueParams[':filename'];
        }

        if ($conditions->datefrom) {
            $where[] = "filetime >= :filetimef";
            $valueParams[':filetimef'] = $conditions->datefrom;
        }

        if ($conditions->dateto) {
            $where[] = "filetime <= :filetimet";
            $valueParams[':filetimet'] = $conditions->dateto;
        }

        if ($conditions->media_type) {
            $where[] = "media_type = :media_type";
            $valueParams[':media_type'] = (int) $conditions->media_type;
        }

        $combination = $conditions->combination ? $conditions->combination : 'AND';
        return true;
    }

    /**
     * Get count query string
     * @return \fpcm\model\dbal\selectParams
     * @since 5.1-dev
     */
    public function getCountQuery(): \fpcm\model\dbal\selectParams
    {
        return $this->getSearchQueryObj()->setItem('\'mediafiles\' as model, count(id) as count');
    }

    /**
     * Get query string
     * @return \fpcm\model\dbal\selectParams
     */
    public function getSearchQuery(): \fpcm\model\dbal\selectParams
    {
        return $this->getSearchQueryObj()->setItem(
            '\'mediafiles\' as model, ' .
            'filename as oid,' .
            $this->dbcon->concatString(['filename', '";"', 'alttext', '";"', 'filetime']).' as text, ' .
            $this->dbcon->concatString(['width', '":"', 'height', '":"', 'media_type']).' as meta'
        )->setFetchAll(true);
    }

    /**
     * Return link to element link
     * @return string
     * @since 5.1-dev
     */
    public function getElementLink(mixed $filename): string
    {
        $tmp = new mediaFile($filename, false);
        return $tmp->getFileUrl();
    }

    /**
     * Return link icon
     * @return \fpcm\view\helper\icon
     * @since 5.1-dev
     */
    public function getElementIcon(): \fpcm\view\helper\icon
    {
        return new \fpcm\view\helper\icon('photo-film');
    }

    /**
     * Returns selectParams object instance
     * @return \fpcm\model\dbal\selectParams
     * @since 5.1-dev
     */
    private function getSearchQueryObj(): \fpcm\model\dbal\selectParams
    {
        return (new \fpcm\model\dbal\selectParams($this->table))->setWhere('(filename LIKE :term OR alttext LIKE :term)');
    }

    /**
     * Prepare result text
     * @param string $text
     * @return string
     */
    public function prepareText(string $text): string
    {
        list($name, $alttext, $date) = explode(';', $text);

        $name = basename($name);

        return sprintf(
            '%s%s<br><span class="fpcm ui-font-small text-secondary">%s</span>',
            new \fpcm\view\helper\escape($alttext ? $alttext : $name),
            new \fpcm\view\helper\escape($alttext ? ' ('.$name.')' : ''),
            new \fpcm\view\helper\dateText($date)
        );
    }

}
