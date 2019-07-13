<?php

namespace nkorg\yatdl;

/**
 * YaML Table Definition Language Parser Libary\n
 * Item Abstract
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2016-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version YaTDL3.0
 */
abstract class item {

    /**
     *
     * @var array
     */
    protected $data = [];

    /**
     * Constructor
     * @param array $data
     */
    final public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Magic getter
     * @param string $name
     * @ignore
     */
    final public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }
    
    /**
     * Magic setter
     * @param string $name
     * @param mixed $value
     * @return bool
     * @ignore
     */
    final public function __set(string $name, $value)
    {
        $this->data[$name] = $value;
        return true;
    }

}
