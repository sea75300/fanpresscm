<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits\articles;

/**
 * Article sources utils
 * 
 * @package fpcm\model\traits\articles
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.0-a1
 */
trait sourcesUtils {


    /**
     * Add sources string to auto-complete file option, max. 25 values saved
     * @param string $sources
     * @return bool
     * @since 4.1
     */
    static public function addSourcesAutocomplete(string $sources) : bool
    {
        if (!trim($sources)) {
            return true;
        }
        
        $sources = preg_split('/([,;]\ )/', $sources);
        if (!is_array($sources)) {
            $sources = [];
        }

        $fopt = new \fpcm\model\files\fileOption(self::SOURCES_AUTOCOMPLETE);
        $data = $fopt->read();
        if (!is_array($data)) {
            $data = [];
        }

        return $fopt->write(array_slice(array_unique(array_merge($data, $sources)), 0, FPCM_ARTICLES_SOURCES_AUTOCOMPLETE));
    }

    /**
     * Fetch sources strings from auto-complete file option
     * @return array
     * @since 4.1
     */
    static public function fetchSourcesAutocomplete() : array
    {
        $data = (new \fpcm\model\files\fileOption(self::SOURCES_AUTOCOMPLETE))->read();
        return is_array($data) ? $data : [];
    }

}
