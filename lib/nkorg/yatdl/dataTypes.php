<?php

namespace nkorg\yatdl;

/**
 * YaML Table Definition Language Parser Libary\n
 datatypes definitions
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2016-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version YaTDL4.0
 * 
 */
class dataTypes {

    /**
     * Type list definition
     * @return array
     */
    static public function getTypeList() : array
    {
        return [
            'int', 'bigint', 'varchar', 'text', 'mtext', 'ltext', 'char',
            'bool', 'bin', 'lbin', 'float', 'double'
        ];
    }

    /**
     * Text type definition
     * @return array
     */
    static public function getTextTypeList() : array
    {
        return ['varchar', 'text', 'mtext', 'bin'];
    }

    /**
     * MySQL lenght typ definition
     * @return array
     */
    static public function getLenghtTypesMySQL() : array
    {
        return ['int', 'bigint', 'bool', 'smallint', 'float', 'double'];
    }

}
