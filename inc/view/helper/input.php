<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper;
    
    /**
     * Imput view helper object
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    abstract class input extends helper {

        use traits\iconHelper,
            traits\valueHelper,
            traits\typeHelper;

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
                $this->placeholder  ? $this->getIconString()
                                    : ( "<label>{$this->getIconString()}{$this->getDescriptionTextString()}</label>" ),
                "<input type=\"{$this->type}\"",
                $this->getNameIdString(),
                $this->getClassString(),
                $this->getReadonlyString(),
                $this->getValueString(),
                "maxlength=\"{$this->maxlenght}\"",
                $this->getPlaceholderString(),
                $this->getDataString(),
                ">",
                $this->useWrapper ? "</div></div>" : '',
            ]);
        }

        /**
         * Optional init function
         * @return void
         */
        protected function init()
        {
            $this->class  = 'fpcm-ui-input';
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
        
        protected function getPlaceholderString()
        {
            return ($this->placeholder  ? "placeholder=\"{$this->text}\"" : '');
        }

    }
?>