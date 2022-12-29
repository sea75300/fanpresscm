<?php
/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * File option objekt
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\files
 */
class fileOption {

    const EXTENSION_CACHE = '.fpcm';

    /**
     * Complete file path
     * @var string
     */
    private $path;

    /**
     * Constructor
     * @param string $option
     */
    public function __construct($option)
    {
        $option = explode('/', $option, 2);
        $hasSubdir = isset($option[1]) && count($option) === 2 ? true : false;

        if ($hasSubdir) {
            $subdir = $option[0].DIRECTORY_SEPARATOR;
            $name = $option[1];
        }
        else {
            $subdir = '';
            $name = $option[0];
        }

        $this->path = \fpcm\classes\dirs::getDataDirPath( $this->getType(), $subdir.\fpcm\classes\tools::getHash($name) . $this->getExt() );
        if (!$hasSubdir) {
            return;
        }

        $parent = dirname($this->path);
        if (is_dir($parent)) {
            return;
        }

        if (mkdir($parent)) {
            return;
        }

        trigger_error('Unable to create option parent "'.$parent.'" for option '. implode('/', $option));
    }
    
    /**
     * Write content to file option
     * @param mixed $data
     * @return bool
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
        if (!file_exists($this->path)) {
            return true;
        }

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