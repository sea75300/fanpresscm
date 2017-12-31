<?php
    /**
     * Public template file object
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\pubtemplates;

    /**
     * generisches Template Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class template extends \fpcm\model\abstracts\file {

        /**
         * erlaubte Template-Tags
         * @var array
         */
        protected $allowedTags = array(
            '<div>', '<span>', '<p>', '<b>', '<strong>', '<i>', '<em>', '<u>', '<a>', '<h1>', '<h2>', '<h3>', '<h4>', '<h5>', 
            '<h6>', '<img>', '<table>', '<tr>', '<td>', '<br>', '<form>', '<input>', '<button>', '<select>', '<option>',
            '<ul>', '<ol>', '<li>', '<script>', '<iframe>', '<label>'
        );

        /**
         * Template-Platzhalter
         * @var array
         */
        protected $replacementTags      = [];

        /**
         * Interne Platzhalter
         * @var array
         */
        protected $replacementInternal  = [];

        /**
         * Platzhalter mit Sprachbezeichner
         * @var array
         * @since FPCM 3.5.2
         */
        protected $replacementTranslated  = [];
        
        /**
         * Smiley-Cache
         * @var \fpcm\classes\cache
         */
        protected $smileyCache;

        /**
         * Konstruktor
         * @param string $filename Template-Datei unterhalb von data/styles/$filepath
         * @param string $filepath Template-Datei-Pfad unterhalb von data/styles/
         */
        public function __construct($filename = '', $filepath = '') {
            parent::__construct($filename, $filepath);
            $this->init(null);
            $this->smileyCache = new \fpcm\classes\cache('smileyCache');
        }
        
        /**
         * Gibt erlaubte HTML-Tags als string zurück
         * @param string $delim
         * @return string
         */
        public function getAllowedTags($delim = '') {
            return implode($delim, $this->allowedTags);
        }

        /**
         * Liefert Platzhalter zurück
         * @return array
         */
        public function getReplacementTags() {
            return $this->replacementTags;
        }

        /**
         * Liefert interne Platzhalter zurück
         * @return array
         */
        public function getReplacementInternal() {
            return $this->replacementInternal;
        }

        /**
         * Fügt erlaubte HTML-Tags hinzu
         * @param array $allowedTags
         */
        public function setAllowedTags(array $allowedTags) {
            $this->allowedTags = array_merge($this->allowedTags, $allowedTags);
        }

        /**
         * Fügt Platzhalter hinzu
         * @param array $replacementTags
         */
        public function setReplacementTags(array $replacementTags) {
            $this->replacementTags = $replacementTags;
        }

        /**
         * nicht verwendet
         * @return boolean
         */
        public function delete() {
            return false;
        }
        
        /**
         * nicht verwendet
         * @param string $newname
         * @param int $userId
         * @return boolean
         */
        public function rename($newname, $userId = false) {
            return false;
        }       
        
        /**
         * Datei-Inhalt festlegen
         * @param string $content
         */
        public function setContent($content) {
            parent::setContent(strip_tags($content, $this->getAllowedTags()));
        }

        /**
         * Datei-Inhalt zurückgeben
         * @return string
         */
        public function getContent() {
            return strip_tags(parent::getContent(), $this->getAllowedTags());
        }
        
        /**
         * Speichert Template in Dateisystem
         * @return boolean
         */
        public function save() {
            if (!$this->exists() || !$this->content || !$this->isWritable()) return false;
            
            $this->content = $this->events->runEvent('templateSave', array('file' => $this->fullpath, 'content' => $this->content))['content'];
            
            if (!file_put_contents($this->fullpath, $this->content)) {
                trigger_error('Unable to update template '.$this->fullpath);
                return false;
            }
            
            $this->cache->cleanup();
            
            return true;
        }
        
        /**
         * Parst Template-Platzhalter
         * @return string
         */
        public function parse() {
            
            if (!count($this->replacementTags) || !$this->content) return false;

            $this->replacementTags = $this->events->runEvent('parseTemplate', $this->replacementTags);
            
            $tags    = array_merge($this->replacementInternal, $this->replacementTags);
            return str_replace(array_keys($tags), array_values($tags), $this->content);
        }
        
        /**
         * Platzhalter-Übersetzungen
         * @param string $prefix
         * @return array
         * @since FPCM 3.5.2
         */
        public function getReplacementTranslations($prefix) {

            if (count($this->replacementTranslated)) {
                return $this->replacementTranslated;
            }

            foreach ($this->replacementTags as $key => $value) {
                $data = explode(':', strtoupper(str_replace(array('{{', '}}'), '', $key)));
                $this->replacementTranslated[$key] = $this->language->translate($prefix.$data[0]);
            }

            return $this->replacementTranslated;

        }
        
        /**
         * Parst Smileys in Artikeln und Kommentaren
         * @param string $content
         * @return string
         */
        protected function parseSmileys($content) {
            
            if ($this->smileyCache->isExpired()) {
                $smileysList = new \fpcm\model\files\smileylist();
                $smileys = $smileysList->getDatabaseList();
                $this->smileyCache->write($smileys, $this->config->system_cache_timeout);
            } else {
                $smileys = $this->smileyCache->read();
            }
                        
            foreach ($smileys as $smiley) {
                $content = str_replace($smiley->getSmileyCode(), $this->parseSmileyFilePath($smiley), $content);
            }
            
            return $content;
        }
        
        /**
         * Parst Smileys
         * @param \fpcm\model\files\smiley $smiley
         * @return string
         */
        private function parseSmileyFilePath(\fpcm\model\files\smiley $smiley) {
            return '<img src="'.$smiley->getSmileyUrl().'" class="fpcm-pub-smiley" '.$smiley->getWhstring().' alt="">';
        }

        /**
         * Initialisiert Template-Inhalt
         * @param void $initDB
         */
        protected function init($initDB) {

            if (!$this->exists()) {
                return false;
            }

            $this->content      = file_get_contents($this->fullpath);
            $this->allowedTags  = $this->events->runEvent('publicTemplateHtmlTags', $this->allowedTags);
        }

        /**
         * Links in Text parsen
         * @param string $content
         * @param array $attributes
         * @param bool $returnOnly
         * @return string
         */
        protected function parseLinks(&$content, array $attributes=array(), $returnOnly = false) {

            $attrs = '';
            foreach ($attributes as $attribute => $value) {
                $attrs .= " {$attribute}=\"{$value}\"";
            }

            $regEx   = '/((http|https?):\/\/\S+[^\s.,>)\]\"\'<\/])/is';

            if ($returnOnly) {
                preg_match_all($regEx, $content, $matches);
                return $matches;
            }

            $content  = preg_replace($regEx, "<a href=\"$0\"{$attrs}>$0</a>", $content);

            return $content;

        }
       
    }
?>