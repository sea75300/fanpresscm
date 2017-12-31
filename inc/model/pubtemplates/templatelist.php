<?php
    /**
     * Template model
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\pubtemplates;

    /**
     * Template Listen Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class templatelist extends \fpcm\model\abstracts\staticModel {

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
        }

        /**
         * Gibt Liste mit allen Templates für die News/Artikel-Anzeige zurück
         * @return array
         */
        public function getArticleTemplates() {
            $templates = glob(\fpcm\classes\baseconfig::$stylesDir.'articles/*.html');

            return $this->getList($templates);
        }

        /**
         * Gibt Liste mit allen Templates für die Kommentar-Anzeige zurück
         * @return array
         */        
        public function getCommentTemplates() {
            $templates = glob(\fpcm\classes\baseconfig::$stylesDir.'comments/*.html');

            return $this->getList($templates);        
        }

        /**
         * Gibt Liste mit allen Templates für sonstige Anzeigen zurück
         * @return array
         */        
        public function getCommonTemplates() {
            $templates = glob(\fpcm\classes\baseconfig::$stylesDir.'common/*.html');

            return $this->getList($templates);        
        }        

        /**
         * Erzeugt Template-Liste
         * @param array $templates
         * @return array
         */
        private function getList($templates) {
            $templateList = [];
            foreach ($templates as $template) {

                $basename = basename($template);
                if (preg_match('/^(_preview)([0-9]+)(\.html)$/i', $basename)) {
                    continue;
                }

                $templateList[$basename] = substr($basename, 0, -5);
            }

            return $templateList;        
        }
    }
