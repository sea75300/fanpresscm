<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

/**
 * YaML Table Definition Language<br>
 * Parse Wrapper<br>
 * uses \nkorg\yatdl\parser as of FPCM 3.6
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.2.0
 */
class yatdl extends \fpcm\model\abstracts\staticModel {

    /**
     * Datentypen mit Längen-Angabe
     * @var \nkorg\yatdl\yatdl
     * @since 3.6
     */
    protected $parser;
    
    /**
     * Constructore
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        include_once \fpcm\classes\loader::libGetFilePath('nkorg');

        $this->parser = new \nkorg\yatdl\parser(
            \Spyc::YAMLLoad($filePath),
            \fpcm\classes\loader::getObject('\fpcm\classes\database')->getDbtype(),
            \fpcm\classes\loader::getObject('\fpcm\classes\database')->getYaTDLDataTypes()
        );
    }

    /**
     * Setzt zusätzliches Tabellen-Prefix
     * @param string $tablePrefix
     * @since 3.4
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->parser->setTablePrefix($tablePrefix);
    }

    /**
     * Parst Array aus YAML-String in SQL-String
     * @return bool
     */
    public function parse()
    {
        return $this->parser->parse();
    }

    /**
     * Gibt fertigen SQL-String zurück
     * @return string
     */
    public function getSqlString()
    {
        return $this->parser->getSqlString();
    }

    /**
     * Debug-Ausgabe von geparstem YAML-String
     * @return void
     */
    public function dumpYamlArray()
    {
        $this->parser->dumpYamlArray();
    }

    /**
     * Gibt geparsten YAML-String als Array zurück
     * @return array
     * @since 3.3.2
     */
    public function getArray()
    {
        return $this->parser->getArray();
    }

    /**
     * Returns parsed data as \nkorg\yatdl\tableItem instance
     * @return \nkorg\yatdl\tableItem
     * @since 5.0.0-b1
     */
    public function getTable()
    {
        return $this->parser->getTabData();
    }

}
