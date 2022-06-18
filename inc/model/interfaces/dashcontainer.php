<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * FanPress CM Model Dashboard container Interface
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\interfaces
 */
interface dashcontainer {

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline();

    /**
     * Return container content
     * @return string
     */
    public function getContent();

    /**
     * Return container position
     * @return int
     */
    public function getPosition();
}
