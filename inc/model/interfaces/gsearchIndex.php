<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * FanPress CM Model global search indexer
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\interfaces
 */
interface gsearchIndex {

    /**
     * Get query string
     * @return string
     */
    public function getSearchQuery(): string;

    /**
     * Return container content
     * @return string
     */
    public function getCountQuery();

    /**
     * Return container content
     * @return string
     */
    public function getTableName();

}
