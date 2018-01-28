<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view\helper;
    
    /**
     * Select menu view helper object
     * 
     * @package fpcm\view\helper
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    class select extends helper {

        /* @var Auto-add empty first option */
        const FIRST_OPTION_EMPTY        = -1;

        /* @var Auto-add first action with please select text */
        const FIRST_OPTION_PLEASESELECT = -2;

        /* @var Do not auto-add first option */
        const FIRST_OPTION_DISABLED     = -3;
        
        use traits\iconHelper,
            traits\valueHelper,
            traits\selectedHelper;

        /**
         * Select options
         * @var array
         */
        protected $options = [];

        /**
         *
         * @var int
         */
        protected $firstOption;

        /**
         * Return element string
         * @return string
         */
        protected function getString()
        {
            return implode(' ', [
                "<select",
                $this->getNameIdString(),
                $this->getClassString(),
                $this->getReadonlyString(),
                $this->getDataString(),
                ">",
                $this->getOptionsString(),
                "</select>",
            ]);
        }

        /**
         * Optional init function
         * @return void
         */
        protected function init()
        {
            $this->class        = 'fpcm-ui-input-select';
            $this->firstOption  = self::FIRST_OPTION_PLEASESELECT;
        }

        /**
         * Set options for selectbox
         * @param array $options
         * @return $this
         */
        public function setOptions(array $options)
        {
            $this->options = $options;
            return $this;
        }

        /**
         * Select auto-added first option, values are fpcm\view\helper\select::FIRST_OPTION_EMPTY, fpcm\view\helper\select::FIRST_OPTION_PLEASESELECT, fpcm\view\helper\select::FIRST_OPTION_DISABLED
         * @param int $firstOption
         * @return $this
         */
        public function setFirstOption($firstOption)
        {
            $this->firstOption = (int) $firstOption;
            return $this;
        }

        /**
         * Create options string
         * @return string
         */
        protected function getOptionsString()
        {   
            if (!count($this->options)) {
                return '';
            }

            $return = [];
            
            switch ($this->firstOption) {
                case self::FIRST_OPTION_EMPTY :
                    $return[] = "<option {$this->getValueString()} {$this->getSelectedString()}></option>";
                    break;
                case self::FIRST_OPTION_PLEASESELECT :
                    $return[] = "<option {$this->getValueString()} {$this->getSelectedString()}>{$this->language->translate('GLOBAL_SELECT')}</option>";
                    break;
            }

            $this->value = '';
            
            foreach ($this->options as $key => $value) {
                $this->value = $this->escapeVal($value);
                $key         = $this->escapeVal($key);
                $return[]    = "<option {$this->getValueString()} {$this->getSelectedString()}>{$this->language->translate($key)}</option>";
            }
            
            return implode(PHP_EOL, $return);
        }

    }
?>