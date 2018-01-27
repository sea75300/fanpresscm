<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper\traits;
    
    /**
     * View helper with Icon
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    trait iconHelper {

        /**
         * Input icon
         * @var string
         */
        protected $icon     = '';

        /**
         * Button text
         * @var string
         */
        protected $iconOnly = false;

        
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