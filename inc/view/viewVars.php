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
     * @property array  $navigation
     * @property array  $navigationActiveModule
     * @property array  $messages
     * @property array  $filesJs
     * @property array  $filesCss
     * @property array  $varsJs
     * @property bool   $loggedIn
     * @property \fpcm\classes\language $lang
     * @property \fpcm\model\users\author $currentUser
     */ 
    class viewVars {

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
         * Return view include path
         * @param string $view
         * @return string
         */
        public function getIncludePath($view)
        {
            return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, $view);
        }
    }
?>