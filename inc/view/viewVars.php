<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view;
    
    /**
     * Defaul view vars
     * 
     * @package fpcm\view
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * 
     * @property string $version
     * @property string $self
     * @property string $dateTimeMask
     * @property string $dateTimeZone
     * @property string $currentModule
     * @property string $themePath
     * @property string $frontEndLink
     * @property string $basePath
     * @property string $notificationString
     * @property string $helpLink
     * @property string $formActionTarget
     * @property string $langCode
     * 
     * @property array  $navigation
     * @property array  $navigationActiveModule
     * @property array  $messages
     * @property array  $filesJs
     * @property array  $filesCss
     * @property array  $varsJs
     * @property array  $buttons
     * @property bool   $loggedIn
     * @property \fpcm\classes\language $lang
     * @property \fpcm\model\users\author $currentUser
     * 
     * @method helper\badge         badge(string $name [, string $id])
     * @method helper\boolSelect    boolSelect(string $name [, string $id])
     * @method helper\boolToText    boolToText(string $name [, string $id])
     * @method helper\button        button(string $name [, string $id])
     * @method helper\checkbox      checkbox(string $name [, string $id])
     * @method helper\clearArticleCacheButton clearArticleCacheButton(string $name [, string $id])
     * @method helper\dateText      dateText(int $timespan [, string $format])
     * @method helper\deleteButton  deleteButton(string $name [, string $id])
     * @method helper\editButton    editButton(string $name [, string $id])
     * @method helper\escape        escape(mixed $value [, int $mode])
     * @method helper\hiddenInput   hiddenInput(string $name [, string $id])
     * @method helper\icon          icon(string $icon)
     * @method helper\input         input(string $name [, string $id])
     * @method helper\linkButton    linkButton(string $name [, string $id])
     * @method helper\openButton    openButton(string $name [, string $id])
     * @method helper\passwordInput passwordInput(string $name [, string $id])
     * @method helper\radiobutton   radiobutton(string $name [, string $id])
     * @method helper\resetButton   resetButton(string $name [, string $id])
     * @method helper\saveButton    saveButton(string $name [, string $id])
     * @method helper\select        select(string $name [, string $id])
     * @method helper\shorthelpButton shorthelpButton(string $name [, string $id])
     * @method helper\submitButton  submitButton(string $name [, string $id])
     * @method helper\textInput     textInput(string $name [, string $id])
     * @method helper\textarea      textarea(string $name [, string $id])
     */ 
    class viewVars {
        
        use helper\traits\escapeHelper;

        /**
         * Var values
         * @var array
         */
        private $vars = [];

        /**
         * Magic Getter
         * @param string $name
         * @return mixed|null
         */
        public function __get($name)
        {
            return isset($this->vars[$name]) ? $this->vars[$name] : null;
        }

        /**
         * Magic setter
         * @param string $name
         * @param mixed $value
         */
        public function __set($name, $value)
        {
            $this->vars[$name] = $value;
        }

        /**
         * Return a view helper object
         * @param string $name
         * @param array $arguments
         * @return helper
         */
        public function __call($name, array $arguments)
        {
            $prefix      = 'fpcm\\view\\helper\\';
            $helperClass = $prefix.$name;
            if (!class_exists($helperClass)) {
                trigger_error('View helper '.$name.' does not exists.');
                exit('View helper '.$name.' does not exists.');
            }
            
            
            $whiteList = ['dateText', 'escape'];

            if (!in_array($name, $whiteList) && (empty($arguments[0]) || !is_string($arguments[0])) ) {
                trigger_error('Invalid view helper params found for name of '.$name);
                exit('Invalid view helper params found for name of '.$name);
            }

            return new $helperClass($arguments[0], (isset($arguments[1]) ? $arguments[1] : ''));
        }
        
        /**
         * Return view include path
         * @param string $view
         * @return string
         */
        public function getIncludePath($view)
        {
            $path = \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, $view);
            
            if (!file_exists($path)) {
                trigger_error('Include view path '.$view.' does not exists');
                return '';
            }

            return $path;
        }
        
        /**
         * Write result of language variable
         * @param string $var
         * @param array $param
         */
        public function write($var, array $params = [])
        {
            print $this->translate($var, $params);
        }
        
        /**
         * Returns result of language variable
         * @param type $var
         * @param array $params
         * @return string
         */
        public function translate($var, array $params = [])
        {
            return $this->lang->translate($var, $params);
        }
        
        /**
         * Returns language code
         * @param type $var
         * @param array $params
         * @return string
         */
        public function writeMonth($monthId)
        {
            $this->lang->writeMonth($monthId);
        }

    }
?>