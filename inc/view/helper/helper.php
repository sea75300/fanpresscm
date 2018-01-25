<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper;
    
    /**
     * Abstract view helper object
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * 
     * @property string $name
     * @property string $id
     */
    abstract class helper {

        /**
         * Element data
         * @var array
         */
        protected $data     = [];

        /**
         * Element ID
         * @var string
         */
        protected $id       = '';

        /**
         * Element name
         * @var string
         */
        protected $name     = '';

        /**
         * CSS class string
         * @var string
         */
        protected $class    = '';

        /**
         * Element is readonly
         * @var boolean
         */
        protected $readonly = false;

        /**
         * Element prefix
         * @var string
         */
        protected $prefix   = '';

        /**
         *
         * @var \fpcm\classes\language
         */
        protected $language;

        /**
         * Flag if elemtn string was return by @see __toString
         * @var boolean
         */
        protected $returned = false;

        /**
         * Konstruktor
         * @param string $name
         * @param string $id
         */
        final public function __construct($name, $id = '')
        {
            if (!is_string($name) || !is_string($id)) {
                trigger_error('Invalid view helper params found in name or id');
                return false;
            }

            $this->init();
            
            $this->name     = $this->prefix. ucfirst($name);
            $this->id       = trim($id) ? $id : $this->getCleanName();
            
            $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language');
        }

        /**
         * 
         * @return string
         */
        final public function __toString()
        {
            $this->returned = true;            
            return $this->getString();
        }

        /**
         * 
         * @return void
         */
        final public function __destruct()
        {
            if ($this->returned) {
                return;
            }

            print $this->getString();
        }

        /**
         * Name cleanup from bracket, etc.
         * @return string
         */
        final protected function getCleanName()
        {
            return trim(str_replace(['[','(',')',']'], '', $this->name));
        }

        /**
         * Returns name and ID string
         * @param string $prefix
         * @return string
         */
        final protected function getNameIdString()
        {
            return "name=\"{$this->name}\" id=\"{$this->name}\" ";
        }
        
        public function setClass($class) {
            $this->class .= $class;
            return $this;
        }

        /**
         * Return class string
         * @return string
         */
        protected function getClassString()
        {
            return "class=\"{$this->class}\" ";
        }

        /**
         * 
         * @param boolean $readonly
         * @return $this
         */
        public function setReadonly($readonly)
        {
            $this->readonly = (bool) $readonly;
            return $this;
        }

        /**
         * Optional init function
         * @return void
         */
        protected function init()
        {
            return;
        }

        /**
         * Return element string
         * @return string
         */
        abstract protected function getString();
    }
?>