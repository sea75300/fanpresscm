<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\crons;
    
    /**
     * FanPress CM remove old article revisions Cronjob
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\crons
     * @since FPCM 3.4
     */    
    class removeRevisions extends \fpcm\model\abstracts\cron {

        /**
         * Auszuführender Cron-Code
         */
        public function run() {
            
            $limit = \fpcm\classes\baseconfig::$fpcmConfig->articles_revisions_limit;
            if (!$limit) {
                $this->updateLastExecTime();
                return true;
            }
            
            $limit = (time() - $limit);
            $this->dbcon->delete(\fpcm\classes\database::tableRevisions, 'revision_idx <= ?', array($limit));
            $this->updateLastExecTime();

            return true;
        }
        
    }
