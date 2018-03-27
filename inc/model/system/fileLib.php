<?php
    /**
     * View file lib
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\system;

    /**
     * View File library Loader Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class fileLib extends \fpcm\model\abstracts\staticModel {
        
        /**
         * FPCM CSS library
         * @var array
         */
        private $csslib = array(
            'lib/jquery-ui/jquery-ui.min.css',
            'lib/fancybox/jquery.fancybox.min.css',
            'lib/font-awesome/css/fontawesome-all.min.css',
            'lib/bootstrap/bootstrap-grid.min.css',
            'core/theme/style.php'
        );

        /**
         * FPCM JS library
         * @var array
         */
        private $jslib = array(
            'lib/jquery/jquery-3.3.1.min.js',
            'lib/jquery-ui/jquery-ui.min.js',
            'lib/fancybox/jquery.fancybox.min.js',
            'core/js/script.php'
        );
        
        /**
         * Gibt CSS library zurück
         * @return array
         */
        public function getCsslib() {
            $modulesFiles = $this->events->runEvent('themeAddCssFiles');
            $modulesFiles = $modulesFiles ? $modulesFiles : array();
            
            return array_map(array($this, 'addRootPath'), array_merge($this->csslib, $modulesFiles));
        }

        /**
         * Gibt JS library zurück
         * @return array
         */
        public function getJslib() {
            $modulesFiles = $this->events->runEvent('themeAddJsFiles');
            $modulesFiles = $modulesFiles ? $modulesFiles : array();
            
            return array_map(array($this, 'addRootPath'), array_merge($this->jslib, $modulesFiles));
        }
        
        /**
         * Gibt CSS library für Public Controller zurück
         * @return array
         */
        public function getCssPubliclib() {
            $modulesFiles = $this->events->runEvent('publicAddCssFiles');
            $modulesFiles = $modulesFiles ? $modulesFiles : array();
            
            return array_merge(array($this->config->system_css_path), $modulesFiles);
        }
        
        /**
         * Gibt JS library für Public Controller zurück
         * @return array
         */
        public function getJsPubliclib() {
            $modulesFiles = $this->events->runEvent('publicAddJsFiles');
            $modulesFiles = $modulesFiles ? $modulesFiles : array();
            
            return array_merge(
                array(
                    \fpcm\classes\loader::libGetFileUrl('jquery', 'jquery-3.3.1.min.js'),
                    \fpcm\classes\dirs::getRootUrl('js/fpcm.js')
                ),
                $modulesFiles
            );
        }

        /**
         * Kombinierte library-Pfad mit Root Pfad
         * @param string $path
         * @return string
         */
        private function addRootPath($path) {
            return \fpcm\classes\dirs::getRootUrl($path);
        }
        
        
    }
