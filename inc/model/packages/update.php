<?php

/**
 * Package object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\packages;

/**
 * Update package objekt
 * 
 * @package fpcm\model\packages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.1
 */
class update extends package {

    /**
     *
     * @var \fpcm\model\updater\system
     */
    private $updater;

    /**
     * Initializes interval objects
     * @return boolean
     */
    public function initObjects()
    {
        $this->updater = new \fpcm\model\updater\system();
        return true;
    }

    /**
     * 
     * @return string
     */
    public function getLocalDestinationPath()
    {
        return \fpcm\classes\dirs::getFullDirPath('/');
    }

    /**
     * 
     * @return string
     */
    public function getLocalPath()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, basename($this->updater->url));
    }

    /**
     * 
     * @return string
     */
    protected function getExtractionPath()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, 'update');
    }

    /**
     * 
     * @return string
     */
    public function getLocalSignature()
    {
        return hash_file(\fpcm\classes\security::defaultHashAlgo, $this->getLocalPath());
    }

    /**
     * 
     * @return string
     */
    public function getRemotePath()
    {
        return $this->updater->url;
    }

    /**
     * 
     * @return string
     */
    public function getRemoteSignature()
    {
        return $this->updater->signature;
    }

    /**
     * Check if local files are writable
     * @return boolean
     */
    public function checkFiles()
    {
        $path = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_CONFIG, 'files.txt');
        if (!$path || !file_exists($path)) {
            return false;
        }

        $files = file($path, FILE_IGNORE_NEW_LINES);
        if (!count($files)) {
            return false;
        }

        $excludes = [
            'fanpress',
            'fanpress/data/config/installer.enabled'
        ];
        
        $files = array_slice($files, 0, -2);
        $notWritable = [];
        foreach ($files as $file) {

            if (in_array($file, $excludes) || is_writable(str_replace('fanpress/', \fpcm\classes\dirs::getFullDirPath('/'), $file))) {
                continue;
            }
            
            $notWritable[] = $file.' > NOT WRIATBLE';
        }

        if (count($notWritable)) {
            fpcmLogSystem('Update check failed due to unwritable files.');
            fpcmLogSystem(implode(PHP_EOL, $notWritable));
            return self::FILESCHECK_ERROR;
        }

        return true;
    }

}
