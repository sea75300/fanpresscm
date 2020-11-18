<?php

/**
 * FanPress CM 4.x
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
final class navigationList implements \ArrayAccess {

    /**
     * Item list
     * @var array
     * @ignore
     */
    private $data;

    /**
     * @ignore
     */
    final public function __construct()
    {
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
        
        unset($this->data[$area][$id]);
        return true;
    }

    /**
     * Fetch item list
     * @return array
     */
    public function fetch() : array
    {
        return $this->data;
    }

    /**
     * Offset exists
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Returns offet value
     * @param string $offset
     * @return navigationItem
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Set offset to value
     * @param type $offset
     * @param \fpcm\model\theme\navigationItem $value
     * @return void
     * @see navigationList::add
     */
    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof navigationItem) {
            trigger_error('Item added to mńavigation list must be an instance of "\fpcm\model\theme\navigationItem".');
            return;
        }
        
        $this->add($offset, $value);
        return;
    }

    /**
     * Unset offset
     * @param string $offset
     * @return void
     * @ignore
     */
    public function offsetUnset($offset): void
    {
        return;
    }
    
    /**
     * Checks submenu if available and removed items without access permissions
     * @param \fpcm\model\theme\navigationItem $item
     * @return bool
     */
    private function checkSubmenu(navigationItem &$item) : bool
    {
        if (!$item->hasSubmenu()) {
            return true;
        }

        $submenu = array_filter($item->getSubmenu(), function (navigationItem $subItem) {
            return $subItem->isAccessible() === false ? false : true;
        });

        $item->setSubmenu($submenu);
        return true;
    }

}
