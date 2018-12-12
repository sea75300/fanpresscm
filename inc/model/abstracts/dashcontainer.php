<?php

/**
 * Dashboard container model
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * Dashboard container model base
 * 
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
abstract class dashcontainer extends model implements \fpcm\model\interfaces\dashcontainer {

    /**
     * Default container cache module
     */
    const CACHE_M0DULE_DASHBOARD = 'dashboard';

    /**
     * Container height big
     */
    const DASHBOARD_HEIGHT_VERYBIG = 'very-big';

    /**
     * Container height big
     */
    const DASHBOARD_HEIGHT_BIG = 'big';

    /**
     * Container height medium
     */
    const DASHBOARD_HEIGHT_MEDIUM = 'middle';

    /**
     * Container height medium
     */
    const DASHBOARD_HEIGHT_SMALL_MEDIUM = 'small-medium';

    /**
     * Container height SMALL
     */
    const DASHBOARD_HEIGHT_SMALL = 'small';

    /**
     * Container max position
     */
    const DASHBOARD_POS_MAX = '{{max}}';

    /**
     * Container-Name
     * @var string
     */
    protected $name = '';

    /**
     * ggf. nötige Container-Berechtigungen
     * @var array
     */
    protected $checkPermissions = [];

    /**
     * Container-Position
     * @var int 
     */
    protected $position = 0;

    /**
     * Konstruktor
     */
    final public function __construct()
    {
        parent::__construct();
        $this->initObjects();
    }

    /**
     * Cache-Name zurückgeben
     * @param string $addName
     * @return string
     */
    public function getCacheName($addName = '')
    {
        $this->cacheName = self::CACHE_M0DULE_DASHBOARD . '/' . $this->getName() . $addName;
        return $this->cacheName;
    }

    /**
     * Container-Breite-CSS-Klasse (big/small) zurückgeben
     * @return string
     */
    public function getWidth()
    {
        return 4;
    }

    /**
     * Container-Höhe-Klasse (big/middle/small) zurückgeben
     * @return string
     */
    public function getHeight()
    {
        return self::DASHBOARD_HEIGHT_SMALL;
    }

    /**
     * Container-Berechtigungen, die geprüft werden müssen, zurückgeben
     * @return string
     */
    public function getPermissions()
    {
        return [];
    }

    /**
     * Return JavaScript view vars
     * @return array
     * @since FPCM 3.1.3
     */
    public function getJavascriptVars()
    {
        return [];
    }

    /**
     * Return JavaScript language vars
     * @return array
     * @since FPCM 3.1.3
     */
    public function getJavascriptLangVars()
    {
        return [];
    }

    /**
     * Return JavaScript files
     * @return array
     * @since FPCM 3.1.3
     */
    public function getJavascriptFiles()
    {
        return [];
    }

    /**
     * Returns view vars
     * @return array
     * @since FPCM 3.1.3
     */
    public function getControllerViewVars()
    {
        return [];
    }

    /**
     * Gibt Liste mit zu Variablen zurück, welche an Dashboard-Controller-View übergeben werden sollen
     * @return ''
     * @since FPCM 3.1.3
     */
    public function getView()
    {
        return '';
    }

    /**
     * Initialize objects
     * @return bool
     */
    protected function initObjects()
    {
        return true;
    }

    /**
     * Container-Name zurückgeben
     * @return string
     */
    abstract public function getName();

    /**
     * container-Objekt via print/echo ausgeben
     * @return string
     */
    final public function __toString()
    {
        $html = [];
        $html[] = '<div id="fpcm-dashboard-container-' . $this->getName() . '" class="fpcm-dashboard-container fpcm-dashboard-container-' . $this->getName() . ' col-sm-12 col-md-' . $this->getWidth() . ' fpcm-dashboard-container-width-' . $this->getWidth() . ' fpcm-dashboard-container-height-' . $this->getHeight() . ' fpcm-ui-padding-none-lr" data-container="' . $this->getName() . '">';
        $html[] = ' <div class="fpcm-dashboard-container-inner ui-widget-content ui-corner-all ui-state-normal">';
        $html[] = '     <div class="fpcm-dashboard-container-header">';
        $html[] = '         <h3 class="fpcm-dashboard-container-headline ui-corner-top ui-corner-all">' . $this->language->translate($this->getHeadline()) . ' '.(new \fpcm\view\helper\icon('arrows-alt'))->setClass('fpcm-dashboard-container-move fpcm-ui-float-right').'</h3>';
        $html[] = '     </div>';
        $html[] = '     <div class="fpcm-dashboard-container-content">' . $this->getContent() . '</div>';
        $html[] = ' </div>';
        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }

}

?>