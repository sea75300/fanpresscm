<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\cli;

    /**
     * FanPress CM cli cache module
     * 
     * @package fpcm\model\cli
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5.1
     */
    final class cache extends \fpcm\model\abstracts\cli {

        /**
         * Modul ausführen
         * @return void
         */
        public function process() {
            
            if (!isset($this->funcParams[1])) {
                $this->output('Invalid params detected, missing cache name or param "all"!');
                return true;
            }

            $cacheName   = $this->funcParams[1] === 'all' ? null : $this->funcParams[1];
            $cacheModule = !isset($this->funcParams[2]) || $this->funcParams[2] === 'all' ? '*' : $this->funcParams[2];
            $cacheName   = $cacheModule.'/'.$cacheName;

            if ($this->funcParams[0] === self::FPCMCLI_PARAM_CLEAN) {
                $this->cache->cleanup($cacheName);
                $this->output('Cache was cleared!');
                return true;
            }

            if ($this->funcParams[0] === self::FPCMCLI_PARAM_INFO) {
                $this->output('Cache expiration interval: '.date('Y-m-d H:i:s', $this->cache->getExpirationTime($cacheName)));
                $this->output('Cache is expired: '.(int) $this->cache->isExpired($cacheName));
                return true;
            }

            if ($this->funcParams[0] === self::FPCMCLI_PARAM_SIZE) {
                $this->output('Cache total size: '.\fpcm\classes\tools::calcSize($this->cache->getSize($cacheName)));
                return true;
            }

            if ($this->funcParams[0] === self::FPCMCLI_PARAM_LIST) {
                $this->output('Cache structur: ');
                $this->output($this->cache->getCacheComplete());
                return true;
            }

        }

        /**
         * Hilfe-Text zurückgeben ausführen
         * @return array
         */
        public function help() {

            $lines   = [];
            $lines[] = '> Cache:';
            $lines[] = '';
            $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php cache <action params> <internal cache name> <internal cache module>';
            $lines[] = '';
            $lines[] = '    Action params:';
            $lines[] = '';
            $lines[] = '      --clean       clean up cache';
            $lines[] = '      --size        returns total size of current cache content';
            $lines[] = '      --info        return information of cache expiration time';
            $lines[] = '      --list        list current cache file and folder structure';
            return $lines;
        }

    }
