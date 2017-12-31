<?php
    /**
     * Public latest news template file object
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\pubtemplates;

    /**
     * Latest News Template Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class latestnews extends template {

        /**
         * Template-Platzhalter
         * @var array
         */
        protected $replacementTags = array(
            '{{headline}}'                      => '',
            '{{author}}'                        => '',
            '{{date}}'                          => '',
            '{{permaLink}}:{{/permaLink}}'      => '',
            '{{commentLink}}:{{/commentLink}}'  => ''
        );      

        /**
         * Konstruktor
         * @param string $fileName
         */
        public function __construct($fileName = null) {
            
            if (!$fileName) {
                $fileName = 'latest';
            }

            parent::__construct($fileName.'.html', \fpcm\classes\baseconfig::$stylesDir.'common/');
        }
        
        /**
         * Parst Template-Platzhalter
         * @return boolean
         */        
        public function parse() {

            if (!count($this->replacementTags) || !$this->content) return false;

            $content = $this->content;
            $tags    = array_merge($this->replacementInternal, $this->replacementTags);
            foreach ($tags as $replacement => $value) {
                
                $replacement = explode(':', $replacement);                
                $values = [];
                
                switch ($replacement[0]) {
                    case '{{permaLink}}':
                        $keys   = $replacement;                        
                        $values = array("<a href=\"$value\" class=\"fpcm-pub-permalink\">", '</a>');
                        break;
                    case '{{commentLink}}':
                        $keys   = $replacement;                        
                        $values = array("<a href=\"$value\" class=\"fpcm-pub-commentlink\">", '</a>');
                        break;
                    default:
                        $keys   = $replacement;                        
                        $values = array($value);                        
                        break;
                }
                
                $content = str_replace($keys, $values, $content);
            }            
            
            return $content;
        }      

    }
?>