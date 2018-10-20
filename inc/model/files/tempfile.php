<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Temp file object
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class tempfile extends \fpcm\model\abstracts\file {

    /**
     * Konstruktor
     * @param string $filename
     */
    public function __construct($filename = '')
    {
        parent::__construct($filename);
        $this->init();
    }

    /**
     * Returns file data base path
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, \fpcm\classes\tools::getHash($filename).'.tmp');
    }

    /**
     * Speichert eine neue temporäre Datei in data/temp/
     * @return bool
     */
    public function save()
    {
        if ($this->exists()) {
            $this->delete();
        }

        return file_put_contents($this->fullpath, $this->content);
    }

    /**
     * Initialisiert Objekt einer temporären Datei
     * @return void
     */
    public function init()
    {
        if ($this->exists()) {
            return;
        }

        $this->content = file_get_contents($this->fullpath);
    }

}

?>