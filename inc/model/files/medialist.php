<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Image list object
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class medialist
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
     * Select callback
     * @var callable
     */
    private $callback;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();

        $this->table = \fpcm\classes\database::tableMedia;
        $this->basepath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MEDIA, '/');
        $this->exts = media::$allowedExts;

        $this->callback = function($ds, &$res) {

            $obj = new media(
                initDB: false
            );

            $obj->createFromDbObject($ds);
            $res[$ds->filename] = $obj;
        };
    }

    /**
     * Return list of files in file system
     * @return array
     */
    public function getFolderList()
    {
        $res1 = parent::getFolderList();
        $this->pathprefix = '????-??'.DIRECTORY_SEPARATOR;
        $res2 = parent::getFolderList();

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

        $obj = (new \fpcm\model\dbal\selectParams($this->table))
            ->setWhere($where)
            ->setFetchAll(true)
            ->setCallback($this->callback);

        return $this->dbcon->selectFetch($obj);
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

        $obj = (new \fpcm\model\dbal\selectParams($this->table))
            ->setWhere($where)
            ->setParams($valueParams)
            ->setFetchAll(true)
            ->setCallback($this->callback);

        if ($this->dbcon->getLastQueryErrorCode() === \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX) {
            return \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX;
        }

        return $this->dbcon->selectFetch($obj);
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

            /* @var $file image */
            $ids = array_map(function ($file) {
                return $file->getId();
            }, $notInFs);

            if (!$this->dbcon->delete($this->table, $this->dbcon->inQuery('id', $ids), array_values($ids)) ) {
                trigger_error('Unable to remove file index data for files with names: ' . implode(', ', array_keys($notInFs)));
            }

            \fpcm\model\reminders\reminders::getInstance()->removeByObject(media::class, $ids);
        }

        if (count($notInDb)) {

            $this->indexUserId = (int) $userId;
            $this->finfo = new \finfo();

            array_map([$this, 'addToIndex'], array_keys($notInDb));
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
        $mediaObj = new \fpcm\model\files\media($file, false);
        $mediaObj->setFiletime($mediaObj->getModificationTime());
        $mediaObj->setUserid($this->indexUserId);

        $mime = $this->finfo->file($mediaObj->getFullpath(), FILEINFO_MIME_TYPE);

        if (!media::isValidType(\fpcm\model\abstracts\file::retrieveFileExtension($mediaObj->getFullpath()), $mime )) {
            trigger_error("Unsupported filetype \"{$mime}\" in \"{$mediaObj->getFullpath()}\"");
            return false;
        }

        if (!in_array($mediaObj->getMimetype(), media::$allowedTypes) || !in_array(strtolower($mediaObj->getExtension()), media::$allowedExts)) {
            trigger_error(sprintf('Filetype "%s" not allowed in "%s".', $mediaObj->getMimetype(), $mediaObj->getFilename()));
            return false;
        }

        $res = $mediaObj->save();
        if (!$res && $this->dbcon->getLastQueryErrorCode() === \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY) {
            trigger_error("Unable to save image \"{$mediaObj->getFilename()}\" to database, file is already indexed.");
            return false;
        }

        if (!$res) {
            trigger_error("Unable to save image \"{$mediaObj->getFilename()}\" to database, due to unknows reason.");
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
            $where[] = "filename ".$this->dbcon->dbLike()." :filename";
            $valueParams[':filename'] = '%' . $conditions->filename . '%';
        }

        if ($conditions->datefrom) {
            $where[] = "filetime >= :filetimef";
            $valueParams[':filetimef'] = $conditions->datefrom;
        }

        if ($conditions->dateto) {
            $where[] = "filetime <= :filetimet";
            $valueParams[':filetimet'] = $conditions->dateto;
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
        return $this->getSearchQueryObj()->setItem('\'media\' as model, count(id) as count');
    }

    /**
     * Get query string
     * @return \fpcm\model\dbal\selectParams
     */
    public function getSearchQuery(): \fpcm\model\dbal\selectParams
    {
        return $this->getSearchQueryObj()->setItem(
            '\'media\' as model, ' .
            'filename as oid,' .
            $this->dbcon->concatString(['filename', '";"', 'filetime']).' as text,  \'\' as meta'
        )->setFetchAll(true);
    }

    /**
     * Return link to element link
     * @return string
     * @since 5.1-dev
     */
    public function getElementLink(mixed $filename): string
    {
        $tmp = new media($filename, false);
        return $tmp->getFileUrl();
    }

    /**
     * Return link icon
     * @return \fpcm\view\helper\icon
     * @since 5.1-dev
     */
    public function getElementIcon(): \fpcm\view\helper\icon
    {
        return new \fpcm\view\helper\icon('film');
    }

    /**
     * Returns selectParams object instance
     * @return \fpcm\model\dbal\selectParams
     * @since 5.1-dev
     */
    private function getSearchQueryObj(): \fpcm\model\dbal\selectParams
    {
        return (new \fpcm\model\dbal\selectParams($this->table))->setWhere('(filename LIKE :term)');
    }

    /**
     * Prepare result text
     * @param string $text
     * @return string
     */
    public function prepareText(string $text): string
    {
        list($name, $date) = explode(';', $text);

        return sprintf(
            '%s<br><span class="fpcm ui-font-small text-secondary">%s</span>',
            new \fpcm\view\helper\escape(basename($name)),
            new \fpcm\view\helper\dateText($date)
        );
    }

}
