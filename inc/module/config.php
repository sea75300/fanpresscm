<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Module config
 * 
 * @property string $key Module key
 * @property string $author Module author
 * @property string $name Module name
 * @property string $description Module description
 * @property string $link Module key
 * @property string $version Module version
 * @property string $basePath Module base path
 * @property string $help Help item
 * @property bool $useDataFolder Use data folder
 * @property bool $removeDataFolder Removce data folder
 * @property array $requirements Module requirements array
 * @property array $tables Module tables data
 * @property array $configOptions Module config data
 * @property array $crons Module cronjob data
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\module
 */
class config implements \JsonSerializable {

    /**
     * Module config data
     * @var bool
     */
    protected $data = [];

    /**
     * Konstruktor
     * @param string $moduleKey Module key string
     * @param mixed $installed config data as array or JSOn string
     */
    public function __construct($moduleKey, $installed = null)
    {
        $this->basePath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $moduleKey);
        $this->key = $moduleKey;

        $this->data     = array_merge($this->data, ($installed === null
                        ? \Spyc::YAMLLoad($this->basePath.DIRECTORY_SEPARATOR.'module.yml')
                        : (is_array($installed) ? $installed :  json_decode($installed, true)) ) );
    }

    /**
     * 
     * @param string $name
     * @return mixed|null
     * @ignore
     */
    public function __get($name)
    {
        $return = isset($this->data[$name]) ? $this->data[$name] : null;

        return  is_string($return) && substr($return, 0, 1) === '{' && substr($return, 0, -1) === '}'
                ? json_decode($return)
                : $return;
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     * @ignore
     */
    public function __set($name, $value)
    {
        $this->data[$name] = is_array($value) || is_object($value) ? json_encode($value) : $value;
    }

    /**
     * 
     * @return array
     * @ignore
     */
    
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $return = $this->data;
        unset($return['basePath'], $return['key']);

        return $return;
    }

}
