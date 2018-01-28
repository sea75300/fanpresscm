<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper\traits;
    
    /**
     * View helper with value
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    trait selectedHelper {

        /**
         * Element value
         * @var string
         */
        protected $selected    = '';
        
        /**
         * Set input value
         * @param mixed $value
         * @param int $escapeMode
         * @return $this
         */
        public function setSelected($selected)
        {
            $this->selected = $selected;
            return $this;
        }

        protected function getSelectedString()
        {
            return $this->value == $this->selected ? 'selected' : '';
        }

    }
?>