<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dbal;

/**
 * IP-Listen Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
class queryAssignResult {

    /**
     * SQL query snippets
     * @var array
     */
    private array $queries = [];

    /**
     * Value array
     * @var array
     */
    private array $values = [];

    /**
     * Return query strings
     * @return array
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * Return values
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Set queries
     * @param array $queries
     * @return $this
     */
    public function setQueries(string|array $queries)
    {
        if (is_array($queries)) {
            $this->queries += $queries;
        }
        else {
            $this->queries[] = $queries;
        }
        
        return $this;
    }

    /**
     * Set values
     * @param array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = array_merge($this->values, $values);
        return $this;
    }

}
