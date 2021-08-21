<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * System templates for Javascript
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\files
 * @since 5.0-dev
 */
final class jsViewTemplate extends \fpcm\model\abstracts\file implements \JsonSerializable {

    /**
     * Constructor
     * @param string $filename
     */
    public function __construct($filename = '')
    {
        parent::__construct($filename);
        $this->loadContent();
    }

    /**
     * Returns base path for file
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'systpl' . DIRECTORY_SEPARATOR . $filename . '.fpcm.txt');
    }

    /**
     * 
     * @return string
     * @ignore
     */
    public function getFileUrl()
    {
        return '';
    }

    /**
     * 
     * @param type $newname
     * @param type $userid
     * @return bool
     * @ignore
     */
    public function rename($newname, $userid = false)
    {
        return false;
    }

    /**
     * 
     * @param string $content
     * @return bool
     * @ignore
     */
    public function setContent($content)
    {
        return false;
    }

    /**
     * 
     * @param string $filename
     * @return bool
     * @ignore
     */
    public function setFilename($filename)
    {
        return false;
    }

    /**
     * 
     * @return bool
     * @ignore
     */
    public function writeContent()
    {
        return false;
    }

    /**
     * Is valid data path folder, okay: here core/views/systpl
     * @param string $path
     * @param string $type
     * @return bool
     */
    public function isValidDataFolder(string $path = '', string $type = '/'): bool
    {
        return strpos(realpath($this->fullpath), \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'systpl')) === 0;
    }

    /**
     * 
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getContent();
    }

}

?>