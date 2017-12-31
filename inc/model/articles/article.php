<?php
    /**
     * FanPress CM Article Model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\articles;

    /**
     * Artikel Objekt
     * 
     * @package fpcm\model\articles
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    class article extends \fpcm\model\abstracts\model {

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
         * News-Titel
         * @var string
         */
        protected $title            = '';
        
        /**
         * News-Text
         * @var string
         */
        protected $content          = '';      
        
        /**
         * Kategorien
         * @var array
         */
        protected $categories       = [];
        
        /**
         * Status: Entwurf
         * @var int
         */
        protected $draft            = 0;
        
        /**
         * Status: archiviert
         * @var int
         */
        protected $archived         = 0;
        
        /**
         * Status: gepinnt
         * @var int
         */
        protected $pinned           = 0;
        
        /**
         * Status: automatisch freischalten
         * @var int
         */
        protected $postponed        = 0;
        
        /**
         * Status: gelöscht
         * @var int
         */
        protected $deleted          = 0;
        
        /**
         * Kommentare aktiv
         * @var int
         */
        protected $comments         = 1;
        
        /**
         * Artikel muss freigegeben werden
         * @var int
         */
        protected $approval         = 0;
        
        /**
         * Pfad zum Artikel-Bild
         * @var string
         * @since FPCM 3.1.0
         */
        protected $imagepath        = '';

        /**
         * Veröffentlichungszeit
         * @var int
         */
        protected $createtime       = 0;
        
        /**
         * Author
         * @var int
         */
        protected $createuser       = 0;
        
        /**
         * Zeitpunkt der letzten Änderung
         * @var int
         */
        protected $changetime       = 0;
        
        /**
         * Benutzer der letzten Änderung
         * @var int
         */
        protected $changeuser       = 0;
        
        /**
         * MD5 Pfad
         * @var string
         */
        protected $md5path          = '';

        /**
         * Artikel-Quellen
         * @var string
         * @since FPCM 3.4
         */
        protected $sources          = '';

        /**
         * Artikel-Quellen
         * @var int
         * @since FPCM 3.5
         */
        protected $inedit           = '';

        /**
         * richtiges Löschen erzwingen
         * @var int
         */
        protected $forceDelete      = 0;

        /**
         * Auszuschließende Elemente beim in save/update
         * @var array
         */
        protected $dbExcludes = array('defaultPermissions', 'forceDelete', 'editPermission', 'tweetOverride', 'tweetCreate', 'crypt');
        
        /**
         * Action-String für edit-Action
         * @var string
         */        
        protected $editAction = 'articles/edit&articleid=';
        
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
        public function __construct($id = null) {
            $this->table        = \fpcm\classes\database::tableArticles;
            $this->wordbanList  = new \fpcm\model\wordban\items();
            $this->crypt        = new \fpcm\classes\crypt();
            
            parent::__construct($id);
        }
        
        /**
         * Gibt Artikel- zurück
         * @return string
         */
        public function getTitle() {
            return $this->title;
        }

        /**
         * Gibt Artikel-Inhalt zurück
         * @return strig
         */
        public function getContent() {
            return $this->content;
        }

        /**
         * Gibt Artikel-Kategorie-IDs zurück
         * @return array
         */
        public function getCategories() {
            if (!is_array($this->categories)) {
                $this->categories = json_decode($this->categories, true);
            }
            
            return $this->categories;
        }

        /**
         * Gibt Artikel-Entwurf-Status zurück
         * @return bool
         */
        public function getDraft() {
            return $this->draft;
        }

        /**
         * Gibt Artikel-archiviert-Status zurück
         * @return bool
         */
        public function getArchived() {
            return $this->archived;
        }

        /**
         * Gibt Artikel-gepinnt-Status zurück
         * @return bool
         */
        public function getPinned() {
            return $this->pinned;
        }

        /**
         * Gibt Artikel-geplant-Status zurück
         * @return bool
         */
        public function getPostponed() {
            return $this->postponed;
        }

        /**
         * Gibt Artikel-gelöscht-Status zurück
         * @return bool
         */
        public function getDeleted() {
            return $this->deleted;
        }

        /**
         * Gibt Artikel-Erstellungszeitpunkt zurück
         * @return int
         */
        public function getCreatetime() {
            return $this->createtime;
        }

        /**
         * Gibt Artikel-Author-ID zurück
         * @return int
         */
        public function getCreateuser() {
            return $this->createuser;
        }

        /**
         * Gibt Zeitpunkt der letzten Änderung zurück
         * @return int
         */
        public function getChangetime() {
            return $this->changetime;
        }

        /**
         * Gibt Benutzer-ID zurück, von dem letzte Änderung durchgeführt wurde
         * @return int
         */
        public function getChangeuser() {
            return $this->changeuser;
        }

        /**
         * Gibt Artikel-Kommentare aktiv-Status zurück
         * @return bool
         */
        public function getComments() {
            return $this->comments;
        }
        
        /**
         * Gibt Artikel-MD5-Pfad zurück
         * @return string
         */
        public function getMd5path() {
            return $this->md5path;
        }
        
        /**
         * Gibt Artikel-muss freigeschaltet werden-Status zurück
         * @return bool
         */
        public function getApproval() {
            return $this->approval;
        }
        
        /**
         * Gibt Pfad zum Artikel-Bild zurück
         * @return string
         * @since FPCM 3.1.0
         */
        public function getImagepath() {
            return $this->imagepath;
        }

        /**
         * Liefert Status, ob Artikel bearbeitet werden kann zurück
         * @return bool
         * @since FPCM 3.3
         */
        public function getEditPermission() {
            return $this->editPermission;
        }

        /**
         * Gibt Artikel-Quellen zurück
         * @return bool
         * @since FPCM 3.4
         */
        public function getSources() {
            return $this->sources;
        }
        
        /**
         * Tweet-Erstellung aktiv?
         * @return bool
         * @since FPCM 3.5.2
         */
        function tweetCreationEnabled() {
            return (bool) $this->tweetCreate;
        }
                   
        /**
         * Ttiel setzen
         * @param string $title
         */
        public function setTitle($title) {
            $this->title = strip_tags($title, '<b><strong><i><em><u><span><br>');
        }

        /**
         * Inhalt setzen
         * @param string $content
         */
        public function setContent($content) {
            $this->content = $content;
        }

        /**
         * Kategorien setzen
         * @param array $categories
         */
        public function setCategories(array $categories) {
            $this->categories = json_encode($categories);
        }

        /**
         * Entwurf-Status setzen
         * @param bool $draft
         */
        public function setDraft($draft) {
            $this->draft = (int) $draft;
        }

        /**
         * archiviert Status setzen
         * @param bool $archived
         */
        public function setArchived($archived) {
            $this->archived = (int) $archived;
        }

        /**
         * gepinnt Status
         * @param bool $pinned
         */
        public function setPinned($pinned) {
            $this->pinned = (int) $pinned;
        }

        /**
         * Geplant-Status setzen
         * @param bool $postponed
         */
        public function setPostponed($postponed) {
            $this->postponed = (int) $postponed;
        }

        /**
         * Gelöscht Status setzen
         * @param bool $deleted
         */
        public function setDeleted($deleted) {
            $this->deleted = (int) $deleted;
        }

        /**
         * Zeitpunk der Erzeugung setzen
         * @param int $createtime
         */
        public function setCreatetime($createtime) {
            $this->createtime = (int) $createtime;
        }

        /**
         * Benutzer der Erzeugung setzen
         * @param int $createuser
         */
        public function setCreateuser($createuser) {
            $this->createuser = (int) $createuser;
        }

        /**
         * Zeitpunk der letzten Änderung setzen
         * @param int $changetime
         */
        public function setChangetime($changetime) {
            $this->changetime = (int) $changetime;
        }

        /**
         * Benutzer der letzten Änderung setzen
         * @param int $changeuser
         */
        public function setChangeuser($changeuser) {
            $this->changeuser = (int) $changeuser;
        }

        /**
         * Kommentar-aktiv-Status setzen
         * @param bool $comments
         */
        public function setComments($comments) {
            $this->comments = (int) $comments;
        }

        /**
         * Freigabe-Status setzen
         * @param bool $approval
         */
        public function setApproval($approval) {
            $this->approval = (int) $approval;
        }
        
        /**
         * Setzt Pfad zum Artikel-Bild
         * @param string $imagepath
         * @since FPCM 3.1.0
         */
        public function setImagepath($imagepath) {
            $this->imagepath = $imagepath;
        }

        /**
         * Setzt Artikel-Quellen-Daten
         * @param string $sources
         * @return bool
         * @since FPCM 3.4
         */
        public function setSources($sources) {
            return $this->sources = strip_tags($sources);
        }

        /**
         * Setzt Status, ob Artikel bearbeitet werden kann
         * @param bool $editPermission
         * @since FPCM 3.3
         */
        public function setEditPermission($editPermission) {
            $this->editPermission = $editPermission;
        }
        
        /**
         * Artikel vollständig löschen erzwingen
         * @param bool $forceDelete
         */   
        public function setForceDelete($forceDelete) {
            $this->forceDelete = $forceDelete;
        }

        /**
         * MD5-Pfad setztne
         * @param string $str
         */
        public function setMd5path($str) {
            $this->md5path = md5($str);
        }

        /**
         * Text für überschriebenes Tweet-Template zurückgeben
         * @return string
         * @since FPCM 3.3
         */
        function getTweetOverride() {
            return $this->tweetOverride;
        }

        /**
         * Text für überschriebenes Tweet-Template setzen
         * @param string $tweetOverride
         * @since FPCM 3.3
         */
        function setTweetOverride($tweetOverride) {
            $this->tweetOverride = $tweetOverride;
        }

        /**
         * Tweet-Erstellung aktivieren
         * @param bool $tweetCreate
         * @since FPCM 3.5.2
         */
        function enableTweetCreation($tweetCreate) {
            $this->tweetCreate = (bool) $tweetCreate;
        }
                
        /**
         * schönen URL-Pfad zurückgeben
         * @return string
         */
        public function getArticleNicePath() {
            return rawurlencode($this->id.'-'.str_replace(array(' ', '---'), '-', strtolower($this->title)));
        }

        /**
         * Gibt Direkt-Link zum Artikel zurück
         * @return string
         */
        public function getArticleLink() {

            $idParam = ($this->config->articles_link_urlrewrite ? $this->getArticleNicePath() : $this->getId());
            
            if (!$this->config->system_mode) {
                return \fpcm\classes\baseconfig::$rootPath.\fpcm\classes\tools::getControllerLink('fpcm/article', array('id' => $idParam));
            }

            return $this->config->system_url.'?module=fpcm/article&id='.$idParam;
        }

        /**
         * Link zum Löschen des Artikel-Caches
         * @return string
         * @since FPCM 3.6
         */
        public function getArticleCacheParams() {
            return ['cache' => urlencode($this->crypt->encrypt('article')), 'objid' => $this->id];
        }
        
        /**
         * Erzeugt Short-Link zum Artikel zurück
         * @return string
         */
        public function getArticleShortLink() {

            $shortenerUrl = 'http://is.gd/create.php?format=simple&url='.urlencode($this->getArticleLink());
            
            if (!\fpcm\classes\baseconfig::canConnect()) {
                return $this->events->runEvent('articleShortLink', array('artikellink' => urlencode($this->getArticleLink()), 'url' => $shortenerUrl))['url'];
            }

            if (defined('FPCM_ARTICLE_DISABLE_SHORTLINKS') && FPCM_ARTICLE_DISABLE_SHORTLINKS) {
                $url = $shortenerUrl;
            }
            else {
                $remote = fopen($shortenerUrl, 'r');
                if (!$remote) {
                    return $this->events->runEvent('articleShortLink', array('artikellink' => urlencode($this->getArticleLink()), 'url' => $shortenerUrl))['url'];
                }
                $url = fgetss($remote);
            }
            
            return $this->events->runEvent('articleShortLink', array('artikellink' => urlencode($this->getArticleLink()), 'url' => $url))['url'];
        }
        
        /**
         * Liefert <img>-Tag für Artikel-Image zurück
         * @return string
         * @since FPCM 3.1.0
         */
        public function getArticleImage() {
            
            if (!trim($this->imagepath)) {
                return '';
            }
            
            return "<img class=\"fpcm-pub-article-image\" src=\"{$this->imagepath}\" alt=\"{$this->title}\" title=\"{$this->title}\">";
        }

        /**
         * Speichert eine neuen Artikel in der Datenbank
         * @return int
         */        
        public function save() {

            $this->removeBannedTexts();

            $params = $this->getPreparedSaveParams();
            $params = $this->events->runEvent('articleSave', $params);

            if (!$this->dbcon->insert($this->table, implode(',', array_keys($params)), implode(', ', $this->getPreparedValueParams(count($params))), array_values($params))) {
                return false;
            }
            
            $this->id = $this->dbcon->getLastInsertId();
            
            $this->cleanupCaches();
            $this->createTweet();
            
            $this->events->runEvent('articleSaveAfter', $this->id);

            return $this->id;
        }

        /**
         * Aktualisiert einen Artikel in der Datenbank
         * @return boolean
         */        
        public function update() {            

            $this->removeBannedTexts();

            $params     = $this->getPreparedSaveParams();
            $fields     = array_keys($params);
            
            $params[]   = $this->getId();
            $params     = $this->events->runEvent('articleUpdate', $params);

            $return = false;
            if ($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
                $return = true;
            }
            
            $this->cleanupCaches();
            $this->init();
            $this->createTweet();

            $this->events->runEvent('articleUpdateAfter', $this->id);

            return $return;            
        }
        
        /**
         * Löscht News in der Datenbank
         * @return bool
         */
        public function delete() {

            $this->cleanupCaches();

            if ($this->config->articles_trash && !$this->forceDelete) {
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
         *Artikel-Daten für Revision vorbereiten
         * @since FPCM 3.4
         */
        public function prepareRevision() {
            $this->data['preparedRevision'] = $this->getPreparedSaveParams();
        }

        /**
         * Erzeugt eine Revision des Artikels
         * @param int $timer
         * @return boolean
         */
        public function createRevision($timer = 0) {

            $content = $this->getPreparedSaveParams();            
            $content = $this->events->runEvent('createRevision', $content);

            if (!$timer) {
                $timer = $this->changetime;
            }

            $revision = new revision();
            $revision->setArticleId($this->id);
            $revision->setRevisionIdx($timer);
            $revision->setContent($content);
            
            $newHash = $revision->createHashSum();
            $revision->setHashsum($newHash);

            if (is_array($this->data['preparedRevision']) && $revision->createHashSum($this->data['preparedRevision']) === $newHash) {
                return true;
            }

            if (!$revision->save()) {
                trigger_error('Unable to create revision for article '.$this->id);
                return false;
            }
            
            return true;
        }
        
        /**
         * Gib Revisionen des Artikels zurück
         * @param bool $full Soll die Revision ganz zurückgegebn werden oder nur Titel
         * @return array
         */
        public function getRevisions($full = false) {

            $result = $this->dbcon->select(
                \fpcm\classes\database::tableRevisions,
                'article_id, revision_idx, content',
                'article_id = ? '.$this->dbcon->orderBy(array('revision_idx DESC')),
                array($this->id)
            );

            $revisionSets = $this->dbcon->fetch($result, true);
            if (!is_array($revisionSets) || !count($revisionSets)) {
                return [];
            }
            $revisionFiles = $this->events->runEvent('getRevisionsBefore', $revisionSets);            
            
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
            
            $revisions = $this->events->runEvent('getRevisionsAfter', array('full' => $full, 'revisions' => $revisions))['revisions'];

            return $revisions;

        }
        
        /**
         * Anzahl Revisionen des Artikels
         * @return array
         * @since FPCM 3.6
         */
        public function getRevisionsCount() {

            return $this->dbcon->count(
                \fpcm\classes\database::tableRevisions,
                'id',
                'article_id = ? ',
                [$this->id]
            );

        }

        /**
         * Lädt Revision eines Artikels
         * @param int $revisionTime Revisions-ID
         * @return boolean
         */
        public function getRevision($revisionTime) {

            $revision = new revision($this->id, $revisionTime);
            if (!$revision->exists()) {
                return false;
            }
            
            $revision = $this->events->runEvent('getRevision', $revision);
            foreach ($revision->getContent() as $key => $value) {                
                $this->$key = $value;                
            }            
            
            return true;            
        }
        
        /**
         * Stellt Revision eines Artikels wieder her
         * @param int $revisionTime Revisions-ID
         * @return boolean
         */
        public function restoreRevision($revisionTime) {
            if (!$this->createRevision(time())) return false;
            $this->getRevision($revisionTime);
            return $this->update();            
        }
        
        /**
         * Löscht Revisionen
         * @param array $revisionList Liste von Revisions-IDs
         * @return boolean
         */
        public function deleteRevisions(array $revisionList = array()) {

            if (!count($revisionList)) {

                return $this->dbcon->delete(
                    \fpcm\classes\database::tableRevisions,
                    'article_id = ?',
                    array($this->id)
                );

            }

            return $this->dbcon->delete(
                \fpcm\classes\database::tableRevisions,
                'article_id = ? AND revision_idx IN ('.implode(',', array_map('intval', $revisionList)).')',
                array($this->id)
            );

        }
        
        /**
         * Erzeugt einen Tweet bei Twitter, wenn Verbindung aktiv und Events ausgewählt
         * @param bool $force
         * @return boolean
         */
        public function createTweet($force = false) {

            if (!\fpcm\classes\baseconfig::canConnect() || (!$this->config->twitter_events['create'] && !$this->config->twitter_events['update'] && !$force)) {
                return false;
            }

            if (!$force && (!$this->tweetCreate || $this->approval || $this->postponed || $this->draft || $this->deleted || $this->archived)) {
                return false;
            }

            /* @var $eventResult article */
            $eventResult = $this->events->runEvent('articleCreateTweet', $this);

            $author  = new \fpcm\model\users\author($eventResult->getCreateuser());
            
            $tpl = new \fpcm\model\pubtemplates\tweet();
            $tpl->setReplacementTags(array(
                '{{headline}}'   => $eventResult->getTitle(),
                '{{author}}'     => $author->getDisplayname(),
                '{{date}}'       => date($this->config->system_dtmask, $this->getCreatetime()),
                '{{changeDate}}' => date($this->config->system_dtmask, $this->getChangetime()),
                '{{permaLink}}'  => $eventResult->getArticleLink(),
                '{{shortLink}}'  => $eventResult->getArticleShortLink()
            ));
            
            if ($this->tweetOverride !== false) {
                $tpl->setContent($this->tweetOverride);
            }
            
            $twitter = new \fpcm\model\system\twitter();
            return $twitter->updateStatus($tpl->parse());
        }
        
        /**
         * Bereitet Daten für Speicherung in Datenbank vor
         * @param boolean $ignoreEditor
         * @return boolean
         */
        public function prepareDataSave($ignoreEditor = false) {
            if (!$this->config->system_editor && !$ignoreEditor) return false;
            
            $this->replaceBr();
            $this->content = str_replace(array('<br>', '<br />', '<br/>'), '', $this->content);
            $this->content = nl2br($this->content, false);
            
            $search  = array('p', 'ul', 'li', 'table', 'tr', 'th', 'td', 'div', 'blockquote', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');

            $eventData = $this->events->runEvent('articlePrepareDataSave', array('content' => $this->content, 'searchParams' => $search));

            $this->content = $eventData['content'];
            $search        = $eventData['searchParams'];

            array_map(array($this, 'replaceDirtyTags'), $search);

            return true;
        }
        
        /**
         * Bereitet Daten nach Laden aus Datenbank vor
         * @return boolean
         */
        public function prepareDataLoad() {
            if (!$this->config->system_editor) return false;

            $this->replaceBr();
            
            return true;
        }

        /**
         * Sperrt Artikel als in Bearbeitung
         * @return bool
         * @since FPCM 3.5
         */
        public function setInEdit() {
            return $this->dbcon->update($this->table, ['inedit'], [time().'-'.FPCM_USERID, $this->id], 'id = ?');
        }

        /**
         * In Bearbeitung Informationen auslesen
         * @return array
         * @since FPCM 3.5.3
         */
        public function getInEdit() {
            return explode('-', $this->inedit);
        }

        /**
         * Ist Artikel in Bearbeitung
         * @return bool
         * @since FPCM 3.5
         */
        public function isInEdit() {
            
            if (!trim($this->inedit)) {
                return false;
            }

            $data = explode('-', $this->inedit);
            return $data[0] > time() - FPCM_ARTICLE_LOCKED_INTERVAL && $data[1] != FPCM_USERID ? true : false;
        }

        /**
         * Prüft, ob Artikel öffentlich sichtbar ist
         * @return boolean
         * @since FPCM 3.5
         */
        public function publicIsVisible() {

            if (!$this->exists() || ($this->getDeleted() && !\fpcm\classes\baseconfig::$fpcmSession->exists())) {
                return false;
            }

            if (($this->getDraft() || $this->getPostponed()) && !\fpcm\classes\baseconfig::$fpcmSession->exists()) {
                return false;
            }

            return true;
        }

        /**
         * Ersetzt <br>, <br /> bzw. <br/> durch Leerzeichen
         */
        private function replaceBr() {
            $this->content = str_replace(array('<br>', '<br />', '<br/>'), '', $this->content);
        }
        
        /**
         * Ersetzt "kaputte" HTML-Tag-Kombinationen in Zusammenhang mit automatischen <br>-Tags beim Speichern
         * @param string $htmlTag
         */
        private function replaceDirtyTags($htmlTag) {
            $search = array("<{$htmlTag}><br>", "<{$htmlTag}><br/>", "<{$htmlTag}><br />");
            $this->content = str_replace($search, "<{$htmlTag}>", $this->content);

            $search = array("</{$htmlTag}><br>", "</{$htmlTag}><br/>", "</{$htmlTag}><br />");
            $this->content = str_replace($search, "</{$htmlTag}>", $this->content);
        }
        
        /**
         * Führt Ersetzung von gesperrten Texten in Artikel-Daten durch
         * @return boolean
         * @since FPCM 3.2.0
         */
        private function removeBannedTexts() {

            if ($this->wordbanList->checkArticleApproval($this->title) ||
                $this->wordbanList->checkArticleApproval($this->content)) {
                $this->setApproval(1);
            }

            $this->title     = $this->wordbanList->replaceItems($this->title);
            $this->content   = $this->wordbanList->replaceItems($this->content);
            $this->imagepath = $this->wordbanList->replaceItems($this->imagepath);

            return true;
        }

        /**
         * Bereinigt Caches
         * @return void
         * @since FPCM 3.4-rc3
         */
        private function cleanupCaches() {

            $this->cache->cleanup(false, \fpcm\model\articles\article::CACHE_ARTICLE_MODULE);
            $this->cache->cleanup(false, \fpcm\model\abstracts\dashcontainer::CACHE_M0DULE_DASHBOARD);

        }
    }