<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\modules;

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
 * @property array $requirements Module requirements array
 * @property array $tables Module tables data
 * @property array $configOptions Module config data
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\modules
 */
class config implements \JsonSerializable {

    /**
     *
     * @var bool
     */
    protected $data = [];

    /**
     * Konstruktor
     * @param string $moduleKey
     */
    public function __construct($moduleKey, $installed = null)
    {
        include_once \fpcm\classes\loader::libGetFilePath('spyc/Spyc.php');

        $this->basePath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $moduleKey.DIRECTORY_SEPARATOR);

        $this->data     = array_merge($this->data, ($installed === null
                        ? \Spyc::YAMLLoad($this->basePath.'module.yml')
                        : json_decode($installed, true)));
    }

    /**
     * 
     * @param string $name
     * @return mixed|null
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
     */
    public function __set($name, $value)
    {
        $this->data[$name] = is_array($value) || is_object($value) ? json_encode($value) : $value;
    }

    /**
     * 
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

}
