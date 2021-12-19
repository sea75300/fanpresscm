<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * Cli progress bar object
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @author mayconbordin, https://gist.github.com/mayconbordin
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4-b5
 */
final class progress {

    /* Maximum chars for one cli line */
    const LINE_MAX_CHARS = 70;

    /* Progress char */
    const PROGRESS_CHAR = '#';

    /**
     * Maxmimum progress value
     * @var int
     */
    private $maxValue = 0;

    /**
     * Current value used to calculate char output
     * @var int
     */
    private $currentValue = 0;

    /**
     * Cli flag
     * @var bool
     */
    private $isCli = true;

    /**
     * Constructor
     * @param int $maxValue
     * @param int $currentValue
     * @param callable $displayCallback
     */
    public function __construct(int $maxValue, int $currentValue = 0)
    {
        $this->maxValue = $maxValue;
        $this->currentValue = $currentValue;
        $this->isCli = \fpcm\classes\baseconfig::isCli();
    }

    /**
     * Update current value for calculation
     * @param int $currentValue
     * @return $this
     */
    public function setCurrentValue(int $currentValue)
    {
        $this->currentValue = $currentValue;
        return $this;
    }

    /**
     * Show progress output
     * @return bool
     */
    public function output() : bool
    {
        if (!$this->isCli) {
            return false;
        }

        $percent = round(($this->currentValue * 100) / $this->maxValue);
        $bars = round((self::LINE_MAX_CHARS * $percent) / 100);
        print sprintf("%s%%[%s#%s]\r", $percent, str_repeat(self::PROGRESS_CHAR, $bars), str_repeat(" ", self::LINE_MAX_CHARS-$bars));
        return true;
    }

    /**
     * Destructor
     * @ignore
     */
    public function __destruct()
    {
        if (!$this->isCli) {
            return;
        }

        print PHP_EOL;
    }


}
