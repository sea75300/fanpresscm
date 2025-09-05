<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\articles;

/**
 * Article search wrapper object
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\articles
 * @since 3.5
 *
 * @property int $ids Artikel-IDs
 * @property int $user via Benutzer
 * @property int $changeuser via change user
 * @property int $category via Kategorie
 * @property int $datefrom seit Datum X.Y.Z
 * @property int $dateto bis Datum X.Y.Z
 * @property int $changefrom bis Datum X.Y.Z
 * @property int $changeto bis Datum X.Y.Z
 * @property int $pinned_until pinned until to date
 * @property int $relates_to related to article
 * @property int $combinationDatefrom AND/OR for datefrom
 * @property int $combinationDateto AND/OR for dateto
 * @property int $combinationUserid AND/OR for userid
 * @property int $combinationCategoryid AND/OR for categoryid
 * @property int $combinationPinned AND/OR for pinned
 * @property int $combinationPostponed AND/OR for postponed
 * @property int $combinationComments AND/OR for comments
 * @property int $combinationApproval AND/OR for approval
 * @property int $combinationDraft AND/OR for draft
 * @property int $combinationDeleted AND/OR for deleted
 * @property int $combinationArchived AND/OR for archived
 * @property string $title via Title-Inhalt
 * @property string $content via content-Inhalt
 * @property string $sources sources
 * @property string $combination logische Verknüpfung AND/OR
 * @property bool $postponed nur geplante Artikel
 * @property bool $archived nur archivierte Artikel
 * @property bool $pinned nur gepinnte Artikel
 * @property bool $comment Kommentare sind aktiv
 * @property bool $comments Kommentare sind aktiv
 * @property bool $deleted nur gelöschte Artikel
 * @property bool $draft nur Entwürfe
 * @property bool $drafts nur Entwürfe
 * @property bool $approval nur freizugebende Artikel
 * @property bool $metaOnly Artikel-Text nicht abrufen
 * @property bool $multipleQuery Multiple select queries
 * @property array $limit Abfrage einschränken
 * @property array $orderby Array von Sortierungen in SQL-Syntax
 * @property bool $modeArchive flag for archive articles
 * @property bool $modeActive flag for archive articles
 * @property bool $modeDeleted flag for deleted articles
 */
class search extends \fpcm\model\abstracts\searchWrapper {

    const TYPE_TITLE = 0;
    const TYPE_CONTENT = 1;
    const TYPE_COMBINED = 2;
    const TYPE_COMBINED_OR = 3;

    /**
     * Assign title field
     * @return void
     */
    public function assignTitle() : void
    {
        if (!$this->title) {
            return;
        }

        $this->queryAssignResult->setQueries("title {$this->getDB()->dbLike()} :title");
        $this->queryAssignResult->setValues([':title' => '%' . $this->title . '%']);
    }

    /**
     * Prepare title value
     * @return void
     */
    public function prepareTitle() : void
    {
        $this->title = trim($this->title);
    }

    /**
     * Assign content field
     * @return void
     */
    public function assignContent() : void
    {
        if (!$this->content) {
            return;
        }

        $this->queryAssignResult->setQueries("content {$this->getDB()->dbLike()} :content");
        $this->queryAssignResult->setValues([':content' => '%' . $this->content . '%']);
    }

    /**
     * Prepare title value
     * @return void
     */
    public function prepareContent() : void
    {
        $this->content = trim($this->content);
    }

    /**
     * Assign sources field
     * @return void
     */
    public function assignSources() : void
    {
        if (!$this->sources) {
            return;
        }

        $this->queryAssignResult->setQueries("sources {$this->getDB()->dbLike()} :sources");
        $this->queryAssignResult->setValues([':sources' => '%' . $this->sources . '%']);
    }

    /**
     * Prepare sources value
     * @return void
     */
    public function prepareSources() : void
    {
        $this->sources = trim($this->sources);
    }

    /**
     * Assign title field
     * @return void
     */
    public function assignCategory() : void
    {
        if ($this->category === null) {
            return;
        }

        $this->queryAssignResult->setQueries(
            sprintf(
                'id IN (select distinct article_id from %s where %s)',
                $this->getDB()->getTablePrefixed(\fpcm\classes\database::tableArticleCategories),
                'category_id IN (:category)'
            )
        );
        $this->queryAssignResult->setValues([':category' => $this->category]);
    }

    /**
     * Prepare title value
     * @return void
     */
    public function prepareCategory() : void
    {
        $this->category = (int) $this->category;
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

        $this->queryAssignResult->setQueries('createtime >= :createtime');
        $this->queryAssignResult->setValues([':createtime' => $this->datefrom]);
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

        $this->dateto = strtotime($this->dateto);
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

        $this->queryAssignResult->setQueries('createtime < :createtime');
        $this->queryAssignResult->setValues([':createtime' => $this->dateto]);
    }

    /**
     * Assign date from field
     * @return void
     */
    public function assignChangefrom() : void
    {
        if ($this->changefrom === null) {
            return;
        }

        $this->queryAssignResult->setQueries('createtime >= :changefrom');
        $this->queryAssignResult->setValues([':changefrom' => $this->changefrom]);
    }

    /**
     * Prepare date from value
     * @return void
     */
    public function prepareChangefrom() : void
    {
        if (!\fpcm\classes\tools::validateDateString($this->changefrom)) {
            return;
        }

        $this->changefrom = strtotime($this->changefrom);
    }

    /**
     * Prepare date to value
     * @return void
     */
    public function prepareChangeto() : void
    {
        if (!\fpcm\classes\tools::validateDateString($this->changeto)) {
            return;
        }

        $this->changeto = strtotime($this->changeto);
    }

