<?php
    /**
     * FanPress CM temp file cleanup cronjob
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\crons;
    
    /**
     * Cronjob temp files cleanup
     * 
     * @package fpcm\model\crons
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class clearTemp extends \fpcm\model\abstracts\cron {

        /**
         * Auszuführender Cron-Code
         */
        public function run() {

            if (!\fpcm\classes\baseconfig::asyncCronjobsEnabled()) {
                return false;
            }

            if (!is_writable(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP))) {
                trigger_error('Unable to cleanup '.\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP).'! Access denied!');
                return false;
            }

            $tempFiles = glob(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, '*'));
            if (!is_array($tempFiles) || !count($tempFiles)) {
                fpcmLogCron('Nothing to do in '.\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP));
                return true;
            }

            foreach ($tempFiles as $tempFile) {
                
                if ($tempFile == \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, 'index.html')) continue;
                
                if (filectime($tempFile) + 3600 * 24 > time()) continue;
                
                if (is_dir($tempFile)) {
                    \fpcm\model\files\ops::deleteRecursive($tempFile);
                    continue;
                }
                unlink($tempFile);
            }

            fpcmLogCron('Temp files removed in '.\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP));
            
            return true;
        }
        
    }
