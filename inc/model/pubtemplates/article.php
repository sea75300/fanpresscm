<?php
    /**
     * Public article template file object
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\pubtemplates;

    /**
     * Article Template Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class article extends template {

        /**
         * Template-Platzhalter
         * @var array
         */
        protected $replacementTags = array(
            '{{headline}}'                      => '',
            '{{text}}'                          => '',
            '{{author}}'                        => '',
            '{{authorEmail}}'                   => '',
            '{{authorInfoText}}'                => '',
            '{{authorAvatar}}'                  => '',
            '{{date}}'                          => '',
            '{{changeDate}}'                    => '',
            '{{changeUser}}'                    => '',
            '{{statusPinned}}'                  => '',
            '{{shareButtons}}'                  => '',
            '{{categoryIcons}}'                 => '',
            '{{categoryTexts}}'                 => '',
            '{{commentCount}}'                  => '',
            '{{permaLink}}:{{/permaLink}}'      => '',
            '{{commentLink}}:{{/commentLink}}'  => '',
            '{{articleImage}}'                  => '',
            '{{sources}}'                       => ''
        );
        
        /**
         * Interne Platzhalter
         * @var array
         */
        protected $replacementInternal  = array(
            '<readmore>:</readmore>'
        );        
        
        /**
         * Kommentar-Parsner aktiv
         * @var bool
         */
        protected $commentsEnabled = true;
        
        /**
         * Tag-Kombinationen, die beseitigt werden müssen
         * @var array
         */
        protected $cleanups = array(
            '<p><readmore>' => '<readmore>',
            '</readmore></p>' => '</readmore>'
        );

        /**
         * Konstruktor
         * @param string $fileName Template-Datei unterhalb von data/styles/articles
         */
        public function __construct($fileName) {
            parent::__construct($fileName.'.html', \fpcm\classes\baseconfig::$stylesDir.'articles/');
        }
        
        /**
         * Parst Template-Platzhalter
         * @return boolean
         */        
        public function parse() {

            if (!count($this->replacementTags) || !$this->content) return false;

            $this->replacementTags = $this->events->runEvent('parseTemplateArticle', $this->replacementTags);
            
            $content = $this->content;
            $tags    = array_merge($this->replacementInternal, $this->replacementTags);
            foreach ($tags as $replacement => $value) {
                
                $replacement = explode(':', $replacement);                
                $values = [];
                
                switch ($replacement[0]) {
                    case '{{permaLink}}' :
                        $keys   = $replacement;                        
                        $values = array("<a href=\"$value\" class=\"fpcm-pub-permalink\">", '</a>');
                        break;
                    case '{{commentLink}}' :
                        $keys   = $replacement;                        
                        $values = $this->commentsEnabled ? array("<a href=\"$value\" class=\"fpcm-pub-commentlink\">", '</a>') : array('', '');
                        break;
                    case '<readmore>' :
                        $keys   = $replacement;
                        $values = array('<a href="#" class="fpcm-pub-readmore-link" id="'.$value.'">'.$this->language->translate('ARTICLES_PUBLIC_READMORE').'</a><div class="fpcm-pub-readmore-text" id="fpcm-pub-readmore-text-'.$value.'">', '</div>');
                        break;
                    case '{{sources}}' :
                        $keys   = $replacement;
                        $this->parseLinks($value, array('rel' => 'noopener noreferrer'));
                        $values = array($value);
                        break;
                    default:
                        $keys   = $replacement;                        
                        $values = array($value);                        
                        break;
                }
                
                $content = str_replace($keys, $this->cleanup($values), $content);
            }            

            return $this->parseSmileys($content);
        }
        
        /**
         * Kommentar-Parser aktivieren
         * @param bool $commentsEnabled
         */
        public function setCommentsEnabled($commentsEnabled) {
            $this->commentsEnabled = $commentsEnabled;
        }
        
        /**
         * Tags aufräumen
         * @param string $values
         * @return string
         */
        protected function cleanup($values) {            
            return str_replace(array_keys($this->cleanups), array_values($this->cleanups), $values);
        }

    }
?>