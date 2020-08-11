<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\articles;

/**
 * Artikel object
 * 
 * @package fpcm\model\articles
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class article extends \fpcm\model\abstracts\dataset {

    use \fpcm\model\traits\autoTable;
    
    /**
     * Cache-Name für einzelnen Artikel
     * @since FPCM 3.4
     */
    const CACHE_ARTICLE_SINGLE = 'articlesingle';

    /**
     * Cache-Module-Name
     * @since FPCM 3.4
     */
    const CACHE_ARTICLE_MODULE = 'articles';

    /**
     * Sources auto-complete fileoption name
     * @since FPCM 4.1
     */
    const SOURCES_AUTOCOMPLETE = 'articles/sources';

    /**
     * News-Titel
     * @var string
     */
    protected $title = '';

    /**
     * News-Text
     * @var string
     */
    protected $content = '';

    /**
     * Kategorien
     * @var array
     */
    protected $categories = [];

    /**
     * Status: Entwurf
     * @var int
     */
    protected $draft = 0;

    /**
     * Status: archiviert
     * @var int
     */
    protected $archived = 0;

    /**
     * Status: gepinnt
     * @var int
     */
    protected $pinned = 0;

    /**
     * Status: automatisch freischalten
     * @var int
     */
    protected $postponed = 0;

    /**
     * Status: gelöscht
     * @var int
     */
    protected $deleted = 0;

    /**
     * Kommentare aktiv
     * @var int
     */
    protected $comments = 1;

    /**
     * Artikel muss freigegeben werden
     * @var int
     */
    protected $approval = 0;

    /**
     * Pfad zum Artikel-Bild
     * @var string
     * @since FPCM 3.1.0
     */
    protected $imagepath = '';

    /**
     * Veröffentlichungszeit
     * @var int
     */
    protected $createtime = 0;

    /**
     * Author
     * @var int
     */
    protected $createuser = 0;

    /**
     * Zeitpunkt der letzten Änderung
     * @var int
     */
    protected $changetime = 0;

    /**
     * Benutzer der letzten Änderung
     * @var int
     */
    protected $changeuser = 0;

    /**
     * Artikel-Quellen
     * @var string
     * @since FPCM 3.4
     */
    protected $sources = '';

    /**
     * Artikel-Quellen
     * @var int
     * @since FPCM 3.5
     */
    protected $inedit = '';

    /**
     * richtiges Löschen erzwingen
     * @var int
     */
    protected $forceDelete = 0;

    /**
     * Auszuschließende Elemente beim in save/update
     * @var array
     */
    protected $dbExcludes = [
        'defaultPermissions',
        'forceDelete',
        'editPermission',
        'tweetOverride',
        'tweetCreate',
        'crypt'
    ];

    /**
     * Action-String für edit-Action
     * @var string
     */
    protected $editAction = 'articles/edit&id=';

    /**
     * Wortsperren-Liste
     * @var \fpcm\model\wordban\items
     * @since FPCM 3.2.0
     */
    protected $wordbanList;

    /**
     * Crypto-Objekt
     * @var \fpcm\classes\crypt
     * @since FPCM 3.6
     */
    protected $crypt;

    /**
     * Status ob Artikel bearbeitet werden kann
     * @var bool
     * @since FPCM 3.3
     */
    protected $editPermission = true;

    /**
     * Text für überschriebenes Tweet-Template
     * @var string
     * @since FPCM 3.3
     */
    protected $tweetOverride = false;

    /**
     * TWeet Erstellung aktivieren
     * @var bool
     * @since FPCM 3.5.2
     */
    protected $tweetCreate = null;

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct($id = null)
    {
        $this->wordbanList = new \fpcm\model\wordban\items();
        $this->crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');

        parent::__construct($id);
    }

    /**
     * Gibt Artikel- zurück
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Gibt Artikel-Inhalt zurück
     * @return strig
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Gibt Artikel-Kategorie-IDs zurück
     * @return array
     */
    public function getCategories()
    {
        if (!is_array($this->categories)) {
            $this->categories = json_decode($this->categories, true);
        }

        return $this->categories;
    }

    /**
     * Gibt Artikel-Entwurf-Status zurück
     * @return bool
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Gibt Artikel-archiviert-Status zurück
     * @return bool
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * Gibt Artikel-gepinnt-Status zurück
     * @return bool
     */
    public function getPinned()
    {
        return $this->pinned;
    }

    /**
     * Gibt Artikel-geplant-Status zurück
     * @return bool
     */
    public function getPostponed()
    {
        return $this->postponed;
    }

    /**
     * Gibt Artikel-gelöscht-Status zurück
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Gibt Artikel-Erstellungszeitpunkt zurück
     * @return int
     */
    public function getCreatetime()
    {
        return $this->createtime;
    }

    /**
     * Gibt Artikel-Author-ID zurück
     * @return int
     */
    public function getCreateuser()
    {
        return $this->createuser;
    }

    /**
     * Gibt Zeitpunkt der letzten Änderung zurück
     * @return int
     */
    public function getChangetime()
    {
        return $this->changetime;
    }

    /**
     * Gibt Benutzer-ID zurück, von dem letzte Änderung durchgeführt wurde
     * @return int
     */
    public function getChangeuser()
    {
        return $this->changeuser;
    }

    /**
     * Gibt Artikel-Kommentare aktiv-Status zurück
     * @return bool
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Gibt Artikel-muss freigeschaltet werden-Status zurück
     * @return bool
     */
    public function getApproval()
    {
        return $this->approval;
    }

    /**
     * Gibt Pfad zum Artikel-Bild zurück
     * @return string
     * @since FPCM 3.1.0
     */
    public function getImagepath()
    {
        return $this->imagepath;
    }

    /**
     * Liefert Status, ob Artikel bearbeitet werden kann zurück
     * @return bool
     * @since FPCM 3.3
     */
    public function getEditPermission()
    {
        return $this->editPermission;
    }

    /**
     * Gibt Artikel-Quellen zurück
     * @return bool
     * @since FPCM 3.4
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Tweet-Erstellung aktiv?
     * @return bool
     * @since FPCM 3.5.2
     */
    function tweetCreationEnabled()
    {
        return (bool) $this->tweetCreate;
    }

    /**
     * Ttiel setzen
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = strip_tags($title, '<b><strong><i><em><u><span><br>');
    }

    /**
     * Inhalt setzen
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Kategorien setzen
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = json_encode($categories);
    }

    /**
     * Entwurf-Status setzen
     * @param bool $draft
     */
    public function setDraft($draft)
    {
        $this->draft = (int) $draft;
    }

    /**
     * archiviert Status setzen
     * @param bool $archived
     */
    public function setArchived($archived)
    {
        $this->archived = (int) $archived;
    }

    /**
     * gepinnt Status
     * @param bool $pinned
     */
    public function setPinned($pinned)
    {
        $this->pinned = (int) $pinned;
    }

    /**
     * Geplant-Status setzen
     * @param bool $postponed
     */
    public function setPostponed($postponed)
    {
        $this->postponed = (int) $postponed;
    }

    /**
     * Gelöscht Status setzen
     * @param bool $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = (int) $deleted;
    }

    /**
     * Zeitpunk der Erzeugung setzen
     * @param int $createtime
     */
    public function setCreatetime($createtime)
    {
        $this->createtime = (int) $createtime;
    }

    /**
     * Benutzer der Erzeugung setzen
     * @param int $createuser
     */
    public function setCreateuser($createuser)
    {
        $this->createuser = (int) $createuser;
    }

    /**
     * Zeitpunk der letzten Änderung setzen
     * @param int $changetime
     */
    public function setChangetime($changetime)
    {
        $this->changetime = (int) $changetime;
    }

    /**
     * Benutzer der letzten Änderung setzen
     * @param int $changeuser
     */
    public function setChangeuser($changeuser)
    {
        $this->changeuser = (int) $changeuser;
    }

    /**
     * Kommentar-aktiv-Status setzen
     * @param bool $comments
     */
    public function setComments($comments)
    {
        $this->comments = (int) $comments;
    }

    /**
     * Freigabe-Status setzen
     * @param bool $approval
     */
    public function setApproval($approval)
    {
        $this->approval = (int) $approval;
    }

    /**
     * Setzt Pfad zum Artikel-Bild
     * @param string $imagepath
     * @since FPCM 3.1.0
     */
    public function setImagepath($imagepath)
    {
        $this->imagepath = $imagepath;
    }

    /**
     * Setzt Artikel-Quellen-Daten
     * @param string $sources
     * @return bool
     * @since FPCM 3.4
     */
    public function setSources($sources)
    {
        return $this->sources = strip_tags($sources);
    }

    /**
     * Setzt Status, ob Artikel bearbeitet werden kann
     * @param bool $editPermission
     * @since FPCM 3.3
     */
    public function setEditPermission($editPermission)
    {
        $this->editPermission = $editPermission;
    }

    /**
     * Artikel vollständig löschen erzwingen
     * @param bool $forceDelete
     */
    public function setForceDelete($forceDelete)
    {
        $this->forceDelete = $forceDelete;
    }

    /**
     * Text für überschriebenes Tweet-Template zurückgeben
     * @return string
     * @since FPCM 3.3
     */
    function getTweetOverride()
    {
        return $this->tweetOverride;
    }

    /**
     * Text für überschriebenes Tweet-Template setzen
     * @param string $tweetOverride
     * @since FPCM 3.3
     */
    function setTweetOverride($tweetOverride)
    {
        $this->tweetOverride = $tweetOverride;
    }

    /**
     * Tweet-Erstellung aktivieren
     * @param bool $tweetCreate
     * @since FPCM 3.5.2
     */
    function enableTweetCreation($tweetCreate)
    {
        $this->tweetCreate = (bool) $tweetCreate;
    }

    /**
     * schönen URL-Pfad zurückgeben
     * @return string
     */
    public function getArticleNicePath()
    {
        return rawurlencode($this->id . '-' . str_replace(array(' ', '---'), '-', strtolower($this->title)));
    }
    
    /**
     * Return frontend article link
     * @param string $params
     * @return string
     */
    public function getElementLink($params = '')
    {
        $idParam = ($this->config->articles_link_urlrewrite ? $this->getArticleNicePath() : $this->getId());

        if (!$this->config->system_mode) {
            return \fpcm\classes\tools::getFullControllerLink('fpcm/article', [
                'id' => $idParam
            ]);
        }

        return $this->config->system_url . '?module=fpcm/article&id=' . $idParam.$params;
    }

    /**
     * Link zum Löschen des Artikel-Caches
     * @return string
     * @since FPCM 3.6
     */
    public function getArticleCacheParams()
    {
        return ['cache' => urlencode($this->crypt->encrypt('article')), 'objid' => $this->id];
    }

    /**
     * Erzeugt Short-Link zum Artikel zurück
     * @return string
     */
    public function getArticleShortLink()
    {
        $elLink = $this->getElementLink();
        $elLinkEncode = urlencode($elLink);
        
        $external = !\fpcm\classes\baseconfig::canConnect() || (defined('FPCM_ARTICLE_DISABLE_SHORTLINKS') && FPCM_ARTICLE_DISABLE_SHORTLINKS) ? false : true;
        
        $return = $this->events->trigger('article\getShortLink', [
            'url' => $elLink,
            'encoded' => $elLinkEncode,
            'active' => $external,
            'default' => true,
        ]);

        if (!$external) {
            return $elLink;
        }

        if (!$return['default']) {
            return $return['url'];
        }

        $shortened = file_get_contents('http://is.gd/create.php?format=simple&url=' . $elLinkEncode, false);
        if($shortened === false) {
            trigger_error('Unable to fetch short link data for '.$elLink.' , return with default article link.');
            return $elLink;
        }

        return $shortened;
    }

    /**
     * Liefert <img>-Tag für Artikel-Image zurück
     * @return string
     * @since FPCM 3.1.0
     */
    public function getArticleImage()
    {
        if (!trim($this->imagepath)) {
            return '';
        }

        return "<img class=\"fpcm-pub-article-image\" src=\"{$this->imagepath}\" alt=\"{$this->title}\" title=\"{$this->title}\">";
    }

    /**
     * Löscht News in der Datenbank
     * @return bool
     */
    public function delete()
    {
        $this->cleanupCaches();

        if (!$this->forceDelete) {
            $this->deleted = 1;

            return $this->update();
        }

        $this->deleteRevisions();

        $commentList = new \fpcm\model\comments\commentList();
        $commentList->deleteCommentsByArticle($this->id);

        $return = parent::delete();
        $this->deleted = 1;

        return $return;
    }

    /**
     * Artikel-Daten für Revision vorbereiten
     * @since FPCM 3.4
     */
    public function prepareRevision()
    {
        $this->data['preparedRevision'] = $this->getPreparedSaveParams();
    }

    /**
     * Erzeugt eine Revision des Artikels
     * @param int $timer
     * @return bool
     */
    public function createRevision($timer = 0)
    {
        $content = $this->getPreparedSaveParams();
        $content = $this->events->trigger('revision\create', $content);

        if (!$timer) {
            $timer = $this->changetime;
        }

        $revision = new revision();
        $revision->setArticleId($this->id);
        $revision->setRevisionIdx($timer);
        $revision->setContent($content);

        $newHash = $revision->createHashSum();
        $revision->setHashsum($newHash);

        if (isset($this->data['preparedRevision']) &&
                is_array($this->data['preparedRevision']) &&
                $revision->createHashSum($this->data['preparedRevision']) === $newHash) {
            return true;
        }

        if (!$revision->save()) {
            trigger_error('Unable to create revision for article ' . $this->id);
            return false;
        }

        return true;
    }

    /**
     * Gib Revisionen des Artikels zurück
     * @param bool $full Soll die Revision ganz zurückgegebn werden oder nur Titel
     * @return array
     */
    public function getRevisions($full = false)
    {
        $result = $this->dbcon->select(
                \fpcm\classes\database::tableRevisions, 'article_id, revision_idx, content', 'article_id = ? ' . $this->dbcon->orderBy(array('revision_idx DESC')), array($this->id)
        );

        $revisionSets = $this->dbcon->fetch($result, true);
        if (!is_array($revisionSets) || !count($revisionSets)) {
            return [];
        }
        $revisionFiles = $this->events->trigger('revision\getBefore', $revisionSets);

        $revisions = [];
        foreach ($revisionSets as $revisionSet) {

            $revisionObj = new revision($this->id);
            $revisionObj->createFromDbObject($revisionSet);

            $revData = $revisionObj->getContent();
            $revTime = $revisionObj->getRevisionIdx();

            if (!is_array($revData) || !$revTime) {
                continue;
            }

            $revisions[$revTime] = $full ? $revData : $revData['title'];
        }

        $revisions = $this->events->trigger('revision\getAfter', array('full' => $full, 'revisions' => $revisions))['revisions'];

        return $revisions;
    }

    /**
     * Anzahl Revisionen des Artikels
     * @return array
     * @since FPCM 3.6
     */
    public function getRevisionsCount()
    {
        return $this->dbcon->count(\fpcm\classes\database::tableRevisions, 'id', 'article_id = ? ', [$this->id]);
    }

    /**
     * Lädt Revision eines Artikels
     * @param int $revisionTime Revisions-ID
     * @return bool
     */
    public function getRevision($revisionTime)
    {
        $revision = new revision($this->id, $revisionTime);
        if (!$revision->exists()) {
            return false;
        }

        $revision = $this->events->trigger('revision\get', $revision);
        foreach ($revision->getContent() as $key => $value) {
            $this->$key = $value;
        }

        return true;
    }

    /**
     * Stellt Revision eines Artikels wieder her
     * @param int $revisionTime Revisions-ID
     * @return bool
     */
    public function restoreRevision($revisionTime)
    {
        if (!$this->createRevision(time())) {
            return false;
        }

        $this->getRevision($revisionTime);
        return $this->update();
    }

    /**
     * Löscht Revisionen
     * @param array $revisionList Liste von Revisions-IDs
     * @return bool
     */
    public function deleteRevisions(array $revisionList = [])
    {
        if (!count($revisionList)) {
            return $this->dbcon->delete(\fpcm\classes\database::tableRevisions, 'article_id = ?', array($this->id));
        }

        return $this->dbcon->delete(\fpcm\classes\database::tableRevisions, 'article_id = ? AND revision_idx IN (' . implode(',', array_map('intval', $revisionList)) . ')', array($this->id));
    }

    /**
     * Erzeugt einen Tweet bei Twitter, wenn Verbindung aktiv und Events ausgewählt
     * @param bool $force
     * @return bool
     */
    public function createTweet($force = false)
    {
        if (!\fpcm\classes\baseconfig::canConnect() || (!$this->config->twitter_events['create'] && !$this->config->twitter_events['update'] && !$force)) {
            return false;
        }

        if (!$force && (!$this->tweetCreate || $this->approval || $this->postponed || $this->draft || $this->deleted || $this->archived)) {
            return false;
        }

        /* @var $eventResult article */
        $eventResult = $this->events->trigger('article\createTweet', $this);

        $author = new \fpcm\model\users\author($eventResult->getCreateuser());

        $tpl = new \fpcm\model\pubtemplates\tweet();
        $tpl->setReplacementTags(array(
            '{{headline}}' => $eventResult->getTitle(),
            '{{author}}' => $author->getDisplayname(),
            '{{date}}' => date($this->config->system_dtmask, $this->getCreatetime()),
            '{{changeDate}}' => date($this->config->system_dtmask, $this->getChangetime()),
            '{{permaLink}}' => $eventResult->getElementLink(),
            '{{shortLink}}' => $eventResult->getArticleShortLink()
        ));

        if ($this->tweetOverride !== false) {
            $tpl->setContent($this->tweetOverride);
        }

        $twitter = new \fpcm\model\system\twitter();
        return $twitter->updateStatus($tpl->parse());
    }

    /**
     * Sperrt Artikel als in Bearbeitung
     * @return bool
     * @since FPCM 3.5
     */
    public function setInEdit()
    {
        return $this->dbcon->update($this->table, ['inedit'], [time() . '-' . \fpcm\classes\loader::getObject('\fpcm\model\system\session')->getUserId(), $this->id], 'id = ?');
    }

    /**
     * In Bearbeitung Informationen auslesen
     * @return array
     * @since FPCM 3.5.3
     */
    public function getInEdit()
    {
        return explode('-', $this->inedit);
    }

    /**
     * Ist Artikel in Bearbeitung
     * @return bool
     * @since FPCM 3.5
     */
    public function isInEdit()
    {
        if (!trim($this->inedit)) {
            return false;
        }

        $data = explode('-', $this->inedit);
        return $data[0] > time() - FPCM_ARTICLE_LOCKED_INTERVAL && $data[1] != \fpcm\classes\loader::getObject('\fpcm\model\system\session')->getUserId() ? true : false;
    }

    /**
     * Prüft, ob Artikel öffentlich sichtbar ist
     * @return bool
     * @since FPCM 3.5
     */
    public function publicIsVisible()
    {
        $sessionExists = \fpcm\classes\loader::getObject('\fpcm\model\system\session')->exists();

        if (!$this->exists() || ($this->getDeleted() && !$sessionExists)) {
            return false;
        }

        if (($this->getDraft() || $this->getPostponed()) && !$sessionExists) {
            return false;
        }

        return true;
    }

    /**
     * Returns array with all status icons
     * @param bool $showDraftStatus
     * @param bool $showCommentsStatus
     * @param bool $showArchivedStatus
     * @return array
     */
    public function getMetaDataStatusIcons($showDraftStatus, $showCommentsStatus, $showArchivedStatus)
    {
        return [
            $this->getStatusIconPinned(),
            $showDraftStatus ? $this->getStatusIconDraft() : '',
            $this->getStatusIconPostponed(),
            $this->getStatusIconApproval(),
            $showCommentsStatus ? $this->getStatusIconComments() : '',
            $showArchivedStatus ? $this->getStatusIconArchive() : '',
        ];
    }

    /**
     * Returns pinned status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconPinned()
    {
        return (new \fpcm\view\helper\icon('thumbtack fa-rotate-90 fa-inverse'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-pinned fpcm-ui-status-' . $this->getPinned())
                        ->setText('EDITOR_STATUS_PINNED')
                        ->setStack('square');
    }

    /**
     * Returns draft status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconDraft()
    {
        return (new \fpcm\view\helper\icon('file-alt fa-inverse', 'far'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-draft fpcm-ui-status-' . $this->getDraft())
                        ->setText('EDITOR_STATUS_DRAFT')
                        ->setStack('square');
    }

    /**
     * Returns postponed status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconPostponed()
    {
        return (new \fpcm\view\helper\icon('calendar-plus fa-inverse'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-postponed fpcm-ui-status-' . $this->getPostponed())
                        ->setText($this->language->translate('EDITOR_STATUS_POSTPONETO') . ( $this->getPostponed() ? ' ' . new \fpcm\view\helper\dateText($this->getCreatetime()) : ''))
                        ->setStack('square');
    }

    /**
     * Returns approval status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconApproval()
    {
        return (new \fpcm\view\helper\icon('thumbs-up fa-inverse', 'far'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-approval fpcm-ui-status-' . $this->getApproval())
                        ->setText('EDITOR_STATUS_APPROVAL')
                        ->setStack('square');
    }

    /**
     * Returns comments enabled status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconComments()
    {
        return (new \fpcm\view\helper\icon('comments fa-inverse', 'far'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-comments fpcm-ui-status-' . $this->getComments())
                        ->setText('EDITOR_STATUS_COMMENTS')
                        ->setStack('square');
    }

    /**
     * Returns archive status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconArchive()
    {
        return (new \fpcm\view\helper\icon('archive fa-inverse'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-archived fpcm-ui-status-' . $this->getArchived())
                        ->setText('EDITOR_STATUS_ARCHIVE')
                        ->setStack('square');
    }

    /**
     * Führt Ersetzung von gesperrten Texten in Artikel-Daten durch
     * @return bool
     * @since FPCM 3.2.0
     */
    protected function removeBannedTexts()
    {
        if ($this->wordbanList->checkArticleApproval($this->title) ||
                $this->wordbanList->checkArticleApproval($this->content)) {
            $this->setApproval(1);
        }

        $this->title = $this->wordbanList->replaceItems($this->title);
        $this->content = $this->wordbanList->replaceItems($this->content);
        $this->imagepath = $this->wordbanList->replaceItems($this->imagepath);

        return true;
    }

    /**
     * Bereinigt Caches
     * @return void
     * @since FPCM 3.4-rc3
     */
    private function cleanupCaches()
    {
        $this->cache->cleanup(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE . '/'.\fpcm\classes\cache::CLEAR_ALL);
        $this->cache->cleanup(\fpcm\model\articles\article::CACHE_ARTICLE_SINGLE . '/'.\fpcm\classes\cache::CLEAR_ALL);
        $this->cache->cleanup(\fpcm\model\pubtemplates\sharebuttons::CACHE_MODULE . '/'.\fpcm\classes\cache::CLEAR_ALL);
        $this->cache->cleanup(\fpcm\model\abstracts\dashcontainer::CACHE_M0DULE_DASHBOARD . '/'.\fpcm\classes\cache::CLEAR_ALL);
    }

    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::getEventModule
     * @return string
     * @since FPCM 4.1
     */
    protected function getEventModule() : string
    {
        return 'article';
    }

    /**
     * Is triggered after successful database insert
     * @see \fpcm\model\abstracts\dataset::afterSaveInternal
     * @return bool
     * @since FPCM 4.1
     */
    protected function afterSaveInternal(): bool
    {
        $this->cleanupCaches();
        $this->createTweet();
        return true;
    }

    /**
     * Is triggered after successful database update
     * @see \fpcm\model\abstracts\dataset::afterUpdateInternal
     * @return bool
     * @since FPCM 4.1
     */
    protected function afterUpdateInternal(): bool
    {
        $this->cleanupCaches();
        $this->init();
        $this->createTweet();
        return true;
    }

    /**
     * Add sources string to auto-complete file option, max. 25 values saved
     * @param string $sources
     * @return bool
     * @since FPCM 4.1
     */
    static public function addSourcesAutocomplete(string $sources) : bool
    {
        if (!trim($sources)) {
            return true;
        }
        
        $fopt = new \fpcm\model\files\fileOption(self::SOURCES_AUTOCOMPLETE);
        $data = $fopt->read();
        if (!is_array($data)) {
            $data = [];
        }

        return $fopt->write(array_slice(array_unique(array_merge($data, [$sources])), 0, FPCM_ARTICLES_SOURCES_AUTOCOMPLETE));
    }

    /**
     * Fetch sources strings from auto-complete file option
     * @return array
     * @since FPCM 4.1
     */
    static public function fetchSourcesAutocomplete() : array
    {
        $data = (new \fpcm\model\files\fileOption(self::SOURCES_AUTOCOMPLETE))->read();
        return is_array($data) ? $data : [];
    }

}