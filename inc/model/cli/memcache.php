<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli memcache module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
final class memcache extends \fpcm\model\abstracts\cli {

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {
        if (FPCM_CACHE_BACKEND !== '\\fpcm\\model\\cache\\memcacheBackend') {
            $this->output('Cache backend is not \\fpcm\\model\\cache\\memcacheBackend');
            return true;            
        }

        $this->output(print_r((new \fpcm\model\cache\memcacheConnector())->getInstance()->getStats(null), true));
        return true;        
    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {

        $lines = [];
        $lines[] = '> Memcache:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php memcache';
        $lines[] = '';
        $lines[] = 'Displays memcached stats.';
        return $lines;
    }

}
