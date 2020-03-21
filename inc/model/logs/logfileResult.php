<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\logs;

/**
 * Log file result object
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.4
 */
final class logfileResult {

    /**
     * Cols callback function
     * @var callable
     */
    private $colsCallback;

    /**
     * Row callback function
     * @var callable
     */
    private $rowCallback;

    /**
     * Logfile items
     * @var array
     */
    private $items;

    /**
     * Item count
     * @var int
     */
    private $itemsCount;

    /**
     * Logfile size
     * @var int
     */
    private $size;

    /**
     * Return logfile content as object/ dataview
     * @var bool
     */
    private $object;

    /**
     * Constructor
     * @param array $items
     * @param int $itemsCount
     * @param int $size
     * @param callable $colsCallback
     * @param callable $rowCallback
     * @param bool $object
     */
    public function __construct(array $items, $itemsCount, int $size, $colsCallback, $rowCallback, bool $object = true)
    {
        $this->colsCallback = is_callable($colsCallback) ? $colsCallback : null;
        $this->rowCallback = is_callable($rowCallback) ? $rowCallback : null;
        $this->items = $items;
        $this->itemsCount = $itemsCount;
        $this->size = $size;
        $this->object = $object;
    }
    
    /**
     * Fetch log data
     * @return array
     */
    public function fetchData(): array
    {
        return $this->items;
    }

    /**
     * Fetch number of items
     * @return int
     */
    public function getItemsCount()
    {
        return $this->itemsCount;
    }

    /**
     * Get logfile size
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Check if logfile content is to be displayed as data view
     * @return bool
     */
    public function asObject(): bool
    {
        return $this->object;
    }

    /**
     * Return cal callback function
     * @return callable
     */
    public function colsCallback(): callable
    {
        return $this->colsCallback;
    }

    /**
     * Return row callback function
     * @return callable
     */
    public function rowCallback(): callable {
        return $this->rowCallback;
    }


}

?>