<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub;


/**
 * Template manager file loader
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
class templates extends \fpcm\model\abstracts\staticModel {

    private string $basePath = '';

    public function __construct()
    {
        parent::__construct();
        $this->basePath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_STYLES);
    }

    private function getPattern() : string
    {
        return '/*/*.html' . ($this->config->system_twig ? '.twig' : '');
    }

    /**
     * Fetch template files from
     * @return array
     */
    public function get() : array
    {
        $files = $this->query();
        if (!$files || !count($files)) {
            return [];
        }


        $return = [];
        foreach ($files as $file) {
            try {
                $file = new file($file);
                $return[] = $file;
            } catch (\Exception $e) {
                trigger_error($e);
            }
        }

        return $return;
    }

    /**
     * Get filename by hash
     * @param string $hash
     * @return string
     */
    public function getByHash(string $hash) : string
    {
        $files = $this->query();

        $return = array_find($files, function ($path) use ($hash) {
            return hash_equals(
                \fpcm\classes\tools::getHash($path),
                $hash
            );
        });

        return $return ?? '';
    }

    /**
     * Query files
     * @return array
     */
    private function query() : array
    {
        $pattern = $this->getPattern();
        $files = glob($this->basePath . $pattern);
        if (!$files || !count($files)) {
            return [];
        }

        return $files;
    }
}
