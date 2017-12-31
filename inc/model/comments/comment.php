<?php
    /**
     * FanPress CM Comment Model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\comments;

    /**
     * Kommentar-Objekt
     * 
     * @package fpcm\model\comments
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */    
    class comment extends \fpcm\model\abstracts\model {
        
        /**
         * Erlaubte HTML-Tags in einem Kommentar, für Formular
         */
        const COMMENT_TEXT_HTMLTAGS_FORM = '<b>, <strong>, <i>, <em>, <u>, <a href="">, <blockquote>, <p>, <span>, <div>, <ul>, <ol>, <li>, <img>';
        
        /**
         * Erlaubte HTML-Tags in einem Kommentar, interne Prüfung
         */
        const COMMENT_TEXT_HTMLTAGS_CHECK = '<b><strong><i><em><u><a><blockquote><p><span><div><ul><ol><li><img>';
        
        /**
         * Article-ID
         * @var int
         */
        protected $articleid        = 0;

        /**
         * Kommentare-Author-Name
         * @var string
         */
        protected $name             = '';
        
        /**
         * E-Mail-Adresse
         * @var string
         */
        protected $email            = '';
        
        /**
         * Webseite des Authors
         * @var string
         */
        protected $website          = '';
        
        /**
         * Kommentare Text
         * @var string
         */
        protected $text             = '';
        
        /**
         * Status "privat"
         * @var int
         */
        protected $private          = 0;
        
        /**
         * Status "genehmigt"
         * @var int
         */
        protected $approved         = 0;
        
        /**
         * Kommentar wurde als Spam eingestuft
         * @var bool
         */
        protected $spammer        = 0;
        
        /**
         * IP-Adresse, von der der Kommentar geschrieben wurde
         * @var string
         */
        protected $ipaddress        = '';
        
        /**
         * Veröffentlichungszeit
         * @var int
         */
        protected $createtime       = 0;
        
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
         * Action-String für edit-Action
         * @var string
         */        
        protected $editAction = 'comments/edit&commentid=';
        
        /**
         * Wortsperren-Liste
         * @var \fpcm\model\wordban\items
         * @since FPCM 3.2.0
         */
        protected $wordbanList;

        /**
         * Status ob Kommentar bearbeitet werden kann
         * @var bool
         * @since FPCM 3.3
         */
        protected $editPermission = true;

        /**
         * Auszuschließende Elemente beim in save/update
         * @var array
         */
        protected $dbExcludes = array('editPermission');

        /**
         * Konstruktor
         * @param int $id
         */
        public function __construct($id = null) {
            $this->table = \fpcm\classes\database::tableComments;
            $this->wordbanList = new \fpcm\model\wordban\items();
            
            parent::__construct($id);
            
        }

        /**
         * Artikel-ID
         * @return int
         */
        public function getArticleid() {
            return $this->articleid;
        }

        /**
         * Author-Name
         * @return string
         */
        public function getName() {
            return $this->name;
        }

        /**
         * Author-Email-Adresse
         * @return string
         */
        public function getEmail() {
            return $this->email;
        }

        /**
         * Webseite des Authors
         * @return string
         */
        public function getWebsite() {
            return $this->website;
        }

        /**
         * Kommentar-Text
         * @return string
         */
        public function getText() {
            return $this->text;
        }

        /**
         * Ist Kommentar privat
         * @return bool
         */
        public function getPrivate() {
            return $this->private;
        }

        /**
         * Ist Kommentar genehmigt
         * @return bool
         */
        public function getApproved() {
            return $this->approved;
        }

        /**
         * Ist Kommentar Spam
         * @return bool
         */        
        public function getSpammer() {
            return $this->spammer;
        }

        /**
         * IP-Adresse des Authors
         * @return string
         */
        public function getIpaddress() {
            return $this->ipaddress;
        }
        
        /**
         * Erstellungszeitpunkt
         * @return int
         */
        public function getCreatetime() {
            return $this->createtime;
        }

        /**
         * Zeitpunkt der letzten Änderung
         * @return int
         */
        public function getChangetime() {
            return $this->changetime;
        }

        /**
         * Benutzer der letzten Änderung durchgeführt hat
         * @return int
         */
        public function getChangeuser() {
            return $this->changeuser;
        }

        /**
         * Liefert Status, ob Kommentar bearbeitet werden kann zurück
         * @return bool
         * @since FPCM 3.3
         */
        public function getEditPermission() {
            return $this->editPermission;
        }

        /**
         * Setzt Artikel-ID
         * @param int $articleid
         */
        public function setArticleid($articleid) {
            $this->articleid = $articleid;
        }

        /**
         * Setzt Author-Name
         * @param string $name
         */
        public function setName($name) {
            $this->name = $name;
        }

        /**
         * Setzt Author-Email-Adresse
         * @param string $email
         */
        public function setEmail($email) {
            $this->email = $email;
        }

        /**
         * Setzt Webseiten-URL
         * @param string $website
         */
        public function setWebsite($website) {
            $this->website = $website;
        }

        /**
         * Setzt Kommentar-Text
         * @param string $text
         */
        public function setText($text) {
            $this->text = $text;
        }

        /**
         * Setzt Privat-Status
         * @param bool $private
         */
        public function setPrivate($private) {
            $this->private = (int) $private;
        }

        /**
         * Setzt Genehmigt-Status
         * @param int $approved
         */
        public function setApproved($approved) {
            $this->approved = (int) $approved;
        }
        
        /**
         * Setzt Spam-Status
         * @param int $spammer
         */
        public function setSpammer($spammer) {
            $this->spammer = (int) $spammer;
        }
        
        /**
         * Setzt IP-Adresse des Authors
         * @param type $ipaddress
         */
        public function setIpaddress($ipaddress) {
            $this->ipaddress = $ipaddress;
        }

        /**
         * Setzt Erstellungszeitpunkt
         * @param int $createtime
         */
        public function setCreatetime($createtime) {
            $this->createtime = (int) $createtime;
        }

        /**
         * Setzt Zeitpunkt der letzten Änderung
         * @param int $changetime
         */
        public function setChangetime($changetime) {
            $this->changetime = (int) $changetime;
        }

        /**
         * Setzt Benutzer, der letzte Änderung durchgeführt hat
         * @param int $changeuser
         */
        public function setChangeuser($changeuser) {
            $this->changeuser = (int) $changeuser;
        }

        /**
         * Setzt Status, ob Kommentar bearbeitet werden kann
         * @param bool $editPermission
         * @since FPCM 3.3
         */
        public function setEditPermission($editPermission) {
            $this->editPermission = $editPermission;
        }
        
        /**
         * Speichert einen neuen Kommentar in der Datenbank
         * @return int
         */        
        public function save() {
            
            $this->removeBannedTexts();
            
            $params = $this->getPreparedSaveParams();
            $params = $this->events->runEvent('commentSave', $params);

            if (!$this->dbcon->insert($this->table, implode(',', array_keys($params)), implode(', ', $this->getPreparedValueParams()), array_values($params))) {
                return false;
            }
            
            $this->cache->cleanup(false, \fpcm\model\articles\article::CACHE_ARTICLE_MODULE);

            return $this->dbcon->getLastInsertId();
        }
        
        /**
         * Aktualisiert einen Kommentar in der Datenbank
         * @return boolean
         */        
        public function update() {

            $this->removeBannedTexts();

            $params     = $this->getPreparedSaveParams();
            $fields     = array_keys($params);
            
            $params[]   = $this->getId();
            $params     = $this->events->runEvent('commentUpdate', $params);

            $return = false;
            if ($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
                $return = true;
            }
            
            $this->cache->cleanup(false, \fpcm\model\articles\article::CACHE_ARTICLE_MODULE);             
            $this->init();
            
            return $return;
        }
        
        /**
         * Gibt Direkt-Link zum Artikel zurück
         * @return string
         */
        public function getArticleLink() {
            return $this->config->system_url.'?module=fpcm/article&id='.$this->articleid.'#comments';
        }

        /**
         * Bereitet Daten für Speicherung in Datenbank vor
         * @return boolean
         * @since FPCM 3.6
         */
        public function prepareDataSave() {
            
            $search = ['onload', 'onclick', 'onblur', 'onkey', 'onmouse'];
            $this->text = str_replace($search, 'forbidden', $this->text);

            return true;
        }
        
        /**
         * Führt Ersetzung von gesperrten Texten in Kommentar-Daten durch
         * @return boolean
         * @since FPCM 3.2.0
         */
        private function removeBannedTexts() {

            if ($this->wordbanList->checkCommentApproval($this->name) ||
                $this->wordbanList->checkCommentApproval($this->email) ||
                $this->wordbanList->checkCommentApproval($this->website) ||
                $this->wordbanList->checkCommentApproval($this->text)) {
                $this->setApproved(0);
            }

            $this->name  = $this->wordbanList->replaceItems($this->name);
            $this->email  = $this->wordbanList->replaceItems($this->email);
            $this->website  = $this->wordbanList->replaceItems($this->website);
            $this->text  = $this->wordbanList->replaceItems($this->text);
            
            return true;
        }
        
    }
