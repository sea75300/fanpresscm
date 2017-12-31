<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\theme;

    /**
     * ACP notification item in top menu
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\theme
     * @since FPCM 3.6
     */ 
    class notificationItem {

        /**
         * im Navigation angezeigte Beschreibung
         * @var string
         */
        protected $description  = '';

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
         * JavaScript Callback in fpcm.notifications
         * @var string
         */
        protected $callback     = '';

        /**
         * Konstruktor
         * @param string $description Sprachvariable der Beschreibung
         * @param string $icon Icon
         * @param string $class CSS-Klasse (optional)
         * @param string $id ID des Elements
         */
        function __construct($description, $icon, $class = false, $id = false, $callback = false) {
            $this->description  = \fpcm\classes\baseconfig::$fpcmLanguage->translate($description);
            $this->icon         = $icon;
            $this->class        = trim($class) ? ' '. trim($class) : '';
            $this->id           = trim($id) ? trim($id) : uniqid('fpcm-notification-item');
            $this->callback     = $callback;
        }

        /**
         * Beschreibung zurückgeben
         * @return string
         */
        public function getDescription() {
            return $this->description;
        }

        /**
         * Beschreibung anpassen, inkl. Platzhalter
         * @param string $description
         * @param array $params
         */
        public function setDescription($description, array $params = []) {
            $this->description = \fpcm\classes\baseconfig::$fpcmLanguage->translate($description, $params);
        }
                
        /**
         * CSS-Klassen für Icon zurückgeben
         * @return string
         */
        public function getIcon() {
            return $this->icon;
        }

        /**
         * CSS-Klassen zurückgeben
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
         * Objekt als String zurückgeben
         * @return string
         */
        public function __toString() {
            
            if ($this->callback && strpos($this->callback, 'http') === 0) {
                return "<li title=\"{$this->description}\" id=\"{$this->id}\" class=\"fpcm-menu-top-level1 fpcm-notification-item\"><a href=\"{$this->callback}\"><span class=\"{$this->icon}{$this->class}\" title=\"{$this->description}\"></span></a></li>";
            }
            elseif($this->callback) {
                return "<li title=\"{$this->description}\" id=\"{$this->id}\" data-callback=\"{$this->callback}\" class=\"fpcm-menu-top-level1 fpcm-notification-item\"><a href=\"#\"><span class=\"{$this->icon}{$this->class}\" title=\"{$this->description}\"></span></a></li>";
            }
            
            return "<li title=\"{$this->description}\" id=\"{$this->id}\" class=\"fpcm-menu-top-level1 fpcm-notification-item\"><span class=\"{$this->icon}{$this->class}\" title=\"{$this->description}\"></span></li>";
        }

    }
