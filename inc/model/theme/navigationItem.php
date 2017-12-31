<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\theme;

    /**
     * ACP navigation item object
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\theme
     * @since FPCM 3.5
     */ 
    class navigationItem extends \fpcm\model\abstracts\staticModel {

        /**
         * im Navigation angezeigte Beschreibung
         * @var string
         */
        protected $description  = '';

        /**
         * Zielurl
         * @var string
         */
        protected $url         = '';

        /**
         * CSS-Klassen für Icon
         * @var string
         */
        protected $icon         = '';

        /**
         * allgemeine CSS-Klassen
         * @var string
         */
        protected $class        = '';

        /**
         * Item-ID
         * @var string
         */
        protected $id           = '';

        /**
         * Eltern-Bereich des Menü-Eintrages
         * @var string
         */
        protected $parent       = 'after';

        /**
         * Berechtigungen
         * @var array
         */
        protected $permission   = [];

        /**
         * Untermenü, array mit Elementen vom Typ navigationItem
         * @var array
         */
        protected $submenu      = [];

        /**
         * Status, ob auf Element Platzhalter folgt
         * @var bool
         */
        protected $spacer      = false;

        /**
         * aktuell ausgewähltes Modul
         * @var string
         */
        private $currentModule = '';

        /**
         * Konstruktor
         */
        public function __construct() {
            
            $this->config   = \fpcm\classes\baseconfig::$fpcmConfig;
            $this->language = \fpcm\classes\baseconfig::$fpcmLanguage;

            $this->id            = uniqid('fpcm-nav-item');
            $this->currentModule = \fpcm\classes\tools::getNavigationActiveCheckStr();
            
        }

        /**
         * Beschreibung zurückgeben
         * @return string
         */
        public function getDescription() {
            return $this->description;
        }

        /**
         * Zielurl zurückgeben
         * @return string
         */
        public function getUrl() {
            return $this->url;
        }

        /**
         * Zielurl zurückgeben
         * @return string
         */
        public function getFullUrl() {
            return \fpcm\classes\baseconfig::$rootPath.'index.php?module='.$this->url;
        }

        /**
         * CSS-Klassen für Icon zurückgeben
         * @return string
         */
        public function getIcon() {
            return $this->icon;
        }

        /**
         * allgemeine CSS-Klassen zurückgeben
         * @return string
         */
        public function getClass() {
            return $this->class;
        }

        /**
         * Item-ID zurückgeben
         * @return string
         */
        public function getId() {
            return $this->id;
        }

        /**
         * Eltern-Bereich zurückgeben
         * @return string
         */
        public function getParent() {
            return $this->parent;
        }
        
        /**
         * Berechtigungen zurückgeben
         * @return array
         */
        public function getPermission() {
            return $this->permission;
        }

        /**
         * Untermenü-Elemente zurückgegen
         * @return array
         */
        public function getSubmenu() {
            return $this->submenu;
        }

        /**
         * Beschreibung setzen
         * @param string $description
         */
        public function setDescription($description) {
            $this->description = $description;
        }

        /**
         * Zielurl setzen
         * @param string $url
         */
        public function setUrl($url) {
            $this->url = $url;
        }

        /**
         * CSS-Klassen für Icon setzen
         * @param string $icon
         */
        public function setIcon($icon) {
            $this->icon = $icon;
        }

        /**
         * allgemeine CSS-Klassen setzen
         * @param string $class
         */
        public function setClass($class) {
            $this->class = $class;
        }

        /**
         * Item-ID setzen
         * @param string $id
         */
        public function setId($id) {
            $this->id = $id;
        }

        /**
         * Eltern-Bereich setzen
         * @param string $parent
         */
        public function setParent($parent) {
            $this->parent = $parent;
        }
                
        /**
         * Berechtigungen setzen
         * @param array $permission
         */
        public function setPermission(array $permission) {
            $this->permission = $permission;
        }

        /**
         * Untermenü-Array füllen
         * @param array $submenu
         */
        public function setSubmenu(array $submenu) {
            $this->submenu = $submenu;
        }

        /**
         * Status, dass Spacer nach Element angezeigt werden soll
         * @param bool $spacer
         */
        public function setSpacer($spacer) {
            $this->spacer = (bool) $spacer;
        }

        /**
         * Status, ob Spacer nach Element angezeigt werden soll
         * @return bool
         */
        public function hasSpacer() {
            return (bool) $this->spacer;
        }

        /**
         * Status, Untermenü-Einträge existieren
         * @return bool
         */
        public function hasSubmenu() {
            return count($this->submenu) ? true : false;
        }

        /**
         * Status, Berechtigungen geprüft werden müssen
         * @return bool
         */
        public function hasPermission() {
            return count($this->permission) ? true : false;
        }

        /**
         * Status zurückgeben, ob Ziel aktiv ist
         * @return bool
         */
        public function isActive() {
            return ( substr($this->url, 0, strlen($this->currentModule)) === $this->currentModule ? true : false );
        }

        /**
         * navigationItem aus Array erzeugen
         * @param array $data
         * @return \fpcm\model\theme\navigationItem
         */
        public static function createItemFromArray(array $data) {
            
            $item = new navigationItem();
            $item->setUrl(isset($data['url']) ? $data['url'] : '#');
            $item->setDescription(isset($data['description']) ? $data['description'] : '');
            $item->setIcon(isset($data['icon']) ? $data['icon'] : 'fa fa-fw fa-square');
            $item->setId(isset($data['id']) ? $data['id'] : '');
            $item->setClass(isset($data['class']) ? $data['class'] : '');
            $item->setPermission(isset($data['permission']) && is_array($data['permission']) ? $data['permission'] : []);
            $item->setSubmenu(isset($data['submenu']) && is_array($data['submenu']) ? $data['submenu'] : []);
            
            return $item;
        }

        /**
         * @ignore
         * @return array
         */
        public function __sleep() {
            $this->config     = null;
            $this->language   = null;
            
            return ['description', 'url', 'icon', 'class', 'id', 'parent', 'permission', 'submenu', 'spacer'];
        }

        /**
         * @ignore
         * @return void
         */
        public function __wakeup() {
            $this->config        = \fpcm\classes\baseconfig::$fpcmConfig;
            $this->language      = \fpcm\classes\baseconfig::$fpcmLanguage;
            $this->currentModule = \fpcm\classes\tools::getNavigationActiveCheckStr();
        }

    }
