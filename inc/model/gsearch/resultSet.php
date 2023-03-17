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
class resultSet implements \JsonSerializable
{
    use \fpcm\model\traits\jsonSerializeReturnObject;

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

    /**
     * Init lightbox for result list
     * @var bool
     */
    private bool $lightbox;

    /**
     * Constructor
     * @param string $model
     * @param array $items
     * @param int $count
     * @param bool $lightbox
     */
    public function __construct(array $items, int $count, bool $lightbox)
    {
        $this->items = $items;
        $this->count = $count;
        $this->lightbox = $lightbox;
    }

    /**
     * Get list of items
     * @return array
     */
    public function getItems(): array {
        return $this->items;
    }

    /**
     * Get total search count
     * @return int
     */
    public function getCount(): int {
        return $this->count;
    }

    /**
     * Get lightbox init flag
     * @return bool
     */
    public function getLightbox(): bool
    {
        return $this->lightbox;
    }

}
