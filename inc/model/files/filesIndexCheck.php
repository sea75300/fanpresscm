<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * files.txt inde check object
 *
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.5-a1
 */
final class filesIndexCheck {

    /**
     * /data folder whitelist for checks
     */
    private const DIRS_DATA_WHITELIST = [
        'dbstruct',
        'share'
    ];

    /**
     * @var fanpress/ folder string
     */
    private const FPCM_FOLDER_STR = 'fanpress/';

    /**
     * @var /data folder string
     */
    private const DATA_FOLDER_STR = '/data';

    /**
     * files.txt file path
     * @var string
     */
    private string $path;

    /**
     * Base path
     * @var string
     */
    private string $base;

    /**
     * Complete file index
     * @var array
     */
    private array $completeIndex = [];

    /**
     * Directory index
     * @var array
     */
    private array $dirs = [];

    /**
     * Files index
     * @var array
     */
    private array $files = [];

    /**
     * Excluded files
     * @var array
     */
    private array $excludes = [];

    /**
     * Log function placeholder
     * @var string
     */
    private string $logFunction = 'logQuiet';

    /**
     * Progress index
     * @var int
     */
    private int $index = 1;

    /**
     * CLI progressbar object
     * @var \fpcm\model\cli\progress
     */
    private ?\fpcm\model\cli\progress $progress = null;

    /**
     * Show progress bar
     * @var bool
     */
    private bool $showProgress = false;

    /**
     * Flag if index was build
     * @var bool
     */
    private bool $indexed = false;

    /**
     * Konstruktor
     */
    public function __construct(bool $progress = false)
    {
        $this->path = \fpcm\model\packages\update::getFilesListPath();
        $this->base = \fpcm\classes\dirs::getFullDirPath(DIRECTORY_SEPARATOR);
        $this->showProgress = $progress;
    }

    /**
     * Check if files.txt file exists
     * @return bool
     */
    final public function exists() : bool
    {
        return file_exists($this->path) && is_readable($this->path);
    }

    /**
     * Prepares check index
     * @return bool
     * @throws \Exception
     */
    public function prepareIndex() : bool
    {
        $this->completeIndex = file($this->path);
        if (!is_array($this->completeIndex)) {
            throw new \Exception(sprintf('Failed to read %s', $this->path), -1);
        }

        $this->completeIndex = array_slice($this->completeIndex, 0, -2);
        if (!is_array($this->completeIndex)) {
            $this->output('Invalid files data', true);
            throw new \Exception('Invalid files data', -2);
        }

        $this->completeIndex = array_map(function ($fp) {
            $fp = trim($fp);
            return $fp === 'fanpress' ? $this->base : str_replace(self::FPCM_FOLDER_STR, $this->base, $fp);
        }, $this->completeIndex);

        if (!count($this->completeIndex)) {
            throw new \Exception('Invalid files data', -3);
        }

        $dirs = array_filter($this->completeIndex, function ($fp) {

            $fp = trim($fp);
            $bn = basename($fp);

            if (str_contains($fp, self::DATA_FOLDER_STR) && !in_array($bn, self::DIRS_DATA_WHITELIST) ) {
                return false;
            }

            if (!str_starts_with($fp, $this->base)) {
                $p = str_replace('//', '/', str_replace(self::FPCM_FOLDER_STR, $this->base, $fp));
            }
            else {
                $p = $fp;
            }

            return is_dir($p);
        });

        if (!count($dirs)) {
            throw new \Exception('Invalid directory list count data', -4);
        }

        $this->indexed = true;

        $this->dirs = $dirs;
        return true;
    }

    /**
     * Rund file check
     * @return bool
     * @throws \Exception
     */
    public function checkFiles() : bool
    {
        if ($this->showProgress) {
            $this->progress = new \fpcm\model\cli\progress(count($this->dirs));
            $this->logFunction = 'logProgress';
        }

        if (!count($this->dirs)) {
            throw new \Exception('Invalid directory list count data', -5);
        }
        
        $this->index = 1;

        foreach ($this->dirs as $dir) {

            $this->{$this->logFunction}($dir);

            $lup = realpath($dir . DIRECTORY_SEPARATOR);
            if (!$lup) {
                throw new \Exception(sprintf('Invalid files lookup path %s', $lup), -6);
            }

            $lup .= DIRECTORY_SEPARATOR . '*';

            $glob = glob($lup);
            if (!is_array($glob) ) {
                throw new \Exception(sprintf('Failed to check files %s', $lup), -7);
            }

            $this->files = array_merge_recursive($this->files, $glob);

            $this->index++;
            usleep(2500);
        }

        $this->files = array_diff($this->files, $this->completeIndex);
        $this->files = array_diff($this->files, $this->excludes);

        $this->dirs = [];

        return count($this->files) > 0;
    }

    /**
     * Cleanup files
     * @return bool
     * @throws \Exception
     */
    public function cleanup() : bool
    {
        if ($this->showProgress) {
            $this->progress = new \fpcm\model\cli\progress(count($this->files));
            $this->logFunction = 'logProgress';
        }

        if (!$this->indexed) {
            throw new \Exception(sprintf('Index was nout build before, cancel process...', $this->path), -8);
        }
        
        $this->index = 1;

        foreach ($this->files as $path) {

            $this->{$this->logFunction}($path);

            if (!file_exists($path) || !is_writable($path)) {
                continue;
            }

            if (is_dir($path)) {
                $this->dirs[] = $path;
                continue;
            }

            if (!unlink($path)) {
                throw new \Exception(sprintf('Failed to remove files %s, cancel process...', $path), -9);
            }

            $this->index++;

        }

        unset($path);

        if (count($this->dirs)) {

            foreach ($this->dirs as $path) {

                $this->{$this->logFunction}($path);

                if (!\fpcm\model\files\ops::deleteRecursive($path)) {
                    throw new \Exception(sprintf('Failed to remove folder %s, cancel process...', $path), -10);
                }

                $this->index++;

            }

        }

        usleep(5000);
        $this->resetIndex();
        return true;
    }

    /**
     * Progress output
     * @param string $text
     * @return void
     */
    private function logProgress(string $text) : void
    {
        $this->progress->setOutputText(\fpcm\model\files\ops::removeBaseDir($text))->setCurrentValue($this->index)->output();
    }

    /**
     * Quiet output
     * @param string $text
     * @return void
     */
    private function logQuiet(string $text) : void
    {
        return;
    }

    /**
     * Set excluded files
     * @param array $excludes
     * @return $this
     */
    public function setExcludes(array $excludes)
    {
        $this->excludes = $excludes;
        return $this;
    }

    /**
     * Get outdated directory list
     * @return array
     */
    public function getDirs(): array
    {
        return $this->dirs;
    }

    /**
     * Get outdated files list
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Base path
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->base;
    }

    /**
     *
     * @return void
     */
    public function resetIndex() : void
    {
        $this->files = [];
        $this->dirs = [];
    }

}
