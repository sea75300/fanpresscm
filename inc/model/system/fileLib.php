<?php
    /**
     * View file lib
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
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
            'inc/lib/jquery-ui/jquery-ui.min.css',
            'inc/lib/fancybox/jquery.fancybox.min.css',
            'inc/lib/font-awesome/css/font-awesome.min.css',
            'core/theme/style.php'
        );

        /**
         * FPCM JS library
         * @var array
         */
        private $jslib = array(
            'inc/lib/jquery/jquery-3.2.1.min.js',
            'inc/lib/jquery-ui/jquery-ui.min.js',
            'inc/lib/fancybox/jquery.fancybox.min.js',
            'core/js/script.php'
        );

        /**
         * FPCM Code Mirror JS library
         * @var array
         */
        private $cmJsFiles = array(
            'inc/lib/codemirror/lib/codemirror.js',
            'inc/lib/codemirror/addon/selection/active-line.js',
            'inc/lib/codemirror/addon/edit/matchbrackets.js',
            'inc/lib/codemirror/addon/edit/matchtags.js',
            'inc/lib/codemirror/addon/edit/closetag.js',
            'inc/lib/codemirror/addon/fold/xml-fold.js',
            'inc/lib/codemirror/addon/hint/show-hint.js',
            'inc/lib/codemirror/addon/hint/xml-hint.js',
            'inc/lib/codemirror/addon/hint/html-hint.js',
            'inc/lib/codemirror/addon/runmode/runmode.js',
            'inc/lib/codemirror/addon/runmode/colorize.js',
            'inc/lib/codemirror/mode/xml/xml.js',
            'inc/lib/codemirror/mode/javascript/javascript.js',
            'inc/lib/codemirror/mode/css/css.js',
            'inc/lib/codemirror/mode/htmlmixed/htmlmixed.js'
        );

        /**
         * FPCM Code Mirror CSS library
         * @var array
         */
        private $cmCssFiles = array(
            'inc/lib/codemirror/lib/codemirror.css',
            'inc/lib/codemirror/theme/fpcm.css',
            'inc/lib/codemirror/addon/hint/show-hint.css'
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
         * Gibt Code Mirror JS library zurück
         * @return array
         */        
        public function getCmJsFiles() {
            return array_map(array($this, 'addRootPath'), $this->cmJsFiles);
        }

        /**
         * Gibt Code Mirror CSS library zurück
         * @return array
         */        
        public function getCmCssFiles() {
            return array_map(array($this, 'addRootPath'), $this->cmCssFiles);
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
                    \fpcm\classes\loader::libGetFileUrl('jquery', 'jquery-3.2.1.min.js'),
                    \fpcm\classes\baseconfig::$rootPath.'js/fpcm.js'
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
            return \fpcm\classes\baseconfig::$rootPath.$path;
        }
        
        
    }
