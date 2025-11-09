<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard;

/**
 * Recent articles dashboard container object
 *
 * @package fpcm\model\dashboard\lists
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class containers extends \fpcm\model\abstracts\staticModel implements \ArrayAccess
{
    private string $prefix = '';

    /**
     * Add single container
     * @param string $container
     * @return bool
     */
    public function addContainer(string $container) : bool
    {
        $this->data[] = sprintf('%s\%s', $this->prefix, $container);
        return true;
    }

    /**
     * Add multiple containers
     * @param array $containers items must be string
     * @return bool
     */
    public function addContainers(array $containers) : bool
    {
        $containers = array_map(fn($cc) => sprintf('%s\%s', $this->prefix, $cc), $containers);
        $this->data = array_merge_recursive($containers);
        $this->prefix = '';
        return true;
    }

    /**
     * Get conatiners list
     * @return array
     */
    public function getContainers() : array
    {
        if (!is_array($this->data)) {
            return [];
        }

        return $this->data;
    }

    /**
     * Check if containers included
     * @return bool
     */
    public function hasContainers() : bool
    {
        return is_array($this->data) && count($this->data);
    }

    /**
     * Set conatiern prefix
     * @param string $prefix
     * @return $this
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = sprintf('\fpcm\%s', $prefix);
        return $this;
    }

    /**
     * Reset prefix
     * @return void
     */
    final public function resetPrefix() : void
    {
        $this->prefix = '';
    }

    /**
     *
     * @param mixed $offset
     * @return bool
     * @ignore
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->data[$offset] ?: false;
    }

    /**
     *
     * @param mixed $offset
     * @return mixed
     * @ignore
     */
    public function offsetGet(mixed $offset): mixed
    {
        return null;
    }

    /**
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     * @ignore
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        trigger_error(sprintf(
            'Accessing dashboard container list in event "%s" is deprecated. Use %s::%s/%s method instead in "%s".',
            'dashboardContainersLoad',
            __CLASS__,
            'addContainer',
            'addContainers',
            htmlspecialchars($value)
        ), E_USER_DEPRECATED);

        $this->data[] = $value;
    }

    /**
     *
     * @param mixed $offset
     * @return void
     * @ignore
     */
    public function offsetUnset(mixed $offset): void
    {
        return;
    }

}
