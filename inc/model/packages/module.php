<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\packages;

/**
 * System package objekt
 * 
 * @package fpcm\model\packages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.1
 */
class module extends package {

    /**
     * Repository object
     * @var \fpcm\model\updater\modules
     */
    protected $repo;

    /**
     * Hash value of module key
     * @var string
     */
    protected $hashKey;

    /**
     * Module key
     * @var string
     */
    protected $moduleKey;

    /**
     * Initialize objects
     * @return bool
     */
    protected function initObjects()
    {
        $this->moduleKey = \fpcm\module\module::getKeyFromFilename($this->packageName);
        $this->repo = (new \fpcm\model\updater\modules())->getDataCachedByKey($this->moduleKey);
        $this->hashKey = \fpcm\classes\tools::getHash($this->packageName);
        
        if (!\fpcm\module\module::validateKey(  $this->moduleKey)) {
            $this->preValidate = false;
            return false;
        }

        return true;
    }

    /**
     * Replaces "vendor/modules" key by module base path
     * @param string $path
     * @return string
     */
    protected function replaceFanPressBaseFolder($path)
    {
        return str_replace($this->moduleKey, $this->getLocalDestinationPath(), $path);
    }

    /**
     * Returns files.txt path
     * @return string
     */
    protected function getFileListPath()
    {
        return $this->getExtractionPath().DIRECTORY_SEPARATOR.$this->moduleKey.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'files.txt';
    }

    /**
     * Returns local path to extract archive
     * @return string
     */
    protected function getExtractionPath()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, 'module_'.$this->hashKey);
    }

    /**
     * Returns local destination path for packeg content
     * @return string
     */
    public function getLocalDestinationPath()
    {
        if ($this->data) {
            return $this->data;
        }

        $this->data = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $this->moduleKey);
        if (!\fpcm\model\files\ops::isValidDataFolder($this->data, \fpcm\classes\dirs::DATA_MODULES)) {
            $this->preValidate = false;
        }

        return $this->data;
    }

    /**
     * Returns local path for package file
     * @return string
     */
    public function getLocalPath()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, basename($this->repo['packageUrl']));
    }

    /**
     * Returns local package hash
     * @return string
     */
    public function getLocalSignature()
    {
        return \fpcm\model\files\ops::hashFile($this->getLocalPath());
    }

    /**
     * Returns remote package path
     * @return string
     */
    public function getRemotePath()
    {
        return $this->repo['packageUrl'];
    }

    /**
     * Returns remote package signature string
     * @return string
     */
    public function getRemoteSignature()
    {
        return $this->repo['signature'];
    }

    /**
     * Checks local filesystem if files are writable
     * @return bool
     */
    public function checkFiles()
    {
        $path = $this->getLocalDestinationPath().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'files.txt';

        if(!$this->preValidate) {
            return false;
        }

        $files = $this->getFileList($path, 1);
        if (!count($files)) {
            return false;
        }

        $notWritable = [];
        foreach ($files as $file) {

            if (is_writable($this->replaceFanPressBaseFolder($file))) {
                continue;
            }
            
            $notWritable[] = $file.' > NOT WRIATBLE';
        }

        if (count($notWritable)) {
            fpcmLogSystem('Module files check failed due to unwritable files.');
            fpcmLogSystem(implode(PHP_EOL, $notWritable));
            return self::FILESCHECK_ERROR;
        }

        return true;
    }

    /**
     * Updates files in local file system
     * @return bool
     */
    public function copy()
    {
        $srcBasePath    = $this->getExtractionPath();
        if(!$this->preValidate) {
            return false;
        }

        $files          = $this->getFileList($this->getFileListPath(), 1);
        if (!count($files)) {
            return self::FILESCOPY_ERROR;
        }

        $proto = [];
        $failed = [];

        $destinationPath = $this->getLocalDestinationPath();
        if(!$this->preValidate) {
            return false;
        }

        $vendorPath = dirname($destinationPath);
        if (!file_exists($vendorPath) && !mkdir($vendorPath, 0777)) {
            trigger_error('Unable to create vendor folder '. basename($vendorPath).' for '.$this->moduleKey);
            return self::FILESCOPY_ERROR;
        }

        if (!file_exists($destinationPath) && !mkdir($destinationPath, 0777)) {
            trigger_error('Unable to create vendor subfolder '. basename($destinationPath).' for '.$this->moduleKey);
            return self::FILESCOPY_ERROR;
        }
        
        foreach ($files as $file) {

            if (!trim($file)) {
                continue;
            }
            
            $src = $srcBasePath.DIRECTORY_SEPARATOR.$file;
            $dest = $this->replaceFanPressBaseFolder($file);

            if (!trim($src) || !trim($dest)) {
                continue;
            }
            
            $isDir = is_dir($src);
            $srcExists = file_exists($src);
            $destExists = file_exists($dest);

            if ($isDir && $destExists || !$srcExists) {
                continue;
            }

            if ($isDir && !mkdir($dest, 0777)) {
                $proto[] = $dest.' new folder processing failed';
                $failed++;
            }
            
            if ($isDir) {
                continue;
            }

            if ($destExists) {

                if (\fpcm\model\files\ops::hashFile($src) === \fpcm\model\files\ops::hashFile($dest)) {
                    $proto[] = $dest.' > file processing skipped';
                    continue;
                }

                $backFile = $dest.'.back';
                if (file_exists($backFile)) {
                    unlink($backFile);
                }

                if (!copy($dest, $backFile)) {
                    $failed[] = $backFile.' > backup creation failed';
                    $proto[] = $backFile.' > backup creation failed';
                }

            }

            if (!copy($src, $dest)) {
                $failed[] = $dest.' > file processing failed';
                $proto[] = $dest.' > copy processing failed';
                continue;
            }

            $proto[] = $dest.' > file processing OK';
        }

        $fopt = new \fpcm\model\files\fileOption('modulecopy'.$this->hashKey);
        $fopt->write($proto);
        
        if (count($failed)) {
            fpcmLogPackages($this->packageName.' - failed files', $failed);
            return self::FILESCOPY_ERROR;
        }
        
        return true;
    }

    /**
     * Updates local package manager log
     * @return bool
     */
    public function updateLog()
    {
        if(!$this->preValidate) {
            return false;
        }

        $fopt = new \fpcm\model\files\fileOption('modulecopy'.$this->hashKey);
        $data = array_map([$this, 'removeModuleBaseDir'], $fopt->read());        
        
        if (!fpcmLogPackages($this->moduleKey.' - '.$this->packageName, $data)) {
            return false;
        }

        return $fopt->remove();
    }

    /**
     * Removes base folder from local package filepath
     * @see \fpcm\module\module::getLocalDestinationPath
     * @param string $item
     * @return string
     */
    private function removeModuleBaseDir($item)
    {
        return $this->moduleKey.str_replace($this->getLocalDestinationPath(), '', $item);
    }

}