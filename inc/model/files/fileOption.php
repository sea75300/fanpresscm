<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * File option objekt
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\files
 */
class fileOption {

    const EXTENSION_CACHE = '.fpcm';

    /**
     *
     * @var string
     */
    private $path;

    /**
     * Constructor
     * @param string $option
     */
    public function __construct($option)
    {
        $this->path = \fpcm\classes\dirs::getDataDirPath( $this->getType(), \fpcm\classes\tools::getHash($option) . $this->getExt() );
    }

    /**
     * Write content to file option
     * @param mixed $data
     * @param integer $expires
     * @return boolean
     */
    public function write($data)
    {
        if (!file_put_contents($this->path, json_encode($data))) {
            trigger_error('Unable to write file option ' . ops::removeBaseDir($this->path, true));
            return false;
        }

        return true;
    }

    /**
     * Fetch data from file option
     * @return mixed
     */
    public function read()
    {
        if (!file_exists($this->path)) {
            return null;
        }

        return json_decode(file_get_contents($this->path));
    }

    /**
     * Remove file option
     * @return bool
     */
    public function remove()
    {
        return unlink($this->path);
    }

    /**
     * Return extension for cache file
     * @return string
     */
    protected function getExt()
    {
        return self::EXTENSION_CACHE;
    }

    /**
     * Return path type
     * @return string
     */
    protected function getType()
    {
        return \fpcm\classes\dirs::DATA_OPTIONS;
    }

}

?>