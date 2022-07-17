<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\theme;

/**
 * ACP navigation navigation list object
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\theme
 * @since 4.5
 */
final class navigationList {

    /**
     * Item list
     * @var array
     * @ignore
     */
    private $data;

    /**
     * Active navigation item
     * @var string
     * @since 5.0.0-a4
     */
    private $activeNavItem = '';

    /**
     * @ignore
     */
    final public function __construct(string $activeNavItem = '')
    {
        $this->activeNavItem = $activeNavItem ?? \fpcm\classes\tools::getNavigationActiveCheckStr();

        $this->data = [
            navigationItem::AREA_DASHBOARD => [],
            navigationItem::AREA_ADDNEWS => [],
            navigationItem::AREA_EDITNEWS => [],
            navigationItem::AREA_COMMENTS => [],
            navigationItem::AREA_FILEMANAGER => [],
            navigationItem::AREA_OPTIONS => [],
            navigationItem::AREA_MODULES => [],
            navigationItem::AREA_TRASH => [],
            navigationItem::AREA_AFTER => []
        ];
    }

    /**
     * Add item to navigation
     * @param string $area
     * @param \fpcm\model\theme\navigationItem $item
     * @return bool
     */
    public function add(string $area, navigationItem $item) : bool
    {
        if (!isset($this->data[$area])) {
            trigger_error('Call to undefined navigation area: '.$area);
            return false;
        }

        if ($item->isAccessible() === false) {
            return true;
        }

        $this->checkSubmenu($item);
        $this->data[$area][$item->getId()] = $item;
        return true;
    }

    /**
     * Remove item from navigation
     * @param string $area
     * @param string $id
     * @return bool
     */
    public function remove(string $area, string $id) : bool
    {
        if (!isset($this->data[$area])) {
            trigger_error('Call to undefined navigation area: '.$area);
            return false;
        }
        
        if ($id == $area) {
            $this->data[$area] = [];
            return true;
        }
        
        unset($this->data[$area][$id]);
        return true;
    }

    /**
     * Fetch item list
     * @return array
     */
    public function fetch(string $str = '') : array
    {
        if (trim($str)) {
            return $this->data[$str];
        }
        
        return $this->data;
    }
    
    /**
     * Checks submenu if available and removed items without access permissions
     * @param \fpcm\model\theme\navigationItem $item
     * @return bool
     */
    private function checkSubmenu(navigationItem &$item) : bool
    {
        $item->initDefault($this->activeNavItem);
        if (!$item->hasSubmenu()) {
            return true;
        }

        $submenu = array_filter($item->getSubmenu(), function (navigationItem $subItem) {

            if ($subItem->isAccessible() === false) {
                return false;
            }

            return true;
        });

        foreach ($submenu as $subItem) {
            $subItem->initDefault($this->activeNavItem);
            $subItem->setIsSubmenuItem(true);
            $subItem->setParent($item);
        }

        $item->setSubmenu($submenu);
        return true;
    }

}
