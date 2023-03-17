<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\search;

/**
 * Module-Event: models
 * 
 * Event is executed before global search is executed to add additional search models
 * Return value eventResult object with array of class names with implements \fpcm\model\interfaces\gsearchIndex
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.1-dev
 */
final class models extends \fpcm\events\abstracts\event {

}
