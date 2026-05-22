<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Common dataview lists trait
 *
 * @package fpcm\controller\traits\articles\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait lists {

    /**
     * Data view object
     * @var \fpcm\components\dataView\dataView
     */
    protected \fpcm\components\dataView\dataView $dataView;

    /**
     * User list object
     * @var \fpcm\model\users\userList
     */
    protected \fpcm\model\users\userList $userList;

    /**
     *
     * @var array
     */
    protected array $users = [];

    /**
     * Current Page
     * @var int
     */
    protected ?int $page = 1;

    /**
     * Current offset
     * @var int
     */
    protected int $offset = 0;

}
