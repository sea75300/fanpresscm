<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\theme;

/**
 * ACP navigation item object
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\theme
 * @since FPCM 3.5
 */
class navigationItem extends \fpcm\model\abstracts\staticModel {

    const AREA_DASHBOARD = 'dashboard';
    const AREA_ADDNEWS = 'addnews';
    const AREA_EDITNEWS = 'editnews';
    const AREA_COMMENTS = 'comments';
    const AREA_FILEMANAGER = 'filemanager';
    const AREA_OPTIONS = 'options';
    const AREA_MODULES = 'modules';
    const AREA_TRASH = 'trashes';
    const AREA_AFTER = 'after';
    
    /**
     * im Navigation angezeigte Beschreibung
     * @var string
     */
    protected $description = '';

    /**
     * Zielurl
     * @var string
     */
    protected $url = '';

    /**
     * CSS-Klassen für Icon
     * @var string
     */
    protected $icon = '';

    /**
     * allgemeine CSS-Klassen
     * @var string
     */
    protected $class = '';

    /**
     * Wrapper-CSS-Klassen
     * @var string
     */
    protected $wrapperClass = '';

    /**
     * Item-ID
     * @var string
     */
    protected $id = '';

    /**
     * Eltern-Bereich des Menü-Eintrages
     * @var string
     */
    protected $parent = 'after';

    /**
     * Berechtigungen
     * @var array
     * @deprecated since version FPCM 4.4
     */
    protected $permission = [];

    /**
     * Permissions flag for access
     * @var bool
     */
    protected $accessible = null;

    /**
     * Untermenü, array mit Elementen vom Typ navigationItem
     * @var array
     */
    protected $submenu = [];

    /**
     * Status, ob auf Element Platzhalter folgt
     * @var bool
     */
    protected $spacer = false;

    /**
     * aktuell ausgewähltes Modul
     * @var string
     */
    private $currentModule = '';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language');

        $this->id = uniqid('fpcm-nav-item');
        $this->currentModule = \fpcm\classes\tools::getNavigationActiveCheckStr();
    }

    /**
     * Beschreibung zurückgeben
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Zielurl zurückgeben
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Zielurl zurückgeben
     * @return string
     */
    public function getFullUrl()
    {
        return \fpcm\classes\dirs::getRootUrl('index.php?module=' . $this->url);
    }

    /**
     * CSS-Klassen für Icon zurückgeben
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * allgemeine CSS-Klassen zurückgeben
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * allgemeine Wrapper-CSS-Klassen zurückgeben
     * @return string
     */
    public function getWrapperClass()
    {
        return $this->wrapperClass;
    }

    /**
     * Item-ID zurückgeben
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Eltern-Bereich zurückgeben
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Berechtigungen zurückgeben
     * @return array
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Untermenü-Elemente zurückgegen
     * @return array
     */
    public function getSubmenu()
    {
        return $this->submenu;
    }

    /**
     * Beschreibung setzen
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $this->language->translate($description);
        return $this;
    }

    /**
     * Zielurl setzen
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * CSS-Klassen für Icon setzen
     * @param string $icon
     * @param string $prefix
     * @param bool $useFa
     * @return $this
     */
    public function setIcon(string $icon, string $prefix = 'fa', bool $useFa = true)
    {
        $this->icon = (string) new \fpcm\view\helper\icon($icon, $prefix, $useFa);
        return $this;
    }

    /**
     * allgemeine CSS-Klassen setzen
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * allgemeine WrapperCSS-Klassen setzen
     * @param string $wrapperClass
     */
    public function setWrapperClass($wrapperClass)
    {
        $this->wrapperClass = $wrapperClass;
        return $this;
    }

    /**
     * Item-ID setzen
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Eltern-Bereich setzen
     * @param string $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Berechtigungen setzen
     * @param array $permission
     */
    public function setPermission(array $permission)
    {
        $this->permission = $permission;
        return $this;
    }

    /**
     * Untermenü-Array füllen
     * @param array $submenu
     */
    public function setSubmenu(array $submenu)
    {
        $this->submenu = $submenu;
        return $this;
    }

    /**
     * Status, dass Spacer nach Element angezeigt werden soll
     * @param bool $spacer
     */
    public function setSpacer($spacer)
    {
        $this->spacer = (bool) $spacer;
        return $this;
    }

    /**
     * Status, ob Spacer nach Element angezeigt werden soll
     * @return bool
     */
    public function hasSpacer()
    {
        return (bool) $this->spacer;
    }

    /**
     * Status, Untermenü-Einträge existieren
     * @return bool
     */
    public function hasSubmenu()
    {
        return count($this->submenu) ? true : false;
    }

    /**
     * Status, Berechtigungen geprüft werden müssen
     * @return bool
     */
    public function hasPermission()
    {
        return count($this->permission) ? true : false;
    }

    /**
     * Status zurückgeben, ob Ziel aktiv ist
     * @return bool
     */
    public function isActive()
    {
        return ( substr($this->url, 0, strlen($this->currentModule)) === $this->currentModule ? true : false );
    }

    /**
     * Returns true, is navigation is is accessible
     * @return bool
     */
    public function isAccessible()
    {
        return $this->accessible;
    }

    /**
     * Set accessible mode
     * @param bool $accessible
     * @return $this
     */
    public function setAccessible(bool $accessible) {
        $this->accessible = $accessible;
        return $this;
    }
    
    /**
     * @ignore
     * @return array
     */
    public function __sleep()
    {
        $this->config = null;
        $this->language = null;

        return ['description', 'url', 'icon', 'class', 'id', 'parent', 'accessible', 'permission', 'submenu', 'spacer', 'wrapperClass'];
    }

    /**
     * @ignore
     * @return void
     */
    public function __wakeup()
    {
        $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language');
        $this->currentModule = \fpcm\classes\tools::getNavigationActiveCheckStr();
    }

}