    /**
     * Assign date to field
     * @return void
     */
    public function assignChangeto() : void
    {
        if ($this->changeto === null) {
            return;
        }

        $this->queryAssignResult->setQueries('createtime < :changeto');
        $this->queryAssignResult->setValues([':changeto' => $this->changeto]);
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

        $this->queryAssignResult->setQueries('deleted = :deleted');
        $this->queryAssignResult->setValues([':deleted' => $this->deleted]);
    }

    /**
     * Assign user id field
     * @return void
     */
    public function assignUser() : void
    {
        if ($this->user === null) {
            return;
        }

        $this->queryAssignResult->setQueries('createuser = :user') ;
        $this->queryAssignResult->setValues([':user' => $this->user]);
    }

    /**
     * Prepare user id value
     * @return void
     */
    public function prepareUser() : void
    {
        $this->user = (int) $this->user;
    }

    /**
     * Assign changeuser id field
     * @return void
     */
    public function assignChangeuser() : void
    {
        if ($this->changeuser === null) {
            return;
        }

        $this->queryAssignResult->setQueries('changeuser = :changeuser') ;
        $this->queryAssignResult->setValues([':changeuser' => $this->changeuser]);
    }

    /**
     * Prepare changeuser id value
     * @return void
     */
    public function prepareChangeuser() : void
    {
        $this->changeuser = (int) $this->changeuser;
    }

    /**
     * Assign pinned field
     * @return void
     */
    public function assignPinned() : void
    {
        if ($this->pinned === null) {
            return;
        }

        $this->queryAssignResult->setQueries('pinned = :pinned') ;
        $this->queryAssignResult->setValues([':pinned' => $this->pinned]);
    }

    /**
     * Prepare pinned value
     * @return void
     */
    public function preparePinned() : void
    {
        $this->pinned = (int) $this->pinned;
    }

    /**
     * Assign postponed field
     * @return void
     */
    public function assignPostponed() : void
    {
        if ($this->postponed === null) {
            return;
        }

        $this->queryAssignResult->setQueries('postponed = :postponed') ;
        $this->queryAssignResult->setValues([':postponed' => $this->postponed]);
    }

    /**
     * Prepare postponed value
     * @return void
     */
    public function preparePostponed() : void
    {
        $this->postponed = (int) $this->postponed;
    }

    /**
     * Assign comments field
     * @return void
     */
    public function assignComments() : void
    {
        if ($this->comments === null) {
            return;
        }

        $this->queryAssignResult->setQueries('comments = :comments') ;
        $this->queryAssignResult->setValues([':comments' => $this->comments]);
    }

    /**
     * Prepare comments value
     * @return void
     */
    public function prepareComments() : void
    {
        $this->comments = (int) $this->comments;
    }

    /**
     * Assign approval field
     * @return void
     */
    public function assignApproval() : void
    {
        if ($this->approval === null) {
            return;
        }
        
        $val = $this->approval > -1 ? $this->approval : 0;

        $this->queryAssignResult->setQueries('approval = :approval') ;
        $this->queryAssignResult->setValues([':approval' => $val]);
    }

    /**
     * Prepare approval value
     * @return void
     */
    public function prepareApproval() : void
    {
        $this->approval = (int) $this->approval;
    }

    /**
     * Assign draft field
     * @return void
     */
    public function assignDraft() : void
    {
        if ($this->draft === null) {
            return;
        }
        
        $val = $this->draft > -1 ? $this->draft : 0;

        $this->queryAssignResult->setQueries('draft = :draft') ;
        $this->queryAssignResult->setValues([':draft' => $val]);
    }

    /**
     * Prepare draft value
     * @return void
     */
    public function prepareDraft() : void
    {
        $this->draft = (int) $this->draft;
    }

    /**
     * Assign archived field
     * @return void
     */
    public function assignArchived() : void
    {
        if ($this->archived === null) {
            return;
        }
        
        $this->queryAssignResult->setQueries('archived = :archived') ;
        $this->queryAssignResult->setValues([':archived' => $this->archived]);
    }

    /**
     * Prepare pinned_until value
     * @return void
     */
    public function preparePinned_until() : void
    {
        $this->pinned_until = (int) $this->pinned_until;
    }

    /**
     * Assign pinned_until field
     * @return void
     */
    public function assignPinned_until() : void
    {
        if ($this->pinned_until === null) {
            return;
        }

        $this->queryAssignResult->setQueries('pinned_until = :pinned_until') ;
        $this->queryAssignResult->setValues([':pinned_until' => $this->pinned_until]);
    }

    /**
     * Prepare pinned_until value
     * @return void
     */
    public function prepareRelates_to() : void
    {
        $this->relates_to = (int) $this->relates_to;
    }

    /**
     * Assign relates_to field
     * @return void
     */
    public function assignRelates_to() : void
    {
        if ($this->relates_to === null) {
            return;
        }

        $this->queryAssignResult->setQueries('relates_to = :relates_to') ;
        $this->queryAssignResult->setValues([':relates_to' => $this->relates_to]);
    }

    /**
     * Prepare user id value
     * @return void
     */
    public function prepareArchived() : void
    {
        $this->archived = (int) $this->archived;
    }
    
    /**
     * Returns field whitelist for ordering
     * @return array
     * @since 5.3.0-dev
     */
    public function getOrderFields() : array
    {
        return  [
            'title',
            'content',
            'createuser',
            'createtime',
            'changetime',
            'changeuser',
            'draft',
            'archived',
            'pinned',
            'pinned_until',
            'postponed',
            'approval',
            'comments'
        ];
    }
    
    public function getDefaultOrder() : string
    {
        return 'createtime';
    }

}
