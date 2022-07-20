<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

use fpcm\classes\loader;

/**
 * Dashboard container model base
 * 
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class dashcontainer extends model implements \fpcm\model\interfaces\dashcontainer, \Stringable {

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
     * Berechtigungen
     * @var \fpcm\model\permissions\permissions
     * @since 4.4
     */
    protected $permissions;

    /**
     * Konstruktor
     */
    final public function __construct()
    {
        parent::__construct();
        $this->permissions = \fpcm\classes\loader::getObject('\fpcm\model\permissions\permissions');
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
     * Returns stored container position
     * @return int|bool
     * @since 4.1
     */
    final public function getStoredPosition()
    {
        $conf = loader::getObject('\fpcm\model\system\session')->getCurrentUser()->getUserMeta('dashboardpos');
        return is_array($conf) && isset($conf[$this->getName()]) ? $conf[$this->getName()] : false;
    }

    /**
     * Returns stored container position
     * @return int|bool
     * @since 4.1
     */
    final public function isDisabled() : bool
    {
        $conf = loader::getObject('\fpcm\model\system\session')->getCurrentUser()->getUserMeta('dashboard_containers_disabled');
        if (!is_array($conf)) {
            return false;
        }

        return in_array(get_class($this), $conf);
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
     * @since 3.1.3
     */
    public function getJavascriptVars()
    {
        return [];
    }

    /**
     * Return JavaScript language vars
     * @return array
     * @since 3.1.3
     */
    public function getJavascriptLangVars()
    {
        return [];
    }

    /**
     * Return JavaScript files
     * @return array
     * @since 3.1.3
     */
    public function getJavascriptFiles()
    {
        return [];
    }

    /**
     * Returns view vars
     * @return array
     * @since 3.1.3
     */
    public function getControllerViewVars()
    {
        return [];
    }
    
    /**
     * Return container button
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton() : ?\fpcm\view\helper\linkButton
    {
        return null;
    }

    /**
     * Gibt Liste mit zu Variablen zurück, welche an Dashboard-Controller-View übergeben werden sollen
     * @return string
     * @since 3.1.3
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
        
        $pos = $this->getStoredPosition();
        if ($pos === false) {
            $pos = $this->getPosition();
        }
        
        $btn = $this->getButton();
        if ($btn instanceof \fpcm\view\helper\button) {
            $btn->setClass('btn-sm')->overrideButtonType('link');
        }
        else {
            $btn = '<span class="d-block p-1">&nbsp;</span>';
        }
        
        $html = [];
        $html[] = '<div id="fpcm-dashboard-container-' . $this->getName() . '" class="fpcm dashboard-container-wrapper col-12 col-lg-' . $this->getWidth() . '  vh-25" data-cname="' . $this->getName() . '" data-cpos="' . $pos . '">';
        $html[] = ' <div class="card m-1 shadow-sm fpcm dashboard-container ui-background-white-50p ui-blurring">';
        $html[] = '     <div class="card-body pt-1 ps-1 pe-1 pb-2 ui-align-ellipsis">';
        $html[] = '         <h3 class="card-title fpcm dashboard-container headline ui-headline-font m-2 fs-5 border-1 border-bottom border-primary" title="' . strip_tags($this->language->translate($this->getHeadline())) . '">';
        $html[] = '             <span class="d-inline-block text-truncate w-100">' . $this->language->translate($this->getHeadline()) . '</span> ';
        $html[] = '         </h3>';
        $html[] = '         <div class="card-text fpcm dashboard-container content">' . $this->getContent() . '</div>';
        $html[] = '     </div>';
        $html[] = '     <div class="card-footer bg-transparent">';
        $html[] = '         <div class="row g-0">';
        $html[] = '             <div class="col flex-grow-1">';
        $html[] = '             ' . $btn;
        $html[] = '             </div>';
        $html[] = '             <div class="col-auto  align-self-center" draggable="true">';
        $html[] = '             ' . $this->getToggleButton();
        $html[] = '             ' . (new \fpcm\view\helper\icon('arrows-alt'))->setText('FILE_LIST_EDIT_MOVE')->setClass('fpcm dashboard-container-move');
        $html[] = '             </div>';
        $html[] = '         </div>';
        $html[] = '     </div>';
        $html[] = ' </div>';
        $html[] = '</div>';

        return implode(PHP_EOL, $html);
    }

    /**
     * Add disable button if not disabled
     * @return string
     * @since 5.1-dev
     */
    private function getToggleButton()
    {
        if ($this->isDisabled()) {
            return '';
        }

        return (new \fpcm\view\helper\button('disable'. md5($this->getName())))
                ->overrideButtonType('link')
                ->setClass('btn-sm shadow-none text-dark ui-dashboard-container-disable')
                ->setIcon('toggle-off')
                ->setIconOnly()
                ->setText('GLOBAL_DISABLE')
                ->setData(['cname' => base64_encode($this::class) ]);
    }
    
}

?>