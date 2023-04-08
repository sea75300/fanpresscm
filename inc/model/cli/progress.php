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
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4-b5
 */
final class progress {

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
     * Maximum chars for one cli line
     * @var int
     */
    private $maxLineChars = 70;

    /**
     * Output text
     * @var string
     */
    private $outputText = '';

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
        $this->isCli = \fpcm\classes\baseconfig::isCli() && !defined('FPCM_CLI_OUTPUT_NONE');
        
        if (!$this->isCli) {
            return;
        }
        
        try {
            $cols = exec('tput cols');
            if ($cols === false) {
                return;
            }

            $this->maxLineChars = (int) $cols - 8;

        } catch (\Exception $exc) {
            io::output($exc->getMessage(), true);
        }

        
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
     * Set output text for cli
     * @param string $outputText
     * @return $this
     */
    public function setOutputText(string $outputText)
    {
        $this->outputText = substr($outputText, 0, floor($this->maxLineChars / 2)) . ': ';
        $this->maxLineChars -= mb_strlen($this->outputText);
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
        $bars = round(($this->maxLineChars * $percent) / 100);
        print sprintf("%s%s%%[%s#%s] \r", $this->outputText, $percent, str_repeat(self::PROGRESS_CHAR, $bars), str_repeat(" ", $this->maxLineChars - $bars));
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
