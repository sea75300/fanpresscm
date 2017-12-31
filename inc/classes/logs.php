<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\classes;

    /**
     * Class to handle logs
     * 
     * @package fpcm\classes\logs
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @deprecated FPCM 3.6
     */ 
    final class logs {
        
        /**
         * Schreibt Daten in System-Log
         * @param string $data
         * @return boolean
         */
        public static function syslogWrite($data) {
            
            trigger_error(__FUNCTION__.' is deprecated as of FPCM 3.6, use fpcmLogSystem instread.');
            
            $data   = is_array($data) || is_object($data)
                    ? print_r($data, true)
                    : $data;

            if (file_put_contents(baseconfig::$logFiles['syslog'], json_encode(array('time' => date('Y-m-d H:i:s'),'text' => $data)).PHP_EOL, FILE_APPEND) === false) {
                trigger_error('Unable to write data to system log');
                return false;
        }
        
            return true;
        }
        
        /**
         * Schreibt Daten in SQL-Log
         * @param string $data
         * @return boolean
         */
        public static function sqllogWrite($data) {
            
            trigger_error(__FUNCTION__.' is deprecated as of FPCM 3.6, use fpcmLogSql instread.');
            
            $data   = is_array($data) || is_object($data)
                    ? print_r($data, true)
                    : $data;

            if (file_put_contents(baseconfig::$logFiles['dblog'], json_encode(array('time' => date('Y-m-d H:i:s'),'text' => $data)).PHP_EOL, FILE_APPEND) === false) {
                trigger_error('Unable to write data to db log');
                return false;
            }

            return false;
        }

        /**
         * Schreibt Daten in SQL-Log
         * @param string $packageName
         * @param array $data
         * @return boolean
         * @since FPCM 3.2.0
         */
        public static function pkglogWrite($packageName, array $data) {
            
            trigger_error(__FUNCTION__.' is deprecated as of FPCM 3.6, use fpcmLogPackages instread.');
            
            if (file_put_contents(baseconfig::$logFiles['pkglog'], json_encode(array('time' => date('Y-m-d H:i:s'), 'pkgname' => $packageName, 'text' => $data)).PHP_EOL, FILE_APPEND) === false) {
                trigger_error('Unable to write data to db log');
                return false;
            }
        
            return false;
        }
        
        /**
         * Leer System-Log
         * @param int $log
         * * 1 => System-Log
         * * 2 => PHP Error Log
         * * 3 => Datenbank-Log
         * * 4 => Paket-Manager-Log
         * * default: Session-Log
         * @return boolean
         */
        public static function clearLog($log) {
            
            if ($log < 1) {
                return baseconfig::$fpcmSession->clearSessions();
            }
            
            $logfile = new \fpcm\model\files\logfile($log);
            return $logfile->clear();
            
        }
        
    }
?>