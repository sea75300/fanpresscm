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
 * @copyright (c) 2011-2020, Stefan Seehafer
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
 * @property string $bodyClass
 * @property string $toolbarItemRight
 * @property string $toolbarArea
 * @property string $ipAddress
 * @property string $includeForms
 * @property int    $loginTime
 * 
 * @property \fpcm\model\permissions\permissions  $permissions
 * @property \fpcm\model\theme\navigationList  $navigation
 * 
 * @property array  $navigationActiveModule
 * @property array  $messages
 * @property array  $filesJs
 * @property array  $filesJsLate
 * @property array  $filesCss
 * @property array  $varsJs
 * @property array  $buttons
 * @property bool   $loggedIn
 * @property bool   $fullWrapper
 * @property bool   $showPageToken
 * @property \fpcm\model\users\author $currentUser
 * @property helper\pager $pager
 * 
 * @method helper\badge             badge(string $name , string $id)
 * @method helper\boolSelect        boolSelect(string $name , string $id)
 * @method helper\boolToText        boolToText(string $name , string $id)
 * @method helper\button            button(string $name , string $id)
 * @method helper\checkbox          checkbox(string $name , string $id)
 * @method helper\clearArticleCacheButton clearArticleCacheButton(string $name , string $id)
 * @method helper\dateText          dateText(int $timespan , string $format)
 * @method helper\dateTimeInput     dateTimeInput(string $name , string $id)
 * @method helper\deleteButton      deleteButton(string $name , string $id)
 * @method helper\editButton        editButton(string $name , string $id)
 * @method helper\escape            escape(mixed $value , int $mode)
 * @method helper\hiddenInput       hiddenInput(string $name , string $id)
 * @method helper\input             input(string $name , string $id)
 * @method helper\linkButton        linkButton(string $name , string $id)
 * @method helper\openButton        openButton(string $name , string $id)
 * @method helper\pager             pager(string $actionLink, int $currentPage, int $currentPageItemsCount, int $itemsPerPage, int $maxItemCount)
 * @method helper\passwordInput     passwordInput(string $name , string $id)
 * @method helper\radiobutton       radiobutton(string $name , string $id)
 * @method helper\radiocheck        radiocheck(string $name , string $id)
 * @method helper\resetButton       resetButton(string $name , string $id)
 * @method helper\saveButton        saveButton(string $name , string $id)
 * @method helper\select            select(string $name , string $id)
 * @method helper\shorthelpButton   shorthelpButton(string $name , string $id)
 * @method helper\submitButton      submitButton(string $name , string $id)
 * @method helper\tabItem           tabItem(string $name , string $id)
 * @method helper\textInput         textInput(string $name , string $id)
 * @method helper\numberInput       numberInput(string $name , string $id)
 * @method helper\textarea          textarea(string $name , string $id)
 * @method helper\dropdown          dropdown(string $name , string $id)
 * @method helper\dropdownItem      dropdownItem(string $name , string $id)
 * @method helper\rangeInput        rangeInput(string $name , string $id)
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
        return $this->vars[$name] ?? null;
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
        $prefix = 'fpcm\\view\\helper\\';
        $helperClass = $prefix . $name;
        if (!class_exists($helperClass)) {
            trigger_error('View helper ' . $name . ' does not exists in ' . $helperClass);
            exit('View helper "' . $name . '" does not exists ' . $helperClass);
        }
        
        $whiteList = ['dateText', 'escape', 'icon'];

        if (!in_array($name, $whiteList) && (empty($arguments[0]) || !is_string($arguments[0]))) {
            trigger_error('Invalid view helper params found for name of ' . $name);
            exit('Invalid view helper params found for name of ' . $name);
        }

        return new $helperClass($arguments[0], (isset($arguments[1]) ? $arguments[1] : ''));
    }

    /**
     * Return view include path
     * @param string $view
     * @return string
     */
    public function getIncludePath($view) : string
    {
        $path = realpath(\fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, $view));
        if (!trim($path) || strpos($path, \fpcm\classes\dirs::getFullDirPath('') ) !== 0 || !file_exists($path)) {
            trigger_error('Include view path ' . $view . ' does not exists');
            return '';
        }

        return $path;
    }
    
    /**
     * 
     * Write result of language variable
     * @param string $var
     * @param array $params
     */
    public function write($var, array $params = [])
    {
        print $this->translate($var, $params);
    }
    
    /**
     * 
     * Returns result of language variable
     * @param string $var
     * @param array $params
     * @return string
     */
    public function translate($var, array $params = [])
    {
        return $this->lang->translate($var, $params);
    }

    /**
     * Displays month name by ID
     * @param int $monthId
     * @see \fpcm\classes\language::writeMonth
     */
    public function writeMonth($monthId)
    {
        $this->lang->writeMonth($monthId);
    }
    
    /**
     * Returns controller link
     * @param string $controller
     * @param array $params
     * @return string
     * @see \fpcm\classes\tools::getControllerLink
     * @since 4.2
     */
    public function controllerLink(string $controller, array $params = []) : string
    {
        return \fpcm\classes\tools::getControllerLink($controller, $params);
    }

    /**
     * Calculates bytes sizes 
     * @param int $value
     * @param int $decimals
     * @param string $delimDec
     * @param string $delimTousands
     * @return string
     * @see \fpcm\classes\tools::calcSize
     * @since 4.2
     */
    public function calcSize(int $value, int $decimals = 2, string $delimDec = ',', string $delimTousands = '.') : string
    {
        return \fpcm\classes\tools::calcSize($value, $decimals, $delimDec, $delimTousands);
    }

    /**
     * Returns page token field view helper object
     * @return \fpcm\view\helper\pageTokenField
     * @since 4.3 as own funktion
     */
    final public function pageTokenField() : helper\pageTokenField
    {
        return new helper\pageTokenField();
    }

    /**
     * Returns icon view helper object
     * @param string $icon
     * @param string $prefix
     * @param bool $useFa
     * @return \fpcm\view\helper\icon
     * @since 4.3 as own funktion
     */
    final public function icon(string $icon, string $prefix = 'fa', bool $useFa = true) : helper\icon
    {
        return new helper\icon($icon, $prefix, $useFa);
    }

    /**
     * Prints default box values
     * @return void
     * @since 4.5
     */
    final public function defaultBoxHalf() : void
    {
        print 'col-12 col-md-6';
    }

}

?>