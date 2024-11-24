<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM files module
 *
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.2-a1
 */
final class files extends \fpcm\model\abstracts\cli {

    /**
     * /data folder whitelist for checks
     */
    const DIRS_DATA_WHITELIST = [
        'dbstruct',
        'share'
    ];

    /**
     * File index array
     * @var array
     */
    private array $filesIndex = [];

    /**
     * Folder excludes parameter
     * @var array
     */
    private array $excludes = [];

    /**
     * Base path
     * @var string
     */
    private string $base = '';

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {
        $fn = match ($this->funcParams[0]) {
            self::PARAM_EXECCHECK => 'check',
            self::PARAM_REMOVE => 'remove',
            default => null
        };

        if (!$fn) {
            $this->output('Invalid params', true);
        }

        $this->base = \fpcm\classes\dirs::getFullDirPath(DIRECTORY_SEPARATOR);

        if (count($this->funcParams) > 1) {
            $this->excludes = array_map([$this, 'escapeExcludes'], array_slice($this->funcParams, 1));
            $this->output(sprintf("Exclude from check:\n\n%s\n", implode(PHP_EOL, $this->excludes)));
        }


        $this->{$fn}();
        return true;
    }

    /**
     * Run check
     * @param bool $out
     * @return bool
     */
    private function check(bool $out = true)
    {
        fpcmLogSystem('Scan for file system for outdated files...');

        $filesPath = \fpcm\model\packages\update::getFilesListPath();
        if (!file_exists($filesPath) || !is_readable($filesPath)) {
            $this->output(sprintf('%s does not exists or is no readable', $filesPath), true);
        }

        $fileListContent = file($filesPath);
        if (!is_array($fileListContent)) {
            $this->output(sprintf('Failed to read %s', $filesPath), true);
            return false;
        }

        $fileListContent = array_slice($fileListContent, 0, -2);
        if (!is_array($fileListContent)) {
            $this->output('Invalid files data', true);
        }

        $fileListContent = array_map(function ($fp) {
            $fp = trim($fp);
            return $fp === 'fanpress' ? $this->base : str_replace('fanpress/', $this->base, $fp);
        }, $fileListContent);

        if (!count($fileListContent)) {
            $this->output('Invalid files data', true);
            return false;
        }
        
        $dirs = array_filter($fileListContent, function ($fp) {

            $fp = trim($fp);
            $bn = basename($fp);

            if (str_contains($fp, '/data') && !in_array($bn, self::DIRS_DATA_WHITELIST) ) {
                return false;
            }

            if (!str_starts_with($fp, $this->base)) {
                $p = str_replace('//', '/', str_replace('fanpress/', $this->base, $fp));
            }
            else {
                $p = $fp;
            }
            
            return is_dir($p);
        });
        
        if (!count($dirs)) {
            $this->output('Invalid directory list count data', true);
            return false;
        }

        $progress = new progress(count($dirs));

        $i = 1;

        foreach ($dirs as $dir) {

            $progress
                    ->setOutputText(\fpcm\model\files\ops::removeBaseDir($dir))
                    ->setCurrentValue($i)
                    ->output();

            $lup = realpath($dir . DIRECTORY_SEPARATOR);
            if (!$lup) {
                $this->output(sprintf('Invalid files lookup path %s', $lup), true);
            }

            $lup .= DIRECTORY_SEPARATOR . '*';

            $glob = glob($lup);
            if (!is_array($glob) ) {
                $this->output(sprintf('Failed to check files %s', $lup), true);
            }

            $this->filesIndex = array_merge_recursive($this->filesIndex, $glob);

            $i++;
            usleep(2500);
        }


        $this->filesIndex = array_diff($this->filesIndex, $fileListContent);
        $this->filesIndex = array_diff($this->filesIndex, $this->excludes);

        if (!$out) {
            return true;
        }

        if (!count($this->filesIndex)) {
            $this->output("No outdated files found.", true);
        }

        $this->output(sprintf("Outdated found:\n\n- %s\n", implode(PHP_EOL . '- ', $this->filesIndex)));
        return true;
    }

    /**
     * Remove old files
     */
    private function remove()
    {

        if (!$this->check(true)) {
            $this->output(sprintf('Failed to check files %s', $this->base), true);
        }

        if (io::input('Press any key to continue') === false) {
            exit;
        }

        $progress = new progress(count($this->filesIndex));

        $i = 1;

        foreach ($this->filesIndex as $path) {


            $progress
                    ->setOutputText(\fpcm\model\files\ops::removeBaseDir($path))
                    ->setCurrentValue($i)
                    ->output();


            if (!file_exists($path) || !is_writable($path)) {
                continue;
            }

            if (!unlink($path)) {
                $this->output(sprintf('Failed to remove files %s, cancel process...', $path), true);
            }

            $i++;
        }
        
        usleep(5000);
        $this->filesIndex = [];

        $this->check();
    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {
        $lines = [];
        $lines[] = '> File system actions:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php files <action params> <logfile name>';
        $lines[] = '';
        $lines[] = '    Action params:';
        $lines[] = '';
        $lines[] = '      --check        check for oudated files';
        $lines[] = '      --remove       removed outdates files';
        return $lines;
    }

    /**
     * Escape exclude paths
     * @param string $str
     * @return string
     */
    private function escapeExcludes(string $str) : string
    {
        $return = trim(escapeshellarg(trim($str)), "'");

        if (str_starts_with($return, $this->base)) {
            return $return;
        }

        return $this->base . $return;
    }

}
