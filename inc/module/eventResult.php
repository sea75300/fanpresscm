<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * FanPress CM Event result object
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\module
 * @since 5.1-dev
 */
final class eventResult {

    /**
     * 
     * @var bool
     */
    private $successed = true;

    /**
     * 
     * @var mixed
     */
    private $data;

    /**
     * 
     * @var bool
     */
    private $continue = true;

    /**
     * Get success status
     * @return bool
     */
    public function getSuccessed(): bool
    {
        return $this->successed;
    }

    /**
     * Get returned data
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Continue processing
     * @return bool
     */
    public function getContinue(): bool
    {
        return $this->continue;
    }

    /**
     * Set success status
     * @param bool $successed
     * @return $this
     */
    public function setSuccessed(bool $successed)
    {
        $this->successed = $successed;
        return $this;
    }

    /**
     * Set returned data
     * @param mixed $data
     * @return $this
     */
    public function setData(mixed $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Prevent or continue processing
     * @param bool $continue
     * @return $this
     */
    public function setContinue(bool $continue)
    {
        $this->continue = $continue;
        return $this;
    }



}
