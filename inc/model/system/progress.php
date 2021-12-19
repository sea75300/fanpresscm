<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

/**
 * System config Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5
 */
class progress implements \JsonSerializable {

    /**
     * Current value
     * @var mixed
     */
    private $current = null;

    /**
     * Next execution possible
     * @var int
     */
    private $next = true;

    /**
     * Stop flag
     * @var bool
     */
    private $stop = false;

    /**
     * Data parameter
     * @var null|array
     */
    private $data = null;

    /**
     * Data parameter
     * @var null|array
     */
    private $unique = null;

    /**
     * Callback Function 
     * @var callable
     */
    private $callback = null;

    /**
     * Maximum execution time in seconds, 80% of max_execution_time
     * @var int
     */
    private $maxExec = 10;

    /**
     * Controller
     * @param callable $callback
     * @return void
     */
    public function __construct(callable $callback, string $unique = '')
    {
        $this->callback = $callback;
        $this->unique = $unique;

        $ini = ini_get('max_execution_time');
        if (!$ini) {
            return;
        }
        
        $this->maxExec = 3; //round(((int) $ini) * 0.8, 0, PHP_ROUND_HALF_DOWN);
    }

    /**
     * Get current value
     * @return mixed
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Next execution required/ possible
     * @return int
     */
    public function getNext()
    {
        return (int) $this->next;
    }

    /**
     * Get data
     * @return array|null
     */
    public function getData() : ?array
    {
        return $this->data;
    }

    /**
     * Check if progress was stopped
     * @return bool
     */
    public function getStop(): bool
    {
        return $this->stop;
    }

    /**
     * Set current value
     * @param mixed $current
     * @return $this
     */
    public function setCurrent($current)
    {
        $this->current = $current;
        return $this;
    }

    /**
     * Set Next execution flag
     * @param int|bool $next
     * @return $this
     */
    public function setNext($next)
    {
        $this->next = (int) $next;
        return $this;
    }

    /**
     * Set data
     * @param array $data
     * @return $this
     */
    public function setData(?array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Process progress
     * @return bool
     */
    final public function process() : bool
    {
        if ($this->callback === null) {
            return false;
        }

        $continue = $this->next;
        $cb = $this->callback;
        $start = time();

        while ($continue) {

            $continue = $cb($this->data, $this->current, $this->next, $this->stop);
            
            if ($this->stop) {
                $this->setNext(false);
                return true;
            }
            
            if (!$continue) {
                $continue = false;
                break;
            }

            if (time() - $start >= $this->maxExec) {
                $continue = false;
                break;
            }

        }

        unset($cb, $continue);
        return true;
    }

    /**
     * JSON data serialization
     * @return array
     * @ignore
     */
    public function jsonSerialize() : array
    {
        $return = get_object_vars($this);
        unset($return['callback'], $return['maxExec']);
        return $return;
    }


}
