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
 */
class twig
{

    const VAR_DEBUG = 'debug';

    const VAR_LOGGED_IN = 'is_logged_in';

    const VAR_PERMISSIONS = 'permissions';

    const VAR_LANG = 'lang';

    const VAR_BASE_PATH = 'basePath';

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
     * Render Twig based template
     * @return string
     * @throws Exception
     */
    public function render() : string
    {
        $this->beforeRender();

        require_once \fpcm\classes\loader::libGetFilePath('twig/vendor');

        $loader = new \Twig\Loader\FilesystemLoader($this->paths, $this->base);

        $this->variables['fpcm'] = [
            self::VAR_BASE_PATH => \fpcm\classes\tools::getFullControllerLink(),
            self::VAR_LANG => $this->getLanguage()->getAll(),
            self::VAR_DEBUG => \fpcm\classes\baseconfig::debugModeActive(),
            self::VAR_LOGGED_IN => \fpcm\model\system\session::getInstance()->exists(),
            self::VAR_PERMISSIONS => $this->fetchPermissions()
        ];

        $twig = new \Twig\Environment($loader, [
            'cache' => $this->cachePath,
            'autoescape' => false,
            'debug' => (bool) \fpcm\classes\baseconfig::debugModeActive()
        ]);

        if (\fpcm\classes\baseconfig::debugModeActive()) {
            $twig->addExtension(new \Twig\Extension\DebugExtension());
        }

        $twig->addFunction(new \Twig\TwigFunction('get_pager', function (array $pager) {

            fpcmLogSystem($pager);
            
            list($items, $perPage, $current, $archive, $action) = $pager;

            $count = ceil($items / $perPage);
            if (!$count) {
                return [];
            }

            $result = [
                'pages' => [],
                'next' => $current < $count ? sprintf('%s?module=%s&page=%d', $this->config->system_url, $action, $current + 1) : '',
                'previous' => $current > 1 ? sprintf('%s?module=%s&page=%d', $this->config->system_url, $action, $current - 1) : '',
                'archive' => $archive ? sprintf('%s?module=fpcm/archive', $this->config->system_url) : ''
            ];
            
            foreach (array_fill(1, $count, []) as $key => &$value) {

                $result['pages'][] = [
                    'label' => $key,
                    'class' => $key == $current || ($key == 1 && !$current) ? 'fpcm-pub-pagination-page-active' : '',
                    'link'  => $key >= 2
                            ? sprintf('%s?module=%s&page=%d', $this->config->system_url, $action, $key)
                            : sprintf('%s?module=%s', $this->config->system_url, $action)
                ];
            }

            return $result;
        }));

        return $twig->render($this->template, $this->variables);
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
     * before render event
     * @return bool
     */
    public function beforeRender() : bool
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

}
