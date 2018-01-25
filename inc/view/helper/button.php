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
    abstract class button extends helper {

        /**
         * Button type
         * @var string
         */
        protected $type     = '';

        /**
         * Button icon
         * @var string
         */
        protected $icon     = '';

        /**
         * Button text
         * @var string
         */
        protected $text     = '';

        /**
         * Button text
         * @var string
         */
        protected $iconOnly = false;

        /**
         * Return element string
         * @return string
         */
        protected function getString()
        {
            return implode('', [
                ($this->readonly ? '<span ' : "<button type=\"{$this->type}\" "),
                ($this->readonly ? $this->getClassString() : "{$this->getNameIdString()}{$this->getClassString()}"),
                ($this->iconOnly ? "title=\"{$this->text}\">{$this->icon}" : ">{$this->icon}<span>{$this->text}</span>"),
                ($this->readonly ? '</span>' : "</button>")
            ]);
        }

        /**
         * Optional init function
         * @return void
         */
        protected function init()
        {
            $this->prefix = 'btn';
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
         * Set button to display icon only
         * @param string $iconOnly
         * @return $this
         */
        public function setIconOnly($iconOnly) {
            $this->iconOnly = (bool) $iconOnly;
            return $this;
        }

    }
?>