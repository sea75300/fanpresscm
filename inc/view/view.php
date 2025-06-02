<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view;

/**
 * Default view object
 *
 * @package fpcm\view
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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
    const PATH_MODULE = '{$module}';

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
     * @since 4.2
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
    protected $jsFiles = [];

    /**
     * View JS files
     * @var array
     * @since 4.5
     */
    protected $jsFilesLate = [];
/**
     * Javascript ECMA-modules
     * @var array
     * @since 5.2.0
     */
    protected $jsModuleFiles = [];

    /**
     * View CSS files
     * @var array
     */
    protected $cssFiles = [];

    /**
     * View messages
     * @var array
     */
    protected $messages = [];

    /**
     * View JS vars
     * @var array
     */
    protected $jsVars = [];

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
    protected $rendered = false;

    /**
     * View was already rendered
     * @var bool
     */
    protected $showPageToken = true;

    /**
     * Include full js vars if no header included
     * @var bool
     * @since 5.0.0-b3
     */
    protected $fullJsVarsNoheader = false;

    /**
     * Root urls for replacements
     * @var array
     * @since 4.1
     */
    protected $rootUrls = [];

    /**
     * Root urls for replacements
     * @var string
     * @since 4.5-b7
     */
    protected $module = null;

    /**
     * Active nagivation item
     * @var string
     * @since 5.0.0-a4
     */
    protected $navigationActiveModule = '';

    /**
     * Konstruktor
     * @param string $viewName Viewname ohne Endung .php
     * @param string $module Module-Key
     */
    public function __construct($viewName = '', ?string $module = null)
    {
        if (trim($viewName)) {
            $this->setViewPath($viewName, $module);
        }

        $this->module = $module;
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
    protected function initFileLib() : bool
    {
        if ($this->showHeader === self::INCLUDE_HEADER_NONE) {
            return true;
        }

        $this->initCssFiles();
        $this->initJsFiles();
        $this->initComponents();

        if (!is_object($this->language)) {
            return true;
        }

        $this->addJsLangVars([
            'GLOBAL_CONFIRM', 'GLOBAL_CLOSE', 'GLOBAL_OK', 'GLOBAL_YES',
            'GLOBAL_NO', 'GLOBAL_SAVE', 'GLOBAL_CLOSE', 'GLOBAL_OPENNEWWIN',
            'GLOBAL_EXTENDED', 'GLOBAL_EDIT_SELECTED', 'GLOBAL_NOTFOUND',
            'GLOBAL_NOTFOUND2', 'SAVE_FAILED_ARTICLES', 'AJAX_REQUEST_ERROR',
            'AJAX_RESPONSE_ERROR', 'CONFIRM_MESSAGE', 'CACHE_CLEARED_OK',
            'SELECT_ITEMS_MSG', 'HL_HELP', 'CSRF_INVALID', 'HEADLINE',
            'GLOBAL_RESET', 'GLOBAL_PLEASEWAIT', 'LABEL_SEARCH_GLOBAL_RESULTSIZE',
            'GLOBAL_HIDE', 'GLOBAL_SHOW'
        ]);

        $this->jsLangVars['calendar']['days'] = $this->language->getDays();
        $this->jsLangVars['calendar']['daysShort'] = $this->language->getDaysShort();
        $this->jsLangVars['calendar']['months'] = array_values($this->language->getMonths());
        return true;
    }

    /**
     * Checks item if included system paths
     * @param string $item
     * @since 3.6
     */
    private function addRootPath(string $item) : string
    {
        if (!$item) {
            return '';
        }

        if (str_starts_with($item, 'http') || str_starts_with($item, '//') ||
           ( !str_ends_with($item, '.js') && !str_ends_with($item, '.css') )
           ) {
            return $item;
        }

        if (str_starts_with($item, self::ROOTURL_CORE_THEME) ||
            str_starts_with($item, self::ROOTURL_CORE_JS) ||
            str_starts_with($item, self::ROOTURL_LIB) ) {
            return \fpcm\classes\tools::strReplaceArray($item, $this->rootUrls);
        }

        if (str_ends_with($item, '.css') && !str_starts_with($item, self::ROOTURL_CORE_THEME)) {
            $prefix = self::ROOTURL_CORE_THEME;
        }

        if (!str_starts_with($item, self::ROOTURL_LIB) && !str_starts_with($item, self::ROOTURL_CORE_JS)) {
            $prefix = self::ROOTURL_CORE_JS;
        }

        return \fpcm\classes\tools::strReplaceArray($prefix . $item, $this->rootUrls);
    }

    /**
     * Prepares profile menu
     * @return bool
     * @since 5.1-dev
     */
    private function prepareProfileMenu()
    {

        $default = [
            sprintf('<a class="text-truncate" href="%s">%s%s</a>',
                    \fpcm\classes\tools::getControllerLink('system/profile'),
                    new helper\icon('wrench'),
                    $this->language->translate('PROFILE_MENU_OPENPROFILE')),

            sprintf('<a class="text-truncate" href="%s" rel="license">%s%s</a>',
                    \fpcm\classes\tools::getControllerLink('system/info'),
                    new helper\icon('info-circle'),
                    $this->language->translate('HL_HELP_SUPPORT')),

            sprintf('<a class="text-truncate" href="%s" rel="license">%s%s</a>',
                    \fpcm\classes\tools::getControllerLink('system/logout'),
                    new helper\icon('sign-out-alt'),
                    $this->language->translate('LOGOUT_BTN')),

        ];

        $ev = $this->events->trigger('view\extendProfileMenu', $default);
        if (!$this->handleEventResultData($ev, 'view\extendProfileMenu')) {
            $this->defaultViewVars->profileMenuButtons = $default;
            return false;
        }        

        $this->defaultViewVars->profileMenuButtons = $ev->getData();
        return true;
    }

    /**
     * Prepares toolbar
     * @return bool
     * @since 5.1-dev
     */
    private function prepareToolbar()
    {
        $toolbarButtons = new \fpcm\events\view\extendToolbarResult();
        $toolbarButtons->buttons = $this->buttons;

        $ev = $this->events->trigger('view\extendToolbar', $toolbarButtons);
        if (!$this->handleEventResultData($ev, 'view\extendToolbar')) {
            return false;
        }

        $toolbarButtons = $ev->getData();
        if (!$toolbarButtons instanceof \fpcm\events\view\extendToolbarResult) {
            trigger_error("Returned data of view\extendToolbar event must be an instance of \fpcm\events\view\extendToolbarResult");
            return false;
        }

        $this->defaultViewVars->toolbarArea = $toolbarButtons->area;
        $this->defaultViewVars->buttons = $toolbarButtons->buttons;
        return true;
    }

    /**
     * Initializes notifications
     * @return bool
     */
    protected function prepareNotifications() : bool
    {
        if (!\fpcm\classes\baseconfig::dbConfigExists()) {
            return false;
        }

        $this->notifications->prependSystemNotifications();
        $this->defaultViewVars->notifications = $this->notifications;
        return true;
    }

    /**
     * Add JavScript files to view
     * @param array $jsFiles
     */
    public function addJsFiles(array $jsFiles)
    {
        $this->jsFiles = array_merge($this->jsFiles, $jsFiles);
    }

    /**
     * Add JavScript files to view
     * @param array $jsFiles
     */
    public function addJsFilesLate(array $jsFilesLate)
    {
        $this->jsFilesLate = array_merge($this->jsFilesLate, $jsFilesLate);
    }

    /**
     * Add CSS files variable to view
     * @param array $cssFiles
     */
    public function addCssFiles(array $cssFiles)
    {
        $this->cssFiles = array_merge($this->cssFiles, $cssFiles);
    }

    /**
     * Add new JS vars
     * @param mixed $jsVars
     */
    public function addJsVars(array $jsVars)
    {
        $this->jsVars = array_merge($this->jsVars, $jsVars);
    }

    /**
     * Merge new JS vars
     * @param string $jsVar
     * @param array $jsVars
     */
    protected function mergeJsVars($jsVar, array $jsVars)
    {
        $this->jsVars[$jsVar] = array_merge($this->jsVars[$jsVar], $jsVars[$jsVar]);
    }

    /**
     * Overrides CSS files variable to view
     * @param array $cssFiles
     */
    public function overrideCssFiles(array $cssFiles)
    {
        $this->cssFiles = $cssFiles;
    }

    /**
     * Overrides new JS vars
     * @param mixed $jsFiles
     */
    public function overrideJsFiles(array $jsFiles)
    {
        $this->jsFiles = $jsFiles;
    }

    /**
     * Overrides new JS language vars
     * @param array $jsVars
     * @deprecated 5.2
     */
    public function overrideJsLangVars(array $jsVars)
    {
        trigger_error(sprintf('%s is deprecated as of FPCM 5.2. Use "addJsLangVars" function with second parameter true instead.', __METHOD__), E_USER_DEPRECATED);
        $this->addJsLangVars($jsVars, true);
    }

    /**
     * Add new JS language vars
     * @param mixed $jsVars
     */
    public function addJsLangVars(array $jsVars, bool $override = false)
    {
        $keys = array_values($jsVars);
        $values = array_map([$this->language, 'translate'], array_values($jsVars));

        $this->jsLangVars = $override
                          ? array_combine($keys, $values)
                          : array_merge($this->jsLangVars, array_combine($keys, $values));
    }

    /**
     * Add JavaScritp ECMA module files
     * @param array $jsModuleFiles
     * @return $this
     * @since 5.2
     */
    public function setJsModuleFiles(array $jsModuleFiles)
    {
        $base = \fpcm\classes\dirs::getECMAurl();

        $jsModuleFiles = array_map(
            function ($item) use ($base) {

                if (str_starts_with($item, 'http')) {
                    return $item;
                }

                return $base.$item;
            },
            $jsModuleFiles
        );


        $this->jsModuleFiles = array_merge($this->jsModuleFiles, $jsModuleFiles);
        return $this;
    }

    /**
     * Add js and css files from 3rd party library
     * @param string $lib
     * @param array $jsFiles
     * @param array $cssFiles
     * @return bool
     * @since 4.5
     */
    final public function addFromLibrary(string $lib, array $jsFiles = [], array $cssFiles = []) : bool
    {
        if (!trim($lib)) {
            return false;
        }

        $this->addJsFiles(array_map(function ($item) use ($lib) {
            return self::ROOTURL_LIB . $lib . '/' . $item;
        }, $jsFiles));

        if (!count($cssFiles)) {
            return true;
        }

        $this->addCssFiles(array_map(function ($item) use ($lib) {
            return self::ROOTURL_LIB . $lib . '/' . $item;
        }, $cssFiles));

        return true;
    }

    /**
     * Add js and css files from modules
     * @param array $jsFiles
     * @param array $cssFiles
     * @param null|string $moduleKey
     * @return bool
     * @since 4.5-b7
     */
    final public function addFromModule(array $jsFiles = [], array $cssFiles = [], ?string $moduleKey = null) : bool
    {
        if ($moduleKey === null || !trim($moduleKey)) {
            $moduleKey = $this->module;
        }

        $jsPath = \fpcm\module\module::getJsDirByKey($moduleKey, '');

        $this->addJsFiles(array_map(function ($item) use ($jsPath) {
            return $jsPath . $item;
        }, $jsFiles));

        if (!count($cssFiles)) {
            return true;
        }

        $cssPath = \fpcm\module\module::getStyleDirByKey($moduleKey, '');

        $this->addCssFiles(array_map(function ($item) use ($cssPath) {
            return $cssPath.$item;
        }, $cssFiles));

        return true;
    }

    /**
     * Add view data from component
     * @param \fpcm\model\interfaces\viewComponent $component
     * @return bool
     * @since 5.2.0-a1
     */
    final public function addFromCpomponent($component) : bool
    {

        if (!$component instanceof \fpcm\model\interfaces\viewComponent) {
            trigger_error(sprintf('Parameter 1 for %s msut be an instance of \fpcm\model\interfaces\viewComponent. Class given: %s', __METHOD__, $component::class));
            return false;
        }

        $this->addCssFiles($component->getCssFiles());
        $this->addJsFiles($component->getJsFiles());
        $this->addJsFilesLate($component->getJsFilesLate());
        $this->setJsModuleFiles($component->getJsModuleFiles());
        $this->addJsVars($component->getJsVars());
        $this->addJsLangVars($component->getJsLangVars());
        $this->viewVars += $component->getViewVars();

        return true;
    }

    /**
     * Add array of buttons to toolbar
     * @param array $buttons Array of fpcm/view/helper/helper items
     */
    public function addButtons(array $buttons)
    {
        foreach ($buttons as $button) {
            $this->addButton($button);
        }
    }

    /**
     * Add button to toolbar
     * @param helper\helper $button
     * @param type $pos
     * @return void
     */
    public function addButton($button, $pos = false)
    {
        if (!$button instanceof helper\helper) {
            trigger_error('Invalid parameter, $button must be an instance of /fpcm/view/helper.');
            return;
        }


        $button->setClass('shadow-sm');

        if ($pos) {
            $this->buttons[$pos] = $button;
            ksort($this->buttons);
            return;
        }

        $this->buttons[] = $button;
    }

    /**
     * Force to load jQuery in Pub-Controllers before other JS-Files if not already done
     * @since 3.2.0
     */
    public function prependjQuery()
    {
        if ($this->config->system_loader_jquery) {
            return false;
        }

        array_unshift($this->jsFiles, \fpcm\components\components::getjQuery());
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
     * @since 4.2
     */
    public function addTopDescription(string $descr, array $params = [], bool $spf = false)
    {
        $this->viewVars['topDescription'] = $this->language->translate($descr, $params, $spf);
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
     * Include full js var set if no header is included
     * @param bool $fullJsVarsNoheader
     */
    public function includeFullJsVarsNoheader(bool $fullJsVarsNoheader)
    {
        $this->fullJsVarsNoheader = $fullJsVarsNoheader;
    }

    /**
     * Renders view
     * @param bool $return
     * @param bool $includeFullJsVars
     * @return void
     */
    public function render(bool $return = false)
    {
        if ($return) {
            ob_start();
        }

        if (!file_exists($this->viewPath) || strpos(realpath($this->viewPath), \fpcm\classes\dirs::getFullDirPath('') ) !== 0) {
            trigger_error("View file {$this->viewName} not found in {$this->viewPath}!", E_USER_ERROR);
            exit("View file {$this->viewName} not found in {$this->viewPath}!");
        }

        $this->initAssigns();
        extract($this->events->trigger('view\renderBefore', $this->viewVars)->getData());

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

        if ($return) {
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

        return true;
    }

    /**
     * Initializes basic view vars
     * @return bool
     */
    protected function initAssigns() : bool
    {
        $this->defaultViewVars->loggedIn = false;

        $this->initAssignsWithDb();
        $this->initAssignsWidthSession();

        $this->defaultViewVars->langCode = $this->language->getLangCode();
        $this->defaultViewVars->self = trim(filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL));
        $this->defaultViewVars->basePath = \fpcm\classes\tools::getFullControllerLink();
        $this->defaultViewVars->themePath = \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_THEME);
        $this->defaultViewVars->debugMode = defined('FPCM_DEBUG') && FPCM_DEBUG;

        /* @var $req \fpcm\model\http\request */
        $req = \fpcm\classes\loader::getObject('\fpcm\model\http\request');
        $this->defaultViewVars->currentModule = $req->getModule();
        $this->defaultViewVars->ipAddress = $req->getIp();

        $this->prepareToolbar();

        if ($this->showHeader === self::INCLUDE_HEADER_FULL) {
            $this->prepareProfileMenu();
        }

        unset($req);

        $this->defaultViewVars->formActionTarget = $this->formAction;
        $this->defaultViewVars->bodyClass = $this->bodyClass;
        $this->defaultViewVars->lang = \fpcm\classes\loader::getObject('\fpcm\classes\language');
        $this->defaultViewVars->filesCss = array_unique( array_map([$this, 'addRootPath'], $this->cssFiles) );
        $this->defaultViewVars->filesECMAFiles = $this->jsModuleFiles;

        if (defined('FPCM_VIEW_JS_USE_MINIFIED') || !FPCM_VIEW_JS_USE_MINIFIED) {
            $this->rootUrls['.js'] = $this->getJsExt();
        }

        $this->defaultViewVars->filesJs = array_unique( array_map([$this, 'addRootPath'], $this->jsFiles) );
        $this->defaultViewVars->filesJsLate = array_unique( array_map([$this, 'addRootPath'], $this->jsFilesLate) );

        $this->defaultViewVars->fullWrapper = in_array($this->defaultViewVars->currentModule, ['system/installer']);
        $this->defaultViewVars->showPageToken = $this->showPageToken;

        $this->jsVars['currentModule'] = $this->defaultViewVars->currentModule;

        $varsJs = [
            'vars' => [
                'ui' => [
                    'messages' => $this->messages,
                    'lang' => $this->jsLangVars,
                    'idPrefix' => helper\helper::ID_START_PREFIX
                ],
                'jsvars' => $this->jsVars,
                'ajaxActionPath' => \fpcm\classes\tools::getFullControllerLink('ajax/'),
                'ajaxRefreshDisable' => defined('FPCM_DISABLE_AJAX_CRONJOBS_PUB') && FPCM_DISABLE_AJAX_CRONJOBS_PUB
            ]
        ];

        if ($this->showHeader === self::INCLUDE_HEADER_NONE && !$this->fullJsVarsNoheader) {
            $this->defaultViewVars->varsJs = $varsJs;
            $this->assign('theView', $this->defaultViewVars);
            return true;
        }

        $varsJs['vars']['ui']['notifyicon'] = \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_THEME, 'favicon-32x32.png');
        $varsJs['vars']['ui']['components'] = [
            'icon' => new helper\jsIcon(''),
            'input' => (string) (new helper\textInput('{{name}}', '{{id}}'))->setValue('{{value}}')
                        ->setText('{{text}}')->setClass('{{class}}')->setType('{{type}}')
                        ->setPlaceholder('{{placeholder}}')->setMaxlenght('255')
                        ->setDisplaySizesDefault()
        ];

        $varsJs['vars']['ui']['dialogTpl'] = new \fpcm\model\files\jsViewTemplate('dialog');
        $varsJs['vars']['actionPath'] = \fpcm\classes\tools::getFullControllerLink('');

        $this->defaultViewVars->varsJs = $varsJs;

        if ($this->showHeader === self::INCLUDE_HEADER_FULL) {
            $this->prepareNotifications();
        }

        /* @var $theView viewVars */
        $this->assign('theView', $this->defaultViewVars);
        return true;
    }

    /**
     *
     * @return bool
     * @since 5.2.0-a1
     */
    private function initAssignsWithDb() : bool
    {
        if ( !\fpcm\classes\baseconfig::dbConfigExists() ) {
            $this->defaultViewVars->version = \fpcm\classes\baseconfig::getVersionFromFile();
            $this->defaultViewVars->dateTimeMask = 'd.m.Y H:i';
            $this->defaultViewVars->dateTimeZone = 'Europe/Berlin';
            return false;
        }

        $this->defaultViewVars->version = trim($this->config->system_version) ? $this->config->system_version : \fpcm\classes\baseconfig::getVersionFromFile();
        $this->defaultViewVars->dateTimeMask = $this->config->system_dtmask;
        $this->defaultViewVars->dateTimeZone = $this->config->system_timezone;
        $this->defaultViewVars->frontEndLink = $this->config->system_url;
        $this->defaultViewVars->darkMode = $this->config->system_darkmode;
        return true;
    }

    /**
     * Init with existing session
     * @return bool
     * @since 5.2.0-a1
     */
    private function initAssignsWidthSession() : bool
    {
        if ( !$this->session?->exists() ) {
            return false;
        }

        $this->addJsLangVars(['SESSION_TIMEOUT']);
        $this->addJsVars(['sessionCheck' => true]);

        $this->defaultViewVars->currentUser = $this->session->getCurrentUser();
        $this->defaultViewVars->loginTime = $this->session->getLogin();

        if ($this->showHeader === self::INCLUDE_HEADER_FULL) {
            $this->defaultViewVars->navigation = (new \fpcm\model\theme\navigation($this->navigationActiveModule))->render();
        }

        $this->defaultViewVars->loggedIn = true;
        $this->defaultViewVars->permissions = \fpcm\classes\loader::getObject('\fpcm\model\permissions\permissions');

        $this->defaultViewVars->backdrop = (new \fpcm\model\files\backdropImage())->getUrl();
        $this->defaultViewVars->darkMode = $this->session->getCurrentUser()->getUserMeta()->system_darkmode;
        return true;
    }

    /**
     * Returns view path
     * @return string
     */
    public function getViewPath() : string
    {
        return $this->viewPath;
    }

    /**
     * Sets view path
     * @param string $viewName
     * @param string $module
     */
    public function setViewPath($viewName, $module = null)
    {
        $viewName .= '.php';

        $this->viewPath = $module
                        ? \fpcm\module\module::getTemplateDirByKey($module, $viewName)
                        : \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, $viewName);

        if (strpos($viewName, self::PATH_COMPONENTS) !== false) {
            $this->viewPath = str_replace(
                self::PATH_COMPONENTS,
                \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'components'.DIRECTORY_SEPARATOR),
                $viewName
            );
        }

        $this->viewName = $viewName;
    }

    /**
     * Return assigned view vars
     * @param string $var
     * @return mixed
     */
    public function getViewVars($var = false)
    {
        return $this->viewVars[$var] ?? $this->viewVars;
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
        $this->jsVars['fieldAutoFocus'] = (string) $elementId;
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

        $this->navigationActiveModule = $elementId;
        return true;
    }

    /**
     * Check if view was already rendered
     * @return bool
     */
    public function wasRendered() : bool
    {
        return $this->rendered;
    }

    /**
     * Returns Sha256-hash on view path
     * @return string
     * @since 4.1
     */
    public function getViewHash() : string
    {
        return $this->viewHash;
    }

    /**
     * Returns Sha256-hash on view path
     * @param string $viewHash
     * @return bool
     * @since 4.1
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
     * @since 4.1
     */
    public function setActiveTab(int $tab)
    {
        $this->jsVars['activeTab'] = $tab;
        $this->defaultViewVars->activeTab = $tab;
    }

    /**
     * Set <body>-tag CSS class
     * @param int $bodyClass
     * @return void
     * @since 4.2
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
            if (isset($this->jsVars['dataviews'])) {
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
     * do not use if you want to include tabs in another view!!!
     * @param string $tabsId
     * @param array $tabs
     * @param string $tabsClass
     * @since 4.3
     */
    public function addTabs(string $tabsId, array $tabs, string $tabsClass = '', int $active = -1)
    {
        if (count($tabs) === 1) {
            $active = 0;
        }

        $etRes = new \fpcm\events\view\extendTabsResult;
        $etRes->tabsId = $tabsId;
        $etRes->tabs = $tabs;
        $etRes->tabsClass = $tabsClass;
        $etRes->activeTab = $active;

        $ev = $this->events->trigger('view\extendTabs', $etRes);
        if (!$this->handleEventResultData($ev, 'view\extendTabs')) {
            return false;
        }        

        $etRes = $ev->getData();
        if (!$etRes instanceof \fpcm\events\view\extendTabsResult) {
            trigger_error("Returned data of view\extendTabs event must be an instance of \fpcm\events\view\extendTabsResult");
            return false;
        }        
        

        $tabsId = $etRes->tabsId;
        $tabs = $etRes->tabs;
        $tabsClass = $etRes->tabsClass;
        $active = $etRes->activeTab;

        if ($active > -1 && isset($tabs[$active])) {
            $tabs[$active]->setState(helper\tabItem::STATE_ACTIVE);

        }

        if (!str_starts_with($tabsId, helper\helper::ID_START_PREFIX)) {
            $tabsId = helper\helper::ID_START_PREFIX . $tabsId;
        }

        $this->setViewPath('components/tabs');
        $this->assign('tabsId', $tabsId);
        $this->assign('tabs', $tabs);
        $this->assign('tabsClass', $tabsClass);

        $this->setActiveTab($active);
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
     * Add off canvas widget to view
     * @param string $headline
     * @param array $filePath
     * @since 5.0.0-b6
     */
    public function addOffCanvas(string $headline, string $filePath)
    {
        $this->assign('offcanvasFile', $filePath . '.php');
        $this->assign('offcanvasHeadline', $headline);
        $this->defaultViewVars->showOffCanvas = true;
    }

    /**
     * Add items to right hand position in toolbar
     * @param string|array $data
     * @since 4.3
     */
    public function addToolbarRight(string|array $data)
    {
        if (is_array($data)) {
            $data = implode('', $data);
        }

        $this->defaultViewVars->toolbarItemRight = $data;
    }

    /**
     * Adds Javascript parameters for search form
     * @param array $fields
     * @param array $combinations
     * @since 5.2.0-a1
     */
    public function addSearchForm(array $fields, array $combinations = [])
    {
        $this->jsVars['searchForm'] = [
            'combinations' => $combinations,
            'fields' => $fields
        ];
    }

    /**
     * Add dialogs to view
     * @param array $dialogs
     * @since 5.2.4-b3
     */
    public function addDialogs(helper\dialog|array $dialogs)
    {
        if (!isset($this->jsVars['dialogs'])) {
            $this->jsVars['dialogs'] = [];
        }

        if (!is_array($dialogs)) {
            $dialogs = [$dialogs];
        }

        /* @var helper\dialog $dlg */
        foreach ($dialogs as $dlg) {

            if (!$dlg instanceof helper\dialog) {
                continue;
            }

            $this->jsVars['dialogs'][$dlg->getName()] = $dlg;
        }

    }

    /**
     * Add path for default forms.php view file
     * @param string str
     * @since 5.0-dev
     */
    public function includeForms(string $str)
    {
        $this->defaultViewVars->includeForms = $str . '/forms.php';
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

        $this->jsFiles = $this->events->trigger($type.'\addJsFiles', $this->jsFiles)->getData();
        $this->jsFilesLate = $this->events->trigger($type.'\addJsFilesLate', $this->jsFilesLate)->getData();
        $this->jsModuleFiles = $this->events->trigger($type.'\addJsModules', $this->jsModuleFiles)->getData();
        $this->cssFiles = $this->events->trigger($type.'\addCssFiles', $this->cssFiles)->getData();

        return true;
    }

    /**
     * Add AJAX page token to view
     * @param string $name
     * @return bool
     * @since 4.3
     */
    public function addAjaxPageToken(string $name) : bool
    {
        $name = 'ajax/'.$name;

        $this->jsVars['pageTokens'][$name] = (new \fpcm\classes\pageTokens)->refresh($name);
        return true;
    }

    /**
     * Initialize default CSS files
     * @return array
     */
    private function initCssFiles()
    {
        if (defined('FPCM_MODE_PUBVIEW') && FPCM_MODE_PUBVIEW) {
            return;
        }

        $this->addCssFiles([
            self::ROOTURL_LIB.'bootstrap/css/bootstrap.min.css',
            self::ROOTURL_LIB.'font-awesome/css/all.min.css',
            self::ROOTURL_CORE_THEME.'style.css'
        ]);

        return $this->cssFiles;
    }

    /**
     * Initialize default JavaScript files
     * @return array
     */
    private function initJsFiles()
    {
        if (defined('FPCM_MODE_PUBVIEW') && FPCM_MODE_PUBVIEW) {
            return;
        }

        $ext = self::getJsExt();

        $this->addJsFiles([
            \fpcm\components\components::getjQuery(),
            self::ROOTURL_LIB.'bootstrap/js/bootstrap.bundle.min.js',
            self::ROOTURL_LIB.'bs-autocomplete/autocomplete.js',
            '{$coreJs}/ajax' . $ext,
            '{$coreJs}/dom' . $ext,
            '{$coreJs}/ui/base' . $ext,
            '{$coreJs}/ui/dialogs' . $ext,
            '{$coreJs}/ui/webnotify' . $ext,
            '{$coreJs}/ui/notifications' . $ext,
            '{$coreJs}/ui/loader' . $ext,
            '{$coreJs}/ui/tabs' . $ext,
            '{$coreJs}/ui/pager' . $ext,
            '{$coreJs}/ui/deprecated' . $ext,
            '{$coreJs}/system' . $ext,
            '{$coreJs}/gsearch' . $ext
        ]);

        $this->addJsFilesLate([self::ROOTURL_CORE_JS.'init'.self::getJsExt()]);
        $this->setJsModuleFiles(['/ui/forms.js']);
        return $this->jsFiles;
    }

    /**
     * Inits components
     * @return void
     * @since 5.2.0-a1
     */
    private function initComponents() : void
    {
        if (defined('FPCM_MODE_PUBVIEW') && FPCM_MODE_PUBVIEW) {
            return;
        }

        $this->addFromCpomponent( \fpcm\components\components::getLightbox() );
        return;
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

    /**
     * Return JS file extension
     * @return string
     * @since 4.5
     */
    final public static function getJsExt() : string
    {
        $jsExt = '.js';
        if (!defined('FPCM_VIEW_JS_USE_MINIFIED') || !FPCM_VIEW_JS_USE_MINIFIED) {
            return $jsExt;
        }

        return '.min'.$jsExt;
    }

    /**
     * Handle event result
     * @param \fpcm\module\eventResult $ev
     * @param string $evName
     * @return bool
     */
    private function handleEventResultData(\fpcm\module\eventResult $ev, string $evName) : bool
    {
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event %s failed. Returned success = %s, continue = %s", $evName, $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        return true;
    }
}
