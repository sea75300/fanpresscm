<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view;

/**
 * Default view object
 * 
 * @package fpcm\view
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class view {

    const INCLUDE_HEADER_FULL = 0b00001;
    const INCLUDE_HEADER_SIMPLE = 0b00010;
    const INCLUDE_HEADER_NONE = 0b00100;

    const ROOTURL_CORE_JS = '{$coreJs}';
    const ROOTURL_CORE_THEME = '{$coreTheme}';
    const ROOTURL_LIB = '{$lib}';
    const ROOTURL_UNIQUE = '{$unique}';

    const PATH_COMPONENTS = '{$components}';

    const JS_FILETYP_URL  = 0b100;
    const JS_FILETYP_FILE = 0b010;
    const JS_FILETYP_FILE_EXT = 0b001;
    const JS_FILES_CACHE = 'themejsfiles/';

    /**
     * Complete view path
     * @var string
     */
    protected $viewPath = '';

    /**
     * View file name
     * @var string
     */
    protected $viewName = '';

    /**
     * View file path hash
     * @var string
     */
    protected $viewHash = '';

    /**
     * Form action path
     * @var string
     */
    protected $formAction = '';

    /**
     * <body> CSS class
     * @var string
     * @since FPCM 4.2
     */
    protected $bodyClass = '';

    /**
     * Include header and footer in view::render
     * @var int
     */
    protected $showHeader;

    /**
     * View vars
     * @var array
     */
    protected $viewVars = [];

    /**
     * View JS files
     * @var array
     */
    protected $viewJsFiles = [];

    /**
     * Local view files in core/js
     * @var array
     * @since FPCm 4.1
     */
    protected $viewJsFilesLocal = [];

    /**
     * View CSS files
     * @var array
     */
    protected $viewCssFiles = [];

    /**
     * View messages
     * @var array
     */
    protected $messages = [];

    /**
     * View JS vars
     * @var array
     */
    protected $jsvars = [];

    /**
     * View JS language vars
     * @var array
     */
    protected $jsLangVars = [];

    /**
     * Toolbar buttons
     * @var array
     */
    protected $buttons = [];

    /**
     * Notifications
     * @var \fpcm\model\theme\notifications
     */
    protected $notifications;

    /**
     * Default vars object
     * @var viewVars
     */
    protected $defaultViewVars;

    /**
     * Cache object
     * @var \fpcm\classes\cache
     */
    protected $cache;

    /**
     * Session object
     * @var \fpcm\model\system\session
     */
    protected $session;

    /**
     * Config
     * @var \fpcm\model\system\config
     */
    protected $config;

    /**
     * Events
     * @var \fpcm\events\events
     */
    protected $events;

    /**
     * Config
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     * View was already rendered
     * @var bool
     */
    protected $rendered;

    /**
     * View was already rendered
     * @var bool
     */
    protected $showPageToken = true;

    /**
     * Root urls for replacements
     * @var array
     * @since FPCm 4.1
     */
    protected $rootUrls = [];
    
    /**
     * Konstruktor
     * @param string $viewName Viewname ohne Endung .php
     * @param string $module Module-Key
     */
    public function __construct($viewName = '', $module = false)
    {
        if (trim($viewName)) {
            $this->setViewPath($viewName, $module);
        }
        

        $this->showHeader = self::INCLUDE_HEADER_FULL;
        
        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $this->events = \fpcm\classes\loader::getObject('\fpcm\events\events');

        if (\fpcm\classes\baseconfig::dbConfigExists()) {
            $this->session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
            $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
            $this->notifications = \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications');
        }

        $this->rootUrls = [
            self::ROOTURL_LIB => \fpcm\classes\dirs::getLibUrl(''),
            self::ROOTURL_CORE_JS => \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_JS, ''),
            self::ROOTURL_CORE_THEME => \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_THEME, '')
        ];

        $this->defaultViewVars = new viewVars();
        $this->initFileLib();
    }

    /**
     * Inits file library
     * @return bool
     */
    protected function initFileLib()
    {
        if ($this->showHeader === self::INCLUDE_HEADER_NONE) {
            return true;
        }

        $this->initCssFiles();
        $this->initJsFiles();

        if (!is_object($this->language)) {
            return true;
        }

        $this->addJsLangVars([
            'GLOBAL_CONFIRM', 'GLOBAL_CLOSE', 'GLOBAL_OK', 'GLOBAL_YES', 'GLOBAL_NO', 'GLOBAL_SAVE', 'GLOBAL_CLOSE',
            'GLOBAL_OPENNEWWIN', 'GLOBAL_EXTENDED', 'GLOBAL_EDIT_SELECTED', 'GLOBAL_NOTFOUND', 'SAVE_FAILED_ARTICLES',
            'AJAX_REQUEST_ERROR', 'AJAX_RESPONSE_ERROR', 'CONFIRM_MESSAGE', 'CACHE_CLEARED_OK', 'SELECT_ITEMS_MSG',
            'HL_HELP', 'CSRF_INVALID', 'HEADLINE'
        ]);

        $this->jsLangVars['calendar']['days'] = $this->language->getDays();
        $this->jsLangVars['calendar']['daysShort'] = $this->language->getDaysShort();
        $this->jsLangVars['calendar']['months'] = array_values($this->language->getMonths());
    }

    /**
     * Checks item if included system paths
     * @param string $item
     * @since FPCM 3.6
     */
    private function addRootPath($item)
    {
        if (!$item) {
            return '';
        }

        $jsCorePath = '';

        $type = $this->getJsFileType($item, $jsCorePath);
        if ($type === self::JS_FILETYP_FILE) {
            $this->viewJsFilesLocal[] = $jsCorePath;
            return $jsCorePath;
        }

        return str_replace(array_keys($this->rootUrls), array_values($this->rootUrls), $item);
    }

    /**
     * Checks path type of given JS file
     * @param string $item
     * @param string $jsCorePath
     * @since FPCM 4.1
     */
    private function getJsFileType(string $item, &$jsCorePath) : int
    {
        $item = trim($item);
        if (!$item || substr($item, -3) !== '.js' || substr($item, 0, 4) === 'http' || substr($item, 0, 2) === '//') {
            return self::JS_FILETYP_URL;
        }
        
        $jsCorePath = \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, $item);
        if (file_exists($jsCorePath)) {
            return self::JS_FILETYP_FILE;
        }
        
        return self::JS_FILETYP_FILE_EXT;
    }

    /**
     * Initializes notifications
     * @return bool
     */
    protected function prepareNotifications()
    {
        if (!\fpcm\classes\baseconfig::dbConfigExists()) {
            return false;
        }
        
        if ($this->config->system_maintenance) {
            $this->notifications->addNotification(new \fpcm\model\theme\notificationItem(
                (new helper\icon('lightbulb'))->setText('SYSTEM_OPTIONS_MAINTENANCE')->setClass('fpcm-ui-important-text')
            ));
        }
        
        if (!$this->config->file_uploader_new) {
            $this->notifications->addNotification(new \fpcm\model\theme\notificationItem(
                (new helper\icon('file-upload'))->setText('FILES_NEWUPLOADER_DISABLED')->setClass('fpcm-ui-important-text')
            ));
        }

        if (!\fpcm\classes\baseconfig::asyncCronjobsEnabled()) {
            $this->notifications->addNotification(new \fpcm\model\theme\notificationItem(
                (new helper\icon('history'))->setText('SYSTEM_OPTIONS_CRONJOBS')->setClass('fpcm-ui-important-text')
            ));
        }
        
        if (defined('FPCM_DEBUG') && FPCM_DEBUG) {
            $this->notifications->addNotification(new \fpcm\model\theme\notificationItem(
                (new helper\icon('terminal'))->setText('DEBUG_MODE')->setClass('fpcm-ui-important-text')
            ));
        }

        $this->defaultViewVars->notificationString = $this->notifications->getNotificationsString();
        return true;
    }

    /**
     * Add JavScript files to view
     * @param array $viewJsFiles
     */
    public function addJsFiles(array $viewJsFiles)
    {
        $this->viewJsFiles = array_merge($this->viewJsFiles, $viewJsFiles);
    }

    /**
     * Add CSS files variable to view
     * @param array $viewCssFiles
     */
    public function addCssFiles(array $viewCssFiles)
    {
        $this->viewCssFiles = array_merge($this->viewCssFiles, $viewCssFiles);
    }

    /**
     * Add new JS vars
     * @param mixed $jsvars
     */
    public function addJsVars(array $jsvars)
    {
        $this->jsvars = array_merge($this->jsvars, $jsvars);
    }
    
    /**
     * Merge new JS vars
     * @param string $jsVar
     * @param array $jsvars
     */
    protected function mergeJsVars($jsVar, array $jsvars)
    {
        $this->jsvars[$jsVar] = array_merge($this->jsvars[$jsVar], $jsvars[$jsVar]);
    }

    /**
     * Overrides CSS files variable to view
     * @param array $viewCssFiles
     */
    public function overrideCssFiles(array $viewCssFiles)
    {
        $this->viewCssFiles = $viewCssFiles;
    }

    /**
     * Overrides new JS vars
     * @param mixed $viewJsFiles
     */
    public function overrideJsFiles(array $viewJsFiles)
    {
        $this->viewJsFiles = $viewJsFiles;
    }
    
    /**
     * Overrides new JS language vars
     * @param array $jsvars
     */
    public function overrideJsLangVars(array $jsvars)
    {
        $keys = array_values($jsvars);
        $values = array_map([$this->language, 'translate'], array_values($jsvars));

        $this->jsLangVars = array_combine($keys, $values);
    }

    /**
     * Add new JS language vars
     * @param mixed $jsvars
     */
    public function addJsLangVars(array $jsvars)
    {
        $keys = array_values($jsvars);
        $values = array_map([$this->language, 'translate'], array_values($jsvars));

        $this->jsLangVars = array_merge($this->jsLangVars, array_combine($keys, $values));
    }

    /**
     * Add array of buttons to toolbar
     * @param array[fpcm/view/helper/helper] $buttons
     */
    public function addButtons(array $buttons)
    {
        foreach ($buttons as $button) {
            $this->addButton($button);
        }
    }

    /**
     * Add button to toolbar
     * @param \fpcm\view\helper\button $button
     * @param type $pos
     * @return void
     */
    public function addButton($button, $pos = false)
    {
        if (!$button instanceof helper\helper) {
            trigger_error('Invalid parameter, $button must be an instance of /fpcm/view/helper.');
            return;
        }

        if ($pos) {
            $this->buttons[$pos] = $button;
            ksort($this->buttons);
            return;
        }

        $this->buttons[] = $button;
    }

    /**
     * Force to load jQuery in Pub-Controllers before other JS-Files if not already done
     * @since FPCM 3.2.0
     */
    public function prependjQuery()
    {
        if ($this->config->system_loader_jquery) {
            return false;
        }

        array_unshift($this->viewJsFiles, \fpcm\classes\loader::libGetFileUrl('jquery/jquery-3.4.1.min.js'));
    }

    /**
     * Assign new variable to view
     * @param string $varName
     * @param mixes $varValue
     */
    public function assign($varName, $varValue)
    {
        $this->viewVars[$varName] = $varValue;
    }

    /**
     * Adds top description
     * @param string $descr
     * @param array $params
     * @since FPCM 4.2
     */
    public function addTopDescription(string $descr, array $params = [])
    {
        $this->viewVars['topDescription'] = $this->language->translate($descr, $params);
    }

    /**
     * Add red error message
     * @param string $messageText
     * @param string $params
     * @return void
     */
    public function addErrorMessage($messageText, $params = [])
    {
        $this->messages[] = new message($this->language->translate($messageText, $params), message::TYPE_ERROR, message::ICON_ERROR);

    }

    /**
     * Add blue notification message
     * @param string $messageText
     * @param string $params
     * @return void
     */
    public function addNoticeMessage($messageText, $params = [])
    {
        $this->messages[] = new message($this->language->translate($messageText, $params), message::TYPE_NOTICE, message::ICON_NOTICE);
    }

    /**
     * Add yellow message
     * @param string $messageText
     * @param string $params
     * @return void
     */
    public function addMessage($messageText, $params = [])
    {
        $this->messages[] = new message($this->language->translate($messageText, $params), message::TYPE_NEUTRAL, message::ICON_NEUTRAL);
    }

    /**
     * Set help link data
     * @param string $entry
     * @param int $chapter
     * @return bool
     */
    public function setHelpLink(string $entry, int $chapter = 0) : bool
    {
        if (!trim($entry)) {
            return false;
        }

        $this->defaultViewVars->helpLink = [
            'ref' => urlencode(base64_encode(strtoupper($entry))),
            'chapter' => $chapter
        ];

        $this->addJsLangVars(['HL_HELP']);
        return true;
    }

    /**
     * Include header and footer into view,
     * @see \fpcm\view\view::INCLUDE_HEADER_FULL
     * @see \fpcm\view\view::INCLUDE_HEADER_SIMPLE
     * @see \fpcm\view\view::INCLUDE_HEADER_NONE
     * @param int $showHeader
     */
    public function showHeaderFooter($showHeader)
    {
        $this->showHeader = $showHeader;
    }

    /**
     * Renders a set up view
     * @return bool
     */
    public function render()
    {
        if (!file_exists($this->viewPath) || strpos(realpath($this->viewPath), \fpcm\classes\dirs::getFullDirPath('') ) !== 0) {
            trigger_error("View file {$this->viewName} not found!");
            exit("View file {$this->viewName} not found!");
        }

        $this->initAssigns();
        extract($this->events->trigger('view\renderBefore', $this->viewVars));

        switch ($this->showHeader) {
            case self::INCLUDE_HEADER_FULL :
                include_once \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'common/header.php');
                break;
            case self::INCLUDE_HEADER_SIMPLE :
                include_once \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'common/headersimple.php');
                break;
        }

        include $this->viewPath;

        switch ($this->showHeader) {
            case self::INCLUDE_HEADER_FULL :
                include_once \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'common/footer.php');
                break;
            case self::INCLUDE_HEADER_SIMPLE :
                include_once \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'common/footersimple.php');
                break;
        }

        $this->events->trigger('view\renderAfter');
        $this->rendered = true;

        return true;
    }

    /**
     * Initializes basic view vars
     * @return bool
     */
    protected function initAssigns()
    {
        $this->defaultViewVars->loggedIn = false;

        $hasDbConfig = \fpcm\classes\baseconfig::dbConfigExists();

        if ($hasDbConfig && $this->session->exists()) {
            $this->addJsLangVars(['SESSION_TIMEOUT']);
            $this->addJsVars(['sessionCheck' => true]);

            $this->defaultViewVars->currentUser = $this->session->getCurrentUser();
            $this->defaultViewVars->loginTime = $this->session->getLogin();
            $this->defaultViewVars->navigation = (new \fpcm\model\theme\navigation())->render();
            $this->defaultViewVars->navigationActiveModule = \fpcm\classes\tools::getNavigationActiveCheckStr();
            $this->defaultViewVars->loggedIn = true;
            $this->defaultViewVars->permissions = \fpcm\classes\loader::getObject('\fpcm\model\permissions\permissions');
        }

        if ($hasDbConfig) {
            $this->defaultViewVars->version = $this->config->system_version;
            $this->defaultViewVars->dateTimeMask = $this->config->system_dtmask;
            $this->defaultViewVars->dateTimeZone = $this->config->system_timezone;
            $this->defaultViewVars->frontEndLink = $this->config->system_url;            
        }
        else {
            $this->defaultViewVars->version = \fpcm\classes\baseconfig::getVersionFromFile();
            $this->defaultViewVars->dateTimeMask = 'd.m.Y H:i';
            $this->defaultViewVars->dateTimeZone = 'Europe/Berlin';
        }

        $this->defaultViewVars->langCode = $this->language->getLangCode();
        $this->defaultViewVars->self = strip_tags(trim($_SERVER['PHP_SELF']));
        $this->defaultViewVars->basePath = \fpcm\classes\tools::getFullControllerLink();
        $this->defaultViewVars->themePath = \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_THEME);

        $this->defaultViewVars->currentModule = \fpcm\classes\http::getModuleString();

        
        $toolbarButtons = new \fpcm\events\view\extendToolbarResult();
        $toolbarButtons->buttons = $this->buttons;
        
        /* @var $toolbarButtons \fpcm\events\view\extendToolbarResult */
        $toolbarButtons = $this->events->trigger('view\extendToolbar', $toolbarButtons);        
        $this->defaultViewVars->toolbarArea = $toolbarButtons->area;
        $this->defaultViewVars->buttons = $toolbarButtons->buttons;
        unset($toolbarButtons);

        $this->defaultViewVars->formActionTarget = $this->formAction;
        $this->defaultViewVars->bodyClass = $this->bodyClass;
        $this->defaultViewVars->lang = \fpcm\classes\loader::getObject('\fpcm\classes\language');
        $this->defaultViewVars->filesCss = array_unique(array_map([$this, 'addRootPath'], $this->viewCssFiles));

        $this->viewJsFiles = array_unique(array_diff(array_map([$this, 'addRootPath'], $this->viewJsFiles), $this->viewJsFilesLocal));
        $this->viewJsFilesLocal = array_unique($this->viewJsFilesLocal);

        $this->viewHash = \fpcm\classes\tools::getHash($this->viewPath.$this->viewHash. implode('-', $this->viewJsFilesLocal));
        $this->viewJsFiles = array_map(function($item) {
            return str_replace(self::ROOTURL_UNIQUE, $this->viewHash, $item);
        }, $this->viewJsFiles);
        
        $this->defaultViewVars->filesJs = $this->viewJsFiles;
        $this->cache->write(self::JS_FILES_CACHE.$this->getViewHash(), $this->viewJsFilesLocal);

        $this->defaultViewVars->fullWrapper = in_array($this->defaultViewVars->currentModule, ['installer']);
        $this->defaultViewVars->showPageToken = $this->showPageToken;

        $this->jsvars['currentModule'] = $this->defaultViewVars->currentModule;

        $this->defaultViewVars->varsJs = [
            'vars' => [
                'ui' => [
                    'messages' => $this->messages,
                    'lang' => $this->jsLangVars,
                ],
                'jsvars' => $this->jsvars,
                'actionPath' => \fpcm\classes\tools::getFullControllerLink(''),
                'ajaxActionPath' => \fpcm\classes\tools::getFullControllerLink('ajax/'),
            ]
        ];

        $this->prepareNotifications();

        /* @var $theView viewVars */
        $this->assign('theView', $this->defaultViewVars);
        return true;
    }

    /**
     * Returns view path
     * @return string
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }
    
    /**
     * Sets view path
     * @param string $viewName
     * @param string $module
     */
    public function setViewPath($viewName, $module = false)
    {
        $viewName .= '.php';

        $this->viewPath = $module
                        ? \fpcm\module\module::getTemplateDirByKey($module, $viewName)
                        : \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, $viewName);
        
        $this->viewPath = str_replace(self::PATH_COMPONENTS, \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'components'.DIRECTORY_SEPARATOR), $viewName);

        $this->viewName = $viewName;
    }
    
    /**
     * Return assigned view vars
     * @param string $var
     * @return mixed
     */
    public function getViewVars($var = false)
    {
        return $var ? $this->viewVars[$var] : $this->viewVars;
    }

    /**
     * Overrides assigned view vars
     * @param array $viewVars
     */
    public function setViewVars(array $viewVars)
    {
        $this->viewVars = $viewVars;
    }

    /**
     * Auto focus element
     * @param string $elementId
     */
    public function setFieldAutofocus($elementId)
    {
        $this->jsvars['fieldAutoFocus'] = (string) $elementId;
    }

    /**
     * Set active navigation item
     * @param string $elementId
     * @return bool
     */
    public function setActiveNavigationElement($elementId)
    {
        if (!trim($elementId)) {
            return false;
        }

        $this->jsvars['navigationActive'] = (string) $elementId;
    }

    /**
     * Check if view was already rendered
     * @return bool
     */
    public function wasRendered()
    {
        return $this->rendered;
    }

    /**
     * Returns Sha256-hash on view path
     * @return string
     * @since FPCM 4.1
     */
    public function getViewHash() : string
    {
        return $this->viewHash;
    }

    /**
     * Returns Sha256-hash on view path
     * @param string $viewHash
     * @return bool
     * @since FPCM 4.1
     */
    public function setViewHashDefault(string $viewHash) : bool
    {
        if (trim($this->viewHash)) {
            trigger_error('View hash value was already set');
            return false;
        }
        
        $this->viewHash = $viewHash;
        return false;
    }

    /**
     * Set form action path
     * @param string $controller
     * @param array $params
     * @param bool $isLink
     * @return void
     */
    public function setFormAction($controller, array $params = [], $isLink = false)
    {
        if ($isLink) {
            $this->formAction = $controller . (count($params) ? '&' . http_build_query($params) : '');
            return;
        }

        $this->formAction = \fpcm\classes\tools::getFullControllerLink($controller, $params);
    }

    /**
     * Set Active tab
     * @param int $tab
     * @return void
     * @since FPCM 4.1
     */
    public function setActiveTab(int $tab)
    {
        $this->jsvars['activeTab'] = $tab;
        $this->viewVars['activeTab'] = $tab;
    }

    /**
     * Set <body>-tag CSS class
     * @param int $bodyClass
     * @return void
     * @since FPCM 4.2
     */
    public function setBodyClass(string $bodyClass)
    {
        $this->bodyClass = $bodyClass;
    }

    /**
     * Enables output of page token field
     * @param bool $showPageToken
     * @return $this
     */
    public function showPageToken($showPageToken)
    {
        $this->showPageToken = (bool) $showPageToken;
        return $this;
    }
    
    /**
     * Adds dataview object to view variables
     * @param \fpcm\components\dataView\dataView $dataView
     */
    public function addDataView(\fpcm\components\dataView\dataView $dataView)
    {
        $this->assign('dataViewId', $dataView->getName());

        $vars = $dataView->getJsVars();
        
        if (count($vars)) {
            if (isset($this->jsvars['dataviews'])) {
                $this->mergeJsVars('dataviews', $vars);
            }
            else {
                $this->addJsVars($vars);
            }
        }

        $files = $dataView->getJsFiles();
        if (count($files)) {
            $this->addJsFiles($files);
        }

        $langVars = $dataView->getJsLangVars();
        if (count($langVars)) {
            $this->addJsLangVars($langVars);
        }

    }
    
    /**
     * Sets view to standard tab view,
     * do not use if you want to include tabs in aother view!!!
     * @param string $tabsId
     * @param array $tabs
     * @param string $tabsClass
     * @since FPCM 4.3
     */
    public function addTabs(string $tabsId, array $tabs, string $tabsClass = '')
    {
        $this->setViewPath('components/tabs');
        $this->assign('tabsId', $tabsId);
        $this->assign('tabs', $tabs);
        $this->assign('tabsClass', $tabsClass);
    }

    /**
     * Add pager to view
     * @param \fpcm\view\helper\pager $pager
     */
    public function addPager(helper\pager $pager)
    {
        $this->defaultViewVars->pager = $pager;
        $this->addJsVars(['pager' => $pager->getJsVars()]);
        $this->addJsLangVars($pager->getJsLangVars());
    }

    /**
     * Add HTML items into toolbar right hand to pager
     * @param string $data
     * @since FPCM 4.3
     */
    public function addToolbarRight(string $data)
    {
        $this->defaultViewVars->toolbarItemRight = $data;
    }

    /**
     * Triggers events addJsFiles/addCssFiles for given type
     * @param string $type
     */
    public function triggerFilesEvents($type = 'theme')
    {
        if (!$type) {
            return false;
        }
        
        $this->viewJsFiles = $this->events->trigger($type.'\addJsFiles', $this->viewJsFiles);
        $this->viewCssFiles = $this->events->trigger($type.'\addCssFiles', $this->viewCssFiles);    

        return true;
    }

    /**
     * Add AJAX page token to view
     * @param string $name
     * @return bool
     * @since FPCm 4.3
     */
    public function addAjaxPageToken(string $name) : bool
    {
        $name = 'ajax/'.$name;

        $this->jsvars['pageTokens'][$name] = (new \fpcm\classes\pageTokens)->refresh($name);
        return true;
    }

    /**
     * Initialize default CSS files
     * @return array
     */
    private function initCssFiles()
    {
        $this->addCssFiles([
            self::ROOTURL_LIB.'jquery-ui/jquery-ui.min.css',
            self::ROOTURL_LIB.'fancybox/jquery.fancybox.min.css',
            self::ROOTURL_LIB.'font-awesome/css/all.min.css',
            self::ROOTURL_LIB.'bootstrap/bootstrap-grid.min.css',
            self::ROOTURL_CORE_THEME.'style.php'
        ]);

        return $this->viewCssFiles;
    }

    /**
     * Initialize default JavaScript files
     * @return array
     */
    private function initJsFiles()
    {
        $this->addJsFiles([
            self::ROOTURL_LIB.'jquery/jquery-3.4.1.min.js',
            self::ROOTURL_LIB.'jquery-ui/jquery-ui.min.js',
            self::ROOTURL_LIB.'fancybox/jquery.fancybox.min.js',
            self::ROOTURL_CORE_JS.'script.php?uq={$unique}'
        ]);

        return $this->viewJsFiles;
    }

    /**
     * Checks User Agent for a certain browser
     * @param string $key
     * @return bool
     * @static
     */
    public static function isBrowser($key)
    {
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }

        return preg_match("/($key)/is", $_SERVER['HTTP_USER_AGENT']) === 1 ? true : false;
    }

}

?>