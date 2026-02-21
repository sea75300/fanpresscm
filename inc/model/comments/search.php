<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\comments;

/**
 * Comment search wrapper object
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\comments
 * @since 3.5
 *
 * @property int $articleid Artikel-ID
 * @property int $datefrom seit Datum X.Y.Z
 * @property int $dateto bis Datum X.Y.Z
 * @property int $combinationDatefrom AND/OR for datefrom
 * @property int $combinationDateto AND/OR for dateto
 * @property int $combinationArticleid AND/OR for articleid
 * @property int $combinationPrivate AND/OR for private
 * @property int $combinationApproved AND/OR for approved
 * @property int $combinationSpam AND/OR for spam
 * @property int $combinationDeleted AND/OR for deleted
 * @property string $name via name
 * @property string $email via e-mail
 * @property string $website via website
 * @property string $text via comment text
 * @property string $combination logische Verknüpfung AND/OR
 * @property string $ipaddress IP-Adresse
 * @property bool $searchtype Suchtyp
 * @property bool $spam nur als Spam markierte Kommentare
 * @property bool $private nur als Privat markierte Kommentare
 * @property bool $approved nur als Freigegeben markierte Kommentare
 * @property bool $unapproved nur als nicht Freigegeben markierte Kommentare
 * @property bool $deleted nur als nicht Freigegeben markierte Kommentare
 * @property bool $metaOnly Kommentar-Text nicht abrufen
 * @property array $limit Abfrage einschränken
 * @property array $orderby Array von Sortierungen in SQL-Syntax
 * @property bool $modeDeleted flag for deleted articles
 */
class search extends \fpcm\model\abstracts\searchWrapper {

    const TYPE_ALL = 0;
    const TYPE_TEXT = 1;
    const TYPE_NAMEMAILWEB = 2;
    const TYPE_ALLOR = 3;
    const TYPE_NAMEMAILWEB_OR = 4;

    /**
     * Liefert Daten zurück, die über Eigenschaften erzeugt wurden
     * @return array
     */
    public function getData()
    {
        if (!isset($this->data['searchtype'])) {
            $this->data['searchtype'] = 0;
        }

        return $this->data;
    }

    /**
     * Assign name field
     * @return void
     */
    public function assignName() : void
    {
        if (!$this->name) {
            return;
        }

        $this->queryAssignResult->setQueries("name {$this->getDB()->dbLike()} ?");
        $this->queryAssignResult->setValues(['%' . $this->name . '%']);

    }

    /**
     * Assign e-mail field
     * @return void
     */
    public function assignEmail() : void
    {
        if (!$this->email) {
            return;
        }

        $this->queryAssignResult->setQueries("email {$this->getDB()->dbLike()} ?");
        $this->queryAssignResult->setValues(['%' . $this->email . '%']);

    }

    /**
     * Assign website field
     * @return void
     */
    public function assignWebsite() : void
    {
        if (!$this->website) {
            return;
        }

        $this->queryAssignResult->setQueries("website {$this->getDB()->dbLike()} ?");
        $this->queryAssignResult->setValues(['%' . $this->website . '%']);

    }

    /**
     * Assign text field
     * @return void
     */
    public function assignText() : void
    {
        if (!$this->text) {
            return;
        }

        $this->queryAssignResult->setQueries("text {$this->getDB()->dbLike()} ?");
        $this->queryAssignResult->setValues(['%' . $this->text . '%']);

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

        $this->queryAssignResult->setQueries('createtime >= ?');
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

        $this->queryAssignResult->setQueries('createtime < ?');
        $this->queryAssignResult->setValues([$this->dateto]);
    }

    /**
     * Assign spam field
     * @return void
     */
    public function assignSpam() : void
    {
        if ($this->spam < 0) {
            return;
        }

        $this->queryAssignResult->setQueries('spammer = ?');
        $this->queryAssignResult->setValues([$this->spam]);
    }

    /**
     * Assign spam field
     * @return void
     */
    public function assignApproved() : void
    {
        if ($this->approved < 0) {
            return;
        }

        $this->queryAssignResult->setQueries($this->getCondition('approved', 'approved = ?'));
        $this->queryAssignResult->setValues([$this->approved]);
    }

    /**
     * Assign spam field
     * @return void
     */
    public function assignPrivate() : void
    {
        if ($this->private < 0) {
            return;
        }

        $this->queryAssignResult->setQueries('private = ?');
        $this->queryAssignResult->setValues([$this->private]);
    }

    /**
     * Assign spam field
     * @return void
     */
    public function assignArticleid() : void
    {
        if ($this->articleid < 0) {
            return;
        }

        $this->queryAssignResult->setQueries('articleid = ?');
        $this->queryAssignResult->setValues([$this->articleid]);
    }

    /**
     * Assign spam field
     * @return void
     */
    public function assignDeleted() : void
    {
        if ($this->deleted < 0) {
            return;
        }

        $this->queryAssignResult->setQueries('deleted = ?');
        $this->queryAssignResult->setValues([$this->deleted]);
    }

    /**
     * Assign spam field
     * @return void
     */
    public function assignIpaddress() : void
    {
        if (!$this->ipaddress) {
            return;
        }

        $this->queryAssignResult->setQueries('ipaddress = ?');
        $this->queryAssignResult->setValues([$this->ipaddress]);
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
     * Prepare spam value
     * @return void
     */
    public function prepareSpam() : void
    {
        $this->spam = (int) $this->spam;
    }

    /**
     * Prepare articleid value
     * @return void
     */
    public function prepareArticleid() : void
    {
        $this->articleid = (int) $this->articleid;
    }

    /**
     * Prepare approved value
     * @return void
     */
    public function prepareApproved() : void
    {
        $this->approved = (int) $this->approved;
    }

    /**
     * Prepare private value
     * @return void
     */
    public function preparePrivate() : void
    {
        $this->private = (int) $this->private;
    }

    /**
     * Prepare name value
     * @return void
     */
    public function prepareName() : void
    {
        $this->name = trim($this->name);
    }

    /**
     * Prepare email value
     * @return void
     */
    public function prepareEmail() : void
    {
        $this->email = trim($this->email);
    }

    /**
     * Prepare website value
     * @return void
     */
    public function prepareWebsite() : void
    {
        $this->website = trim($this->website);
    }

    /**
     * Prepare website value
     * @return void
     */
    public function prepareText() : void
    {
        $this->text = trim($this->text);
    }

    /**
     * Prepare ipaddress value
     * @return void
     */
    public function prepareIpaddress() : void
    {
        $this->ipaddress = filter_var($this->ipaddress, FILTER_VALIDATE_IP, [ 'flags' => FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 ]);
    }

    /**
     * Returns field whitelist for ordering
     * @return array
     * @since 5.3.0-dev
     */
    public function getOrderFields() : array
    {
        return  ['email', 'website', 'text', 'createtime', 'spam', 'private', 'approved', 'ipaddress'];
    }

    /**
     * Retrun deafult order field
     * @return string
     * @since 5.3.0-dev
     */
    public function getDefaultOrder() : string
    {
        return 'createtime';
    }

}
