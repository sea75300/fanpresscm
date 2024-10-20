<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * Object search wrapper object
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\abstracts
 * @since 3.5
 * @package fpcm\model\abstracts
 * @abstract
 */
abstract class searchWrapper extends staticModel {

    const COMBINATION_AND = 0;
    const COMBINATION_OR = 1;
    
    /**
     * Multiple search flag
     * @var bool
     * @since 4.3
     */
    protected $isMultiple = false;

    /**
     * Liefert Daten zurück, die über Eigenschaften erzeugt wurden
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Funktion liefert Informationen zurpck, ob Suchparameter vorhanden
     * @return bool
     */
    public function hasParams()
    {
        return count($this->data) ? true : false;
    }

    /**
     * Is multiple flag set
     * @return bool
     * @since 4.2
     */
    public function isMultiple() : bool
    {
        return $this->isMultiple;
    }

    /**
     * Sets multiple lag
     * @param bool $isMultiple
     * @return $this
     * @since 4.3
     */
    public function setMultiple(bool $isMultiple)
    {
        $this->isMultiple = $isMultiple;
        return $this;
    }
    
    /**
     * Returns condition for given value
     * @param string $condition
     * @param string $query
     * @return string
     */
    public function getCondition(string $condition, string $query)
    {
        $value = $this->{'combination'.ucfirst($condition)};
        if ($value === self::COMBINATION_AND) {
            return ' AND '.$query;
        }

        if ($value === self::COMBINATION_OR) {
            return ' OR '.$query;
        }
        
        return $query;
    }

}
