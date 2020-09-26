<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\packages;

/**
 * Update package objekt
 * 
 * @package fpcm\model\packages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.1
 */
class update extends package {

    /**
     * Repository object
     * @var \fpcm\model\updater\system
     */
    protected $updater;

    /**
     * Initializes interval objects
     * @return bool
     */
    public function initObjects()
    {
        $this->updater = new \fpcm\model\updater\system();
        return true;
    }

    /**
     * Returns local destination path for packeg content
     * @return string
     */
    public function getLocalDestinationPath()
    {
        return \fpcm\classes\dirs::getFullDirPath(DIRECTORY_SEPARATOR);
    }

    /**
     * Returns local path for package file
     * @return string
     */
    public function getLocalPath()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, basename($this->updater->url));
    }

    /**
     * Returns local path to extract archive
     * @return string
     */
    protected function getExtractionPath()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, 'update');
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
        return $this->updater->url;
    }

    /**
     * Returns remote package signature string
     * @return string
     */
    public function getRemoteSignature()
    {
        return $this->updater->signature;
    }

    /**
     * Validate archive content after opening archive
     * @return bool
     * @since 4.5
     */
    public function extractionValidateArchiveData() : bool
    {
        return true;
    }

    /**
     * Check if local files are writable
     * @return bool
     */
    public function checkFiles()
    {
        $files = $this->getFileList(self::getFilesListPath(), 1);
        if (!count($files)) {
            return false;
        }

        $excludes = $this->getExcludes();
        $notWritable = [];

        foreach ($files as $file) {

            if (in_array($file, $excludes) || is_writable($this->replaceFanPressBaseFolder($file))) {
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

    /**
     * Create backup zip archive for current version
     * @return bool
     * @since 4.5
     */
    public function backup() : bool
    {
        $srcBasePath = \fpcm\classes\dirs::getFullDirPath('');
        $files = $this->retrieveFilesFromFileTxt($srcBasePath);
        if (!count($files)) {
            return false;
        }

        /* @var $conf \fpcm\model\system\config */
        $conf = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $backupFile = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_BACKUP, 'fsback_'.preg_replace('/[^0-9a-z]/i', '', $conf->system_version).'.zip' );

        if ($this->archive->open($backupFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE ) !== true) {
            trigger_error('Unable to open backup ZIP archive: ' . $backupFile);
            return false;
        }

        $progress = new \fpcm\model\cli\progress(count($files));

        $zip = $this->archive;
        
        $dirs = array_filter($files, function($file) {
            return is_dir($this->replaceFanPressBaseFolder($file));
        });

        $opsys = 0;
        $attr = 0;
        $error = 0;
        $counter = 0;
        
        array_walk($dirs, function ($path, $i) use ($backupFile, $progress, &$counter, &$error, &$zip, &$opsys, &$attr) {

            $counter++;
            
            $progress->setCurrentValue($counter);
            $progress->output();

            if ($this->archive->getExternalAttributesName($path, $opsys, $attr)) {
                return true;
            }
            
            if ($zip->addEmptyDir($path)) {
                return true;
            }

            $error++;
            trigger_error('Failed to add '.$path . ' to backup file '.$backupFile, E_USER_WARNING);
            return false;
        });
        
        unset($dirs);
        
        $realFiles = array_filter($files, function($file) {
            return !is_dir($this->replaceFanPressBaseFolder($file));
        });

        array_walk($realFiles, function ($path, $i) use ($backupFile, $progress, &$counter, &$error, &$zip, &$opsys, &$attr) {

            $counter++;
            
            $progress->setCurrentValue($counter);
            $progress->output();

            $fullPath = $this->replaceFanPressBaseFolder($path);            
            if ($this->archive->getExternalAttributesName($path, $opsys, $attr)) {
                return true;
            }

            if ($zip->addFile($fullPath, $path)) {
                return true;
            }

            $error++;
            trigger_error('Failed to add '.$path . ' to backup file '.$backupFile, E_USER_WARNING);
            return false;
        });

        $this->archive->close();
        return $error ? false : true;
    }

    /**
     * Updates files in local file system
     * @return bool
     */
    public function copy()
    {
        $srcBasePath = $this->getExtractionPath();
        $files = $this->retrieveFilesFromFileTxt($srcBasePath . DIRECTORY_SEPARATOR . fanpress);
        if (!count($files)) {
            return self::FILESCOPY_ERROR;
        }

        $progress = new \fpcm\model\cli\progress(count($files));

        $proto = [];
        $failed = [];
        foreach ($files as $i => $file) {
            
            $progress->setCurrentValue(($i+1));
            $progress->output();
            
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
                $proto[] = $dest.' new folder failed';
                $failed++;
            }
            
            if ($isDir) {
                continue;
            }

            if ($destExists) {

                if (file_exists($dest.'.back')) {
                    unlink($dest.'.back');
                }

                if (\fpcm\model\files\ops::hashFile($src) === \fpcm\model\files\ops::hashFile($dest)) {
                    $proto[] = $dest.' > file update skipped';
                    continue;
                }

            }

            if (!copy($src, $dest)) {
                $failed[] = $dest.' > file update failed';
                $proto[] = $dest.' > file update failed';
                continue;
            }

            $proto[] = $dest.' > file update OK';
        }

        $fopt = new \fpcm\model\files\fileOption('updatecopy');
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
        $fopt = new \fpcm\model\files\fileOption('updatecopy');
        $data = array_map('\fpcm\model\files\ops::removeBaseDir', $fopt->read());
        if (!fpcmLogPackages($this->packageName, $data)) {
            return false;
        }

        return $fopt->remove();
    }

    /**
     * Performs cleanup of update files and cache
     * @return bool
     */
    public function cleanupFiles()
    {
        $oldList = $this->getFileList(self::getFilesListPath() . '.back', 1);
        $newList = $this->getFileList(self::getFilesListPath(), 1);

        if (!count($oldList) || !count($newList)) {
            return true;
        }

        $diff = array_diff($oldList, $newList);
        if (!count($diff)) {
            return true;
        }

        foreach ($diff as $file) {
            
            if (!file_exists($file) || is_dir($file)) {
                continue;
            }

            $delPath = $this->replaceFanPressBaseFolder($file);
            if (!unlink($delPath)) {
                trigger_error('Unable to remove file '.$delPath);
                return false;
            }

        }

        return true;
    }

    /**
     * Returns list of files not to check or change
     * @return array
     */
    private function getExcludes()
    {
        return ['fanpress'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'installer.enabled', 'fanpress'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'backup'];
    }

    /**
     * Get data/config/files.txt path
     * @return string
     * @since 4.1
     */
    final public static function getFilesListPath() : string
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_CONFIG, 'files.txt');
    }

    /**
     * Retrieves file list for package
     * @return array
     * @since 4.5
     */
    private function retrieveFilesFromFileTxt(string $srcBasePath) : array
    {  
        $files = $this->getFileList($srcBasePath .DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'files.txt', 1);
        if (!count($files)) {
            return [];
        }
        
        $excludes = $this->getExcludes();
        return array_filter(array_diff($files, $excludes), function ($file) {
            return trim($file) ? true : false;
        });

    }

}
