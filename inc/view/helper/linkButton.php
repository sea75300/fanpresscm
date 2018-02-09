<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper;
    
    /**
     * Link button view helper object
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    class linkButton extends button {

        /**
         * Link URL
         * @var string
         */
        protected $url = '';

        /**
         * Link URL target
         * @var string
         */
        protected $target = '';


        /**
         * Optional init function
         * @return void
         */
        protected function init()
        {
            $this->class = 'fpcm-ui-button fpcm-ui-button-link';
        }

        /**
         * Return element string
         * @return string
         */
        protected function getString()
        {
            if ($this->readonly) {
                $this->class = str_replace('fpcm-loader', '', $this->class);
                return implode(' ', [
                    "<span",
                    $this->getClassString(),
                    ($this->iconOnly ? "title=\"{$this->text}\">{$this->getIconString()}" : ">{$this->getIconString()}{$this->getDescriptionTextString()}"),
                    '</span>'
                ]);                
            }
            
            return implode(' ', [
                "<a href=\"{$this->url}\"",
                $this->target ? "target=\"{$this->target}\"" : '',
                $this->getNameIdString(),
                $this->getClassString(),
                $this->getDataString(),
                ($this->iconOnly ? "title=\"{$this->text}\">{$this->getIconString()}" : ">{$this->getIconString()}{$this->getDescriptionTextString()}"),
                '</a>'
            ]);

        }

        /**
         * Set link url
         * @param string $url
         * @return $this
         */
        public function setUrl($url)
        {
            $this->url = $url;
            return $this;
        }

        /**
         * Set link target
         * @param string $target
         * @return $this
         */
        public function setTarget($target) {
            $this->target = $target;
            return $this;
        }

    }
?>