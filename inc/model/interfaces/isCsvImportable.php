<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * CSV importtable interface
 * 
 * @package fpcm\model\interfaces
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5
 */
interface isCsvImportable {

    public function getFields() : array;

    public function assignCsvRow(array $csvRow) : bool;
    
}

?>