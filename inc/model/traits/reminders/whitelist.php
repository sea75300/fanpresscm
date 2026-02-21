<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits\reminders;

/**
 * Reminer whitelist trait
 *
 * @package fpcm\model\traits\reminders
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-b2
 */
trait whitelist {

    /**
     * Reminder object whitelist
     * @var array
     */
    private array $whitelist = [
        'fpcm\model\files\mediaFile'
    ];

    /**
     * Check is object type is white listed
     * @param string $type
     * @return bool
     */
    protected function isListed(string $type) : bool
    {
        $return = in_array($type, $this->whitelist);
        if (!$return) {
            trigger_error(sprintf('Instance of %s is not white listed for reminders!', $type));
        }
        
        return $return;
    }
    
}
