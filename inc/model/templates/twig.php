<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates;


/**
 * Twig based public frontend template
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
class twig
{

    const VAR_DEBUG = 'debug';

    const VAR_LOGGED_IN = 'is_logged_in';

    const VAR_PERMISSIONS = 'permissions';

    const VAR_BASE_PATH = 'basePath';

    const VAR_SHARE = 'shares';

    const VAR_COMMENTS_ACTIVE = 'commentsActive';

    const VAR_JS_VARS = 'scriptVars';

    /**
     * base template file name
     * @var string
     */
    private string $base = '';

    /**
     * Template file name
     * @var string
     */
    private string $template = '';

    /**
     * Template lookup paths
     * @var array
     */
    private array $paths = [];

    /**
     * Template variables
     * @var array
     */
    private array $variables = [];

    /**
     * Script variables
     * @var scriptVars|null
     */
    private ?scriptVars $scriptVars = null;

    /**
     * Cache path
     * @var array
     */
    private string $cachePath = '';

    /**
     * Config object instance
     * @var \fpcm\model\system\config|null
     */
    private ?\fpcm\model\system\config $config = null;

    /**
     * Language object instance
     * @var \fpcm\classes\language|null
     */
    private ?\fpcm\classes\language $language = null;

    /**
     * Permissions object instance
     * @var \fpcm\model\permissions\permissions
     */
    private ?\fpcm\model\permissions\permissions $permissions = null;

    /**
     * Constructor
     * @param array $variables
     */
    final public function __construct()
    {
        $this->cachePath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_CACHE, '/twig');
        $this->onInit();
    }

    /**
     * Load system template
     * @param string $path
     * @return bool
     */
    final public function fromSystemTemplate(string $template, array $variables = []) : bool
    {
        $this->template = $template;
        $this->base = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_STYLES);
        $this->paths = ['articles', 'comments', 'common'];
        $this->variables = $variables;
        return true;
    }

    /**
     * Add script vars to template
     * @param scriptVars $vars
     * @return bool
     */
    final public function setScriptVars(scriptVars $vars) : bool
    {
        $this->scriptVars = $vars;
        return true;
    }

    /**
     * Render Twig based template
     * @return string
     * @throws Exception
     */
    public function render() : string
    {

        try {

            $this->onBeforeRender();

            require_once \fpcm\classes\loader::libGetFilePath('twig/vendor');

            $loader = new \Twig\Loader\FilesystemLoader($this->paths, $this->base);

            $twig = new \Twig\Environment($loader, [
                'cache' => $this->cachePath,
                'autoescape' => false,
                'debug' => (bool) \fpcm\classes\baseconfig::debugModeActive()
            ]);

            $twig->addGlobal('fpcm', [
                self::VAR_BASE_PATH => \fpcm\classes\tools::getFullControllerLink(),
                self::VAR_DEBUG => \fpcm\classes\baseconfig::debugModeActive(),
                self::VAR_LOGGED_IN => \fpcm\model\system\session::getInstance()->exists(),
                self::VAR_PERMISSIONS => $this->fetchPermissions(),
                self::VAR_SHARE => [
                    'show' => $this->getConfig()->system_show_share,
                    'count' => $this->getConfig()->system_share_count
                ],
                self::VAR_COMMENTS_ACTIVE => $this->getConfig()->system_comments_enabled,
                self::VAR_JS_VARS => $this->scriptVars

            ]);

            if (\fpcm\classes\baseconfig::debugModeActive()) {
                $twig->addExtension(new \Twig\Extension\DebugExtension());
            }

            $twig->addFunction(new \Twig\TwigFunction(
                'translate',
                function (string $var, ...$replacements) {
                    return $this->getLanguage()->translate($var, $replacements, true);
                }
            ));

            $this->registerFunctions($twig);

            $this->onRender($twig);

            return $twig->render($this->template, $this->variables);
        } catch (\Exception $exc) {
            trigger_error($exc, E_USER_NOTICE);
            return 'TWIG ERROR!';
        }

    }

    /**
     * On init event
     * @return bool
     */
    public function onInit() : bool
    {
        return true;
    }

    /**
     * Before render event
     * @return bool
     */
    public function onBeforeRender() : bool
    {
        return true;
    }

    /**
     * On render event
     * @param \Twig\Environment $twig
     * @return bool
     */
    public function onRender(\Twig\Environment &$twig) : bool
    {
        return true;
    }

    /**
     * Register functions
     * @param \Twig\Environment $twig
     * @return bool
     */
    public function registerFunctions(\Twig\Environment &$twig) : bool
    {
        return true;
    }

    /**
     * Fetch permission array
     * @return array
     */
    public function fetchPermissions() : array
    {
        return [];
    }

    /**
     * Get config instance object
     * @return \fpcm\model\system\config
     */
    final protected function getConfig() : \fpcm\model\system\config
    {
        if ($this->config === null) {
            $this->config = \fpcm\model\system\config::getInstance();
        }

        return $this->config;
    }

    /**
     * Get language instance object
     * @return \fpcm\classes\language
     */
    final protected function getLanguage() : \fpcm\classes\language
    {
        if ($this->language === null) {
            $this->language = \fpcm\classes\language::getInstance();
        }

        return $this->language;
    }

    /**
     * Get permissions instance object
     * @return \fpcm\model\permissions\permissions
     */
    final protected function getPermissions() : \fpcm\model\permissions\permissions
    {
        if ($this->permissions === null) {
            $this->permissions = new \fpcm\model\permissions\permissions();
        }

        return $this->permissions;
    }

    /**
     * Remove cache folder
     * @return bool
     */
    final public function clearCache() : bool
    {
        return \fpcm\model\files\ops::deleteRecursive($this->cachePath);
    }
}
