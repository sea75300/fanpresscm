<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper;
    
    /**
     * Text input view helper object
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    abstract class radiocheck extends helper {

        use traits\iconHelper,
            traits\valueHelper,
            traits\typeHelper,
            traits\selectedHelper;

        /**
         * Optional init function
         * @return void
         */
        protected function init()
        {
            $this->class  = 'fpcm-ui-input-radiocheck';
        }

        /**
         * Return element string
         * @return string
         */
        protected function getString()
        {
            return implode(' ', [
                "<label for=\"{$this->id}\">",
                $this->getIconString(),
                $this->getDescriptionTextString(),
                "<input type=\"{$this->type}\"",
                $this->getNameIdString(),
                $this->getClassString(),
                $this->getReadonlyString(),
                $this->getValueString(),
                $this->getDataString(),
                ">",
                "</label>"
            ]);
        }

        
    }
?>