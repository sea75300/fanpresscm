<?php

/**
 * FanPress CM 4.x
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
     * Data parameter
     * @var null|array
     */
    private $data = null;

    /**
     *
     * @var callable
     */
    private $callback = null;

    /**
     *
     * @var int
     */
    private $maxExec = 5;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;   

        $ini = 0; //ini_get('max_execution_time');
        if (!$ini) {
            return;
        }
        
        $this->maxExec = round(((int) $ini) * 0.8, 0, PHP_ROUND_HALF_DOWN);
    }

    public function getCurrent()
    {
        return $this->current;
    }

    public function getNext()
    {
        return (int) $this->next;
    }

    public function getData() : ?array
    {
        return $this->data;
    }

    public function setCurrent($current)
    {
        $this->current = $current;
        return $this;
    }

    public function setNext($next)
    {
        $this->next = (int) $next;
        return $this;
    }

    public function setData(?array $data)
    {
        $this->data = $data;
        return $this;
    }
    
    final public function process() : bool
    {
        if ($this->callback === null) {
            return false;
        }

        $continue = $this->next;
        $cb = $this->callback;
        $start = time();

        while ($continue) {

            $continue = $cb($this->data, $this->current, $this->next);
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
     * JSON data
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
