<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\updater;

use fpcm\classes\loader;
use fpcm\model\files\fileOption;
use fpcm\model\packages\repository;

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
        $this->fileOption = new fileOption(repository::FOPT_MODULES);
        return true;
    }

    /**
     * Returns module repo data
     * @return array
     */
    public function getData()
    {
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
            $this->list = \Spyc::YAMLLoadString($this->fileOption->read());
        }

        return $this->list[$key] ?? false;
    }


}
