<?php
    /**
     * FanPress CM article editor plugin base model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.1.0
     */
    namespace fpcm\model\abstracts;

    /**
     * Article editor plugin base model
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.1.0
     */ 
    abstract class articleEditor extends staticModel {

        /**
         * Dateilisten-Objekt
         * @var \fpcm\model\files\imagelist
         */
        protected $fileList;
        
        /**
         * Filelibrary-Objekt
         * @var \fpcm\model\system\fileLib
         */
        protected $fileLib;

        /**
         * Konstruktor
         */
        public function __construct() {

            parent::__construct();
            
            $this->fileList = new \fpcm\model\files\imagelist();
            $this->fileLib  = new \fpcm\model\system\fileLib();

        }

        /**
         * Pfad der Editor-Template-Datei
         * @return string
         */
        abstract public function getEditorTemplate();

        /**
         * Liefert zu ladender Javascript-Dateien für Editor zurück
         * @return array
         */ 
        abstract public function getJsFiles();

        /**
         * Liefert zu ladender CSS-Dateien für Editor zurück
         * @return array
         */
        abstract public function getCssFiles();

        /**
         * Array von Javascript-Variablen, welche in Editor-Template genutzt werden
         * @return array
         */
        abstract public function getJsVars();

        /**
         * Array von Sprachvariablen für Nutzung in Javascript
         * @return array
         * @since FPCM 3.3
         */
        abstract public function getJsLangVars();

        /**
         * Array von Variablen, welche in Editor-Template genutzt werden
         * @return array
         */
        abstract public function getViewVars();

        /**
         * Array mit Informationen u. a. für template-Plugin von TinyMCE
         * @return array
         * @since FPCM 3.3
         */
        abstract public function getTemplateDrafts();
        
        /**
         * Editor-Styles initialisieren
         * @return array
         */
        protected function getEditorStyles() {
            if (!$this->config->system_editor_css) return [];
            
            $classes = explode(PHP_EOL, $this->config->system_editor_css);
            
            $editorStyles = [];
            foreach ($classes as $class) {
                $class = trim(str_replace(array('.', '{', '}'), '', $class));                
                $editorStyles[$class] = $class;
            }
            
            return $this->events->runEvent('editorAddStyles', $editorStyles);
        }
        
        /**
         * Editor-Links initialisieren
         * @return string
         */
        public function getEditorLinks() {
            $links = $this->events->runEvent('editorAddLinks');
            if (!is_array($links) || !count($links)) return [];
            return $links;
        }
        
        /**
         * Dateiliste initialisieren
         * @return array
         */
        public function getFileList() {
            $data = [];            
            foreach ($this->fileList->getDatabaseList() as $image) {
                $data[] = array('label' => $image->getFilename(), 'value' => $image->getImageUrl());
            }

            $res = $this->events->runEvent('editorGetFileList', array('label' => 'label', 'files' => $data));
            return isset($res['files']) && count($res['files']) ? $res['files'] : array();
        }
        
        /**
         * Gibt Textpattern-Konfiguration zurück,
         * nur in TinyMCE genutzt
         * @return array
         */
        protected function getTextPatterns() {
            return [
                ['start' => '- ',   'cmd' => 'InsertUnorderedList'],
                ['start' => '* ',   'cmd' => 'InsertUnorderedList'],
                ['start' => '# ',   'cmd' => 'InsertOrderedList'],
                ['start' => '1. ',  'cmd' => 'InsertOrderedList'],
            ];
        }

    }
