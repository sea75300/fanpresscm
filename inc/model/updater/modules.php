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
     * Initialisiert System Updater
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileOption = new \fpcm\model\files\fileOption(\fpcm\model\packages\repository::FOPT_MODULES);
        return true;
    }

    /**
     * 
     * @return array
     */
    public function getData()
    {
        include_once \fpcm\classes\loader::libGetFilePath('spyc/Spyc.php');
        return \Spyc::YAMLLoadString($this->fileOption->read());
    }


}

?>