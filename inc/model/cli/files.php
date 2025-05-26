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
     * files.txt index check object
     * @var \fpcm\model\files\filesIndexCheck
     */
    private \fpcm\model\files\filesIndexCheck $obj;

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

        $this->obj = new \fpcm\model\files\filesIndexCheck(true);
        $this->base = $this->obj->getBasePath();

        if (count($this->funcParams) > 1) {
            $this->excludes = array_map([$this, 'escapeExcludes'], array_slice($this->funcParams, 1));
            $this->output(sprintf("Exclude from check:\n\n%s\n", implode(PHP_EOL, $this->excludes)));
            $this->obj->setExcludes($this->excludes);
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

        if (!$this->obj->exists()) {
            $this->output(sprintf('%s does not exists or is no readable', $filesPath), true);
        }

        try {
            $this->obj->prepareIndex();
            $this->obj->checkFiles();

        } catch (\Exception $exc) {
            $this->output($exc->getMessage(), true);
            return false;
        }

        if (!$out) {
            return true;
        }

        $files = $this->obj->getFiles();
        if (!count($files)) {
            $this->output("No outdated files found.", true);
        }

        $this->output(sprintf("Outdated found:\n\n- %s\n", implode(PHP_EOL . '- ', $files)));
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

        try {
            $this->obj->cleanup();
        } catch (\Exception $exc) {
            $this->output($exc->getMessage(), true);
            return false;
        }

        usleep(5000);
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
