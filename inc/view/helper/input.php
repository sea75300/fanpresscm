<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper;
    
    /**
     * Button view helper object
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    abstract class input extends helper {

        /**
         * Input type
         * @var string
         */
        protected $type     = '';

        /**
         * Input icon
         * @var string
         */
        protected $icon     = '';

        /**
         * Input label
         * @var string
         */
        protected $text     = '';

        /**
         * Element value
         * @var string
         */
        protected $value    = '';

        /**
         * Maximum input lenght
         * @var int
         */
        protected $maxlenght = 255;

        /**
         * Use label text as placeholder
         * @var string
         */
        protected $placeholder = false;

        /**
         * Return element string
         * @return string
         */
        protected function getString()
        {
            return implode(' ', [
                
                $this->useWrapper   ? "<div class=\"fpcm-ui-input-wrapper {$this->wrapperClass}\"><div class=\"fpcm-ui-input-wrapper-inner\">" : '',
                $this->placeholder  ? ($this->icon ? $this->icon : '')
                                    : ( "<label>".($this->icon ? $this->icon : '')."<span class=\"fpcm-ui-label\">{$this->text}</span></label>" ),

                "<input type=\"{$this->type}\"",
                "{$this->getNameIdString()}{$this->getClassString()}",
                $this->readonly     ? "readonly" : '',
                "value=\"{$this->value}\"",
                "maxlength=\"{$this->maxlenght}\"",
                $this->placeholder  ? "placeholder=\"{$this->text}\"" : '',
                ">",
                $this->useWrapper ? "</div></div>" : '',
            ]);
        }
        
        /**
         * Set input value
         * @param mixed $value
         * @param int $escapeMode
         * @return $this
         */
        public function setValue($value, $escapeMode = null)
        {
            $this->value = self::escapeVal($value,$escapeMode);
            return $this;
        }

        
        /**
         * Set button icon
         * @param string $icon
         * @return $this
         */
        public function setIcon($icon)
        {
            $this->icon = "<span class=\"fpcm-ui-icon {$icon}\"></span> ";
            return $this;
        }
        
        /**
         * Set button description
         * @param string $text
         * @param array $params
         * @return $this
         */
        public function setText($text, $params = [])
        {
            $this->text = $this->language->translate(strtoupper($text), $params);
            return $this;
        }

        /**
         * Set max lenght
         * @param int $maxlenght
         * @return $this
         */
        public function setMaxlenght($maxlenght)
        {
            $this->maxlenght = (int) $maxlenght;
            return $this;
        }

        /**
         * Use label text as placeholder
         * @param bool $placeholder
         * @return $this
         */
        public function setPlaceholder($placeholder)
        {
            $this->placeholder = (bool) $placeholder;
            return $this;
        }

        /**
         * Escapes given values
         * @param string $value
         * @param int $mode
         * @return void
         */
        public static function escapeVal($value, $mode = null) {
            return htmlentities($value, ($mode !== null ? (int) $mode : ENT_COMPAT | ENT_HTML5));
        }

    }
?>