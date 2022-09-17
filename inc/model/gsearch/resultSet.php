<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\gsearch;

/**
 * Global search indexer result set
 * 
 * @package fpcm\model\gsearch
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class resultSet
{
    /**
     * List of resultItem objects
     * @var array
     */
    private array $items;

    /**
     * Search total count
     * @var int
     */
    private int $count;

    public function __construct(array $items, int $count)
    {
        $this->items = $items;
        $this->count = $count;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getCount(): int
    {
        return $this->count;
    }

}
