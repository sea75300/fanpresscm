<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Files search wrapper object
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\articles
 * @since 3.6.3
 *
 * @property int $datefrom seit Datum X.Y.Z
 * @property int $dateto bis Datum X.Y.Z
 * @property int $userid User-ID or 0
 * @property int $mediatype Media type (-1 all, 0 image, 1 audio/video)
 * @property int $combinationDatefrom AND/OR for datefrom
 * @property int $combinationDateto AND/OR for dateto
 * @property int $combinationUserid AND/OR for userid
 * @property string $filename Filename
 * @property string $alttext Alternate text
 * @property string $credits credits text
 * @property string $combination logische Verknüpfung AND/OR
 * @property array $limit Abfrage einschränken
 * @property array $orderby Array von Sortierungen in SQL-Syntax
 */
class search extends \fpcm\model\abstracts\searchWrapper {

    /**
     * Assign Filename field
     * @return void
     */
    public function assignFilename() : void
    {
        if (!$this->filename) {
            return;
        }

        $this->queryAssignResult->setQueries("filename {$this->getDB()->dbLike()} ?");
        $this->queryAssignResult->setValues(['%' . $this->filename . '%']);

    }

    /**
     * Assign Filename field
     * @return void
     */
    public function assignAlttext() : void
    {
        if (!$this->alttext) {
            return;
        }

        $this->queryAssignResult->setQueries("alttext {$this->getDB()->dbLike()} ?");
        $this->queryAssignResult->setValues(['%' . $this->alttext . '%']);

    }

    /**
     * Assign Filename field
     * @return void
     */
    public function assignCredits() : void
    {
        if (!$this->credits) {
            return;
        }

        $this->queryAssignResult->setQueries("iptcstr {$this->getDB()->dbLike()} ?");
        $this->queryAssignResult->setValues(['%' . $this->credits . '%']);

    }

    /**
     * Assign date from field
     * @return void
     */
    public function assignDatefrom() : void
    {
        if ($this->datefrom === null) {
            return;
        }

        $this->queryAssignResult->setQueries('filetime >= ?');
        $this->queryAssignResult->setValues([$this->datefrom]);
    }

    /**
     * Assign date to field
     * @return void
     */
    public function assignDateto() : void
    {
        if ($this->dateto === null) {
            return;
        }

        $this->queryAssignResult->setQueries('filetime < ?');
        $this->queryAssignResult->setValues([$this->dateto]);
    }

    /**
     * Assign user id field
     * @return void
     */
    public function assignUserid() : void
    {
        if ($this->userid < 0) {
            return;
        }

        $uids = [0, $this->userid];

        $this->queryAssignResult->setQueries($this->getDB()->inQuery('userid', $uids)) ;
        $this->queryAssignResult->setValues($uids);
    }

    /**
     * Assign user id field
     * @return void
     */
    public function assignMediatype() : void
    {
        if ($this->mediatype < 0) {
            return;
        }

        $this->queryAssignResult->setQueries('media_type = ?');
        $this->queryAssignResult->setValues([$this->mediatype]);
    }

    /**
     * Prepare Filename value
     * @return void
     */
    public function prepareFilename() : void
    {
        $this->filename = trim($this->filename);
    }

    /**
     * Prepare date from value
     * @return void
     */
    public function prepareDatefrom() : void
    {
        if (!\fpcm\classes\dateTimeHelper::validateDateString($this->datefrom)) {
            return;
        }

        $this->datefrom = \fpcm\classes\dateTimeHelper::getTimestampFromString($this->datefrom);
    }

    /**
     * Prepare date to value
     * @return void
     */
    public function prepareDateto() : void
    {
        if (!\fpcm\classes\dateTimeHelper::validateDateString($this->dateto)) {
            return;
        }

        $this->dateto = \fpcm\classes\dateTimeHelper::getTimestampFromString($this->dateto);
    }

    /**
     * Prepare user id value
     * @return void
     */
    public function prepareUserid() : void
    {
        $this->userid = (int) $this->userid;
    }

    /**
     * Prepare user id value
     * @return void
     */
    public function prepareMediatype() : void
    {
        $this->mediatype = (int) $this->mediatype;
    }

    /**
     * Returns field whitelist for ordering
     * @return array
     * @since 5.3.0-dev
     */
    public function getOrderFields() : array
    {
        return ['filename', 'alttext', 'filetime', 'userid', 'media_type'];
    }

    /**
     * Retrun deafult order field
     * @return string
     * @since 5.3.0-dev
     */
    public function getDefaultOrder() : string
    {
        return 'filetime';
    }
}
