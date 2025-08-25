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
 * @property int $combinationDatefrom AND/OR for datefrom
 * @property int $combinationDateto AND/OR for dateto
 * @property int $combinationUserid AND/OR for userid
 * @property string $filename Dateiname
 * @property string $combination logische Verknüpfung AND/OR
 * @property array $limit Abfrage einschränken
 * @property array $orderby Array von Sortierungen in SQL-Syntax
 */
class search extends \fpcm\model\abstracts\searchWrapper {

    /**
     * Assign Filename field
     * @param \fpcm\model\dbal\queryAssignResult $qas
     * @return void
     */
    public function assignFilename() : void
    {
        if (!$this->filename) {
            return;
        }

        $this->queryAssignResult->setQueries("(filename {$this->getDB()->dbLike()} ? OR alttext {$this->getDB()->dbLike()} ? OR iptcstr {$this->getDB()->dbLike()} ?)");
        $this->queryAssignResult->setValues([
            '%' . $this->filename . '%',
            '%' . $this->filename . '%',
            '%' . $this->filename . '%'
        ]);

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

        $this->queryAssignResult->setQueries($this->getCondition('datefrom', 'filetime >= ?'));
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

        $this->queryAssignResult->setQueries($this->getCondition('dateto', 'filetime >= ?'));
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

        $this->queryAssignResult->setQueries( $this->getCondition( 'userid', $this->getDB()->inQuery('userid', $uids) ) );
        $this->queryAssignResult->setValues($uids);
    }


    /**
     * Prepare Filename value
     * @param \fpcm\model\dbal\queryAssignResult $qas
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
        if (!\fpcm\classes\tools::validateDateString($this->datefrom)) {
            return;
        }

        $this->datefrom = strtotime($this->datefrom);
    }

    /**
     * Prepare date to value
     * @return void
     */
    public function prepareDateto() : void
    {
        if (!\fpcm\classes\tools::validateDateString($this->dateto)) {
            return;
        }

        $this->datefrom = strtotime($this->dateto);
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
     * Prepare order string
     * @param string $field
     * @param string $order
     * @return void
     * @since 5.3.0-dev
     */
    public function prepareOrder(string $field, string $order) : void
    {
        if (!in_array($field, ['filename', 'alttext', 'filetime', 'userid'])) {
            $field = 'filetime';
        }

        if (!in_array($order, ['desc', 'asc'])) {
            $order = ' desc';
        }

        $this->orderby = [sprintf("%s %s", $field, $order)];
    }
}
