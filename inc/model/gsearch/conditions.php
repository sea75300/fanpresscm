<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\gsearch;

/**
 * Global search indexer conditions
 * 
 * @package fpcm\model\gsearch
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class conditions
{
    /**
     * Search term
     * @var string
     */
    private string $term;

    /**
     * Constructor
     * @param string $term
     */
    public function __construct(string $term)
    {
        $this->term = $term;
    }

    /**
     * Return search term
     * @return string
     */
    public function getTerm(): string {
        return $this->term;
    }

}
