<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\updater;

/**
 * Module Updater Objekt
 * 
 * @package fpcm\model\updater
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class modules extends \fpcm\model\abstracts\staticModel {

    /**
     * Data cache
     * @var array
     */
    private $list = [];

    /**
     * File option object for repo data
     * @var \fpcm\model\files\fileOption
     */
    private $fileOption;

    /**
     * Initialisiert System Updater
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileOption = new \fpcm\model\files\fileOption(\fpcm\model\packages\repository::FOPT_MODULES);
        return true;
    }

    /**
     * Returns module repo data
     * @return array
     */
    public function getData()
    {
        include_once \fpcm\classes\loader::libGetFilePath('spyc/Spyc.php');
        return \Spyc::YAMLLoadString($this->fileOption->read());
    }

    /**
     * Returns module repo data by key
     * @param string $key
     * @return array
     */
    public function getDataCachedByKey($key)
    {
        if (!count($this->list)) {
            include_once \fpcm\classes\loader::libGetFilePath('spyc/Spyc.php');
            $this->list = \Spyc::YAMLLoadString($this->fileOption->read());
        }

        return isset($this->list[$key]) ? $this->list[$key] : false;
    }


}

?>