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
    public function getSearchQuery(): \fpcm\model\dbal\selectParams;

    /**
     * Get count query string
     * @return string
     */
    public function getCountQuery(): \fpcm\model\dbal\selectParams;

    /**
     * Return link to element link
     * @return string
     */
    public function getElementLink(mixed $param): string;

    /**
     * Return link icon
     * @return \fpcm\view\helper\icon
     */
    public function getElementIcon(): \fpcm\view\helper\icon;

    /**
     * Prepare result text
     * @param string $text
     * @return string
     */
    public function prepareText(string $text): string;

}
