<?php
    /**
     * Public tweet template file object
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\pubtemplates;

    /**
     * Tweet Template Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class tweet extends template {

        /**
         * Template-Platzhalter
         * @var array
         */
        protected $replacementTags = array(
            '{{headline}}'   => '',
            '{{author}}'     => '',
            '{{date}}'       => '',
            '{{changeDate}}' => '',
            '{{permaLink}}'  => '',
            '{{shortLink}}'  => ''
        );     
        
        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct('tweet.html', \fpcm\classes\baseconfig::$stylesDir.'common/');
        }
        
        /**
         * Parst Template-Platzhalter
         * @return boolean
         */        
        public function parse() {

            if (!count($this->replacementTags) || !$this->content) return false;

            $this->replacementTags = $this->events->runEvent('parseTemplateTweet', $this->replacementTags);
            
            $content = $this->content;
            $tags    = array_merge($this->replacementInternal, $this->replacementTags);
            foreach ($tags as $replacement => $value) {                
                $replacement = explode(':', $replacement);
                $content = str_replace($replacement, $value, $content);
            }            
            
            return $content;
        }
        
        /**
         * Speichert Template in Dateisystem
         * @return boolean
         */
        public function save() {
            $this->content = strip_tags($this->content);
            
            $this->content = $this->events->runEvent('templateSave', array('file' => $this->fullpath, 'content' => $this->content))['content'];
            
            return parent::save();
        }
    }
?>