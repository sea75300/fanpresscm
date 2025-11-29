<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Assign properties from dataset to current object
 *
 * @package fpcm\model\traits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-a2
 */
trait assignThisProperties {

    /**
     * Assign $data to current object
     * @param mixed $data
     * @return bool
     */
    protected function assignThis(mixed $data) : bool
    {
        if (!is_array($data) || is_object($data)) {
            return false;
        }

        foreach ($data as $key => $value) {

            if (!isset($this->$key)) {
                continue;
            }

            $this->$key = $value;
        }

        return true;
    }
}
