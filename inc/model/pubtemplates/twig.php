<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;


/**
 * Twig based public frontend template
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class twig {

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
     * Constructor
     * @param array $variables
     */
    final public function __construct()
    {
        $this->cachePath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_CACHE, '/twig');
    }

    /**
     * Load system template
     * @param string $path
     * @return void
     */
    final public function fromSystemTemplate(string $template, array $variables = []) : void
    {
        $this->template = $template;
        $this->base = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_STYLES);
        $this->paths = ['articles', 'comments', 'common'];

        $this->variables = $variables;
    }

    /**
     * Render Twig based template
     * @return string
     * @throws Exception
     */
    public function render() : string
    {

        require_once \fpcm\classes\loader::libGetFilePath('twig/vendor');

        $loader = new \Twig\Loader\FilesystemLoader($this->paths, $this->base);

        $this->variables['basePath'] = \fpcm\classes\tools::getFullControllerLink();
        
        $twig = new \Twig\Environment($loader, [
            'cache' => $this->cachePath,
            'autoescape' => false
        ]);

        return $twig->render($this->template, $this->variables);
    }

}
