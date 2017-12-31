<?php
    /**
     * Configuration object
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\system;
    
    
    /**
     * System config Objekt
     * 
     * @property string $system_version Version
     * @property string $system_email E-Mail_Adresse
     * @property string $system_url Frontend-Url
     * @property string $system_lang Sprache
     * @property string $system_dtmask Date-Time-Maske
     * @property bool   $system_comments_enabled Kommentare aktiv
     * @property int    $system_session_length ACP-Session Länge
     * @property bool   $system_mode Frontend-Modus (0 = iframe, 1= phpinclude)
     * @property string $system_css_path Pfad zur CSS-Datei
     * @property bool   $system_show_share Share-Buttons anzeigen
     * @property string $system_timezone Zeitzone
     * @property int    $system_cache_timeout Cache-Timeout
     * @property bool   $system_loader_jquery jQuery in Frontend laden
     * @property bool   $system_editor aktiver Editor (0 = TinyMCE, 1= HTML)
     * @property int    $system_editor_fontsize Standard-Schriftgröße im Editor
     * @property bool   $system_maintenance Wartungsmodusaktiv
     * @property int    $system_loginfailed_locked Anzahl fehlgeschlagener Login-Versuche, nach denen Login temporär gesperrt wird
     * @property int    $system_updates_devcheck Entwickler-Versionen bei Update-Prüfung anzeigen
     * @property bool   $system_updates_emailnotify E-Mail-Benachrichtigung über Updates
     * @property int    $system_updates_manual Interval für manuelle Update-Prüfung
     * 
     * @property bool   $articles_revisions Revisionen aktiv
     * @property bool   $articles_trash Papierkorb aktiv
     * @property int    $articles_limit Artikel pro Seite im Fronend
     * @property string $articles_template_active aktives Template für Artikel-Liste
     * @property string $article_template_active aktives Template für Artikel-Einzel-Ansicht
     * @property bool   $articles_archive_show Archiv-Link anzeigen
     * @property string $articles_sort Sortierung der Artikel im Frontend
     * @property string $articles_sort_order Reihenfolge der Sortierung der Artikel im Frontend
     * @property bool   $articles_rss RSS-Feed ist aktiv
     * @property int    $articles_acp_limit Anzahl an Artikeln in der ACP-Liste
     * @property int    $articles_archive_datelimit Datum, bis zu dem Artikel im Archiv maximal angezeigt werden sollen
     * @property int    $articles_revisions_limit Anzahl Revisionen, bei der alte Revisionen bereinigt werden
     * @property bool   $articles_link_urlrewrite URL-Rewriting aktivieren (ID + Artikel-Titel sind in Artikel-Link enthalten)
     * @property bool   $articles_imageedit_persistence Änderungen an Bilder pber TinyMCE auf Server speichern
     * 
     * @property string $comments_template_active aktives Kommentar-Template
     * @property int    $comments_flood Sperre zwischen zwei Kommentaren
     * @property bool   $comments_email_optional E-Mail muss beim Kommentar-Schreiben angegegebn werden
     * @property bool   $comments_confirm Kommentare müssen freigegeben werden
     * @property string $comments_antispam_question Spam-Captcha-Frage
     * @property string $comments_antispam_answer Spam-Captcha-Antwort
     * @property int    $comments_notify wohin sollen Benachrichtigung bei neuem Kommentar gehen (0 = nur globale E-MailAdresse, 1 = nur Author, 2 = beide)
     * @property int    $comments_markspam_commentcount Anzahl an Spam deklarierter vorhandener Kommentare, über der ein ein neuer Kommentar automatisch als Spam markiert wird
     * 
     * @property int    $file_img_thumb_width Breite der Thumbnails
     * @property int    $file_img_thumb_height Höhe der Thumbnails
     * @property bool   $file_uploader_new jQuery-Uploader aktiv
     * @property int    $file_list_limit Anzahl an Dateien pro Seite in Dateimanager
     * 
     * @property array  $twitter_data Daten für Twitter-Verbindung
     * @property array  $twitter_events Events, wenn Tweets erzeugt werden sollen
     * 
     * @property array  $smtp_enabled E-Mail-Versand via SMTP aktiv
     * @property array  $smtp_settings Konfiguration für E-Mail-Versand via SMTP
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class config extends \fpcm\model\abstracts\model {

        /**
         * Neue Konfiguration
         * @var array
         */        
        protected $newConfig = [];
        
        /**
         * this->data cachen
         * @var bool
         */
        protected $useCache  = true;

        /**
         * Konstruktor
         * @param bool $initUserSettings Benutzereinstellungen laden
         * @param bool $useCache Configuration aus Cache laden
         * @return boolean
         */
        function __construct($initUserSettings = true, $useCache = true) {
            
            $this->table    = \fpcm\classes\database::tableConfig;
            $this->dbcon    = \fpcm\classes\baseconfig::$fpcmDatabase;
            $this->events   = \fpcm\classes\baseconfig::$fpcmEvents;
            $this->cache    = new \fpcm\classes\cache('config', 'system');
            $this->useCache = $useCache;

            $this->data = [];

            if (\fpcm\classes\baseconfig::installerEnabled()) return false;
            
            $this->init();
            
            if ($initUserSettings) {
                $this->setUserSettings();
            }            
        }
        
        /**
         * Speichert neue Konfiguration
         * @param array $newConfig
         */
        public function setNewConfig(array $newConfig) {
            $this->newConfig = $newConfig;
        }        

        /**
         * not used
         * @return boolean
         */
        public function save() {
            return false;
        }
        
        /**
         * not used
         * @return boolean
         */
        public function delete() {
            return false;
        }

        /**
         * Konfiguration aktualisieren
         * @return boolean
         */
        public function update() {
            if (!count($this->newConfig)) return false;

            $params = $this->events->runEvent('configUpdate', $this->newConfig);

            $data  = [];
            $where = [];
            
            foreach ($params as $key => $value) {

                if (!array_key_exists($key, $this->data) && !\fpcm\classes\baseconfig::installerEnabled()) {
                    continue;      
                }

                $data[$key]  = [$value, $key];
                $where[$key] = 'config_name = ?';
            }

            $this->dbcon->updateMultiple($this->table, ['config_value'], $data, $where);
            $this->refresh();

            return true;            
        }
        
        /**
         * Neuen Config-Key erzeugen
         * @param string $keyname
         * @param string $keyvalue
         * @return boolean
         */
        public function add($keyname, $keyvalue) {
            if (isset($this->data[$keyname])) return -1;

            $keyvalue = is_array($keyvalue) ? json_encode($keyvalue) : $keyvalue;
            $res = $this->dbcon->insert($this->table, 'config_name, config_value', '?, ?', array($keyname, $keyvalue));
            
            $this->refresh();
            
            return $res;
        }
        
        /**
         * Config Key löschen
         * @param string $keyname
         * @return boolean
         */
        public function remove($keyname) {
            if (!isset($this->data[$keyname])) return false;

            $res = $this->dbcon->delete($this->table, "config_name ".$this->dbcon->dbLike()." ?", array($keyname));
            
            $this->refresh();
            
            return $res;
        }
        
        /**
         * Überschreibt systemweite Einstellungen mit Benutzer-Einstellungen
         * @return void
         */
        public function setUserSettings() {

            if (!defined('FPCM_USERID') || !FPCM_USERID) return false;

            $cache2 = new \fpcm\classes\cache($this->cacheName.'_user'.FPCM_USERID, 'system');
            
            $userData = $cache2->read();
            
            if ($cache2->isExpired() || !$this->useCache || !is_array($userData)) {
                $userData = $this->dbcon->fetch($this->dbcon->select(\fpcm\classes\database::tableAuthors, 'id, usrmeta', 'id = ?', array(FPCM_USERID)));
                $userData = json_decode($userData->usrmeta, true);

                if (!is_array($userData)) return false;                    
                
                $cache2->write($userData, $this->system_cache_timeout);
            }

            foreach ($userData as $key => $value) {
                $this->data[$key] = $value;
            }
            
            if ($this->system_lang != \fpcm\classes\baseconfig::$fpcmLanguage->getLangCode()) {
                \fpcm\classes\baseconfig::$fpcmLanguage = new \fpcm\classes\language($this->system_lang);                
            }
        }
        
        /**
         * Wartungsmodus aktivieren
         * @param bool $mode
         * @return bool
         */
        public function setMaintenanceMode($mode) {
            $this->newConfig = ['system_maintenance' => (int) $mode];
            return $this->update();
        }

        /**
         * Inittiert Objekt mit Daten aus der Datenbank
         */
        public function init() {
            
            if (\fpcm\classes\baseconfig::installerEnabled()) return false;
            
            if ($this->cache->isExpired() || !$this->useCache) {
                $configData = $this->dbcon->fetch($this->dbcon->select($this->table), true);
                foreach ($configData as $data) {
                    $this->data[$data->config_name] = $data->config_value;
                }

                $this->data['twitter_data']   = json_decode($this->data['twitter_data'], true);
                $this->data['twitter_events'] = json_decode($this->data['twitter_events'], true);
                $this->data['smtp_settings']  = json_decode($this->data['smtp_settings'], true);

                $this->cache->write($this->data, $this->data['system_cache_timeout']);
                
                return;
            }
            
            $this->data = $this->cache->read();
        }

        /**
         * Bereitet Daten für Speicherung in Datenbank vor
         * @return boolean
         * @since FPCM 3.6
         */
        public function prepareDataSave() {

            if (isset($this->newConfig['twitter_events']) && is_array($this->newConfig['twitter_events'])) {
                $this->newConfig['twitter_events'] = json_encode($this->newConfig['twitter_events']);
            }

            if (isset($this->newConfig['twitter_data']) && is_array($this->newConfig['twitter_data'])) {
                $this->newConfig['twitter_data'] = json_encode($this->newConfig['twitter_data']);
            }

            if (isset($this->newConfig['smtp_settings']) && is_array($this->newConfig['smtp_settings'])) {
                $this->newConfig['smtp_settings'] = json_encode($this->newConfig['smtp_settings']);
            }

            if (isset($this->newConfig['articles_limit'])) {
                $this->newConfig['articles_limit'] = (int) $this->newConfig['articles_limit'];
            }

            if (isset($this->newConfig['articles_acp_limit'])) {
                $this->newConfig['articles_acp_limit'] = (int) $this->newConfig['articles_acp_limit'];
            }

            if (isset($this->newConfig['system_cache_timeout'])) {
                $this->newConfig['system_cache_timeout'] = (int) $this->newConfig['system_cache_timeout'];
            }

            if (isset($this->newConfig['system_session_length'])) {
                $this->newConfig['system_session_length'] = (int) $this->newConfig['system_session_length'];
            }

            if (isset($this->newConfig['comments_flood'])) {
                $this->newConfig['comments_flood'] = (int) $this->newConfig['comments_flood'];
            }

            if (isset($this->newConfig['system_loginfailed_locked'])) {
                $this->newConfig['system_loginfailed_locked'] = (int) $this->newConfig['system_loginfailed_locked'];
            }

            if (isset($this->newConfig['comments_markspam_commentcount'])) {
                $this->newConfig['comments_markspam_commentcount'] = (int) $this->newConfig['comments_markspam_commentcount'];
            }

            if (isset($this->newConfig['file_img_thumb_width'])) {
                $this->newConfig['file_img_thumb_width'] = (int) $this->newConfig['file_img_thumb_width'];
            }

            if (isset($this->newConfig['file_img_thumb_height'])) {
                $this->newConfig['file_img_thumb_height'] = (int) $this->newConfig['file_img_thumb_height'];
            }

            if (isset($this->newConfig['file_list_limit'])) {
                $this->newConfig['file_list_limit'] = (int) $this->newConfig['file_list_limit'];
            }

            if (isset($this->newConfig['system_updates_devcheck'])) {
                $this->newConfig['system_updates_devcheck'] = (int) $this->newConfig['system_updates_devcheck'];
            }

            if (isset($this->newConfig['articles_revisions_limit'])) {
                $this->newConfig['articles_revisions_limit'] = (int) $this->newConfig['articles_revisions_limit'];
            }

            if (isset($this->newConfig['articles_link_urlrewrite'])) {
                $this->newConfig['articles_link_urlrewrite'] = (int) $this->newConfig['articles_link_urlrewrite'];
            }

            if (isset($this->newConfig['articles_imageedit_persistence'])) {
                $this->newConfig['articles_imageedit_persistence'] = (int) $this->newConfig['articles_imageedit_persistence'];
            }

            if (isset($this->newConfig['articles_archive_datelimit'])) {
                $this->newConfig['articles_archive_datelimit'] = $this->newConfig['articles_archive_datelimit']
                                                               ? strtotime($this->newConfig['articles_archive_datelimit']) : 0;
            }
            
            return true;
        }
        /**
         * Config-Refresh
         */
        private function refresh() {
            $this->cache->cleanup();
            $this->init();            
        }

        /**
         * Array mit Schriftgrößen für Editor
         * @return array
         * @since FPCM 3.4
         */
        public static function getDefaultFontsizes() {

            $defaultFontsizes = [];
            for ($i=8; $i<=16;$i++) {
                $defaultFontsizes[$i.' pt'] = $i.'pt';
            }
            
            return $defaultFontsizes;
        }

        /**
         * Array mit Anzahl-Limits für Artikel in ACP-Liste
         * @return array
         * @since FPCM 3.4
         */
        public static function getAcpArticleLimits() {

            return array(
                10 => 10,
                25 => 25,
                50 => 50,
                75 => 75,
                100 => 100,
                125 => 125,
                150 => 150,
                200 => 200,
                250 => 250
            );

        }

        /**
         * Array mit Anzahl-Limits für Artikel-Listen
         * @return array
         * @since FPCM 3.4
         */
        public static function getArticleLimits() {

            return array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                10 => 10,
                15 => 15,
                20 => 20,
                25 => 25,
                30 => 30,
                40 => 40,
                50 => 50
            );

        }
        
    }
