<?php
    /**
     * Dashboard container model
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * Dashboard container model base
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class dashcontainer extends model implements \fpcm\model\interfaces\dashcontainer {

        /**
         * Stanard-Dashboard-Conatiner-Cache-Module
         * @since FPCM 3.4
         */
        const CACHE_M0DULE_DASHBOARD = 'dashboard';

        /**
         * Container-Name
         * @var string
         */
        protected $name     = '';
        
        /**
         * Überschrift in Container-Ausgabe
         * @var string
         */
        protected $headline = '';

        /**
         * Inhalt des Containers in Ausgabe
         * @var string
         */
        protected $content  = '';
        
        /**
         * Breite des Containers
         * * true = big
         * * false = small
         * @var bool
         */
        protected $width    = false;
        
        /**
         * Höhe des Containers
         * * 0 = small
         * * 1 = middle
         * * 2 = big
         * @var int
         */
        protected $height   = 0;
        
        /**
         * ggf. nötige Container-Berechtigungen
         * @var array
         */
        protected $checkPermissions = [];

        /**
         * Container-Position
         * @var int 
         */
        protected $position = 0;

        /**
         * Container-Name zurückgeben
         * @return string
         */
        public function getName() {
            return $this->name;
        }

        /**
         * Container-Kopfzeile zurückgeben
         * @return string
         */
        public function getHeadline() {
            return $this->headline;
        }

        /**
         * Container-Conhalt zurückgeben
         * @return string
         */
        public function getContent() {
            return $this->content;
        }

        /**
         * Container-Breite-CSS-Klasse (big/small) zurückgeben
         * @return string
         */
        final public function getWidth() {
            return $this->width ? 'big' : 'small';
        }

        /**
         * Container-Höhe-Klasse (big/middle/small) zurückgeben
         * @return string
         */
        public function getHeight() {
            return $this->height == 2 ? 'big' : ($this->height == 1 ? 'middle' : 'small');
        }

        /**
         * Container-Position zurückgeben
         * @return string
         */
        public function getPosition() {
            return $this->position;
        }

        /**
         * Container-Berechtigungen, die geprüft werden müssen, zurückgeben
         * @return string
         */
        public function getPermissions() {
            return $this->checkPermissions;
        }
        
        /**
         * Gibt Liste mit zu ladenden Javascript-Dateien zurück
         * @return array
         * @since FPCM 3.1.3
         */
        public function getJavascriptFiles() {
            return [];
        }

        /**
         * Gibt benötigte Javascript-Variablen zurück
         * @return array
         * @since FPCM 3.1.3
         */
        public function getJavascriptVars() {
            return [];
        }

        /**
         * Gibt Liste mit zu Variablen zurück, welche an Dashboard-Controller-View übergeben werden sollen
         * @return array
         * @since FPCM 3.1.3
         */
        public function getControllerViewVars() {
            return [];
        }
        
        /**
         * container-Objekt via print/echo ausgeben
         * @return string
         */
        public function __toString() {

            $html   = [];
            $html[] = '<div class="fpcm-dashboard-container fpcm-dashboard-container-'.$this->getName().' fpcm-dashboard-container-width-'.$this->getWidth().' fpcm-dashboard-container-height-'.$this->getHeight().'">';
            $html[] = ' <div class="fpcm-dashboard-container-inner ui-widget-content ui-corner-all ui-state-normal">';
            $html[] = '     <div class="fpcm-dashboard-container-header">';
            $html[] = '         <h3 class="fpcm-dashboard-container-headline ui-corner-top ui-corner-all">'.$this->getHeadline().'</h3>';
            $html[] = '     </div>';
            $html[] = '     <div class="fpcm-dashboard-container-content-wrapper">';
            $html[] = '         <div class="fpcm-dashboard-container-content">'.$this->getContent().'</div>';
            $html[] = '     </div>';
            $html[] = ' </div>';
            $html[] = '</div>';
            
            return implode(PHP_EOL, $html);
        }
                
        /**
         * Unused
         * @return void
         */
        public function delete() {
            return;
        }

        /**
         * Unused
         * @return void
         */
        protected function init() {
            return;
        }

        /**
         * Unused
         * @return void
         */
        public function save() {
            return;
        }

        /**
         * Unused
         * @return void
         */
        public function update() {
            return;
        }

        
    }
?>