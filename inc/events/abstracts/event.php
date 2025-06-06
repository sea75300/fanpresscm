<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\abstracts;

/**
 * Event model base
 *
 * @package fpcm\events\abstracts
 * @abstract
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class event {

    /**
     * Base instaces a module event has to implement
     */
    const EVENT_BASE_INSTANCE = '\\fpcm\\module\\event';

    /**
     * Event-Daten
     * @var mixed|\fpcm\module\eventResult
     */
    protected $data;

    /**
     * Event-Cache
     * @var \fpcm\classes\cache
     */
    protected $cache;

    /**
     * Berechtigungen
     * @var \fpcm\model\permissions\permissions
     */
    protected $permissions;

    /**
     * Konstruktor
     * @param mixed $dataParams
     * @return bool
     */
    public function __construct($dataParams = null)
    {
        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return false;
        }

        $this->data = $dataParams;
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');

        if (isset($GLOBALS['fpcm']['events']['activeModules']) && count($GLOBALS['fpcm']['events']['activeModules'])) {
            return;
        }

        if (!$this->cache->isExpired('modules/activeeventscache')) {
            $GLOBALS['fpcm']['events']['activeModules'] = $this->cache->read('modules/activeeventscache');
            return;
        }

        $GLOBALS['fpcm']['events']['activeModules'] = \fpcm\classes\loader::getObject('\fpcm\module\modules')->getEnabledDatabase();
        $this->cache->write('modules/activeeventscache', $GLOBALS['fpcm']['events']['activeModules']);
    }

    /**
     * Magic get
     * @param string $name
     * @return mixed
     * @ignore
     */
    public function __get($name)
    {
        return $this->data[$name] ?? false;
    }

    /**
     * Magic set
     * @param mixed $name
     * @param mixed $value
     * @ignore
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
     * @ignore
     */
    public function __call($name, $arguments)
    {
        print "Function '{$name}' not found in " . static::class . '<br>';
        return false;
    }

    /**
     * Magische Methode für nicht vorhandene, statische Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     * @ignore
     */
    public static function __callStatic($name, $arguments)
    {
        printf('Static function %s not found in %s!<br>', $name, static::class);
        return false;
    }

    /**
     * User acan execute event
     * @return bool
     * @since 4.4
     */
    public function isExecutable() : bool
    {
        return true;
    }

    /**
     * Checks if module event class has implemented \fpcm\module\event
     * @param mixed $object
     * @return bool
     */
    protected function is_a($object) : bool
    {
        $base = self::EVENT_BASE_INSTANCE;
        if ($object instanceof $base) {
            return true;
        }

        trigger_error(sprintf("Event object of class %s must be an instance of %s", $object::class, self::EVENT_BASE_INSTANCE));
        return false;
    }

    /**
     * Liefert Array mit Event-Klassen in installierten Modulen zurück
     * @return array
     * @since 3.3
     */
    protected function getEventClasses() : array
    {
        if (!count($GLOBALS['fpcm']['events']['activeModules'])) {
            return [];
        }

        $classes = [];

        $baseClass = $this->getEventClassBase();
        $eventBaseClass = DIRECTORY_SEPARATOR . 'events' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $baseClass) . '.php';
        foreach ($GLOBALS['fpcm']['events']['activeModules'] as $module) {

            $path = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $module.$eventBaseClass);
            if (!file_exists($path)) {
                continue;
            }

            $classes[] = \fpcm\module\module::getEventNamespace($module, $baseClass);
        }

        return $classes;
    }

    /**
     * Returns event base class data
     * @return string
     */
    protected function getEventClassBase() : string
    {
        return str_replace('fpcm\\events\\', '', static::class);
    }

    /**
     * Executes a certain event
     * @param array $data
     * @return array
     */

    /**
     * Eexcutes event
     * @return \fpcm\module\eventResult
     */
    public function run() : \fpcm\module\eventResult
    {
        $this->beforeRun();

        $eventClasses = $this->getEventClasses();
        if (!count($eventClasses)) {
            return (new \fpcm\module\eventResult())->setData($this->data);
        }

        if ($this instanceof \fpcm\events\interfaces\componentProvider) {
            $eventClasses = array_slice($eventClasses, 0, 1);
        }

        foreach ($eventClasses as $class) {

            if (!class_exists($class)) {
                trigger_error(sprintf('Undefined event class %s, Event: %s', $class, self::class));
                continue;
            }

            $this->processClass($class);
            if (!$this->data->getSuccessed() || !$this->data->getContinue()) {
                 return $this->data;
            }

        }
        
        $this->afterRun();

        return $this->data;
    }

    /**
     * Prepare event before running
     * @return bool
     */
    protected function beforeRun() : void
    {
        return;
    }

    /**
     * After event running
     * @return bool
     */
    protected function afterRun() : void
    {
        return;
    }

    /**
     * Returns event params
     * @return mixed
     */
    protected function getEventParams() : mixed
    {
        return $this->data;
    }

    /**
     * Process class data
     * @param string $class
     * @return bool
     */
    protected function processClass(string $class) : bool
    {
        $module = new $class($this->getEventParams());
        if (!$this->is_a($module)) {
            return false;
        }

        if (method_exists($this, 'doEventbyArea')) {
            $this->data = $this->doEventbyArea($module);
            return true;
        }
        
        $this->data = $module->run();
        return true;
    }

    /**
     * Returns full event namespace
     * @param string $event
     * @return string
     */
    final public static function getEventNamespace(string $event) : string
    {
        return 'fpcm\\events\\'.$event;
    }

}
