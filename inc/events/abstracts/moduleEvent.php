<?php

/**
 * FanPress CM module event model
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * Module event basis
 * 
 * @package fpcm\events\abstracts
 * @abstract
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class moduleEvent implements \fpcm\model\interfaces\event {

    /**
     * data Array
     * @var array
     */
    protected $data;

    /**
     * Sprachobjekt
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     * Config-Objekt
     * @var \fpcm\model\system\config
     */
    protected $config;

    /**
     * Notifications
     * @var \fpcm\model\theme\notifications
     * @since FPCM 3.6
     */
    protected $notifications;

    /**
     * Konstruktor
     * @return bool
     */
    final public function __construct()
    {
        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return false;            
        }

        $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language');
        $this->notifications = \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications');
    }

    /**
     * Magic get
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : false;
    }

    /**
     * Magic set
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Magische Methode für nicht vorhandene Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {
        print "Function '{$name}' not found in " . get_class($this) . '<br>';
        return false;
    }

    /**
     * Magische Methode für nicht vorhandene, statische Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     */
    public static function __callStatic($name, $arguments)
    {
        print "Static function '{$name}' not found in " . get_class($this) . '<br>';
        return false;
    }

}
