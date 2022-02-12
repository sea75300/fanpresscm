<?php

namespace nkorg\yatdl;

/**
 * YaML Table Definition Language Parser Libary\n
 * Table item
 * 
 * @package nkorg\yatdl
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2016-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version 4.4
 * 
 * @property string $name item name
 * @property string $primarykey primary key column
 * @property string $engine engine (MySQL/MariaDB only)
 * @property string $charset charset (MySQL/MariaDB only)
 * 
 * @property bool $isview flag if item is view
 * @property string $query query string, only for views
 * @property string $querymysql mysql query string, only for views
 * @property string $querypgsql pgsql query string, only for views
 * 
 * @property array $autoincrement auto incremen setting
 * @property array $cols table column
 * @property array $indices table indices
 * @property array $defaultvalues table default values
 * 
 */
class tableItem extends item
{

}
