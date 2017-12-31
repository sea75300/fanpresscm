<?php
    /**
     * FanPress CM Update Check Cronjob
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\crons;
    
    /**
     * Cronjob update check
     * 
     * @package fpcm\model\crons
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class updateCheck extends \fpcm\model\abstracts\cron {

        /**
         * Auszuführender Cron-Code
         */
        public function run() {

            $updater = new \fpcm\model\updater\system();
            
            $res = $updater->checkUpdates();
            $this->setReturnData($res);
            
            /* @var $config \fpcm\model\system\config */
            $config = \fpcm\classes\baseconfig::$fpcmConfig;
            
            if (!$res && $this->getAsyncCurrent() && $config->system_updates_emailnotify) {
                
                $replacements = array(
                    '{{version}}' => $updater->getRemoteData('version'),
                    '{{acplink}}' => \fpcm\classes\baseconfig::$rootPath
                );
                
                $language = \fpcm\classes\baseconfig::$fpcmLanguage;
                $email = new \fpcm\classes\email($config->system_email,
                                                 $language->translate('CRONJOB_UPDATES_NEWVERSION'),
                                                 $language->translate('CRONJOB_UPDATES_NEWVERSION_TEXT', $replacements));                
                $email->submit();
            }            
            $this->updateLastExecTime();
            
            return true;
        }
        
    }
