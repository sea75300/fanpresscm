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
    trait valueHelper {

        /**
         * Element value
         * @var string
         */
        protected $value    = '';
        
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