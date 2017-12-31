<?php
    /**
     * Public comment form template file object
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\pubtemplates;

    /**
     * Kommentar Form Template Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class commentform extends template {

        /**
         * Template-Platzhalter
         * @var array
         */
        protected $replacementTags = array(
            '{{formHeadline}}'                   => '',
            '{{submitUrl}}'                      => '',
            '{{nameDescription}}'                => '',
            '{{nameField}}'                      => '',
            '{{emailDescription}}'               => '',
            '{{emailField}}'                     => '',
            '{{websiteDescription}}'             => '',
            '{{websiteField}}'                   => '',
            '{{textfield}}'                      => '',
            '{{smileysDescription}}'             => '',
            '{{smileys}}'                        => '',
            '{{tags}}'                           => '',
            '{{spampluginQuestion}}'             => '',
            '{{spampluginField}}'                => '',
            '{{privateCheckbox}}'                => '',
            '{{submitButton}}'                   => '',
            '{{resetButton}}'                    => ''
        );      
        
        /**
         * Konstruktor
         * @param string $fileName
         */
        public function __construct($fileName = null) {
            
            if (!$fileName) {
                $fileName = 'comment_form';
            }

            parent::__construct($fileName.'.html', \fpcm\classes\baseconfig::$stylesDir.'common/');
        }
        
        /**
         * Parst Template-Platzhalter
         * @return boolean
         */        
        public function parse() {
            if (!count($this->replacementTags) || !$this->content) return false;
            $this->replacementTags = $this->events->runEvent('parseTemplateCommentForm', $this->replacementTags);
            $tags = array_merge($this->replacementInternal, $this->replacementTags);
            return str_replace(array_keys($tags), array_values($tags), $this->content);
        }
        
        /**
         * Speichert Template in Dateisystem
         * @return boolean
         */
        public function save() {
            if (!$this->exists() || !$this->content) return false;
            
            $this->content = $this->events->runEvent('templateSave', array('file' => $this->fullpath, 'content' => $this->content))['content'];
            
            if (strpos($this->content, '{{submitUrl}}') === false) {
                trigger_error('Unable to update comment form template, {{submitUrl}} replacement is missing!');
                return null;
            }
            
            if (!file_put_contents($this->fullpath, $this->content)) {
                trigger_error('Unable to update template '.$this->fullpath);
                return false;
            }
            
            return true;
        }

    }
?>